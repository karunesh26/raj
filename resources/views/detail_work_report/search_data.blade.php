<link href="{{ URL::asset('external/css/datatable/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('external/css/datatable/buttons.dataTables.min.css') }}" rel="stylesheet">
<div class="table-responsive">
@if($role == '4' || $role == '6' || $role == '11')
<table id="datatable1" class="table table-bordered table-striped" style="width:100%">
	<thead>
	<tr>
		<th colspan="9"><center><h3>Follow-Up Details</h3></center></th>
	</tr>
	<tr>
		<th><center>Name :</center></th>
		<th colspan="2">{{ $emp_detail[0]->name }}</th>
		<th><center>Zone :</center></th>
		<th colspan="2">{{ $emp_zone }}</th>
		<th colspan="2"><center>Date :</center></th>
		<th>{{ date('d-m-Y',strtotime($date)) }}</th>
	</tr>
	<tr>
		<th colspan="2"><center>Previous Pending Follow-Up</center></th>
		<th colspan="2"><center>Allotment</center></th>
		<th colsapn="2"><center>Follow-Up</center></th>
		<th colspan="1"><center>Total Follow-Up</center></th>
		<th colspan="2"><center>Pending Follow-Up</center></th>
	</tr>
	<tr>
		<td colspan="2"><center>{{ $previous_pending_followup }}</center></td>
		<td colspan="2"><center>{{ $allotment_total }}</center></td>
		<td colspan="1"><center>{{ $total_follow_up }}</center></td>
		<td colspan="1"><center>{{ $allotment_total+$total_follow_up }}</center></td>
		<td colspan="2"><center>{{ $pending_follow_up }}</center></td>
		<td></td>
	</tr>
	<tr>
		<th><center>E.Hot</center></th>
		<th><center>E.Live</center></th>
		<th><center>General</center></th>
		<th><center>Hot</center></th>
		<th><center>Live</center></th>
		<th><center>Order Book</center></th>
		<th><center>Postponed</center></th>
		<th colspan="2"><center>Regret</center></th>
	</tr>
	<tr>
		<th><center>{{ count($ehot_detail) }}</center></th>
		<th><center>{{ count($elive_detail) }}</center></th>
		<th><center>{{ count($general_detail) }}</center></th>
		<th><center>{{ count($hot_detail) }}</center></th>
		<th><center>{{ count($live_detail) }}</center></th>
		<th><center>{{ count($order_book) }}</center></th>
		<th><center>{{ count($postponed_list) }}</center></th>
		<th colspan="2"><center>{{ count($regret_list) }}</center></th>
	</tr>
	</thead>
	<tbody>
		<tr class="danger"><th colspan="9"><center>E.Hot</center></th></tr>
		<tr class="success">
			<th><center>Sr. No.</center></th>
			<th><center>Quotation Number</center></th>
			<th><center>Client Name</center></th>
			<th><center>Mobile No.</center></th>
			<th><center>Inquiry For</center></th>
			<th><center>Next Follow Up Date</center></th>
			<th colspan="3"><center>Remark</center></th>
		</tr>
		@if(! empty($ehot_detail))
		@foreach($ehot_detail as $key=>$val)
			@php
				$mobile = array();
				if($val->mobile != ''){
					$mobile[] = $val->mobile;
				}
				if($val->mobile_2 != ''){
					$mobile[] = $val->mobile_2;
				}
				if($val->mobile_3 != ''){
					$mobile[] = $val->mobile_3;
				}
			@endphp
			<tr>
				<td>{{ $key+1 }}</td>
				<td>{{ $val->quatation_no }}</td>
				<td>{{ $val->prefix.' '.$val->name }}</td>
				<td>{!! implode('<br />',$mobile) !!}</td>
				<td>{{ $val->product_name }}</td>
				<td>{{ date('d-m-Y',strtotime($val->next_followup_date)) }}</td>

				<td colspan="3">{{ $val->call_receive_remark }}</td>
			</tr>
		@endforeach
		@else
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td colspan="3"></td>
			</tr>
		@endif
		<tr class="danger"><th colspan="9"><center>E.Live</center></th></tr>
		<tr class="success">
			<th><center>Sr. No.</center></th>
			<th><center>Quotation Number</center></th>
			<th><center>Client Name</center></th>
			<th><center>Mobile No.</center></th>
			<th><center>Inquiry For</center></th>
			<th><center>Next Follow Up Date</center></th>
			<th colspan="3"><center>Remark</center></th>
		</tr>
		@if(!empty($elive_detail))
		@foreach($elive_detail as $key=>$val)
			@php
				$mobile = array();
				if($val->mobile != ''){
					$mobile[] = $val->mobile;
				}
				if($val->mobile_2 != ''){
					$mobile[] = $val->mobile_2;
				}
				if($val->mobile_3 != ''){
					$mobile[] = $val->mobile_3;
				}
			@endphp
			<tr>
				<td>{{ $key+1 }}</td>
				<td>{{ $val->quatation_no }}</td>
				<td>{{ $val->prefix.' '.$val->name }}</td>
				<td>{!! implode('<br />',$mobile) !!}</td>
				<td>{{ $val->product_name }}</td>
				<td>{{ $val->next_followup_date }}</td>
				<td colspan="3">{{ $val->call_receive_remark }}</td>
			</tr>
		@endforeach
		@else
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td colspan="3"></td>
			</tr>
		@endif
		<tr class="danger"><th colspan="9"><center>Hot</center></th></tr>
		<tr class="success">
			<th><center>Sr. No.</center></th>
			<th><center>Quotation Number</center></th>
			<th><center>Client Name</center></th>
			<th><center>Mobile No.</center></th>
			<th><center>Inquiry For</center></th>
			<th><center>Next Follow Up Date</center></th>
			<th colspan="3"><center>Remark</center></th>
		</tr>
		@if(! empty($hot_detail))
		@foreach($hot_detail as $key=>$val)
			@php
				$mobile = array();
				if($val->mobile != ''){
					$mobile[] = $val->mobile;
				}
				if($val->mobile_2 != ''){
					$mobile[] = $val->mobile_2;
				}
				if($val->mobile_3 != ''){
					$mobile[] = $val->mobile_3;
				}
			@endphp
			<tr>
				<td>{{ $key+1 }}</td>
				<td>{{ $val->quatation_no }}</td>
				<td>{{ $val->prefix.' '.$val->name }}</td>
				<td>{!! implode('<br />',$mobile) !!}</td>
				<td>{{ $val->product_name }}</td>
				<td>{{ date('d-m-Y',strtotime($val->next_followup_date)) }}</td>
				<td colspan="3">{{ $val->call_receive_remark }}</td>
			</tr>
		@endforeach
		@else
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td colspan="3"></td>
			</tr>
		@endif

		<tr class="danger"><th colspan="9"><center>Live</center></th></tr>
		<tr class="success">
			<th><center>Sr. No.</center></th>
			<th><center>Quotation Number</center></th>
			<th><center>Client Name</center></th>
			<th><center>Mobile No.</center></th>
			<th><center>Inquiry For</center></th>
			<th><center>Next Follow Up Date</center></th>
			<th colspan="3"><center>Remark</center></th>
		</tr>
		@if(! empty($live_detail))
		@foreach($live_detail as $key=>$val)
			@php
				$mobile = array();
				if($val->mobile != ''){
					$mobile[] = $val->mobile;
				}
				if($val->mobile_2 != ''){
					$mobile[] = $val->mobile_2;
				}
				if($val->mobile_3 != ''){
					$mobile[] = $val->mobile_3;
				}
			@endphp
			<tr>
				<td>{{ $key+1 }}</td>
				<td>{{ $val->quatation_no }}</td>
				<td>{{ $val->prefix.' '.$val->name }}</td>
				<td>{!! implode('<br />',$mobile) !!}</td>
				<td>{{ $val->product_name }}</td>
				<td>{{ date('d-m-Y',strtotime($val->next_followup_date)) }}</td>
				<td colspan="3">{{ $val->call_receive_remark }}</td>
			</tr>
		@endforeach
		@else
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td colspan="3"></td>
			</tr>
		@endif

		<tr class="danger"><th colspan="9"><center>General</center></th></tr>
		<tr class="success">
			<th><center>Sr. No.</center></th>
			<th><center>Quotation Number</center></th>
			<th><center>Client Name</center></th>
			<th><center>Mobile No.</center></th>
			<th><center>Inquiry For</center></th>
			<th><center>Next Follow Up Date</center></th>
			<th colspan="3"><center>Remark</center></th>
		</tr>
		@if(! empty($general_detail))
		@foreach($general_detail as $key=>$val)
			@php
				$mobile = array();
				if($val->mobile != ''){
					$mobile[] = $val->mobile;
				}
				if($val->mobile_2 != ''){
					$mobile[] = $val->mobile_2;
				}
				if($val->mobile_3 != ''){
					$mobile[] = $val->mobile_3;
				}
			@endphp
			<tr>
				<td>{{ $key+1 }}</td>
				<td>{{ $val->quatation_no }}</td>
				<td>{{ $val->prefix.' '.$val->name }}</td>
				<td>{!! implode('<br />',$mobile) !!}</td>
				<td>{{ $val->product_name }}</td>
				<td>{{ date('d-m-Y',strtotime($val->next_followup_date)) }}</td>
				<td colspan="3">{{ $val->call_receive_remark }}</td>
			</tr>
		@endforeach
		@else
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td colspan="3"></td>
			</tr>
		@endif



		<tr class="danger"><th colspan="9"><center>Price & Technicle Issue</center></th></tr>
		<tr class="success">
			<th><center>Sr. No.</center></th>
			<th><center>Quotation Number</center></th>
			<th><center>Client Name</center></th>
			<th><center>Mobile No.</center></th>
			<th><center>Inquiry For</center></th>
			<th colspan="4"><center>Remark</center></th>
		</tr>
		@if(! empty($prise_issue))
		@foreach($prise_issue as $key=>$val)
			@php
				$mobile = array();
				if($val->mobile != ''){
					$mobile[] = $val->mobile;
				}
				if($val->mobile_2 != ''){
					$mobile[] = $val->mobile_2;
				}
				if($val->mobile_3 != ''){
					$mobile[] = $val->mobile_3;
				}
			@endphp
			<tr>
				<td>{{ $key+1 }}</td>
				<td>{{ $val->quatation_no }}</td>
				<td>{{ $val->prefix.' '.$val->name }}</td>
				<td>{!! implode('<br />',$mobile) !!}</td>
				<td>{{ $val->product_name }}</td>
				<td colspan="4">{{ $val->remark }}</td>
			</tr>
		@endforeach
		@else
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td colspan="4"></td>
			</tr>
		@endif
		<tr class="danger"><th colspan="9"><center>Order Book</center></th></tr>
		<tr class="success">
			<th><center>Sr. No.</center></th>
			<th><center>Quotation Number</center></th>
			<th><center>Client Name</center></th>
			<th><center>Mobile No.</center></th>
			<th><center>Inquiry For</center></th>
			<th colspan="4"><center>Remark</center></th>
		</tr>
		@if(! empty($order_book))
		@foreach($order_book as $key=>$val)
			@php
				$mobile = array();
				if($val->mobile != ''){
					$mobile[] = $val->mobile;
				}
				if($val->mobile_2 != ''){
					$mobile[] = $val->mobile_2;
				}
				if($val->mobile_3 != ''){
					$mobile[] = $val->mobile_3;
				}
			@endphp
			<tr>
				<td>{{ $key+1 }}</td>
				<td>{{ $val->quatation_no }}</td>
				<td>{{ $val->prefix.' '.$val->name }}</td>
				<td>{!! implode('<br />',$mobile) !!}</td>
				<td>{{ $val->product_name }}</td>
				<td colspan="4">{{ $val->remark }}</td>
			</tr>
		@endforeach
		@else
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td colspan="4"></td>
			</tr>
		@endif
		<tr class="danger"><th colspan="9"><center>Postponed</center></th></tr>
		<tr class="success">
			<th><center>Sr. No.</center></th>
			<th><center>Quotation Number</center></th>
			<th><center>Client Name</center></th>
			<th><center>Mobile No.</center></th>
			<th><center>Inquiry For</center></th>
			<th><center>Next Follow Up Date</center></th>
			<th colspan="3"><center>Remark</center></th>
		</tr>
		@if(! empty($postponed_list))
		@foreach($postponed_list as $key=>$val)
			@php
				$mobile = array();
				if($val->mobile != ''){
					$mobile[] = $val->mobile;
				}
				if($val->mobile_2 != ''){
					$mobile[] = $val->mobile_2;
				}
				if($val->mobile_3 != ''){
					$mobile[] = $val->mobile_3;
				}
			@endphp
			<tr>
				<td>{{ $key+1 }}</td>
				<td>{{ $val->quatation_no }}</td>
				<td>{{ $val->prefix.' '.$val->name }}</td>
				<td>{!! implode('<br />',$mobile) !!}</td>
				<td>{{ $val->product_name }}</td>
				<td>{{ date('d-m-Y',strtotime($val->next_followup_date)) }}</td>
				<td colspan="3">{{ $val->call_receive_remark }}</td>
			</tr>
		@endforeach
		@else
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td colspan="3"></td>
			</tr>
		@endif
		<tr class="danger"><th colspan="9"><center>Regret</center></th></tr>
		<tr class="success">
			<th><center>Sr. No.</center></th>
			<th><center>Quotation Number</center></th>
			<th><center>Client Name</center></th>
			<th><center>Mobile No.</center></th>
			<th><center>Inquiry For</center></th>
			<th colspan="4"><center>Remark</center></th>
		</tr>
		@if(! empty($regret_list))
		@foreach($regret_list as $key=>$val)
			@php
				$mobile = array();
				if($val->mobile != ''){
					$mobile[] = $val->mobile;
				}
				if($val->mobile_2 != ''){
					$mobile[] = $val->mobile_2;
				}
				if($val->mobile_3 != ''){
					$mobile[] = $val->mobile_3;
				}
			@endphp
			<tr>
				<td>{{ $key+1 }}</td>
				<td>{{ $val->quatation_no }}</td>
				<td>{{ $val->prefix.' '.$val->name }}</td>
				<td>{!! implode('<br />',$mobile) !!}</td>
				<td>{{ $val->product_name }}</td>
				<td colspan="4">{{ $val->regret_remark }}</td>
			</tr>
		@endforeach
		@else
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td colspan="4"></td>
			</tr>
		@endif
	</tbody>
