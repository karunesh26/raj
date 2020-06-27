<?php
$company_data = DB::table('company')->get();

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>{{ $company_data[0]->name }}</title>
	<link rel="shortcut icon" type="image/png" href="{{ URL::asset('external/site/favicon.png') }}"/>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

 <!-- Bootstrap 3.3.7 -->
    <link href="{{ URL::asset('external/bower_components/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">


     <!-- Font Awesome -->
    <link href="{{ URL::asset('external/bower_components/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">

     <!-- Ionicons -->
    <link href="{{ URL::asset('external/bower_components/Ionicons/css/ionicons.min.css') }}" rel="stylesheet">

     <!-- DataTables -->
       <link href="{{ URL::asset('external/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}" rel="stylesheet" >

     <!-- daterange picker -->

   <link  href="{{ URL::asset('external/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">

   <link  href="{{ URL::asset('external/plugins/timepicker/bootstrap-timepicker.min.css') }}" rel="stylesheet">
  <link  href="{{ URL::asset('external/css/datatable/buttons.dataTables.min.css') }}" rel="stylesheet">
   <link  href="{{ URL::asset('external/css/datatable/jquery.dataTables.min.css') }}" rel="stylesheet">

    <!-- Select2 -->
  <!--<link rel="stylesheet" href="{{ URL::asset('external/bower_components/select2/dist/css/select2.min.css') }}">-->
  <link rel="stylesheet" href="{{ URL::asset('external/select2/select2.css') }}">
       <!-- Theme style -->
     <link href="{{ URL::asset('external/dist/css/AdminLTE.min.css') }}" rel="stylesheet">

    <!-- AdminLTE Skins. -->
    <link href="{{ URL::asset('external/dist/css/skins/_all-skins.min.css') }}" rel="stylesheet">

	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

     <!-- jQuery -->
    <script src="{{ URL::asset('external/bower_components/jquery/dist/jquery.min.js') }}"></script>
    <!--Validation -->
    <script src="{{ URL::asset('external/js/jquery.validate.min.js') }}"></script>




    <style>
	 	.error
		{
			color:#F00;
		}
		.required
		{
			color:#F00;
		}
		.scrollToTop{
			width:70px;
			height:60px;
			padding:10px;
			text-align:center;
			font-weight: bold;
			color: #444;
			text-decoration: none;
			position:fixed;
			display:none;
			top:85%;
			right:10px;
			cursor:pointer;
		}
		.scrollToTop:hover{
			text-decoration:none;
		}
		.dataTables_processing{
			margin-top:2px !important;
			padding-top:5px !important;
			background:#e2e2e2 !important;
			color:#3c8dbc !important;
		}

	 </style>

