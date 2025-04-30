<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Staff;
use App\Models\Report;
use App\Models\Tenant;
use App\Models\Payment;
use App\Models\Contract;
use App\Models\Property;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ImageProperty;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Illuminate\Validation\Rules\Password;

class TenantController extends Controller
{

    public function dashboard() {
        $tenantId = Auth::guard('tenant')->user()->id;

        // Fetch all contracts for the tenant where status is not 3 or 4
        $contracts = Contract::where('tenant_id', $tenantId)
                             ->whereNotIn('status', [3, 4])
                             ->get();

        // Fetch the latest 2 reports for the tenant
        $reports = Report::where('tenant_id', $tenantId)
                         ->orderBy('created_at', 'desc')
                         ->take(2)
                         ->get();

        return view('tenant/TDashboard', compact('contracts', 'reports'));
    }




    // public function contract() {
    //     $tenant = Tenant::findOrFail(Auth::guard('tenant')->user()->id);

    //     // Fetch the first contract where the status is not 3 or 4
    //     $contract = Contract::where('tenant_id', $tenant->id)
    //                         ->whereNotIn('status', [3, 4])
    //                         ->firstOrFail();

    //     $property = Property::findOrFail($contract->property_id);
    //     $agent = Staff::findOrFail($contract->agent_id);
    //     $images = ImageProperty::where('property_id', $property->id)->get();

    //     return view('tenant.TContract', compact('tenant', 'contract', 'property', 'images', 'agent'));
    // }

    public function contract() {
        $tenant = Tenant::findOrFail(Auth::guard('tenant')->user()->id);

        // Fetch all contracts for the tenant ordered by creation date (latest first)
        $contracts = Contract::where('tenant_id', $tenant->id)
                             ->orderBy('created_at', 'desc') // Order by latest first
                             ->get();

        // Prepare arrays to hold properties and agents for each contract
        $properties = [];
        $agents = [];
        $images = [];

        foreach ($contracts as $contract) {
            $properties[$contract->id] = Property::findOrFail($contract->property_id);
            $agents[$contract->id] = Staff::findOrFail($contract->agent_id);
            $images[$contract->id] = ImageProperty::where('property_id', $properties[$contract->id]->id)->get();
        }

        // Passing multiple contracts and related details to the view
        return view('tenant.TContract', compact('tenant', 'contracts', 'properties', 'images', 'agents'));
    }



    public function vprofile(){
        return view('tenant.TProfile');
    }

