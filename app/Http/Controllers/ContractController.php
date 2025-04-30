<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Tenant;
use App\Models\Payment;
use App\Models\Contract;
use App\Models\Property;
use App\Mail\ContractEmail;
use Illuminate\Http\Request;
use App\Models\ImageProperty;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ContractController extends Controller
{
    public function agentcontract(){
        $contracts = Contract::where('agent_id', Auth::guard('staff')->user()->id)->get();
        return view('agent.AContract', compact('contracts'));
    }

    public function create(){
        $agent = Auth::guard('staff')->user()->id;
        $properties = Property::where('available', 1)->where('agent_id', $agent)->get();
        $tenants = Tenant::all();
        return view('agent.contract.create', compact('properties', 'tenants'));
    }

    public function store(Request $request){
        DB::beginTransaction();

        try {
            // Fetch the tenant based on the tenant ID
            $tenant = Tenant::find($request->input('tenantid'));

            // Determine the validation rules
            $rules = [
                'propertyid' => 'required',
                'tenantid' => 'required',
                'period' => 'required|integer|min:1',
                'start' => 'required|date|after_or_equal:today',
            ];

            // If the tenant already has a number_ic, allow the same number_ic, otherwise enforce uniqueness
            if ($tenant && $tenant->number_ic) {
                $rules['icnumber'] = 'required|in:' . $tenant->number_ic;
            } else {
                $rules['icnumber'] = 'required|unique:tenants,number_ic';
            }

            // Apply the validation rules
            $request->validate($rules);

            $property = Property::findOrFail($request->propertyid);
            $deposit = $property->deposit;
            $agentId = Auth::guard('staff')->user()->id;
            $tenantId = $request->tenantid;
            $period = $request->period;
            $monthly = $property->monthly;
            $balance = $monthly * $period;
            $startDate = Carbon::parse($request->start)->format('Y-m-d');
            $endDate = Carbon::parse($request->start)->addMonths($period)->format('Y-m-d');

            // Execute the stored procedure
            $result = DB::select('CALL InsertContract(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                $request->propertyid, null, $agentId, $tenantId, $period, 0, 0, $balance, 2, $startDate, $endDate
            ]);

            $contractId = $result[0]->contractId;

            $property->available = 2;
            $property->save();

            $tenant->role = 1;
            $tenant->number_ic = $request->icnumber;
            $tenant->save();

            $lateFee = 50; // Example late fee

            // Generate PDF
            $pdf = Pdf::loadView('pdf.contract', [
                'agent' => Auth::guard('staff')->user()->name,
                'tenant' => $tenant,
                'property' => $property,
                'period' => $period,
                'monthly' => $monthly,
                'balance' => $balance,
                'deposit' => $deposit,
                'lateFee' => $lateFee,
                'startDate' => $startDate,
                'endDate' => $endDate,
            ]);

            // Send the email with the PDF attached
            Mail::to($tenant->email)->send(new ContractEmail($tenant, $pdf, $property));

            DB::commit(); // Commit the transaction

            // Redirect back with a status message
            return back()->with('status', 'New Contract Submitted');

        } catch (\Exception $e) {
            DB::rollBack(); // Roll back the transaction on error
            return back()->withErrors(['error' => 'Failed to submit the contract: ' . $e->getMessage()]);
        }
    }




    public function aview(int $id){
        $contract = Contract::findOrFail($id);  // Fetches the contract or fails
        $pid = $contract->property_id;          // Property ID from contract
        $tid = $contract->tenant_id;            // Tenant ID from contract

        $property = Property::findOrFail($pid); // Fetches the property or fails
        $tenant = Tenant::findOrFail($tid);     // Fetches the tenant or fails

        // Fetch payments where 'contract_id' matches the contract's ID
        $payments = Payment::where('contract_id', $contract->id)->get();

        $images = ImageProperty::where('property_id', $pid)->get(); // Fetches images for the property

        return view('agent.contract.view', compact('contract', 'images', 'property', 'tenant', 'payments'));
    }



    public function edit(int $id) {
        // Fetch the contract details
        $contract = Contract::with('property')->findOrFail($id); // Ensure you're loading the property relation

        // Check if the contract has a valid property ID
        if (!$contract->property) {
            // Handle the case where the property is missing
            return back()->with('error', 'The contract does not have an associated property.');
        }

        // Get the agent's ID
        $agent = Auth::guard('staff')->user()->id;

        // Fetch available properties related to the agent that have status equal to 1
        $properties = Property::where('available', 1)
                              ->where('agent_id', $agent)
                              ->where('status', 1)
                              ->get();

        // Get the currently selected property ID and address
        $selectedPropertyId = old('propertyid', $contract->property->id);
        $selectedPropertyAddress = $contract->property->address;

        // Fetch tenants with a specific role
        $tenants = Tenant::all(); // Fetch all tenants without any role condition


        // Return the view with the fetched data
        return view('agent.contract.edit', [
            'properties' => $properties,
            'tenants' => $tenants,
            'contracts' => $contract,
            'selectedPropertyId' => $selectedPropertyId,
            'selectedPropertyAddress' => $selectedPropertyAddress
        ]);
    }



    public function update(Request $request, int $id) {
        $oc = Contract::findOrFail($id);
        $ot = $oc->tenant_id;

        $tenant = Tenant::findOrFail($ot);
        $otherActive = Contract::where('tenant_id', $ot)
                               ->where('id', '!=', $id)
                               ->where('status', '<=', 4) // assuming statuses 1-4 are active
                               ->exists();

        $icnumberRule = 'required';
        if (!$otherActive) { // Only apply unique constraint if no other active contracts
            $icnumberRule .= '|unique:tenants,number_ic,' . $ot;
        }

        // Validate the request data
        $request->validate([
            'propertyid' => 'required',
            'tenantid' => 'required',
            'period' => 'required|integer|min:1',
            'start' => 'required|date|after_or_equal:today',
            'icnumber' => $icnumberRule,
        ]);

        // Fetch the property details
        $property = Property::findOrFail($request->propertyid);
        $agentId = Auth::guard('staff')->user()->id;
        $tenantId = $request->tenantid;
        $period = $request->period;
        $balance = $property->monthly * $period;
        $startDate = Carbon::parse($request->start)->format('Y-m-d');
        $endDate = Carbon::parse($request->start)->addMonths($period)->format('Y-m-d');
        $icNumber = $request->icnumber;

        // Execute the stored procedure to update the contract and handle related updates
        DB::select('CALL UpdateContractDetails(?, ?, ?, ?, ?, ?, ?, ?, ?)', [
            $id, $request->propertyid, $agentId, $tenantId, $period, $balance, $startDate, $endDate, $icNumber
        ]);

        // Redirect back with a status message
        return back()->with('status', 'Contract Updated');
    }





    // public function destroy(int $id){
    //     $oldContract = Contract::findOrFail($id);
    //     $oldProperty = Property::findOrFail($oldContract->property_id);
    //     $oldTenant = Tenant::findOrFail($oldContract->tenant_id);

    //     // Mark the old property as available
    //     $oldProperty->available = 1;
    //     $oldProperty->save();

    //     // Update the old tenant's IC number if necessary
    //     $oldTenant->number_ic = null;
    //     $oldTenant->role = 2;
    //     $oldTenant->save();

    //     $oldContract->delete();
    //     return back()->with('status', 'Contract Deleted');
    // }

    public function destroy(int $id){
        // Execute the stored procedure to delete the contract and handle related updates
        DB::select('CALL DeleteContract(?)', [$id]);

        return back()->with('status', 'Contract Deleted');
    }

    // TenantController.php
    public function getTenantInfo($id)
    {
        $tenant = Tenant::find($id);

        return response()->json([
            'icnumber' => $tenant->number_ic ?? null,
        ]);
    }



    // public function destroy(int $id){
    //     // Find the old contract and related property and tenant
    //     $oldContract = Contract::findOrFail($id);
    //     $oldProperty = Property::findOrFail($oldContract->property_id);
    //     $oldTenant = Tenant::findOrFail($oldContract->tenant_id);

    //     // Mark the old property as available
    //     $oldProperty->available = 1;
    //     $oldProperty->save();

    //     // Update the old tenant's IC number if necessary
    //     $oldTenant->number_ic = null;
    //     $oldTenant->role = 2;
    //     $oldTenant->save();

    //     // Execute the stored procedure to delete the contract
    //     DB::select('CALL DeleteContract(?)', [$id]);

    //     return back()->with('status', 'Contract Deleted');
    // }

    //staff

    // public function staffcontract(){
    //     // Fetch all contracts from the Contract model
    //     $contracts = Contract::all(); // This retrieves all contracts from the database

    //     // Return the view with the contracts data
    //     return view('staff.contract.SContract', compact('contracts'));
    // }

    public function staffcontract(){
        // Fetch all contracts from the Contract model where status is not equal to 5
        $contracts = Contract::where('status', '!=', 5)->get(); // This retrieves all contracts with status not equal to 5

        // Return the view with the contracts data
        return view('staff.contract.SContract', compact('contracts'));
    }


    public function sview(int $id){
        $contract = Contract::findOrFail($id);  // Fetches the contract or fails
        $pid = $contract->property_id;          // Property ID from contract
        $tid = $contract->tenant_id;            // Tenant ID from contract

        $property = Property::findOrFail($pid); // Fetches the property or fails
        $tenant = Tenant::findOrFail($tid);     // Fetches the tenant or fails

        // Fetch payments where 'contract_id' matches the contract's ID
        $payments = Payment::where('contract_id', $contract->id)->get();

        $images = ImageProperty::where('property_id', $pid)->get(); // Fetches images for the property

        return view('staff.contract.view', compact('contract', 'images', 'property', 'tenant', 'payments'));
    }



    public function sedit(int $id) {
        // Fetch the contract details
        $contract = Contract::with('property')->findOrFail($id); // Ensure you're loading the property relation

        // Check if the contract has a valid property ID
        if (!$contract->property) {
            // Handle the case where the property is missing
            return back()->with('error', 'The contract does not have an associated property.');
        }

        // Get the agent's ID
        $agent = Auth::guard('staff')->user()->id;

        // Fetch available properties related to the agent that have status equal to 1
        $properties = Property::where('available', 1)
                              ->where('agent_id', $agent)
                              ->where('status', 1)
                              ->get();

        // Get the currently selected property ID and address
        $selectedPropertyId = old('propertyid', $contract->property->id);
        $selectedPropertyAddress = $contract->property->address;

        // Fetch tenants with a specific role
        $tenants = Tenant::all(); // Fetch all tenants without any role condition


        // Return the view with the fetched data
        return view('staff.contract.edit-contract', [
            'properties' => $properties,
            'tenants' => $tenants,
            'contracts' => $contract,
            'selectedPropertyId' => $selectedPropertyId,
            'selectedPropertyAddress' => $selectedPropertyAddress
        ]);
    }


    public function supdate(Request $request, int $id)
    {
        $oc = Contract::findOrFail($id);
        $ot = $oc->tenant_id;

        $tenant = Tenant::findOrFail($ot);
        $otherActive = Contract::where('tenant_id', $ot)
                               ->where('id', '!=', $id)
                               ->where('status', '<=', 4) // assuming statuses 1-4 are active
                               ->exists();

        $icnumberRule = 'required';
        if (!$otherActive) { // Only apply unique constraint if no other active contracts
            $icnumberRule .= '|unique:tenants,number_ic,' . $ot;
        }

        // Validate the request data
        $request->validate([
            'propertyid' => 'required',
            'tenantid' => 'required',
            'period' => 'required|integer|min:1',
            'start' => 'required|date|after_or_equal:today',
            'icnumber' => $icnumberRule,
        ]);

        // Fetch the property by ID
        $property = Property::findOrFail($request->propertyid);

        // Calculate balance
        $balance = $property->monthly * $request->period;

        // Format dates
        $formattedDate = Carbon::parse($request->start)->format('Y-m-d');
        $formattedFutureDate = Carbon::parse($request->start)->addMonths($request->period)->format('Y-m-d');

        // Find the old contract and related property and tenant
        $oldContract = Contract::findOrFail($id);
        $oldAgent = $oldContract->agent_id;
        $oldProperty = Property::findOrFail($oldContract->property_id);
        $oldTenant = Tenant::findOrFail($oldContract->tenant_id);

        // Mark the old property as available
        $oldProperty->available = 1;
        $oldProperty->save();

        // Check if the old tenant has other active contracts
        $activeContractsCount = Contract::where('tenant_id', $oldTenant->id)
            ->whereIn('status', [1, 2, 3, 4])
            ->where('id', '!=', $id) // Exclude the current contract being updated
            ->count();

        // Only update the old tenant's IC number and role if they have no other active contracts
        if ($activeContractsCount === 0) {
            $oldTenant->number_ic = null;
            $oldTenant->role = 2;
            $oldTenant->save();
        }

        // Determine the status based on the value of $request->stat
        if ($request->stat == 3) {
            $oldContract->status = 3; // Terminated
            $oldProperty->available = 1;
            $property->available = 1;
            $property->save();
            $oldContract->save();
            $oldProperty->save();
        } else if ($request->stat == 4) {
            $oldContract->status = 4; // Voided
            $oldProperty->available = 1;
            $property->available = 1;
            $property->save();
            $oldContract->save();
            $oldProperty->save();
        }else{
            // Update the contract with new details
            $oldContract->update([
                'property_id' => $request->propertyid,
                'agent_id' => $oldAgent,
                'tenant_id' => $request->tenantid,
                'deposit' => $oldContract->deposit,
                'period' => $request->period,
                'total' => $oldContract->total,
                'balance' => $oldContract->balance,
                'start_date' => $formattedDate,
                'end_date' => $formattedFutureDate,
            ]);
        }



        // Mark the new property as unavailable
        $property->available = 2;
        $property->save();

        // Update the new tenant's role and IC number
        $tenant = Tenant::findOrFail($request->tenantid);
        $tenant->role = 1;
        $tenant->number_ic = $request->icnumber;
        $tenant->save();

        // Redirect back with a status message
        return back()->with('status', 'Contract Updated');
    }


    // public function sdestroy(int $id){
    //     // Execute the stored procedure to delete the contract and handle related updates
    //     DB::select('CALL DeleteContract(?)', [$id]);

    //     return back()->with('status', 'Contract Deleted');
    // }

    public function sdestroy(int $contractId)
{
    // Start transaction to ensure data integrity
    DB::transaction(function () use ($contractId) {
        // Check for existing payments associated with the contract
        $paymentExists = DB::table('payments')->where('contract_id', $contractId)->exists();

        if ($paymentExists) {
            // Update contract status to 5 if payments are found
            DB::table('contracts')->where('id', $contractId)->update(['status' => 5]);
        } else {
            // Delete the contract if no payments are found
            DB::table('contracts')->where('id', $contractId)->delete();
        }
    });

    // Redirect or respond back with success or error message
    return redirect()->back()->with('status', 'Operation completed successfully.');
}


}
