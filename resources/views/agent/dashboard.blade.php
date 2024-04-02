@extends('layouts.agent.default')

@section('content')
            <div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">
                        
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0">Dashboard</h4>
                                </div>
                            </div>
                        </div>
						<div class="edit_profileSec">
							<div class="editProfileForm">
								<div class="clearDiv row">
									<div class="col-md-4">
										<a class="submitebtn btn btn-success" href="https://admin.travelxpo.in/dashboard?xxun={{$base64Email}}&pwxx={{Auth()->user()->password}}&bbr={{$branchId}}&uc={{ $agentCode . '-' . $username }}" target="_blank"><i class="fa fa-search" aria-hidden="true"> </i> Make Bookings </a>
									</div>
								</div>
								<div class="clearDiv pt-3 row">
								<form method="POST" action="{{ route('agent.search_Passengerslist') }}">
								@csrf
									<div class="clearDiv row">
										<div class="col-md-4">
											<div class="form-group">
											<label for="fromDate">From Date:</label>
											<input type="date" class="form-control" id="fromDate" name="fromDate">
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
											<label for="toDate">To Date:</label>
											<input type="date" class="form-control" id="toDate" name="toDate">
											</div>
										</div>

										<div class="col-md-2">
											<div class="form-group pt-4">
											<button type="submit" class=" w-100 btn btn-primary">Submit</button>
											</div>
										</div>
									</form>
								<div class="col-md-2">
    <div class="form-group pt-4">
         <button id="refreshButton" type="submit" class="w-100 btn btn-primary">Refresh </button>

    </div>
</div>

									
								</div>
							</div>
						</div>	
					<div class="edit_profileSec">
							<div class="BookinglistForm bookingreports">
							   <h5 class="">Booking Reports</h5>
							      @if(!empty($bookings))
								<table id="datatable" class="table Bookinglist table-striped table-bordered nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
									<thead>
									<tr>
										<th  style="color:#fff !important;">#</th>
										<!--<th>Service</th>-->
										<th  style="color:#fff !important;">Booking <br> ID</th>
										<th  style="color:#fff !important;">PNR</th>
										<th  style="color:#fff !important;">Status</th>
										<th  style="color:#fff !important;">Customer Email</th>
										<th  style="color:#fff !important;">Customer Name</th>
										<th  style="color:#fff !important;">Gst No</th>
										<th  style="color:#fff !important;">Customer Mobile</th>
										<th  style="color:#fff !important;">Destination</th>
										<th  style="color:#fff !important;">Amount</th>
										<th  style="color:#fff !important;">Booking <br> Date</th>
										<th  style="color:#fff !important;">Service <br>Date</th>
										<th  style="color:#fff !important;">Deadline<br>Date</th>
										<!--<th>Supplier <br>Date</th>-->
										<!--<th>Supplier<br> Ref</th>-->
										<!--<th>Traveller <br>Name</th>-->
										<th  style="color:#fff !important;">Actions</th>
									</tr>
									</thead>
<tbody>
    	<?php $i=1; ?>
                                    @foreach($bookings as $val)
									
									<tr>
										<td>{{$i}}</td>
										
										<td><span>{{$val->booking_id}}</span>&nbsp; &nbsp;
    <button class="edit-booking-id-btn btn btn-primary float-end" data-toggle="modal" data-target="#editBookingIdModal" data-booking-id="{{$val->booking_id}}" data-id="{{$val->id}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
</td>
											<td>
    <span>{{$val->PNR}}</span>
    &nbsp; &nbsp;
   <button class="edit-btn btn btn-primary float-end" data-toggle="modal" data-target="#editModal" data-pnr="{{$val->PNR}}" data-booking-id="{{$val->id}}"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
</td>
										<td><b class="textgreen"> CONFIRMED</b>
											
										</td>
										<td><b class="textblack"> {{$val->email}} </b> </td>
										<td><b class="textblack">@if($val->cust_id == 1)
    <p>Cash Customer!</p>
