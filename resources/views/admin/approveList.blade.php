@if (Auth::user()->role === 1)
        @php
            $type = 'layouts.admin.default';
            $paymentStatus = route('admin.paymentStatus');
        @endphp
   @else (Auth::user()->role === 2)
        @php
            $type = 'layouts.agent.default';
            $paymentStatus = route('agent.paymentStatus');
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
                                    <h4 class="mb-sm-0">PAYMENT APPROVAL LIST</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

			<div class="row">
				<div class="col-md-12 p-0">
					
                         
					 <div class="card-body">
				     
				 @if(!empty($list))
                  <div class="edit_profileSec">
							<div class="editProfileForm">
							   <h5 class=""> PAYMENT APPROVAL LIST </h5>	
								<table id="datatable" class="table table-bordered  nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
									<thead>
                          <tr style="font-size:11px;">
                            <th>
                              
                            </th>
							<th>Branch Name</th>
                            <th>Date Of Payment</th>
                            <th> Payment Amount </th>
							<th> Payment Due</th>
							<th>Referene No </th>
						    <th>Mode Of Payment </th>
                            <th> STATUS CHANGE</th>
                           
                          </tr>
                        </thead>
                        <tbody>
						 <?php $i=1; ?>
                                    @foreach($list as $val)
                          <tr>
                            <td>{{$i}}</td>
							  <td>{{$val->branch_name}}</td>
                             <td>{{$val->date_of_payment}}</td>
                             <td>{{$val->payment_amt}}</td>
                               <td>{{$val->payment_due}}</td>
                             <td>{{$val->reference_no}}</td>
                               <td> @if ($val->mode_of_payment === 'credit limit')
        <p>Opening Balance</p>
    @else
        <p> {{ $val->mode_of_payment }}</p>
    @endif</td>
						  <td>
    @if($val->status == 0)
        <button class="btn btn-danger status-btn" data-id="{{ $val->id }}" data-status="active">Not Approved</button>
    @else
        <button class="btn btn-success status-btn" data-id="{{ $val->id }}" data-status="inactive" disabled>Approved</button>
    @endif
</td>
						
                          </tr>
                        <?php $i++; ?>
                                    @endforeach
                                       
                        </tbody>
                      </table>
                    </div>
						@endif
				
                  </div>	
                
            </div>
						 
					</div>
				</div>
				<div class="editProfileForm mb-5">
						<div class="col-md-4">
							@if(auth()->user()->agent_type === 'txpo')
    <a class="submitebtn btn btn-success" href="{{ route('admin.approvalReport') }}" id="export" onclick="exportStudent(event.target);"><i class="fa fa-download" aria-hidden="true"></i> Download</a>
@elseif(auth()->user()->agent_type === 'main')
    <a class="submitebtn btn btn-success" href="{{ route('agent.approvalReport') }}" id="export" onclick="exportStudent(event.target);"><i class="fa fa-download" aria-hidden="true"></i> Download</a>

@endif

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
            var status = btn.data('status');

            // Send AJAX request
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{$paymentStatus}}",
                type: 'POST',
                data: {
                    id: id,
                    status: status
                },
                success: function(response) {
    // Update button and status
    if (response.status === 'success') {
        if (response.newStatus === 1) {
            btn.removeClass('btn-danger').addClass('btn-success').text('Active');
            btn.data('status', 'active');
			window.location.reload();
        } else {
            btn.removeClass('btn-success').addClass('btn-danger').text('Inactive');
            btn.data('status', 'inactive');
			window.location.reload();
        }
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