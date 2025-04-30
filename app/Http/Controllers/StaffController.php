<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Staff;
use App\Models\Report;
use App\Models\Tenant;
use App\Models\Payment;
use App\Models\Contract;
use App\Models\Landlord;
use App\Models\Property;
use Illuminate\View\View;
use App\Models\Appointment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Illuminate\Validation\Rules\Password;

class StaffController extends Controller
{
    public function dashboard(Request $request){
        // Fetch data from the database
        $totalPayments = Payment::sum('total');
        $totalProperties = Property::where('status', '!=', 5)->count();
        $totalContracts = Contract::where('status', '!=', 4)->count();
        $totalStaffs = Staff::where('status', 1)->count();
        $totalLandlords = Landlord::where('status', 1)->count();
        $totalTenants = Contract::whereNotIn('status', [4, 5])
                        ->distinct('tenant_id')
                        ->count('tenant_id');


        // Get the selected year from the request, default to the current year if not provided
        $selectedYear = $request->input('year', Carbon::now()->year);

        // Fetch the income data for the selected year
        $incomeData = DB::table('payments')
            ->select(DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"), DB::raw('SUM(total) as total_income'))
            ->whereYear('created_at', $selectedYear)
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
            ->orderBy('month')
            ->get();

        // Generate a list of all months in the selected year
        $months = [];
        for ($month = 1; $month <= 12; $month++) {
            $months[] = Carbon::create($selectedYear, $month)->format('Y-m');
        }

        // Fill the data with zeros for missing months
        $incomeArray = [];
        foreach ($months as $month) {
            $incomeArray[$month] = 0;
        }

        foreach ($incomeData as $data) {
            $incomeArray[$data->month] = $data->total_income;
        }

        // Format labels to display month names
        $formattedLabels = [];
        foreach (array_keys($incomeArray) as $month) {
            $formattedLabels[] = Carbon::createFromFormat('Y-m', $month)->format('F');
        }

        $incomeValues = array_values($incomeArray);

        // Fetch the property data for the selected year
        $propertyCounts = DB::table('properties')
            ->select('type', DB::raw('count(*) as count'))
            ->whereYear('created_at', $selectedYear)
            ->groupBy('type')
            ->get();

        // Map type values to their respective property types
        $propertyTypes = [
            1 => 'Bungalow/Villa',
            2 => 'Semi-D',
            3 => 'Terrace',
            4 => 'Townhouse',
            5 => 'Flat/Apartment',
            6 => 'Condominium',
            7 => 'Penthouse',
            8 => 'Shop House'
        ];

        // Initialize the chart data with all types set to 0
        $chartLabels = array_values($propertyTypes);
        $chartData = array_fill(0, count($propertyTypes), 0);

        // Update chart data with actual counts from the database
        foreach ($propertyCounts as $propertyCount) {
            $index = array_search($propertyTypes[$propertyCount->type], $chartLabels);
            if ($index !== false) {
                $chartData[$index] = $propertyCount->count;
            }
        }

        // Fetch the counts of tenants with role = 1 for the selected year
        $tenantCount = DB::table('tenants')
            ->where('role', 1)
            ->whereYear('created_at', $selectedYear)
            ->count();

        // Fetch the counts of landlords for the selected year
        $landlordCount = DB::table('landlords')
            ->whereYear('created_at', $selectedYear)
            ->count();

        // Fetch the counts of contracts with status != 4 for the selected year
        $contractCount = DB::table('contracts')
            ->whereNotIn('status', [4, 5]) // Exclude statuses 4 and 5
            ->whereYear('created_at', $selectedYear) // Only count contracts created in the selected year
            ->count();


        // Fetch all available years from the payments, properties, tenants, landlords, and contracts tables
        $availableYears = DB::table('payments')
            ->select(DB::raw('YEAR(created_at) as year'))
            ->distinct()
            ->union(DB::table('properties')->select(DB::raw('YEAR(created_at) as year'))->distinct())
            ->union(DB::table('tenants')->select(DB::raw('YEAR(created_at) as year'))->distinct())
            ->union(DB::table('landlords')->select(DB::raw('YEAR(created_at) as year'))->distinct())
            ->union(DB::table('contracts')->select(DB::raw('YEAR(created_at) as year'))->distinct())
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        // Pass data to the view
        return view('staff.dashboard.SDashboard', compact(
            'totalPayments',
            'totalProperties',
            'totalContracts',
            'totalStaffs',
            'totalLandlords',
            'totalTenants',
            'formattedLabels',
            'incomeValues',
            'chartLabels',
            'chartData',
            'tenantCount',
            'landlordCount',
            'contractCount',
            'availableYears',
            'selectedYear'
        ));

    }



    //Agent
    public function agentdashboard(){
        $agentId = Auth::guard('staff')->user()->id; // Get the logged-in agent's ID

        $totalProperties = Property::where('agent_id', $agentId)->count();

        $totalContracts = Contract::where('agent_id', $agentId)
                                  ->where('status', '!=', 4)
                                  ->count();

        // Get the count of unique tenant IDs from the contracts table where agent_id is equal to $agentId
        $totalTenants = Contract::where('agent_id', $agentId)
                                ->where('status', '!=', 4)
                                ->distinct('tenant_id')
                                ->count('tenant_id');

        $totalReports = Report::where('agent_id', $agentId)->count();

        $reportsStatus2 = Report::where('agent_id', $agentId)
                                ->where('status', 2)
                                ->count();

        $appointments1 = Appointment::where('agent_id', Auth::guard('staff')->user()->id)
                                ->where('status', 2)
                                ->orderBy('id', 'desc')
                                ->get();

        return view('agent.dashboard.ADashboard', compact('totalProperties', 'totalContracts', 'totalTenants', 'totalReports', 'reportsStatus2', 'appointments1'));
    }



    public function agenttenant(){
        return view('agent.ATenant');
    }


    public function agentprofile(){
        return view('agent.AProfile');
    }


    public function agent_update_profile(Request $request, int $id){

        $staff = Staff::findOrFail($id); // Retrieve the staff member

        $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'phone' => [
                'nullable',
                'regex:/^\d{10,11}$/',
                'unique:staff,phone,' . $id // Exclude the current record from the unique check
            ],
        ]);

