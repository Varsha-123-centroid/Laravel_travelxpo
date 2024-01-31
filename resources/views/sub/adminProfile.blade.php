@extends('layouts.admin.default')

@section('content')
<div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">
                        
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0">Edit Profile</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

			<div class="row">
				<div class="col-md-12 p-0">
					<form action="" id="formup" method="post" enctype="multipart/form-data">
						<input type="hidden" name="action_name" value="edit_profile" autocomplete="none">
						<input type="hidden" name="encrypt_agent" value="1ff1de774005f8da13f42943881c655f" autocomplete="none">
						<div class="edit_profileSec">
          	<div class="editProfileForm">
							<h5 class=""> Admin Profile Details</h5>
								<div class="clearDiv row">
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
										<label>Full Name Name<span class="mandatory">*</span></label>
										<input type="text" name="name" id="name" value="{{$name}}" title="Full Name" placeholder="Full Name" class="form-control" autocomplete="none">
									</div>
									
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
										<label>Phone Number</label>
										<input type="text" name="phone" id="phone" class="form-control" value="{{$mobile}}" title="phone" placeholder="Phone Number" autocomplete="none">
									</div>
									<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group">
										<label>Email</label>
										<input type="email" name="email" id="email" class="form-control" value="{{$email}}" title="email" placeholder="Email" autocomplete="none">
									</div>
									    
									
	<div class="editProfileForm">
							    <div class="col-md-12 col-md-12 col-sm-12 col-xs-12">
									<div class="editProfileSubmitBtn">
									<button id="submit-button" type="submit" class="submitebtn" >
									Submit    </button>
										<!-- <a href="javascript:void(0)">Reset </a> -->
										
									</div>
								</div>
							</div>
							</form>
								</div>
							</div>
						
							</div>

								</div>
							</div>
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
		
        var formData = new FormData(form[0]); // Create a FormData object

        // Send an AJAX POST request
        $.ajax({
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ route('admin.updateAdminProfile') }}",
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
        Swal.fire('Error', 'An error occurred while saving the Admin Data', 'error');
    }
}
        });
    });
});
</script>