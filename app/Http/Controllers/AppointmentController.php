<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Staff;
use App\Models\Tenant;
use App\Models\Property;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AppointmentConfirmation;

class AppointmentController extends Controller
{
    public function view(){
        $appointments = Appointment::where('agent_id', Auth::guard('staff')->user()->id)->get();

        return view('agent.appointment.AAppointment', compact('appointments'));
    }

    public function create(){
        $agent = Auth::guard('staff')->user()->id;
        $properties = Property::where('available', 1)->where('agent_id', $agent)->get();
        $tenants = Tenant::where('role', 2)->get();
        return view('agent.appointment.create', compact('properties', 'tenants'));
    }

    public function store(Request $request){

        $timeFormatted = '';
        // Validate the incoming request
        $request->validate([
            'propertyid' => 'required',
            'tenantid' => 'required',
            'date' => 'required|date|after:today',
            'time' => 'required|date_format:H:i',
        ]);

        $formattedDate = Carbon::parse($request->date)->format('Y-m-d');

        $time24HourFormat = $request->time . ':00'; // Add seconds part


        // Create the contract
        $appointment = Appointment::create([
            'property_id' => $request->propertyid,
            'agent_id' => Auth::guard('staff')->user()->id,
            'tenant_id' => $request->tenantid,
            'date' => $formattedDate,
            'time' => $time24HourFormat,
        ]);

        $tenant = Tenant::find($request->tenantid);

        // Save the appointment or any other logic

        // Send the email
        // Mail::to($tenant->email)->send(new AppointmentConfirmation($tenant, $request->date, $request->time));


        // Redirect back with a status message
        return back()->with('status', 'New Appointment Submitted');
    }

    public function reply(Request $request){
        $request->validate([
            'appointment_id' => 'required',
            'status' => 'required',
            'remark' => 'required',
        ]);



        $appointment = Appointment::findOrFail($request->appointment_id);

        $formattedDate = Carbon::parse($appointment->date)->format('d-m-Y');

        $time24HourFormat = $appointment->time; // Add seconds part

        $appointment->status = $request->status;
        $appointment->remark = $request->remark;
        $appointment->save();

        $tenant = Tenant::find($appointment->tenant_id);

        // Save the appointment or any other logic
        $agent = Staff::find(Auth::guard('staff')->user()->id);

        // Send the email
        Mail::to($tenant->email)->send(new AppointmentConfirmation($tenant, $formattedDate, $time24HourFormat, $agent));

        return back()->with('status','Appointment Update');
    }

    public function edit(int $id) {
        // Fetch the contract details
        $appointments = Appointment::findOrFail($id);



        // Get the agent's ID
        $agent = Auth::guard('staff')->user()->id;

        // Fetch available properties related to the agent
        $properties = Property::where('available', 1)
                              ->where('agent_id', $agent)
                              ->get();

        // Fetch tenants with a specific role
        $tenants = Tenant::where('role', 2)->get();

        // Return the view with the fetched data
        return view('agent.appointment.edit', [
            'properties' => $properties,
            'tenants' => $tenants,
            'appointments' => $appointments
        ]);
    }

    public function update(Request $request, int $id){
        $appointments = Appointment::findOrFail($id);

        $timeFormatted = '';
        // Validate the incoming request
        $request->validate([
            'propertyid' => 'required',
            'tenantid' => 'required',
            'date' => 'required|date',
            'time' => 'required|date_format:H:i',
        ]);

        $formattedDate = Carbon::parse($request->date)->format('Y-m-d');

        $time24HourFormat = $request->time . ':00'; // Add seconds part

        $appointments->update([
            'property_id' => $request->propertyid,
            'agent_id' => Auth::guard('staff')->user()->id,
            'tenant_id' => $request->tenantid,
            'date' => $formattedDate,
            'time' => $time24HourFormat,
        ]);

        return back()->with('status', 'Appointment Updated');


    }

    public function destroy(int $id){
        // Execute the stored procedure to delete the appointment
        DB::select('CALL DeleteAppointment(?)', [$id]);

        return back()->with('status', 'Appointment Deleted');
    }

    public function tdestroy(int $id){
        // Execute the stored procedure to delete the appointment
        DB::select('CALL DeleteAppointment(?)', [$id]);

        return back()->with('status', 'Appointment Deleted');
    }

    public function gdestroy(int $id){
        // Execute the stored procedure to delete the appointment
        DB::select('CALL DeleteAppointment(?)', [$id]);

        return back()->with('success', 'Appointment Successfully Deleted.');
    }


    public function gview(){
        $appointments = Appointment::where('tenant_id', Auth::guard('tenant')->user()->id)->get();
        return view('guest.appointment', compact('appointments'));
    }

    public function tview(){
        $appointments = Appointment::where('tenant_id', Auth::guard('tenant')->user()->id)->get();
        return view('tenant.TAppointment', compact('appointments'));
    }

    public function tstore(Request $request)
{
    // Validate the incoming request
    $request->validate([
        'propertyid' => 'required',
        'tenantid' => 'required',
        'date' => 'required|date|after:today',
        'time' => 'required|date_format:H:i',
    ]);

    $formattedDate = Carbon::parse($request->date)->format('Y-m-d');
    $time24HourFormat = $request->time . ':00'; // Add seconds part

    // Fetch the property and agent ID
    $property = Property::findOrFail($request->propertyid);
    $agentid = $property->agent_id;

    try {
        // Create the appointment
        $appointment = Appointment::create([
            'property_id' => $request->propertyid,
            'agent_id' => $agentid,
            'tenant_id' => $request->tenantid,
            'date' => $formattedDate,
            'time' => $time24HourFormat,
        ]);



        // Redirect back with a success status message
        return back()->with('success', 'New Appointment Successfully Submitted.');
    } catch (\Exception $e) {
        // In case of any exceptions, redirect back with an error message
        return back()->with('error', 'Failed to schedule the appointment: ' . $e->getMessage());
    }
}


}
