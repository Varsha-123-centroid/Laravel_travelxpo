<?php
namespace App\Http\Controllers\Api;
use App\Http\Library\ApiHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use app\Helpers\CcAcripto;
use Carbon\Carbon;



class CcaRequestController extends Controller
{
    use ApiHelpers; // <---- Using the apiHelpers Trait

  public function billing_details(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'amount' => 'required',
            'billing_name' => 'required',
            'billing_address' => 'required',
            'billing_city' => 'required',
            'billing_state' => 'required',
            'billing_zip' => 'required',
            'billing_country' => 'required',
            'billing_tel' => 'required',
            'billing_email' => 'required|email',
            // Add other validation rules as needed
        ]);

        // Process the payment or any other logic here

        // You can send a success response with the received data
        return response()->json(['message' => 'Please do Customer Details ', 'data' => $validatedData], 201);
    }
public function handleCcavenueRedirect(Request $request)
{
	    $workingKey = '76F08E97FED563A85A91221D9CBF034C';

    // Get the encrypted response from the request
    $encResponse = $request->input('encResp');

    // Process the CCAvenue response
    $rcvdString = CcAcripto::decrypt($encResponse, $workingKey);
    $order_status = "";
	$order_id="";
	$tracking_id="";
	$bank_ref_no="";
    $decryptValues = explode('&', $rcvdString);
    $dataSize = sizeof($decryptValues);

    $response = array();

    for ($i = 0; $i < $dataSize; $i++) {
        $information = explode('=', $decryptValues[$i]);
		if($i==0)	$order_id=$information[1];
		if($i==1)	$tracking_id=$information[1];
		if($i==2)	$bank_ref_no=$information[1];
        if ($i == 3) $order_status = $information[1];
    }
DB::table('order_payments')->insert([
    'order_id' => $order_id,
    'tracking_id' => $tracking_id,
    'bank_ref_no' => $bank_ref_no,
    'order_status' => $order_status,
]);

    if ($order_status === "Success") {
        $response['message'] = "Thank you for booking with us. Your credit card has been charged and your transaction is successful.";
    } else if ($order_status === "Failure") {
        $response['message'] = "Thank you for booking with us. However, the transaction has been declined.";
    } else {
        $response['message'] = "Security Error. Illegal access detected";
    }

   /*  $response['data'] = array();
    for ($i = 0; $i < $dataSize; $i++) {
        $information = explode('=', $decryptValues[$i]);
        $response['data'][$information[0]] = $information[1];
    } */
	
	$encryptionKey = hex2bin("00112233445566778899aabbccddeeff");            //random_bytes(32); // Generate a random 16-byte key
$encodedData  = CcAcripto::customEncrypt($order_id, $encryptionKey);
$encryptedOrderId = urlencode($encodedData);
return redirect('https://travelxpo.in/paymentSuccess?status=' . $order_status . '&orderId=' . $encryptedOrderId);
	
}

