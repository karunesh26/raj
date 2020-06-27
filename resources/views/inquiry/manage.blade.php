@extends('template.template')

@section('content')
@php
$get_inq_url = URL::action($controller_name.'@get_inq');
$get_active_inq_url = URL::action($controller_name.'@get_active_inq');
$get_cancel_inq_url = URL::action($controller_name.'@get_cancel_inq');
$get_delete_inq_url = URL::action($controller_name.'@get_delete_inq');
@endphp
<style>
.dataTables_processing{
	color:blue;
	text-align:center;
	font-size:20px;
}
</style>
 <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1> <?php echo $msgName;?> Details</h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('Dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo $msgName;?></li>
      </ol>
    </section>

    <section class="content">

		<div class="row">
        	 <div class="col-xs-12">
			   @if(session()->has('success'))
                       <span class="7"><div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong>
                        {{ session()->get('success') }}
                   </strong></div></span>
               @endif

                @if(session()->has('error'))
                       <span class="7"><div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong>
                        {{ session()->get('error') }}
                   </strong></div></span>
               @endif
               </div>
         </div>



		 @if($role_id != '1')
            @if($add_permission == 1)
            <div class="row">
              <div class="col-xs-12">
                <a style="float:right"class="btn bg-orange btn-flat" href="<?php echo $controller_name;?>/add"> <i class="glyphicon glyphicon-plus icon-white"></i>  New</a>
              </div>
            </div>
            @endif
          @else
            <div class="row">
              <div class="col-xs-12">
                <a style="float:right"class="btn bg-orange btn-flat" href="<?php echo $controller_name;?>/add"> <i class="glyphicon glyphicon-plus icon-white"></i>  New</a>
              </div>
            </div>
          @endif

        <div class="row">
            <div class="col-xs-12">
				<div class="nav-tabs-custom">
           		 <ul class="nav nav-tabs">
                     <li class="active"><a href="#new_inquiry" data-toggle="tab" >Inquiry</a></li>
                     <li class=""><a id="active_inq_click" href="#active_inquiry" data-toggle="tab" >Active Inquiry</a></li>
                     <li class=""><a id="cancel_inq_click" href="#cancel_inquiry" data-toggle="tab" >Cancel Inquiry</a></li>
                     <li class=""><a id="delete_inq_click" href="#delete_inquiry" data-toggle="tab" >Delete Inquiry</a></li>

                 </ul>
            	<div class="tab-content">
              		<div class="tab-pane  active" id="new_inquiry">
                    	 <div class="box box-warning">

                            <div class="box-body">

                              <div class="table-responsive">
								  <table id="inquiry_all" class="table table-bordered table-striped datatable">
									 <thead>
									<tr>
										<th>Sr</th>
										<th>Inquiry No.</th>
										<th>Inquiry Date.</th>
										<th>Inquiry For</th>
										<th>Customer Name</th>
										<th>Mobile No.</th>
										<th>Email Id</th>
										<th>Inquiry Person</th>
										<th>Manage</th>
									</tr>
								  </thead>

								   <tbody>


									 </tbody>
								  </table>
								  </div>
                             </div>
                          </div>
              		</div>
                    <div class="tab-pane " id="active_inquiry">
                     	 <div class="box box-warning">

                            <div class="box-body">
								<div class="table-responsive">
								  <table id="inquiry_active" class="table table-bordered table-striped datatable">
									 <thead>
									<tr>
										<th>Sr</th>
										<th>Inquiry No.</th>
										<th>Inquiry Date.</th>
										<th>Inquiry For</th>
										<th>Customer Name</th>
										<th>Mobile No.</th>
										<th>Email Id</th>
										<th>Inquiry Person</th>
										<th>Manage</th>
									</tr>
								  </thead>

								   <tbody>

									  </tbody>


								  </table>
								  </div>

                             </div>
                          </div>

              		</div>

					<div class="tab-pane " id="cancel_inquiry">
                     	 <div class="box box-warning">

                            <div class="box-body">

                              <div class="table-responsive">
								  <table id="inquiry_cancel" class="table table-bordered table-striped">
									 <thead>
									<tr>
										<th>Sr</th>
										<th>Inquiry No.</th>
										<th>Inquiry Date.</th>
										<th>Inquiry For</th>
										<th>Customer Name </th>
										<th>Mobile No</th>
										<th>Email Id</th>
										<th>Inquiry Person</th>
										<th>Cancel Date</th>
										<th>Canceled By</th>
										<th>Manage</th>
									</tr>
								  </thead>
								   <tbody>

									  </tbody>


								  </table>
								  </div>
                             </div>
                          </div>
              		</div>
					<div class="tab-pane " id="delete_inquiry">
                     	 <div class="box box-warning">

                            <div class="box-body">

                              <div class="table-responsive">
								  <table id="inquiry_delete" class="table table-bordered table-striped datatable" >
									<thead>
										<tr>
											<th>Sr</th>
											<th>Inquiry No.</th>
											<th>Inquiry Date.</th>
											<th>Inquiry For</th>
											<th>Customer Name</th>
											<th>Mobile No.</th>
											<th>Email Id</th>
											<th>Inquiry Person</th>
											<th>Delete By</th>
											<th>Manage</th>
										</tr>
								  </thead>
								   <tbody>

									  </tbody>
								  </table>
								  </div>
                             </div>
                          </div>
              		</div>
                 </div>
               </div>
             </div>
		 </div>
    </section>
<script>
$(document).ready(function (){

	$.fn.dataTable.ext.errMode = 'none';
	$(document).on('click', '.confirm-delete', function(e) {
        e.preventDefault(); // Prevent the href from redirecting directly
        var linkURL = $(this).attr("href");
        warnBeforeDelete(linkURL);
    });

	$(document).on('click', '.delete-inq', function(e) {
        e.preventDefault(); // Prevent the href from redirecting directly
        var linkURL = $(this).attr("href");
        delete_fun(linkURL);
    });

	$('#inquiry_all').DataTable({
		"processing": true,
		"pageLength":  100,
		"serverSide": true,
		"pagingType": "full_numbers",
		"sDom": '<"H"lfrp>t<"F"ip>',
		"ajax":{
			"url": "{{ $get_inq_url }}",
			"dataType": "json",
			"type": "POST",
			"data":{ _token: "{{csrf_token()}}"}
		},
		"columns": [
			{ "data": "id" },
			{ "data": "inquiry_no" },
			{ "data": "inquiry_date" },
			{ "data": "product_name" },
			{ "data": "name" },
			{ "data": "mobile" },
			{ "data": "email"},
			{ "data": "username" },
			{ "data": "action" },
		],
		"columnDefs": [{
			"targets": [0,7],
			"orderable": false
		}],
	});




	$('body').on('click','#active_inq_click',function(e){
		if (! $.fn.dataTable.isDataTable( '#inquiry_active' ) )
		{

			$('#inquiry_active').DataTable({
				"processing": true,
				"pageLength":  100,
				"serverSide": true,
				"pagingType": "full_numbers",
				"sDom": '<"H"lfrp>t<"F"ip>',
				"ajax":{
					"url": "{{ $get_active_inq_url }}",
					"dataType": "json",
					"type": "POST",
					"data":{ _token: "{{csrf_token()}}"}
				},
				"columns": [
					{ "data": "id" },
					{ "data": "inquiry_no" },
					{ "data": "inquiry_date" },
					{ "data": "product_name" },
					{ "data": "name" },
					{ "data": "mobile" },
					{ "data": "email" },
					{ "data": "username" },
					{ "data": "action" },
				],
				"columnDefs": [{
					"targets": [0,7],
					"orderable": false
				}],
			});

		}
	});


	$('body').on('click','#cancel_inq_click',function(e){
		if (! $.fn.dataTable.isDataTable( '#inquiry_cancel' ) )
		{

			$('#inquiry_cancel').DataTable({
				"processing": true,
				"pageLength":  100,
				"serverSide": true,
				"pagingType": "full_numbers",
				"sDom": '<"H"lfrp>t<"F"ip>',
				"ajax":{
					"url": "{{ $get_cancel_inq_url }}",
					"dataType": "json",
					"type": "POST",
					"data":{ _token: "{{csrf_token()}}"}
				},
				"columns": [
					{ "data": "id" },
					{ "data": "inquiry_no" },
					{ "data": "inquiry_date" },
					{ "data": "product_name" },
					{ "data": "name" },
					{ "data": "mobile" },
					{ "data": "email" },
					{ "data": "username" },
					{ "data": "cancel_date"},
					{ "data": "delete_by"},
					{ "data": "action" },
				],
				"columnDefs": [{
					"targets": [0,9],
					"orderable": false
				}],
			});
		}
	});

	$('body').on('click','#delete_inq_click',function(e){
		if (! $.fn.dataTable.isDataTable( '#inquiry_delete' ) )
		{
			$('#inquiry_delete').DataTable({
				"processing": true,
				"pageLength":  100,
				"serverSide": true,
				"pagingType": "full_numbers",
				"sDom": '<"H"lfrp>t<"F"ip>',
				"ajax":{
					"url": "{{ $get_delete_inq_url }}",
					"dataType": "json",
					"type": "POST",
					"data":{ _token: "{{csrf_token()}}"}
				},
				"columns": [
					{ "data": "id" },
					{ "data": "inquiry_no" },
					{ "data": "inquiry_date" },
					{ "data": "product_name" },
					{ "data": "name" },
					{ "data": "mobile" },
					{ "data": "email"},
					{ "data": "username" },
					{ "data": "delete_by" },
					{ "data": "action" },
				],
				"columnDefs": [{
					"targets": [0,8],
					"orderable": false
				}],
			});
		}
	});

    function warnBeforeDelete(linkURL) {
        swal({
            title: "Are You Sure To Active This Inquiry?",
            text: "",
            type: "warning",
            showCancelButton: true
        }, function() {

            window.location.href = linkURL;
        });
    }
	function delete_fun(linkURL) {
        swal({
            title: "Are You Sure To Delete This Inquiry?",
            text: "",
            type: "warning",
            showCancelButton: true
        }, function() {

            window.location.href = linkURL;
        });
    }
	$(document).ajaxStart(function() {
		$(".blockUI").show();
	});

	$(document).ajaxStop(function() {
		$(".blockUI").hide();
	});
	});
</script>
@endsection
