<!doctype html>
<html lang="en">
<head>
        
        <meta charset="utf-8" />
        <title>Dashboard | Agent</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
         <meta name="csrf-token" content="{{ csrf_token() }}" />
        <!-- App favicon -->
        <link rel="shortcut icon" href="{{asset('theme/admin/assets/images/favicon.png')}}">

        <!-- jquery.vectormap css -->
        <link href="{{asset('theme/admin/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css')}}" rel="stylesheet" type="text/css" />
	    <link href="{{asset('theme/admin/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('theme/admin/assets/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css')}}" rel="stylesheet">
        <link href="{{asset('theme/admin/assets/libs/spectrum-colorpicker2/spectrum.min.css" rel="stylesheet')}}" type="text/css">
        <link href="{{asset('theme/admin/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.css')}}" rel="stylesheet">
        <!-- DataTables -->
        <!-- DataTables -->
        <link href="{{asset('theme/admin/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('theme/admin/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('theme/admin/assets/libs/datatables.net-select-bs4/css/select.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />

        <!-- Bootstrap Css -->
        <link href="{{asset('theme/admin/assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />
        <!-- Icons Css -->
		<link rel="stylesheet" href="{{asset('theme/admin/assets/libs/twitter-bootstrap-wizard/prettify.css')}}">

        <!-- select2 css -->
        <link href="{{asset('theme/admin/assets/libs/select2/css/select2.min.css')}}" rel="stylesheet" type="text/css" />

        <!-- dropzone css -->
        <link href="{{asset('theme/admin/assets/libs/dropzone/min/dropzone.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('theme/admin/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
        <link href="{{asset('theme/admin/assets/css/font-awesome.css')}}" rel="stylesheet" type="text/css" />
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- App Css-->
        <link href="{{asset('theme/admin/assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />
<style>
.table>:not(caption)>*>* {
    padding: 6px;
    background-color: var(--bs-table-bg);
    border-bottom-width: 1px;
    -webkit-box-shadow: inset 0 0 0 9999px var(--bs-table-accent-bg);
    box-shadow: inset 0 0 0 9999px var(--bs-table-accent-bg);
    font-size: 14px;
    text-align: left;
    color: #000;
}
.table>thead th{
    vertical-align: bottom;
    color: #fff !important;
	background: #453185f7 !important;
}
.editProfileForm input, .editProfileForm label {
    display: block;
    color: #000000;
    font-size: 14px;
}


</style>
</head>



    <body data-sidebar="dark">
    
    <!-- <body data-layout="horizontal" data-topbar="dark"> -->

        <!-- Begin page -->
        <div id="layout-wrapper">

            
            <header id="page-topbar">
                <div class="navbar-header">
                    <div class="d-flex">
                        <!-- LOGO -->
                        <div class="navbar-brand-box">
                           <a href="index.html" class="logo logo-dark">
                                <span class="logo-sm">
                                    <img src="{{asset('theme/admin/assets/images/logo.png')}}" alt="logo-sm-dark" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{asset('theme/admin/assets/images/logo.png')}}" alt="logo-dark" height="20">
                                </span>
                            </a>

                            <a href="index.html" class="logo logo-light">
                                <span class="logo-sm">
                                    <img src="{{asset('theme/admin/assets/images/logo.png')}}" alt="logo-sm-light" height="22">
                                </span>
                                <span class="logo-lg">
                                    <img src="{{asset('theme/admin/assets/images/logo.png')}}" alt="logo-light" height="70">
                                </span>
                            </a>
                        </div>

                        <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
                            <i class="ri-menu-2-line align-middle"></i>
                        </button>
					<!--	<div class="dropdown dropdown-mega d-none d-lg-block ms-2">
                            <button type="button" class="btn header-item waves-effect" data-bs-toggle="dropdown" aria-haspopup="false" aria-expanded="false">Flights</button>
							<button type="button" class="btn header-item waves-effect" data-bs-toggle="dropdown" aria-haspopup="false" aria-expanded="false">Hotels</button>
							<button type="button" class="btn header-item waves-effect" data-bs-toggle="dropdown" aria-haspopup="false" aria-expanded="false">Transfers</button>
							<button type="button" class="btn header-item waves-effect" data-bs-toggle="dropdown" aria-haspopup="false" aria-expanded="false">Activities</button>
                        </div>-->
                        </div>

                    <div class="d-flex">

                        <div class="dropdown d-inline-block user-dropdown ">
							<div class="userName">
                            <span class="userTxt" id="myaccnt_btn_name">
                                <span class="avlCreditTxt">Logged in as :</span>
                                <span class="login_Txt"><b>{{Auth()->user()->name}}</b></span>
                              </span>
                               <div class="avlCreditTxt">
                                <?php