</table>
@else

@endif
</div>

<script src="{{ URL::asset('external/js/datatable/jquery.dataTables.min.js') }}"></script>
<script src="{{ URL::asset('external/js/datatable/dataTables.buttons.min.js') }}"></script>
<script src="{{ URL::asset('external/js/datatable/buttons.flash.min.js') }}"></script>
<script src="{{ URL::asset('external/js/datatable/jszip.min.js') }}"></script>
<script src="{{ URL::asset('external/js/datatable/buttons.html5.min.js') }}"></script>
<script type="text/javascript">
$(document).ready(function() {
	$.fn.dataTable.ext.errMode = 'none';
	// $('#datatable1').DataTable({
	// 	'paging'      : true,
	// 	'lengthChange': true,
	// 	'searching'   : true,
	// 	'ordering'    : true,
	// 	'info'        : true,
	// 	'autoWidth'   : true,
	// 	"pageLength":  100,
	// 	dom: 'Bfrtip',
	// 	 buttons: [
  //           {
  //               extend: 'excel',
	// 			text:'Export To Excel',
  //               title: 'Inquiry & Quotation Report',
	// 			footer: true
  //           },
  //           {
  //               extend: 'csv',
	// 			text:'Export To CSV',
  //               title: 'Inquiry & Quotation Report',
	// 			footer: true
  //           }
  //       ],
	// 	"columnDefs": [ {
  //       "orderable": false
  //       } ],
	// });
});

</script>