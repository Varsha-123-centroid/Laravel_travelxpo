<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Str;
use PDF;
use NumberFormatter;


class ticketController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
		
        
    }
	
	 public function savePNR(Request $request)
    {
        // Retrieve the edited PNR and booking ID from the request
        $editedPNR = $request->input('editedPNR');
        $bookingId = $request->input('bookingId');

         // Update the PNR in your database using DB::table
    $affectedRows = DB::table('passenger_bookings')
        ->where('id', $bookingId)
        ->update(['PNR' => $editedPNR]);

    // Check if the update was successful
    if ($affectedRows > 0) {
        // The PNR was updated successfully
        return response()->json(['success' => true, 'message' => 'PNR updated successfully']);
    } else {
        // Handle the case where the PNR was not updated (e.g., no changes made)
        return response()->json(['success' => false, 'message' => 'No changes made to PNR']);
    }
       
    }
	public function saveBookingId(Request $request)
    {
        // Retrieve the edited PNR and booking ID from the request
        $editedBookingId = $request->input('editedBookingId');
        $id = $request->input('id');

         // Update the PNR in your database using DB::table
    $affectedRows = DB::table('passenger_bookings')
        ->where('id', $id)
        ->update(['booking_id' => $editedBookingId]);

    // Check if the update was successful
    if ($affectedRows > 0) {
        // The PNR was updated successfully
        return response()->json(['success' => true, 'message' => 'BookingID updated successfully']);
    } else {
        // Handle the case where the PNR was not updated (e.g., no changes made)
        return response()->json(['success' => false, 'message' => 'No changes made to BookingID']);
    }
       
    }
public function authenticateTicket(Request $request)
	{
	 $ip_address =  $_SERVER['REMOTE_ADDR'];
	
       $bookingId = $request->input('booking_id');
        $pnr = $request->input('pnr');
		 $token = DB::table('token')->whereDate('token_created_date', '=', date('Y-m-d'))->first();
     
        if (!$token) {
            // If the token doesn't exist for today's date, create a new token
            $ip_address = $_SERVER['REMOTE_ADDR'];
            $url = 'http://api.tektravels.com/SharedServices/SharedData.svc/rest/Authenticate';
            $data = [
                'ClientId' => 'ApiIntegrationNew',
                'UserName' => 'TRAVELNETIC',
                'Password' => 'Travel@4321',
                'EndUserIp' => $ip_address,
            ];
            $json_payload = json_encode($data);
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json_payload);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            
            $response = curl_exec($ch);
            curl_close($ch);
            
            $result = (array) json_decode($response);
            
            if ($response === false) {
                // Handle error condition
                return response()->json(['error' => 'Failed to authenticate.']);
            } else {
				$lastToken = DB::table('token')
            ->orderBy('token_created_date', 'desc')
            ->first();
			 DB::table('token')->where('id', $lastToken->id)->delete();
                $tokenId = $result['TokenId'];
               
                // Create or update the token record
                DB::table('token')->updateOrInsert(
                    ['token_name' => $tokenId],
                   
                );
				
			 $ticketHTML =$this->generateTicket($bookingId,$pnr);
            }
        }
		else{  $ticketHTML = $this->generateTicket($bookingId,$pnr); }
		 // Retrieve the token with today's date
     return response()->json(['ticketHTML' => $ticketHTML]);
		}
      
	public function generateTicket($bookingId,$pnr)
	{
		$tokenId = DB::table('token')
    ->whereDate('token_created_date', '=', date('Y-m-d'))
    ->value('token_name');
 $ip_address =  $_SERVER['REMOTE_ADDR'];

    // Generate ticket using the retrieved token
 $url = "http://api.tektravels.com/BookingEngineService_Air/AirService.svc/rest/GetBookingDetails/";


    $data = [
        'TokenId' => $tokenId,
        'EndUserIp' => $ip_address,
        'PNR' => $pnr,
        'BookingId' => $bookingId
    ];
    $json_payload = json_encode($data);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $json_payload);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/json',
				'Content-Length: ' . strlen($json_payload)
			));
	$response = curl_exec($ch);
  	// Check if there was an error with the cURL request
			if (curl_errno($ch)) {
				echo 'Error: ' . curl_error($ch);
			}

			// Close the cURL session
			curl_close($ch);

    $result = (array) json_decode($response);
	
$flightItinerary = $result['Response']->FlightItinerary;
$serbookingId = $flightItinerary->BookingId;
$serviceCharge = DB::table('passenger_bookings')
    ->where('booking_id', $serbookingId)
    ->selectRaw('SUM(expo_price + agent_price + subagent_price) as service_charge')
    ->first();

