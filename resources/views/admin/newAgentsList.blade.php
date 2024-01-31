
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
                                    <h4 class="mb-sm-0"></h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

			<div class="row">
				<div class="col-md-12 p-0">
					
                         
						<div class="edit_profileSec">
							<div class="editProfileForm">
							   <h5 class="">AGENT REGISTRATION LIST FOR APPROVAL</h5>	<br>
							    @if(!empty($newAgentsList))
								<table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
									<thead>
									<tr>
										<th>Agency Name</th>
										<th>Company Agency Mail</th>
										<th>Mobile Number</th>
									
										<th>Actions</th>
									</tr>
									</thead>


									<tbody>
									<?php $i=1; ?>
                                    @foreach($newAgentsList as $val)
									<tr>
										<td>{{$val->agencyname}}</td>
										<td>{{$val->companyagencymail}}</td>
										<td>{{$val->mobilenumber}}</td>
										
										<td id="tooltip-container1">
											<a href="" class="me-3 text-primary" data-bs-container="#tooltip-container1" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><i class="mdi mdi-pencil font-size-18"></i></a>
											<a href="" class="text-danger" data-bs-container="#tooltip-container1" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><i class="mdi mdi-trash-can font-size-18"></i></a>
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