$user = Auth::user();
$email = $user->email;
$agentid = $user->agentid;
$agenttype = $user->agent_type;

// Retrieve the branch ID based on user's agent type and ID
$branchId = DB::table('branch')
    ->where('user_type', $agenttype)
    ->where('user_id', $agentid)
    ->value('id');

// Retrieve the available balance for the branch
$availablebal = DB::table('cash_balance')
    ->where('branch_id', '=', $branchId)
    ->orderBy('id', 'desc')
    ->value('balance');
?>

<span class="avalpointsTxt">
    <span class="avlCreditTxt">Available Balance :</span>
    <span class="currencyTxt">
        @if(isset($availablebal))
            {{ $availablebal }}
        @else
            0
        @endif
    </span>
</span><BR>
<span class="avalpointsTxt">
    <span class="avlCreditTxt">Available Points:</span>
    <span class="currencyTxt"> 0
    </span>
</span><br>
<span class="avalpointsTxt">
    <span class="avlCreditTxt">Loyality Tier :</span>
    <span class="currencyTxt">
       
            0
       
    </span>
</span>

                                </span>
                              </div>
							</div>
						</div>

                        <div class="dropdown d-inline-block user-dropdown">
                            <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                              <span class="d-none d-xl-inline-block ms-1">Agent</span>
                                <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                            </button>
                            <div class="dropdown-menu dropdown-menu-end">
                                <!-- item
                                <a class="dropdown-item" href="#"><i class="ri-file-list-3-line align-middle me-1"></i> My Bookings</a>
                                <a class="dropdown-item" href="#"><i class="ri-user-line align-middle me-1"></i> My Account</a>
                                <a class="dropdown-item" href="#"><i class="ri-plane-line align-middle me-1"></i>Travellers</a>
                                <a class="dropdown-item" href="#"><i class="ri-chat-quote-line align-middle me-1"></i>Quotation</a>
								<a class="dropdown-item" href="#"><i class="mdi mdi-handshake align-middle me-1"></i> Loyalty</a>
                                <div class="dropdown-divider"></div>-->
                                <a class="dropdown-item text-danger" href="{{ route('agent.logout') }}"><i class="ri-shut-down-line align-middle me-1 text-danger"></i> Logout</a>
                                
                            </div>
                        </div>

            
                    </div>
                </div>
            </header>

            <!-- ========== Left Sidebar Start ========== -->
            <div class="vertical-menu">

                <div data-simplebar class="h-100">

                    <!--- Sidemenu -->
                    <div id="sidebar-menu">
                        <!-- Left Menu Start -->
                        <ul class="metismenu list-unstyled" id="side-menu">
                            <li class="menu-title">Menu</li>

                            <li>
                                <a href="{{route('agent.dashboard')}}" class="waves-effect">
                                    <i class="ri-dashboard-line"></i>
                                    <span>Dashboard</span>
                                </a>
                            </li>

                           <li>
                                <a href="{{route('agent.adminProfile')}}" class=" waves-effect">
                                    <i class="ri-calendar-2-line"></i>
                                    <span>Edit Profile</span>
                                </a>
                            </li>
