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
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						 <li class="active"><a href="#new" data-toggle="tab" >Order Book Detail</a></li>
						 <li class=""><a id="generate_power_tab" href="#cancel_order" data-toggle="tab" > Cancel Order Detail </a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane  active" id="new">
							 <div class="box box-warning">
								<div class="box-body">
									<div id="search_data">
										<table id="power_data" class="table table-bordered table-striped datatable">
											<thead>
												<tr>
													<th>Sr. No.</th>
													<th>Quotation No.</th>
													<th>Client Name</th>
													<th>Mobile No.</th>
													<th>Email Id</th>
													<th>Inquiry For</th>
													<th>Order Book Date</th>
													<th>Order Book By</th>
													<th>Quotation By</th>
													<th width="8%">Manage</th>
												</tr>
											</thead>
											<tbody>
											@php $c = 1; @endphp
											@foreach($result as $k=>$v)
												@if($v->cancel_by != 0)
													@continue;
												@endif
												@php
													$view_fun = 'Follow_up/'.$utility->encode($v->order_inq_id);
													$del_url = $controller_name."/delete/".$utility->encode($v->order_id);
													if($v->quatation_no == '')
													{
														$quot_num = $v->revise_quatation_no;
														$added_quot = $v->revise_user;
													}
													else
													{
														$quot_num = $v->quatation_no;
														$added_quot = $v->quot_user;
													}

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

												@endphp
												<tr>
													<td>{{ $c }}</td>
													<td>{{ $quot_num }}</td>
													<td>{{ $v->name }}</td>
													<td>{{ $mobile }}</td>
													<td>{{ $email }}</td>
													<td>{{ $v->product_name }}</td>
													<td>{{ date('d-m-Y',strtotime($v->order_book_date)) }}</td>
													<td>{{ $v->order_by_user }}</td>
													<td>{{ $added_quot }}</td>
													<td>
														<a href="{{ $view_fun }}" target="_blank"  class="btn bg-olive btn-sm"><i class='glyphicon glyphicon-eye-open icon-white'></i></a>
														<a class='btn bg-maroon btn-flat btn-sm delete-inq' onclick="return confirm('Are You Sure To cancel?')" href="{{ $del_url }}" class='btn btn-warning'><i class='glyphicon glyphicon-trash icon-white'></i></a>
													</td>
												</tr>
												@php $c++; @endphp
											@endforeach
											</tbody>
										</table>
									</div>
								 </div>
							  </div>
						</div>
						<div class="tab-pane" id="cancel_order">
							 <div class="box box-warning">
								<div class="box-body">
									<div id="search_data">
										<table id="generated_power" class="table table-bordered table-striped datatable">
											<thead>
												<tr>
													<th>Sr. No.</th>
													<th>Quotation No.</th>
													<th>Client Name</th>
													<th>Mobile No.</th>
													<th>Email Id</th>
													<th>Inquiry For</th>
													<th>Order Book Date</th>
													<th>Order Book By</th>
													<th>Quotation By</th>
													<th>Order Cancel By</th>
													<th>Manage</th>
												</tr>
											</thead>
											<tbody>
											@php $counter = 1; @endphp
											@foreach($result as $k=>$v)
												@if($v->cancel_by == 0)
													@continue;
												@endif
												@php
													$view_fun = 'Follow_up/'.$utility->encode($v->order_inq_id);
													$active_url = $controller_name."/active/".$utility->encode($v->order_id);
													$add_to_follow_up = $controller_name."/add_to_follow/".$utility->encode($v->order_id);
													if($v->quatation_no == '')
													{
														$quot_num = $v->revise_quatation_no;
														$added_quot = $v->revise_user;
													}
													else
													{
														$quot_num = $v->quatation_no;
														$added_quot = $v->quot_user;
													}

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

												@endphp
												<tr>
													<td>{{ $counter }}</td>
													<td>{{ $quot_num }}</td>
													<td>{{ $v->name }}</td>
													<td>{{ $mobile }}</td>
													<td>{{ $email }}</td>
													<td>{{ $v->product_name }}</td>
													<td>{{ date('d-m-Y',strtotime($v->order_book_date)) }}</td>
													<td>{{ $v->order_by_user }}</td>
													<td>{{ $added_quot }}</td>
													<td>{{ $v->order_cancel_user }}</td>
													<td>
													@if($role_id == 1)
														<a class='btn bg-maroon btn-flat btn-sm delete-inq' onclick="return confirm('Are You Sure To Active This Order?')" href="{{ $active_url }}" class='btn btn-warning'><i class=' 	glyphicon glyphicon-ok icon-white'></i></a>
														<a href="{{ $add_to_follow_up }}" target="_blank"  class="btn bg-olive btn-sm">Add to Follow Up</a>
													@endif
													</td>
												</tr>
												@php $counter++; @endphp
											@endforeach
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
	$('.datepicker').datepicker({
      autoclose: true,
	  format: 'dd-mm-yyyy',
    });
	$('.datatable').DataTable({
		"pageLength":  100,
		"pagingType": "full_numbers",
		"ordering": false,
		"sDom": '<"H"lfrp>t<"F"ip>',
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
