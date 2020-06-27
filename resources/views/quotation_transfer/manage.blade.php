@extends('template.template')

@section('content')
@php
$get_quotation_all_url = URL::action($controller_name.'@get_quotation_all');
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
         
        <div class="row">
            <div class="col-xs-12">
              
                  <div class="box box-warning">
                  
                    <div class="box-body">
                     	
                      <table id="get_all_quotation" class="table table-bordered table-striped">
                         <thead>
                        <tr>
							<th width="7%" >Sr. No.</th>
                            <th>Quotation No</th>
                            <th>Customer Name</th>
                            <th>Zone Name</th>
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
    </section>
<script>
$(document).ready(function (){
	$("#get_all_quotation").DataTable({
		processing:true,
		"pageLength":  100,
		"pagingType": "full_numbers",
		"ordering": false,
		"sDom": '<"H"lfrp>t<"F"ip>',
		serverside:true,
		columns:[
		{data:null},
		{data:"quotation_no"},
		{data:"customer_name"},
		{data:"zone_name"},
		{data:"actions","orderable":false}
		],
		autoWidth:false,
		ajax:"{{ $get_quotation_all_url }}"
		}).on( 'order.dt search.dt', function () {
		$("#get_all_quotation").DataTable().column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
			cell.innerHTML = i+1;
		} );
	}).draw();
});
</script>
@endsection