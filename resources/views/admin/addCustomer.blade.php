
@if(Auth::User()->agent_type== "txpo")
 @php 
   $type = "layouts.admin.default";
@endphp
@elseif(Auth::User()->agent_type== "main")
@php 
   $type = "layouts.agent.default";
@endphp
@endif


@extends($type)

@section('content')
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">
                        
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0">CREATE A NEW CUSTOMER</h4>
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
							<h5 class="">Customer Information</h5>
								<div class="clearDiv row">
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
										<label>Customer Name /Company Name<span class="mandatory">*</span></label>
										<input type="text" name="txt_agency_name" id="txt_agency_name" value="" title="Company Name" placeholder="Company Name" class="form-control" autocomplete="none">
									</div>
									
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
  <label>Email</label>
  <input type="email" name="txt_agency_email" id="txt_agency_email" value=""  class="form-control txt_iata_number itaDisabled" title="IATA number" placeholder="Company Email" autocomplete="none" oninput="validateEmail('txt_agency_email', 'agency-email-error')">
  <span id="agency-email-error" class="error-message"></span>
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
	                                       <div class="contactDlts row">
	                                       
	                                         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                                             <label>Mobile Number<span class="mandatory">*</span></label>
	                                             	<input type="text" name="txt_mobile" value=""  title="Mobile Number" placeholder="Mobile Number with code" class="form-control edit_phoneNumber phNumber"  autocomplete="none">
	                                         </div>
	                                    </div>
									</div><div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
	                                       <div class="contactDlts row">
	                                       
	                                         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                                             <label>Opening Balace Credit<span class="mandatory">*</span></label>
	               <input type="text" name="opening_balace_credit" value="0.0"  placeholder="Opening Balace Credit" class="form-control edit_phoneNumber phNumber"  autocomplete="none">
	                                         </div>
	                                    </div>
									</div>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
	                                      <div class="contactDlts row">
	                                         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                                                <label>Opening Balace Debit<span class="mandatory">*</span></label>
	               					<input type="text" name="opening_balance_debit" value="0.0"  placeholder="Opening Balace Debit" class="form-control edit_phoneNumber phNumber"  autocomplete="none">
	                                         </div>
	                                    </div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
	                                    <div class="contactDlts row">
	                                         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                                                <label>Markup Percent<span class="mandatory">*</span></label>
	               					<input type="text" name="markup_percent" value="0.0"  placeholder="Markup Percent" class="form-control edit_phoneNumber phNumber"  autocomplete="none">
	                                         </div>
	                                    </div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
	                                     <div class="contactDlts row">
	                                         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                                             <label>Expiry Date<span class="mandatory">*</span></label>
	               					<input type="date" name="expiry_date" value=""  placeholder="Expiry Date" class="form-control edit_phoneNumber phNumber"  autocomplete="none">
	                                         </div>
	                                    </div>
					</div>


					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
	                                     <div class="contactDlts row">
	                                         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                                             <label>Contact Person<span class="mandatory">*</span></label>
	               					<input type="text" name="contact_person" id="contact_person" value=""  placeholder="Contact Person" class="form-control edit_phoneNumber phNumber"  autocomplete="none">
	                                         </div>
	                                    </div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
	                                     <div class="contactDlts row">
	                                         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                                             <label>Contact Designation<span class="mandatory">*</span></label>
	               					<input type="text" name="contact_desig" id="contact_desig" value=""  placeholder="Contact Designation" class="form-control edit_phoneNumber phNumber"  autocomplete="none">
	                                         </div>
	                                    </div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
	                                     <div class="contactDlts row">
	                                         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                                             <label>Contact Telphone<span class="mandatory">*</span></label>
	               					<input type="text" name="contact_tel" id="contact_tel" value=""  placeholder="Contact Telphone" class="form-control edit_phoneNumber phNumber"  autocomplete="none">
	                                         </div>
	                                    </div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
	                                     <div class="contactDlts row">
	                                         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                                             <label>Contact E-mail<span class="mandatory">*</span></label>
	               					<input type="text" name="contact_mail" id="contact_mail" value=""  placeholder="Contact E-mail" class="form-control edit_phoneNumber phNumber"  autocomplete="none">
	                                         </div>
	                                    </div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
	                                     <div class="contactDlts row">
	                                         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                                             <label>GST No</label>
	               					<input type="text" name="gst_no" value=""  placeholder="GST No" class="form-control edit_phoneNumber phNumber"  autocomplete="none">
	                                         </div>
	                                    </div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
	                                     <div class="contactDlts row">
	                                         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                                             <label>Credit Limit<span class="mandatory">*</span></label>
	               					<input type="text" name="credit_limit" id="credit_limit" value=""  placeholder="Credit Limit" class="form-control edit_phoneNumber phNumber"  autocomplete="none">
	                                         </div>
	                                    </div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
	                                     <div class="contactDlts row">
	                                         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                                             <label>Pay Terms<span class="mandatory">*</span></label>
							<div class="input_icon">
	               					<select class="form-control" name="pay_terms" id="pay_terms"  title="India">
								<option value="0">Select</option>
    								@foreach ($pay_terms as $pay_term)	 
        							<option value="{{ $pay_term->terms_id}}">{{ $pay_term->terms_name }}</option>
    								@endforeach
							</select>
							<span class="editProfileSelect"><i class="fa fa-caret-down" aria-hidden="true"></i></span>
							</div>
	                                         </div>
	                                    </div>
					</div>
					<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
	                                     <div class="contactDlts row">
	                                         <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
	                                             <label>Customer Group<span class="mandatory">*</span></label>
						<div class="input_icon">
	               					<select class="form-control" name="customer_group" id="customer_group"  title="India">
								<option value="0">Select</option>
    								@foreach ($cust_groups as $group)	 
        							<option value="{{ $group->cust_grp_id}}">{{ $group->cust_group_name }}</option>
    								@endforeach
							</select>
							<span class="editProfileSelect"><i class="fa fa-caret-down" aria-hidden="true"></i></span>
						</div>
	                                         </div>
	                                    </div>
					</div>






















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
	// Validation code
	var username = $('#txt_username').val();
	var password = $('#txt_password_text').val();
	var confirmPassword = $('#txt_current_password_text').val();
	var agencyEmail = $("#txt_agency_email").val();
	var agencyName = $("#txt_agency_name").val();
	var address = $("#txt_address").val();
	var country = $("#sel_country").val();
	var city = $("#sel_city").val();
	var mobileNumber = $("input[name='txt_mobile']").val().trim();
	
	var contact_person= $('#contact_person').val();
	var contact_desig= $('#contact_desig').val();
	var contact_tel= $("#contact_tel").val();
	var contact_mail= $("#contact_mail").val();
	var credit_limit= $("#credit_limit").val();
	var pay_terms= $("#pay_terms").val();
	var customer_group= $("#customer_group").val();

    
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

	  if (agencyName.trim() === "") {
   Swal.fire('Validation Error', 'Please enter Agencyname', 'error');
    return ;
  }

  if (agencyEmail === "") {
   Swal.fire('Validation Error', 'Please enter AgencyEmail', 'error');
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


  if (mobileNumber === "") {
       Swal.fire('Validation Error', 'Please Enter a Mobile number', 'error');
    return ;
  }

 if (contact_person.trim() === '') {
      Swal.fire('Validation Error', 'Please enter a contact person', 'error');
      return;
    }
if (contact_desig.trim() === '') {
      Swal.fire('Validation Error', 'Please enter a contact  designation', 'error');
      return;
    }
if (contact_tel.trim() === '') {
      Swal.fire('Validation Error', 'Please enter a contact telphone', 'error');
      return;
    }
if (contact_mail.trim() === '') {
      Swal.fire('Validation Error', 'Please enter a contact E-mail', 'error');
      return;
    }
if (credit_limit.trim() === '') {
      Swal.fire('Validation Error', 'Please enter credit limit', 'error');
      return;
    }
if (pay_terms.trim() === "0") {
      Swal.fire('Validation Error', 'Please select a pay terms', 'error');
      return;
    }
if (customer_group.trim() === "0") {
      Swal.fire('Validation Error', 'Please select a customer group', 'error');
      return;
    }

 

        var formData = new FormData(form[0]); // Create a FormData object

        // Send an AJAX POST request
        $.ajax({
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ route('customer.customerSave') }}",
            data: formData,
            dataType: 'json',
            processData: false, // Prevent jQuery from automatically processing the data
            contentType: false, // Prevent jQuery from automatically setting the content type
          success: function (response) {
    Swal.fire('Success', response.success, 'success').then(function () {
        window.location.href="{{ route('customer.customersList') }}"
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
                url: "{{ route('admin.checkCustomername') }}",
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
</script>