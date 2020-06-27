@if(count($document_detail))
	<div class="table-responsive">
		<table id="datatable" class="table table-bordered table-striped">
			 <thead>
			<tr>
				<th>Sr</th>
				<th>Document Date & Time</th>
				<th>Document Detail</th>
				<th>Added By</th>
				<th>Download</th>
			</tr>
		  </thead>
		   <tbody>
		 
				@foreach ($document_detail as $key=>$value)
				<tr>
					<td>{{ $key+1}}</td>
					<td>{{ date('d-m-Y h:i A',strtotime($value->document_attached_date.'' .$value->document_attached_time))}}</td>
					<td>{{ $value->d}}</td>
					<td>{{ $value->username}}</td>
					<td><a href="{{ URL::action('Follow_up@download',$value->document_name) }}" class="btn btn-success" > Download </a></td>
				</tr>
			  @endforeach
		   </tbody>
		  </table>
	</div>
@endif