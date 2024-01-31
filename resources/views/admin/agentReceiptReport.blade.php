
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
                                    <h4 class="mb-sm-0">Agent Reciept Report</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

			<div class="row">
				<div class="col-md-12 p-0">
					
                         
						<div class="edit_profileSec">
							<div class="editProfileForm">
							   <h5 class="">Agent Reciept Report</h5>	
							    @if(!empty($reciept))
								<table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
									<thead>
									<tr>
									    <th> Branch Name </th>
										<th> Reciept Date </th>
										<th>Reciept Number</th>
										<th>Account Reference No</th>
									    <th>Agent Name</th>
										<th>Receipt Amount</th>
										<th>Receipt Due Amount</th>
										<th>Receipt Mode</th>
									</tr>
									</thead>


									<tbody>
									<?php $i=1; ?>
                                    @foreach($reciept as $val)
									<tr>
										
										 <td>{{$val->branch_name}}</td>
										 <td>{{$val->approval_date}}</td>
										
										<td> {{$val->receipt_number}} </td>
										<td> {{$val->reference_no}} </td>
										<td> {{$val->first_name}} </td>
										<td> {{$val->payment_amt}} </td>
										<td> {{$val->payment_due}} </td>
										<td> {{$val->mode_of_payment}} </td>
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
				  <div class="editProfileForm mb-5">
						<div class="col-md-4">
							@if(auth()->user()->agent_type === 'txpo')
    <a class="submitebtn btn btn-success" href="{{ route('admin.exportAgentSales') }}" id="export" onclick="exportStudent(event.target);"><i class="fa fa-download" aria-hidden="true"></i> Download</a>
@elseif(auth()->user()->agent_type === 'main')
    <a class="submitebtn btn btn-success" href="{{ route('agent.exportAgentSales') }}" id="export" onclick="exportStudent(event.target);"><i class="fa fa-download" aria-hidden="true"></i> Download</a>

@endif

						</div>
					</div>
			</div>
			</div>
			</div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
