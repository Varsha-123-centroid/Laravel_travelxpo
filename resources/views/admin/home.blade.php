<!doctype html>
<html lang="en">
<head>
  <style>
    label.error {
        color: red;
    }

    .error {
        display: block;
        margin-top: 5px;
        font-size: 12px;
    }
</style>


        <meta charset="utf-8" />
        <title>Login || Travexpo</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Travexpo Admin" name="description" />
        <meta content="Travexpo" name="author" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('theme/admin/assets/images/favicon.png')}}">
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('theme/admin/assets/images/favicon.ico')}}">

        <!-- Bootstrap Css -->
        <link href="{{asset('theme/admin/assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
        <link href="{{asset('theme/admin/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <!-- App Css-->
        <link href="{{asset('theme/admin/assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />

    </head>

    <body class="auth-body-bg">
      	<div class="row">
			<form id="formup"  data-id="{{ $formData->id ?? '' }}" enctype="multipart/form-data">
			@csrf
				<div class="col-md-12 p-0">
					
					<div class="edit_profileSec">
						<div class="editProfileForm">
							<h5 class="">Access</h5>
							<div class="clearDiv row">
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
    <label>User Name<span class="mandatory">*</span></label>
    <div class="input_icon">
        <input type="text" class="form-control" name="txt_username" id="txt_username" autocomplete="none">
    </div>
    <span id="username-error" class="text-danger"></span>
</div>
								<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
  <label>Password<span class="mandatory">*</span></label>
  <div class="input_icon">
    <input type="password" class="form-control" name="txt_password_text" id="txt_password_text"  autocomplete="none">
    <span class="showPassBtn" onclick="togglePasswordVisibility('txt_password_text', 'showPassIcon1')"><i class="fa fa-eye-slash" id="showPassIcon1"></i></span>
  </div>
</div>

<!-- Confirm Password Field -->
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
  <label>Confirm Password<span class="mandatory">*</span></label>
  <div class="input_icon">
    <input type="password" class="form-control" name="txt_current_password_text" id="txt_current_password_text" autocomplete="none" onblur="validatePasswords()">
    <span class="showPassBtn" onclick="togglePasswordVisibility('txt_current_password_text', 'showPassIcon2')"><i class="fa fa-eye-slash" id="showPassIcon2"></i></span>
  </div><p id="password_match_message"></p>
</div>
							</div>
						</div>
					</div>
						<div class="edit_profileSec">
							<div class="editProfileForm">
							<h5 class="">Company Details</h5>
								<div class="clearDiv row">
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
										<label>Agency Name<span class="mandatory">*</span></label>
										<input type="text" name="txt_agency_name" id="txt_agency_name" value="" title="Company Name" placeholder="Company Name" class="form-control" autocomplete="none">
									</div>
									
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
  <label>Company (Agency Email)</label>
  <input type="email" name="txt_agency_email" id="txt_agency_email" value=""  class="form-control txt_iata_number itaDisabled" title="IATA number" placeholder="Company Email" autocomplete="none" oninput="validateEmail('txt_agency_email', 'agency-email-error')">
  <span id="agency-email-error" class="error-message"></span>
</div>

									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
										<label>Accounding ID</label>
										<input type="text" name="txt_account_id" id="txt_account_id" class="form-control" value="" title="Company Reg.No" placeholder="Company Reg. No" autocomplete="none">
									</div>
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
										<label>First Name<span class="mandatory">*</span></label>
										<input type="text" name="txt_agency_fname" id="txt_agency_fname" value="" title="Company Name" placeholder="First Name" class="form-control" autocomplete="none">
									</div>
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
										<label>Middle Name<span class="mandatory">*</span></label>
										<input type="text" name="txt_agency_mname" id="txt_agency_mname" value="" title="Company Name" placeholder="Middle Name" class="form-control" autocomplete="none">
									</div>
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
										<label>Last Name</label>
										<input type="text" name="txt_agency_lname" id="txt_agency_lname" value=""  class="form-control txt_iata_number itaDisabled" title="IATA number" placeholder="Last Name" autocomplete="none">
									</div>
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
										<label>Designation</label>
										<input type="text" name="txt_agency_designation" id="txt_agency_designation" class="form-control" value="" title="Company Reg.No" placeholder="Designation" autocomplete="none">
									</div>
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
										<label>Nature of Business</label>
										<div class="input_icon">
											<select class="form-control" name="sel_nature_of_business" id="sel_nature_of_business">
												<option value="0">Select</option>
												<option value="Destination Management Company">Destination Management Company</option>
												<option value="Tour Operator">Tour Operator</option>
												<option value="Travel Agent" selected="">Travel Agent</option>
												<option value="Wholesale Travel Company">Wholesale Travel Company</option>
												<option value="Corporate">Corporate</option>
											</select>
											<span class="editProfileSelect"><i class="fa fa-caret-down" aria-hidden="true"></i></span>
										</div>
									</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group input_dv">
    <label>Agent Logo</label>
    <div class="">
        <input type="file" class="form-control" id="agent_logo" name="agent_logo" />
    </div>
