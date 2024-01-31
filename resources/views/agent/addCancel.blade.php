@if (Auth::check())
    @if (Auth::user()->role === 1)
        @php
            $type = 'layouts.admin.default';
            $getTicketsAfterToday = route('admin.getTicketsAfterToday');
			$getPassengerDetails = route('admin.getPassengerDetails');
			$storePassengerDetails = route('admin.storePassengerDetails');
			$submitmessage= route('admin.submitMessage') ;
			$getcommentslist=route('admin.commentsList') ;
        @endphp
   @elseif (Auth::user()->role === 2)
        @php
            $type = 'layouts.agent.default';
            $getTicketsAfterToday = route('agent.getTicketsAfterToday');
			$getPassengerDetails = route('agent.getPassengerDetails');
			$storePassengerDetails = route('agent.storePassengerDetails');
			$submitmessage= route('agent.submitMessage') ;
			$getcommentslist=route('agent.commentsList') ;
        @endphp 
   @else
        @php
            $type = 'layouts.sub.default';
            $getTicketsAfterToday = route('sub.getTicketsAfterToday');
			$getPassengerDetails = route('sub.getPassengerDetails');
			$storePassengerDetails = route('sub.storePassengerDetails');
			$submitmessage= route('sub.submitMessage') ;
			$getcommentslist=route('sub.commentsList') ;
        @endphp
       
    @endif
@endif
  @extends($type)
@section('content')
<style>
   
	
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
                                    <h4 class="mb-sm-0">Find Ticket Details From EmailId</h4>
                                </div>
                            </div>
                        </div>
                        <!-- end page title -->

			<div class="row">
				<div class="col-md-12 p-0">
    <form id="formup" enctype="multipart/form-data"  method="post">
        @csrf
        <div class="edit_profileSec">
            <div class="editProfileForm">
                <div class="clearDiv row">
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3 form-group">
                        <label>Email Id/Phone Number<span class="mandatory">*</span></label>
                        <div class="input_icon">
                            <input type="text" name="ephone" id="ephone" value="" title="" placeholder="" class="form-control" autocomplete="none">
                        </div>
                    </div>
                    <!-- Add more form fields as needed -->
                </div>
                <button type="button" id="submitBtn">Submit</button>
            </div>
        </div>
    </form>
</div>
<div id="tableContainer"></div>
<div id="passengerTable"></div>
<div id="messageFormContainer"></div>
			</div>
			</div>
			</div><!-- Comments Modal -->
<div class="modal fade" id="commentsModal" tabindex="-1" role="dialog" aria-labelledby="commentsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="commentsModalLabel">Submit Comments</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
				<input type="hidden" id="bookingIdInput">
<input type="hidden" id="pnrInput">
            </div>
            <div class="modal-body" >
                <div class="mb-3">
                    <label for="commentsTextarea" class="form-label">Please Enter Comments:</label>
                    <textarea class="form-control" id="commentsTextarea" rows="4"></textarea>
                </div>
            </div>
			<div class="modal-body" id="commentsModalBody">
        <!-- This is where your chat messages will be appended -->
    </div>
            <div class="modal-footer">
                 <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="submitMessage()" id="submitCommentsBtn">Submit Comments</button>
            </div>
        </div>
    </div>
