<?php

use App\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StripeController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ContractController;
use App\Http\Controllers\LandlordController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ImagePropertyController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', '/home');

Route::get('/home', function () {
    return view('index');
})->name('home');


require __DIR__ . '/auth.php';



//Agent
Route::middleware(['auth:staff', 'agent'])->group(function () {

    //Profile
    Route::get('agent/profile', [StaffController::class, 'agentprofile']);
    Route::post('agent/profile/update/{id}', [StaffController::class, 'agent_update_profile']);
    Route::post('agent/profile/avatar/{id}', [StaffController::class, 'agent_update_avatar']);
    Route::post('agent/profile/password/{id}', [StaffController::class, 'agent_update_password']);


    //Property
    Route::get('agent/property', [PropertyController::class, 'aview'])->name('agent_property');
    Route::get('agent/property/view/{id}', [PropertyController::class, 'agentview']);

    //Tenant
    Route::get('agent/tenant', [TenantController::class, 'avTenant']);
    Route::get('agent/tenant/view/{id}', [TenantController::class, 'viewTenant']);

    //Report
    Route::get('agent/report', [ReportController::class, 'avReport']);
    Route::get('agent/report/reply/{id}', [ReportController::class, 'avView']);
    Route::post('agent/report/reply/{id}', [ReportController::class, 'avReply']);


    //Appointment
    Route::get('agent/appointment', [AppointmentController::class, 'view']);
    Route::get('agent/appointment/create', [AppointmentController::class, 'create']);
    Route::post('agent/appointment/create', [AppointmentController::class, 'store'])->name('appointment_store');
    Route::post('agent/appointment/reply', [AppointmentController::class, 'reply'])->name('appointment_reply');
    Route::get('agent/appointment/update/{id}', [AppointmentController::class, 'edit']);
    Route::post('agent/appointment/update/{id}', [AppointmentController::class, 'update']);
    Route::get('agent/appointment/delete/{id}', [AppointmentController::class, 'destroy']);



    //Manage Contract
    Route::get('agent/contract', [ContractController::class, 'agentcontract']);
    Route::get('agent/contract/create', [ContractController::class, 'create']);
    Route::post('agent/contract/create', [ContractController::class, 'store'])->name('contract_store');
    Route::get('agent/contract/view/{id}', [ContractController::class, 'aview']);
    Route::get('agent/contract/edit/{id}', [ContractController::class, 'edit']);
    Route::post('agent/contract/edit/{id}', [ContractController::class, 'update']);
    Route::get('agent/contract/delete/{id}', [ContractController::class, 'destroy']);

    // web.php (or routes file)
    Route::get('/get-tenant-info/{id}', [ContractController::class, 'getTenantInfo'])->name('get-tenant-info');



    Route::get('agent/dashboard', [StaffController::class, 'agentDashboard'])->name('agent_dashboard');
    // Other agent-specific routes
});



