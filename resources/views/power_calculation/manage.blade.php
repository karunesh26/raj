@extends('template.template')

@section('content')
<?php
	$get_power_data_url = URL::action($controller_name.'@get_power_data');
	$generated_power_data_url = URL::action($controller_name.'@generated_power_data');
?>
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


        <div class="row">
            <div class="col-xs-12">
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						 <li class="active"><a href="#new" data-toggle="tab" >Generate Power Calculation</a></li>
						 <li class=""><a id="generate_power_tab" href="#proforma_invoice" data-toggle="tab" > Generated Power Calculation</a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane  active" id="new">
							 <div class="box box-warning">
								<div class="box-body">
									<div id="search_data">
										<table id="power_data" class="table table-bordered table-striped">
											<thead>
												<tr>
													<th>Sr No.</th>
													<th>Quotation No</th>
													<th>Client Name</th>
													<th>Mobile No.</th>
													<th>Email Id</th>
													<th width="20%">Manage</th>
												</tr>
											</thead>
											<tbody>

											</tbody>
										</table>
									</div>
								 </div>
							  </div>
						</div>
						<div class="tab-pane" id="proforma_invoice">
							 <div class="box box-warning">
								<div class="box-body">
									<div id="search_data">
										<table id="generated_power" class="table table-bordered table-striped">
											<thead>
												<tr>
													<th>Sr No.</th>
													<th>Power Calculation No</th>
													<th>Quotation No</th>
													<th>Client Name</th>
													<th>Mobile No.</th>
													<th>Email Id</th>
													<th width="20%">Manage</th>
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
 $(document).ready(function () {

	$("#power_data").DataTable({
		processing:true,
		"pageLength":  100,
		"pagingType": "full_numbers",
		"ordering": false,
		"sDom": '<"H"lfrp>t<"F"ip>',
		serverside:true,
		columns:[
		{data:null},
		{data:"quotation_no"},
		{data:"client_name"},
		{data:"mobile"},
		{data:"email"},
		{data:"actions","orderable":false}
		],
		autoWidth:false,
		ajax:"{{ $get_power_data_url }}"
		}).on( 'order.dt search.dt', function () {
		$("#power_data").DataTable().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
			cell.innerHTML = i+1;
		} );
	}).draw();

	 $('body').on('click','#generate_power_tab',function(){
		if (! $.fn.dataTable.isDataTable( '#generated_power' ) )
		{
			 $("#generated_power").DataTable({
				processing:true,
				"pageLength":  100,
				"pagingType": "full_numbers",
				"ordering": false,
				"sDom": '<"H"lfrp>t<"F"ip>',
				serverside:true,
				columns:[
				{data:null},
				{data:"power_no"},
				{data:"quotation_no"},
				{data:"client_name"},
				{data:"mobile"},
				{data:"email"},
				{data:"actions","orderable":false}
				],
				autoWidth:false,
				ajax:"{{ $generated_power_data_url }}"
				}).on( 'order.dt search.dt', function () {
				$("#generated_power").DataTable().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
					cell.innerHTML = i+1;
				} );
			}).draw();
		}
	 });




	$('.datepicker').datepicker({
      autoclose: true,
	  format: 'dd-mm-yyyy',
    });

	$('.datatable').DataTable({
	  'paging'      : true,
	  'lengthChange': true,
	  'searching'   : true,
	  'ordering'    : true,
	  'info'        : true,
	  'autoWidth'   : true,
	"pageLength":  100,
	})

 });
</script>

@endsection