</div>

	
@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
 $(document).ready(function () {
    $('#submitBtn').on('click', function () {
        var ephone = $('#ephone').val();

        $.ajax({
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{$getTicketsAfterToday}}", // Replace with your actual route
            data: {
                ephone: ephone,
                _token: '{{ csrf_token() }}',
            },
            dataType: 'json',
            success: function (data) {
                // Check if data is not empty
                if (data.length > 0) {
                    // Create a table dynamically
                    var table = $('<table>').addClass('table');
                    var thead = $('<thead>').appendTo(table);
                    var tbody = $('<tbody>').appendTo(table);

                    // Add table headers
                    var headers = ['Email', 'Mobile', 'PNR', 'Booking ID', 'Price', 'Departure Date', 'Origin', 'Destination','Action'];
                    var headerRow = $('<tr>').appendTo(thead);
                    $.each(headers, function (_, header) {
                        $('<th>').text(header).appendTo(headerRow);
                    });

                    // Add table rows with data
                    $.each(data, function (_, booking) {
                        var row = $('<tr>').appendTo(tbody);
                        $('<td>').text(booking.email).appendTo(row);
                        $('<td>').text(booking.mobile).appendTo(row);
                        $('<td>').text(booking.PNR).appendTo(row);
                        $('<td>').text(booking.booking_id).appendTo(row);
                        $('<td>').text(booking.total_ticket_fare).appendTo(row);
                        $('<td>').text(booking.departure_datetime).appendTo(row);
                        $('<td>').text(booking.departure_citycode).appendTo(row);
                        $('<td>').text(booking.arrivel_citycode).appendTo(row);
						 var actionButton = $('<button>').text('View Details').addClass('btn btn-primary btn-sm');
actionButton.on('click', function () {
    // Get the booking ID and PNR from the row or wherever they are stored
    var bookingId = booking.booking_id;
    var pnr = booking.PNR;

    // Make an AJAX request to the server
    $.ajax({
        type: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "{{$getPassengerDetails}}", // Replace with your actual route
        data: {
            bookingId: bookingId,
            pnr: pnr,
            _token: '{{ csrf_token() }}',
        },
        dataType: 'json',
        success: function (response) {
      if (response.passengerDetails && response.passengerDetails.length > 0) {
    // Create a table and append it to the #passengerTable element
    var table = $('<table>').addClass('table');
    var headerRow = $('<tr>').appendTo(table);

    // Add table headers
    $.each(response.passengerDetails[0], function (key) {
        $('<th>').text(key).appendTo(headerRow);
    });

    // Add a checkbox header
    $('<th>').html('<input type="checkbox" id="selectAllCheckbox">').appendTo(headerRow);

    // Add table rows
    $.each(response.passengerDetails, function (index, passenger) {
        var row = $('<tr>').appendTo(table);

        // Add data columns
        $.each(passenger, function (key, value) {
            $('<td>').text(value).appendTo(row);
        });

        // Add a checkbox for each row
        $('<td>').html('<input type="checkbox" class="selectCheckbox">').appendTo(row);
		 row.data('passengerData', passenger);
    });

    // Add a single "Cancel" button after the table
    var cancelButton = $('<button>').text('Cancel').addClass('btn btn-danger').on('click', function () {
        // Collect the selected rows
       var selectedRows = [];
    $('.selectCheckbox:checked').each(function () {
        selectedRows.push($(this).closest('tr'));
    });

    // Extract passenger data from selected rows
    var passengerDetails = selectedRows.map(function (row) {
        return row.data('passengerData');
    });

    // Submit the selected rows
    submitSelectedRows(passengerDetails);
    });

    // Append the table to the #passengerTable element and the "Cancel" button to the same container
    $('#passengerTable').html('').append(table).append(cancelButton);

    // Handle select all checkbox
    $('#selectAllCheckbox').on('change', function () {
        $('.selectCheckbox').prop('checked', $(this).prop('checked'));
    });
}else {
            // No passenger details available
            $('#passengerTable').html('<p>No passenger details available</p>');
        }
        },
        error: function (xhr, status, error) {
            // Handle errors
            console.error('Error:', status, error);
        }
    });
});

$('<td>').append(actionButton).appendTo(row);
 var submitCommentsButton = $('<button>').text('Submit Comments').addClass('btn btn-primary btn-sm');
submitCommentsButton.on('click', function () {
    var bookingId = booking.booking_id;
    var pnr = booking.PNR;

    // Set data attributes using attr
   $('#bookingIdInput').val(bookingId);
    $('#pnrInput').val(pnr);

  $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
         url: "{{$getcommentslist}}",
        type: 'GET',
        data: { bookingId: bookingId, pnr: pnr },
        success: function (data) {
           var commentsArray = data.messageComments;

        // Update the modal or perform any other actions with the comments data
        var htmlChat = '<div class="chat-container">';
htmlChat += '<h3>Comments</h3>';
       $.each(commentsArray, function (index, comment) {
    htmlChat += renderChatMessage(comment);
});
        htmlChat += '</div>';

        // Append the chat container HTML to your modal
        $('#commentsModalBody').html(htmlChat);

        // Show the comments modal
        },
        error: function (xhr, status, error) {
            // Handle errors
            console.error(error);
        }
    });
    // Show the comments modal
    $('#commentsModal').modal('show');
                        });

                        $('<td>').append(submitCommentsButton).appendTo(row);
                    });

                    // Append the table to a specified HTML element
                    $('#tableContainer').html(table);
                } else {
                    // Handle case when no data is returned
                    alert('No data found.');
                }
            },
            error: function (xhr, status, error) {
                // Handle the error response
                if (xhr.responseJSON && xhr.responseJSON.error) {
                    alert('Error: ' + xhr.responseJSON.error);
                } else {
                    alert('An error occurred while fetching data.');
                }
            }
        });
    });
	
});
function renderChatMessage(comment) {
   var userId = comment.branch_user_id;
    var message = (userId == 1) ? comment.txpo_comments : comment.customer_comments;

    // Determine the message position based on the user ID
    var messageClass = (userId == 1) ? 'right-message' : 'left-message';

    var chatMessage = '<div class="chat-message ' + messageClass + '">';
    chatMessage += '<p>' + message + '</p>';
    chatMessage += '</div>';

    return chatMessage;
}

function submitMessage() {
        var messages = $('#commentsTextarea').val();
	
        var bookingId =$('#bookingIdInput').val();
		var pnr=$('#pnrInput').val();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: "{{$submitmessage}}",
            method: 'POST',
            data: {
                messages: messages,
                bookingId: bookingId,
				pnr:pnr,
               
            },
            success: function (response) {
             
alert('Message/Comments submitted successfully:');
            },
            error: function (error) {
                console.log('Error submitting Comments:', error);
            }
        });
    }
function submitSelectedRows(passengerDetails) {
   

    // Perform the AJAX request
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: "{{$storePassengerDetails}}",
        method: 'POST',
        data: {
            passengerDetails: passengerDetails,
            _token: '{{ csrf_token() }}',
        },
        success: function (response) {
            console.log('Passenger details stored successfully.');
			alert('Cancellation successful!');
            window.location.reload();
        },
        error: function (error) {
            console.error('Error storing passenger details:', error);
        }
    });
}

</script>

