@extends('layouts.admin.default')

@section('content')
<div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">
                        
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0">AGENT LIST</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

			<div class="row">
				<div class="col-md-12 p-0">
					
                         
						<div class="edit_profileSec">
							<div class="editProfileForm">
							   <h5 class="">AGENT LIST</h5>	
							    @if(!empty($agents))
								<table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
									<thead style="background: #453185f7;color:#fff;">
									<tr>
										<th> Agent Name</th>
										<th>Mobile Number</th>
										<th>Business <br>Volume <br>Category</th>
									
										<th>Actions</th>
									</tr>
									</thead>


									<tbody>
									<?php $i=1; ?>
                                    @foreach($agents as $val)
									<tr>
										<td>{{$val->company_name}}</td>
										<td>{{$val->mobile_number}}</td>
										<td>{{$val->business_vol_category}}</td>
										
										<td id="tooltip-container1">
											<a href="{{ route('admin.agentEdit', ['id' => $val->id]) }}" class="me-3 text-primary" data-bs-container="#tooltip-container1" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i class="mdi mdi-pencil font-size-18"></i></a>
											<a href="" class="text-danger" data-bs-container="#tooltip-container1" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><i class="mdi mdi-trash-can font-size-18"></i></a>
											  @if($val->status== 0)
											<button type="button" class="btn btn-link status-btn" data-id="{{ $val->id }}">
    <a href="#" class="text-success" data-bs-container="#tooltip-container1" data-bs-toggle="tooltip" data-bs-placement="top" title="Status Change">
        <i class="mdi mdi-swap-horizontal font-size-18"></i>
    </a>
</button>
										@endif
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
<script>
$(document).ready(function() {
    // Handle button click event
    $('.status-btn').click(function() {
        var btn = $(this);
        var id = btn.data('id');
alert(id);
        // Send AJAX request
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('admin.outsideRegStatus') }}",
            type: 'POST',
            data: {
                id: id,
            },
            success: function(response) {
                if (response.status === 'success') {
                    // Update button and status
                    window.location.reload();
                } else {
                    alert('Failed to update status. Please try again.');
                }
            },
            error: function(xhr, status, error) {
                alert('An error occurred. Please try again.');
            }
        });
    });
});
</script>