</div>
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group input_dv">
										<label>Profile Image
										
										</label>
																				
										<div class="">
				  	   <input type="file" class="form-control" id="agent_image" name="agent_image"  />
					 
					</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
										<label>Address<span class="mandatory">*</span></label>
										<input type="text" name="txt_address" class="form-control" value="" placeholder="Address" title="Address" id="txt_address" autocomplete="none">
									</div>
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
										<label>Country<span class="mandatory">*</span></label>
										<div class="input_icon">
												<select class="form-control" name="sel_country" id="sel_country"  title="India">   <option value="0">Select</option>
    @foreach ($countries as $country)
	
        <option value="{{ $country->id }}">{{ $country->country_name }}</option>
    @endforeach
</select>																																               
											<span class="editProfileSelect"><i class="fa fa-caret-down" aria-hidden="true"></i></span>
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
										<label>City<span class="mandatory">*</span></label>
										<div class="input_icon">
										<input type="text" name="sel_city" class="form-control" value="" placeholder="Enter City" title="City" id="sel_city">
											<span class="editProfileSelect"><i class="" aria-hidden="true"></i></span>
										</div>
									
									</div>
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
										<label>Pincode/Zipcode</label>
										<input type="text" name="txt_pincode" id="txt_pincode" class="form-control" value=""  title="Pincode/Zipcode" placeholder="Pincode/Zipcode"  autocomplete="none">
									</div>
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
										<div class="approvalRadioBtn">
											<label>IATA Registration Number</label>
											<div class="edit_Profile d-flex">
											<input type="text" name="rad_iata_status" id="rad_iata_status" class="form-control" value=""  title="IATA Registration Number" placeholder="IATA Registration Number"  autocomplete="none">
												
											</div>
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
		                                   <div class="contactDlts row">
		                                   
		                                     <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		                                         <label>Phone Number<span class="mandatory">*</span></label>
		                                         	<input type="text" name="txt_phone" value=""  title="Phone Number" placeholder="Phone Number With Code" class="form-control edit_phoneNumber phNumber" autocomplete="none">
											</div>
										</div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
	                                       <div class="contactDlts row">
	                                       
	                                         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                                             <label>Mobile Number<span class="mandatory">*</span></label>
	                                             	<input type="text" name="txt_mobile" value=""  title="Mobile Number" placeholder="Mobile Number with code" class="form-control edit_phoneNumber phNumber"  autocomplete="none">
	                                         </div>
	                                    </div>
									</div>
								
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
	                                     <div class="contactDlts row">
	                                        
	                                          <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                                             <label>Fax Number</label>
	                                             	<input type="text" name="txt_fax" value=""  title="Fax No." placeholder="Fax Number with code" class="form-control edit_phoneNumber phNumber" autocomplete="none">
	                                             </div>
	                                      </div>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
										<label>Website</label>
										<input type="text" name="txt_website" value="" title="Website" placeholder="Website" class="form-control" autocomplete="none">
									</div>
											<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
										<label>Business Volume Category<span class="mandatory">*</span></label>
										<div class="input_icon">
												<select class="form-control" name="sel_category" id="sel_category"  title="sel_category">   <option value="0">Select Category</option>
   <option value="General">General</option>
	
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
												<input type="text" name="txt_acc_name" value=""  title="Name" placeholder="Name" class="form-control" autocomplete="none">
											</div>
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
  <label>Email</label>
  <input type="email" name="txt_acc_email" id="txt_acc_email" value=""  title="Email Address" placeholder="Email Address" class="form-control" autocomplete="none" oninput="validateEmail('txt_acc_email', 'acc-email-error')">
  <span id="acc-email-error" class="error-message"></span>
</div>

											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
												<label>Phone Number</label>
												<input type="text" name="txt_acc_ph" value=""  title="Email Address" placeholder="Phone Number" class="form-control" autocomplete="none">
											</div>
										</div>
									</div>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
										<h4>Reservations</h4>
										<div class="row">
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
												<label>Name</label>
												<input type="text" name="txt_res_name" value=""  title="Name" placeholder="Name" class="form-control" autocomplete="none">
											</div>
										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
  <label>Email</label>
  <input type="email" name="txt_res_email" id="txt_res_email" value=""  title="Email Address" placeholder="Email Address" class="form-control" autocomplete="none" oninput="validateEmail('txt_res_email', 'res-email-error')">
  <span id="res-email-error" class="error-message"></span>
