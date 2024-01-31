<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AgentController extends Controller
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
		
		$email = Auth::user()->email;
       $agentid = Auth::user()->agentid;
	    $agenttype = Auth::user()->agent_type;
		$username = Auth::user()->username;
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
		
	   $branchId = DB::table('branch')
                ->where('user_type', $agenttype)
                ->where('user_id', $agentid)
                ->value('id');
			
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
                AND DATE(passenger_bookings.departure_datetime) BETWEEN '$fromDate' AND '$toDate'
                UNION
                SELECT passenger_bookings.*, 1 AS customer_id, 'CASH' AS customer_name, '' AS customer_gst
                FROM passenger_bookings
                WHERE passenger_bookings.branch_id = $branchId
                AND passenger_bookings.cust_id = 1
                AND passenger_bookings.total_ticket_fare > 0
                AND DATE(passenger_bookings.departure_datetime) BETWEEN '$fromDate' AND '$toDate') as xxx"))
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

		
// Convert the email to base64 encoding
	$avilablebal = DB::table('cash_balance')
    ->select('balance')
    ->where('branch_id', '=', $branchId)
    ->orderBy('id', 'desc')
    ->limit(1)
    ->value('balance');
      $base64Email = base64_encode($email);
	 
        return view('agent.dashboard',compact('base64Email','branchId','avilablebal','bookings','agentCode','username','customers','branchId'));
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
	    public function addSubAgent()
    {
		$countries = DB::table('countries')->select('id', 'country_name')->get();
        $currency = DB::table('currency')->select('id', 'currency_code')->get();
		$timezones = timezone_identifiers_list();
		
        return view('agent.addSubAgent',compact('countries','currency','timezones'));
    }