<li>
								<a href="{{route('agent.setMarkup')}}" class=" waves-effect">
									<i class="ri-chat-1-line"></i>
									<span>Set Markup</span>
								</a>
							</li>
							
							<li>
                                <a href="{{route('agent.addSubAgent')}}" class=" waves-effect">
                                    <i class="ri-artboard-2-line"></i>
                                    <span>Manage Sub Agent</span>
                                </a>
                            </li>
						<li>
                                <a href="{{route('agent.subAgentList')}}" class=" waves-effect">
                                    <i class="ri-artboard-2-line"></i>
                                    <span>Sub Agent List</span>
                                </a>
                            </li>
							<li>
                                <a href="{{route('agent.changeAdminPassword')}}" class=" waves-effect">
                                    <i class="ri-store-2-line"></i>
                                    <span>Change Password</span>
                                </a>
                            </li>
							<li>
                                <a href="{{route('agent.makePayment')}}" class=" waves-effect">
                                    <i class="ri-artboard-2-line"></i>
                                    <span>Supplier Payment</span>
                                </a>
                            </li>
								<li>
                                <a href="{{route('agent.approveList')}}" class=" waves-effect">
                                    <i class="ri-artboard-2-line"></i>
                                    <span>Payment Approving list</span>
                                </a>
                            </li>

				<li>
                                <a href="{{route('customer.addCustomer')}}" class=" waves-effect">
                                    <i class="ri-artboard-2-line"></i>
                                    <span>Add Customer</span>
                                </a>
                            </li>

				<li>
                                <a href="{{route('customer.customersList')}}" class=" waves-effect">
                                    <i class="ri-artboard-2-line"></i>
                                    <span>Customer list</span>
                                </a>
                            </li>
 
	<li>
                                <a href="{{route('agent.operationStaff')}}" class=" waves-effect">
                                    <i class="ri-artboard-2-line"></i>
                                    <span>Manage Operation Staff</span>
                                </a>
                            </li>
						
                         <li>
                                <a href="{{route('agent.dailySalesReport')}}" class=" waves-effect">
                                    <i class="ri-artboard-2-line"></i>
                                    <span> Daily Sales Report</span>
                                </a>
                            </li>  
  <li>
                                <a href="{{route('agent.bookingReport')}}" class=" waves-effect">
                                    <i class="ri-artboard-2-line"></i>
                                    <span> Booking Report</span>
                                </a>
                            </li>  

                        <li>
                                <a href="{{route('agent.agent_recieptReport')}}" class=" waves-effect">
                                    <i class="ri-artboard-2-line"></i>
                                    <span> Agent Receipt Report</span>
                                </a>
                            </li>
							<li>
                                <a href="{{route('agent.Cancel')}}" class=" waves-effect">
                                    <i class="ri-artboard-2-line"></i>
                                    <span> Ticket Cancel</span>
                                </a>
                            </li>
                    </div>
                    <!-- Sidebar -->
                </div>
            </div>
            <!-- Left Sidebar End -->

            

            <!-- ============================================================== -->
            <!-- Start right Content here -->
            <!-- ============================================================== -->
