@if(! empty($remark))
	@php
		$remark_data = array_reverse(explode("****",$remark[0]->inq_remark));
		$remark_by = array_reverse(explode("****",$remark[0]->remark_by));
		$remark_date = array_reverse(explode("****",$remark[0]->remark_date));
	@endphp
	<table class="table table-bordered" width="100%">
		<thead>
			<tr>
				<th>No</th>
				<th>Inq Number</th>
				<th>Remark</th>
				<th>Added By</th>
				<th>Date & Time</th>
			</tr>
		</thead>
		<tbody>
			@foreach($remark_data as $key=>$val)
			<tr>
				<td>{{ $key+1 }}</td>
				<td>{{ $remark[0]->inquiry_no }}</td>
				<td>{{ $val }}</td>
				<td>
					@if(isset($remark_by[$key]))
						@if(isset($emp_detail[$remark_by[$key]]))
							{{ $emp_detail[$remark_by[$key]] }}
						@endif
					@endif
				</td>
				<td>
					@if(isset($remark_date[$key]) && $remark_date[$key] != '')
						{{ date('d-m-Y h:i:s',strtotime($remark_date[$key])) }}
					@endif
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
@endif