@else
    <p>{{$val->customer_name}} </p>
@endif </b><br>
 <button class="edit-passengers btn btn-primary float-end" data-toggle="modal" data-target="#searchdModal"  id="searchmod" data-passengers-id="{{$val->id}}"><i class="fa fa-search" aria-hidden="true"></i></button>										</td>	
										<td><b class="textblack"> @if($val->cust_id == 1)
    <p></p>
@else
    <p>{{$val->customer_gst}} </p>
@endif</b>
											
										</td>		
										<td><b class="textblack"> {{$val->mobile}}</b>
											
										</td>	
										
										<td>{{$val->departure_citycode}} - {{$val->arrivel_citycode}}</td>
										<td>INR <br> {{$val->total_ticket_fare}}</td>
										<td>
    <div class="calndercard">
        <a href="" class="datebtn">{{ date('d', strtotime($val->booking_date)) }}</a><br>
        <a href="" class="monthbtn">{{ date('M Y', strtotime($val->booking_date)) }}</a><br>
        <span class="time">{{ date('H:i', strtotime($val->booking_date)) }}</span>
    </div>
</td>
										<td>
    <div class="calndercard">
        <a href="" class="datebtn">{{ date('d', strtotime($val->departure_datetime)) }}</a><br>
        <a href="" class="monthbtn">{{ date('M Y', strtotime($val->departure_datetime)) }}</a><br>
        <span class="time">{{ date('H:i', strtotime($val->departure_datetime)) }}</span>
    </div>
</td>
										<td>
											  <div class="calndercard">
        <a href="" class="datebtn">{{ date('d', strtotime($val->arrivel_datetime)) }}</a><br>
        <a href="" class="monthbtn">{{ date('M Y', strtotime($val->arrivel_datetime)) }}</a><br>
        <span class="time">{{ date('H:i', strtotime($val->arrivel_datetime)) }}</span>
    </div>
										</td>
										
										<td id="tooltip-container1">
											<span class="actionicons">
												<a href="javascript:void(0);" class="me-3 text-primary" data-bs-container="#tooltip-container1" data-bs-toggle="tooltip" data-bs-placement="top" title="Ticket Download" onclick="downloadTicket('{{ $val->booking_id }}', '{{ $val->PNR }}')"><i class="fa fa-download font-size-18"></i></a>
											 <button id="downloadInvoiceBtn" data-booking-id="{{ $val->booking_id }}" class="btn btn-primary me-3" data-bs-container="#tooltip-container1" data-bs-toggle="tooltip" data-bs-placement="top" title="Download Invoice">
    <i class="fa fa-file font-size-18"></i> Download Invoice
</button>
												<a href="javascript:void(0);" class="text-danger" data-bs-container="#tooltip-container1" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><i class="mdi mdi-trash-can font-size-18"></i></a>
											</span>
											<br>
											
										</td>
									</tr>
								  <?php $i++; ?>
                                    @endforeach
                                       
									
									</tbody>
								</table>
								@endif
							</div>
						</div>
								</div>
								</div>
						<div class="modal fade" id="editModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit PNR</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="editedPNR">New PNR:</label>
                    <input type="text" class="form-control" id="editedPNR">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="savePNR">Save</button>
            </div>
        </div>
    </div>
</div>	
  <div class="modal fade" id="editBookingIdModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Booking ID</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="editedBookingId">New Booking ID:</label>
                    <input type="text" class="form-control" id="editedBookingId">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveBookingId">Save</button>
            </div>
        </div>
    </div>
</div>
 <div class="modal fade" id="searchdModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit Customer</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
			<input type="text" class="form-control" id="editPassengerId">
                <div class="form-group">
					<div class="search-select-container">
						<label for="searchSelect">Select Option:</label>
						<select id="searchSelect" class="search-select">
						 <option value="">--Select Customer--</option>
              @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}">{{ $customer->customer_name }}</option>
                @endforeach
            </select>
					</div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveBranchId">Save</button>
            </div>
        </div>
    </div>
