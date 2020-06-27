@if(count($follow_ups))
<?php //print_r($follow_ups); ?>
<div class="table-responsive">
	<table id="datatable" class="table table-bordered table-striped">
		 <thead>
		<tr>
			<th>Sr</th>
			<th>Follow-Up Date & Time</th>
			<th>Follow-Up By</th>
			<th>Follow-Up Way</th>
			<th>Details</th>
			<th>Next Follow-Up Date</th>
			<th>Remark</th>
		</tr>
	  </thead>
	   <tbody>
	 
			@foreach($follow_ups as $key=>$value)
			<tr>
				<td>{{ $key+1}}</td>
				<td>{{ date('d-m-Y h:i A',strtotime($value->follow_up_date.'' .$value->follow_up_time))}}</td>
				<td>{{ $value->username}}</td>
				<td>{{ $value->followup_way_name}}</td>
				<td>{{ $value->detail}}</td>
				<td>{{ date('d-m-Y',strtotime($value->next_followup_date))}}</td>
				<td>{{ $value->call_receive_remark}}</td>
		  @endforeach
	   </tbody>
	  </table>
</div>
@endif