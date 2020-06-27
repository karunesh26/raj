@if(! empty($result))
<div class="table-responsive">
<table id="datatable1" class="table table-bordered table-striped" style="width:100%">
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
		<th>Manage</th>
	</tr>
</thead>
<tbody>
	@foreach($result as $k=>$v)
	@php 
		$view_fun = 'Follow_up/'.$utility->encode($v->order_inq_id);
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
		<td>{{ $k+1 }}</td>
		<td>{{ $quot_num }}</td>
		<td>{{ $v->name }}</td>
		<td>{{ $mobile }}</td>
		<td>{{ $email }}</td>
		<td>{{ $v->product_name }}</td>
		<td>{{ date('d-m-Y',strtotime($v->order_book_date)) }}</td>
		<td>{{ $v->order_by_user }}</td>
		<td>{{ $added_quot }}</td>
		<td><a href="{{ $view_fun }}" target="_blank" class="btn bg-olive btn-sm">View</a></td>
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