</head>
<body class="hold-transition skin-blue layout-top-nav fixed">
<div class="wrapper">

  <header class="main-header">
    <nav class="navbar navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <a href="{{ URL::to('/') }}" class="navbar-brand"><?php //echo $company_data[0]->name;?></a>
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
            <i class="fa fa-bars"></i>
          </button>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a class="dashboard_check" href="{{ URL::to('Dashboard') }}"><span>Dashboard</span> </a></li>
				@php
					$company_master = App\Models\Data_model::get_menu('company_master');
					$marketing_master = App\Models\Data_model::get_menu('marketing_master');
					$sales_master = App\Models\Data_model::get_menu('sales_master');
					$marketing = App\Models\Data_model::get_menu('marketing');
					$sales = App\Models\Data_model::get_menu('sales');
					$reports = App\Models\Data_model::get_menu('report');
				@endphp
				<!-- company master menu start -->
				@if( ! empty($company_master))
				<li class="dropdown ">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">Company Master<span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
					@foreach($company_master as $key=>$val)
						<li><a href="{{ URL::to($val->controller_name) }}"><span>{{ $val->display_name }}</span> </a></li>
					@endforeach
					</ul>
				</li>
				@endif
				<!-- company master menu end -->

				<!-- marketing master menu start -->
				@if( ! empty($marketing_master))
				<li class="dropdown ">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">Marketing Master<span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
					@foreach($marketing_master as $key=>$val)
						<li><a href="{{ URL::to($val->controller_name) }}"><span>{{ $val->display_name }}</span> </a></li>
					@endforeach
					</ul>
				</li>
				@endif
				<!-- marketing master menu end-->

				<!-- sales master menu start -->
				@if( ! empty($sales_master))
				<li class="dropdown ">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">Sales Master<span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
					@foreach($sales_master as $key=>$val)
						<li><a href="{{ URL::to($val->controller_name) }}"><span>{{ $val->display_name }}</span> </a></li>
					@endforeach
					</ul>
				</li>
				@endif
				<!-- sales Master menu end-->

				<!-- marketing menu start -->
				@if( ! empty($marketing))
				<li class="dropdown ">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true"> Marketing<span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
					@foreach($marketing as $key=>$val)
						<li><a href="{{ URL::to($val->controller_name) }}"><span>{{ $val->display_name }}</span> </a></li>
					@endforeach
					</ul>
				</li>
				@endif
				<!-- marketing menu end-->

				<!-- sales menu start -->
				@if( ! empty($sales))
				<li class="dropdown ">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">Sales<span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
					@foreach($sales as $key=>$val)
						<li><a href="{{ URL::to($val->controller_name) }}"><span>{{ $val->display_name }}</span> </a></li>
					@endforeach
					</ul>
				</li>
				@endif
				<!-- sales menu end-->

				<!-- report menu start -->
				@if( ! empty($reports))
				<li class="dropdown ">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">Reports<span class="caret"></span></a>
					<ul class="dropdown-menu" role="menu">
					@foreach($reports as $key=>$val)
						<li><a href="{{ URL::to($val->controller_name) }}"><span>{{ $val->display_name }}</span> </a></li>
					@endforeach
					</ul>
				</li>
				@endif
				<!-- report menu end -->
          </ul>

        </div>
        <!-- /.navbar-collapse -->
        <!-- Navbar Right Menu -->
       <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">
           <li class="dropdown user user-menu">
            <a href="{{ URL::to('Change_password') }}" >Change Password</a>
          </li>

            <li class="dropdown user user-menu">
          <a href="{{ URL::to('logout') }}" >Sign out</a>
          </li>
          </ul>
        </div>
        <!-- /.navbar-custom-menu -->
      </div>
      <!-- /.container-fluid -->
    </nav>
  </header>
  <!-- =============================================== -->



  <!-- =============================================== -->
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->


    <!-- Main content -->
    <div class="container" style="width:100% !important;">
	<div class="blockUI" style=""></div>
	<div class="blockUI blockOverlay" style="z-index: 1000; border: medium none; margin: 0px; padding: 0px; width: 100%; height: 100%; top: 0px; left: 0px; background-color: rgb(0, 0, 0); opacity: 0.6; cursor: wait; position: fixed;"></div>
	<div class="blockUI blockMsg blockPage" style="z-index: 1011; position: fixed; padding: 15px; margin: 0px; width: 30%; top: 40%; left: 35%; text-align: center; color: rgb(255, 255, 255); border: medium none;  cursor: wait; opacity: 0.5;">
		<img alt="loading.." src="{{ URL::asset('external/gif/6.gif')}}">
	</div>
       @yield('content')
    </div>
	<a class="scrollToTop"><img class="scrollToTop" src="{{ URL::asset('external/arrow_up.png') }}"/></a>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
         <div class="pull-right hidden-xs">
              <b> Developed By </b> <a target="_blank" href="http://angelspearlinfotech.com/">Angel's Pearl Infotech</a>
            </div>
            <strong>© <?php echo date("Y"); ?> All Rights Reserved.</strong>
   </footer>

  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->


    <script src="{{ URL::asset('external/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>

    <!-- DataTables -->
	<script src="{{ URL::asset('external/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ URL::asset('external/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>

    <!-- SlimScroll -->
    <script src="{{ URL::asset('external/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>

   <!-- FastClick -->
    <script src="{{ URL::asset('external/bower_components/fastclick/lib/fastclick.js') }}"></script>

    <!-- AdminLTE App -->
    <script src="{{ URL::asset('external/dist/js/adminlte.min.js') }}"></script>

    <!-- AdminLTE for demo purposes -->
    <script src="{{ URL::asset('external/dist/js/demo.js') }}"></script>

	<!-- date-time-picker -->
	<script src="{{ URL::asset('external/bower_components/moment/min/moment.min.js') }}"></script>
	<script src="{{ URL::asset('external/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('external/plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>

    <!-- Select2 -->
	<!--<script src="{{ URL::asset('external/bower_components/select2/dist/js/select2.full.min.js') }}"></script>-->

  <script src="{{ URL::asset('external/select2/select2.js') }}"></script>

	<!-- Underscore -->
	<script src="{{ URL::asset('external/js/underscore-min.js') }}"></script>

	<!-- common js -->
	<script src="{{ URL::asset('external/js/common.js') }}"></script>

	<!-- CKEditior js -->
	<script src="{{ URL::asset('external/ckeditor/ckeditor.js') }}"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>

    <script>
    function select2Focus()
    {
      var select2 = $(this).data('select2');
      setTimeout(function() {
          if (!select2.opened()) {
              select2.open();
          }
      }, 0);
    }
    $(document).ready(function () {

		$(window).scroll(function(){
			if ($(this).scrollTop() > 100) {
				$('.scrollToTop').fadeIn();
			} else {
				$('.scrollToTop').fadeOut();
			}
		});

		//Click event to scroll to top
		$('.scrollToTop').click(function(){
			$('html, body').animate({scrollTop : 0},800);
			return false;
		});

   		$('.sidebar-menu').tree();
		 $('#datatable').DataTable({
			  'paging'      : true,
			  'lengthChange': true,
			  'searching'   : true,
			  'ordering'    : true,
			  'info'        : true,
			  'autoWidth'   : true,
         "pageLength":  100,
			"pagingType": "full_numbers",
			"ordering": false,
			"sDom": '<"H"lfrp>t<"F"ip>',
			});
      	$('.blockUI').hide();

  /*$('select').select2({placeholder: "Select"});*/

    $('.select2').select2({placeholder: "Select"}).one('select2-focus', select2Focus).on("select2-blur", function () {
      $(this).one('select2-focus', select2Focus)
	})
	$('.timepicker').timepicker({
		showInputs: false
    });

 });

    </script>


  </body>
</html>