Route::middleware(['auth:staff', 'staff'])->group(function () {
    Route::get('staff/dashboard', [StaffController::class, 'dashboard'])->name('staff_dashboard');
    Route::get('/fetch-all-years-data', [StaffController::class, 'fetchAllYearsData']);

    Route::get('/admin/dashboard/data', [StaffController::class, 'fetchData']);




    Route::get('agent/dashboard', [StaffController::class, 'agentdashboard'])->name('agent_dashboard');

    Route::get('staff/profile', [StaffController::class, 'profile']);
    Route::get('staff/setting', [StaffController::class, 'setting']);



    //Available
    Route::get('staff/property/unavailable/{id}', [PropertyController::class, 'available']);
    Route::get('staff/property/available/{id}', [PropertyController::class, 'unavailable']);

    Route::post('update-agent', [PropertyController::class, 'updateAgent'])->name('update-agent');


    //Landlord-all
    Route::get('staff/user/landlord', [LandlordController::class, 'vLandlord'])->name('view_landlord');
    Route::get('staff/user/landlord/view/{id}', [LandlordController::class, 'vdLandlord']);
    Route::get('staff/user/landlord/edit/{id}', [LandlordController::class, 'vpLandlord']);
    Route::post('staff/profile/landlord/bio/{id}', [LandlordController::class, 'slandlord_update_profile']);
    Route::post('staff/profile/landlord/avatar/{id}', [LandlordController::class, 'slandlord_update_avatar']);
    Route::post('staff/profile/landlord/password/{id}', [LandlordController::class, 'slandlord_update_password']);

    Route::get('staff/landlord/online/{id}', [LandlordController::class, 'online']);
    Route::get('staff/landlord/offline/{id}', [LandlordController::class, 'offline']);


    //Tenant-all
    Route::get('staff/user/tenant', [TenantController::class, 'vTenant']);
    Route::get('staff/user/tenant/view/{id}', [TenantController::class, 'vdTenant']);
    Route::get('staff/user/tenant/edit/{id}', [TenantController::class, 'vpTenant']);
    Route::post('staff/profile/tenant/bio/{id}', [TenantController::class, 'stenant_update_profile']);
    Route::post('staff/profile/tenant/avatar/{id}', [TenantController::class, 'stenant_update_avatar']);
    Route::post('staff/profile/tenant/password/{id}', [TenantController::class, 'stenant_update_password']);

    Route::get('staff/tenant/online/{id}', [TenantController::class, 'online']);
    Route::get('staff/tenant/offline/{id}', [TenantController::class, 'offline']);

    Route::post('staff/profile/bio/{id}', [StaffController::class, 'staff_update_profile']);
    Route::post('staff/profile/password/{id}', [StaffController::class, 'staff_update_password']);
    Route::post('staff/profile/avatar/{id}', [StaffController::class, 'staff_update_avatar']);




    //Contract
    Route::get('staff/contract', [ContractController::class, 'staffcontract']);
    Route::get('staff/contract/view/{id}', [ContractController::class, 'sview']);
    Route::get('staff/contract/edit/{id}', [ContractController::class, 'sedit']);
    Route::post('staff/contract/edit/{id}', [ContractController::class, 'supdate']);
    Route::get('staff/contract/delete/{id}', [ContractController::class, 'sdestroy']);

    // web.php (or routes file)
    Route::get('/get-tenant-info/{id}', [ContractController::class, 'getTenantInfo'])->name('get-tenant-info');


    //Report
    Route::get('staff/report', [ReportController::class, 'svReport']);
    Route::get('staff/report/reply/{id}', [ReportController::class, 'svView']);
    Route::post('staff/report/reply/{id}', [ReportController::class, 'svReply']);


    //Property-all
    Route::get('staff/property', [PropertyController::class, 'sview'])->name('staff_property');
    Route::get('staff/property/view/{id}', [PropertyController::class, 'staffview']);
    Route::get('staff/property/edit/{id}', [PropertyController::class, 'staffedit']);
    Route::put('staff/property/edit/{id}', [PropertyController::class, 'staffupdate']);
    Route::get('staff/property/delete/{id}', [PropertyController::class, 'destroy']);

    //Property-approval/active/inactive
    Route::get('staff/property/approval/{id}', [PropertyController::class, 'approval']);
    Route::get('staff/property/inactive/{id}', [PropertyController::class, 'inactive']);
    Route::get('staff/property/active/{id}', [PropertyController::class, 'active']);

    //Property-available
    Route::get('staff/property/unavailable/{id}', [PropertyController::class, 'available']);
    Route::get('staff/property/available/{id}', [PropertyController::class, 'unavailable']);
    //Property-agent-assign
    Route::post('update-agent', [PropertyController::class, 'updateAgent'])->name('update-agent');

    //Agent-all
    Route::get('staff/user/agent', [StaffController::class, 'vAgent'])->name('view_agent');
    Route::get('staff/user/agent/view/{id}', [StaffController::class, 'vdAgent']);
    Route::get('/contracts/status/{agent_id}', [StaffController::class, 'getContractStatusCount']);

    Route::get('staff/user/agent/edit/{id}', [StaffController::class, 'vpAgent']);
    Route::post('staff/profile/agent/bio/{id}', [StaffController::class, 'sagent_update_profile']);
    Route::post('staff/profile/agent/avatar/{id}', [StaffController::class, 'sagent_update_avatar']);
    Route::post('staff/profile/agent/password/{id}', [StaffController::class, 'sagent_update_password']);

    Route::get('staff/agent/online/{id}', [StaffController::class, 'online']);
    Route::get('staff/agent/offline/{id}', [StaffController::class, 'offline']);

});

Route::prefix('staff')->group(function () {
    Route::get('/login', [StaffController::class, 'login'])->name('staff_login');
    Route::get('/logout', [StaffController::class, 'logout'])->name('staff_logout');
    Route::post('/login-submit', [StaffController::class, 'login_submit'])->name('staff_login_submit');

    //Register
    Route::get('/register', [StaffController::class, 'register'])->name('staff_register');
    Route::post('/staff/register-submit', [StaffController::class, 'StaffRegister'])->name('staff_register_submit');
});



//ImageProperty

//Landlord-property
Route::get('landlord/property/image/{id}', [ImagePropertyController::class, 'edit']);
Route::get('landlord/property/image/delete/{id}', [ImagePropertyController::class, 'destroy']);
Route::get('staff/property/image/delete/{id}', [ImagePropertyController::class, 'destroy']);







Route::get('property', [PropertyController::class, 'gproperty']);






