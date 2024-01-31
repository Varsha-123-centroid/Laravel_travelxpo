<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AgentController;
use App\Http\Controllers\Admin\SubController;
use App\Http\Controllers\Staff\CustomLoginController;
use App\Http\Controllers\Api\CcaRequestController;
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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/clear', function() { 

   Artisan::call('cache:clear');
   Artisan::call('config:clear');
   Artisan::call('config:cache');
   Artisan::call('view:clear');

   return "Cleared!";

});


Route::get('/redirect-to-react', [CcaRequestController::class, 'redirectToReactApp']);




//Clear Cache facade value:
Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});

//Reoptimized class loader:
Route::get('/optimize', function() {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});

//Route cache:
Route::get('/route-cache', function() {
    $exitCode = Artisan::call('route:cache');
    return '<h1>Routes cached</h1>';
});

//Clear Route cache:
Route::get('/route-clear', function() {
    $exitCode = Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});

//Clear View cache:
Route::get('/view-clear', function() {
    $exitCode = Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});

//Clear Config cache:
Route::get('/config-cache', function() {
    $exitCode = Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home'); 
Route::get('/registration', [App\Http\Controllers\AgentRegistrationController::class, 'index'])->name('registration'); 
Route::match(['get', 'post'], '/agentNewRegistration', [App\Http\Controllers\AgentRegistrationController::class, 'agentNewRegistration'])->name('agentNewRegistration'); 
Route::match(['get', 'post'], '/checkUsername', [App\Http\Controllers\AgentRegistrationController::class, 'checkUsername'])->name('checkUsername'); 



	Route::match(['get', 'post'], '/customer/addCustomer', [App\Http\Controllers\Admin\AdminController::class, 'addCustomer'])->name('customer.addCustomer');
	Route::match(['get', 'post'],'/customer/customerSave/{id?}', [App\Http\Controllers\Admin\AdminController::class, 'customerSave'])->name('customer.customerSave');
	Route::match(['get', 'post'], '/customer/customersList', [App\Http\Controllers\Admin\AdminController::class, 'customersList'])->name('customer.customersList');
	Route::get('/customer/{id}/customerEdit', [App\Http\Controllers\Admin\AdminController::class, 'customerEdit'])->name('customer.customerEdit'); 
	Route::match(['get', 'post'],'/customer/customerUpdate/', [App\Http\Controllers\Admin\AdminController::class, 'customerUpdate'])->name('customer.customerUpdate');
	Route::match(['get', 'post'], '/agent/checkCustomername', [App\Http\Controllers\Admin\AdminController::class, 'checkCustomername'])->name('admin.checkCustomername');



///*********************************Operation Staff****************************///////////

   

Route::get('custom-login', [CustomLoginController::class, 'showLoginForm'])->name('custom.login');
Route::post('custom-login', [CustomLoginController::class, 'login']);
Route::get('custom-dashboard', [CustomLoginController::class, 'index'])->name('custom-dashboard');
Route::match(['get', 'post'], '/logout', [CustomLoginController::class, 'logout'])->name('custom.logout');




/////////////******** Travelexpo Admin ********************************///////////////////
Route::middleware(['auth', 'role:1'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

	Route::match(['get', 'post'], '/admin/outsideRegStatus', [App\Http\Controllers\Admin\AdminController::class, 'outsideRegStatus'])->name('admin.outsideRegStatus');
	Route::match(['get', 'post'], '/admin/addCountry', [App\Http\Controllers\Admin\AdminController::class, 'addCountry'])->name('admin.addCountry');
   Route::match(['get', 'post'], '/admin/addAirlines', [App\Http\Controllers\Admin\AdminController::class, 'addAirlines'])->name('admin.addAirlines');
    Route::match(['get', 'post'], '/admin/addAirport', [App\Http\Controllers\Admin\AdminController::class, 'addAirport'])->name('admin.addAirport');
	 Route::match(['get', 'post'], '/admin/autocompletecountry', [App\Http\Controllers\Admin\AdminController::class, 'autocompletecountry'])->name('admin.autocompletecountry');
	  Route::match(['get', 'post'], '/admin/autocompletecity', [App\Http\Controllers\Admin\AdminController::class, 'autocompletecity'])->name('admin.autocompletecity');
    Route::match(['get', 'post'], '/admin/addCity', [App\Http\Controllers\Admin\AdminController::class, 'addCity'])->name('admin.addCity');
   Route::match(['get', 'post'],'/admin/deleteAirlines', [App\Http\Controllers\Admin\AdminController::class, 'deleteAirlines'])->name('admin.deleteAirlines');
   Route::match(['get', 'post'],'/admin/deleteCity', [App\Http\Controllers\Admin\AdminController::class, 'deleteCity'])->name('admin.deleteCity');
   Route::match(['get', 'post'],'/admin/deleteCountry', [App\Http\Controllers\Admin\AdminController::class, 'deleteCountry'])->name('admin.deleteCountry');
    Route::match(['get', 'post'],'/admin/deleteAirport', [App\Http\Controllers\Admin\AdminController::class, 'deleteAirport'])->name('admin.deleteAirport');
   Route::match(['get', 'post'], '/admin/airlineSave', [App\Http\Controllers\Admin\AdminController::class, 'airlineSave'])->name('admin.airlineSave');
      Route::match(['get', 'post'], '/admin/airportSave', [App\Http\Controllers\Admin\AdminController::class, 'airportSave'])->name('admin.airportSave');
   Route::match(['get', 'post'], '/admin/citySave', [App\Http\Controllers\Admin\AdminController::class, 'citySave'])->name('admin.citySave');
   Route::match(['get', 'post'], '/admin/countrySave', [App\Http\Controllers\Admin\AdminController::class, 'countrySave'])->name('admin.countrySave');
	Route::match(['get', 'post'], '/admin/addAgent', [App\Http\Controllers\Admin\AdminController::class, 'addAgent'])->name('admin.addAgent');
	Route::match(['get', 'post'], '/admin/newAgentsList', [App\Http\Controllers\Admin\AdminController::class, 'newAgentsList'])->name('admin.newAgentsList');
	Route::match(['get', 'post'], '/admin/logout', [App\Http\Controllers\Admin\AdminController::class, 'logout'])->name('admin.logout');
	Route::match(['get', 'post'],'/admin/agentSave/{id?}', [App\Http\Controllers\Admin\AdminController::class, 'agentSave'])->name('admin.agentSave');
	Route::match(['get', 'post'],'/admin/checkCreditLimit', [App\Http\Controllers\Admin\AdminController::class, 'checkCreditLimit'])->name('admin.checkCreditLimit');
	Route::match(['get', 'post'], '/admin/checkUsername', [App\Http\Controllers\Admin\AdminController::class, 'checkUsername'])->name('admin.checkUsername');
	Route::match(['get', 'post'], '/admin/checkAirlineCode', [App\Http\Controllers\Admin\AdminController::class, 'checkAirlineCode'])->name('admin.checkAirlineCode');
	Route::match(['get', 'post'], '/admin/checkAirportCode', [App\Http\Controllers\Admin\AdminController::class, 'checkAirportCode'])->name('admin.checkAirportCode');
	Route::match(['get', 'post'], '/admin/checkCityCode', [App\Http\Controllers\Admin\AdminController::class, 'checkCityCode'])->name('admin.checkCityCode');
	Route::match(['get', 'post'], '/admin/checkCountryCode', [App\Http\Controllers\Admin\AdminController::class, 'checkCountryCode'])->name('admin.checkCountryCode');
	Route::match(['get', 'post'], '/admin/changeAdminPassword', [App\Http\Controllers\Admin\AdminController::class, 'ShowchangePassword'])->name('admin.changeAdminPassword');
	Route::match(['get', 'post'], '/admin/updateAdminPassword', [App\Http\Controllers\Admin\AdminController::class, 'UpdatechangePassword'])->name('admin.updateAdminPassword');
	Route::match(['get', 'post'], '/admin/adminProfile', [App\Http\Controllers\Admin\AdminController::class, 'adminProfile'])->name('admin.adminProfile');
	Route::match(['get', 'post'], '/admin/updateAdminProfile', [App\Http\Controllers\Admin\AdminController::class, 'updateAdminProfile'])->name('admin.updateAdminProfile');
	Route::match(['get', 'post'], '/admin/agentList', [App\Http\Controllers\Admin\AdminController::class, 'agentList'])->name('admin.agentList');
	
	Route::get('/admin/agents/{id}/agentEdit', [App\Http\Controllers\Admin\AdminController::class, 'agentEdit'])->name('admin.agentEdit');
	
	
	Route::match(['get', 'post'],'/admin/agentUpdate/', [App\Http\Controllers\Admin\AdminController::class, 'agentUpdate'])->name('admin.agentUpdate');
	
	Route::match(['get', 'post'],'/admin/setMarkup/', [App\Http\Controllers\Admin\AdminController::class, 'setMarkup'])->name('admin.setMarkup');
	Route::match(['get', 'post'],'/admin/markupSave/', [App\Http\Controllers\Admin\AdminController::class, 'markupSave'])->name('admin.markupSave');
	Route::match(['get', 'post'],'/admin/updateMarkupStatus/', [App\Http\Controllers\Admin\AdminController::class, 'updateMarkupStatus'])->name('admin.updateMarkupStatus');
	Route::match(['get', 'post'], '/admin/makePayment', [App\Http\Controllers\Admin\AgentController::class, 'makePayment'])->name('admin.makePayment');
	Route::match(['get', 'post'], '/admin/paymentSave', [App\Http\Controllers\Admin\AgentController::class, 'paymentSave'])->name('admin.paymentSave');
	Route::match(['get', 'post'], '/admin/approveList', [App\Http\Controllers\Admin\AdminController::class, 'approveList'])->name('admin.approveList');
	Route::match(['get', 'post'], '/admin/approvalReport', [App\Http\Controllers\Admin\AdminController::class, 'approvalReport'])->name('admin.approvalReport');
	Route::match(['get', 'post'], '/admin/paymentStatus', [App\Http\Controllers\Admin\AdminController::class, 'paymentStatus'])->name('admin.paymentStatus');
	
	Route::match(['get', 'post'], '/admin/authenticateTicket', [App\Http\Controllers\Admin\ticketController::class, 'authenticateTicket'])->name('admin.authenticateTicket');
	
	Route::match(['get', 'post'], '/admin/generatePDFInvoice', [App\Http\Controllers\Admin\ticketController::class, 'generatePDFInvoice'])->name('admin.generatePDFInvoice');
	
	Route::match(['get', 'post'], '/admin/Cancel', [App\Http\Controllers\Admin\AgentController::class, 'addCancel'])->name('admin.Cancel');
	Route::match(['get', 'post'], '/admin/getTicketsAfterToday', [App\Http\Controllers\Admin\AgentController::class, 'getTicketsAfterToday'])->name('admin.getTicketsAfterToday');
	Route::match(['get', 'post'], '/admin/getPassengerDetails', [App\Http\Controllers\Admin\AgentController::class, 'getPassengerDetails'])->name('admin.getPassengerDetails');
	Route::match(['get', 'post'], '/admin/storePassengerDetails', [App\Http\Controllers\Admin\AgentController::class, 'storePassengerDetails'])->name('admin.storePassengerDetails');
	Route::match(['get', 'post'], '/admin/cancelList', [App\Http\Controllers\Admin\AdminController::class, 'cancelList'])->name('admin.cancelList');
	Route::match(['get', 'post'], '/admin/cancelBooking', [App\Http\Controllers\Admin\AdminController::class, 'cancelBooking'])->name('admin.cancelBooking');
	Route::match(['get', 'post'], '/admin/submitCancellation', [App\Http\Controllers\Admin\AdminController::class, 'submitCancellation'])->name('admin.submitCancellation');
	Route::match(['get', 'post'], '/admin/submitMessage', [App\Http\Controllers\Admin\AdminController::class, 'submitMessage'])->name('admin.submitMessage');
	Route::match(['get', 'post'], '/admin/commentsList', [App\Http\Controllers\Admin\AgentController::class, 'commentsList'])->name('admin.commentsList');
	
	Route::match(['get', 'post'], '/admin/savePnr', [App\Http\Controllers\Admin\ticketController::class, 'savePnr'])->name('admin.savePnr');
	Route::match(['get', 'post'], '/admin/saveBookingId', [App\Http\Controllers\Admin\ticketController::class, 'saveBookingId'])->name('admin.saveBookingId');
	Route::match(['get', 'post'],'/admin/fetchAirlineData', [App\Http\Controllers\Admin\AdminController::class, 'fetchAirlineData'])->name('admin.fetchAirlineData');
	Route::match(['get', 'post'],'/admin/dailySalesReport', [App\Http\Controllers\Admin\AdminController::class, 'dailySalesReport'])->name('admin.dailySalesReport');
	Route::match(['get', 'post'],'/admin/updateAirline', [App\Http\Controllers\Admin\AdminController::class, 'updateAirline'])->name('admin.updateAirline');
	Route::match(['get', 'post'],'/admin/exportDailySales', [App\Http\Controllers\Admin\AdminController::class, 'exportDailySales'])->name('admin.exportDailySales');
	Route::match(['get', 'post'],'/admin/bookingReport', [App\Http\Controllers\Admin\AdminController::class, 'bookingReport'])->name('admin.bookingReport');
	Route::match(['get', 'post'],'/admin/getbookingReport', [App\Http\Controllers\Admin\AdminController::class, 'getbookingReport'])->name('admin.getbookingReport');
	Route::match(['get', 'post'],'/admin/exportBookingReport', [App\Http\Controllers\Admin\AdminController::class, 'exportBookingReport'])->name('admin.exportBookingReport');
	Route::match(['get', 'post'],'/admin/get_customers', [App\Http\Controllers\Admin\AdminController::class, 'get_customers'])->name('admin.get_customers');
	Route::match(['get', 'post'], '/admin/search_Passengerslist', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('admin.search_Passengerslist');
    Route::match(['get', 'post'],'/admin/agent_recieptReport', [App\Http\Controllers\Admin\AdminController::class, 'agent_recieptReport'])->name('admin.agent_recieptReport');
	 Route::match(['get', 'post'],'/admin/exportAgentSales', [App\Http\Controllers\Admin\AdminController::class, 'exportAgentSales'])->name('admin.exportAgentSales');
	
});
//////////////////**********************************************************//////////////////////// 
/////////////******** Agent Admin ********************************///////////////////
Route::middleware(['auth', 'role:2'])->group(function () {
    Route::get('/agent/dashboard', [AgentController::class, 'index'])->name('agent.dashboard');
	Route::match(['get', 'post'],'/agent/dailySalesReport', [App\Http\Controllers\Admin\AgentController::class, 'dailySalesReport'])->name('agent.dailySalesReport');
	Route::match(['get', 'post'],'/agent/exportDailySales', [App\Http\Controllers\Admin\AgentController::class, 'exportDailySales'])->name('agent.exportDailySales');
	Route::match(['get', 'post'], '/agent/operationStaff', [App\Http\Controllers\Admin\AdminController::class, 'operationStaff'])->name('agent.operationStaff');
    Route::match(['get', 'post'],'/agent/operationSave/{id?}', [App\Http\Controllers\Admin\AdminController::class, 'operationSave'])->name('agent.operationSave');
    Route::match(['get', 'post'], '/agent/checkUsernameOperationStaff', [App\Http\Controllers\Admin\AgentController::class, 'checkUsernameOperationStaff'])->name('agent.checkUsernameOperationStaff');
	Route::match(['get', 'post'], '/agent/operationStaffList', [App\Http\Controllers\Admin\AdminController::class, 'operationStaffList'])->name('agent.operationStaffList');
	Route::get('/agent/{id}/operationStaffEdit', [App\Http\Controllers\Admin\AdminController::class, 'operationStaffEdit'])->name('agent.operationStaffEdit');
	Route::match(['get', 'post'],'/agent/operationStaffUpdate/', [App\Http\Controllers\Admin\AdminController::class, 'operationStaffUpdate'])->name('agent.operationStaffUpdate');
	
	Route::match(['get', 'post'], '/agent/addSubAgent', [App\Http\Controllers\Admin\AgentController::class, 'addSubAgent'])->name('agent.addSubAgent');
	Route::match(['get', 'post'], '/agent/logout', [App\Http\Controllers\Admin\AgentController::class, 'logout'])->name('agent.logout');
	Route::match(['get', 'post'],'/agent/subAgentSave/{id?}', [App\Http\Controllers\Admin\AgentController::class, 'subAgentSave'])->name('agent.subAgentSave');
	Route::match(['get', 'post'], '/agent/checkUsername', [App\Http\Controllers\Admin\AgentController::class, 'checkUsername'])->name('agent.checkUsername');
	
	Route::match(['get', 'post'], '/agent/subAgentList', [App\Http\Controllers\Admin\AgentController::class, 'subAgentList'])->name('agent.subAgentList');
	Route::get('/agent/agents/{id}/subAgentEdit', [App\Http\Controllers\Admin\AgentController::class, 'subAgentEdit'])->name('agent.subAgentEdit');
	Route::match(['get', 'post'],'/agent/subAgentUpdate/', [App\Http\Controllers\Admin\AgentController::class, 'subAgentUpdate'])->name('agent.subAgentUpdate');
	Route::match(['get', 'post'], '/agent/changeAdminPassword', [App\Http\Controllers\Admin\AdminController::class, 'ShowchangePassword'])->name('agent.changeAdminPassword');
	Route::match(['get', 'post'], '/agent/updateAdminPassword', [App\Http\Controllers\Admin\AdminController::class, 'UpdatechangePassword'])->name('agent.updateAdminPassword');
	Route::match(['get', 'post'], '/agent/adminProfile', [App\Http\Controllers\Admin\AdminController::class, 'adminProfile'])->name('agent.adminProfile');
	Route::match(['get', 'post'], '/agent/updateAdminProfile', [App\Http\Controllers\Admin\AdminController::class, 'updateAdminProfile'])->name('agent.updateAdminProfile');
	Route::match(['get', 'post'],'/agent/setMarkup/', [App\Http\Controllers\Admin\AdminController::class, 'setMarkup'])->name('agent.setMarkup');
	Route::match(['get', 'post'],'/agent/markupSave/', [App\Http\Controllers\Admin\AdminController::class, 'markupSave'])->name('agent.markupSave');
	Route::match(['get', 'post'],'/agent/updateMarkupStatus/', [App\Http\Controllers\Admin\AdminController::class, 'updateMarkupStatus'])->name('agent.updateMarkupStatus');
	Route::match(['get', 'post'], '/agent/makePayment', [App\Http\Controllers\Admin\AgentController::class, 'makePayment'])->name('agent.makePayment');
	Route::match(['get', 'post'], '/agent/Cancel', [App\Http\Controllers\Admin\AgentController::class, 'addCancel'])->name('agent.Cancel');
	Route::match(['get', 'post'], '/agent/getTicketsAfterToday', [App\Http\Controllers\Admin\AgentController::class, 'getTicketsAfterToday'])->name('agent.getTicketsAfterToday');
	Route::match(['get', 'post'], '/agent/getPassengerDetails', [App\Http\Controllers\Admin\AgentController::class, 'getPassengerDetails'])->name('agent.getPassengerDetails');
	Route::match(['get', 'post'], '/agent/commentsList', [App\Http\Controllers\Admin\AgentController::class, 'commentsList'])->name('agent.commentsList');
	Route::match(['get', 'post'], '/agent/storePassengerDetails', [App\Http\Controllers\Admin\AgentController::class, 'storePassengerDetails'])->name('agent.storePassengerDetails');
	Route::match(['get', 'post'], '/agent/submitMessage', [App\Http\Controllers\Admin\AdminController::class, 'submitMessage'])->name('agent.submitMessage');
	Route::match(['get', 'post'], '/agent/paymentSave', [App\Http\Controllers\Admin\AgentController::class, 'paymentSave'])->name('agent.paymentSave');
	Route::match(['get', 'post'], '/agent/approveList', [App\Http\Controllers\Admin\AdminController::class, 'approveList'])->name('agent.approveList');
	Route::match(['get', 'post'], '/agent/approvalReport', [App\Http\Controllers\Admin\AdminController::class, 'approvalReport'])->name('agent.approvalReport');
	Route::match(['get', 'post'], '/agent/paymentStatus', [App\Http\Controllers\Admin\AdminController::class, 'paymentStatus'])->name('agent.paymentStatus');
	Route::match(['get', 'post'],'/agent/checkCreditLimitAgent', [App\Http\Controllers\Admin\AgentController::class, 'checkCreditLimitAgent'])->name('agent.checkCreditLimitAgent');
	Route::match(['get', 'post'], '/agent/authenticateTicket', [App\Http\Controllers\Admin\ticketController::class, 'authenticateTicket'])->name('agent.authenticateTicket');
	Route::match(['get', 'post'], '/agent/savePnr', [App\Http\Controllers\Admin\ticketController::class, 'savePnr'])->name('agent.savePnr');
	Route::match(['get', 'post'], '/agent/saveBookingId', [App\Http\Controllers\Admin\ticketController::class, 'saveBookingId'])->name('agent.saveBookingId');
	Route::match(['get', 'post'], '/agent/generatePDFInvoice', [App\Http\Controllers\Admin\ticketController::class, 'generatePDFInvoice'])->name('agent.generatePDFInvoice');
	
	Route::match(['get', 'post'],'/agent/bookingReport', [App\Http\Controllers\Admin\AdminController::class, 'bookingReport'])->name('agent.bookingReport');
	Route::match(['get', 'post'],'/agent/getbookingReport', [App\Http\Controllers\Admin\AdminController::class, 'getbookingReport'])->name('agent.getbookingReport');
	Route::match(['get', 'post'],'/agent/exportBookingReport', [App\Http\Controllers\Admin\AdminController::class, 'exportBookingReport'])->name('agent.exportBookingReport');
	Route::match(['get', 'post'],'/agent/get_customers', [App\Http\Controllers\Admin\AdminController::class, 'get_customers'])->name('agent.get_customers');
	Route::match(['get', 'post'], '/agent/search_Passengerslist', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('agent.search_Passengerslist');
	 Route::match(['get', 'post'],'/agent/agent_recieptReport', [App\Http\Controllers\Admin\AdminController::class, 'agent_recieptReport'])->name('agent.agent_recieptReport');
	  Route::match(['get', 'post'],'/agent/exportAgentSales', [App\Http\Controllers\Admin\AdminController::class, 'exportAgentSales'])->name('agent.exportAgentSales');
	  Route::match(['get', 'post'], '/agent/search_Passengerslist', [App\Http\Controllers\Admin\AgentController::class, 'index'])->name('agent.search_Passengerslist');
});

//////////////////**********************************************************////////////////////////
/////////////******** Sub Agent********************************///////////////////
Route::middleware(['auth', 'role:3'])->group(function () {
    Route::get('/sub/dashboard', [SubController::class, 'index'])->name('sub.dashboard');
	Route::match(['get', 'post'], '/sub/addSubAgent', [App\Http\Controllers\Admin\AgentController::class, 'addSubAgent'])->name('sub.addSubAgent');
	Route::match(['get', 'post'],'/sub/dailySalesReport', [App\Http\Controllers\Admin\SubController::class, 'dailySalesReport'])->name('sub.dailySalesReport');
	Route::match(['get', 'post'],'/sub/exportDailySales', [App\Http\Controllers\Admin\SubController::class, 'exportDailySales'])->name('sub.exportDailySales');
	Route::match(['get', 'post'], '/sub/logout', [App\Http\Controllers\Admin\SubController::class, 'logout'])->name('sub.logout');
	Route::match(['get', 'post'], '/sub/operationStaff', [App\Http\Controllers\Admin\SubController::class, 'operationStaff'])->name('sub.operationStaff');
	
	 Route::match(['get', 'post'],'/sub/operationSave/{id?}', [App\Http\Controllers\Admin\SubController::class, 'operationSave'])->name('sub.operationSave');
    Route::match(['get', 'post'], '/sub/checkUsernameOperationStaff', [App\Http\Controllers\Admin\AgentController::class, 'checkUsernameOperationStaff'])->name('sub.checkUsernameOperationStaff');
	Route::match(['get', 'post'], '/sub/operationStaffList', [App\Http\Controllers\Admin\SubController::class, 'operationStaffList'])->name('sub.operationStaffList');
	Route::get('/sub/{id}/operationStaffEdit', [App\Http\Controllers\Admin\SubController::class, 'operationStaffEdit'])->name('sub.operationStaffEdit');
	Route::match(['get', 'post'],'/sub/operationStaffUpdate/', [App\Http\Controllers\Admin\SubController::class, 'operationStaffUpdate'])->name('sub.operationStaffUpdate');
	Route::match(['get', 'post'], '/sub/submitMessage', [App\Http\Controllers\Admin\AdminController::class, 'submitMessage'])->name('sub.submitMessage');
	Route::match(['get', 'post'], '/sub/commentsList', [App\Http\Controllers\Admin\AgentController::class, 'commentsList'])->name('sub.commentsList');
	Route::match(['get', 'post'], '/sub/Cancel', [App\Http\Controllers\Admin\AgentController::class, 'addCancel'])->name('sub.Cancel');
	Route::match(['get', 'post'], '/sub/getTicketsAfterToday', [App\Http\Controllers\Admin\AgentController::class, 'getTicketsAfterToday'])->name('sub.getTicketsAfterToday');
	Route::match(['get', 'post'], '/sub/getPassengerDetails', [App\Http\Controllers\Admin\AgentController::class, 'getPassengerDetails'])->name('sub.getPassengerDetails');
	Route::match(['get', 'post'], '/sub/storePassengerDetails', [App\Http\Controllers\Admin\AgentController::class, 'storePassengerDetails'])->name('sub.storePassengerDetails');
	Route::match(['get', 'post'],'/sub/subAgentSave/{id?}', [App\Http\Controllers\Admin\AgentController::class, 'subAgentSave'])->name('sub.subAgentSave');
	Route::match(['get', 'post'], '/sub/checkUsername', [App\Http\Controllers\Admin\AgentController::class, 'checkUsername'])->name('sub.checkUsername');
	Route::match(['get', 'post'], '/sub/subAgentList', [App\Http\Controllers\Admin\AgentController::class, 'subAgentList'])->name('sub.subAgentList');
	Route::get('/sub/agents/{id}/subAgentEdit', [App\Http\Controllers\Admin\AgentController::class, 'subAgentEdit'])->name('sub.subAgentEdit');
	Route::match(['get', 'post'],'/sub/subAgentUpdate/', [App\Http\Controllers\Admin\AgentController::class, 'subAgentUpdate'])->name('sub.subAgentUpdate');
	Route::match(['get', 'post'], '/sub/changeAdminPassword', [App\Http\Controllers\Admin\AdminController::class, 'ShowchangePassword'])->name('sub.changeAdminPassword');
	Route::match(['get', 'post'], '/sub/updateAdminPassword', [App\Http\Controllers\Admin\AdminController::class, 'UpdatechangePassword'])->name('sub.updateAdminPassword');
	Route::match(['get', 'post'], '/sub/adminProfile', [App\Http\Controllers\Admin\AdminController::class, 'adminProfile'])->name('sub.adminProfile');
	Route::match(['get', 'post'], '/sub/updateAdminProfile', [App\Http\Controllers\Admin\AdminController::class, 'updateAdminProfile'])->name('sub.updateAdminProfile');
	Route::match(['get', 'post'],'/sub/setMarkup/', [App\Http\Controllers\Admin\AdminController::class, 'setMarkup'])->name('sub.setMarkup');
	Route::match(['get', 'post'],'/sub/markupSave/', [App\Http\Controllers\Admin\AdminController::class, 'markupSave'])->name('sub.markupSave');
	Route::match(['get', 'post'],'/sub/updateMarkupStatus/', [App\Http\Controllers\Admin\AdminController::class, 'updateMarkupStatus'])->name('sub.updateMarkupStatus');
	Route::match(['get', 'post'], '/sub/subMarkupList', [App\Http\Controllers\Admin\SubController::class, 'subMarkupList'])->name('sub.subMarkupList');
	Route::match(['get', 'post'], '/sub/makePayment', [App\Http\Controllers\Admin\AgentController::class, 'makePayment'])->name('sub.makePayment');
	Route::match(['get', 'post'], '/sub/paymentSave', [App\Http\Controllers\Admin\AgentController::class, 'paymentSave'])->name('sub.paymentSave');
	Route::match(['get', 'post'], '/sub/authenticateTicket', [App\Http\Controllers\Admin\ticketController::class, 'authenticateTicket'])->name('sub.authenticateTicket');
	Route::match(['get', 'post'], '/sub/savePnr', [App\Http\Controllers\Admin\ticketController::class, 'savePnr'])->name('sub.savePnr');
	Route::match(['get', 'post'], '/sub/saveBookingId', [App\Http\Controllers\Admin\ticketController::class, 'saveBookingId'])->name('sub.saveBookingId');
	Route::match(['get', 'post'], '/sub/generatePDFInvoice', [App\Http\Controllers\Admin\ticketController::class, 'generatePDFInvoice'])->name('sub.generatePDFInvoice');
	
	Route::match(['get', 'post'],'/sub/bookingReport', [App\Http\Controllers\Admin\AdminController::class, 'bookingReport'])->name('sub.bookingReport');
	Route::match(['get', 'post'],'/sub/getbookingReport', [App\Http\Controllers\Admin\AdminController::class, 'getbookingReport'])->name('sub.getbookingReport');
	Route::match(['get', 'post'],'/sub/exportBookingReport', [App\Http\Controllers\Admin\AdminController::class, 'exportBookingReport'])->name('sub.exportBookingReport');
	Route::match(['get', 'post'],'/sub/get_customers', [App\Http\Controllers\Admin\AdminController::class, 'get_customers'])->name('sub.get_customers');
	Route::match(['get', 'post'], '/sub/search_Passengerslist', [App\Http\Controllers\Admin\SubController::class, 'index'])->name('sub.search_Passengerslist');
	
});