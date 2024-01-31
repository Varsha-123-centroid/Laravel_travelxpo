@extends('layouts.admin.default')

@section('content')
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">
                        
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0">EDIT AGENT</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

			<div class="row">
			<form id="formup"  data-id="{{ $formData->id ?? '' }}" enctype="multipart/form-data">
			@csrf
				<div class="col-md-12 p-0">
					
					
						<div class="edit_profileSec">
							<div class="editProfileForm">
							<h5 class="">Agency Information</h5>
								<div class="clearDiv row">
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
										<label>Agency Name<span class="mandatory">*</span></label>
										<input type="text" name="txt_agency_name" id="txt_agency_name" value="{{$agentData->company_name}}" title="Company Name" placeholder="Company Name" class="form-control" autocomplete="none">
										<input type="hidden" name="editid" id="editid" value="{{$agentData->id}}" >
									</div>
									
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
  <label>Company (Agency Email)</label>
  <input type="email" name="txt_agency_email" id="txt_agency_email" value="{{$agentData->company_email}}" maxlength="20" class="form-control txt_iata_number itaDisabled" title="IATA number" placeholder="Company Email" autocomplete="none" oninput="validateEmail('txt_agency_email', 'agency-email-error')">
  <span id="agency-email-error" class="error-message"></span>
</div>

									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
										<label>Accounding ID</label>
										<input type="text" name="txt_account_id" id="txt_account_id" class="form-control" value="{{$agentData->company_reg_no}}" title="Company Reg.No" placeholder="Company Reg. No" autocomplete="none">
									</div>
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
										<label>First Name<span class="mandatory">*</span></label>
										<input type="text" name="txt_agency_fname" id="txt_agency_fname" value="{{$agentData->first_name}}" title="Company Name" placeholder="First Name" class="form-control" autocomplete="none">
									</div>
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
										<label>Middle Name<span class="mandatory">*</span></label>
										<input type="text" name="txt_agency_mname" id="txt_agency_mname" value="{{$agentData->middle_name}}" title="Company Name" placeholder="Middle Name" class="form-control" autocomplete="none">
									</div>
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
										<label>Last Name</label>
										<input type="text" name="txt_agency_lname" id="txt_agency_lname" value="{{$agentData->last_name}}" maxlength="20" class="form-control txt_iata_number itaDisabled" title="IATA number" placeholder="Last Name" autocomplete="none">
									</div>
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
										<label>Designation</label>
										<input type="text" name="txt_agency_designation" id="txt_agency_designation" class="form-control" value="{{$agentData->designation}}" title="Company Reg.No" placeholder="Designation" autocomplete="none">
									</div>
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
										<label>Nature of Business</label>
										<div class="input_icon">
											<select class="form-control" name="sel_nature_of_business" id="sel_nature_of_business">
												<option value="0">Select</option>
												<option value="Destination Management Company"
												@if($agentData->nature_of_business=='Destination Management Company') selected @endif>Destination Management Company</option>
												<option value="Tour Operator"@if($agentData->nature_of_business=='Tour Operator') selected @endif>Tour Operator</option>
												<option value="Travel Agent" @if($agentData->nature_of_business=='Travel Agent') selected @endif>Travel Agent</option>
												<option value="Wholesale Travel Company"
												 @if($agentData->nature_of_business=='Wholesale Travel Company') selected @endif>Wholesale Travel Company</option>
												<option value="Corporate"@if($agentData->nature_of_business=='Corporate') selected @endif>Corporate</option>
											</select>
											<span class="editProfileSelect"><i class="fa fa-caret-down" aria-hidden="true"></i></span>
										</div>
									</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group input_dv">
    <label>Agent Logo</label>
    <div class="">
         <input type="file" class="form-control" id="agent_logo" name="agent_logo" />
		 <input type="hidden" name="current_file_logo" value="{{$agentData->company_logo}}">
		 @if(!empty($agentData->company_logo))
		 <a href="{{asset('uploads/agent/'.$agentData->company_logo)}}" download>
  <img src="{{asset('uploads/agent/'.$agentData->company_logo) }}" download alt="Agent Image" style="width: 180px; height: 180px;">

</a>@endif
    </div>
