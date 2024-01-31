<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AgentController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\B2cController;
use App\Http\Controllers\Api\CcaRequestController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('agent/getBalance', [AgentController::class, 'getBalance']);
Route::post('agent/getMarkupAmt', [BookingController::class, 'getMarkupAmt']);
Route::post('customerAuth', [CustomerController::class, 'customerAuth']);
Route::post('booking', [BookingController::class, 'booking']);
Route::post('/ccav-request', [CcaRequestController::class, 'handleRequest']);
Route::post('/pay-activate', [CcaRequestController::class, 'payActivate']);
Route::post('/billing-details', [CcaRequestController::class, 'billing_details']);
Route::post('/cca-response', [CcaRequestController::class, 'cca_response']);
Route::post('/get-pair-quotes', [B2cController::class, 'getPairQuotes']);
Route::post('/ccavenue/callback', [CcaRequestController::class, 'handleCcavenueRedirect']);
Route::post('/redirect-to-react', [CcaRequestController::class, 'redirectToReactApp']);
Route::post('/verify-order', [CcaRequestController::class, 'verifyOrder']);
Route::post('/generate-token', [CcaRequestController::class, 'generateToken']);
Route::post('/get-airport', [CcaRequestController::class, 'get_airport']);
Route::post('/get-markup-data', [CcaRequestController::class, 'getMarkupData']);
Route::post('/get-city', [CcaRequestController::class, 'get_city']);
Route::post('/getUserData', [CcaRequestController::class, 'getUserData']);
Route::post('/get-Allbookings', [CcaRequestController::class, 'getTicketsAfterToday']);
Route::post('/generateTicket', [CcaRequestController::class, 'getTicketDetails']);
Route::post('/saveTicket', [CcaRequestController::class, 'storePassengerDetails']);
Route::post('/getMessages', [CcaRequestController::class, 'getTicketsComments']);
Route::post('/getCancels', [CcaRequestController::class, 'getCancels']);
Route::post('/saveTicketComments', [CcaRequestController::class, 'saveTicketComments']);