$dbserviceFee = $serviceCharge->service_charge;
    // Format the ticket data into HTML
    $ticketHTML = '<div class="main-contents">';
    $ticketHTML .= '<div class="container-fluid">';
    $ticketHTML .= '<div class="row align-items-center justify-content-center">';
    $ticketHTML .= '<div class="col-md-11 p-0">';
    $ticketHTML .= '<h3 class="text-center text-white">Flight Ticket</h3>';
    $ticketHTML .= '<div class="edit_profileSec">';
    $ticketHTML .= '<div class="editProfileForm">';
    $ticketHTML .= '<div class="clearDiv row">';
    $ticketHTML .= '<div class="bookingFrom homepagebooking">';
    $ticketHTML .= '<div class="edit_profileSec">';
    $ticketHTML .= '<div class="editProfileForm">';
    $ticketHTML .= '<h3 class=""><img src="" style="width:40px;height:40px"> &nbsp;Depart From ' . $flightItinerary->Origin . ' | Arrives at ' . $flightItinerary->Destination . '</h3>';
    $ticketHTML .= '<h5>Booking ID: ' . $flightItinerary->BookingId . '</h5>';
    $ticketHTML .= '<h5>PNR: ' . $flightItinerary->PNR . ', ' . $flightItinerary->IsDomestic . '</h5>';
    $ticketHTML .= '<table id="datatable" class="table table-bordered dt-responsive nowrap" style="text-align:left;border-collapse: collapse; border-spacing: 0; table-layout: fixed;border-collapse: collapse;">';
    $ticketHTML .= '<thead style="background-color:#4a4646d1;color:white">';
    $ticketHTML .= '<tr>';
    $ticketHTML .= '<th>Passenger Name</th>';
    $ticketHTML .= '<th>Ticket</th>';
    $ticketHTML .= '<th>Price</th>';
    $ticketHTML .= '</tr>';
    $ticketHTML .= '</thead>';
    $ticketHTML .= '<tbody>';

    // Loop through passengers and generate rows
    foreach ($flightItinerary->Passenger as $passenger) {
        $ticketHTML .= '<tr>';
        $ticketHTML .= '<td><h5><strong>' . $passenger->Title . '. ' . $passenger->FirstName . ' ' . $passenger->LastName . '</strong></h5></td>';
        $ticketHTML .= '<td><h5><strong>Mobile: ' . $passenger->ContactNo . '</strong></h5></td>';
	$email = !empty($passenger->Email) ? $passenger->Email : 'N/A';
$ticketHTML .= '<td><h5><strong>Email: ' . $email . '</strong></h5></td>';

       // $ticketHTML .= '<td><h5><strong>Email: ' . $passenger->Email . '</strong></h5></td>';
        //$ticketHTML .= '<td><h5><strong>Email: varsha.unni08@gmail.com</strong></h5></td>';
        $ticketHTML .= '</tr>';
        $baseFare = $passenger->Fare->BaseFare;
        $tax = $passenger->Fare->Tax;
        $serviceFee = $dbserviceFee;
        $otherCharges = $passenger->Fare->OtherCharges;
        $totalPrice = $baseFare + $tax + $serviceFee + $otherCharges;
        $ticketHTML .= '<tr><td>Base Fare</td><td></td><td>' . $baseFare . '</td></tr>';
        $ticketHTML .= '<tr><td>Tax</td><td></td><td>' . $tax . '</td></tr>';
        $ticketHTML .= '<tr><td>Service Charge</td><td></td><td>' . $dbserviceFee . '</td></tr>';
        $ticketHTML .= '<tr><td>Other Charge</td><td></td><td>' . $otherCharges . '</td></tr>';
        $ticketHTML .= '<tr><td>Total</td><td></td><td>' . $totalPrice . '</td></tr>';
    }

    $ticketHTML .= '</tbody>';
    $ticketHTML .= '</table>';

    // Loop through segments and generate rows
    foreach ($flightItinerary->Segments as $segment) {
        $ticketHTML .= '<table id="datatable" class="table table-bordered dt-responsive nowrap" style="text-align:left;border-collapse: collapse; border-spacing: 0; table-layout: fixed;border-collapse: collapse;">';
        $ticketHTML .= '<tr><td colspan="3">Flight</td></tr>';
        $ticketHTML .= '<tr>';
        $ticketHTML .= '<td style="text-align:left;word-wrap: break-word;width: 200px;">Airline:<strong>' . $segment->Airline->AirlineName . '</strong></td>';
        $ticketHTML .= '<td style="text-align:left;word-wrap: break-word;width: 200px;">AirlineCode: <strong>' . $segment->Airline->AirlineCode . '</strong></td>';
        $ticketHTML .= '<td style="text-align:left;word-wrap: break-word;width: 200px;">Flight Number: <strong>' . $segment->Airline->FlightNumber . '</strong></td>';
        $ticketHTML .= '</tr>';
        $ticketHTML .= '<tr><td colspan="3">Departure</td></tr>';
        $ticketHTML .= '<tr>';
        $ticketHTML .= '<td style="text-align:left;word-wrap: break-word;width: 200px;">Airport Name:<strong>' . $segment->Origin->Airport->AirportName . '</strong></td>';
        $ticketHTML .= '<td style="text-align:left;word-wrap: break-word;width: 200px;">City Name: <strong>' . $segment->Origin->Airport->CityName . '</strong></td>';
        $ticketHTML .= '<td style="text-align:left;word-wrap: break-word;width: 200px;">Terminal: <strong>' . $segment->Origin->Airport->Terminal . '</strong>, ' . $segment->Origin->DepTime . '</td>';
        $ticketHTML .= '</tr>';
        $ticketHTML .= '<tr><td colspan="3">Arrival</td></tr>';
        $ticketHTML .= '<tr>';
        $ticketHTML .= '<td style="text-align:left;word-wrap: break-word;width: 200px;">Airport Name:<strong>' . $segment->Destination->Airport->AirportName . '</strong></td>';
        $ticketHTML .= '<td style="text-align:left;word-wrap: break-word;width: 200px;">City Name: <strong>' . $segment->Destination->Airport->CityName . '</strong></td>';
        $ticketHTML .= '<td style="text-align:left;word-wrap: break-word;width: 200px;">Terminal: <strong>' . $segment->Destination->Airport->Terminal . '</strong>, ' . $segment->Destination->ArrTime . '</td>';
        $ticketHTML .= '</tr>';
        $ticketHTML .= '</table>';
    }

    // Loop through fare rules and generate rows
    foreach ($flightItinerary->FareRules as $fareRule) {
        $ticketHTML .= '<table id="datatable" class="table table-bordered dt-responsive nowrap" style="text-align:left;border-collapse: collapse; border-spacing: 0; table-layout: fixed;border-collapse: collapse;">';
        $ticketHTML .= '<tr>';
        $ticketHTML .= '<td colspan="3" style="text-align:left;border: solid 1px #666;word-wrap: break-word;width: 100%;"><strong>' . $fareRule->FareRuleDetail . '</strong></td>';
        $ticketHTML .= '</tr>';
        $ticketHTML .= '</table>';
    }

    $ticketHTML .= '</div>';
    $ticketHTML .= '</div>';
    $ticketHTML .= '</div>';
    $ticketHTML .= '</div>';
    $ticketHTML .= '</div>';
    $ticketHTML .= '</div>';
    $ticketHTML .= '</div>';

    // Send the ticket HTML to the user for printing
  return $ticketHTML;
	}
    // Save the user data
	
	public function generatePDFInvoice(Request $request)
{
    $bookingid = $request->booking_id;

    // Get branch_id from passenger_bookings
    $branchId = DB::table('passenger_bookings')
        ->where('booking_id', $bookingid)
        ->value('branch_id');

    if ($branchId) {
        // Retrieve user_id and user_type from the branch table
        $branchInfo = DB::table('branch')
            ->select('user_id', 'user_type')
            ->where('id', $branchId)
            ->first();

        if ($branchInfo) {
            $user_id = $branchInfo->user_id;
            $user_type = $branchInfo->user_type;

            // Initialize an empty array to store the result
            $userData = [];

            // Check user_type and fetch data from the respective table with selected fields
            if ($user_type === 'txpo') {
                $userData = DB::table('travelxpo_admin')
                    ->select('*')
                    ->where('id', $user_id)
                    ->first();
			   $userData->additionalParameter = '1';
            } elseif ($user_type === 'main') {
                $userData = DB::table('agents')
                    ->select('*')
                    ->where('id', $user_id)
                    ->first();
            } elseif ($user_type === 'sub') {
                $userData = DB::table('sub_agents')
                    ->select('*')
                    ->where('id', $user_id)
                    ->first();
            }
        }
    }

    // Get data from the 'invoice' table where booking_id matches
    $invoice = DB::table('invoice')
        ->where('booking_id', $bookingid)
        ->first();

    if ($userData) {
        // Check if an invoice was found
        if ($invoice) {
            // Get the invoice id and store it in a variable
            $invoiceId = $invoice->id;
            $invoicetotalamt = $invoice->total_amount;

            // Convert the total amount to words
            $numberToWords = new NumberFormatter("en", NumberFormatter::SPELLOUT);
            $totalAmountInWords = ucfirst($numberToWords->format($invoicetotalamt));
            // Get data from the 'invoice_detail' table where invoice_id matches the stored invoiceId
            $invoiceDetail = DB::table('invoice_detail')
                ->where('invoice_id', $invoiceId)
                ->get();  // You can use get() for multiple records or first() for a single record

            // Create a PDF view with $userData, $invoice, and $invoiceDetail
            $pdf = PDF::loadView('admin.invoice', ['userData' => $userData, 'invoice' => $invoice, 'invoiceDetail' => $invoiceDetail,'totalAmountInWords' => $totalAmountInWords]);

            // Generate the PDF content
            $pdfContent = $pdf->output(); // Get the PDF content as a string

            // Return the PDF as a response
            return response()->json(['pdf' => base64_encode($pdfContent)]);
        }
    }
}


}