public function verifyOrder(Request $request)
{
    $encryptedOrderId = $request->input('encryptedOrderId');
    $mailId = $request->input('mailId');
    $encryptionKey =  hex2bin("00112233445566778899aabbccddeeff");   
    $decodedData = urldecode($encryptedOrderId);
	
    // Decrypt the encryptedOrderId to get the original order_id
    $order_id = CcAcripto::customDecrypt($decodedData,$encryptionKey);

    // Check if the order exists in the order_payments table
    $orderPayment = DB::table('order_payments')->where('order_id', $order_id)->first();

    if ($orderPayment) {
        // Update the 'mail_id' field using DB
        DB::table('order_payments')
            ->where('order_id', $order_id)
            ->update(['email_id' => $mailId]);

        // Return a verification message
        return response()->json(['message' => 'Order verified and updated successfully','status' => 1]);
    } else {
        // Order not found
        return response()->json(['message' => 'Order not found','status' => 0], 404);
    }
}
public function generateToken(Request $request)
{
    // Check if a token for today's date exists
    $today = Carbon::today();
    $existingToken = DB::table('token')
        ->whereDate('token_created_date', $today)
        ->select('token_name')
        ->first();

    if ($existingToken) {
        // If a token for today already exists, return it
        return response()->json(['token' => $existingToken->token_name]);
    } else {
    // If the token doesn't exist for today's date, create a new token
    $ip_address = $_SERVER['REMOTE_ADDR'];
    $url = 'http://api.tektravels.com/SharedServices/SharedData.svc/rest/Authenticate';
    $data = [
        'ClientId' => 'ApiIntegrationNew',
        'UserName' => 'Travelx',
        'Password' => 'Travelx@1234',
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
    
    if ($response === false) {
        // Handle cURL error
        return response()->json(['error' => 'Failed to make API request.']);
    }
    
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code !== 200) {
        // Handle non-200 HTTP response
        return response()->json(['error' => 'API request returned an error.']);
    }
    
    $result = json_decode($response, true);
    
    if (empty($result['TokenId'])) {
        // Handle missing TokenId in API response
        return response()->json(['error' => 'TokenId not found in API response.']);
    }
    
    // Delete the last token if it exists
    $lastToken = DB::table('token')
        ->orderBy('token_created_date', 'desc')
        ->first();
    
    if ($lastToken) {
        DB::table('token')->where('id', $lastToken->id)->delete();
    }
    
    $tokenId = $result['TokenId'];
    
    // Create or update the token record
    DB::table('token')->insert([
        'token_name' => $tokenId,
        'token_created_date' => now(), // Replace with the actual date
    ]);
    
    // Return the generated token
    return response()->json(['token' => $tokenId], 201);
}
}
      public function handleRequest(Request $request)
    {
		$currentDatetime = date('YmdHis');

// Generate three random numbers between 100 and 999.
$randomNumbers = mt_rand(100, 999);

// Combine the current datetime and random numbers to create the order_id.
$order_id = $currentDatetime . $randomNumbers;
        // Gather the input data from the request
        $data = [
            'tid' => '',
            'merchant_id' => 953288,
            'order_id' => $order_id ,
            'amount' => $request->input('amount'),
            'currency' => 'INR',
            'redirect_url' => 'https://b2b.travelxpo.in/api/ccavenue/callback',
            'cancel_url' => 'https://b2b.travelxpo.in/api/ccavenue/callback',
            'language' => 'EN',
			'billing_name' => $request->input('billing_name'),
			'billing_address' => $request->input('billing_address'),
			'billing_city' => $request->input('billing_city'),
			'billing_state' => $request->input('billing_state'),
			'billing_zip' => $request->input('billing_zip'),
			'billing_country' => $request->input('billing_country'),
			'billing_tel' => $request->input('billing_tel'),
			'billing_email' => $request->input('billing_email'),
				
            // Add other fields as needed
        ]; 

 // Now, generate the encrypted data and access code
    $working_key = '76F08E97FED563A85A91221D9CBF034C'; // Shared by CCAVENUES
    $access_code = 'AVFA84JF69AB91AFBA'; // Shared by CCAVENUES

    // Create the merchant data string
    $merchant_data = '';
    foreach ($data as $key => $value) {
        $merchant_data .= $key . '=' . $value . '&';
    }

    // Encrypt the merchant data
    $encrypted_data = CcAcripto::encrypt($merchant_data, $working_key); // Method for encrypting the data

    // Prepare the response data
    $responseData = [
        'encrypted_data' => $encrypted_data,
        'access_code' => $access_code,
    ];

    // Return the response as JSON
    return response()->json($responseData);

        // Make a POST request to ccavRequestHandler.php
     /*  $response = Http::post('http://3.108.180.21/travelexpo/public/PHP_Kit/IFRAME_KIT/ccavRequestHandler.php', $data);
	   if ($response->successful()) {
        echo "yes corerect";
    } else {
        echo "not correct";
    }*/

      
    }

  public function get_airport(Request $request)
    {
		$airports = DB::table('iata_code')
        ->select('airport_code as code', 'location as name', 'countries.country_name as country')
        ->join('countries', 'iata_code.country_code', '=', 'countries.ctrycod')
        ->get();

    return response()->json($airports);
	}
  public function getMarkupData(Request $request)
    {
        $branchid = $request->input('branchid');

        $result = $this->getDataRecursively($branchid);
        
        if ($result) {
            return response()->json($result);
        } else {
            return response()->json(['message' => 'No data found for the given branchid'], 404);
        }
    }
private function getDataRecursively($branchid) {
    $markupData = DB::table('markup_percent')
    ->where('branchid', $branchid)
    ->where('status', 1) // This condition filters for 'status' equal to 1
    ->select('branchid', 'markup_type', 'markup_percent', 'branch_markup', 'agent_type')
    ->first();


    if (!$markupData) {
        return null;
    }

    $branch = DB::table('branch')
        ->where('id', $branchid)
        ->select('id', 'parent_id')
        ->first();

    if (!$branch) {
        return null;
    }

    $parentid = $branch->parent_id;

    $result = [
        'markup_data' => $markupData,
    ];

    if ($parentid) {
        $parentMarkupData = $this->getDataRecursively($parentid);
        if ($parentMarkupData) {
            $result[] = $parentMarkupData;
        }
    }

    return array_values($result);
}
public function get_city(Request $request)
    {
		try {
        $cities = DB::table('z_hotel_city')
            ->select('cityid as code', 'destination as name', 'country as country')
            ->get();

        return response()->json($cities);
    } catch (\Exception $e) {
        // Log the error
        \Log::error($e);

        // Return an error response
        return response()->json(['error' => 'An error occurred.'], 500);
    }
	}