@yield('content')
            <!-- end main content-->
  <footer class="footer">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-6">
                                
                            </div>
                            <div class="col-sm-6">
                                <div class="text-sm-end d-none d-sm-block">
                                    <script>document.write(new Date().getFullYear())</script> © Travexpo.
                                </div>
                            </div>
                        </div>
                    </div>
                </footer>
                
            </div>
            </div>
            </div>
            </div>
            </div>
        </div>
        <!-- END layout-wrapper -->



        <!-- Right bar overlay-->
        <div class="rightbar-overlay"></div>

         <!-- JAVASCRIPT -->
        <script src="{{asset('theme/admin/assets/libs/jquery/jquery.min.js')}}"></script>
        <script src="{{asset('theme/admin/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{asset('theme/admin/assets/libs/metismenu/metisMenu.min.js')}}"></script>
        <script src="{{asset('theme/admin/assets/libs/simplebar/simplebar.min.js')}}"></script>
        <script src="{{asset('theme/admin/assets/libs/node-waves/waves.min.js')}}"></script>

         <script src="{{asset('theme/admin/assets/libs/select2/js/select2.min.js')}}"></script>
        <script src="{{asset('theme/admin/assets/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
        <script src="{{asset('theme/admin/assets/libs/spectrum-colorpicker2/spectrum.min.js')}}"></script>
        <script src="{{asset('theme/admin/assets/libs/bootstrap-touchspin/jquery.bootstrap-touchspin.min.js')}}"></script>
        <script src="{{asset('theme/admin/assets/libs/admin-resources/bootstrap-filestyle/bootstrap-filestyle.min.js')}}"></script>
        <script src="{{asset('theme/admin/assets/libs/bootstrap-maxlength/bootstrap-maxlength.min.js')}}"></script>
        <!-- apexcharts -->
        <script src="{{asset('theme/admin/assets/libs/apexcharts/apexcharts.min.js')}}"></script>

        <!-- jquery.vectormap map -->
        <script src="{{asset('theme/admin/assets/libs/admin-resources/jquery.vectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>
        <script src="{{asset('theme/admin/assets/libs/admin-resources/jquery.vectormap/maps/jquery-jvectormap-us-merc-en.js')}}"></script>

        <!-- Required datatable js -->
       <script src="{{asset('theme/admin/assets/libs/datatables.net/js/jquery.dataTables.min.js')}}"></script>
        <script src="{{asset('theme/admin/assets/libs/datatables.net-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
        <!-- Buttons examples -->
        <script src="{{asset('theme/admin/assets/libs/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
        <script src="{{asset('theme/admin/assets/libs/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js')}}"></script>
        <script src="{{asset('theme/admin/assets/libs/jszip/jszip.min.js')}}"></script>
        <script src="{{asset('theme/admin/assets/libs/pdfmake/build/pdfmake.min.js')}}"></script>
        <script src="{{asset('theme/admin/assets/libs/pdfmake/build/vfs_fonts.js')}}"></script>
        <script src="{{asset('theme/admin/assets/libs/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
        <script src="{{asset('theme/admin/assets/libs/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
        <script src="{{asset('theme/admin/assets/libs/datatables.net-buttons/js/buttons.colVis.min.js')}}"></script>

        <script src="{{asset('theme/admin/assets/libs/datatables.net-keytable/js/dataTables.keyTable.min.js')}}"></script>
        <script src="{{asset('theme/admin/assets/libs/datatables.net-select/js/dataTables.select.min.js')}}"></script>
        
        <!-- Responsive examples -->
        <script src="{{asset('theme/admin/assets/libs/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
        <script src="{{asset('theme/admin/assets/libs/datatables.net-responsive-bs4/js/responsive.bootstrap4.min.js')}}"></script>

        <!-- Datatable init js -->
        <script src="{{asset('theme/admin/assets/js/pages/datatables.init.js')}}"></script>

        <!-- twitter-bootstrap-wizard js -->
        <script src="{{asset('theme/admin/assets/libs/twitter-bootstrap-wizard/jquery.bootstrap.wizard.min.js')}}"></script>

        <script src="{{asset('theme/admin/assets/libs/twitter-bootstrap-wizard/prettify.js')}}"></script>

        <!-- select 2 plugin -->
        <script src="{{asset('theme/admin/assets/libs/select2/js/select2.min.js')}}"></script>

        <!-- dropzone plugin -->
        <script src="{{asset('theme/admin/assets/libs/dropzone/min/dropzone.min.js')}}"></script>

        <!-- init js -->
        <script src="{{asset('theme/admin/assets/js/pages/ecommerce-add-product.init.js')}}"></script>

        <!-- App js -->
        <script src="{{asset('theme/admin/assets/js/app.js')}}"></script>
		
    </body>


<!-- Mirrored from themesdesign.in/nazox-react/layouts/ by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 25 Feb 2023 11:14:19 GMT -->
</html>

<style>
.submitebtn {
    padding: 10px 25px;
    display: inline-block;
    align-items: center;
    justify-content: center;
    text-align: center;
    font-size: 14px;
    background: #453185;
    color: #fff;
    text-decoration: none;
    cursor: pointer;
    transition: 0.3s;
    border-radius: 20px;
    border-radius: 25px;
}
.submitebtn:hover{
    padding: 10px 25px;
    display: inline-block;
    align-items: center;
    justify-content: center;
    text-align: center;
    font-size: 14px;
    background:#160a3c;
    color: #fff;
    text-decoration: none;
    cursor: pointer;
    transition: 0.3s;
    border-radius: 20px;
    border-radius: 25px;
}
a, button {
    outline: 0!important;
}
</style>