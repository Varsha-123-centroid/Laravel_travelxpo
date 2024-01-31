@extends('layouts.admin.default')

@section('content')

            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">
                        
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0">CREATE AIRPORT</h4>
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
										<label>Location</label>
										<input type="text" name="location" id="location" value=""  class="form-control " title="Country Name" placeholder="Airport Location" autocomplete="none">
									</div>
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
										<label>AirportCode</label>
										<input type="text" name="airportcode" id="airportcode" class="form-control" value="" title="Country Code" placeholder="Airport Code" autocomplete="none">
										 <span id="airportcode-error" class="text-danger"></span>
									</div>
									<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
										<label>Country Code</label>
										<input type="text" name="countrycode" id="countrycode" class="form-control skillitems" value="" title="Country Code " placeholder="Country Code" autocomplete="none">
										  <input type="hidden" id="cntryid" name="cntryid" value="0"/>
										 
                                    <div id="cntry_pos"></div> 
								
									</div>
							        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
										<label>City Code</label>
										<input type="text" name="citycode" id="citycode" class="form-control skillitems" value="" title="City Code " placeholder="City Code" autocomplete="none">
									   <input type="hidden" id="cityid" name="cityid" value="0"/>
									    <div id="city_pos"></div> 
									</div>
									<div class="col-md-12 col-md-12 col-sm-12 col-xs-12">
									<div class="editProfileSubmitBtn">
									<!--	<a class="submitebtn" href=""><i class="fa fa-floppy-o" aria-hidden="true"> </i>  Save </a>-->
										<button type="submit" id="submitBtn" class="btn btn-success btn-block enter-btn" style="float:right;">Submit</button> 
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
							   <h5 class="">Airport List</h5>	<br>
							    @if(!empty($list))
								<table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
									<thead>
									<tr>
										<th>Location</th>
										<th>Airport Code</th>
										<th>Country Code </th>
									    <th>City Code </th>
										<th>Actions</th>
									</tr>
									</thead>


									<tbody>
									<?php $i=1; ?>
                                    @foreach($list as $val)
									<tr>
										<td>{{$val->location}}</td>
										<td>{{$val->airport_code}}</td>
										<td>{{$val->country_code}}</td>
										<td>{{$val->city_code}}</td>
										<td id="tooltip-container1">
										
											<a href="" class="text-danger delete-record"  data-record-id="{{ $val->id }}" data-bs-container="#tooltip-container1" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><i class="mdi mdi-trash-can font-size-18"></i></a>
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
				</div>
				  
			</div>
	
@endsection
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>




<script>
 
   $(document).ready(function () {
    // Handle form submission
    $('#formup').submit(function (event) {
        event.preventDefault(); // Prevent the form from submitting normally

        var form = $(this);
        // Validation code
		var location = $('#location').val();
        var airportcode = $('#airportcode').val();
  
    
    // Perform validation checks
    if (location.trim() === '') {
      Swal.fire('Validation Error', 'Please enter Location', 'error');
      return;
    }
    
    if (airportcode.trim() === '') {
      Swal.fire('Validation Error', 'Please enter AirportCode', 'error');
      return;
    }
  

        var formData = new FormData(form[0]); // Create a FormData object

        // Send an AJAX POST request
        $.ajax({
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ route('admin.airportSave') }}",
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
        Swal.fire('Error', 'An error occurred while saving the Airport', 'error');
    }
}
        });
    });
});
</script>
<script>
    $(document).ready(function() {
        $('#airportcode').on('blur', function() {
            var airportcode = $(this).val();

            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ route('admin.checkAirportCode') }}",
                type: 'POST',
                data: {
                    airportcode: airportcode,
                    _token: '{{ csrf_token() }}'
                },
               success: function(response) {
    if (response.status === 'exists') {
        $('#airportcode-error').text(response.message).addClass('text-danger');
        $('#airportcode').val("");
    } else {
        $('#airportcode-error').text(response.message).removeClass('text-danger').addClass('text-success');
    }
},
                error: function(xhr, status, error) {
                    var errorMessage = JSON.parse(xhr.responseText);
                    $('#airportcode-error').text(errorMessage.message).addClass('text-danger');
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
     url: "{{ route('admin.deleteAirport')}}",
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
 $(function () {
    $("#countrycode").autocomplete({
        source: function (request, response) {
            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: "{{ route('admin.autocompletecountry')}}",
                dataType: 'json',
                data: {
                    query: request.term
                },
              success: function( data ) {
              response( data );
            },
                error: function (xhr, status, error) {
                    // Handle other errors if necessary
                    console.error(xhr.responseText);
                }
            });
        },
        minLength: 2,
        select: function (event, ui) {
            // Set selection
            $('#countrycode').val(ui.item.label);
            $('#cntryid').val(ui.item.value);
            return false;
        },
        appendTo: "#cntry_pos",
    });
});

</script>
<script>
 $(function () {
    $("#citycode").autocomplete({
        source: function (request, response) {
            $.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: "{{ route('admin.autocompletecity')}}",
                dataType: 'json',
                data: {
                    query: request.term
                },
              success: function( data ) {
              response( data );
            },
                error: function (xhr, status, error) {
                    // Handle other errors if necessary
                    console.error(xhr.responseText);
                }
            });
        },
        minLength: 2,
        select: function (event, ui) {
            // Set selection
            $('#citycode').val(ui.item.label);
            $('#cityid').val(ui.item.value);
            return false;
        },
        appendTo: "#city_pos",
    });
});

</script>