</div>


											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
												<label>Phone Number</label>
												<input type="text" name="txt_res_ph" value=""  title="Email Address" placeholder="Phone Number" class="form-control" autocomplete="none">
											</div>
										</div>
									</div>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
										<h4>Management </h4>
										<div class="row">
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
												<label>Name</label>
												<input type="text" name="txt_mgt_name" value=""  title="Name" placeholder="Name" class="form-control" autocomplete="none">
											</div>
										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
  <label>Email</label>
  <input type="email" name="txt_mgt_email" id="txt_mgt_email" value=""  title="Email Address" placeholder="Email Address" class="form-control" autocomplete="none" oninput="validateEmail('txt_mgt_email', 'mgt-email-error')">
  <span id="mgt-email-error" class="error-message"></span>
</div>
											<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
												<label>Phone Number</label>
												<input type="text" name="txt_mgt_ph" value=""  title="Email Address" placeholder="Phone Number" class="form-control" autocomplete="none">
											</div>
										</div>
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
	 
        <option value="{{ $currencies->id }}">{{ $currencies->currency_code }}</option>
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
	  
         <option value="{{ $timezone }}">{{ $timezone }}</option>
    @endforeach
</select>
											<span class="editProfileSelect"><i class="fa fa-caret-down" aria-hidden="true"></i></span>
										</div>
									</div>
									
								
								
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
										<label>Branch<span class="mandatory">*</span></label>
										<div class="input_icon">
											<input type="text" name="txt_branch" value=""  title="Name" placeholder="Enter Branch" class="form-control" autocomplete="none">
											<span class="editProfileSelect"><i class="" aria-hidden="true"></i></span>
										</div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
										<label>Sales Manager</label>
										<div class="input_icon">
										<input type="text" name="txt_sales_mgr" value=""  title="Name" placeholder="Enter Branch" class="form-control" autocomplete="none">
											<span class="editProfileSelect"><i class="" aria-hidden="true"></i></span>
										</div>
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
										<label>Consultant<span class="mandatory">*</span></label>
										<div class="input_icon">
										<input type="text" name="txt_consultant" value=""  title="Name" placeholder="Enter Branch" class="form-control" autocomplete="none">
											<span class="editProfileSelect"><i class="" aria-hidden="true"></i></span>
										</div>
									</div>
									
								</div>
								
								
							
								
							
							<div class="editProfileForm">
							    <div class="col-md-12 col-md-12 col-sm-12 col-xs-12">
									<div class="editProfileSubmitBtn">
									<!--	<a class="submitebtn" href=""><i class="fa fa-floppy-o" aria-hidden="true"> </i>  Save </a>-->
										<button type="submit" id="submitBtn" class="btn btn-success btn-block enter-btn" style="float:right;">Submit</button> 
									</div>
								</div>
							</div>
						</div>
						</form>
                                            
					</div>
				</div>
				  
			</div>
	

    

    <!-- JAVASCRIPT -->
    <script src="{{asset('theme/admin/assets/libs/jquery/jquery.min.js')}}"></script>
    <script src="{{asset('theme/admin/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
    <script src="{{asset('theme/admin/assets/libs/metismenu/metisMenu.min.js')}}"></script>
    <script src="{{asset('theme/admin/assets/libs/simplebar/simplebar.min.js')}}"></script>
    <script src="{{asset('theme/admin/assets/libs/node-waves/waves.min.js')}}"></script>

    <script src="{{asset('theme/admin/assets/js/app.js')}}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Include jQuery Validation library -->
<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
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
        // Validation code
		var username = $('#txt_username').val();
    var password = $('#txt_password_text').val();
    var confirmPassword = $('#txt_current_password_text').val();
	  var agencyEmail = $("#txt_agency_email").val();
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

    
    // Perform validation checks
    if (username.trim() === '') {
      Swal.fire('Validation Error', 'Please enter a username', 'error');
      return;
    }
    
    if (password.trim() === '') {
      Swal.fire('Validation Error', 'Please enter a password', 'error');
      return;
    }
    
    if (password !== confirmPassword) {
      Swal.fire('Validation Error', 'Passwords do not match', 'error');
      return;
    }
	  if (firstName.trim() === '') {
      Swal.fire('Validation Error', 'Please enter Firstname', 'error');
      return;
    }
	  if (agencyName.trim() === "") {
   Swal.fire('Validation Error', 'Please enter Agencyname', 'error');
    return ;
  }

  if (agencyEmail === "") {
   Swal.fire('Validation Error', 'Please enter AgencyEmail', 'error');
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

        var formData = new FormData(form[0]); // Create a FormData object

        // Send an AJAX POST request
        $.ajax({
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ route('agentNewRegistration') }}",
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
                url: "{{ route('checkUsername') }}",
                type: 'POST',
                data: {
                    username: username,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
    if (response.status === 'exists') {
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


</body>
</html>