public function subAgentSave(Request $request)
{
    // Get form data from the request
    $formData = $request->all();
    $user=Auth::User();
	$agid=$user->agentid;
	$usertype=$user->agent_type;
	
	$results = DB::table('branch')
    ->select('id', 'user_type')
    ->where('user_type', $usertype)
    ->where('user_id', $agid)
     ->first();
	
	       $parentid = $results->id;
		   $parenttype = $results->user_type;
		 
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


    //$iataStatus = isset($formData['rad_iata_status']) ? $formData['rad_iata_status'] : 0;
    $accounttype = isset($formData['rad_accounttype']) ? $formData['rad_accounttype'] : 0;
    $distribution = isset($formData['rad_distribution']) ? $formData['rad_distribution'] : 0;

    // Save the agent data
    $agentId = DB::table('sub_agents')->insertGetId([
	     'agent_id' => $agid,
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
        'iata_status' =>$formData['rad_iata_status'],
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
        'user_type' => 'sub',
        'branch_name' => $formData['txt_branch'],
        'user_id' => $agentId,
		'parent_type' => $parenttype,
		'parent_id' => $parentid,
		'invoice_prefix' => '2023-',
		'invoice_lastnumber' => 1000,
    ]);   
    // Save the user data
    DB::table('users')->insert([
        'username' => $username,
        'password' => $password,
		'name' => $formData['txt_agency_name'],
        'role' => 3,
        'agent_type' => 'sub',
		'status_type' => 'admin',
		'branch_id' => $insertedbranchId,
        'agentid' => $agentId,
		'email'  => $agencymail,
		'created_at'  => Carbon::now(),
		'updated_at'  => Carbon::now(),
    ]);
	
 $paymentid= DB::table('payment')->insertGetId([
    'branchid' => $insertedbranchId,
    'payment_amt' => $formData['txt_credit'],
    'payment_due' => 0,
    'status'=>0,
	'mode_of_payment'=>'credit limit',
	'suplier_category' => $parentid
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
        'agent_type' => 'sub',
    ]);
        }
    // Redirect or return a response
    // You can modify this based on your application's requirements
  return response()->json(['success' => 'SubAgent added successfully']);
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
	public function checkUsernameOperationStaff(Request $request)
    {
        $username = $request->input('username');
        
       $user = DB::table('operation_staff')->where('username', $username)->first();
       
         if ($user) {
        return response()->json(['status' => 'exists', 'message' => 'Username already exists.']);
    }

    return response()->json(['status' => 'available', 'message' => 'Username is available.']);
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
	public function subAgentList()
	{ 
   $agentid = Auth::user()->agentid;
	$agents = DB::table('sub_agents')
    ->select('company_name', 'mobile_number', 'business_vol_category', 'id')
    ->where('agent_id', '=', $agentid)
    ->get();
    return view('agent.subAgentList', compact('agents'));
	}
	public function subAgentEdit(Request $request)
	{
		$id = $request->id;
		$agentData = DB::table('sub_agents')
    ->where('id', '=', $id)
    ->first();

$branchData = DB::table('branch')
    ->where('user_type', '=', 'sub')
    ->where('user_id', '=', $id)
    ->first();

$agentRightsData = DB::table('agent_rights')
    ->where('agent_user_id', '=', $id)
    ->where('agent_type', '=', 'sub')
    ->get(); 
	
		$countries = DB::table('countries')->select('id', 'country_name')->get();
        $currency = DB::table('currency')->select('id', 'currency_code')->get();
		$timezones = timezone_identifiers_list();
	return view('agent.editSubAgent', compact('agentData','branchData','agentRightsData','countries','currency','timezones'));
	}
	public function subAgentUpdate(Request $request)
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

    
    DB::table('sub_agents')
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
        'iata_status' =>  $formData['rad_iata_status'],
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
		'user_type' => 'sub',
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

foreach ($rights as $key => $name) {
    $value = in_array($key, $request->input('rights', [])) ? 'yes' : 'no';

    DB::table('agent_rights')
        ->where('agent_user_id', $agentId)
        ->where('right_name', $name)
        ->where('agent_type', 'sub')
        ->update([
            'right_acess' => $value,
        ]);
}


    // Redirect or return a response
    // You can modify this based on your application's requirements
  return response()->json(['success' => 'Agent Details updated successfully']);
	}
	public function setMarkup()
	{
		$countries = DB::table('countries')->select('id', 'country_name')->get();
		return view('admin.setMarkup',compact('countries'));
	}
	public function markupSave(Request $request)
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
    "agent_type" => 'main',
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
	}
	    public function makePayment()
    { 
		  $userId = Auth::user()->agentid;
          $agenttype =Auth::user()->agent_type;
          $supplier = DB::table('branch AS b1')
    ->join('branch AS b2', function ($join) {
        $join->on('b1.parent_id', '=', 'b2.id')
             ->on('b1.parent_type', '=', 'b2.user_type');
    })
    ->where('b1.user_type', '=', $agenttype)
    ->where('b1.user_id', '=', $userId)
    ->select('b2.id', 'b2.branch_name')
    ->get();
    if ($userId) {
       $branchdetails = DB::table('branch')
    ->where('user_type', '=', $agenttype)
    ->where('user_id', '=', $userId)
    ->first();
	
	$branchId =$branchdetails->id;
	
	if ($branchId) {
        $balance = DB::table('cash_balance')
            ->where('branch_id', $branchId)
			->latest('id')
            ->value('balance');
    }
    }

if ($balance === null) {
    $bal = 0;
}

else{
	$bal=$balance;
}
       
return view('agent.makePayment',compact('bal','supplier'));

    }
	public function paymentSave(Request $request)
	{
		$userId = Auth::user()->agentid;
        $type = Auth::user()->agent_type;

if ($userId) {
    $branchId = DB::table('branch')
        ->where('user_id', $userId)
		->where('user_type', $type)
        ->value('id');
}
		 $formData = $request->all();
		 DB::table('payment')->insert([
        'suplier_category' => $formData['supplier'],
        'date_of_payment' => $formData['datepayment'],
        'particulars' => $formData['particulars'],
        'payment_due' => $formData['paymentdue'],
		'payment_amt' => $formData['amount'],
        'rate_of_excahnge' => $formData['exchange'],
        'mode_of_payment' => $formData['mode'],
        'bank_name' => $formData['bankname'],
		'reference_no' => $formData['refno'],
		'status' =>  $formData['status'],
		'branchid' => $branchId,
    ]);
        
    // Redirect or return a response
    // You can modify this based on your application's requirements
  return response()->json(['success' => 'Request Payment have been done successfully']);
	}
	public function checkCreditLimitAgent(Request $request)
{
    $agentid = Auth::user()->agentid;
    $agentCreditLimit = DB::table('agents')
        ->where('id', $agentid)
        ->value('credit_limit');
		
    $sumOfSubAgentCreditLimits = DB::table('sub_agents')
        ->where('agent_id', $agentid)
        ->select(DB::raw('SUM(credit_limit) as total_credit_limit'))
        ->first();

     $sumOfSubAgentCreditLimits = $sumOfSubAgentCreditLimits->total_credit_limit;

    $enteredCreditLimit = $request->input('creditLimit');

    if ($enteredCreditLimit > ($agentCreditLimit - $sumOfSubAgentCreditLimits)) {
        return response()->json(['error' => 'Credit limit exceeded. Please enter a lower value.']);
    }

    return response()->json(['success' => true]);
}
//REPORTS.....................//