Route::middleware('landlord')->group(function () {
    Route::get('landlord/dashboard', [LandlordController::class, 'dashboard'])->name('landlord_dashboard');
    Route::get('landlord/profile', [LandlordController::class, 'profile'])->name('landlord_profile');
    Route::post('landlord/profile/update/{id}', [LandlordController::class, 'updateprofile']);
    Route::post('landlord/profile/avatar/{id}', [LandlordController::class, 'updateavatar']);
    Route::post('landlord/profile/password/{id}', [LandlordController::class, 'updatepassword']);

    //Property
    Route::get('landlord/property', [PropertyController::class, 'lview'])->name('landlord_property');
    Route::get('landlord/property/create', [PropertyController::class, 'create'])->name('landlord-create-property');
    Route::post('landlord/property/create', [PropertyController::class, 'store'])->name('landlord-submit-property');
    Route::get('landlord/property/edit/{id}', [PropertyController::class, 'edit']);
    Route::put('landlord/property/edit/{id}', [PropertyController::class, 'update']);
    Route::get('landlord/property/delete/{id}', [PropertyController::class, 'destroy']);
    Route::get('landlord/property/view/{id}', [PropertyController::class, 'view']);
});

Route::prefix('landlord')->group(function () {
    Route::get('/login', [LandlordController::class, 'login'])->name('landlord_login');
    Route::get('/logout', [LandlordController::class, 'logout'])->name('landlord_logout');
    Route::post('/login-submit', [LandlordController::class, 'login_submit'])->name('landlord_login_submit');

    //Register
    Route::get('/register', [LandlordController::class, 'register'])->name('landlord_register');
    Route::post('/landlord/register-submit', [LandlordController::class, 'LandlordRegister'])->name('landlord_register_submit');
});


//Tenant
Route::middleware('tenant')->group(function () {
    Route::get('tenant/dashboard', [TenantController::class, 'dashboard'])->name('tenant_dashboard');

    Route::get('tenant/contract', [TenantController::class, 'contract'])->name('tenant_contract');

    //Payment
    Route::get('tenant/payment', [PaymentController::class, 'payment'])->name('tenant_payment');
    Route::get('tenant/payment/new/{id}', [PaymentController::class, 'new'])->name('new_payment');
    Route::post('tenant/payment/new/{id}', [PaymentController::class, 'store']);

    Route::post('mstripe', [StripeController::class, 'mstripe'])->name('mstripe');
    Route::get('msuccess', [StripeController::class, 'msuccess'])->name('msuccess');
    Route::get('mcancel', [StripeController::class, 'mcancel'])->name('mcancel');

    Route::post('stripe', [StripeController::class, 'stripe'])->name('stripe');
    Route::get('success', [StripeController::class, 'success'])->name('success');
    Route::get('cancel', [StripeController::class, 'cancel'])->name('cancel');

    //explore
    Route::get('tenant/explore', [PropertyController::class, 'exproperty'])->name('tenant_explore');

    //Appointment
    Route::get('tenant/appointment', [AppointmentController::class, 'tview'])->name('tappointment');
    Route::group(['middleware' => ['web']], function () {
        Route::post('tenant/appointment', [AppointmentController::class, 'tstore'])->name('tcappointment');
    });
    Route::get('tenant/appointment/delete/{id}', [AppointmentController::class, 'tdestroy']);



    //Report
    Route::get('tenant/report', [ReportController::class, 'index'])->name('tenant_report');
    Route::get('tenant/report/create', [ReportController::class, 'create']);
    Route::post('tenant/report/create', [ReportController::class, 'store']);
    Route::get('tenant/report/edit/{id}', [ReportController::class, 'edit']);
    Route::post('tenant/report/edit/{id}', [ReportController::class, 'update']);
    Route::get('tenant/report/delete/{id}', [ReportController::class, 'destroy']);


    Route::get('tenant/profile', [TenantController::class, 'vprofile'])->name('tenant_profile');
    Route::post('tenant/profile/update/{id}', [TenantController::class, 'updateprofile']);
    Route::post('tenant/profile/avatar/{id}', [TenantController::class, 'updateavatar']);
    Route::post('tenant/profile/password/{id}', [TenantController::class, 'updatepassword']);
});

Route::prefix('tenant')->group(function () {
    Route::get('/login', [TenantController::class, 'login'])->name('tenant_login');
    Route::get('/logout', [TenantController::class, 'logout'])->name('tenant_logout');
    Route::post('/login-submit', [TenantController::class, 'login_submit'])->name('tenant_login_submit');
    Route::get('/register', [TenantController::class, 'register'])->name('tenant_register');
    Route::post('/register-submit', [TenantController::class, 'TenantRegister'])->name('register_submit');
});


Route::post('/login', [TenantController::class, 'loginmodal'])->name('guest_login_modal');
//Guest
Route::get('/home', [TenantController::class, 'welcome'])->name('welcome');
Route::get('/property', [PropertyController::class, 'gproperty'])->name('property');
Route::get('/appointment', [AppointmentController::class, 'gview'])->name('appointment');
Route::get('guest/appointment/delete/{id}', [AppointmentController::class, 'gdestroy']);
Route::get('/contact', [PropertyController::class, 'contact'])->name('contact');
Route::get('/about', [PropertyController::class, 'about'])->name('about');

