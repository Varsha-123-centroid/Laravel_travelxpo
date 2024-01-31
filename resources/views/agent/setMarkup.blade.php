@extends('layouts.admin.default')

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
				<div class="col-md-12 p-0">
					   <form id="fupForm" enctype="multipart/form-data">
                        @csrf 
					<div class="edit_profileSec">
						<div class="editProfileForm">
							<div class="clearDiv row">
					<table class="table table-borderless table-scroll mt-3" id="productTable">
                      <thead>
						<tr >
							<th colspan="4" style="text-align:right"><input type="button" value="+" id="addProduct" class="btn btn-primary"></th></th>
						</tr>
					  </thead>
                      <tbody>
                        <tr class="trrem">
                          <td>
                            <label>Markup Type</label>
									<div class="input_icon">
										<select class="form-control" name="sel_nature_of_business[]">
											<option value="0">Select</option>
											<option value="Destination Management Company">Destination Management Company</option>
											<option value="Tour Operator">Tour Operator</option>
											<option value="Travel Agent" selected="">Travel Agent</option>
											<option value="Wholesale Travel Company">Wholesale Travel Company</option>
											<option value="Corporate">Corporate</option>
										</select>
										<span class="editProfileSelect"><i class="fa fa-caret-down" aria-hidden="true"></i></span>
									</div>
                          </td>
                          <td>
								<label>Country</label>
									<div class="input_icon">
												<select class="form-control" name="sel_country[]" id="sel_country"  title="India">   <option value="0">Select</option>
    @foreach ($countries as $country)
	
        <option value="{{ $country->id }}">{{ $country->country_name }}</option>
    @endforeach
</select>																																               
											<span class="editProfileSelect"><i class="fa fa-caret-down" aria-hidden="true"></i></span>
										</div>
						  </td>
                          <td>
								<label>City</label>
								<div class="input_icon">
										<input type="text" name="sel_city[]" class="form-control" value="" placeholder="Enter City" title="City" id="sel_city">
											<span class="editProfileSelect"><i class="" aria-hidden="true"></i></span>
										</div>
						  </td>
                          <td>
								<label>Markup</label>
									<div class="input_icon input-group fullDiv">
									   <input type="text" id="hotel_service_per" name="hotel_markup_per[]" class="form-control" value="" maxlength="5" autocomplete="none">
									   <span class="input-group-addon markup_option_box"> % </span>
									 </div>
						  </td>
						    

                         </tr>
						 <tr class="trrem">
						 <td>
								<label>Markup Amount</label>
									<div class="input_icon input-group fullDiv">
									   <input type="text" id="hotel_amount_amt" name="hotel_amount_amt[]" class="form-control" value="" autocomplete="none">
									  
									 </div>
						  </td>
							<td>
								<label>Vaild Date<span class="mandatory">*</span></label>
								<div class="inputDiv">
								   <div class="contactDlts row">
									   <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
											<div class="row">
												<div class="col-lg-11" style="padding-right: 2px;">
													<div id="datepicker1">
														<input type="text" name="from_date[]" class="form-control w-100" placeholder="dd M, yyyy" data-date-format="dd M, yyyy" data-date-container="#datepicker1" data-provide="datepicker" style="background:#fff;">
													</div>
												</div>
												<div class="col-lg-1 p-0">	
													<a href="#!" class="arrowicon">To</a>
												</div>
											</div>
										</div>
										<div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-left:7px;">
											<div id="datepicker1">
											<input type="text" class="form-control" name="to_date[]"placeholder="dd M, yyyy" data-date-format="dd M, yyyy" data-date-container="#datepicker1" data-provide="datepicker">
											</div>
										</div>
								   </div>
								</div>
							</td>
						
							
							<td></td>
						</tr>
                      </tbody>
                   </table>
                   
								
								<div class="col-md-12 col-md-12 col-sm-12 col-xs-12">
									<div class="editProfileSubmitBtn">
										
<button type="submit" class="btn btn-success btn-block enter-btn" style="float:right;">Submit</button> 
									</div>
								</div>
								
								
							</div>
						</div>
						
                                            
					</div>
					</form>
				</div>
				  
			</div>
			
 
@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    var html = '<tr class="trrem"><td><label>Markup Type</label><div class="input_icon"><select class="form-control" name="sel_nature_of_business[]"><option value="0">Select</option><option value="Destination Management Company">Destination Management Company</option><option value="Tour Operator">Tour Operator</option><option value="Travel Agent" selected="">Travel Agent</option><option value="Wholesale Travel Company">Wholesale Travel Company</option><option value="Corporate">Corporate</option></select><span class="editProfileSelect"><i class="fa fa-caret-down" aria-hidden="true"></i></span></div></td><td><label>Country</label><div class="input_icon"><select class="form-control" name="sel_country[]"><option value="0">Select</option>@foreach ($countries as $country)<option value="{{ $country->id }}">{{ $country->country_name }}</option> @endforeach</select><span class="editProfileSelect"><i class="fa fa-caret-down" aria-hidden="true"></i></span></div></td><td><label>City</label><div class="input_icon"><input type="text" name="sel_city[]" class="form-control" value="" placeholder="Enter City" title="City" id="sel_city"><span class="editProfileSelect"><i class="" aria-hidden="true"></i></span></div></td><td><label>Markup</label><div class="input_icon input-group fullDiv"><input type="text" id="hotel_markup_per" name="hotel_markup_per[]" class="form-control" value="" maxlength="5" autocomplete="none"><span class="input-group-addon markup_option_box"> % </span></div></td></tr><tr> <td><label>Markup Amount</label><div class="input_icon input-group fullDiv"><input type="text" id="hotel_amount_amt" name="hotel_amount_amt[]" class="form-control" value="" autocomplete="none"></div></td><td><label>Vaild Date<span class="mandatory">*</span></label><div class="inputDiv"><div class="contactDlts row"><div class="col-lg-6 col-md-6 col-sm-6 col-xs-6"><div class="row"><div class="col-lg-11" style="padding-right: 2px;"><div id="datepicker1"><input type="text" name="from_date[]" class="form-control w-100" placeholder="dd M, yyyy" data-date-format="dd M, yyyy" data-date-container="#datepicker1" data-provide="datepicker" style="background:#fff;"></div></div><div class="col-lg-1 p-0">	<a href="#!" class="arrowicon">To</a></div></div></div><div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" style="padding-left:7px;"><div id="datepicker1"><input type="text" name="to_date[]" class="form-control" placeholder="dd M, yyyy" data-date-format="dd M, yyyy" data-date-container="#datepicker1" data-provide="datepicker"></div></div></div></div></td><td><button class="btn btn-danger remove"><i class="fa fa-times" aria-hidden="true"></i></button></td></tr>'; 

    $("#addProduct").click(function(){
        $('tbody').append(html);
    });

    $(document).on('click', '.remove', function(){
        
        $(this).closest('tr').empty().remove();
    });
});

$(document).ready(function(e){
    // Submit form data via Ajax
    $("#fupForm").on('submit', function(e){
        e.preventDefault();  
    
        $.ajax({
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
			url:"{{ route('admin.markupSave')}}",
            type: 'POST',
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function(){
                $('.submitBtn').attr("disabled","disabled");
                $('#fupForm').css("opacity",".5");
            },
            success: (response) => {
                if (response) {
                   // this.reset();
                    alert('Mark Up details has been submitted successfully');
				    window.location.reload();
                }
            },
        });
    });
});
</script>