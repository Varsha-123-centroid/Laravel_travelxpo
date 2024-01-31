<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"/>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
	
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="compatibility.min.js"></script>
    <script src="theViewer.min.js"></script>
    <title>Invoice</title>
	
<style>
body {
    margin: 0;
    font-family: 'DejaVu Sans', sans-serif !important;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1;
    color: #212529;
    text-align: left;
    background-color: #fff;
}
.container {
    max-width: 1300px;
}
.row {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-wrap: wrap;
    flex-wrap: wrap;
    margin-right:15px;
    margin-left:15px;
}
.col-lg-1 {
    max-width: 90px;
}
.col-lg-2 {
    max-width: 217px;
}
.col-lg-3 {
    max-width: 325px;
}
.col-lg-4 {
    max-width: 430px;
}
.col-lg-5 {
    max-width: 542px;
}
.col-lg-6 {
    max-width: 650px;
}
.col-lg-7 {
    max-width: 750px;
}
.col-lg-8 {
    max-width: 866px;
}
.col-lg-9 {
    max-width: 975px;
}
.col-lg-10 {
    max-width:1083px;
}
.col-lg-11 {
    max-width:1191px;
}
.col-lg-12 {
    max-width: 1300px;
}
.addressfrom p{
	margin-bottom:0px;
}
.text-center{
	text-align:center;
}
.text-primary{
	color:#037d99;
}
.text-right{
	text-align:right;
	float:right;
}
.text-left{
	text-align:left;
	float:left;
}
.addressto{
	font-weight:bold;
}
.section1{
	height:280px
}
.section2{
	height:80px
}
.section3{
	height:120px
}
.img-fluid{
	width:300px;
}

</style>
	
</head>
<body>
    <div class="container">
       @if(isset($userData->additionalParameter) && $userData->additionalParameter === '1')
<div class="row section1">
    <div class="col-lg-4 text-left">
        <div class="addressfrom">
            <h3>TRAVELXPO</h3>
            <p>1st Floor, Adarsh Arcade,<br>
                Opp. Railway Muthappan Temple,<br>
                Kannur - 670001<br>
                Phone no. : 9497164477<br>
                Email: dreamholidayskannur@gmail.com<br>
                GSTIN: 32AKHPR5455M2ZO<br>
                State: 32-Kerala
            </p>
        </div>
    </div>
    <div class="col-lg-5">
    
    </div>
    <div class="col-lg-3 text-right">
        <div class="logo image">
            <img class="img-fluid" alt="" src="https://travelxpo.in/travelexpo/theme/admin/assets/images/logo.png"/>
        </div>
    </div>
</div>
@else
<div class="row section1"> <div class="col-lg-4 text-left">
        <div class="addressfrom">
            <h3>{{$userData->company_name}}</h3>
         <p>{!! nl2br(e($userData->address)) !!}</p>

               
                Phone no. : {{$userData->mobile_number}}<br>
                Email: {{$userData->company_email}}<br>
                GSTIN: {{$userData->company_reg_no}}<br>
                
            </p>
        </div>
		 <div class="col-lg-5">
    
    </div>
   <div class="col-lg-3 text-right">
    <div class="logo image">
        @if(!empty($userData->company_logo))
            <img class="img-fluid" alt="" src="{{ asset('uploads/agents/' . $userData->company_logo) }}"/>
        @else
            <img class="img-fluid" alt="" src="https://travelxpo.in/travelexpo/theme/admin/assets/images/logo.png"/>
        @endif
    </div>
</div>
    </div></div>
@endif

		<div class="row section2">
            <div class="col-lg-12">
				<h2 class="text-center text-primary">Tax Invoice</h2>
			</div>
        </div>
		<div class="row section3">
            <div class="col-lg-6 text-left">
				<div class="addressto">
					<p>Bill To</p>
					 <p>{{ $invoice->bill_to }}</p>
				</div>
            </div>
			<div class="col-lg-6 text-right">
				<div class="addressto">
					<p>Invoice No. : {{ $invoice->invoice_billno }}</p>
					<p>Date : {{ $invoice->invoice_date }}</p>
				</div>
            </div>
        </div>
		
		<div class="row section">
            <div class="col-lg-12" id="page-container">
			   <table class="table table-bordered">
						<thead style="background-color:#037d99;color:#fff;">
							<tr>
								<th>Item name</th>
								<th>HSN/SAC</th>
								<th>Quantity </th>
								<th>Unit </th>
								<th>Price/Unit</th>
								<th>Taxable amount</th>
								<th>GST Amount</th>
								<th>Amount</th>
							</tr>
						</thead>
						<tbody>
							@if ($invoiceDetail)
                    @foreach ($invoiceDetail as $item)
                    <tr>
                        <td>{{ $item->description }}</td>
                        <td>{{ $item->hsn }}</td>
                        <td>{{ $item->quantity }}</td>
						 <td>{{ $item->unit }}</td>
                        <td>{{ $item->price_unit }}</td>
                        <td>{{ $item->taxable_amt }}</td>
                        <td>{{ $item->gst }}</td>
                        <td>{{ $item->amount }}</td>
                    </tr>
                    @endforeach
                @endif
                <tr>
							
							<tr>
								<th>Total</th>
								<td></td>
								<td></td>
								<td></td>
								<td></td>
								@if ($invoice)
        <th>₹{{ $invoice->total_taxamt }}</th>
        <th>₹{{ $invoice->total_gst }}</th>
        <th>₹{{ $invoice->total_amount }}</th>
    @else
        <th></th>
        <th></th>
        <th></th>
    @endif
							</tr>
						</tbody>
						<tfoot>
							<tr>
								<th colspan="4">
									<h5 class="mt-3">Invoice Amount In Words:</h5> 
									<p style="background-color:#e5e8e8;color:#000;padding:10px 0px;">{{$totalAmountInWords}}</p>
									<h5>Terms and Conditions: </h5>
									<p style="background-color:#e5e8e8;color:#000;padding:10px 0px;">Thanks for doing business with us!</p>
								</th>
								<th colspan="4">
								@if ($invoice)
									<p>Sub Total: <span class="text-right">&#8377;{{ $invoice->total_taxamt }} </span></p>
									<p>SGST @ 9%: <span class="text-right">₹{{ $invoice->sgst }}</span></p>
									<p>CGST @ 9%:<span class="text-right"> ₹{{ $invoice->cgst }}</span></p>
									<p style="background-color:#037d99;color:#fff;padding:10px 0px;">Total: <span class="text-right">₹{{ $invoice->total_amount }}</span></p>
									<p>Received: <span class="text-right">₹{{ $invoice->received_amt }}</span></p>
									<p>Balance: <span class="text-right">₹{{ $invoice->balance_amt }}</span></p>
									    @endif
								</th>
							</tr>
						</tfoot>		
					</table>		
				</div>
            </div>
			<div class="row section1">
				<div class="col-lg-4  text-left">
					<div class="addressfrom">
						<h4>Pay To</h4>
						<p>Bank Name : RBL BANK LTD <br>
						Bank Account No. : 409001662246<br>
						Bank IFSC code : RATN0000328<br>
						Account holder's name : TRAVELXPO</p>
					</div>
				</div>
				<div class="col-lg-3">
				
				</div>
				<div class="col-lg-4  text-right">
					<div class="addressfrom">
						<h4>For, : TRAVELXPO</h4>
						<p>Authorized Signatory</p>
					</div>
				</div>
			</div>
		</div>
</body>
</html>