</div>
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group input_dv">
										<label>Profile Image
										
										</label>
																				
										<div class="">
				  	   <input type="file" class="form-control" id="agent_image" name="agent_image"  />
					 <input type="hidden" name="current_file_Image" value="{{$agentData->profile_image}}">
	 @if(!empty($agentData->profile_image))	 <a href="{{asset('uploads/agent/'.$agentData->profile_image)}}" download>
  <img src="{{asset('uploads/agent/'.$agentData->profile_image) }}" download alt="Agent Image" style="width: 180px; height: 180px;">

</a>@endif
					</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
										<label>Address<span class="mandatory">*</span></label>
										<input type="text" name="txt_address" class="form-control" value="{{$agentData->address}}" placeholder="Address" title="Address" id="txt_address" autocomplete="none">
									</div>
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
										<label>Country<span class="mandatory">*</span></label>
										<div class="input_icon">
												<select class="form-control" name="sel_country" id="sel_country"  title="India">   <option value="0">Select</option>
    @foreach ($countries as $country)
	
        <option value="{{ $country->id }}" @if($country->id==$agentData->country_id) selected @endif>{{ $country->country_name }}</option>
    @endforeach
</select>																																               
											<span class="editProfileSelect"><i class="fa fa-caret-down" aria-hidden="true"></i></span>
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
										<label>City<span class="mandatory">*</span></label>
										<div class="input_icon">
										<input type="text" name="sel_city" class="form-control" value="{{$agentData->city}}" placeholder="Enter City" title="City" id="sel_city">
											<span class="editProfileSelect"><i class="" aria-hidden="true"></i></span>
										</div>
									
									</div>
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
										<label>Pincode/Zipcode</label>
										<input type="text" name="txt_pincode" id="txt_pincode" class="form-control" value="{{$agentData->postal_code}}" maxlength="6" title="Pincode/Zipcode" placeholder="Pincode/Zipcode"  autocomplete="none">
									</div>
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
										<div class="approvalRadioBtn">
											<label>IATA Status</label>
											<div class="edit_Profile d-flex">
											 @if(!empty($agentData->iata_status))	
												<div class="radio-inline iataStatus">
													<input type="radio" id="approve_iata" name="rad_iata_status" value="1" @if(1==$agentData->iata_status) checked @endif>
													<label for="approve_iata" class="pt-2">  Approved</label>
												</div>
												<div class="radio-inline iataStatus">
													<input type="radio" id="not_approve_iata" name="rad_iata_status" value="0" @if(0==$agentData->iata_status) checked @endif>
													<label for="not_approve_iata"  class="pt-2 pl-1">  Non-Approved</label>
												</div>
@else
		<div class="radio-inline iataStatus">
													<input type="radio" id="approve_iata" name="rad_iata_status" value="1" >
													<label for="approve_iata" class="pt-2">  Approved</label>
												</div>
												<div class="radio-inline iataStatus">
													<input type="radio" id="not_approve_iata" name="rad_iata_status" value="0" >
													<label for="not_approve_iata"  class="pt-2 pl-1">  Non-Approved</label>
												</div>	@endif								</div>
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
		                                   <div class="contactDlts row">
		                                   
		                                     <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                                         <label>Phone Number<span class="mandatory">*</span></label>
		                                         	<input type="text" name="txt_phone" value="{{$agentData->phonenumber}}" maxlength="15" title="Phone Number" placeholder="Phone Number With Code" class="form-control edit_phoneNumber phNumber" autocomplete="none">
											</div>
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
	                                       <div class="contactDlts row">
	                                       
	                                         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                                             <label>Mobile Number<span class="mandatory">*</span></label>
	                                             	<input type="text" name="txt_mobile" value="{{$agentData->mobile_number}}" maxlength="10" title="Mobile Number" placeholder="Mobile Number with code" class="form-control edit_phoneNumber phNumber"  autocomplete="none">
	                                         </div>
	                                    </div>
									</div>
								
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
	                                     <div class="contactDlts row">
	                                        
	                                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                                             <label>Fax Number</label>
	                                             	<input type="text" name="txt_fax" value="{{$agentData->fax_number}}" maxlength="10" title="Fax No." placeholder="Fax Number with code" class="form-control edit_phoneNumber phNumber" autocomplete="none">
	                                             </div>
	                                      </div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
										<label>Website</label>
										<input type="text" name="txt_website" value="{{$agentData->website}}" title="Website" placeholder="Website" class="form-control" autocomplete="none">
									</div>
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
										<label>Business Volume Category<span class="mandatory">*</span></label>
										<div class="input_icon">
												<select class="form-control" name="sel_category" id="sel_category"  title="sel_category">   <option value="0">Select Category</option>
    <option value="General" @if($agentData->business_vol_category=='General') selected @endif>General</option>
	
