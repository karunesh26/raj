@extends('template.template')

@section('content')
<?php 
	$get_cearch_data_url = URL::action($controller_name.'@get_search_data');
?>

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
						<div class="row">
							<div class="col-lg-3">
								<div class="input-group">
									<div class="input-group-addon">
										<img style="height:20px;margin:0;padding:0;" src="{{ URL::asset('external/search_image/search1.png')}}">
									</div>
									<input type="text" name="search" placeholder="Search" id="search" class="form-control">
								</div>
							</div>
							<div class="col-lg-3">
								<div class="input-group">
								  <div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								  </div>
								  <input type="text" name="from_date" id="from_date" placeholder="Order From Date" class="form-control pull-right datepicker" >
								</div>
							</div>
							<div class="col-lg-3">
								<div class="input-group">
								  <div class="input-group-addon">
									<i class="fa fa-calendar"></i>
								  </div>
								  <input type="text" placeholder="Order To Date" name="to_date" id="to_date" class="form-control pull-right datepicker" >
								</div>
							</div>
							<div class="col-lg-3">
								<div class="col-sm-6">
									<a id="search_btn" class="btn btn-success" >Search</a>
								</div>
							</div>
						</div>
						
						<br><div class="box-footer"></div>
						
						<div id="search_data"> 
							<table id="datatable" class="table table-bordered table-striped">
								<thead>
									<tr>
										<th>Sr No.</th>
										<th>Quotation No.</th>
										<th>Client Name</th>
										<th>Mobile No.</th>
										<th>Email Id</th>
										<th>Country</th>
										<th>State</th>
										<th>Inquiry For</th>
										<th>Order Book Date</th>
										<th>Order Book By</th>
										<th width="15%">Manage</th>
									</tr>
								</thead>
								<tbody>
									@foreach($result as $k=>$v)
										@php 
											$mobile = '';
											if($v->mobile != '')
											{
												$mobile.=$v->mobile;
											}
											if($v->mobile_2 != '')
											{
												$mobile.=' / '.$v->mobile_2;
											}	
											if($v->mobile_3 != '')
											{
												$mobile.=' / '.$v->mobile_3;
											}

											$email = '';
											if($v->email != '')
											{
												$email.=$v->email;
											}
											if($v->email_2 != '')
											{
												$email.=' / '.$v->email_2;
											}
											
											
											if($v->quatation_no == '')
											{
												$quot_num = $v->revise_quatation_no;
												$view_fun = 'Quatation/revise_quatation_view/'.$utility->encode($v->revise_id);
											}
											else
											{
												$quot_num = $v->quatation_no;
												$view_fun = 'Quatation/view/'.$utility->encode($v->quatation_id);
											}
											
											
										@endphp
										<tr>
											<td>{{ $k+1 }}</td>
											<td>{{ $quot_num }}</td>
											<td>{{ $v->name }}</td>
											<td>{{ $mobile }}</td>
											<td>{{ $email }}</td>
											<td>{{ $v->country_name }}</td>
											<td>{{ $v->state_name }}</td>
											<td>{{ $v->product_name }}</td>
											<td>{{ date('d-m-Y',strtotime($v->order_book_date)) }}</td>
											<td>{{ $v->order_by_user }}</td>
											<td>
											<a href="{{ 'Job_card/view_job_card/'.$utility->encode($v->order_id)}}" target="_blank" class="btn bg-purple btn-flat btn-sm"><i class="glyphicon glyphicon-eye-open icon-white"></i> View</a>
											
											<a href="{{ $view_fun }}" target="_blank" class="btn bg-olive btn-flat btn-sm"><i class="glyphicon glyphicon-eye-open icon-white"></i> View Quotation</a>
											<a href="{{ 'Job_card/print_pdf/'.$utility->encode($v->order_id).'/'.$utility->encode('print')}}" target="_blank" class="btn btn-danger btn-flat btn-sm"><i class="glyphicon glyphicon-print icon-white"></i> Print</a>
											<a href="{{ 'Job_card/print_pdf/'.$utility->encode($v->order_id).'/'.$utility->encode('download')}}" class="btn btn-info btn-flat btn-sm"><i class="glyphicon glyphicon-download icon-white"></i> Download</a>
											</td>
											
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
                     </div>
                  </div>
             </div>
        </div>
    </section>
<script>
 $(document).ready(function () {
	$('.datepicker').datepicker({
      autoclose: true,
	  format: 'dd-mm-yyyy',
    });
	
	$('body').on('click','#search_btn', function(){
		var search = $('#search').val();
		var from_date = $('#from_date').val();
		var to_date = $('#to_date').val();
		
		if(search == '')
		{
			alert('Please Enter Search Value');
			return false;
		}
		if(from_date != '')
		{
			if(to_date == '')
			{
				alert('Please Select To Date');
				return false;
			}
		}
		
		$('.blockUI').show();
		$.ajax({
			type: "POST",
			url: "<?php echo $get_cearch_data_url;?>",
			data: { "_token": "{{ csrf_token() }}",search:search,from_date:from_date,to_date:to_date},
			success:function(res)
			{
				$('.blockUI').hide();
				$('#search_data').empty().html(res);
			}
		});
	});	
	
	
 });
</script>
	 
@endsection
