
@if(Auth::User()->agent_type== "txpo")
 @php 
   $type = "layouts.admin.default";
@endphp

@endif


@extends($type)



@section('content')
<style>
    .custom-modal .modal-dialog {
        /* Add your custom styles here */
        max-width: 1000px; /* Adjust the width as needed */
    }

    /* Add more custom styles if necessary */
	
	.chat-message {
    width: 100%;
    box-sizing: border-box;
    padding: 5px;
    margin-bottom: 10px;
}

.left-message {
    text-align: left;
    background-color: #e0f7fa; /* Customize background color for left messages */
}

.right-message {
    text-align: right;
    background-color: #a5d6a7; /* Customize background color for right messages */
}

</style>

<div class="main-content">

                <div class="page-content">
                    <div class="container-fluid">
                        
                        <!-- start page title -->
                        <div class="row">
                            <div class="col-12">
                                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                    <h4 class="mb-sm-0">Cancel LIST</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

			<div class="row">
				<div class="col-md-12 p-0">
					
                         
						<div class="edit_profileSec">
							<div class="editProfileForm">
							   <h5 class="">Cancel LIST</h5>	
							    @if(!empty($cancelList))
								<table id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
									<thead>
									<tr>
										
           
            <th>Branch</th>
           <th>BranchId</th>
            <th>Booking ID</th>
            <th>Request Date</th>
			 <th>Mobile</th>
			  <th>PNR</th>
            <th>Departure Date</th>
			
			 <th>Origin</th>
			 <th>Destination</th>
			  <th>Passenger Type</th>
            <th>Actions</th>
									</tr>
									</thead>


									<tbody>
									<?php $i=1; ?>
                                   @foreach($cancelList as $passenger)
									<tr>
										
                <td>{{ $passenger->branch_name }}</td>
                 <td>{{ $passenger->branchid }}</td>
                <td>{{ $passenger->booking_id }}</td>
                <td>{{ $passenger->request_date }}</td>
				<td>{{ $passenger->mobile }}</td>
				<td>{{ $passenger->pnr_number }}</td>
				<td>{{ $passenger->departure_datetime }}</td>
				<td>{{ $passenger->arrivel_citycode }}</td>
				<td>{{ $passenger->departure_citycode }}</td>
				<td>{{ $passenger->passengertype }}</td>
										
										<td id="tooltip-container1">
											
										<a href="#" class="text-danger cancel-button" data-booking-id="{{ $passenger->booking_id }}" data-pnr="{{ $passenger->pnr_number }}" data-bs-container="#tooltip-container1" data-bs-toggle="tooltip" data-bs-placement="top" title="Cancel">
    <i class="mdi mdi-trash-can font-size-18"></i>
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
				 <!-- Add a custom class to the modal's div -->
<div class="modal fade custom-modal" id="messagesModal" tabindex="-1" role="dialog" aria-labelledby="messagesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg"> <!-- Change the class to modal-lg for a larger modal -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="messagesModalLabel">Cancelation Request List</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="messagesModalBody">
                <!-- Content goes here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

			</div>
			</div>
