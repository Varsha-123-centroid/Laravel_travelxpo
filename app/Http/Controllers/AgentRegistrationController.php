<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use DB;
class AgentRegistrationController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
   

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
		$countries = DB::table('countries')->select('id', 'country_name')->get();
        $currency = DB::table('currency')->select('id', 'currency_code')->get();
		$timezones = timezone_identifiers_list();
		//$timezones = DB::table('time_zone')->select('id', 'zone_name')->get();
        return view('admin.home',compact('countries','currency','timezones'));
    }
	  public function agentNewRegistration(Request $request)
    {
		
    // Get form data from the request
    $formData = $request->all();
 $password =  Hash::make($formData['txt_password_text']);

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
     
        'currency' => $formData['sel_currency'],
        'time_zone' => $formData['sel_timezone'],
        'status' =>0,
        'sales_manager' => $formData['txt_sales_mgr'],
        'consultant' => $formData['txt_consultant'],
		'business_vol_category' => $formData['sel_category'],
		'view_username' => $formData['txt_username'],
		'view_password' => $formData['txt_password_text'],
		'hash_password' => $password,
    ]);

    
	$insertedbranchId = DB::table('branch')->insertGetId([
        'user_type' => 'main',
        'branch_name' => $formData['txt_branch'],
        'user_id' => $agentId,
		'parent_type' => 'txpo',
		'parent_id' => 1,
    ]);
  
 
 
	
        // Redirect or perform other actions upon successful registration
      return response()->json(['success' => 'Agent added successfully']);
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
}