        // Only update fields if they are provided
        $updateData = [];
        if ($request->filled('name')) {
            $updateData['name'] = $request->name;
        }
        if ($request->filled('phone')) {
            $updateData['phone'] = $request->phone;
        }

        // Update the staff member
        $staff->update($updateData);

        return back()->with('status', 'Profile Updated Successfully');
    }

    public function agent_update_avatar(Request $request, int $id){

        $path = '';
        $filename = '';

        $request->validate([
            'avatar' => 'required|mimes:png,jpg,jpeg,webp',
        ]);

        if($request->has('avatar')){
            $file = $request->file('avatar');
            $extension = $file->getClientOriginalExtension();

            $path = 'uploads/avatar/staff/';

            $filename = time().'.'.$extension;
            $file->move($path, $filename);

            $oldavatar = Auth::guard('staff')->user()->avatar;
            if(File::exists($oldavatar)){
                File::delete($oldavatar);
            }


        }

        Staff::findOrFail($id)->update([
            'avatar' => $path.$filename,
        ]);

        return back()->with('status','Profile Updated');
    }

    public function agent_update_password(Request $request, int $id){

        $request->validate([
            'newpassword' => [
                'required',
                'confirmed',
                Password::min(8)  // Ensure the new password meets security standards
                        ->numbers()
                        ->symbols()
                        ->letters()
                        ->mixedCase()
            ],
        ]);

        $user = Staff::findOrFail($id);  // Assuming the model is Landlord and you're updating a landlord

        // Update the password directly
        $user->password = Hash::make($request->newpassword);
        $user->save();

        return back()->with('status', 'Password updated successfully.');
    }

    public function online(int $id){
        $agent = Staff::findOrFail($id);
        $agent->status = 1;
        // $agent->staff_id = Auth::guard('staff')->user()->id;
        $agent->save();
        return back()->with('status','agent set to Online');
    }

    public function offline(int $id){
        $agent = Staff::findOrFail($id);
        $agent->status = 2;
        // $agent->staff_id = Auth::guard('staff')->user()->id;
        $agent->save();
        return back()->with('status','Agent set to Offline');
    }




    //Register
    public function register(){
        return view('Staff/StaffRegister');
    }

    public function StaffRegister(Request $request){

        $path = '';
        $filename = '';

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:staff,email'  // Ensure the email is unique in the 'staff' table
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                        ->numbers()
                        ->symbols()
                        ->letters()
                        ->mixedCase()
            ],
            'image' => 'required|mimes:png,jpg,jpeg', // Assuming this corresponds to the 'avatar' field
            'ic' => [
                'required',
                'numeric',
                'digits:12',
                'unique:staff,number_ic'  // Ensure the IC number is unique in the 'staff' table
            ],
            'phone' => [
                'required',
                'regex:/^\d{10,11}$/',
                'unique:staff,phone'  // Ensure the phone number is unique in the 'staff' table
            ],
        ]);

        if($request->has('image')){
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();

            $path = 'uploads/avatar/staff/';

            $filename = time().'.'.$extension;
            $file->move($path, $filename);
        }

        Staff::create([
            'name' => $request->name,
            'email' => $request->email,
            'number_ic' => $request->ic,
            'phone' => $request->phone,
            'avatar' => $path.$filename,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('view_agent')->with('status','Account registered successfully');
    }

    public function profile(){
        return view('staff.SProfile');
    }

    public function setting(){
        return view('staff.SSetting');
    }



    public function vAgent(){
        $agents = Staff::where('role', 2)->get();

        $agents = $agents->map(function ($agent) {
            $agent->assigned_properties_count = Property::where('agent_id', $agent->id)
                                                        ->where('status', '!=', 5)
                                                        ->count();
            $agent->handled_contracts_count = Contract::where('agent_id', $agent->id)
                                                      ->where('status', '!=', 4)
                                                      ->count();
            return $agent;
        });
        return view('staff.user.agent.vAgent', compact('agents'));
    }

    public function vdAgent(int $id){
        $agent = Staff::findOrFail($id);

        // Count the properties assigned to this agent
        $agent->assigned_properties_count = Property::where('agent_id', $agent->id)
                                                    ->where('status', '!=', 5)
                                                    ->count();

        // Count the contracts handled by this agent where status is not 4
        $agent->handled_contracts_count = Contract::where('agent_id', $agent->id)
                                                  ->where('status', '!=', 4)
                                                  ->count();

        // Retrieve properties
        $properties = Property::where('agent_id', $agent->id)->get();

        // Retrieve and format appointment data
        $appointmentCounts = DB::table('appointments')
                               ->select('status', DB::raw('count(*) as total'))
                               ->where('agent_id', $id)
                               ->whereIn('status', [1, 2, 3])
                               ->groupBy('status')
                               ->pluck('total', 'status')
                               ->all();

        $appointmentData = [
            'status_1' => $appointmentCounts[1] ?? 0,
            'status_2' => $appointmentCounts[2] ?? 0,
            'status_3' => $appointmentCounts[3] ?? 0,
        ];

        // Retrieve and format contract data for the pie chart
        $contractCounts = Contract::where('agent_id', $id)
                                  ->whereIn('status', [1, 2, 3, 4]) // Assuming 1 = Active, 2 = Pending, 3 = Terminated, 4 = Voided
                                  ->selectRaw('status, COUNT(*) as total')
                                  ->groupBy('status')
                                  ->pluck('total', 'status')
                                  ->all();

        $contractData = [
            'active' => $contractCounts[1] ?? 0,    // Active contracts
            'pending' => $contractCounts[2] ?? 0,   // Pending contracts
            'terminated' => $contractCounts[3] ?? 0, // Terminated contracts
            'voided' => $contractCounts[4] ?? 0     // Voided contracts
        ];

        return view('staff.user.agent.view', compact('agent', 'properties', 'appointmentData', 'contractData'));
    }


    public function vpAgent(int $id){
        $agent = Staff::findOrFail($id);

        return view('staff.user.agent.edit', compact('agent'));
    }

    public function sagent_update_avatar(Request $request, int $id){

        $path = '';
        $filename = '';

        $agent = Staff::findOrFail($id);

        $request->validate([
            'avatar' => 'required|mimes:png,jpg,jpeg,webp',
        ]);

        if($request->has('avatar')){
            $file = $request->file('avatar');
            $extension = $file->getClientOriginalExtension();

            $path = 'uploads/avatar/staff/';

            $filename = time().'.'.$extension;
            $file->move($path, $filename);

            $oldavatar = $agent->avatar;
            if(File::exists($oldavatar)){
                File::delete($oldavatar);
            }


        }

        Staff::findOrFail($id)->update([
            'avatar' => $path.$filename,
        ]);

        return back()->with('status','Profile Updated');
    }

    //piechart perormance
    // public function getContractStatusCount($agent_id) {
    //     $statusCounts = Contract::where('agent_id', $agent_id)
    //                             ->selectRaw('status, COUNT(*) as count')
    //                             ->groupBy('status')
    //                             ->pluck('count', 'status');

    //     return response()->json($statusCounts);
    // }



    public function sagent_update_profile(Request $request, int $id){
        $staff = Staff::findOrFail($id); // Retrieve the staff member

        $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
                'unique:staff,email,' . $id // Exclude the current record from the unique check
            ],
            'icnumber' => [
                'nullable',
                'numeric',
                'digits:12',
                'unique:staff,number_ic,' . $id // Exclude the current record from the unique check
            ],
            'phone' => [
                'nullable',
                'regex:/^\d{10,11}$/',
                'unique:staff,phone,' . $id // Exclude the current record from the unique check
            ],
        ]);

        // Only update fields if they are provided
        $updateData = [];
        if ($request->filled('name')) {
            $updateData['name'] = $request->name;
        }
        if ($request->filled('email')) {
            $updateData['email'] = $request->email;
        }
        if ($request->filled('icnumber')) {
            $updateData['number_ic'] = $request->icnumber;
        }
        if ($request->filled('phone')) {
            $updateData['phone'] = $request->phone;
        }

        // Update the staff member
        $staff->update($updateData);

        return back()->with('status', 'Profile Updated Successfully');
    }

    public function staff_update_profile(Request $request, int $id){
        $staff = Staff::findOrFail($id); // Retrieve the staff member

        $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
                'unique:staff,email,' . $id // Exclude the current record from the unique check
            ],
            'icnumber' => [
                'nullable',
                'numeric',
                'digits:12',
                'unique:staff,number_ic,' . $id // Exclude the current record from the unique check
            ],
            'phone' => [
                'nullable',
                'regex:/^\d{10,11}$/',
                'unique:staff,phone,' . $id // Exclude the current record from the unique check
            ],
        ]);

        // Only update fields if they are provided
        $updateData = [];
        if ($request->filled('name')) {
            $updateData['name'] = $request->name;
        }
        if ($request->filled('email')) {
            $updateData['email'] = $request->email;
        }
        if ($request->filled('icnumber')) {
            $updateData['number_ic'] = $request->icnumber;
        }
        if ($request->filled('phone')) {
            $updateData['phone'] = $request->phone;
        }

        // Update the staff member
        $staff->update($updateData);

        return back()->with('status', 'Profile Updated Successfully');
    }

    public function staff_update_password(Request $request, int $id){
        $request->validate([
            'newpassword' => [
                'required',
                'confirmed',
                Password::min(8)  // Ensure the new password meets security standards
                        ->numbers()
                        ->symbols()
                        ->letters()
                        ->mixedCase()
            ],
        ]);

        $user = Staff::findOrFail($id);  // Assuming the model is Landlord and you're updating a landlord

        // Update the password directly
        $user->password = Hash::make($request->newpassword);
        $user->save();

        return back()->with('status', 'Password updated successfully.');
    }

    public function staff_update_avatar(Request $request, int $id){

        $path = '';
        $filename = '';

        $agent = Staff::findOrFail($id);

        $request->validate([
            'avatar' => 'required|mimes:png,jpg,jpeg,webp',
        ]);

        if($request->has('avatar')){
            $file = $request->file('avatar');
            $extension = $file->getClientOriginalExtension();

            $path = 'uploads/avatar/staff/';

            $filename = time().'.'.$extension;
            $file->move($path, $filename);

            $oldavatar = $agent->avatar;
            if(File::exists($oldavatar)){
                File::delete($oldavatar);
            }


        }

        Staff::findOrFail($id)->update([
            'avatar' => $path.$filename,
        ]);

        return back()->with('status','Profile Updated');
    }



    public function sagent_update_password(Request $request, int $id){
        // Validate the new password
        $request->validate([
            'oldpassword' => 'required',
            'newpassword' => [
                'required',
                'confirmed',
                Password::min(8)  // Ensure the new password meets security standards
                        ->numbers()
                        ->symbols()
                        ->letters()
                        ->mixedCase()
            ],
        ]);

        $user = Staff::findOrFail($id);  // Assuming the model is Staff and you're updating a staff member

        // Check the old password
        if (!Hash::check($request->oldpassword, $user->password)) {
            return back()->withErrors(['oldpassword' => 'The provided password does not match your current password.']);
        }

        // Update the password
        $user->password = Hash::make($request->newpassword);
        $user->save();

        return back()->with('status', 'Password updated successfully.');
    }



    public function login(){
        return view('staff/StaffLogin');
    }



    public function login_submit(Request $request){
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::guard('staff')->attempt($credentials)) {
            $user = Staff::where('email', $request->input('email'))->first();
            Auth::guard('staff')->login($user);

            if (Auth::guard('staff')->user()->role == 1) {
                return redirect()->route('staff_dashboard')->with('success', 'Login Successful');
            } else {
                return redirect()->route('agent_dashboard')->with('success', 'Login Successful');
            }
        } else {
            return redirect()->route('staff_login')->with('error', 'Login unsuccessful');
        }
    }

    public function logout(){

        Auth::guard('staff')->logout();
        return redirect()->route('staff_login')->with('Success','Logout successfully');
    }

}