@endsection
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
<script>
    $(document).ready(function () {
        $('.cancel-button').on('click', function (e) {
            e.preventDefault();
            var bookingId = $(this).data('booking-id');
            var pnrno= $(this).data('pnr');
            // Fetch data using AJAX
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('admin.cancelBooking') }}",
                method: 'GET',
                data: { bookingId: bookingId },
                dataType: 'json',
              success: function (data) {
    // Assuming 'data' is an object with 'messages' and 'messageComments' properties
    var messagesArray = data.messages;
    var messageCommentsArray = data.messageComments;

    // Update modal content with fetched data in table format
    var html = '<table class="table">';
    html += '<thead><tr><th>ID</th><th>Passenger Name</th><th>Ticket ID</th><th>Status</th><th>Comments</th><th>Ticket Number</th><th>PNR Number</th><th>Mobile Number</th><th>Email</th><th>Booking ID</th><th>Request Date</th></tr></thead>';
    html += '<tbody>';

    // Loop over the outer array
    $.each(messagesArray, function (index, message) {
        // Assuming that 'message' object has properties like 'id' and 'passengername'
        var messageId = message.branch_name;
        var passengerName = message.passengername;
        var ticketid = message.ticketid;
        var status = message.status;
        var comments = message.comments;
        var ticket_number = message.ticket_number;
        var pnr_number = message.pnr_number;
        var mobile_number = message.mobile_number;
        var email = message.email;
        var booking_id = message.booking_id;
        var request_date = message.request_date;

        html += '<tr>';
        html += '<td>' + messageId + '</td>';
        html += '<td>' + passengerName + '</td>';
        html += '<td>' + ticketid + '</td>';
        html += '<td>' + status + '</td>';
        html += '<td>' + comments + '</td>';
        html += '<td>' + ticket_number + '</td>';
        html += '<td>' + pnr_number + '</td>';
        html += '<td>' + mobile_number + '</td>';
        html += '<td>' + email + '</td>';
        html += '<td>' + booking_id + '</td>';
        html += '<td>' + request_date + '</td>';
        // Add more properties as needed
        html += '</tr>';
    });

    html += '</tbody></table>';

    // Chat container
   var htmlChat = '<div class="chat-container">';
htmlChat += '<h3>Comments</h3>'; // Add a heading for the comment list
$.each(messageCommentsArray, function (index, comment) {
    htmlChat += renderChatMessage(comment);
});
htmlChat += '</div>';

    // Message form
    html += '<form id="messageForm">';
    html += '<div class="mb-3">';
    html += '<label for="messageForm" class="form-label">Please Enter Message:</label>';
    html += '<input type="text" class="form-control" id="messagerequest" name="messagerequest">';
    html += '</div>';
    html += '<button type="button" class="btn btn-primary" onclick="submitMessage(' + bookingId + ', \'' + pnrno + '\')">Submit Comments</button>';
    html += '</form>';

    // Cancellation form
    html += '<form id="cancellationForm">';
    html += '<div class="mb-3">';
    html += '<label for="cancellationAmount" class="form-label">Please Enter Cancellation Amount:</label>';
    html += '<input type="text" class="form-control" id="cancellationAmount" name="cancellationAmount">';
    html += '</div>';
    html += '<button type="button" class="btn btn-primary" onclick="submitCancellation(' + bookingId + ')">Submit Cancellation</button>';
    html += '</form>';

    // Append both the table and chat container to the modal body
    $('#messagesModalBody').html(html + htmlChat);

    // Show the modal
    $('#messagesModal').modal('show');
},
 error: function (error) {
                    console.log('Error:', error);
                }
            });
        });
    });
	
function renderChatMessage(comment) {
    // Customize this function based on your data structure
    // Assuming that 'comment' object has properties like 'branch_user_id', 'txpo_comments', 'customer_comments'
    var userId = comment.branch_user_id;
    var message = (userId == 1) ? comment.txpo_comments : comment.customer_comments;

    // Determine the message position based on the user ID
    var messageClass = (userId == 1) ? 'right-message' : 'left-message';

    var chatMessage = '<div class="chat-message ' + messageClass + '">';
    chatMessage += '<p>' + message + '</p>';
    chatMessage += '</div>';

    return chatMessage;
}

	function submitMessage(bookingId,pnrno) {
        var messages = $('#messagerequest').val();
	
        var bookingId =bookingId;
		var pnr=pnrno;
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ route('admin.submitMessage') }}",
            method: 'POST',
            data: {
                messages: messages,
                bookingId: bookingId,
				pnr:pnr,
               
            },
            success: function (response) {
                // Handle the success response
                console.log('messageForm submitted successfully:', response);
alert('Message/Comments submitted successfully:');

                // Optionally, you can close the modal or perform other actions
            },
            error: function (error) {
                console.log('Error submitting Comments:', error);
            }
        });
    }

function submitCancellation(bookingId) {
        var cancellationAmount = $('#cancellationAmount').val();

        // Assuming other variables are accessible in this scope
        var bookingId =bookingId;
		
       

        // Make an AJAX POST request to the controller
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{ route('admin.submitCancellation') }}",
            method: 'POST',
            data: {
                cancellationAmount: cancellationAmount,
                bookingId: bookingId,
               
            },
            success: function (response) {
                // Handle the success response
                console.log('Cancellation submitted successfully:', response);
alert('Cancellation submitted successfully:');
location.reload();
                // Optionally, you can close the modal or perform other actions
            },
            error: function (error) {
                console.log('Error submitting cancellation:', error);
            }
        });
    }
</script>


