@if(Auth::user()->role == 1)
    @php $type = "layouts.admin.default"; @endphp
    @php $report = route('admin.getbookingReport'); @endphp
	@php $excel = route('admin.exportBookingReport'); @endphp
@elseif(Auth::user()->role == 2)
    @php $type = "layouts.agent.default"; @endphp
    @php $report = route('agent.getbookingReport'); @endphp
	@php $excel = route('agent.exportBookingReport'); @endphp
@elseif(Auth::user()->role == 3)
    @php $type = "layouts.sub.default"; @endphp
    @php $report = route('sub.getbookingReport'); @endphp
	@php $excel = route('sub.exportBookingReport'); @endphp
@endif
@extends($type)


@section('content')
<div class="main-content">
	<div class="page-content">
       <div class="container-fluid">
			<div class="row">
				<div class="col-12">
					<div class="page-title-box d-sm-flex align-items-center justify-content-between">
						<h4 class="mb-sm-0">BOOKING REPORT</h4>
					</div>
				</div>
			</div>
			<!-- end page title -->

			<div class="row">
				<div class="col-md-12 p-0 edit_profileSec">
					<div class="editProfileForm ">
					<form id="reportForm" action="{{ $excel }}" method="POST">
					@csrf
    <div class="row ">
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
            <label for="fromDate">From Date:</label>
            <input type="date" class="form-control" id="fromDate" name="fromDate">
        </div>

        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
            <label for="toDate">To Date:</label>
            <input type="date" class="form-control" id="toDate" name="toDate">
        </div>

        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
			<label for="selectBox">Select Box:</label>
			<select class="form-control" id="branches" name="branches">
				@foreach ($subbranches as $subbranch)
					<option value="{{ $subbranch->id }}">{{ $subbranch->branch_name }}</option>
				@endforeach
			</select>
		</div>
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
				
		 <button type="button" class="submitebtn mt-4 btn btn-success p-2" onclick="generateReport()">Generate Report</button>
		 <button type="submit" class="submitebtn btn mt-4 btn-success p-2" >Download</button>
		 </div>
	</form>
</div>


					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 p-0">
					<div class="edit_profileSec">
						<div class="editProfileForm">
						<h3 class="mb-3">BOOKING REPORT</h3>		
						
						<div class="table-responsive">
							<table id="datatable" class="table table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
								<thead>
									<tr>
									    <th>Booking ID</th>
										<th>Booking Date</th>
										<th>Departure Date</th>
										<th>Arrival Date</th>
										<th>PNR</th>
										<th>Booking Amount </th>
										<th>Service Type</th>
										<th>Departure City Code</th>
										<th>Arrival City Code</th>
										<th>Passenger Type </th>
										<th>Branch Name </th>
										<th>Agent Code </th>
										<th>Agent Name </th>
										<th>Agent Consultant </th>
										<th>Agent Markup </th>
										<th>Supplier Booking ID </th>
										<th>Itenary Booking ID </th>
										<th>Supplier Reference No</th>
										<th>Status</th>
										<th>Leader Name</th>
										<th>Service Date</th>
										<th>Hotel Name</th>
										<th>No of Nights</th>
										<th>Total Room Nights</th>
										<th>ROE</th>
										<th>Voucher Id</th>
									    <th>Approximate Revenue</th>
										<th>Supplier RATE</th>
										<th>Supplier Currency</th>
									</tr>
								</thead>
								
									
									   <tbody id="tableBody"></tbody>
                               
									
								</table>
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
<script> function exportStudent(_this) {
      let _url = $(_this).data('href');
      window.location.href = _url;
   } </script>

<script>
    $(document).ready(function () {
        // Get today's date in the format "YYYY-MM-DD"
        var today = new Date().toISOString().split('T')[0];

        // Set the input field values to today's date
        $('#fromDate').val(today);
        $('#toDate').val(today);
    });
</script>
<script>
    function generateReport() {
        var fromDate = $('#fromDate').val();
        var toDate = $('#toDate').val();
        var branchId = $('#branches').val();

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ $report }}",
            type: 'POST',
            data: {
                fromDate: fromDate,
                toDate: toDate,
                branchId: branchId
            },
            success: function (data) {
                // Handle the received data and populate the DataTable
				
                populateDataTable(data);
				
            },
            error: function (error) {
                console.log(error);
                // Handle errors
            }
        });
    }

    function populateDataTable(data) {
    var tableBody = $('#tableBody');
    tableBody.empty();

    // Check if data is empty
    if (data.length === 0) {
        tableBody.html('<tr><td colspan="3" class="text-center">No data available</td></tr>');
        return;
    }

    // Loop through the data and populate the table
    $.each(data, function (index, item) {
        console.log(item); // Confirm the structure and values of the item object

        var row = $('<tr>');
        row.append('<td>' + item.booking_id + '</td>');
        row.append('<td>' + item.booking_date + '</td>');
		row.append('<td>' + item.departure_datetime + '</td>');
		row.append('<td>' + item.arrivel_datetime + '</td>');
		row.append('<td>' + item.PNR + '</td>');
        row.append('<td>' + item.total_ticket_fare + '</td>');
		
		row.append('<td>' + 'Flight Booking' + '</td>');
        row.append('<td>' + item.departure_citycode + '</td>');
        row.append('<td>' + item.arrivel_citycode + '</td>');
		row.append('<td>' + item.passengertype + '</td>');
		row.append('<td>' + item.branch_name + '</td>');
		row.append('<td>' + item.agent_code + '</td>');
		row.append('<td>' + item.agent_name + '</td>');
		
		row.append('<td>' + item.agent_consultant + '</td>');
		row.append('<td>' + item.markupamt + '</td>');
		row.append('<td>' + item.supplier_bookingid + '</td>');
		row.append('<td>' + item.itenary_bookingid + '</td>');
		row.append('<td>' + item.supplier_referenceno + '</td>');
		row.append('<td>' + item.Status + '</td>');
		row.append('<td>' + item.leader_name + '</td>');
		row.append('<td>' + item.service_date + '</td>');
		row.append('<td>' + item.hotel_name + '</td>');
		row.append('<td>' + item.no_of_nights + '</td>');
		row.append('<td>' + item.total_room_nights + '</td>');
		row.append('<td>' + item.roe + '</td>');
		row.append('<td>' + item.voucher_id + '</td>');
		row.append('<td>' + item.approx_revenue + '</td>');
		row.append('<td>' + item.supplier_rate + '</td>');
		row.append('<td>' + item.supplier_currency + '</td>');
        tableBody.append(row);
    });
}


	function exportReport() {
        // Prevent the default behavior of the link
        event.preventDefault();

         var fromDate = $('#fromDate').val();
        var toDate = $('#toDate').val();
        var branchId = $('#branches').val();

        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ $excel }}",
            type: 'POST',
            data: {
                fromDate: fromDate,
                toDate: toDate,
                branchId: branchId
            },
            success: function () {
    // Assuming the export is successful, you might want to notify the user or perform other actions
    console.log('Export successful');
},
error: function (error) {
    console.log('Export error:', error);
    // Handle errors, show an alert, etc.
}
        });
    }								
										
								
</script>