@if(! empty($result))
<div class="table-responsive">
<table id="datatable1" class="table table-bordered table-striped" style="width:100%">
<thead>
	<tr>
		<th>Sr No.</th>
		<th>View</th>
		<th>Quot. Date</th>
		<th>Client Name</th>
		<th>Mobile No.</th>
		<th>Email Id</th>
		<th>Address</th>
		<th>Country</th>
		<th>State</th>
		<th>City</th>
		<th>Inquiry For</th>
		<th>Inquiry Type</th>
		<th>Quot. No.</th>
		<th>Inq. No.</th>
		<th>Inq. Source</th>
		<th>Client Category</th>
		<th>Inq. By</th>
		<th>Quot. By</th>
		<th>Follow-Up By</th>
	</tr>
</thead>
<tbody>
	@foreach($result as $k=>$v)
		<tr>
			<td>{{ $k+1 }}</td>
			<td><a href="{{ 'Follow_up/'.$utility->encode($v->inquiry_id)}}" target="_blank" class="btn btn-success">View</a></td>
			<td>{{ date('d-m-Y',strtotime($v->quatation_date)) }}</td>
			<td>{{ $v->name }}</td>
			<td>
			<?php
				if($v->mobile != '')
					echo $v->mobile;
				
				if($v->mobile_2 != '')
					echo '<br>';
					echo $v->mobile_2;
				
				if($v->mobile_3 != '')
					echo '<br>';
					echo $v->mobile_3;
			?>
			</td>
			<td>
			<?php 
				if($v->email != '')
					echo $v->email;
				if($v->email_2 != '')
					echo '<br>';
					echo $v->email_2;
			?>
			</td>
			<td>{{ $v->address }}</td>
			<td>{{ $v->country_name }}</td>
			<td>{{ $v->state_name }}</td>
			<td>{{ $v->city_name }}</td>
			<td>{{ $v->product_name }}</td>
			<td>{{ $v->category_name }}</td>
			<td>{{ $v->quatation_no }}</td>
			<td>{{ $v->inquiry_no }}</td>
			<td>{{ $v->source_name }}</td>
			<td>{{ $v->client_category_name }}</td>
			<td>{{ $v->inq_user }}</td>
			<td>{{ $v->quot_user }}</td>
			<td>{{ $v->foll_user }}</td>
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