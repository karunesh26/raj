@if(count($visiting_detail))
<div class="table-responsive">
	<table id="datatable" class="table table-bordered table-striped">
		<thead>
			<tr>
				<th>Sr</th>
				<th>Visitor Form No</th>
				<th>Visit Date & Time</th>
				<th>Attended By</th>
				<th>Visit At</th>
				<th>Visit Form</th>
			</tr>
		</thead>
	   <tbody>
			@foreach ($visiting_detail as $key=>$value)
			<tr>
				<td>{{ $key+1}}</td>
				<td>{{ $value->vsf_no }}</td>
				<td>{{ date('d-m-Y h:i A',strtotime($value->visit_date.'' .$value->visit_time))}}</td>
				<td>{{ $value->name }}</td>
				<td>{{ $value->office_name}}</td>
				<td><a href="Follow_up/visitor_view/{{ $value->form_no }}" target="_blank" class="btn btn-success">Form View</a></td>
			</tr>
		  @endforeach
	   </tbody>
	  </table>
</div>
@endif