</select>																																               
											<span class="editProfileSelect"><i class="fa fa-caret-down" aria-hidden="true"></i></span>
										</div>
									</div>

								</div>
							</div>
							
							<div class="editProfileForm">
								<h5 class="">Agency Contact informoation</h5>
								<div class="clearDiv row financeTlt">
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
										<h4>Accounts</h4>
										<div class="row">
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
												<label>Name</label>
												<input type="text" name="txt_acc_name" value="{{$agentData->agency_account_name}}" maxlength="100" title="Name" placeholder="Name" class="form-control" autocomplete="none">
											</div>
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
  <label>Email</label>
  <input type="email" name="txt_acc_email" id="txt_acc_email" value="{{$agentData->agency_account_email}}" maxlength="100" title="Email Address" placeholder="Email Address" class="form-control" autocomplete="none" oninput="validateEmail('txt_acc_email', 'acc-email-error')">
  <span id="acc-email-error" class="error-message"></span>
</div>

											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
												<label>Phone Number</label>
												<input type="text" name="txt_acc_ph" value="{{$agentData->agency_account_phoneno}}" maxlength="100" title="Email Address" placeholder="Phone Number" class="form-control" autocomplete="none">
											</div>
										</div>
									</div>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
										<h4>Reservations</h4>
										<div class="row">
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
												<label>Name</label>
												<input type="text" name="txt_res_name" value="{{$agentData->agency_reservation_name}}" maxlength="100" title="Name" placeholder="Name" class="form-control" autocomplete="none">
											</div>
										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
  <label>Email</label>
  <input type="email" name="txt_res_email" id="txt_res_email" value="{{$agentData->agency_reservation_email}}" maxlength="100" title="Email Address" placeholder="Email Address" class="form-control" autocomplete="none" oninput="validateEmail('txt_res_email', 'res-email-error')">
  <span id="res-email-error" class="error-message"></span>
</div>


											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
												<label>Phone Number</label>
												<input type="text" name="txt_res_ph" value="{{$agentData->agency_reservation_phoneno}}" maxlength="100" title="Email Address" placeholder="Phone Number" class="form-control" autocomplete="none">
											</div>
										</div>
									</div>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
										<h4>Management </h4>
										<div class="row">
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
												<label>Name</label>
												<input type="text" name="txt_mgt_name" value="{{$agentData->agency_mgt_name}}" maxlength="100" title="Name" placeholder="Name" class="form-control" autocomplete="none">
											</div>
										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
  <label>Email</label>
  <input type="email" name="txt_mgt_email" id="txt_mgt_email" value="{{$agentData->agency_mgt_email}}" maxlength="100" title="Email Address" placeholder="Email Address" class="form-control" autocomplete="none" oninput="validateEmail('txt_mgt_email', 'mgt-email-error')">
  <span id="mgt-email-error" class="error-message"></span>
</div>
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
												<label>Phone Number</label>
												<input type="text" name="txt_mgt_ph" value="{{$agentData->agency_mgt_ph}}" maxlength="100" title="Email Address" placeholder="Phone Number" class="form-control" autocomplete="none">
											</div>
										</div>
									</div>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
										<label>Remarks</label>
										<textarea type="text" name="txt_acc_remarks" value="" rows="3" title="Name" placeholder="Name" class="form-control" autocomplete="none">{{$agentData->agency_contactinfo_remarks}}</textarea>
									</div>
								</div>
							</div>
							
							<div class="editProfileForm">
								<h5 class="">Agency Setting</h5>
								<div class="clearDiv row financeTlt">
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
										<label>Currency<span class="mandatory">*</span></label>
										<div class="input_icon">
											<select class="form-control" name="sel_currency" id="sel_currency"  title="India">
											  <option value="0">Select</option>
    @foreach ($currency as $currencies)
	 
        <option value="{{ $currencies->id }}" @if($currencies->id==$agentData->currency) selected @endif>{{ $currencies->currency_code }}</option>
    @endforeach
