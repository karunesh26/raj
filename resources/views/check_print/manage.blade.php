@extends('template.template')

@section('content')
@php
$get_check_detail_url = URL::action($controller_name.'@get_check_detail');
@endphp

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
                <a target="_blank" style="float:right"class="btn bg-orange btn-flat" href="<?php echo $controller_name;?>/add"> <i class="glyphicon glyphicon-plus icon-white"></i>  Print Check</a>
              </div>
            </div>
            @endif
          @else
            <div class="row">
              <div class="col-xs-12">
                <a target="_blank" style="float:right"class="btn bg-orange btn-flat" href="<?php echo $controller_name;?>/add"> <i class="glyphicon glyphicon-plus icon-white"></i>  Print Check</a>
              </div>
            </div>
          @endif

        <div class="row">
            <div class="col-xs-12">

                  <div class="box box-warning">

                    <div class="box-body">

                      <table id="check_print_table" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                          <th>Sr</th>
                          <th>Party Name</th>
                          <th>Date</th>
                          <th>Amount</th>
                          <th>Added By</th>
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
	$("#check_print_table").DataTable({
		processing:true,
		"pageLength":  100,
		"pagingType": "full_numbers",
		"ordering": false,
		"sDom": '<"H"lfrp>t<"F"ip>',
		serverside:true,
		columns:[
		{data:null},
		{data:"party_name"},
		{data:"date"},
		{data:"amount"},
		{data:"added_by"},
		{data:"actions","orderable":false}
		],
		autoWidth:false,
		ajax:"{{ $get_check_detail_url }}"
		}).on( 'order.dt search.dt', function () {
		$("#check_print_table").DataTable().column(0, {search:'applied',order:'applied'}).nodes().each( function (cell, i) {
			cell.innerHTML = i+1;
		} );
	}).draw();
    $(document).on('click', '.confirm-delete', function(e) {
        e.preventDefault();
        var linkURL = $(this).attr("href");
        warnBeforeDelete(linkURL);
    });

    function warnBeforeDelete(linkURL) {
        swal({
            title: "Are You Sure To Delete This Party?",
            text: "",
            type: "warning",
            showCancelButton: true
        }, function() {
            window.location.href = linkURL;
        });
    }

});
</script>
@endsection