    public function updateprofile(Request $request, int $id){

        $tenant = Tenant::findOrFail($id); // Retrieve the staff member

        $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'phone' => [
                'nullable',
                'regex:/^\d{10,11}$/',
                'unique:tenants,phone,' . $id // Exclude the current record from the unique check
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
        $tenant->update($updateData);

        return back()->with('status', 'Profile Updated Successfully');
    }

    public function updateavatar(Request $request, int $id){

        $path = '';
        $filename = '';

        $request->validate([
            'avatar' => 'required|mimes:png,jpg,jpeg,webp',
        ]);

        if($request->has('avatar')){
            $file = $request->file('avatar');
            $extension = $file->getClientOriginalExtension();

            $path = 'uploads/avatar/tenant/';

            $filename = time().'.'.$extension;
            $file->move($path, $filename);

            $oldavatar = Auth::guard('tenant')->user()->avatar;
            if(File::exists($oldavatar)){
                File::delete($oldavatar);
            }


        }

        Tenant::findOrFail($id)->update([
            'avatar' => $path.$filename,
        ]);

        return back()->with('status','Profile Updated');
    }

    public function updatepassword(Request $request, int $id){
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

        $user = Tenant::findOrFail($id);  // Assuming the model is Staff and you're updating a staff member

        // Check the old password
        if (!Hash::check($request->oldpassword, $user->password)) {
            return back()->withErrors(['oldpassword' => 'The provided password does not match your current password.']);
        }

        // Update the password
        $user->password = Hash::make($request->newpassword);
        $user->save();

        return back()->with('status', 'Password updated successfully.');
    }





    //staff

    public function vTenant(){
        // Fetch all tenants
        $tenants = Tenant::where('role', 1)->get();

        // Fetch contracts for each tenant
        foreach ($tenants as $tenant) {
            // Fetch the latest contract for each tenant that is not in status 3 (Terminated) or 4 (another state, e.g., Cancelled)
            $activeContract = $tenant->contracts()->whereNotIn('status', [3, 4])->latest()->first();
            $tenant->contract_status = $activeContract ? $activeContract->status : null; // Assign status or null if no applicable contract exists
        }

        return view('staff.user.tenant.vTenant', compact('tenants'));
    }


    public function vdTenant(int $id) {
        // Fetch the tenant along with its contracts, ordered by creation date or any suitable date field.
        $tenant = Tenant::with([
            'contracts' => function($query) {
                $query->orderBy('created_at', 'desc') // Order contracts by creation date, latest first
                      ->with('property'); // Eager load each contract's associated property
            },
            'contracts.payments' // Eager load payments related to each contract
        ])->findOrFail($id);

        // The contracts and their related payments and properties are now included within $tenant
        return view('staff.user.tenant.view', compact('tenant'));
    }


    public function vpTenant(int $id){
        $tenant = Tenant::findOrFail($id);

        return view('staff.user.tenant.edit', compact('tenant'));
    }

    public function stenant_update_avatar(Request $request, int $id){

        $path = '';
        $filename = '';

        $tenant = Tenant::findOrFail($id);

        $request->validate([
            'avatar' => 'required|mimes:png,jpg,jpeg,webp',
        ]);

        if($request->has('avatar')){
            $file = $request->file('avatar');
            $extension = $file->getClientOriginalExtension();

            $path = 'uploads/avatar/tenant/';

            $filename = time().'.'.$extension;
            $file->move($path, $filename);

            $oldavatar = $tenant->avatar;
            if(File::exists($oldavatar)){
                File::delete($oldavatar);
            }


        }

        Tenant::findOrFail($id)->update([
            'avatar' => $path.$filename,
        ]);

        return back()->with('status','Profile Updated');
    }

    public function stenant_update_profile(Request $request, int $id){
        $tenant = Tenant::findOrFail($id); // Retrieve the staff member

        $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
                'unique:tenants,email,' . $id // Exclude the current record from the unique check
            ],
            'icnumber' => [
                'nullable',
                'numeric',
                'digits:12',
                'unique:tenants,number_ic,' . $id // Exclude the current record from the unique check
            ],
            'phone' => [
                'nullable',
                'regex:/^\d{10,11}$/',
                'unique:tenants,phone,' . $id // Exclude the current record from the unique check
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
        $tenant->update($updateData);

        return back()->with('status', 'Profile Updated Successfully');
    }

    public function stenant_update_password(Request $request, int $id){
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

        $user = Tenant::findOrFail($id);  // Assuming the model is Landlord and you're updating a landlord

        // Update the password directly
        $user->password = Hash::make($request->newpassword);
        $user->save();

        return back()->with('status', 'Password updated successfully.');
    }

    public function online(int $id){
        $tenant = Tenant::findOrFail($id);
        $tenant->status = 1;
        $tenant->staff_id = Auth::guard('staff')->user()->id;
        $tenant->save();
        return back()->with('status','Tenant set to Online');
    }

    public function offline(int $id){
        $tenant = Tenant::findOrFail($id);
        $tenant->status = 2;
        $tenant->staff_id = Auth::guard('staff')->user()->id;
        $tenant->save();
        return back()->with('status','Tenant set to Offline');
    }





    //Agent
    public function avTenant(){
        // Fetch tenants based on their contracts associated with the current agent
        $tenants = Tenant::join('contracts', 'tenants.id', '=', 'contracts.tenant_id')
            ->where('tenants.role', 1)
            ->where('contracts.agent_id', Auth::guard('staff')->user()->id)
            ->where('contracts.status', '!=', 4)
            ->select('tenants.*')
            ->distinct()
            ->get();

        // Fetch contracts for each tenant
        $contracts = [];
        foreach ($tenants as $tenant) {
            $contracts[$tenant->id] = Contract::where('tenant_id', $tenant->id)
                ->where('agent_id', Auth::guard('staff')->user()->id)
                ->where('status', '!=', 4)
                ->get();
        }

        return view('agent.tenant.ATenant', compact('tenants', 'contracts'));
    }

    public function viewTenant(int $id){
        $tenants = Tenant::findOrFail($id);
        // $contract = Contract::where('tenant_id', $tenant->id)->get(); // Add ->get() to retrieve the contract

        // $pid = $contract->property_id; // This will cause an error because $contract is a collection, not a single contract object

        // $property = Property::findOrFail($pid);

        return view('agent.tenant.view', compact('tenants'));
    }






    public function register(){
        return view('tenant/TenantRegister');
    }

    public function TenantRegister(Request $request){
        $path = '';
        $filename = '';

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.Tenant::class],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                        ->numbers()
                        ->symbols()
                        ->letters()
                        ->mixedCase()
            ],
            'image' => 'required|mimes:png,jpg,jpeg',
            'phone' => [
                'nullable',
                'regex:/^\d{10,11}$/',
                'unique:tenants,phone'  // Ensure the phone number is unique
            ],
        ]);

        if($request->has('image')){
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();

            $path = 'uploads/avatar/tenant/';

            $filename = time().'.'.$extension;
            $file->move($path, $filename);
        }

        Tenant::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'avatar' => $path.$filename,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('tenant_login')->with('error', 'Account registered successfully');

    }


    public function login(){
        return view('tenant/TenantLogin');
    }

    public function loginmodal(Request $request){
        $request ->validate([
            'email' =>'required|email',
            'password'=>'required',

        ]);

        $credentials = $request ->only('email','password');

        if(Auth::guard('tenant')->attempt($credentials)){

            $user = Tenant::where('email',$request->input('email'))->first();
            Auth::guard('tenant')->login($user);
            if(Auth::guard('tenant')->user()->role == 1){
                return redirect()->route('tenant_dashboard')->with('success','Login Successful');
            }else{
                return back()->with('success','Login Successful');
            }
            // return redirect()->route('tenant_dashboard')->with('success','Login Successful');
        }else{
            return redirect()->route('guest_login_modal')->with('error','Login unsuccessful');
        }
    }

    public function login_submit(Request $request){
        $request ->validate([
                'email' =>'required|email',
                'password'=>'required',

        ]);

        $credentials = $request ->only('email','password');

        if(Auth::guard('tenant')->attempt($credentials)){

            $user = Tenant::where('email',$request->input('email'))->first();
            Auth::guard('tenant')->login($user);
            if(Auth::guard('tenant')->user()->role == 1){
                return redirect()->route('tenant_dashboard')->with('success','Login Successful');
            }else{
                return redirect()->route('welcome')->with('success','Login Successful');
            }
        }else{
            return redirect()->route('tenant_login')->with('error','Login unsuccessful');
        }
    }

    public function logout(){

        Auth::guard('tenant')->logout();
        return redirect()->route('tenant_login')->with('Success','Logout successfully');
    }


    //Welcome
    public function index(){
        return view('index');
    }


    //Guest
    public function welcome(){
        return view('guest.GHome');
    }

    public function property(){
        return view('guest.GProperty');
    }
}