public function getUserData(Request $request)
{
    // Fetch user_type and user_id from the branch table based on the provided branchId
    $branchId = $request->input('branchId');
$branch = DB::table('branch')->find($branchId);
    if (!$branch) {
        return response()->json(['error' => 'Branch not found'], 404);
    }

    $userType = $branch->user_type;
    $userId = $branch->user_id;

    // Fetch user data based on user_type using Query Builder
    if ($userType === 'main') {
        $userData = DB::table('agents')->where('id', $userId)->first();
    } elseif ($userType === 'sub') {
        $userData = DB::table('sub_agents')->where('id', $userId)->first();
    } 
	elseif ($userType === 'txpo') {
        $userData = DB::table('travelxpo_admin')->where('id', $userId)->first();
    }else {
        return response()->json(['error' => 'Invalid user type'], 400);
    }

    if (!$userData) {
        return response()->json(['error' => 'User not found'], 404);
    }

    return response()->json($userData);
}
 public function getTicketsAfterToday(Request $request)
    {
	 $request->validate([
        'email' => 'required|email',
    ]);

    $currentTime = now();
    $bookings = DB::table('passenger_bookings')
        ->where('departure_datetime', '>', $currentTime)
        ->where('email', $request->input('email'))
		->where('isLCC', 1)
       // ->whereNull('cancel_status') // Use whereNull for checking null values
        ->get();

    return response()->json($bookings);
    }
public function getTicketDetails(Request $request)
    {
		$pnr = $request->input('pnr');
$bookingId = $request->input('booking_id');
$tokenId = DB::table('token')
    ->whereDate('token_created_date', '=', date('Y-m-d'))
    ->value('token_name');
$ip_address = $_SERVER['REMOTE_ADDR'];

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
} else {
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($httpCode !== 200) {
        echo 'HTTP Error: ' . $httpCode;
    } else {
        // Continue with decoding and processing the response
        $result = json_decode($response, true);

        // Check if the decoding was successful
        if ($result === null) {
            echo 'Error decoding JSON: ' . json_last_error_msg();
        } else {
            // Check if 'Response' and 'FlightItinerary' exist in the JSON
            if (isset($result['Response']['FlightItinerary'])) {
                $flightItinerary = $result['Response']['FlightItinerary'];
                $passengers = $flightItinerary['Passenger'];

                // Return the extracted data as a JSON response
                return response()->json($passengers);
            } else {
                echo 'Response or FlightItinerary not found in the JSON.';
            }
        }
    }

    // Close the cURL session
    curl_close($ch);
}
	}	
public function storePassengerDetails(Request $request)
    {
			$email = $request->input('email');
			$mobile = $request->input('mobile');
			$comments = $request->input('comments');
            $pnr = $request->input('pnr');
            $bookingId = $request->input('booking_id');
            $passengerDetailsArray = $request->input('ticketdata');
        $branchid = 1;
		try {
        foreach ($passengerDetailsArray as $passengerDetails) {
            DB::table('messages_passengers')->insert([
                'passengername' => $passengerDetails['passengerName'],
                'ticketid' => $passengerDetails['ticket_id'], // adjust field name accordingly
                'messageid' => $branchid,
                'status' => 'pending',
                'comments' => $comments,
                'ticket_number' => $passengerDetails['ticketNumber'],
                'pnr_number' => $pnr,
                'mobile_number' => $mobile,
                'email' => $email,
                'booking_id' => $bookingId,
                'request_date' => now()->toDateString(),
            ]);
        }

        return response()->json(['success' => true], 200);
		} catch (\Exception $e) {
		  \Log::error('Exception Message: ' . $e->getMessage());
        \Log::error('Exception Code: ' . $e->getCode());
        \Log::error('Exception File: ' . $e->getFile());
        \Log::error('Exception Line: ' . $e->getLine());
        \Log::error('Exception Trace: ' . $e->getTraceAsString());

        // Attempt to get the SQL query information from the exception (if it's a PDOException)
        $sqlQuery = '';
        if ($e instanceof \PDOException) {
            $errorInfo = $e->errorInfo ?? [];
            $sqlQuery = isset($errorInfo[2]) ? $errorInfo[2] : '';
        }

        // Include the SQL query information in the response
        return response()->json(['error' => 'Internal Server Error', 'sql_query' => $sqlQuery], 500);
		}
    }	
	public function getTicketsComments(Request $request)
    {
			$pnr = $request->input('pnr');
            $bookingId = $request->input('booking_id');
        $currentTime = now();
        $bookings = DB::table('messages_comments')
		->where('pnr', $pnr)
		->where('booking_id', $bookingId)
		->orderBy('id', 'desc')
        ->get();
        return response()->json($bookings);
    }
	public function getCancels(Request $request)
    {
		$pnr = $request->input('pnr');
        $bookingId = $request->input('booking_id');
        $currentTime = now();
        $bookings = DB::table('messages_passengers')
		->where('pnr_number', $pnr)
		->where('booking_id', $bookingId)
        ->get();
        return response()->json($bookings);
    }
	public function saveTicketComments(Request $request)
    {
		$pnr = $request->input('pnr');
        $bookingId = $request->input('booking_id');
		$comments = $request->input('comments');

        DB::table('messages_comments')->insert([
				'pnr' => $pnr,
                'booking_id' => $bookingId,
                'customer_comments' => $comments,
                'customer_date' => now()->toDateString(),
            ]);
        return response()->json(['success' => true], 200);
    }
}