public function dailySalesReport()
{
	$user = Auth::user();
    $email = $user->email;
    $agentid = $user->agentid;
    $agenttype = $user->agent_type;

    // Retrieve the branch ID based on user's agent type and ID
    /*$branchId = DB::table('branch')
        ->where('user_type', $agenttype)
        ->where('user_id', $agentid)
        ->value('id','parent_id');*/
		$result = DB::table('branch')
    ->where('user_type', $agenttype)
    ->where('user_id', $agentid)
    ->select('id', 'parent_id')
    ->first();
	if ($result) {
    $branchId = $result->id;
    $parentId = $result->parent_id;
} else {
    // Handle the case where no matching record was found
    $branchId = null;
    $parentId = null;
}
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
        passenger_bookings.branch_id = $branchId
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
        branch.parent_id = $parentId
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
if ($totalAgentSales != 0) {
    $profitPercentage = ($profit / $totalAgentSales) * 100;
} else {
    // Handle the case where $totalAgentSales is zero
    $profitPercentage = 0;  // or any other default value
}
 

	
	return view('admin.dailySalesReport',compact('list','totalAgentSales','totalSupplierPrice','profit','profitPercentage'));
	
}
  public function exportDailySales(Request $request)
{
    $fileName = 'DailySaleReport.csv';
    $user = Auth::user();
    $email = $user->email;
    $agentid = $user->agentid;
    $agenttype = $user->agent_type;

   $result = DB::table('branch')
    ->where('user_type', $agenttype)
    ->where('user_id', $agentid)
    ->select('id', 'parent_id')
    ->first();
	if ($result) {
    $branchId = $result->id;
    $parentId = $result->parent_id;
} else {
    // Handle the case where no matching record was found
    $branchId = null;
    $parentId = null;
}

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
        passenger_bookings.branch_id = $branchId
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
        branch.parent_id = $parentId
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
 public function addCancel()
    {
		 return view('agent.addCancel');
    }
	public function getTicketsAfterToday(Request $request)
{
    $currentTime = now();
    $bookings = DB::table('passenger_bookings')
        ->where('departure_datetime', '>', $currentTime)
        ->where(function ($query) use ($request) {
            $query->where('email', $request->input('ephone'))
                ->orWhere('mobile', $request->input('ephone'));
        })
        ->where('isLCC', 1) 
		->whereNull('cancel_status')
        ->get();

    
    return response()->json($bookings);
}
public function getPassengerDetails(Request $request)
{
    // "PNR": "DLVNJH","BookingId": 1857173,
    $bookingId = $request->input('bookingId');   
    $pnr = $request->input('pnr');
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
        return response()->json(['error' => 'Curl error: ' . curl_error($ch)], 500);
    }

    // Close the cURL session
    curl_close($ch);

    $result = (array) json_decode($response);
    $flightItinerary = $result['Response']->FlightItinerary;

    $passengerDetails = [];
    foreach ($flightItinerary->Passenger as $passenger) {
        // Extracting the required information
        $passengerName = $passenger->Title . ' ' . $passenger->FirstName . ' ' . $passenger->LastName;
        $ticketId = $passenger->Ticket->TicketId;
        $ticketNumber = $passenger->Ticket->TicketNumber;
        $pnrNumber = $passenger->Ticket->TicketNumber; // Assuming PNR is stored in TicketNumber
        $mobileNumber = $passenger->ContactNo;
        $email = $passenger->Email;
        $bookingId = $bookingId;

        
        $passengerDetails[] = [
            'Passenger Name' => $passengerName,
            'Ticket Id' => $ticketId,
            'Ticket Number' => $ticketNumber,
            'PNR Number' => $pnrNumber,
            'Mobile Number' => $mobileNumber,
            'Email' => $email,
            'Bookin ID' => $bookingId,
        ];
    }

    // Return JSON response with passenger details
    return response()->json(['passengerDetails' => $passengerDetails]);
}
public function storePassengerDetails(Request $request)
    {
        $passengerDetailsArray = $request->input('passengerDetails');
		
        $branchid = Auth::user()->branch_id;
          $branchuserid = Auth::user()->id; 
        foreach ($passengerDetailsArray as $passengerDetails) {
            // Using DB facade to insert data into messages_passengers table for each passenger
            DB::table('messages_passengers')->insert([
                'passengername' => $passengerDetails['Passenger Name'],
                'ticketid' => $passengerDetails['Ticket Id'],
                'messageid' => $branchid,
                'status' => 'pending',
                'comments' => 'CANCELATION REQUEST',
                'ticket_number' => $passengerDetails['Ticket Number'],
                'pnr_number' => $passengerDetails['PNR Number'],
                'mobile_number' => $passengerDetails['Mobile Number'],
                'email' => $passengerDetails['Email'],
                'booking_id' => $passengerDetails['Bookin ID'],
                'request_date' => now()->toDateString(),
				'branch_user_id' => $branchuserid,
            ]);
        }

        return response()->json(['success' => true]);
    }
	public function commentsList(Request $request)
    {
		 $bookingId = $request->input('bookingId');
		
$messageComments = DB::table('messages_comments')
    ->where('booking_id', $bookingId)
    ->get();  
        // Return messages as JSON
        return response()->json(['messageComments' => $messageComments]);
	}	
}
