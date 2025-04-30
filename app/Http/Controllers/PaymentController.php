<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Tenant;
use App\Models\Payment;
use App\Models\Contract;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Models\ImageProperty;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function payment() {
        $tenantId = Auth::guard('tenant')->user()->id;

        // Fetch all contracts for the tenant ordered by creation date, latest first
        $contracts = Contract::where('tenant_id', $tenantId)
                              ->orderBy('created_at', 'desc') // Order by creation date descending
                              ->get();

        // Fetch payments for all the contracts and order them by payment date, latest first
        $payments = Payment::whereIn('contract_id', $contracts->pluck('id'))
                            ->orderBy('created_at', 'desc') // Assume payments have a 'created_at' date
                            ->get();

        // Group payments by contract ID for easy access in the view
        $paymentsGroupedByContract = $payments->groupBy('contract_id');

        $monthly = [];
        foreach ($contracts as $contract) {
            $property = Property::find($contract->property_id);
            $monthly[$contract->id] = $property ? $property->monthly : 0;
        }

        return view('tenant.payment.TPayment', compact('contracts', 'paymentsGroupedByContract', 'monthly'));
    }






    public function new(int $id) {
        // Fetch the authenticated tenant's details
        $tenant = Tenant::findOrFail(Auth::guard('tenant')->user()->id);

        // Fetch the specific contract by ID and ensure it belongs to the authenticated tenant
        $contract = Contract::where('tenant_id', $tenant->id)
                            ->where('id', $id) // Match the contract ID to the provided $id
                            ->whereNotIn('status', [3, 4]) // Exclude contracts with forbidden statuses
                            ->firstOrFail(); // Get the contract or fail

        // Fetch the property details associated with the contract
        $property = Property::findOrFail($contract->property_id);

        // Returning the view with the necessary data
        return view('tenant.payment.newpayment', compact('tenant', 'contract', 'property'));
    }


    public function store(Request $request){

    }
}