</select>
											<span class="editProfileSelect"><i class="fa fa-caret-down" aria-hidden="true"></i></span>
										</div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
										<label>Time Zone<span class="mandatory">*</span></label>
										<div class="input_icon">
											<select class="form-control" name="sel_timezone" id="sel_timezone"  title="India"> <option value="0">Select</option>
    @foreach ($timezones as $timezone)
	  
         <option value="{{ $timezone }}" @if($timezone==$agentData->time_zone) selected @endif>{{ $timezone }}</option>
    @endforeach
</select>
											<span class="editProfileSelect"><i class="fa fa-caret-down" aria-hidden="true"></i></span>
										</div>
									</div>
									
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
										<div class="approvalRadioBtn">
											<label>Account Type</label>
											<div class="edit_Profile d-flex">
												<div class="radio-inline iataStatus">
													<input type="radio" id="approve_iata" name="rad_accounttype" value="1" @if(1==$agentData->agent_money_type) checked @endif>
													<label for="approve_iata" class="pt-2">  credit</label>
												</div>
												<div class="radio-inline iataStatus">
													<input type="radio" id="not_approve_iata" name="rad_accounttype" value="0"  @if(0==$agentData->agent_money_type) checked @endif>
													<label for="not_approve_iata"  class="pt-2 pl-1"> Cash</label>
												</div>

											</div>
										</div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
										<label>Credit Limit</label>
										<input type="text" name="txt_credit" id="txt_credit" class="form-control" value="{{$agentData->credit_limit}}" maxlength="6" title="Credit Limit" placeholder=""  autocomplete="none">
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
										<label>Temp Credit Limit</label>
										<input type="text" name="temp_credit" id="temp_credit" class="form-control" value="{{$agentData->temp_credit_limit}}" maxlength="6" title="Credit Limit" placeholder=""  autocomplete="none">
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
										<div class="approvalRadioBtn">
											<label>Credit Distribution</label>
											<div class="edit_Profile d-flex">
												<div class="radio-inline iataStatus">
													<input type="radio" id="approve_iata" name="rad_distribution" value="1" @if(1==$agentData->	credit_distribution) checked @endif>
													<label for="approve_iata" class="pt-2">  Yes</label>
												</div>
												<div class="radio-inline iataStatus">
													<input type="radio" id="not_approve_iata" name="rad_distribution" value="0" @if(0==$agentData->	credit_distribution) checked @endif>
													<label for="not_approve_iata"  class="pt-2 pl-1">  No</label>
												</div>

											</div>
										</div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
										<label>Branch<span class="mandatory">*</span></label>
										<div class="input_icon">
											<input type="text" name="txt_branch" value="{{$branchData->branch_name}}" maxlength="100" title="Name" placeholder="Enter Branch" class="form-control" autocomplete="none">
											<span class="editProfileSelect"><i class="" aria-hidden="true"></i></span>
										</div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
										<label>Sales Manager</label>
										<div class="input_icon">
										<input type="text" name="txt_sales_mgr" value="{{$agentData->sales_manager}}" maxlength="100" title="Name" placeholder="Enter Branch" class="form-control" autocomplete="none">
											<span class="editProfileSelect"><i class="" aria-hidden="true"></i></span>
										</div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
										<label>Consultant<span class="mandatory">*</span></label>
										<div class="input_icon">
										<input type="text" name="txt_consultant" value="{{$agentData->	consultant}}" maxlength="100" title="Name" placeholder="Enter Branch" class="form-control" autocomplete="none">
											<span class="editProfileSelect"><i class="" aria-hidden="true"></i></span>
										</div>
									</div>
									
								</div>
								
								
								<h5 class="">Rights</h5>
								<div class="clearDiv row">
								<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-group">
    <div class="addStaff">
        @if(!empty($agentRightsData[0]->right_acess))
            @if($agentRightsData[0]->right_acess == 'yes')
                <div class="checkbox checkbox-inline">
                    <input id="allowCancel" type="checkbox" name="rights[]" value="multi_currency_search" autocomplete="none" checked>
                    <label for="allowCancel">Multi Currency Search</label>
                </div>
            @else
                <div class="checkbox checkbox-inline">
                    <input id="allowCancel" type="checkbox" name="rights[]" value="multi_currency_search" autocomplete="none">
                    <label for="allowCancel">Multi Currency Search</label>
                </div>
            @endif
        @else
            <div class="checkbox checkbox-inline">
                <input id="allowCancel" type="checkbox" name="rights[]" value="multi_currency_search" autocomplete="none">
                <label for="allowCancel">Multi Currency Search</label>
            </div>
        @endif
    </div>
