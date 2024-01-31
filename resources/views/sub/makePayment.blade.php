@if (Auth::check())
    @if (Auth::user()->role === 1)
        @php
            $type = 'layouts.admin.default';
            $paymentSave = route('admin.paymentSave');
        @endphp
       
   @else
        @php
            $type = 'layouts.agent.default';
            $paymentSave = route('agent.paymentSave');
        @endphp
       
    @endif
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
                                    <h4 class="mb-sm-0">ADD NEW PAYMENT</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

			<div class="row">
				<div class="col-md-12 p-0">
					<form id="formup"   enctype="multipart/form-data">
			@csrf
					<div class="edit_profileSec">
						<div class="editProfileForm">
							<div class="clearDiv row">
							 @if (Auth::user()->role === 1)
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
									<label>Supplier<span class="mandatory">*</span></label>
									<div class="input_icon">
										<select class="form-control" name="supplier" id="supplier">
											<option value="0">Select</option>
											<option value="txpoAdmin">Travelexpo</option>
										
										</select>
										<span class="editProfileSelect"><i class="fa fa-caret-down" aria-hidden="true"></i></span>
									</div>
								</div>
								 @else
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
									<label>Supplier<span class="mandatory">*</span></label>
									<div class="input_icon">
										<select class="form-control" name="supplier" id="supplier">
											<option value="0">Select</option>
											<option value="txpo">Txpo</option>
											<option value="main">Agent</option>
											
										</select>
										<span class="editProfileSelect"><i class="fa fa-caret-down" aria-hidden="true"></i></span>
									</div>
								</div> 
								 @endif
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
								    <label>Date Of Payment<span class="mandatory">*</span></label>
									<div id="datepicker2">
										<input type="text" name="datepayment" id="datepayment" class="form-control w-100" placeholder="dd M, yyyy" data-date-format="dd M, yyyy" data-date-container="#datepicker2" data-provide="datepicker" style="background:#fff;">
									</div>
								</div>
							</div>
							<div class="clearDiv row">
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
									<label>Particulars</label>
									<textarea rows="5" class="form-control w-100" name="particulars"id="particulars"></textarea>
								</div>
							
									 @if (Auth::user()->role === 1)
									<input type="hidden" class="form-control" name="status" id="status" value="1"  placeholder=""autocomplete="none">
								 @else
									 <input type="hidden" class="form-control" name="status" id="status" value="0"  placeholder=""autocomplete="none">
									  @endif
								<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9 form-group">
								    <div class="clearDiv row">
										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
											<label>Payment Due Till Date</label>
											<input type="text" class="form-control" name="paymentdue" id="paymentdue" value="{{$bal}}" title="First Name" class="INR-1600" placeholder=""autocomplete="none">
										</div>
										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
											<label>Payment Amount In (INR) currency<span class="mandatory">*</span></label>
											<input type="text" name="amount" id="amount" value="" title="Last Name" placeholder="" class="form-control" autocomplete="none">
										</div>
										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
											<label>Rate Of Exchange</label>
											<input type="text" name="exchange" id="exchange" value="" title="Last Name" placeholder="" class="form-control" autocomplete="none">
										</div>
									
										<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
											<label>Mode Payment<span class="mandatory">*</span></label>
											<input type="text" name="mode" id="mode" value="" title="Last Name" placeholder="" class="form-control" autocomplete="none">
										</div>
									</div>
								</div>
							</div>
							<h5>Bank Details</h5>
							<div class="clearDiv row">
								
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
									<label>Bank Name<span class="mandatory">*</span></label>
									<input type="text" name="bankname" id="bankname" value="" title="Last Name" placeholder="" class="form-control" autocomplete="none">
								</div>
								<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
									<label>Reference No<span class="mandatory">*</span></label>
									<input type="text" name="refno" id="refno" value="" title="Last Name" placeholder="" class="form-control" autocomplete="none">
								</div>
								<div class="col-md-12 col-md-12 col-sm-12 col-xs-12">
									<div class="editProfileSubmitBtn">
										<button type="submit" class="btn btn-success btn-block enter-btn" style="float:right;">Submit</button> 
									</div>
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
 
   $(document).ready(function () {
    // Handle form submission
    $('#formup').submit(function (event) {
        event.preventDefault(); // Prevent the form from submitting normally

        var form = $(this);
        // Validation code
		var supplier = $('#supplier').val();
        var datepayment = $('#datepayment').val();
        var amount = $('#amount').val();
	    var mode = $("#mode").val();
        var bankname = $("#bankname").val();
        var refno = $("#refno").val();
 

    // Perform validation checks
    if (supplier === "0") {
      Swal.fire('Validation Error', 'Please enter your Supplier', 'error');
      return;
    }
    
    if (datepayment.trim() === '') {
      Swal.fire('Validation Error', 'Please enter Date Of Payment', 'error');
      return;
    }
    
   
	  if (amount.trim() === '') {
      Swal.fire('Validation Error', 'Please enter Amount', 'error');
      return;
    }
	  if (mode.trim() === "") {
   Swal.fire('Validation Error', 'Please enter Mode Of Payment', 'error');
    return ;
  }

  
  if (bankname.trim() === "") {
	    Swal.fire('Validation Error', 'Please enter Bank Name', 'error');
    
    return ;
  }

  if (refno.trim() === "") {
      Swal.fire('Validation Error', 'Please enter the Reference No', 'error');
    return ;
  }

  
        var formData = new FormData(form[0]); // Create a FormData object

        // Send an AJAX POST request
        $.ajax({
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ $paymentSave }}",
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