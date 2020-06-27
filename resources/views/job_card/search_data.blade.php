@if(! empty($result))
<div class="table-responsive">
<table id="datatable1" class="table table-bordered table-striped" style="width:100%">
<thead>
<tr>
	<th>Sr No.</th>
	<th>Quotation No.</th>
	<th>Client Name</th>
	<th>Mobile No.</th>
	<th>Email Id</th>
	<th>Country</th>
	<th>State</th>
	<th>City</th>
	<th>Inquiry For</th>
	<th>Order Book Date</th>
	<th>Order Book By</th>
	<th>Manage</th>
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
		<td>{{ $v->city_name }}</td>
		<td>{{ $v->product_name }}</td>
		<td>{{ date('d-m-Y',strtotime($v->order_book_date)) }}</td>
		<td>{{ $v->order_by_user }}</td>
		<td>
			<a href="{{ $view_fun }}" target="_blank" class="btn bg-olive btn-flat btn-sm"><i class="glyphicon glyphicon-eye-open icon-white"></i> View</a>
			<a href="{{ 'Job_card/print_pdf/'.$utility->encode($v->order_id).'/'.$utility->encode('print')}}" target="_blank" class="btn btn-danger btn-flat btn-sm"><i class="glyphicon glyphicon-print icon-white"></i> Print</a>
			<a href="{{ 'Job_card/print_pdf/'.$utility->encode($v->order_id).'/'.$utility->encode('download')}}" class="btn btn-info btn-flat btn-sm"><i class="glyphicon glyphicon-download icon-white"></i> Download</a>
		</td>
		
	</tr>
@endforeach
</tbody>
</table>
</div>
@else
	<h2>No Search Result Available. </h2>
@endif
<script type="text/javascript"> 
$(document).ready(function() {
		$.fn.dataTable.ext.errMode = 'none';
		$('#datatable1').DataTable({
			  'paging'      : true,
			  'lengthChange': true,
			  'searching'   : true,
			  'ordering'    : true,
			  'info'        : true,
			  'autoWidth'   : true,
			 
				"pageLength":  100,
			}); 
		
});
			
</script>