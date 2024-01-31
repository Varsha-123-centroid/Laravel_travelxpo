<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Str;

class AdminController extends Controller
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
   public function index(Request $request)
{

    $user = Auth::user();
    $email = $user->email;
	$username = $user->username;
    $agentid = $user->agentid;
    $agenttype = $user->agent_type;
if ($agenttype == 'main') {
    // Assuming you have a table named 'agents'
    $agentData = DB::table('agents')
        ->where('id', $agentid)
        ->first();

    if ($agentData) {
        $agentCode = $agentData->agent_code;
    }
} elseif ($agenttype == 'sub') {
    // Assuming you have a table named 'subagents'
    $subAgentData = DB::table('sub_agents')
        ->where('id', $agentid)
        ->first();

    if ($subAgentData) {
        $agentCode = $subAgentData->agent_code;
    }
} elseif ($agenttype == 'txpo') {
    $agentCode = 'txpo';
}

    // Retrieve the branch ID based on user's agent type and ID
    $branchId = DB::table('branch')
        ->where('user_type', $agenttype)
        ->where('user_id', $agentid)
        ->value('id');

    // Retrieve bookings for the branch
    /*$bookings = DB::table('passenger_bookings')
        ->where('branch_id', $branchId)
        ->where('total_ticket_fare', '>', 0)
        ->get();*/
	 if ($request->isMethod('post')) {
		 $fromDate = $request->input('fromDate');
            $toDate = $request->input('toDate');

            // Handle the form submission query with date range
            $bookings = DB::table(DB::raw("
                (SELECT passenger_bookings.*, customers.id AS customer_id, customers.customer_name, customers.customer_gst
                FROM passenger_bookings
                LEFT JOIN customers ON passenger_bookings.branch_id = customers.branch_id
                WHERE passenger_bookings.branch_id = $branchId
                AND passenger_bookings.cust_id = customers.id
                AND passenger_bookings.total_ticket_fare > 0
                AND DATE(passenger_bookings.booking_date) BETWEEN '$fromDate' AND '$toDate'
                UNION
                SELECT passenger_bookings.*, 1 AS customer_id, 'CASH' AS customer_name, '' AS customer_gst
                FROM passenger_bookings
                WHERE passenger_bookings.branch_id = $branchId
                AND passenger_bookings.cust_id = 1
                AND passenger_bookings.total_ticket_fare > 0
                AND DATE(passenger_bookings.booking_date) BETWEEN '$fromDate' AND '$toDate') as xxx"))
                ->orderByDesc('xxx.id')
                ->get();
        } else {
$bookings = DB::table(DB::raw("(SELECT passenger_bookings.*, customers.id AS customer_id, customers.customer_name, customers.customer_gst
    FROM passenger_bookings
    LEFT JOIN customers ON passenger_bookings.branch_id = customers.branch_id
    WHERE passenger_bookings.branch_id = $branchId
    AND passenger_bookings.cust_id = customers.id
    AND passenger_bookings.total_ticket_fare > 0
    UNION
    SELECT passenger_bookings.*, 1 AS customer_id, 'CASH' AS customer_name, '' AS customer_gst
    FROM passenger_bookings
    WHERE passenger_bookings.branch_id = $branchId
    AND passenger_bookings.cust_id = 1
    AND passenger_bookings.total_ticket_fare > 0) as xxx"))
    ->orderByDesc('xxx.id')
    ->get();
		}


$customers = DB::table('customers')
    ->where('branch_id', $branchId)
    ->get();

    // Retrieve the latest available balance for the branch
    $avilablebal = DB::table('cash_balance')
        ->where('branch_id', '=', $branchId)
        ->orderBy('id', 'desc')
        ->value('balance');

    // Convert the email to base64 encoding
    $base64Email = base64_encode($email);

    // Pass the data to the view
    return view('admin.dashboard', compact('base64Email', 'branchId', 'bookings', 'avilablebal','agentCode','username','customers','branchId'));
}
 public function get_customers(Request $request)
    {
		$branchId = $request->input('branchId');
        $customerId = $request->input('customerId');
		$passengerid = $request->input('PassengerId');
		
		// Update the passenger booking with the selected customer ID
       

        $affectedRows =  DB::table('passenger_bookings')
            ->where('branch_id', $branchId)
			->where('id',$passengerid)
            ->update(['cust_id' => $customerId]);

    // Check if the update was successful
    if ($affectedRows > 0) {
        // The PNR was updated successfully
        return response()->json(['success' => true, 'message' => 'Passengers updated successfully']);
    } else {
        // Handle the case where the PNR was not updated (e.g., no changes made)
        return response()->json(['success' => false, 'message' => 'No changes done']);
    }
    }
	
	    public function logout()
    {
		
        Auth::logout();

        return redirect()->route('login');
    }
	// Manage Operation Staff //
	
	   public function operationStaff()
    {
		$countries = DB::table('countries')->select('id', 'country_name')->get();
        $currency = DB::table('currency')->select('id', 'currency_code')->get();
		$timezones = timezone_identifiers_list();
		
        return view('admin.operationStaff',compact('countries','currency','timezones'));
    }
	public function operationSave(Request $request)
{
	  $user = Auth::user();
   
    $agentid = $user->agentid;
    $agenttype = $user->agent_type;
	 $branchId = DB::table('branch')
                ->where('user_type', $agenttype)
                ->where('user_id', $agentid)
                ->value('id');
    // Get form data from the request
    $formData = $request->all();

    // Extract username and password from the form data
    $username = $formData['txt_username'];
    $password =  Hash::make($formData['txt_password_text']);
	$agencymail = $formData['txt_agency_email'];
   // Define the determineRole() function here
   // Determine role based on checkbox values
   // Permission Comments
    // 1: Can Book
    // 2: Can Cancel
    // 3: Unused (Combination of Can Book and Can Cancel)
    // 4: Can View All Bookings
    // 5: Unused (Combination of Can Book and Can View All Bookings)
    // 6: Unused (Combination of Can Cancel and Can View All Bookings)
    // 7: Unused (Combination of Can Book, Can Cancel, and Can View All Bookings)
    // 8: Can Voucher
    // 9: Unused (Combination of Can Book and Can Voucher)
    // 10: Unused (Combination of Can Cancel and Can Voucher)
    // 11: Unused (Combination of Can Book, Can Cancel, and Can Voucher)
    // 12: Unused (Combination of Can View All Bookings and Can Voucher)
    // 13: Unused (Combination of Can Book, Can View All Bookings, and Can Voucher)
    // 14: Unused (Combination of Can Cancel, Can View All Bookings, and Can Voucher)
    // 15: Can Book, Can Cancel, Can View All Bookings, and Can Voucher
   
   
    $role = 0; // Default role (no permissions)

    // Check if "can_voucher" checkbox is selected
    if (in_array('can_voucher', $formData['rights'])) {
        $role += 8; // Can Voucher
    }

    // Check if other checkboxes are selected
    if (in_array('all_bookings', $formData['rights'])) {
        $role += 4; // Can View All Bookings
    }
    if (in_array('can_cancel', $formData['rights'])) {
        $role += 2; // Can Cancel
    }
    if (in_array('can_book', $formData['rights'])) {
        $role += 1; // Can Book
    }

    // Save the operation_staff data
    $operation_staff = DB::table('operation_staff')->insertGetId([
         'first_name' => $formData['txt_agency_fname'],
         'email' => $formData['txt_agency_email'],
         'last_name' => $formData['txt_agency_lname'],
        'designation' => $formData['txt_agency_designation'],
        'address' => $formData['txt_address'],
        'country_id' => $formData['sel_country'],
        'city' => $formData['sel_city'],
        'postal_code' => $formData['txt_pincode'],
        'mobile_number' => $formData['txt_mobile'],
        'status' => $formData['sel_status'],
		'username' => $formData['txt_username'],
		'password' => $password,
		'view_password' => $formData['txt_password_text'],
	    'role' => $role, // Assign the determined role
		'agentid' =>  $agentid,
	    'agenttype' => $agenttype, 
        'created_at'  => Carbon::now(),
		'updated_at'  => Carbon::now(),
        
    ]);

  DB::table('users')->insert([
        'username' => $username,
        'password' => $password,
		'name' => $formData['txt_agency_fname'],
        'role' => 2,
        'agent_type' => $agenttype,
		'status_type' => 'user',
		'branch_id' => $branchId,
        'agentid' => $agentid,
		'email'  => $agencymail,
		'operation_staffid'  => $operation_staff,
		'created_at'  => Carbon::now(),
		'updated_at'  => Carbon::now(),
    ]);

    // Redirect or return a response
    // You can modify this based on your application's requirements
  return response()->json(['success' => 'Operation Staff added successfully']);
}

public function operationStaffList()
	{ 
	 $agentid = Auth::user()->agentid;
    $agenttype = Auth::user()->agent_type;
	$stafflist = DB::table('operation_staff')
    ->select('first_name', 'mobile_number', 'email', 'id')
    ->where('status', 1)
	->where('agentid', '=', $agentid)
	->where('agenttype', '=', $agenttype)
    ->get();
    return view('admin.operationStaffList', compact('stafflist'));
	}
		public function operationStaffEdit(Request $request)
	{
		$id = $request->id;
		$staffData = DB::table('operation_staff')
    ->where('id', '=', $id)
    ->first();

$userRole = DB::table('operation_staff')
        ->where('id', $id)
        ->value('role');
       
		$countries = DB::table('countries')->select('id', 'country_name')->get();
     
	return view('admin.operationStaffEdit', compact('staffData','countries','userRole'));
	}
	public function operationStaffUpdate(Request $request)
	{
	// Get form data from the request
    $formData = $request->all();

    $staffId = $formData['editid'];

    // Process the uploaded images
   
   // Determine role based on checkbox values
    $role = 0; // Default role (no permissions)

    // Check if "can_voucher" checkbox is selected
    if (in_array('can_voucher', $formData['rights'])) {
        $role += 8; // Can Voucher
    }

    // Check if other checkboxes are selected
    if (in_array('all_bookings', $formData['rights'])) {
        $role += 4; // Can View All Bookings
    }
    if (in_array('can_cancel', $formData['rights'])) {
        $role += 2; // Can Cancel
    }
    if (in_array('can_book', $formData['rights'])) {
        $role += 1; // Can Book
    }

    
    DB::table('operation_staff')
    ->where('id', $staffId)
    ->update([
      'first_name' => $formData['txt_agency_fname'],
         'email' => $formData['txt_agency_email'],
         'last_name' => $formData['txt_agency_lname'],
        'designation' => $formData['txt_agency_designation'],
        'address' => $formData['txt_address'],
        'country_id' => $formData['sel_country'],
        'city' => $formData['sel_city'],
        'postal_code' => $formData['txt_pincode'],
        'mobile_number' => $formData['txt_mobile'],
        'status' => $formData['sel_status'],
		
	    'role' => $role, // Assign the determined role
        'created_at'  => Carbon::now(),
		'updated_at'  => Carbon::now(),
    ]);
    // Save the user data


    // Redirect or return a response
    // You can modify this based on your application's requirements
  return response()->json(['success' => 'Operation Staff updated successfully']);
	}

///////////////////////////////////////////////////////////////
public function addCity()
    {
		
		$list = DB::table('city')
    ->select('id', 'city_name', 'city_code')
    ->orderBy('id', 'desc')
    ->get();

        return view('admin.addCity',compact('list'));
    }
	public function addCountry()
    {
		
		$list = DB::table('countries')
    ->select('id', 'country_name', 'ctrycod','country_code_number')
    ->orderBy('id', 'desc')
    ->get();

        return view('admin.addCountry',compact('list'));
    }
	
		public function citySave(Request $request)
{
    // Get form data from the request
    $formData = $request->all();

    // Save the AirlineLogo data
    $agentId = DB::table('city')->insertGetId([
        'city_name' => $formData['cityname'],
        'city_code' => $formData['citycode'],    
    ]);

   
 
  return response()->json(['success' => 'Airport City added successfully']);
}
	public function countrySave(Request $request)
{
    // Get form data from the request
    $formData = $request->all();

    // Save the AirlineLogo data
    $agentId = DB::table('countries')->insertGetId([
        'country_name' => $formData['countryname'],
        'ctrycod' => $formData['countrycode'],  
		'country_code' => $formData['countrycode'],  
        'country_code_number' => $formData['countrynumber'], 		
    ]);

   
 
  return response()->json(['success' => 'Country added successfully']);
}
	public function airportSave(Request $request)
{
    // Get form data from the request
    $formData = $request->all();

    // Save the AirlineLogo data
    $agentId = DB::table('iata_code')->insertGetId([
        'location' => $formData['location'],
        'airport_code' => $formData['airportcode'],  
		'country_code' => $formData['cntryid'],  
        'city_code' => $formData['cityid'], 		
    ]);

   
 
  return response()->json(['success' => 'Airport added successfully']);
}
 public function addAirlines()
    {
		
		$airlinesList= DB::table('airlines')
    ->select('id', 'airline_name', 'airline_code', 'airline_logo')
    ->orderBy('id', 'desc')
    ->get();

        return view('admin.addAirlines',compact('airlinesList'));
    }
	
		public function fetchAirlineData(Request $request)
	{
		$id = $request->recordId;
		$data = DB::table('airlines')
    ->where('id', '=', $id)
    ->first();
	//public_path('uploads/AirlineLogo/');
	
     return response()->json($data);
	}
	 public function addAirport()
    {
		
		$list= DB::table('iata_code')
    ->select('id', 'location', 'airport_code', 'country_code','city_code')
    ->orderBy('id', 'desc')
    ->get();

        return view('admin.addAirport',compact('list'));
    }
   public function deleteAirlines(Request $request)
    {
		$id=$request->recordId;
		
        try {
            // Delete the airline using the DB facade
            DB::table('airlines')->where('id', $id)->delete();

            // Optionally, you can return a success response
            return response()->json(['message' => 'Airline deleted successfully'], 200);
        } catch (\Exception $e) {
            // Handle any exceptions or errors that may occur during deletion
            return response()->json(['error' => 'Error deleting airline'], 500);
        }
    }
	 public function deleteAirport(Request $request)
    {
		$id=$request->recordId;
		
        try {
            // Delete the airline using the DB facade
            DB::table('iata_code')->where('id', $id)->delete();

            // Optionally, you can return a success response
            return response()->json(['message' => 'Airport deleted successfully'], 200);
        } catch (\Exception $e) {
            // Handle any exceptions or errors that may occur during deletion
            return response()->json(['error' => 'Error deleting Airport'], 500);
        }
    }
	public function autocompletecountry(Request $request)
{
    $query = $request->input('query');
 $filterResult = DB::select("SELECT ctrycod,country_name FROM countries where `ctrycod` LIKE '$query%' OR country_name LIKE '$query%' ");
          if (!empty($filterResult)) {
        $data = [];
        foreach ($filterResult as $val) {
            $data[] = array(
                "label" => $val->ctrycod . ',' . $val->country_name,
                "value" => $val->ctrycod,
            );
        }
      
    } else {
         $data = [
  'message'=> 'No Record Found'
] ;
  
    }
        return response()->json($data); 
}
	public function autocompletecity(Request $request)
{
    $query = $request->input('query');
 $filterResult = DB::select("SELECT city_code,city_name FROM city where `city_code` LIKE '$query%' OR city_name LIKE '$query%' ");
          if (!empty($filterResult)) {
        $data = [];
        foreach ($filterResult as $val) {
            $data[] = array(
                "label" => $val->city_code . ',' . $val->city_name,
                "value" => $val->city_code,
            );
        }
      
    } else {
         $data = [
  'message'=> 'No Record Found'
] ;
  
    }
        return response()->json($data); 
}
	  public function deleteCity(Request $request)
    {
		$id=$request->recordId;
		
        try {
            // Delete the airline using the DB facade
            DB::table('city')->where('id', $id)->delete();

            // Optionally, you can return a success response
            return response()->json(['message' => 'Airport City deleted successfully'], 200);
        } catch (\Exception $e) {
            // Handle any exceptions or errors that may occur during deletion
            return response()->json(['error' => 'Error deleting City'], 500);
        }
    }
	public function deleteCountry(Request $request)
    {
		$id=$request->recordId;
		
        try {
            // Delete the airline using the DB facade
            DB::table('countries')->where('id', $id)->delete();

            // Optionally, you can return a success response
            return response()->json(['message' => 'Countries deleted successfully'], 200);
        } catch (\Exception $e) {
            // Handle any exceptions or errors that may occur during deletion
            return response()->json(['error' => 'Error deleting Country'], 500);
        }
    }
	    public function addAgent()
    {
		$countries = DB::table('countries')->select('id', 'country_name')->get();
        $currency = DB::table('currency')->select('id', 'currency_code')->get();
		$timezones = timezone_identifiers_list();
		
        return view('admin.addAgent',compact('countries','currency','timezones'));
    }
	public function airlineSave(Request $request)
{
    // Get form data from the request
    $formData = $request->all();

    // Extract username and password from the form data
  
    // Process the uploaded images
   /*if ($request->hasFile('airline_logo')) {
    $file = $request->file('airline_logo');
    $filename = time() . '.' . $file->getClientOriginalExtension();
    $filePath = public_path('uploads/AirlineLogo/');
    $file->move($filePath, $filename);
} else {
    $filename = '';
}*/
// Process the uploaded images
if ($request->hasFile('airline_logo')) {
    $file = $request->file('airline_logo');
    $filename = $formData['airline_code'] . '.gif'; // Change the file extension to GIF
    $filePath = public_path('uploads/AirlineLogo/');
    $file->move($filePath, $filename);

    // Check the file extension of the uploaded image
    $extension = strtolower($file->getClientOriginalExtension());
    
    // Ensure it's a valid image format (e.g., jpg, jpeg, png)
    if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
        // Load the image based on its file type
        if ($extension === 'jpg' || $extension === 'jpeg') {
            $image = imagecreatefromjpeg($filePath . $filename);
        } elseif ($extension === 'png') {
            $image = imagecreatefrompng($filePath . $filename);
        }
        
        // Save the image as GIF
        imagegif($image, $filePath . $filename);
        
        // Clean up resources
        imagedestroy($image);
    }
} else {
    $filename = '';
}




    // Save the AirlineLogo data
    $agentId = DB::table('airlines')->insertGetId([
        'airline_name' => $formData['airlinename'],
        'airline_code' => $formData['airline_code'],
         'airline_logo' => $filename,
       
    ]);

    // Save the user data
   
  
 
  return response()->json(['success' => 'Airline added successfully']);
}

	
		    public function addCustomer()
    {
		$countries = DB::table('countries')->select('id', 'country_name')->get();
        $currency = DB::table('currency')->select('id', 'currency_code')->get();
		$timezones = timezone_identifiers_list();
		$pay_terms= DB::table('pay_terms')->select('terms_id', 'terms_name')->get();
		$cust_groups= DB::table('cust_groups')->select('cust_grp_id', 'cust_group_name')->get();

        return view('admin.addCustomer',compact('countries','currency','timezones','pay_terms','cust_groups'));
    }
	
public function agentSave(Request $request)
{
    // Get form data from the request
    $formData = $request->all();

    // Extract username and password from the form data
    $username = $formData['txt_username'];
    $password =  Hash::make($formData['txt_password_text']);
	$agencymail = $formData['txt_agency_email'];

    // Process the uploaded images
   if ($request->hasFile('agent_logo')) {
    $file = $request->file('agent_logo');
    $filename = time() . '.' . $file->getClientOriginalExtension();
    $filePath = public_path('uploads/agent/');
    $file->move($filePath, $filename);
} else {
    $filename = '';
}

   if ($request->hasFile('agent_image')) {
    $file = $request->file('agent_image');
    $filename1 = time() . '.' . $file->getClientOriginalExtension();
    $filePath = public_path('uploads/agent/');
    $file->move($filePath, $filename1);
} else {
    $filename1 = '';
}


    $iataStatus =  $formData['rad_iata_status']; 
    $accounttype = isset($formData['rad_accounttype']) ? $formData['rad_accounttype'] : 0;
    $distribution = isset($formData['rad_distribution']) ? $formData['rad_distribution'] : 0;

    // Save the agent data
    $agentId = DB::table('agents')->insertGetId([
        'company_name' => $formData['txt_agency_name'],
        'company_email' => $formData['txt_agency_email'],
        'company_reg_no' => $formData['txt_account_id'],
        'first_name' => $formData['txt_agency_fname'],
        'middle_name' => $formData['txt_agency_mname'],
        'last_name' => $formData['txt_agency_lname'],
        'designation' => $formData['txt_agency_designation'],
        'nature_of_business' => $formData['sel_nature_of_business'],
        'company_logo' => $filename,
        'profile_image' => $filename1,
        'address' => $formData['txt_address'],
        'country_id' => $formData['sel_country'],
        'city' => $formData['sel_city'],
        'postal_code' => $formData['txt_pincode'],
        'iata_status' => $iataStatus,
        'phonenumber' => $formData['txt_phone'],
        'mobile_number' => $formData['txt_mobile'],
        'fax_number' => $formData['txt_fax'],
        'website' => $formData['txt_website'],
        'agency_account_name' => $formData['txt_acc_name'],
        'agency_account_email' => $formData['txt_acc_email'],
        'agency_account_phoneno' => $formData['txt_acc_ph'],
        'agency_reservation_name' => $formData['txt_res_name'],
        'agency_reservation_email' => $formData['txt_res_email'],
        'agency_reservation_phoneno' => $formData['txt_res_ph'],
        'agency_mgt_name' => $formData['txt_mgt_name'],
        'agency_mgt_email' => $formData['txt_mgt_email'],
        'agency_mgt_ph' => $formData['txt_mgt_ph'],
        'agency_contactinfo_remarks' => $formData['txt_acc_remarks'],
        'currency' => $formData['sel_currency'],
        'time_zone' => $formData['sel_timezone'],
        'agent_money_type' => $accounttype,
        'credit_limit' => $formData['txt_credit'],
        'temp_credit_limit' => $formData['temp_credit'],
        'credit_distribution' => $distribution,
        'sales_manager' => $formData['txt_sales_mgr'],
        'consultant' => $formData['txt_consultant'],
		'business_vol_category' => $formData['sel_category'],
    ]);

   
	$insertedbranchId = DB::table('branch')->insertGetId([
        'user_type' => 'main',
        'branch_name' => $formData['txt_branch'],
        'user_id' => $agentId,
		'parent_type' => 'txpo',
		'parent_id' => 1,
		'invoice_prefix' => '2023-',
		'invoice_lastnumber' => 1000,
    ]);
  
   // Save the user data
    DB::table('users')->insert([
        'username' => $username,
        'password' => $password,
		'name' => $formData['txt_agency_name'],
        'role' => 2,
        'agent_type' => 'main',
		'status_type' => 'admin',
		'branch_id' => $insertedbranchId,
        'agentid' => $agentId,
		'email'  => $agencymail,
		'created_at'  => Carbon::now(),
		'updated_at'  => Carbon::now(),
    ]);
  
    $paymentid = DB::table('payment')->insertGetId([
    'branchid' => $insertedbranchId,
    'payment_amt' => $formData['txt_credit'],
    'payment_due' => 0,
    'status'=>0,
	'mode_of_payment'=>'credit limit',
	'suplier_category' => 1
   ]);
   
    DB::table('payment')
    ->where('id', $paymentid)
    ->update(['status' => 1]);
   
 $rights = [
    'multi_currency_search' => 'Multi Currency Search',
    'allow_create_quotations' => 'Allow Create Quotations',
    'allow_non_refundable_bookings' => 'Allow Non-Refundable bookings',
    'allow_voucher_bookings' => 'Allow Voucher bookings',
    'make_bookings' => 'Make Bookings',
    'debug_mode' => 'Debug Mode',
    'flights_allow_ticketing' => 'Flights Allow Ticketing',
	'web_services' => 'Web Services',
];

foreach ($rights as $key => $name) {
    $value = in_array($key, $request->input('rights', [])) ? 'yes' : 'no';

    DB::table('agent_rights')->insert([
        'agent_user_id' => $agentId,
        'right_name' => $name,
        'right_acess' => $value,
        'agent_type' => 'main',
    ]);
        }
    // Redirect or return a response
    // You can modify this based on your application's requirements
  return response()->json(['success' => 'Agent added successfully']);
}

 public function checkCustomername(Request $request)
    {
        $username = $request->input('username');
        
       $user = DB::table('customers')->where('username', $username)->first();
       
         if ($user) {
        return response()->json(['status' => 'exists', 'message' => 'Username already exists.']);
    }

    return response()->json(['status' => 'available', 'message' => 'Username is available.']);
    }
	
public function customerSave(Request $request)
{
	$user_id=Auth::User()->id;
	$string = Str::random(5);
	$code_time=time();
	$cust_code=$user_id.$string.$code_time;
	    // Get form data from the request
    $formData = $request->all();
  $agentid = Auth::user()->agentid;
	    $agenttype = Auth::user()->agent_type;
	   $branchId = DB::table('branch')
                ->where('user_type', $agenttype)
                ->where('user_id', $agentid)
                ->value('id');

    // Extract username and password from the form data
    $username = $formData['txt_username'];
    $password =  Hash::make($formData['txt_password_text']);

  
    // Save the agent data
    $agentId = DB::table('customers')->insertGetId([
		'username' => $username,
        'password' => $password,
		'branch_id'=>$branchId,
        'customer_name' => $formData['txt_agency_name'],
        'email' => $formData['txt_agency_email'],
                 'address' => $formData['txt_address'],
        'country_id' => $formData['sel_country'],
        'city' => $formData['sel_city'],
        'postal_code' => $formData['txt_pincode'],
         'mobile_number' => $formData['txt_mobile'],
        'postal_code' => $formData['txt_pincode'],
		'currency' => $formData['sel_currency'],
        'time_zone' => $formData['sel_timezone'],
        'opening_balance_credit' => $formData['opening_balace_credit'],
        'opening_balance_debit' => $formData['opening_balance_debit'],
        'markup_percent' => $formData['markup_percent'],
        'expiry_date' => $formData['expiry_date'],

	'contact_person' => $formData['contact_person'],
	'contact_desig' => $formData['contact_desig'],
        'contact_tel' => $formData['contact_tel'],
        'contact_mail' => $formData['contact_mail'],
	'customer_gst' => $formData['gst_no'],
        'credit_limit' => $formData['credit_limit'],
        'pay_terms_id' => $formData['pay_terms'],
        'cust_grp_id' => $formData['customer_group'],
	'cust_code' => $cust_code,


		'created_at'  => Carbon::now(),
		'updated_at'  => Carbon::now()
    ]);


    // Redirect or return a response
    // You can modify this based on your application's requirements
  return response()->json(['success' => 'Customer added successfully']);
}

 public function checkUsername(Request $request)
    {
        $username = $request->input('username');
        
       $user = DB::table('users')->where('username', $username)->first();
       
         if ($user) {
        return response()->json(['status' => 'exists', 'message' => 'Username already exists.']);
    }

    return response()->json(['status' => 'available', 'message' => 'Username is available.']);
    }
	 public function checkAirlineCode(Request $request)
    {
        $airlinecode = $request->input('airlinecode');
        
       $user = DB::table('airlines')->where('airline_code', $airlinecode)->first();
       
         if ($user) {
        return response()->json(['status' => 'exists', 'message' => 'AirlineCode already exists.']);
    }

    return response()->json(['status' => 'available', 'message' => 'AirlineCode is available.']);
    }
	 public function checkAirportCode(Request $request)
    {
        $airportcode = $request->input('airportcode');
        
       $user = DB::table('iata_code')->where('airport_code', $airportcode)->first();
       
         if ($user) {
        return response()->json(['status' => 'exists', 'message' => 'Airport already exists.']);
    }

    return response()->json(['status' => 'available', 'message' => 'Airport is available.']);
    }
	 public function checkCityCode(Request $request)
    {
        $citycode = $request->input('citycode');
        
       $user = DB::table('city')->where('city_code', $citycode)->first();
       
         if ($user) {
        return response()->json(['status' => 'exists', 'message' => 'CityCode already exists.']);
    }

    return response()->json(['status' => 'available', 'message' => 'CityCode is available.']);
    }
	 public function checkCountryCode(Request $request)
    {
        $countrycode = $request->input('countrycode');
        
       $user = DB::table('countries')->where('ctrycod', $countrycode)->first();
       
         if ($user) {
        return response()->json(['status' => 'exists', 'message' => 'CountryCode already exists.']);
    }

    return response()->json(['status' => 'available', 'message' => 'CountryCode is available.']);
    }
 public function showChangePassword()
    {   $user=Auth::User();
	    $oldemail=$user->email;
		$id=$user->id;
        return view('auth.passwords.reset',compact('oldemail','id'));
    }
	public function UpdatechangePassword(Request $request)
{
    // Get form data from the request
  //  $formData = $request->all();
	$user = Auth::user();

    // Check if the current password matches the user's password
   
        // Update the user's password
        $user->password = Hash::make($request->password_confirmation);
        $user->save();

        // Log out the user
        Auth::logout();

        // Redirect the user to the login page with a success message
        return redirect()->route('login')->with('success', 'Password updated successfully. Please log in with your new password.');
    
}
   public function adminProfile()
    {
		  $user=Auth::User();
	    $email=$user->email;
		$mobile=$user->mobile;
		$name=$user->name;
        return view('admin.adminProfile',compact('email','mobile','name'));
    }
	public function updateAdminProfile(Request $request)
	{
		$user = Auth::user(); // Assuming you are using Laravel's authentication

if ($user) {
    // Proceed with updating the user's profile
    $formData = $request->all();
    $name = $formData['name'] ?? null;
    $phone = $formData['phone'] ?? null;
    $email = $formData['email'];

    $user->name = $name;
    $user->email = $email;
    $user->mobile = $phone;
    $user->save();

    return response()->json(['success' => 'Admin Profile updated successfully']);
} else {
    return response()->json(['error' => 'User not found'], 404);
}
	}
	public function agentList()
	{ 
	$agents = DB::table('agents')->select('company_name', 'mobile_number','business_vol_category','id','status')->get();
    return view('admin.agentList', compact('agents'));
	}
		public function newAgentsList()
	{ 
	$newAgentsList = DB::table('agent_newregistration')->select('*')->get();
    return view('admin.newAgentsList', compact('newAgentsList'));
	}
		public function customersList()
	{ 
	 $agentid = Auth::user()->agentid;
	 $agenttype = Auth::user()->agent_type;
	   $branchId = DB::table('branch')->where('user_type', $agenttype)->where('user_id', $agentid)->value('id');

	$customers = DB::table('customers')->where('branch_id', $branchId )->select('*')->get();
    return view('admin.customersList', compact('customers'));
	}
	public function agentEdit(Request $request)
	{
		$id = $request->id;
		$agentData = DB::table('agents')
    ->where('id', '=', $id)
    ->first();

$branchData = DB::table('branch')
    ->where('user_type', '=', 'main')
    ->where('user_id', '=', $id)
    ->first();

$agentRightsData = DB::table('agent_rights')
    ->where('agent_user_id', '=', $id)
    ->where('agent_type', '=', 'main')
    ->get(); 
	
		$countries = DB::table('countries')->select('id', 'country_name')->get();
        $currency = DB::table('currency')->select('id', 'currency_code')->get();
		$timezones = timezone_identifiers_list();
	return view('admin.editAgent', compact('agentData','branchData','agentRightsData','countries','currency','timezones'));
	}
	
		public function customerEdit(Request $request)
	{
		$id = $request->id;
		$customerData = DB::table('customers')
    ->where('id', '=', $id)
    ->first();

	
		$countries = DB::table('countries')->select('id', 'country_name')->get();
        	$currency = DB::table('currency')->select('id', 'currency_code')->get();
		$timezones = timezone_identifiers_list();
		$pay_terms= DB::table('pay_terms')->select('terms_id', 'terms_name')->get();
		$cust_groups= DB::table('cust_groups')->select('cust_grp_id', 'cust_group_name')->get();


	return view('admin.editCustomers', compact('customerData','countries','currency','timezones','pay_terms','cust_groups'));
	}

	public function customerUpdate(Request $request)
	{
	// Get form data from the request
    $formData = $request->all();

    $customerId = $formData['editid'];

    // Process the uploaded images
   
 
    
    DB::table('customers')
    ->where('id', $customerId)
    ->update([
        'customer_name' => $formData['txt_agency_name'],
        'email' => $formData['txt_agency_email'],
        'address' => $formData['txt_address'],
        'country_id' => $formData['sel_country'],
        'city' => $formData['sel_city'],
        'postal_code' => $formData['txt_pincode'],
        'mobile_number' => $formData['txt_mobile'],
       'opening_balance_credit' => $formData['opening_balace_credit'],
        'opening_balance_debit' => $formData['opening_balance_debit'],
        'markup_percent' => $formData['markup_percent'],
        'expiry_date' => $formData['expiry_date'],
	'currency' => $formData['sel_currency'],
        'time_zone' => $formData['sel_timezone'],
	

	'contact_person' => $formData['contact_person'],
	'contact_desig' => $formData['contact_desig'],
        'contact_tel' => $formData['contact_tel'],
        'contact_mail' => $formData['contact_mail'],
	'customer_gst' => $formData['gst_no'],
        'credit_limit' => $formData['credit_limit'],
        'pay_terms_id' => $formData['pay_terms'],
        'cust_grp_id' => $formData['customer_group'],


		'updated_at'  => Carbon::now()

    ]);
    // Save the user data


    // Redirect or return a response
    // You can modify this based on your application's requirements
  return response()->json(['success' => 'Customer Details updated successfully']);
	}

	public function agentUpdate(Request $request)
	{
		 // Get form data from the request
    $formData = $request->all();

    $agentId = $formData['editid'];
	
    

    // Process the uploaded images
   if ($request->hasFile('agent_logo')) {
    $file = $request->file('agent_logo');
    $filename = time() . '.' . $file->getClientOriginalExtension();
    $filePath = public_path('uploads/agent/');
    $file->move($filePath, $filename);
} else {
    $filename = $formData['current_file_logo'];
}

   if ($request->hasFile('agent_image')) {
    $file = $request->file('agent_image');
    $filename1 = time() . '.' . $file->getClientOriginalExtension();
    $filePath = public_path('uploads/agent/');
    $file->move($filePath, $filename1);
} else {
    $filename1 = $formData['current_file_Image'];
}

   if (isset($formData['rad_accounttype'])) {
   $accounttype = $formData['rad_accounttype'] ?? 0;
   }
   if (isset($formData['rad_distribution'])) {
   $distribution = $formData['rad_distribution'] ?? 0;
   }

    
    DB::table('agents')
    ->where('id', $agentId)
    ->update([
        'company_name' => $formData['txt_agency_name'],
        'company_email' => $formData['txt_agency_email'],
        'company_reg_no' => $formData['txt_account_id'],
        'first_name' => $formData['txt_agency_fname'],
        'middle_name' => $formData['txt_agency_mname'],
        'last_name' => $formData['txt_agency_lname'],
        'designation' => $formData['txt_agency_designation'],
        'nature_of_business' => $formData['sel_nature_of_business'],
        'company_logo' => $filename,
        'profile_image' => $filename1,
        'address' => $formData['txt_address'],
        'country_id' => $formData['sel_country'],
        'city' => $formData['sel_city'],
        'postal_code' => $formData['txt_pincode'],
        'iata_status' => $formData['rad_iata_status'],
        'phonenumber' => $formData['txt_phone'],
        'mobile_number' => $formData['txt_mobile'],
        'fax_number' => $formData['txt_fax'],
        'website' => $formData['txt_website'],
        'agency_account_name' => $formData['txt_acc_name'],
        'agency_account_email' => $formData['txt_acc_email'],
        'agency_account_phoneno' => $formData['txt_acc_ph'],
        'agency_reservation_name' => $formData['txt_res_name'],
        'agency_reservation_email' => $formData['txt_res_email'],
        'agency_reservation_phoneno' => $formData['txt_res_ph'],
        'agency_mgt_name' => $formData['txt_mgt_name'],
        'agency_mgt_email' => $formData['txt_mgt_email'],
        'agency_mgt_ph' => $formData['txt_mgt_ph'],
        'agency_contactinfo_remarks' => $formData['txt_acc_remarks'],
        'currency' => $formData['sel_currency'],
        'time_zone' => $formData['sel_timezone'],
        'agent_money_type' => $accounttype,
        'credit_limit' => $formData['txt_credit'],
        'temp_credit_limit' => $formData['temp_credit'],
        'credit_distribution' => $distribution,
        'sales_manager' => $formData['txt_sales_mgr'],
        'consultant' => $formData['txt_consultant'],
        'business_vol_category' => $formData['sel_category'],
    ]);
    // Save the user data
   
	 DB::table('branch')
    ->where('user_id',  $agentId)
    ->update([
        'branch_name' => $formData['txt_branch'],
		'user_type' => 'main',
    ]);


 $rights = [
    'multi_currency_search' => 'Multi Currency Search',
    'allow_create_quotations' => 'Allow Create Quotations',
    'allow_non_refundable_bookings' => 'Allow Non-Refundable bookings',
    'allow_voucher_bookings' => 'Allow Voucher bookings',
    'make_bookings' => 'Make Bookings',
    'debug_mode' => 'Debug Mode',
    'flights_allow_ticketing' => 'Flights Allow Ticketing',
	'web_services' => 'Web Services',
];

$rights = [
    'multi_currency_search' => 'Multi Currency Search',
    'allow_create_quotations' => 'Allow Create Quotations',
    'allow_non_refundable_bookings' => 'Allow Non-Refundable bookings',
    'allow_voucher_bookings' => 'Allow Voucher bookings',
    'make_bookings' => 'Make Bookings',
    'debug_mode' => 'Debug Mode',
    'flights_allow_ticketing' => 'Flights Allow Ticketing',
    'web_services' => 'Web Services',
];

foreach ($rights as $key => $name) {
    $value = in_array($key, $request->input('rights', [])) ? 'yes' : 'no';

    DB::table('agent_rights')->updateOrInsert(
        [
            'agent_user_id' => $agentId,
            'right_name' => $name,
            'agent_type' => 'main',
        ],
        [
            'right_acess' => $value,
        ]
    );
}


/*foreach ($rights as $key => $name) {
    $value = in_array($key, $request->input('rights', [])) ? 'yes' : 'no';

  $agentRight = DB::table('agent_rights')
        ->where('agent_user_id', $agentId)
        ->where('right_name', $name)
        ->where('agent_type', 'main')
        ->first(); // Retrieve the record, if exists


    DB::table('agent_rights')
        ->where('agent_user_id', $agentId)
        ->where('right_name', $name)
        ->where('agent_type', 'main')
        ->update([
            'right_acess' => $value,
        ]);
}*/


    // Redirect or return a response
    // You can modify this based on your application's requirements
  return response()->json(['success' => 'Agent Details updated successfully']);
	}
	public function setMarkup()
{
    $user = Auth::user();
    $email = $user->email;
    $agentid = $user->agentid;
    $agenttype = $user->agent_type;

    // Retrieve the branch ID based on the user's agent type and ID
    $branchId = DB::table('branch')
        ->where('user_type', $agenttype)
        ->where('user_id', $agentid)
        ->value('id');

    $countries = DB::table('countries')->select('id', 'country_name')->get();

    if ($user->role === 1) {
        $list = DB::table('markup_percent')
            ->where('markup_for', '=', 'txpo')
            ->where('branchid', $branchId)
            ->orderBy('id', 'desc')
            ->get();
			$branchData='';
    } else if ($user->role === 2) {
        $list = DB::table('markup_percent')
            ->where('markup_for', '=', 'main')
            ->where('branchid', $branchId)
            ->orderBy('id', 'desc')
            ->get();

        // Use the 'use' keyword to pass $agentid to the callback
        $branchData = DB::table('branch as b')
            ->select('b.id', 'b.branch_name')
            ->where('b.user_type', 'sub')
            ->whereIn('b.user_id', function ($query) use ($agentid) {
                $query->select('sa.id')
                    ->from('sub_agents as sa')
                    ->where('sa.agent_id', $agentid);
            })
            ->get();
    } else {
        $list = DB::table('markup_percent')
            ->where('markup_for', '=', 'sub')
            ->where('branchid', $branchId)
            ->orderBy('id', 'desc')
            ->get();
    }

    return view('admin.setMarkup', compact('countries', 'list','branchData'));
}

	public function updateMarkupStatus(Request $request)
{
	  $user = Auth::user();
   
    $agentid = $user->agentid;
    $agenttype = $user->agent_type;
	
	 $branchId = DB::table('branch')
        ->where('user_type', $agenttype)
        ->where('user_id', $agentid)
        ->value('id');
		
    // Retrieve the ID and status from the AJAX request
    $id = $request->input('id');
    $status = $request->input('status');

    // Update the status in the database
     // Update the status in the database
   DB::table('markup_percent')
    ->where('id', $id)
    ->update([
        'status' => ($status == 'active') ? 1 : 0,
        'branchid' => $branchId, // Replace $newBranchId with the value you want to set
    ]);


$updatedStatus = DB::table('markup_percent')
    ->where('id', $id)
    ->value('status');
	
	$response = [
    'status' => 'success',
    'newStatus' => $updatedStatus,
];
    // Return a response indicating success
   return response()->json($response);
}
	
	public function markupSave(Request $request)
{
    $count = count($request->sel_nature_of_business);
    $user = Auth::user();

    for ($i = 0; $i < $count; $i++) {
        $data1 = [
            "from_date" => $request->from_date[$i],
            "to_date" => $request->to_date[$i],
            "country" => $request->sel_country[$i],
            "city" => $request->sel_city[$i],
            "bus_vol_category" => 'General'
        ];

        if (!empty($request->hotel_markup_per[$i])) {
            $data1["markup_percent"] = $request->hotel_markup_per[$i];
            $data1["markup_type"] = 'Percent';
        } elseif (!empty($request->hotel_amount_amt[$i])) {
            $data1["branch_markup"] = $request->hotel_amount_amt[$i];
            $data1["markup_type"] = 'Amount';
        }
if($user->role == 1)
{
	$data1["markup_cancel_amt"] = $request->hotel_cancel_amt[$i];
}
        // Check if user role is 1 or 2 and 'txpo' or 'main' is selected
        if (($user->role == 1 || $user->role == 2) && ($request->sel_nature_of_business[$i] == 'txpo' || $request->sel_nature_of_business[$i] == 'main')) {
            $agentid = $user->agentid;
            $agenttype = $user->agent_type;

            $branchId = DB::table('branch')
                ->where('user_type', $agenttype)
                ->where('user_id', $agentid)
                ->value('id');

            $data1["branchid"] = $branchId;
            $data1["markup_for"] = $request->sel_nature_of_business[$i];
			$data1["agent_type"] = $request->sel_nature_of_business[$i];
        } else {
            $data1["branchid"] = $request->sel_nature_of_business[$i];
            $data1["markup_for"] = 'sub';
			$data1["agent_type"] = 'sub';
        }

        // Insert the data into the 'markup_percent' table
        $result = DB::table('markup_percent')->insert($data1);
    }

    if ($result == 1) {
        return response()->json(['success' => 'MarkUp Details have been uploaded']);
    } else {
        return response()->json(["message" => "Please try again."]);
    }
}


/*	public function markupSave(Request $request)
	{
		 $count = count($request->sel_nature_of_business);

      for($i=0;$i<$count;$i++)
      {      	 

$data1 = array(
    "markup_for" => $request->sel_nature_of_business[$i],
    "markup_percent" => $request->hotel_markup_per[$i],
    "from_date" => $request->from_date[$i],
    "to_date" => $request->to_date[$i],
    "branch_markup" => $request->hotel_amount_amt[$i],
    "agent_type" => $request->sel_nature_of_business[$i],
    "country" => $request->sel_country[$i],
    "city" => $request->sel_city[$i],
	"bus_vol_category" =>'General'
);

$result = DB::table('markup_percent')->insert($data1);
	}
	if($result==1){ 
                    	return response()->json(['success'=>'MarkUp Details has been uploaded']);
                } 
                else{
                    return response()->json(["message" => "Please try again."]);
                }
	}*/
	
		public function paymentStatus(Request $request)
{
    // Retrieve the ID and status from the AJAX request
    $id = $request->input('id');
    $status = $request->input('status');

    // Update the status in the database
     // Update the status in the database
    DB::table('payment')
        ->where('id', $id)
        ->update(['status' => ($status == 'active') ? 1 : 0]);

$updatedStatus = DB::table('payment')
    ->where('id', $id)
    ->value('status');
	
	$response = [
    'status' => 'success',
    'newStatus' => $updatedStatus,
];
    // Return a response indicating success
   return response()->json($response);
}
	public function outsideRegStatus(Request $request)
{
    // Retrieve the ID and status from the AJAX request
    $id = $request->id;

    // Update the status in the database
    try {
        DB::table('agents')
            ->where('id', $id)
            ->update(['status' => 1]);

        $response = [
            'status' => 'success',
        ];
    } catch (\Exception $e) {
        $response = [
            'status' => 'error',
            'message' => 'An error occurred while updating the status.',
        ];
    }

    // Return a response indicating success or error
    return response()->json($response);
}
public function approveList(Request $request)
	{
		$role = Auth::user()->role;
		if($role==1)
		{
		$list = DB::table('payment')
    ->join('branch', 'payment.branchid', '=', 'branch.id')
    ->where('payment.suplier_category', '=', 1)
    ->orderBy('payment.id', 'desc')
    ->select('payment.*', 'branch.branch_name')
    ->get();
		  
		return view('admin.approveList',compact('list'));
	}
	if($role==2)
		{
	    $agentid = Auth::user()->agentid;
	    $agenttype = Auth::user()->agent_type;
	   $branchId = DB::table('branch')
                ->where('user_type', $agenttype)
                ->where('user_id', $agentid)
                ->value('id');
$list = DB::table('payment')
    ->join('branch', 'payment.branchid', '=', 'branch.id')
    ->where('payment.suplier_category', '=', $branchId)
    ->orderBy('payment.id', 'desc')
    ->select('payment.*', 'branch.branch_name')
    ->get();
		return view('admin.approveList',compact('list'));
	}
	  
	}
	
	public function approvalReport(Request $request)
{
    $fileName = 'PaymentApprovalReport';
    $user = Auth::user();
    $email = $user->email;
    $agentid = $user->agentid;
	$branchid = $user->branch_id;
    $tasks = DB::table('payment')
    ->join(DB::raw("(SELECT id, branch_name FROM branch WHERE parent_id = $branchid) xx"), 'xx.id', '=', 'payment.branchid')
    ->leftJoin('agents', 'agents.branch_id', '=', 'xx.id')
    ->join('branch', 'branch.id', '=', 'payment.branchid')
    ->select('payment.*', 'agents.first_name', 'branch.branch_name')
    ->where('payment.status', '=', 1)
    ->get();


    $headers = array(
        "Content-type"        => "text/csv",
        "Content-Disposition" => "attachment; filename=$fileName",
        "Pragma"              => "no-cache",
        "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        "Expires"             => "0"
    );

   $columns = array(
    'Branch Name',
    'Date Of Payment',
    'Payment Amount',
    'Payment Due',
    'Rate of Exchange',
    'Mode of Payment',
    'Bank Name',
    'Reference Number',
	 'Status',
);


    $callback = function () use ($tasks, $columns) {dd($tasks);
        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);

        foreach ($tasks as $task) {
            $row = array(
                'Branch Name' => $task->branch_name,
            'Reciept Date' => $task->approval_date,
            'Reciept Number' => $task->receipt_number,
            'Account Reference No' => $task->reference_no,
            'Agent Name' => $task->first_name,
            'Receipt Amount' => $task->payment_amt,
            'Receipt Due Amount' => $task->payment_due,
            'Receipt Mode' => $task->mode_of_payment,
            'Status' =>Approved,
            'Approval Date' => $task->approval_date,
                
            );

            fputcsv($file, $row);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);

   
}
	
	
	public function checkCreditLimit(Request $request)
{
    $adminCreditLimit = DB::table('travelxpo_admin')->value('credit_limit');

    $sumOfAgentCreditLimits = DB::table('agents')
        ->select(DB::raw('SUM(credit_limit) as total_credit_limit'))
        ->first(); // Use first() to retrieve the result of the query

    $sumOfAgentCreditLimits = $sumOfAgentCreditLimits->total_credit_limit;

    $enteredCreditLimit = $request->input('creditLimit');

    if ($enteredCreditLimit > ($adminCreditLimit - $sumOfAgentCreditLimits)) {
        return response()->json(['error' => 'Credit limit exceeded. Please enter a lower value.']);
    }

    return response()->json(['success' => true]);
}
public function updateAirline(Request $request)
{
    $recordId = $request->input('record_id');

    // Validate the request data if needed

    // Find the airline record by ID
    $airline = DB::table('airlines')->where('id', $recordId)->first();

    if (!$airline) {
        return redirect()->back()->with('error', 'Airline record not found');
    }

    // Get the airline code
    $airlineCode = $request->input('airline_codeedit');

    // Update the record with the form input data
    $updateData = [
        'airline_name' => $request->input('airlinenameedit'),
        'airline_code' => $airlineCode,
    ];

    if ($request->hasFile('airline_logoedit')) {
        $file = $request->file('airline_logoedit');
        $extension = strtolower($file->getClientOriginalExtension());
        $filename = $airlineCode . '.' . $extension; // Use the airline code as the filename with the original extension
        $filePath = public_path('uploads/AirlineLogo/');
        $file->move($filePath, $filename);

        // Check if it's a valid image format (e.g., jpg, jpeg, png) and perform the GIF conversion if needed
        if (in_array($extension, ['jpg', 'jpeg', 'png'])) {
            $image = imagecreatefromstring(file_get_contents($filePath . $filename));
            imagegif($image, $filePath . $filename);
            imagedestroy($image);
        }

        // Add the filename to the update data
        $updateData['airline_logo'] = $filename;
    }

    // Perform the database update
    DB::table('airlines')->where('id', $recordId)->update($updateData);

    return redirect()->back()->with('success', 'Airline record updated successfully');
}

//REPORTS.....................//

public function dailySalesReport()
{
	$user = Auth::user();
    $email = $user->email;
    $agentid = $user->agentid;
    $agenttype = $user->agent_type;

    // Retrieve the branch ID based on user's agent type and ID
    $branchId = DB::table('branch')
        ->where('user_type', $agenttype)
        ->where('user_id', $agentid)
        ->value('id');
		$list = DB::select("
    (SELECT
        passenger_bookings.*,
        invoice.booking_id AS invoice_booking_id,
        invoice.invoice_billno,
        invoice.total_taxamt,
        invoice.invoice_date,
        branch.branch_name,
        CASE
            WHEN passenger_bookings.branch_id = 1 THEN 'Txpo'
            WHEN agents.agent_code IS NOT NULL THEN agents.agent_code
            WHEN sub_agents.agent_code IS NOT NULL THEN sub_agents.agent_code
            ELSE 'Unknown'
        END AS agcode,
        CASE
            WHEN passenger_bookings.branch_id = 1 THEN passenger_bookings.tbo_price
            WHEN branch.user_type = 'main' THEN passenger_bookings.tbo_price + passenger_bookings.expo_price
            WHEN branch.user_type = 'sub' THEN passenger_bookings.tbo_price + passenger_bookings.expo_price + passenger_bookings.agent_price
            ELSE 0
        END AS supplier_price
    FROM
        passenger_bookings
        LEFT JOIN invoice ON passenger_bookings.id = invoice.pb_id
        LEFT JOIN agents ON passenger_bookings.branch_id = agents.branch_id
        LEFT JOIN sub_agents ON passenger_bookings.branch_id = sub_agents.branch_id
        JOIN branch ON passenger_bookings.branch_id = branch.id
    WHERE
        passenger_bookings.branch_id = 1
        AND invoice.booking_id IS NOT NULL AND passenger_bookings.email IS NOT NULL
    ORDER BY passenger_bookings.id ASC)
    
    UNION
    
    (SELECT
        passenger_bookings.*,
        invoice.booking_id AS invoice_booking_id,
        invoice.invoice_billno,
        invoice.total_taxamt,
        invoice.invoice_date,
        branch.branch_name,
        CASE
            WHEN passenger_bookings.branch_id = 1 THEN 'Txpo'
            WHEN agents.agent_code IS NOT NULL THEN agents.agent_code
            WHEN sub_agents.agent_code IS NOT NULL THEN sub_agents.agent_code
            ELSE 'Unknown'
        END AS agcode,
        CASE
            WHEN passenger_bookings.branch_id = 1 THEN passenger_bookings.tbo_price
            WHEN branch.user_type = 'main' THEN passenger_bookings.tbo_price + passenger_bookings.expo_price
            WHEN branch.user_type = 'sub' THEN passenger_bookings.tbo_price + passenger_bookings.expo_price + passenger_bookings.agent_price
            ELSE 0
        END AS supplier_price
    FROM
        passenger_bookings
        LEFT JOIN invoice ON passenger_bookings.id = invoice.pb_id
        LEFT JOIN agents ON passenger_bookings.branch_id = agents.branch_id
        LEFT JOIN sub_agents ON passenger_bookings.branch_id = sub_agents.branch_id
        JOIN branch ON passenger_bookings.branch_id = branch.id
    WHERE
        branch.user_type = 'main'
        AND invoice.booking_id IS NOT NULL AND passenger_bookings.email IS NOT NULL
    ORDER BY passenger_bookings.id ASC)
    
    UNION
    
    (SELECT
        passenger_bookings.*,
        invoice.booking_id AS invoice_booking_id,
        invoice.invoice_billno,
        invoice.total_taxamt,
        invoice.invoice_date,
        branch.branch_name,
        CASE
            WHEN passenger_bookings.branch_id = 1 THEN 'Txpo'
            WHEN agents.agent_code IS NOT NULL THEN agents.agent_code
            WHEN sub_agents.agent_code IS NOT NULL THEN sub_agents.agent_code
            ELSE 'Unknown'
        END AS agcode,
        CASE
            WHEN passenger_bookings.branch_id = 1 THEN passenger_bookings.tbo_price
            WHEN branch.user_type = 'main' THEN passenger_bookings.tbo_price + passenger_bookings.expo_price
            WHEN branch.user_type = 'sub' THEN passenger_bookings.tbo_price + passenger_bookings.expo_price + passenger_bookings.agent_price
            ELSE 0
        END AS supplier_price
    FROM
        passenger_bookings
        LEFT JOIN invoice ON passenger_bookings.id = invoice.pb_id
        LEFT JOIN agents ON passenger_bookings.branch_id = agents.branch_id
        LEFT JOIN sub_agents ON passenger_bookings.branch_id = sub_agents.branch_id
        JOIN branch ON passenger_bookings.branch_id = branch.id
    WHERE
        branch.user_type = 'sub'
        AND invoice.booking_id IS NOT NULL AND passenger_bookings.email IS NOT NULL
    ORDER BY passenger_bookings.id ASC)
");


$totalAgentSales = 0; // Initialize the variable to hold the sum
$totalSupplierPrice =0;
$profit =0;
    foreach ($list as $val) {
        $totalAgentSales += $val->total_ticket_fare;
		$totalSupplierPrice += $val->supplier_price;
		 $profit += ($val->total_ticket_fare) - ($val->supplier_price);
    }

 $profitPercentage = ($profit / $totalAgentSales) * 100;

	
	return view('admin.dailySalesReport',compact('list','totalAgentSales','totalSupplierPrice','profit','profitPercentage'));
	
}
  public function exportDailySales(Request $request)
{
    $fileName = 'DailySaleReport.csv';
    $user = Auth::user();
    $email = $user->email;
    $agentid = $user->agentid;
    $agenttype = $user->agent_type;

    // Retrieve the branch ID based on the user's agent type and ID
    $branchId = DB::table('branch')
        ->where('user_type', $agenttype)
        ->where('user_id', $agentid)
        ->value('id');

    $tasks = DB::select("
    (SELECT
        passenger_bookings.*,
        invoice.booking_id AS invoice_booking_id,
        invoice.invoice_billno,
        invoice.total_taxamt,
        invoice.invoice_date,
        branch.branch_name,
        CASE
            WHEN passenger_bookings.branch_id = 1 THEN 'Txpo'
            WHEN agents.agent_code IS NOT NULL THEN agents.agent_code
            WHEN sub_agents.agent_code IS NOT NULL THEN sub_agents.agent_code
            ELSE 'Unknown'
        END AS agcode,
        CASE
            WHEN passenger_bookings.branch_id = 1 THEN passenger_bookings.tbo_price
            WHEN branch.user_type = 'main' THEN passenger_bookings.tbo_price + passenger_bookings.expo_price
            WHEN branch.user_type = 'sub' THEN passenger_bookings.tbo_price + passenger_bookings.expo_price + passenger_bookings.agent_price
            ELSE 0
        END AS supplier_price
    FROM
        passenger_bookings
        LEFT JOIN invoice ON passenger_bookings.id = invoice.pb_id
        LEFT JOIN agents ON passenger_bookings.branch_id = agents.branch_id
        LEFT JOIN sub_agents ON passenger_bookings.branch_id = sub_agents.branch_id
        JOIN branch ON passenger_bookings.branch_id = branch.id
    WHERE
        passenger_bookings.branch_id = 1
        AND invoice.booking_id IS NOT NULL AND passenger_bookings.email IS NOT NULL
    ORDER BY passenger_bookings.id ASC)
    
    UNION
    
    (SELECT
        passenger_bookings.*,
        invoice.booking_id AS invoice_booking_id,
        invoice.invoice_billno,
        invoice.total_taxamt,
        invoice.invoice_date,
        branch.branch_name,
        CASE
            WHEN passenger_bookings.branch_id = 1 THEN 'Txpo'
            WHEN agents.agent_code IS NOT NULL THEN agents.agent_code
            WHEN sub_agents.agent_code IS NOT NULL THEN sub_agents.agent_code
            ELSE 'Unknown'
        END AS agcode,
        CASE
            WHEN passenger_bookings.branch_id = 1 THEN passenger_bookings.tbo_price
            WHEN branch.user_type = 'main' THEN passenger_bookings.tbo_price + passenger_bookings.expo_price
            WHEN branch.user_type = 'sub' THEN passenger_bookings.tbo_price + passenger_bookings.expo_price + passenger_bookings.agent_price
            ELSE 0
        END AS supplier_price
    FROM
        passenger_bookings
        LEFT JOIN invoice ON passenger_bookings.id = invoice.pb_id
        LEFT JOIN agents ON passenger_bookings.branch_id = agents.branch_id
        LEFT JOIN sub_agents ON passenger_bookings.branch_id = sub_agents.branch_id
        JOIN branch ON passenger_bookings.branch_id = branch.id
    WHERE
        branch.user_type = 'main'
        AND invoice.booking_id IS NOT NULL AND passenger_bookings.email IS NOT NULL
    ORDER BY passenger_bookings.id ASC)
    
    UNION
    
    (SELECT
        passenger_bookings.*,
        invoice.booking_id AS invoice_booking_id,
        invoice.invoice_billno,
        invoice.total_taxamt,
        invoice.invoice_date,
        branch.branch_name,
        CASE
            WHEN passenger_bookings.branch_id = 1 THEN 'Txpo'
            WHEN agents.agent_code IS NOT NULL THEN agents.agent_code
            WHEN sub_agents.agent_code IS NOT NULL THEN sub_agents.agent_code
            ELSE 'Unknown'
        END AS agcode,
        CASE
            WHEN passenger_bookings.branch_id = 1 THEN passenger_bookings.tbo_price
            WHEN branch.user_type = 'main' THEN passenger_bookings.tbo_price + passenger_bookings.expo_price
            WHEN branch.user_type = 'sub' THEN passenger_bookings.tbo_price + passenger_bookings.expo_price + passenger_bookings.agent_price
            ELSE 0
        END AS supplier_price
    FROM
        passenger_bookings
        LEFT JOIN invoice ON passenger_bookings.id = invoice.pb_id
        LEFT JOIN agents ON passenger_bookings.branch_id = agents.branch_id
        LEFT JOIN sub_agents ON passenger_bookings.branch_id = sub_agents.branch_id
        JOIN branch ON passenger_bookings.branch_id = branch.id
    WHERE
        branch.user_type = 'sub'
        AND invoice.booking_id IS NOT NULL AND passenger_bookings.email IS NOT NULL
    ORDER BY passenger_bookings.id ASC)
");


    $totalAgentSales = 0; // Initialize the variable to hold the sum
    $totalSupplierPrice = 0;
    $profit = 0;

    foreach ($tasks as $val) {
        $totalAgentSales += $val->total_ticket_fare;
        $totalSupplierPrice += $val->supplier_price;
        $profit += ($val->total_ticket_fare) - ($val->supplier_price);
    }

    $profitPercentage = ($profit / $totalAgentSales) * 100;

    $headers = array(
        "Content-type"        => "text/csv",
        "Content-Disposition" => "attachment; filename=$fileName",
        "Pragma"              => "no-cache",
        "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        "Expires"             => "0"
    );

    $columns = array(
        'Booking Date',
        'Service Date',
        'Invoice Date',
        'CheckOut Date',
        'Booking ID',
        'Agent Code',
        'Service Name',
        'Voucher ID',
        'Passenger Email',
        'Customer Amount',
        'Service Tax',
        'Supplier Amount',
        'Grand Total Profit',
        'Profit',
    );

    $bookingData = [];

    foreach ($tasks as $task) {
        $row['Booking Date'] = $task->booking_date;
        $row['Service Date'] = $task->departure_datetime;
        $row['Invoice Date'] = $task->invoice_date;
        $row['CheckOut Date'] = $task->arrivel_datetime;
        $row['Booking ID'] = $task->invoice_booking_id;
        $row['Agent Code'] = $task->agcode;
        $row['Service Name'] = 'Flight Booking';
        $row['Voucher ID'] = $task->invoice_billno;
        $row['Passenger Email'] = $task->email;
        $row['Customer Amount'] = $task->total_ticket_fare;
	
        $row['Service Tax'] = $task->total_taxamt;
        $row['Supplier Amount'] = $task->supplier_price;
		
        $row['Grand Total Profit'] = ($task->total_ticket_fare) - ($task->supplier_price);
        $row['Profit'] =  0;  //($task->total_ticket_fare - $task->supplier_price) / $task->total_ticket_fare * 100;
        $bookingData[] = [
            $row['Booking Date'],
            $row['Service Date'],
            $row['Invoice Date'],
            $row['CheckOut Date'],
            $row['Booking ID'],
            $row['Agent Code'],
            $row['Service Name'],
            $row['Voucher ID'],
            $row['Passenger Email'],
            $row['Customer Amount'],
            $row['Service Tax'],
            $row['Supplier Amount'],
            $row['Grand Total Profit'],
            $row['Profit'],
        ];
    }

    $summarizedData = [
        [
            'Branch Name',
            'Total Agent Sales',
            'Total Supplier Price',
            'Total Profit',
            '%',
        ],
        [
            $tasks[0]->branch_name, // Replace with the actual branch name
            $totalAgentSales,
            $totalSupplierPrice,
            $profit,
            $profitPercentage,
        ],
    ];

    $callback = function () use ($bookingData, $summarizedData, $columns) {
        $file = fopen('php://output', 'w');

        // Export individual booking data
        fputcsv($file, $columns);
        foreach ($bookingData as $row) {
            fputcsv($file, $row);
        }

        // Add an empty row to separate the two sections
        fputcsv($file, []);

        // Export the "Summarized Report" data
        foreach ($summarizedData as $row) {
            fputcsv($file, $row);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}
public function bookingReport()
{
	
	$user = Auth::user();
    $email = $user->email;
    $agentid = $user->agentid;
	$branchid = $user->branch_id;
    $agenttype = $user->agent_type;
if ($agenttype == 'txpo') {
    $subbranches = DB::table('branch')
        ->select('id', 'branch_name')
        ->get();
} elseif ($agenttype == 'main') {
    $subbranches = DB::table('branch')
        ->select('id', 'branch_name')
        ->where('id', $branchid)
        ->orWhere('parent_id', $branchid)
        ->get();
} elseif ($agenttype == 'sub') {
    $subbranches = DB::table('branch')
        ->select('id', 'branch_name')
        ->where('id', $branchid)
        ->get();
} else {
    // Handle other cases or provide a default query
    $subbranches = [];
}

	return view('admin.bookingReport',compact('subbranches'));
}
public function getbookingReport(Request $request)
{
    $fromDate = $request->input('fromDate');
    $toDate = $request->input('toDate');
    $branchId = $request->input('branchId');

 $reportData = DB::select("
    SELECT 
        pb.*,
        invoice.invoice_billno,
        invoice.invoice_date,
        b.branch_name,
        CASE 
            WHEN b.user_type = 'main' THEN agents.agent_code
            WHEN b.user_type = 'sub' THEN sub_agents.agent_code
            WHEN b.user_type = 'txpo' THEN 'txpo' END AS agent_code,
        CASE 
            WHEN b.user_type = 'main' THEN agents.first_name
            WHEN b.user_type = 'sub' THEN sub_agents.first_name
            WHEN b.user_type = 'txpo' THEN 'txpo' END AS agent_name,
        CASE 
            WHEN b.user_type = 'main' THEN agents.consultant
            WHEN b.user_type = 'sub' THEN sub_agents.consultant
            WHEN b.user_type = 'txpo' THEN 'txpo_consultant' END AS agent_consultant,
        '' AS supplier_bookingid,
        '' AS itenary_bookingid,
        'Flight Booking' AS service_type,
        '' AS supplier_referenceno,
        'Confirmed' as Status,
        '' AS leader_name,
        '' AS service_date,
        '' AS hotel_name,
        0 AS no_of_nights,
        0 AS total_room_nights,
        '' AS roe,
        '' AS voucher_id,
        0.0 AS approx_revenue,
        0.0 AS supplier_rate,
        '' AS supplier_currency,
        CASE 
            WHEN markup_percent.markup_type = 'Amount' THEN markup_percent.markup_amt
            WHEN markup_percent.markup_type = 'Percent' THEN markup_percent.markup_percent END AS markupamt
    FROM 
        passenger_bookings AS pb
    LEFT JOIN 
        invoice ON pb.id = invoice.pb_id
    LEFT JOIN 
        markup_percent ON pb.branch_id = markup_percent.branchid
    JOIN 
        branch AS b ON pb.branch_id = b.id
    LEFT JOIN 
        agents ON pb.branch_id = agents.branch_id AND b.user_type = 'main'
    LEFT JOIN 
        sub_agents ON pb.branch_id = sub_agents.branch_id AND b.user_type = 'sub'
    WHERE 
        DATE(pb.departure_datetime) BETWEEN '$fromDate' AND '$toDate'
        AND pb.branch_id = $branchId
");

    return response()->json($reportData);
}

public function exportBookingReport(Request $request)
{
    $fileName = 'BookingReport.csv';
    $fromDate = $request->input('fromDate');
    $toDate = $request->input('toDate');
    $branchId = $request->input('branches');

    $tasks = DB::select("
    SELECT 
        pb.*,
        invoice.invoice_billno,
        invoice.invoice_date,
        b.branch_name,
        CASE 
            WHEN b.user_type = 'main' THEN agents.agent_code
            WHEN b.user_type = 'sub' THEN sub_agents.agent_code
            WHEN b.user_type = 'txpo' THEN 'txpo' END AS agent_code,
        CASE 
            WHEN b.user_type = 'main' THEN agents.first_name
            WHEN b.user_type = 'sub' THEN sub_agents.first_name
            WHEN b.user_type = 'txpo' THEN 'txpo' END AS agent_name,
        CASE 
            WHEN b.user_type = 'main' THEN agents.consultant
            WHEN b.user_type = 'sub' THEN sub_agents.consultant
            WHEN b.user_type = 'txpo' THEN 'txpo_consultant' END AS agent_consultant,
        '' AS supplier_bookingid,
        '' AS itenary_bookingid,
        'Flight Booking' AS service_type,
        '' AS supplier_referenceno,
        'Confirmed' as Status,
        '' AS leader_name,
        '' AS service_date,
        '' AS hotel_name,
        0 AS no_of_nights,
        0 AS total_room_nights,
        '' AS roe,
        '' AS voucher_id,
        0.0 AS approx_revenue,
        0.0 AS supplier_rate,
        '' AS supplier_currency,
        CASE 
            WHEN markup_percent.markup_type = 'Amount' THEN markup_percent.markup_amt
            WHEN markup_percent.markup_type = 'Percent' THEN markup_percent.markup_percent END AS markupamt
    FROM 
        passenger_bookings AS pb
    LEFT JOIN 
        invoice ON pb.id = invoice.pb_id
    LEFT JOIN 
        markup_percent ON pb.branch_id = markup_percent.branchid
    JOIN 
        branch AS b ON pb.branch_id = b.id
    LEFT JOIN 
        agents ON pb.branch_id = agents.branch_id AND b.user_type = 'main'
    LEFT JOIN 
        sub_agents ON pb.branch_id = sub_agents.branch_id AND b.user_type = 'sub'
    WHERE 
        DATE(pb.departure_datetime) BETWEEN '$fromDate' AND '$toDate'
        AND pb.branch_id = $branchId
");

    $headers = array(
        "Content-type"        => "text/csv",
        "Content-Disposition" => "attachment; filename=$fileName",
        "Pragma"              => "no-cache",
        "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        "Expires"             => "0"
    );

    $columns = array(
        'Booking ID',
        'Booking Date',
        'Departure Date',
        'Arrival Date',
        'PNR',
        'Booking Amount',
        'Service Type',
        'Departure City Code',
        'Arrival City Code',
        'Passenger Type',
        'Branch Name',
        'Agent Code',
        'Agent Name',
        'Agent Consultant',
        'Agent Markup',
        'Supplier Booking ID',
        'Itenary Booking ID',
        'Supplier Reference No',
        'Status',
        'Leader Name',
        'Service Date',
        'Hotel Name',
        'No of Nights',
        'Total Room Nights',
        'ROE',
        'Voucher Id',
        'Approximate Revenue',
        'Supplier RATE',
        'Supplier Currency',
       
    );

    $callback = function () use ($tasks, $columns) {
        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);

        foreach ($tasks as $task) {
            $row = array(
                'Booking ID' => $task->booking_id,
                'Booking Date' => $task->booking_date,
                'Departure Date' => $task->departure_datetime,
                'Arrival Date' => $task->arrivel_datetime,
                'PNR' => $task->PNR,
                'Booking Amount' => $task->total_ticket_fare,
                'Service Type' => $task->service_type,
                'Departure City Code' => $task->departure_citycode,
                'Arrival City Code' => $task->arrivel_citycode,
                'Passenger Type' => $task->passengertype,
                'Branch Name' => $task->branch_name,
                'Agent Code' => $task->agent_code,
                'Agent Name' => $task->agent_name,
                'Agent Consultant' => $task->agent_consultant,
                'Agent Markup' => $task->markupamt, // Assuming markupamt is the correct field name
                'Supplier Booking ID' => $task->supplier_bookingid,
                'Itenary Booking ID' => $task->itenary_bookingid,
                'Supplier Reference No' => $task->supplier_referenceno,
                'Status' => $task->Status,
                'Leader Name' => $task->leader_name,
                'Service Date' => $task->service_date,
                'Hotel Name' => $task->hotel_name,
                'No of Nights' => $task->no_of_nights,
                'Total Room Nights' => $task->total_room_nights,
                'ROE' => $task->roe,
                'Voucher Id' => $task->voucher_id,
                'Approximate Revenue' => $task->approx_revenue,
                'Supplier RATE' => $task->supplier_rate,
                'Supplier Currency' => $task->supplier_currency,
               
            );

            fputcsv($file, $row);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}

public function agent_recieptReport()
{
	
	$user = Auth::user();
    $email = $user->email;
    $agentid = $user->agentid;
	$branchid = $user->branch_id;
   
$reciept = DB::table('payment')
    ->join(DB::raw("(SELECT id, branch_name FROM branch WHERE parent_id = $branchid) xx"), 'xx.id', '=', 'payment.branchid')
    ->leftJoin('agents', 'agents.branch_id', '=', 'xx.id')
    ->join('branch', 'branch.id', '=', 'payment.branchid')
    ->select('payment.*', 'agents.first_name', 'branch.branch_name')
    ->where('payment.status', '=', 1)
    ->get();

// Use $results as needed




	return view('admin.agentReceiptReport',compact('reciept'));
}

public function exportAgentSales(Request $request)
{
    $fileName = 'AgentSalesReport.csv';
    $user = Auth::user();
    $email = $user->email;
    $agentid = $user->agentid;
	$branchid = $user->branch_id;
    $tasks = DB::table('payment')
    ->join(DB::raw("(SELECT id, branch_name FROM branch WHERE parent_id = $branchid) xx"), 'xx.id', '=', 'payment.branchid')
    ->leftJoin('agents', 'agents.branch_id', '=', 'xx.id')
    ->join('branch', 'branch.id', '=', 'payment.branchid')
    ->select('payment.*', 'agents.first_name', 'branch.branch_name')
    ->where('payment.status', '=', 1)
    ->get();


    $headers = array(
        "Content-type"        => "text/csv",
        "Content-Disposition" => "attachment; filename=$fileName",
        "Pragma"              => "no-cache",
        "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
        "Expires"             => "0"
    );

   $columns = array(
    'Branch Name',
    'Reciept Date',
    'Reciept Number',
    'Account Reference No',
    'Agent Name',
    'Receipt Amount',
    'Receipt Due Amount',
    'Receipt Mode',
);


    $callback = function () use ($tasks, $columns) {
        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);

        foreach ($tasks as $task) {
            $row = array(
                'Branch Name' => $task->branch_name,
                'Reciept Date' => $task->approval_date,
                'Reciept Number' => $task->receipt_number,
                'Account Reference No' => $task->reference_no,
                'Agent Name' => $task->first_name,
                'Receipt Amount' => $task->payment_amt,
                'Receipt Due Amount' => $task->payment_due,
                'Receipt Mode' => $task->mode_of_payment,
                
            );

            fputcsv($file, $row);
        }

        fclose($file);
    };

    return response()->stream($callback, 200, $headers);
}
public function cancelList()
    {
		
		/*$cancelList=   $pendingPassengers = DB::table('messages_passengers')
    ->join('branch', 'messages_passengers.messageid', '=', 'branch.id')
    ->select('messages_passengers.*', 'branch.branch_name')
    ->where('messages_passengers.status', 'pending')
    ->get();*/
$cancelList = DB::table('messages_passengers')
    ->join('branch', 'messages_passengers.messageid', '=', 'branch.id')
    ->join('passenger_bookings', 'passenger_bookings.booking_id', '=', 'messages_passengers.booking_id')
    ->select(
        DB::raw('MAX(messages_passengers.id) as id'),
        'messages_passengers.booking_id',
		DB::raw('SUBSTRING_INDEX(GROUP_CONCAT(branch.id ORDER BY branch.branch_name DESC), ",", 1) as branchid'),
        DB::raw('MAX(branch.branch_name) as branch_name'),
        'messages_passengers.request_date',
        DB::raw('MAX(passenger_bookings.departure_datetime) as departure_datetime'),
        DB::raw('MAX(passenger_bookings.arrivel_citycode) as arrivel_citycode'),
        DB::raw('MAX(passenger_bookings.departure_citycode) as departure_citycode'),
        DB::raw('MAX(passenger_bookings.passengertype) as passengertype'),
		DB::raw('MAX(passenger_bookings.mobile) as mobile'),
		DB::raw('MAX(passenger_bookings.PNR) as pnr_number')
    )
    ->where('messages_passengers.status', 'pending')
    ->groupBy('messages_passengers.booking_id', 'messages_passengers.request_date')
    ->get();


        return view('admin.cancelList',compact('cancelList'));
    }
	public function cancelBooking(Request $request)
    {
		 $bookingId = $request->input('bookingId');
		$messages = DB::table('messages_passengers')
    ->join('branch', 'branch.id', '=', 'messages_passengers.messageid')
    ->select('messages_passengers.*', 'branch.branch_name')
    ->where('messages_passengers.booking_id', $bookingId)
    ->where('messages_passengers.status', 'pending')
    ->get();

$messageComments = DB::table('messages_comments')
    ->where('booking_id', $bookingId)
    ->get();
        // Return messages as JSON
        return response()->json(['messages' => $messages,'messageComments' => $messageComments]);
	}	
public function submitCancellation(Request $request)
{
	 $cancellationAmount = $request->input('cancellationAmount');
    $bookingId = $request->input('bookingId');
   

    // Update messages_passengers table
    DB::table('messages_passengers')
        ->where('booking_id', $bookingId)
        ->update([
            'status' => 'cancelled',
            'comments' => 'CANCELATION DONE',
            'processed_date' => Carbon::now(), // Set to today's date
        ]);

    // Insert into messages table
    DB::table('messages')->insert([
        'cancellation_amt' => $cancellationAmount,
        'Bookingid' => $bookingId,
        'processdate' => Carbon::now(),
        'processedby' => 1,		// Set to today's date
    ]);

    // You can return a response if needed
    return response()->json(['message' => 'Cancellation submitted successfully']);
}
public function submitMessage(Request $request)
{
	$user = Auth::user();
    $userid = $user->id;
    $branchid = $user->branch_id;
    $bookingId = $request->input('bookingId');
    $comments = $request->input('messages'); // Updated input name to 'messagerequest'
    $pnr = $request->input('pnr');

    $data = [
        'pnr' => $pnr,
        'booking_id' => $bookingId,
        'txpo_user_id' => $userid,
		'branch_user_id' => $branchid,
    ];

    // Check branch id and add appropriate comments field
    if ($branchid == 1) {
        $data['txpo_comments'] = $comments;
        $data['customer_date'] = now()->toDateString();
    } else {
        $data['customer_comments'] = $comments;
        $data['customer_date'] = now()->toDateString();
    }

    DB::table('messages_comments')->insert($data);

    // You can return a response if needed
    return response()->json(['message' => 'Message/Comments submitted successfully']);
}
}