</div>
    	<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-group">
    <div class="addStaff">
        @if(!empty($agentRightsData[1]->right_acess))
            @if($agentRightsData[1]->right_acess == 'yes')
                <div class="checkbox checkbox-inline">
                    <input id="allowCancel" type="checkbox" name="rights[]" value="allow_create_quotations" autocomplete="none" checked>
                    <label for="allowCancel">Allow Create Quotations</label>
                </div>
            @else
                <div class="checkbox checkbox-inline">
                    <input id="allowCancel" type="checkbox" name="rights[]" value="allow_create_quotations" autocomplete="none">
                    <label for="allowCancel">Allow Create Quotations</label>
                </div>
            @endif
        @else
            <div class="checkbox checkbox-inline">
                <input id="allowCancel" type="checkbox" name="rights[]" value="allow_create_quotations" autocomplete="none">
                <label for="allowCancel">Allow Create Quotations</label>
            </div>
        @endif
    </div>
</div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-group">
    <div class="addStaff">
        @if(!empty($agentRightsData[2]->right_acess))
            @if($agentRightsData[2]->right_acess == 'yes')
                <div class="checkbox checkbox-inline">
                    <input id="allowCancel" type="checkbox" name="rights[]" value="allow_non_refundable_bookings" autocomplete="none" checked>
                    <label for="allowCancel">Allow Non-Refundable bookings</label>
                </div>
            @else
                <div class="checkbox checkbox-inline">
                    <input id="allowCancel" type="checkbox" name="rights[]" value="allow_non_refundable_bookings" autocomplete="none">
                    <label for="allowCancel">Allow Non-Refundable bookings</label>
                </div>
            @endif
        @else
            <div class="checkbox checkbox-inline">
                <input id="allowCancel" type="checkbox" name="rights[]" value="allow_non_refundable_bookings" autocomplete="none">
                <label for="allowCancel">Allow Non-Refundable bookings</label>
            </div>
        @endif
    </div>
</div>
    
  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
     <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-group">
    <div class="addStaff">
        @if(!empty($agentRightsData[3]->right_acess))
            @if($agentRightsData[3]->right_acess == 'yes')
                <div class="checkbox checkbox-inline">
                    <input id="allowCancel" type="checkbox" name="rights[]" value="allow_voucher_bookings" autocomplete="none" checked>
                    <label for="allowCancel">Allow Voucher bookings</label>
                </div>
            @else
                <div class="checkbox checkbox-inline">
                    <input id="allowCancel" type="checkbox" name="rights[]" value="allow_voucher_bookings" autocomplete="none">
                    <label for="allowCancel">Allow Voucher bookings</label>
                </div>
            @endif
        @else
            <div class="checkbox checkbox-inline">
                <input id="allowCancel" type="checkbox" name="rights[]" value="allow_voucher_bookings" autocomplete="none">
                <label for="allowCancel">Allow Voucher bookings</label>
            </div>
        @endif
    </div>
</div>
   
  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-group">
    <div class="addStaff">
        @if(!empty($agentRightsData[4]->right_acess))
            @if($agentRightsData[4]->right_acess == 'yes')
                <div class="checkbox checkbox-inline">
                    <input id="allowCancel" type="checkbox" name="rights[]" value="make_bookings" autocomplete="none" checked>
                    <label for="allowCancel">Make Bookings</label>
                </div>
            @else
                <div class="checkbox checkbox-inline">
                    <input id="allowCancel" type="checkbox" name="rights[]" value="make_bookings" autocomplete="none">
                    <label for="allowCancel">Make Bookings</label>
                </div>
            @endif
        @else
            <div class="checkbox checkbox-inline">
                <input id="allowCancel" type="checkbox" name="rights[]" value="make_bookings" autocomplete="none">
                <label for="allowCancel">Make Bookings</label>
            </div>
        @endif
    </div>
