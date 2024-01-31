<?php
namespace App\Http\Controllers\Api;
use App\Http\Library\ApiHelpers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http; // Add this use statement
use Illuminate\Support\Facades\Session;


class B2cController extends Controller
{
    use ApiHelpers; // <---- Using the apiHelpers Trait

public function getPairQuotes(Request $request)
{  
    // Fetch the token from the database or wherever you store it
    $tokenId = 'eacb5720-e2a0-4672-bf6b-96035cba8687';

   

    // Extract data from the request (modify these as needed)
   // Extract data from the request (modify these as needed)
$resultIndex = $request->input('ResultIndex'); // Example of retrieving 'ResultIndex' from the request
$agentId = $request->input('agentId'); // Example of retrieving 'agentId' from the request
$branchId = $request->input('branchId'); // Example of retrieving 'branchId' from the request
$markup = $request->input('markup'); // Example of retrieving 'markup' from the request
$totalBookingFare = $request->input('totalBookingFare'); // Example of retrieving 'totalBookingFare' from the request
$flightCharge = $request->input('flightcharge'); // Example of retrieving 'flightcharge' from the request
$adultCount = $request->input('adultCount'); // Example of retrieving 'adultCount' from the request
$childCount = $request->input('childCount'); // Example of retrieving 'childCount' from the request
$infantCount = $request->input('infantCount'); // Example of retrieving 'infantCount' from the request
$traceId = $request->input('TraceId'); // Example of retrieving 'TraceId' from the request
$passengers = $request->input('Passengers', []); // Note the uppercase 'P' in 'Passengers'

    $ipAddress = $request->ip(); // Use Laravel's request object to get the IP address

    // Ensure "Passengers" is an array, or convert it to an array if it's not
    if (!is_array($passengers)) {
        $passengers = [$passengers];
    }

    // Store values in the session
    Session::put("IP", $ipAddress);
    Session::put("TraceId", $traceId);
    Session::put("ob", $resultIndex);
    session(['ob' => $resultIndex]);
	session(['IP' => $ipAddress]);
session(['TokenId' => $tokenId]);
session(['TraceId' => $traceId]);

    // Set the API endpoint URL
    $url = "http://api.tektravels.com/BookingEngineService_Air/AirService.svc/rest/FareQuote/";

    // Set the JSON data to be sent to the API
    $data = [
        "TokenId" => $tokenId,
        "EndUserIp" => $ipAddress,
        "TraceId" => $traceId,
        "ResultIndex" => $resultIndex
    ];

    // Send the HTTP POST request to the API and handle the response
    $response = Http::post($url, $data);

    // Check if there was an error with the HTTP request
    if ($response->failed()) {
        // Handle the error (for example, log it)
        return response()->json(['error' => 'API request failed'], 500);
    }

    // Process the response JSON
   $myObject = json_decode($response);

    // Check if the 'Response' and 'Results' keys exist in the API response
  if (isset($myObject->Response) && isset($myObject->Response->Results)) {
        // Access 'Results' key and process data
       $myObjectMap = $myObject->Response->Results;

        // Access other data as needed
       $fare = $myObjectMap->Fare;
    $OtherCharges = $fare->OtherCharges;
    $Discount = $fare->Discount;
    $PublishedFare = $fare->PublishedFare;
    $CommissionEarned= $fare->CommissionEarned;
    $IncentiveEarned = $fare->IncentiveEarned;
    $PLBEarned = $fare->PLBEarned;
    $OfferedFare = $fare->OfferedFare;
    $TdsOnCommission= $fare->TdsOnCommission;
    $TdsOnPLB = $fare->TdsOnPLB;
    $TdsOnIncentive= $fare->TdsOnIncentive;
    $ServiceFee = $fare->ServiceFee;
        $adult = [];
        $child = [];
        $infant = [];

       foreach ($myObjectMap->FareBreakdown as $val) {
    if ($val->PassengerType == 1) {
        $adult["count"] = $val->PassengerCount;
        $adult["BaseFare"] = $val->BaseFare;
        $adult["Tax"] = $val->Tax;
        $adult["YQTax"] = $val->YQTax;
        $adult["AdditionalTxnFeeOfrd"] = $val->AdditionalTxnFeeOfrd;
        $adult["AdditionalTxnFeePub"] = $val->AdditionalTxnFeePub;
        $adult["PGCharge"] = $val->PGCharge;
    } elseif ($val->PassengerType == 2) {
        $child["count"] = $val->PassengerCount;
        $child["BaseFare"] = $val->BaseFare;
        $child["Tax"] = $val->Tax;
        $child["YQTax"] = $val->YQTax;
        $child["AdditionalTxnFeeOfrd"] = $val->AdditionalTxnFeeOfrd;
        $child["AdditionalTxnFeePub"] = $val->AdditionalTxnFeePub;
        $child["PGCharge"] = $val->PGCharge;
    } elseif ($val->PassengerType == 3) {
        $infant["count"] = $val->PassengerCount;
        $infant["BaseFare"] = $val->BaseFare;
        $infant["Tax"] = $val->Tax;
        $infant["YQTax"] = $val->YQTax;
        $infant["AdditionalTxnFeeOfrd"] = $val->AdditionalTxnFeeOfrd;
        $infant["AdditionalTxnFeePub"] = $val->AdditionalTxnFeePub;
        $infant["PGCharge"] = $val->PGCharge;
    }
}
$ps=[];
	for ($i = 1; $i < $adult; ++$i) {
    // Directly assign passenger data to the array
    $ps[] = [
        "Title" => "Haris",
        "FirstName" => "Foumi",
        "LastName" => "xxx",
        "PaxType" => 1,
        "DateOfBirth" => "1980-09-13T00:00:00",
        "Gender" => 2,
        "PassportNo" => 2434534543,
        "PassportExpiry" => "2026-09-08T00:00:00",
        "AddressLine1" => "address1",
        "AddressLine2" => "address2",
        // ... (other passenger data)

        // Fare data
        "Fare" => [
            "Currency" => "INR",
            "PassengerType" => 1,
            "BaseFare" => 40000,
            "Tax" => 0,
            // ... (other fare data)
        ],

        "City" => "Gurgaon",
        "CountryCode" => "IN",
        "CountryName" => "India",
        "Nationality" => "IN",
        "ContactNo" => '9605909377',
        "Email" => 'foumidaharis2011@gmail.com',
        "IsLeadPax" => '',
        "FFAirlineCode" => null,
        "FFNumber" => '',
        "GSTCompanyAddress" => "",
        "GSTCompanyContactNumber" => "",
        "GSTCompanyName" => "",
        "GSTNumber" => "",
        "GSTCompanyEmail" => "",
    ];
}
if ($myObjectMap->IsLCC === false) {
  
    // Set the results that you want to pass to the ticket booking
  $postData = [
    "ResultIndex" => session('ob'),
    "Passengers" => $ps,
    "EndUserIp" => session('IP'),
    "TokenId" => session('TokenId'),
    "TraceId" => session('TraceId'),
];
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://api.tektravels.com/BookingEngineService_Air/AirService.svc/rest/Book/',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($postData),
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
        ),
    ));

    $response = curl_exec($curl);
dd($response);
    curl_close($curl);

    $myObject = json_decode($response);
    $myObjectbooking = $myObject->Response->Response;

    if (!empty($myObjectbooking)) {
        $bookingId = $myObjectbooking->BookingId;
        $_SESSION["bookingId"] = $bookingId;
        $pnr = $myObjectbooking->PNR;
        $_SESSION["pnr"] = $pnr;
        $_SESSION['myObjectbooking'] = $myObjectbooking;
    }

    // Return the booking details
    return response()->json($myObjectbooking);
} else {
    // Handle the case where IsLCC is not true
    // ...
}

        // Now you can work with the data as needed

        // Example: Return a JSON response
      //  return response()->json(['adult' => $adult, 'child' => $child, 'infant' => $infant]);
    } else {
        // Handle the case where 'Results' key is not present in the API response
        return response()->json(['error' => 'Results key not found in API response'], 500);
    }
}

  
}
