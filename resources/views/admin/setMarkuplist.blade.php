@if (Auth::check())
    @if (Auth::user()->role === 3)
        @php
            $type = 'layouts.sub.default';
            $markStatus = route('sub.updateMarkupStatus');
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
                            <h4 class="mb-sm-0"> MARKUP DETAILS </h4>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                <div class="row">
                  
                        <form id="fupForm" enctype="multipart/form-data">
                            @csrf
                         
                        </form>
                  
					
						 <div class="card-body">
				     
				 @if(!empty($list))
                  <div class="edit_profileSec">
							<div class="editProfileForm">
							   <h5 class="">MARK UP LIST </h5>	
								<table id="datatable" class="table table-bordered  nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
									<thead>
                          <tr style="font-size:11px;">
                            <th>
                              
                            </th>
                            <th> MARK UP FOR</th>
                            <th> FROM DATE </th>
							<th> TO DATE </th>
							<th> BUSINESS VOLUME CATEGORY </th>
						   	<th> MARKUP PERCENTAGE </th>
                            <th> MARKUP AMOUNT</th>
                            <th> STATUS CHANGE</th>
                           
                          </tr>
                        </thead>
                        <tbody>
						 <?php $i=1; ?>
                                    @foreach($list as $val)
                          <tr>
                            <td>{{$i}}</td>
                             <td>{{$val->markup_for}}</td>
                             <td>{{$val->from_date}}</td>
                               <td>{{$val->to_date}}</td>
                             <td>{{$val->bus_vol_category}}</td>
                            <td>{{$val->markup_percent}}</td>
                           <td>{{$val->branch_markup}}</td>
						  <td>
    @if($val->status == 0)
        <button class="btn btn-danger status-btn" data-id="{{ $val->id }}" data-status="active">Inactive</button>
    @else
        <button class="btn btn-success status-btn" data-id="{{ $val->id }}" data-status="inactive">Active</button>
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
                url: "{{$markStatus}}",
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
<style>
input .btn{
    display: block;
    color: #ffffff;
    font-size: 20px;
}


</style>