</div>
   
    
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-group">
    <div class="addStaff">
        @if(!empty($agentRightsData[5]->right_acess))
            @if($agentRightsData[5]->right_acess == 'yes')
                <div class="checkbox checkbox-inline">
                    <input id="allowCancel" type="checkbox" name="rights[]" value="debug_mode" autocomplete="none" checked>
                    <label for="allowCancel">Debug Mode</label>
                </div>
            @else
                <div class="checkbox checkbox-inline">
                    <input id="allowCancel" type="checkbox" name="rights[]" value="debug_mode" autocomplete="none">
                    <label for="allowCancel">Debug Mode</label>
                </div>
            @endif
        @else
            <div class="checkbox checkbox-inline">
                <input id="allowCancel" type="checkbox" name="rights[]" value="debug_mode" autocomplete="none">
                <label for="allowCancel">Debug Mode</label>
            </div>
        @endif
    </div>
</div>
  <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-group">
    <div class="addStaff">
        @if(!empty($agentRightsData[6]->right_acess))
            @if($agentRightsData[6]->right_acess == 'yes')
                <div class="checkbox checkbox-inline">
                    <input id="allowCancel" type="checkbox" name="rights[]" value="flights_allow_ticketing" autocomplete="none" checked>
                    <label for="allowCancel">Flights Allow Ticketing</label>
                </div>
            @else
                <div class="checkbox checkbox-inline">
                    <input id="allowCancel" type="checkbox" name="rights[]" value="flights_allow_ticketing" autocomplete="none">
                    <label for="allowCancel">Flights Allow Ticketing</label>
                </div>
            @endif
        @else
            <div class="checkbox checkbox-inline">
                <input id="allowCancel" type="checkbox" name="rights[]" value="flights_allow_ticketing" autocomplete="none">
                <label for="allowCancel">Flights Allow Ticketing</label>
            </div>
        @endif
    </div>
</div>
    <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-group">
    <div class="addStaff">
        @if(!empty($agentRightsData[7]->right_acess))
            @if($agentRightsData[7]->right_acess == 'yes')
                <div class="checkbox checkbox-inline">
                    <input id="allowCancel" type="checkbox" name="rights[]" value="web_services" autocomplete="none" checked>
                    <label for="allowCancel">Web Services</label>
                </div>
            @else
                <div class="checkbox checkbox-inline">
                    <input id="allowCancel" type="checkbox" name="rights[]" value="web_services" autocomplete="none">
                    <label for="allowCancel">Web Services</label>
                </div>
            @endif
        @else
            <div class="checkbox checkbox-inline">
                <input id="allowCancel" type="checkbox" name="rights[]" value="web_services" autocomplete="none">
                <label for="allowCancel">Web Services</label>
            </div>
        @endif
    </div>
</div>
		</div>
							
							<div class="editProfileForm">
							    <div class="col-md-12 col-md-12 col-sm-12 col-xs-12">
									<div class="editProfileSubmitBtn">
									<!--	<a class="submitebtn" href=""><i class="fa fa-floppy-o" aria-hidden="true"> </i>  Save </a>-->
										<button type="submit" class="btn btn-success btn-block enter-btn" style="float:right;">Submit</button> 
									</div>
								</div>
							</div>
						</div>
						</form>
                                            
					</div>
				</div>
				  
			</div>
	
