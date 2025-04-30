<?php

namespace App\Http\Controllers;


use Carbon\Carbon;
use App\Models\Landlord;
use App\Models\Property;
use Illuminate\View\View;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Auth\Events\Registered;
use App\Providers\RouteServiceProvider;
use Illuminate\Validation\Rules\Password;


class LandlordController extends Controller
{
    //Register
    public function register(){
        return view('landlord/LandlordRegister');
    }

    public function LandlordRegister(Request $request){

        $path = '';
        $filename = '';

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:landlords,email'  // Ensure the email is unique in the landlords table
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
                'unique:landlords,number_ic'  // Ensure the IC number is unique and corresponds to the correct database column
            ],
            'phone' => [
                'required',
                'regex:/^\d{10,11}$/',
                'unique:landlords,phone'  // Ensure the phone number is unique
            ],
        ]);

        if($request->has('image')){
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();

            $path = 'uploads/avatar/landlord/';

            $filename = time().'.'.$extension;
            $file->move($path, $filename);
        }

        Landlord::create([
            'name' => $request->name,
            'email' => $request->email,
            'number_ic' => $request->ic,
            'phone' => $request->phone,
            'avatar' => $path.$filename,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('view_landlord')->with('status','Account registered successfully');
    }



    public function dashboard(){
        return view('landlord.LDashboard');
    }


    //staff

    public function vLandlord(){
        $landlords = Landlord::all();


        $landlords = $landlords->map(function ($landlord) {
            $landlord->assigned_properties_count = Property::where('landlord_id', $landlord->id)
                                                        ->where('status', '!=', 5)
                                                        ->count();
            return $landlord;
        });

        return view('staff.user.landlord.vLandlord', compact ('landlords'));
    }

    public function vdLandlord(int $id){
        $landlord = Landlord::findOrFail($id);

        // Count the properties assigned to this landlord
        $landlord->assigned_properties_count = Property::where('landlord_id', $landlord->id)
                                                    ->where('status', '!=', 5)
                                                    ->count();


        $properties = Property::where('landlord_id', $landlord->id)->get();

        return view('staff.user.landlord.view', compact('landlord', 'properties'));
    }

    public function vpLandlord(int $id){
        $landlord = Landlord::findOrFail($id);

        return view('staff.user.landlord.edit', compact('landlord'));
    }

    public function slandlord_update_avatar(Request $request, int $id){

        $path = '';
        $filename = '';

        $landlord = Landlord::findOrFail($id);

        $request->validate([
            'avatar' => 'required|mimes:png,jpg,jpeg,webp',
        ]);

        if($request->has('avatar')){
            $file = $request->file('avatar');
            $extension = $file->getClientOriginalExtension();

            $path = 'uploads/avatar/landlord/';

            $filename = time().'.'.$extension;
            $file->move($path, $filename);

            $oldavatar = $landlord->avatar;
            if(File::exists($oldavatar)){
                File::delete($oldavatar);
            }


        }

        Landlord::findOrFail($id)->update([
            'avatar' => $path.$filename,
        ]);

        return back()->with('status','Profile Updated');
    }

    public function slandlord_update_profile(Request $request, int $id){
        $landlord = Landlord::findOrFail($id); // Retrieve the staff member

        $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
                'unique:landlords,email,' . $id // Exclude the current record from the unique check
            ],
            'icnumber' => [
                'nullable',
                'numeric',
                'digits:12',
                'unique:landlords,number_ic,' . $id // Exclude the current record from the unique check
            ],
            'phone' => [
                'nullable',
                'regex:/^\d{10,11}$/',
                'unique:landlords,phone,' . $id // Exclude the current record from the unique check
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
        $landlord->update($updateData);

        return back()->with('status', 'Profile Updated Successfully');
    }

    public function slandlord_update_password(Request $request, int $id){
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

        $user = Landlord::findOrFail($id);  // Assuming the model is Landlord and you're updating a landlord

        // Update the password directly
        $user->password = Hash::make($request->newpassword);
        $user->save();

        return back()->with('status', 'Password updated successfully.');
    }

    public function online(int $id){
        $landlord = Landlord::findOrFail($id);
        $landlord->status = 1;
        $landlord->staff_id = Auth::guard('staff')->user()->id;
        $landlord->save();
        return back()->with('status','Landlord set to Online');
    }

    public function offline(int $id){
        $landlord = Landlord::findOrFail($id);
        $landlord->status = 2;
        $landlord->staff_id = Auth::guard('staff')->user()->id;
        $landlord->save();
        return back()->with('status','Landlord set to Offline');
    }



    public function profile(){
        return view('landlord.LProfile');
    }


    public function updateprofile(Request $request, int $id){

        $landlord = Landlord::findOrFail($id); // Retrieve the staff member

        $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'phone' => [
                'nullable',
                'regex:/^\d{10,11}$/',
                'unique:landlords,phone,' . $id // Exclude the current record from the unique check
            ],
        ]);

        $updateData = [];
        if ($request->filled('name')) {
            $updateData['name'] = $request->name;
        }
        if ($request->filled('phone')) {
            $updateData['phone'] = $request->phone;
        }

        // Update the staff member
        $landlord->update($updateData);

        return back()->with('status','Profile Updated');
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

            $path = 'uploads/avatar/landlord/';

            $filename = time().'.'.$extension;
            $file->move($path, $filename);

            $oldavatar = Auth::guard('landlord')->user()->avatar;
            if(File::exists($oldavatar)){
                File::delete($oldavatar);
            }
        }

        Landlord::findOrFail($id)->update([
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

        $user = Landlord::findOrFail($id);  // Assuming the model is Staff and you're updating a staff member

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
        return view('landlord/LandlordLogin');
    }



    public function login_submit(Request $request){

        $request ->validate([
                'email' =>'required|email',
                'password'=>'required',

        ]);

        $credentials = $request ->only('email','password');

        if(Auth::guard('landlord')->attempt($credentials)){

            $user = Landlord::where('email',$request->input('email'))->first();
            Auth::guard('landlord')->login($user);
            return redirect()->route('landlord_dashboard')->with('success','Login Successful');
        }else{
            return redirect()->route('landlord_login')->with('error','Login unsuccessful');
        }
    }

    public function logout(){

        Auth::guard('landlord')->logout();
        return redirect()->route('landlord_login')->with('Success','Logout successfully');
    }
}