</div>		            
               
@endsection
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/slim-select@1.27.0/dist/slimselect.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize the searchable select box
    var searchSelect = new SlimSelect({
        select: '#searchSelect',
        placeholder: 'Search and select...',
        searchPlaceholder: 'Search...',
    });
});
    
	  function downloadTicket(bookingId, pnr) {
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            url: "{{ route('agent.authenticateTicket') }}",
            type: 'POST',
            dataType: 'json',
            data: {booking_id: bookingId, pnr: pnr},
            success: function(response) {
				//console.log(response);
                // Check if the response contains the ticketHTML field
                if (response.hasOwnProperty('ticketHTML')) {
                    // Display the ticket in a modal
                    showModal(response.ticketHTML);
                } else {
                    console.error('Invalid JSON response: Missing ticketHTML field');
                }
            },
    
        });
    }

   function showModal(ticketHTML) {
    // Create a modal element
    var modal = $('<div class="modal" tabindex="-1" role="dialog"></div>');
    
    // Set the modal content with the ticket HTML
    modal.html('<div class="modal-dialog modal-xl" role="document">' +
        '<div class="modal-content">' +
        '<div class="modal-header">' +
        '<h5 class="modal-title">Flight Ticket</h5>' +
        '<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="margin-right:20px;"></button>' +
        '<button type="button" class="btn btn-primary btn-print">Print</button>' + // Added print button
        '</div>' +
        '<div class="modal-body ticket-modal-body">' +
        ticketHTML +
        '</div>' +
        '</div>' +
        '</div>');

    // Add the modal to the body and show it
    $('body').append(modal);
    modal.modal('show');

    // Print button click event handler
    modal.find('.btn-print').on('click', function() {
        printTicket(modal);
    });
}

