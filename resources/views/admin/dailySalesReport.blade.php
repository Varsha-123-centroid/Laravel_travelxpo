@if(Auth::user()->role == 1)
    @php $type = "layouts.admin.default"; @endphp
@elseif(Auth::user()->role == 2)
    @php $type = "layouts.agent.default"; @endphp
@elseif(Auth::user()->role == 3)
    @php $type = "layouts.sub.default"; @endphp
@endif
@extends($type)


@section('content')
<div class="main-content">
	<div class="page-content">
       <div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<div class="page-title-box d-sm-flex align-items-center justify-content-between">
						<h4 class="mb-sm-0">DAILY SALES REPORT</h4>
					</div>
				</div>
			</div>
			<!-- end page title -->

			<div class="row">
				<div class="col-md-12 p-0">
					<div class="edit_profileSec">
						<div class="editProfileForm">
						  <h3 class="mb-3">Summarized Report</h3> <!-- Heading for the report -->
							 <table class="table table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<tr align="center" class="bg-gary text-cenetr">
									<th class="text-cenetr bg-gary" colspan="5">Summarized Report</th>
								</tr>
								<tr>
									<th>Branch Name</th>
									<th>Total Agent Sales</th>
									<th>Total Supplier Price</th>
									<th>Total Profit</th>
									<th>%</th>
								</tr>
								<tr>
									<td>
										@if (!empty($list[0]->branch_name))
											{{ $list[0]->branch_name }}
										
										@endif
									</td>

									<td>
										@if (!empty($totalAgentSales))
											{{ $totalAgentSales }}
									   
										@endif
									</td>

									<td>
										@if (!empty($totalSupplierPrice))
											{{ $totalSupplierPrice }}
									   
										@endif
									</td>

									<td>
										@if (!empty($profit))
											{{ $profit }}
									   
										@endif
									</td>

									<td>
										@if (!empty($profitPercentage))
											{{ $profitPercentage }}%
										
										@endif
									</td>
								</tr>
							</table>
					    </div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 p-0">
					<div class="edit_profileSec">
						<div class="editProfileForm">
						<h3 class="mb-3">Daily Sales Report</h3>		
						 @if(!empty($list))
						<div class="table-responsive">
							<table id="datatable" class="table table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
									<tr>
										<th>Booking Date</th>
										<th>Service Date</th>
										<th>Invoice Date</th>
										<th>CheckOut Date</th>
										<th>Booking ID</th>
									    <th>Agent Code</th>
									    <th>Service Name</th>
									    <th>Voucher ID</th>
									    <th>Passenger Email</th>
										<th>Customer Amount</th>
										<th>Service Tax</th>
										<th>Supplier Amount</th>
										<th>Grand Total Profit</th>
										<th>Profit %</th>
									</tr>
								</thead>
								<tbody>
									<?php $i=1; ?>
                                    @foreach($list as $val)
									<tr>
										<td>{{$val->booking_date}}</td>
										<td>{{$val->departure_datetime}}</td>
										<td>{{$val->invoice_date}}</td>
										<td>{{$val->arrivel_datetime}}</td>
										<td>{{$val->invoice_booking_id}}</td>
									    <td>{{$val->agcode}}</td>
										<td>Flight Booking</td>
										<td>{{$val->invoice_billno}}</td>
										<td>{{$val->email}}</td>
										<td>{{$val->total_ticket_fare}}<?php
										$totalAgentSales += $val->total_ticket_fare; // Add the value to the sum
										?></td>
										<td>{{$val->total_taxamt}}</td>
										<td>{{$val->supplier_price}}</td>
										<th>{{$val->total_ticket_fare-$val->supplier_price}}</th>
										<th>
    @if ($val->total_ticket_fare > 0)
        {{ ($val->total_ticket_fare - $val->supplier_price) / $val->total_ticket_fare * 100 }}%
    @else
        0%
    @endif
</th>
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
				 
					<div class="editProfileForm">
						<div class="col-md-4">
							@if(auth()->user()->agent_type === 'txpo')
    <a class="submitebtn btn btn-success" href="{{ route('admin.exportDailySales') }}" id="export" onclick="exportStudent(event.target);"><i class="fa fa-download" aria-hidden="true"></i> Download</a>
@elseif(auth()->user()->agent_type === 'main')
    <a class="submitebtn btn btn-success" href="{{ route('agent.exportDailySales') }}" id="export" onclick="exportStudent(event.target);"><i class="fa fa-download" aria-hidden="true"></i> Download</a>
@elseif(auth()->user()->agent_type === 'sub')
    <a class="submitebtn btn btn-success" href="{{ route('sub.exportDailySales') }}" id="export" onclick="exportStudent(event.target);"><i class="fa fa-download" aria-hidden="true"></i> Download</a>
@endif

						</div>
					</div>
				 
				 
				</div>
			</div>
				  
			</div>
			</div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script> function exportStudent(_this) {
      let _url = $(_this).data('href');
      window.location.href = _url;
   } </script>