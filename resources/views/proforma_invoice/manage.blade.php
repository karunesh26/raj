@extends('template.template')

@section('content')
<?php
	$get_cearch_data_url = URL::action($controller_name.'@get_search_data');
	$get_customer_mobile = URL::action($controller_name.'@get_customer_mobile');
	$send_sms_url = URL::action($controller_name.'@send_sms');
	$get_quotation_invoice_url = URL::action($controller_name.'@get_quotation_invoice');
	$get_rev_quotation_invoice_url = URL::action($controller_name.'@get_rev_quotation_invoice');

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

						 <li class="active"><a id="quptation_tab_click" href="#quotation" data-toggle="tab" > Quotation</a></li>
						 <li class=""><a id="revise_quotation_tab_click" href="#revise_quotation" data-toggle="tab" > Revise Quotation</a></li>
						 <li class=""><a href="#new" data-toggle="tab" >Order Book</a></li>
						 <li class=""><a href="#proforma_invoice" data-toggle="tab" > Proforma Invoice</a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane" id="new">
							 <div class="box box-warning">
								<div class="box-body">
									<div id="search_data" class="table-responsive">
										<table id="" class="table table-bordered table-striped datatable">
											<thead>
												<tr>
													<th>Sr No.</th>
													<th>Quotation No</th>
													<th>Client Name</th>
													<th>Mobile No.</th>
													<th>Email Id</th>
													<th>Country</th>
													<th>State</th>
													<th>Inquiry For</th>
													<th>Order Book Date</th>
													<th>Order Book By</th>
													<th>Quotation By</th>
													<th width="20%">Manage</th>
												</tr>
											</thead>
											<tbody>
												@php $t=1; @endphp
												@foreach($result as $k=>$v)
													@php
														if($v->quatation_no == '')
														{
															$quot_num = $v->revise_quatation_no;
															$added_quot = $v->revise_user;
															$view_fun = 'Quatation/revise_quatation_view/'.$utility->encode($v->revise_id);
														}
														else
														{
															$quot_num = $v->quatation_no;
															$added_quot = $v->quot_user;
															$view_fun = 'Quatation/view/'.$utility->encode($v->quatation_id);
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
														if($result[0]->mobile_3 != '')
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
														<td>

														{{ $t }}</td>
														<td>{{ $quot_num }}</td>
														<td>{{ $v->name }}</td>
														<td>{{ $mobile }}</td>
														<td>{{ $email }}</td>
														<td>{{ $v->country_name }}</td>
														<td>{{ $v->state_name }}</td>
														<td>{{ $v->product_name }}</td>
														<td>{{ date('d-m-Y',strtotime($v->order_book_date)) }}</td>
														<td>{{ $v->order_user }}</td>
														<td>{{ $added_quot }}</td>
														<td>
															<a href="{{ $view_fun }}" target="_blank" class="btn bg-olive btn-flat btn-sm"><i class="glyphicon glyphicon-eye-open icon-white"></i> View</a>

															<a href="{{ 'Proforma_Invoice/generate_invoice/order_book/'.$utility->encode($v->order_id) }}"  class="btn bg-purple btn-flat btn-sm"><i class="glyphicon glyphicon-upload icon-white"></i> Generate</a>

														</td>

													</tr>
													@php $t++; @endphp
												@endforeach
											</tbody>
										</table>
									</div>
								 </div>
							  </div>
						</div>

						<div class="tab-pane  active" id="quotation">
							 <div class="box box-warning">
								<div class="box-body">
									<div id="search_data" class="table-responsive">
										<table id="quotationTable" class="table table-bordered table-striped">
											<thead>
												<tr>
													<th>Sr No.</th>
													<th>Quotation No</th>
													<th>Client Name</th>
													<th>Mobile No.</th>
													<th>Email Id</th>
													<th>Country</th>
													<th>State</th>
													<th>Inquiry For</th>
													<th>Quotation By</th>
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

						<div class="tab-pane" id="revise_quotation">
							 <div class="box box-warning">
								<div class="box-body">
									<div id="search_data" class="table-responsive">
										<table id="reviseQuotationTable" class="table table-bordered table-striped">
											<thead>
												<tr>
													<th>Sr No.</th>
													<th>Quotation No</th>
													<th>Client Name</th>
													<th>Mobile No.</th>
													<th>Email Id</th>
													<th>Country</th>
													<th>State</th>
													<th>Inquiry For</th>
													<th>Quotation By</th>
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
									<div id="search_data" class="table-responsive">
										<table id="" class="table table-bordered table-striped datatable">
											<thead>
												<tr>
													<th>Sr No.</th>
													<th>Invoice No.</th>
													<th>Quotation No</th>
													<th>Client Name</th>
													<th>Mobile No.</th>
													<th>Email Id</th>
													<th>Country</th>
													<th>State</th>
													<th>Inquiry For</th>
													<th>Order Book Date</th>
													<th>Order Book By</th>
													<th width="20%">Manage</th>
												</tr>
											</thead>
											<tbody>
												@php $h=1; @endphp
												@foreach($proforma_invoice as $k=>$v)
													@php
														if($v->proforma_order_id == 0){
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
														} else {
															if($v->quotNum == '')
															{
																$quot_num = $v->rquotNum;
																$view_fun = 'Quatation/revise_quatation_view/'.$utility->encode($v->rquotId);
															}
															else
															{
																$quot_num = $v->quotNum;
																$view_fun = 'Quatation/view/'.$utility->encode($v->quotId);
															}
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
														if($result[0]->mobile_3 != '')
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
														<td>{{ $h }}
														<input type="hidden" id="proforma_id" name="proforma_id" value="{{ $v->proforma_id }}" />
														<input type="hidden" id="inquiry_id" name="inquiry_id" value="{{ $v->inquiry_id }}" />
														</td>
														<td>{{ $v->invoice_number }}</td>
														<td>{{ $quot_num }}</td>
														<td>{{ $v->name }}</td>
														<td>{{ $mobile }}</td>
														<td>{{ $email }}</td>
														<td>{{ $v->country_name }}</td>
														<td>{{ $v->state_name }}</td>
														<td>{{ $v->product_name }}</td>
														<td>{{ ($v->order_book_date != '') ? date('d-m-Y',strtotime($v->order_book_date)) : '' }}</td>
														<td>{{ $v->order_user }}</td>
														<td>
															<a href="{{ $view_fun }}" target="_blank" class="btn bg-olive btn-flat btn-sm"><i class="glyphicon glyphicon-eye-open icon-white"></i> View</a>

															<a href="{{ 'Proforma_Invoice/print_pdf/'.$utility->encode($v->invoice_number).'/'.$utility->encode('print') }}" target="_blank" class="btn btn-danger btn-flat btn-sm"><i class="glyphicon glyphicon-print icon-white"></i> Print</a>

															<a href="{{ 'Proforma_Invoice/print_pdf/'.$utility->encode($v->invoice_number).'/'.$utility->encode('download')}}"  class="btn btn-info btn-flat btn-sm"><i class="glyphicon glyphicon-download icon-white"></i> Download</a>

															<a id='send_sms_res' data-toggle='modal'  data-target='#send_sms_model' class='btn bg-green btn-flat btn-sm' ><i class='glyphicon glyphicon-envelope icon-white'></i> Send SMS</a>

														</td>

													</tr>
													@php $h++; @endphp
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
	<div class="modal fade" id="send_sms_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<button type="button" class="close"
				   data-dismiss="modal">
					   <span aria-hidden="true">&times;</span>
					   <span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">
					Send SMS
				</h4>
			</div>
			<!-- Modal Body -->
			<div class="modal-body">
				 <div class="tab-pane ">
				{!! Form::open(array('method' => 'post' , 'files' => true ,'id'=>"send_sms_form",'name'=>"send_sms_form",'class'=>"send_sms_form"))!!}
				{{ csrf_field() }}
				<input type="hidden" name="proforma_id" id="proforma_id" />
				 <div class="box-body">
					<div class="form-group col-sm-12">
						  <label class=" col-sm-3 control-label" >Mobile No.<span class="required">*</span></label>
						  <div class="col-sm-9">
							<select multiple name="customer_mno[]" id="customer_mno_sms" class="select2" style="width:100%;">
								<option value="">Select</option>

							</select>
						  </div>
					</div>

				  <div class="form-group col-sm-12">
					<div class="col-sm-offset-3 col-sm-9">
					  <button type="submit" class="btn btn-default" id="product">Send</button>
					  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				  </div>
				 </div>
				{!!Form::close()!!}
				</div>
			</div>
		</div>
	</div>
</div>
<script>
 $(document).ready(function () {
	$('.datepicker').datepicker({
      autoclose: true,
	  format: 'dd-mm-yyyy',
	});

			$('#quotationTable').DataTable({
				"processing": true,
				"pageLength":  100,
				"serverSide": true,
				"pagingType": "full_numbers",
				"sDom": '<"H"lfrp>t<"F"ip>',
				"ajax":{
					"url": "{{ $get_quotation_invoice_url }}",
					"dataType": "json",
					"type": "POST",
					"data":{ _token: "{{csrf_token()}}"}
				},
				"columns": [
					{ "data": "id" },
					{ "data": "quatation_no" },
					{ "data": "name" },
					{ "data": "mobile" },
					{ "data": "email" },
					{ "data": "country" },
					{ "data": "state"},
					{ "data": "product_name" },
					{ "data": "username" },
					{ "data": "action" },
				],
				"columnDefs": [{
					"targets": [0,7],
					"orderable": false
				}],
			});

	var revQuotationTable = '';
	$('body').on('click', '#revise_quotation_tab_click', function(e){
		if (! $.fn.dataTable.isDataTable( '#reviseQuotationTable' ) )
		{
			revQuotationTable = $('#reviseQuotationTable').DataTable({
				"processing": true,
				"pageLength":  100,
				"serverSide": true,
				"pagingType": "full_numbers",
				"sDom": '<"H"lfrp>t<"F"ip>',
				"ajax":{
					"url": "{{ $get_rev_quotation_invoice_url }}",
					"dataType": "json",
					"type": "POST",
					"data":{ _token: "{{csrf_token()}}"}
				},
				"columns": [
					{ "data": "id" },
					{ "data": "quatation_no" },
					{ "data": "name" },
					{ "data": "mobile" },
					{ "data": "email" },
					{ "data": "country" },
					{ "data": "state"},
					{ "data": "product_name" },
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



		$('.datatable').DataTable({
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
	})

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

	$('body').on('click','#send_sms_res',function(e){
		var proforma_id = $(this).closest('tr').find('#proforma_id').val();
		var inquiry_id = $(this).closest('tr').find('#inquiry_id').val();
		$('#send_sms_form').find('#proforma_id').empty().val(proforma_id);

		$.ajax({
				type: 'post',
				url: '{{$get_customer_mobile}}',
				data: { "_token": "{{ csrf_token() }}",inq_id:inquiry_id},
				success: function (response)
				{
					$("#customer_mno_sms").empty().append().html(response).trigger('change.select2');
				},

			});
	});
	$.validator.setDefaults({ ignore: ":hidden:not(.select2)" })
	$('#send_sms_form').validate({
		rules: {
			'customer_mno[]': {required: true,},
		},
		messages:{
			'customer_mno[]': {required: "Please Select Mobile Number",},
		},
	});
	$('#send_sms_form').on('submit', function(e){
		e.preventDefault();
		var formData = new FormData(this);
		var form = this;

		if($("#send_sms_form").valid())
		{
			$(':input[type="submit"]').prop('disabled', true);

			$('.blockUI').show();
			$.ajax({
				type: 'post',
				url: '{{$send_sms_url}}',
				data : formData,
				dataType: "json",
				processData: false,
				contentType: false,
				success: function (response)
				{
					if(response[0] == 'success')
					{
						alert('SMS Send Successfully.');
						$(':input[type="submit"]').prop('disabled', false);
						$('#send_sms_model').modal('hide');
						$('.blockUI').hide();
					}
					else
					{
						alert('SMS Not Successfully Send.');
						$(':input[type="submit"]').prop('disabled', false);
						$('.blockUI').hide();
					}
				},
				error:function()
				{
					alert('SMS Not Successfully Send.');
					$(':input[type="submit"]').prop('disabled', false);
					$('.blockUI').hide();
				}
			});
		}
		else
		{
			return false;
		}
	});

	$(document).ajaxStart(function() {
		$(".blockUI").show();
	});

	$(document).ajaxStop(function() {
		$(".blockUI").hide();
	});

 });
</script>

@endsection