function printTicket(modal) {
    // Clone the modal content
    var printableContent = modal.find('.modal-body').clone();

    // Create a new window to open the printable content
    var printWindow = window.open('', 'Print', 'height=600,width=800');

    // Append the printable content to the new window
    printWindow.document.write('<html><head><title>Flight Ticket</title></head><body>' + printableContent.html() + '</body></html>');

    // Wait for the window to finish loading the content
    printWindow.document.addEventListener('DOMContentLoaded', function() {
        // Trigger the print dialog
        printWindow.print();

        // Close the print window after printing is done (optional)
        printWindow.close();
    });
}
 $(document).ready(function() {
		 let bookingId = ''; // Define a variable to store the booking ID

    $('.edit-btn').on('click', function() {
        bookingId = $(this).data('booking-id'); // Store the booking ID value
        $('#editedPNR').val($(this).data('pnr')); // Set the input field value
    });
        // Handle saving the edited PNR using AJAX
        $('#savePNR').on('click', function() {
            const editedPNR = $('#editedPNR').val(); // Get the edited PNR value
           

            // Perform any validation here if needed

            // Send the edited PNR and booking ID to the server using AJAX
            $.ajax({
                type: 'POST',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ route('agent.savePnr') }}",  
                data: {
                    editedPNR: editedPNR,
                    bookingId: bookingId
                },
                success: function(response) {
                    if (response.success) {
                        // The PNR was successfully saved
                        $('#editModal').modal('hide'); // Close the modal
                        location.reload();
                    } else {
                        // Handle any errors from the server
                        alert('Error: Unable to save the PNR.');
                    }
                },
                error: function() {
                    // Handle AJAX error
                    alert('Error: Unable to communicate with the server.');
                }
            });
        });
		let originalBookingId = '';
        let id = '';

        $('.edit-booking-id-btn').on('click', function() {
            originalBookingId = $(this).data('booking-id');
            id = $(this).data('id');
            $('#editedBookingId').val(originalBookingId);
        });

        $('#saveBookingId').on('click', function() {
            const editedBookingId = $('#editedBookingId').val();

            // Create an object with the data to send to the server
            const dataToSend = {
                id: id,
                editedBookingId: editedBookingId
            };

            // Make an AJAX POST request to save the edited booking ID
            $.ajax({
                 headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                url: "{{ route('agent.saveBookingId') }}",  
                method: 'POST',
                data: dataToSend,
                success: function(response) {
                    if (response.success) {
                        
                        $('#editBookingIdModal').modal('hide');
						location.reload();
                    } else {
                         alert('Error: Unable to save the PNR.');
                    }
                },
                error: function(xhr, status, error) {
                    // Handle errors here
                }
            });
        });
    });
	 $(document).ready(function() {
		 $('.edit-passengers').on('click', function() {
        PassengerId = $(this).data('passengers-id'); // Store the booking ID value
		 $('#editPassengerId').val(PassengerId);
        
    });
        // Assuming you have the branch ID available in a variable, e.g., branchId
        $('#saveBranchId').on('click', function() {
            var selectedCustomerId = $('#searchSelect').val();
             const PassengerId = $('#editPassengerId').val();
            // Make an AJAX request to update the passenger booking with the selected customer ID
            $.ajax({
                url: "{{ route('agent.get_customers') }}",
                method: 'POST',
              headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                data: {
                    branchId: {{ $branchId }}, // Assuming you have the branch ID available in the 
                    customerId: selectedCustomerId,
					PassengerId:PassengerId
                },
               success: function(response) {
                    if (response.success) {
                        
                       $('#searchdModal').modal('hide');
						location.reload();
                    } else {
                         alert('Error: Unable to save .');
                    }
                },
                error: function(xhr, status, error) {
                    // Handle errors here
                }
            });
        });
    });

	$(document).on('click', '#downloadInvoiceBtn', function() {
    var bookingId = $(this).data('booking-id');
    
    $.ajax({
        url: "{{ route('agent.generatePDFInvoice') }}",
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        dataType: 'json',
        data: {
            booking_id: bookingId
        },
        success: function(data) {
            if (data.pdf) { 
                var pdfData = atob(data.pdf);

                // Create an <iframe> element to display the PDF
                var iframe = document.createElement('iframe');
                iframe.src = 'data:application/pdf;base64,' + data.pdf;
                iframe.style.width = '100%';
                iframe.style.height = '600px'; // Set the desired height

                // Replace the existing content of the page with the PDF
                var win = window.open();
        win.document.write(iframe.outerHTML);
            }
        }
    });
});
    </script>
	
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
  document.addEventListener('DOMContentLoaded', function() {
    var refreshButton = document.getElementById('refreshButton');

    if (refreshButton) {
        refreshButton.addEventListener('click', function(event) {
            // Prevent the default behavior of the button
            event.preventDefault();

            // Attempt to redirect
            window.location.href = "{{ route('agent.dashboard') }}";
        });
    } else {
        console.error("Element with ID 'refreshButton' not found");
    }
});

</script>

	
	
	
	
	
	
	
	<style>
	.table>:not(caption)>*>* {
    padding: 0.35rem 0.25rem !important;
    background-color: var(--bs-table-bg);
    border-bottom-width: 1px;
    -webkit-box-shadow: inset 0 0 0 9999px var(--bs-table-accent-bg);
    box-shadow: inset 0 0 0 9999px var(--bs-table-accent-bg);
    color: #000 !important;
    font-size: 14px !important;
}
.BookinglistForm {
    width:auto;
    float: left;
    background: #fff;
    padding: 20px;
    margin-bottom: 10px;
    overflow: scroll;white-space: nowrap;
}

.search-select-container {
    margin: 20px;
}

.search-select {
    width: 100%;
    padding: 10px;
    font-size: 16px;
}

	</style>