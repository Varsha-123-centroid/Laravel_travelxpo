@extends('layouts.admin.default')

@section('content')
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">
                        
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0">CREATE A AIRLINE</h4>
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
						
								<div class="clearDiv row">
								
									

									
								
								
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
										<label>Airline Name</label>
										<input type="text" name="airlinename" id="airlinename" value=""  class="form-control " title="Airline Name" placeholder="Airline Name" autocomplete="none">
									</div>
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
										<label>Airline Code</label>
										<input type="text" name="airline_code" id="airline_code" class="form-control" value="" title="Airline Code" placeholder="Airline Code" autocomplete="none">
										 <span id="airlinecode-error" class="text-danger"></span>
									</div>
								
								   <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 form-group input_dv">
										<label>Airline Logo</label>
										<div class="">
											<input type="file" class="form-control" id="airline_logo" name="airline_logo" />
										</div>
									</div>
									
									<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2 form-group input_dv">
										<div class="editProfileSubmitBtn pt-4">
										<!--	<a class="submitebtn" href=""><i class="fa fa-floppy-o" aria-hidden="true"> </i>  Save </a>-->
											<button type="submit" id="submitBtn" class="btn w-100 btn-success btn-block enter-btn" style="float:right;">Submit</button> 
										</div>
									</div>
		
								</div>
							</div>
							
						
						
							</div>
							
						</div>
						</form>
                                            
					</div>
					<div class="row">
				<div class="col-md-12 p-0">
					
                         
						<div class="edit_profileSec">
							<div class="editProfileForm">
							   <h5 class="">Airlines List</h5>	<br>
							    @if(!empty($airlinesList))
								<table id="datatable" class="table table-bordered  nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
									<thead>
									<tr>
										<th>Airlines Name</th>
										<th>Airlines Code</th>
										<th>Logo</th>
									
										<th>Actions</th>
									</tr>
									</thead>


									<tbody>
									<?php $i=1; ?>
                                    @foreach($airlinesList as $val)
									<tr>
										<td>{{$val->airline_name}}</td>
										<td>{{$val->airline_code}}</td>
										 @if(!empty($val->airline_logo))
										<td><img src="{{asset('public/uploads/AirlineLogo/'.$val->airline_logo) }}" download alt="Airline Logo" style="width: 50px; height: 50px;"/></td>@else <td></td> @endif
										
										<td id="tooltip-container1">
										
											<a href="" class="text-danger delete-record"  data-record-id="{{ $val->id }}" data-bs-container="#tooltip-container1" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><i class="mdi mdi-trash-can font-size-18"></i></a>  &nbsp;&nbsp;&nbsp;
										
    <a href="#" class="text-danger edit-record" data-record-id="{{ $val->id }}">

        <i class="mdi mdi-pencil-outline font-size-18"></i>
    </a>
</td>

									</tr>
									   <?php $i++; ?>
                                    @endforeach
                                       
						
									</tbody>
									@endif
								</table>
							</div>
						</div>
						 
					</div>
				</div>
				<!-- Edit Modal -->
<div class="modal" id="editModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Record</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
			<form id="editForm" action="{{ route('admin.updateAirline') }}" method="POST" enctype="multipart/form-data">

           @csrf
               
                <div class="modal-body">
				 <input type="hidden" name="record_id" id="record_id" >
                   <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
										<label>Airline Name</label>
										<input type="text" name="airlinenameedit" id="airlinenameedit" value=""  class="form-control " title="Airline Name" placeholder="Airline Name" autocomplete="none">
									</div>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
										<label>Airline Code</label>
										<input type="text" name="airline_codeedit" id="airline_codeedit" class="form-control" value="" title="Airline Code" placeholder="Airline Code" autocomplete="none">
										 <span id="airlinecode-error" class="text-danger"></span>
									</div>
									<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 form-group">
    <label>Airline Logo</label>
    <div class="">
      <input type="file" class="form-control" id="airline_logoedit" name="airline_logoedit" />

		<div id="image-name-display"></div>
    </div>
</div>
		 					
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" id="submitBtnupdate" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
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
		var airlinename = $('#airlinename').val();
        var airline_code = $('#airline_code').val();
  
    
    // Perform validation checks
    if (airlinename.trim() === '') {
      Swal.fire('Validation Error', 'Please enter a Airline Name', 'error');
      return;
    }
    
    if (airline_code.trim() === '') {
      Swal.fire('Validation Error', 'Please enter a Airline Code', 'error');
      return;
    }
  

        var formData = new FormData(form[0]); // Create a FormData object

        // Send an AJAX POST request
        $.ajax({
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ route('admin.airlineSave') }}",
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
        Swal.fire('Error', 'An error occurred while saving the Airline', 'error');
    }
}
        });
    });
});
</script>
<script>
    $(document).ready(function() {
        $('#airline_code').on('blur', function() {
            var airlinecode = $(this).val();

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ route('admin.checkAirlineCode') }}",
                type: 'POST',
                data: {
                    airlinecode: airlinecode,
                    _token: '{{ csrf_token() }}'
                },
               success: function(response) {
    if (response.status === 'exists') {
        $('#airlinecode-error').text(response.message).addClass('text-danger');
        $('#airline_code').val("");
    } else {
        $('#airlinecode-error').text(response.message).removeClass('text-danger').addClass('text-success');
    }
},
                error: function(xhr, status, error) {
                    var errorMessage = JSON.parse(xhr.responseText);
                    $('#airlinecode-error').text(errorMessage.message).addClass('text-danger');
                }
            });
        });
		
		
		 $('.delete-record').on('click', function(e) {
        e.preventDefault();

        var recordId = $(this).data('record-id');

        // Confirm deletion
        if (confirm('Are you sure you want to delete this record?')) {
            // Send an Ajax request to delete the record
       $.ajax({
    type: 'Post',
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
     url: "{{ route('admin.deleteAirlines')}}",
    data: {
      recordId:recordId
    },
    success: function(response) {
      Swal.fire('Success', response.success, 'success').then(function () {
        window.location.reload();
    });
    },
    error: function(xhr) {
        // Handle error, e.g., display an error message
        console.error('Error deleting record:', xhr.responseText);
    }
});

        }
    });
    });
	

</script>
<script>
    $(document).ready(function () {
        $('.edit-record').on('click', function (e) {
            e.preventDefault();
			var recordId = $(this).data('record-id');
			$('#record_id').val(recordId);
			 fetchDataAndPopulateModal(recordId);
            $('#editModal').modal('show');
        });
		 function fetchDataAndPopulateModal(recordId) {
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
     url: "{{ route('admin.fetchAirlineData')}}",
    data: {
      recordId:recordId
    },
                method: 'GET',
                success: function (data) {
                    populateModal(data);
                },
                error: function (error) {
                    console.error(error);
                }
            });
        }
		 // Function to populate the modal with data
        function populateModal(data) {
			//console.log(data.airline_name);
            $('#airlinenameedit').val(data.airline_name);
            $('#airline_codeedit').val(data.airline_code);
            $('#image-name-display').text(data.airline_logo);

    // To display the image name, you can also update a separate element on your modal.
   
			 
        }
		 // Manually close the modal when either the "x" button or the "Close" button is clicked
        $('#editModal .close, #editModal button[data-dismiss="modal"]').on('click', function () {
            $('#editModal').modal('hide');
        });
    });
	

</script>
<script>
    $(document).ready(function() {
        $('#submitBtnupdate').on('click', function(e) {
            // No need to prevent default behavior for standard form submission
            // Manually submit the form
            $('#editForm').submit();
        });
    });
</script>