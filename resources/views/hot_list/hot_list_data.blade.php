@if(isset($hot_list_data))
@if(count($hot_list_data))
  <table id="datatable" class="table table-bordered color_table">
	<thead>
		<tr>
			<th colspan=3>Hot List Data</th>
		</tr>
		<tr>
			<th>Sr</th>
			<th>Quotation No.</th>
			<th>Customer</th>
		</tr>
	</thead>
	<tbody>
		@foreach ($hot_list_data as $key=>$value)

		<tr id="follow_up_btn" <?php echo ($value->follow_up_status != 0)?'style="background:#00FA9A" class="active_tr"':'';?> >
			<td align="center">
				{{ $key+1}}<br />
				<input type="hidden" name="follow_up_inquiry_id" id="follow_up_inquiry_id" value="{{$utility->encode($value->inquiry_id) }}" />
			</td>
			<td>{{ $value->quatation_no}}</td>
			<td>{{ $value->name}}</td>
		</tr>
		@endforeach
	</tbody>
  </table>
@endif
@endif
<script type="text/javascript">
jQuery(document).ready(function($){
	$('#datatable').DataTable( {
        scrollY:        '70vh',
        scrollCollapse: true,
        paging:         false
    } );
});
</script>