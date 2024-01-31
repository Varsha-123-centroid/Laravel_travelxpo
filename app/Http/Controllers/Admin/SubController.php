<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class SubController extends Controller
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
		$username =  Auth::user()->username;
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

				
	$avilablebal = DB::table('cash_balance')
    ->select('balance')
    ->where('branch_id', '=', $branchId)
    ->orderBy('id', 'desc')
    ->limit(1)
    ->value('balance');
// Convert the email to base64 encoding
      $base64Email = base64_encode($email);
        return view('sub.dashboard',compact('base64Email','branchId','avilablebal','bookings','agentCode','username','customers','branchId'));
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
	public function subMarkupList()
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

		 $list = DB::table('markup_percent')
    ->where('markup_for', '=', 'sub')
	->where('branchid', $branchId)
	->orderBy('id','desc')
    ->get(); 
		return view('admin.setMarkuplist',compact('list'));
	}
public function subAgentSave(Request $request)
{
    // Get form data from the request
    $formData = $request->all();
    $user=Auth::User();
	$agid=$user->agentid;
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

    // Save the user data
    DB::table('users')->insert([
        'username' => $username,
        'password' => $password,
		'name' => $formData['txt_agency_name'],
        'role' => 3,
        'agent_type' => 'sub',
        'agentid' => $agentId,
		'email'  => $agencymail,
		'created_at'  => Carbon::now(),
		'updated_at'  => Carbon::now(),
    ]);
	 DB::table('branch')->insert([
        'user_type' => 'sub',
        'branch_name' => $formData['txt_branch'],
        'user_id' => $agentId,
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
public function operationStaff()
    {
		$countries = DB::table('countries')->select('id', 'country_name')->get();
        $currency = DB::table('currency')->select('id', 'currency_code')->get();
		$timezones = timezone_identifiers_list();
		
        return view('sub.operationStaff',compact('countries','currency','timezones'));
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
        'role' => 3,
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
    return view('sub.operationStaffList', compact('stafflist'));
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
     
	return view('sub.operationStaffEdit', compact('staffData','countries','userRole'));
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
 public function checkUsername(Request $request)
    {
        $username = $request->input('username');
        
       $user = DB::table('users')->where('username', $username)->first();
       
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
	$agents = DB::table('sub_agents')->select('company_name', 'mobile_number','business_vol_category','id')->get();
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
       
return view('agent.makePayment',compact('bal'));

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
}