@endsection
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  function togglePasswordVisibility(inputId, iconId) {
    var passwordInput = document.getElementById(inputId);
    var showPassIcon = document.getElementById(iconId);

    if (passwordInput.type === "password") {
      passwordInput.type = "text";
      showPassIcon.innerHTML = '<i class="fa fa-eye"></i>';
    } else {
      passwordInput.type = "password";
      showPassIcon.innerHTML = '<i class="fa fa-eye-slash"></i>';
    }
  }

  function validatePasswords() {
  var password = document.getElementById("txt_password_text").value;
  var confirmPassword = document.getElementById("txt_current_password_text").value;
  var passwordMatchMessage = document.getElementById("password_match_message");

  if (password === confirmPassword) {
    passwordMatchMessage.textContent = ""; // Clear the validation message if passwords match
  } else {
    passwordMatchMessage.textContent = "The passwords do not match";
  }
}
   function validateEmail(inputId, errorId) {
  var emailInput = document.getElementById(inputId);
  var email = emailInput.value;
  var errorSpan = document.getElementById(errorId);

  var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

  if (!emailPattern.test(email)) {
    emailInput.classList.add("invalid-email");
    errorSpan.textContent = "Invalid email format";
  } else {
    emailInput.classList.remove("invalid-email");
    errorSpan.textContent = "";
  }
}
</script>
<script>
 
   $(document).ready(function () {
    // Handle form submission
    $('#formup').submit(function (event) {
        event.preventDefault(); // Prevent the form from submitting normally

        var form = $(this);
     
	
	  var agencyName = $("#txt_agency_name").val();
  var firstName = $("#txt_agency_fname").val();
  var middleName = $("#txt_agency_mname").val();
  var address = $("#txt_address").val();
  var country = $("#sel_country").val();
  var city = $("#sel_city").val();
  var branch = $("input[name='txt_branch']").val().trim();
var consultant = $("input[name='txt_consultant']").val().trim();
var phoneNumber = $("input[name='txt_phone']").val().trim();
var mobileNumber = $("input[name='txt_mobile']").val().trim();
  var currency = $("#sel_currency").val();
  var timeZone = $("#sel_timezone").val();
   var category = $("#sel_category").val();
var selectedOption = $("input[name='rad_iata_status']:checked").val();
    
    // Perform validation checks
    
	  if (firstName.trim() === '') {
      Swal.fire('Validation Error', 'Please enter Firstname', 'error');
      return;
    }
	  if (agencyName.trim() === "") {
   Swal.fire('Validation Error', 'Please enter Agencyname', 'error');
    return ;
  }

  
  if (middleName.trim() === "") {
	    Swal.fire('Validation Error', 'Please enter the Middle Name', 'error');
    
    return ;
  }

  if (address.trim() === "") {
      Swal.fire('Validation Error', 'Please enter the Address', 'error');
    return ;
  }

  if (country === "0") {
     Swal.fire('Validation Error', 'Please select a country', 'error');
    return ;
  }

  if (city.trim() === "") {
    Swal.fire('Validation Error', 'Please select a city', 'error');
    return ;
  }

  if (phoneNumber === "") {
   Swal.fire('Validation Error', 'Please Enter a phone number', 'error');
    return ;
  }

  if (mobileNumber === "") {
       Swal.fire('Validation Error', 'Please Enter a Mobile number', 'error');
    return ;
  }

  if (currency === "0") {
       Swal.fire('Validation Error', 'Please select a Currency', 'error');
    return ;
  }

  if (timeZone === "0") {
        Swal.fire('Validation Error', 'Please select a Time Zone', 'error');
    return ;
  }

  if (branch === "") {
  Swal.fire('Validation Error', 'Please enter a branch name', 'error');
  return;
}

if (consultant === "") {
  Swal.fire('Validation Error', 'Please enter a consultant', 'error');
  return;
}
if (category === "") {
  Swal.fire('Validation Error', 'Please Select Business Volume', 'error');
  return;
}
if (!selectedOption) {
  Swal.fire('Validation Error', 'Please select IATA STATUS', 'error');
  return;
}


        var formData = new FormData(form[0]); // Create a FormData object

        // Send an AJAX POST request
        $.ajax({
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ route('admin.agentUpdate') }}",
            data: formData,
            dataType: 'json',
            processData: false, // Prevent jQuery from automatically processing the data
            contentType: false, // Prevent jQuery from automatically setting the content type
          success: function (response) {
    Swal.fire('Success', response.success, 'success').then(function () {
        window.location.reload();
    });
},
error: function (xhr, status, error) {
    // Handle the error response
    if (xhr.responseJSON && xhr.responseJSON.error) {
        Swal.fire('Error', xhr.responseJSON.error, 'error');
    } else {
        Swal.fire('Error', 'An error occurred while saving the agent', 'error');
    }
}
        });
    });
});
</script>
<script>
    $(document).ready(function() {
        $('#txt_username').on('blur', function() {
            var username = $(this).val();

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ route('admin.checkUsername') }}",
                type: 'POST',
                data: {
                    username: username,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.message === 'Username already exists.') {
                        $('#username-error').text(response.message).addClass('text-danger');
                        $('#txt_username').val("");
                    } else {
                        $('#username-error').text(response.message).removeClass('text-danger').addClass('text-success');
                    }
                },
                error: function(xhr, status, error) {
                    var errorMessage = JSON.parse(xhr.responseText);
                    $('#username-error').text(errorMessage.message).addClass('text-danger');
                }
            });
        });
    });
</script>
</script>