<link href="{{ URL::asset('external/css/datatable/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('external/css/datatable/buttons.dataTables.min.css') }}" rel="stylesheet">
<div class="table-responsive">
@if($role == '2' || $role == '3' || $role == '5')
<table id="datatable1" class="table table-bordered table-striped" style="width:100%">
<thead>
<tr>
	<th colspan="8"><center><h3>Inquiry & Quotation Report</h3></center></th>
</tr>
<tr>
	<th><center>Name :</center></th>
	<th colspan="2">{{$emp_detail[0]->name}}</th>
	<th><center>Zone :</center></th>
	<th colspan="2">{{ $emp_zone }}</th>
	<th><center>Date :</center></th>
	<th>{{ date('d-m-Y',strtotime($date)) }}</th>
</tr>
<tr>
	<th colspan="2"><center>Previous Pending Inquiry</center></th>
	<th colspan="2"><center>Inquiry Entry</center></th>
	<th><center>Allot Inquiry</center></th>
	<th><center>Quotation</center></th>
	<th colspan="2"><center>Total Pending Inquiry</center></th>
</tr>
<tr>
	<td colspan="2"><center>{{ $previous_pending_inq }}</center></td>
	<td colspan="2"><center>{{ $inquiry_total_per_day }}</center></td>
	<td><center>{{ $inquiry_allot }}</center></td>
	<td><center>{{ $generate_quot }}</center></td>
	<td colspan="2"><center>{{ $total_pending_inq }}</center></td>
</tr>
<tr class="danger"><th colspan="8"><center>Quotation Details</center></th></tr>
<tr class="success">
	<th>Sr. No</th>
	<th>Quotation No.</th>
	<th>Client Name</th>
	<th>Mobile No.</th>
	<th>State</th>
	<th>Inquiry For</th>
	<th>Inquiry Source</th>
	<th>Quotation Time</th>
</tr>
</thead>
<tbody>
@foreach($quotation_detail as $key=>$val)
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
		<td>{{ $val->state_name }}</td>
		<td>{{ $val->product_name }}</td>
		<td>{{ $val->source_name }}</td>
		<td>{{ date('h:i:s a',strtotime($val->quatation_time)) }}</td>
	</tr>
@endforeach
<tr class="danger"><th colspan="8"><center>Revise Quotation Details</center></th></tr>
<tr class="success">
	<th>Sr. No</th>
	<th>Quotation No.</th>
	<th>Client Name</th>
	<th>Mobile No.</th>
	<th>State</th>
	<th>Inquiry For</th>
	<th width="20%">Remark</th>
	<th>Quotation Time</th>
</tr>
@foreach($revise_quotation_detail as $key=>$val)
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
		<td>{{ $val->revise_quatation_no }}</td>
		<td>{{ $val->prefix.' '.$val->name }}</td>
		<td>{!! implode('<br />',$mobile) !!}</td>
		<td>{{ $val->state_name }}</td>
		<td>{{ $val->product_name }}</td>
		<td>{{ $val->remarks }}</td>
		<td>{{ date('h:i:s a',strtotime($val->revise_time)) }}</td>
	</tr>
@endforeach
<tr class="danger"><th colspan="8"><center>Cancel Inquiry</center></th></tr>
<tr class="success">
	<th>Sr. No.</th>
	<th>Inquiry No.</th>
	<th>Client Name</th>
	<th>Mobile No.</th>
	<th>State</th>
	<th>Inquiry For</th>
	<th>Inquiry Source</th>
	<th width="20%">Cancel Reason</th>
</tr>
@foreach($cancel_inquiry_detail as $key=>$val)
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
		<td>{{ $val->inquiry_no }}</td>
		<td>{{ $val->prefix.' '.$val->name }}</td>
		<td>{!! implode('<br />',$mobile) !!}</td>
		<td>{{ $val->state_name }}</td>
		<td>{{ $val->product_name }}</td>
		<td>{{ $val->source_name }}</td>
		<td>{{ $val->cancel_reason }}</td>
	</tr>
@endforeach
<tr class="danger"><th colspan="8"><center>Pending Inquiry</center></th></tr>
<tr class="success">
	<th>Sr. No.</th>
	<th>Inquiry No.</th>
	<th>Client Name</th>
	<th>Mobile No.</th>
	<th>State</th>
	<th>Inquiry Source</th>
	<th>Calling Time</th>
	<th width="20%">Remark</th>
</tr>
@foreach($pending_inquiry as $key=>$val)
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
		<td>{{ $val->inquiry_no }}</td>
		<td>{{ $val->prefix.' '.$val->name }}</td>
		<td>{!! implode('<br />',$mobile) !!}</td>
		<td>{{ $val->state_name }}</td>
		<td>{{ $val->source_name }}</td>
		<td>{{ date('H:i:s a',strtotime($val->inquiry_time)) }}</td>
		<td>{{ $val->inq_remark }}</td>
	</tr>
@endforeach

</tbody>
</table>
@elseif($role == '4' || $role == '6' || $role == '11')
<table id="datatable1" class="table table-bordered table-striped" style="width:100%">
	<thead>
	<tr>
		<th colspan="8"><center><h3>Follow-Up Details</h3></center></th>
	</tr>
	<tr>
		<th><center>Name :</center></th>
		<th colspan="2">{{ $emp_detail[0]->name }}</th>
		<th><center>Zone :</center></th>
		<th colspan="2">{{ $emp_zone }}</th>
		<th><center>Date :</center></th>
		<th>{{ date('d-m-Y',strtotime($date)) }}</th>
	</tr>
	<tr>
		<th colspan="2"><center>Previous Pending Follow-Up</center></th>
		<th colspan="2"><center>Allotment</center></th>
		<th colsapn="1"><center>Follow-Up</center></th>
		<th colspan="1"><center>Total Follow-Up</center></th>
		<th colspan="2"><center>Pending Follow-Up</center></th>
	</tr>
	<tr>
		<td colspan="2"><center>{{ $previous_pending_followup }}</center></td>
		<td colspan="2"><center>{{ $allotment_total }}</center></td>
		<td colspan="1"><center>{{ $total_follow_up }}</center></td>
		<td colspan="1"><center>{{ $allotment_total+$total_follow_up }}</center></td>
		<td colspan="2"><center>{{ $pending_follow_up }}</center></td>
	</tr>
	<tr>
		<th><center>E.Hot</center></th>
		<th><center>E.Live</center></th>
		<th><center>General</center></th>
		<th><center>Hot</center></th>
		<th><center>Live</center></th>
		<th><center>Order Book</center></th>
		<th><center>Postponed</center></th>
		<th><center>Regret</center></th>
	</tr>
	<tr>
		<th><center>{{ count($ehot_detail) }}</center></th>
		<th><center>{{ count($elive_detail) }}</center></th>
		<th><center>{{ $general_client_category }}</center></th>
		<th><center>{{ $hot_client_category }}</center></th>
		<th><center>{{ $live_client_category }}</center></th>
		<th><center>{{ count($order_book) }}</center></th>
		<th><center>{{ $posponed_client_category }}</center></th>
		<th><center>{{ count($regret_list) }}</center></th>
	</tr>
	</thead>
	<tbody>
		<tr class="danger"><th colspan="8"><center>E.Hot</center></th></tr>
		<tr class="success">
			<th><center>Sr. No.</center></th>
			<th><center>Quotation Number</center></th>
			<th><center>Client Name</center></th>
			<th><center>Mobile No.</center></th>
			<th><center>Inquiry For</center></th>
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
				<td colspan="3"></td>
			</tr>
		@endif
		<tr class="danger"><th colspan="8"><center>E.Live</center></th></tr>
		<tr class="success">
			<th><center>Sr. No.</center></th>
			<th><center>Quotation Number</center></th>
			<th><center>Client Name</center></th>
			<th><center>Mobile No.</center></th>
			<th><center>Inquiry For</center></th>
			<th colspan="3"><center>Remark</center></th>
		</tr>
		@if(! empty($elive_detail))
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
				<td colspan="3"></td>
			</tr>
		@endif
		<tr class="danger"><th colspan="8"><center>Price & Technicle Issue</center></th></tr>
		<tr class="success">
			<th><center>Sr. No.</center></th>
			<th><center>Quotation Number</center></th>
			<th><center>Client Name</center></th>
			<th><center>Mobile No.</center></th>
			<th><center>Inquiry For</center></th>
			<th colspan="3"><center>Remark</center></th>
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
				<td colspan="3">{{ $val->remark }}</td>
			</tr>
		@endforeach
		@else
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td colspan="3"></td>
			</tr>
		@endif
		<tr class="danger"><th colspan="8"><center>Order Book</center></th></tr>
		<tr class="success">
			<th><center>Sr. No.</center></th>
			<th><center>Quotation Number</center></th>
			<th><center>Client Name</center></th>
			<th><center>Mobile No.</center></th>
			<th><center>Inquiry For</center></th>
			<th colspan="3"><center>Remark</center></th>
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
				<td colspan="3">{{ $val->remark }}</td>
			</tr>
		@endforeach
		@else
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td colspan="3"></td>
			</tr>
		@endif

		<tr class="danger"><th colspan="8"><center>Regret</center></th></tr>
		<tr class="success">
			<th><center>Sr. No.</center></th>
			<th><center>Quotation Number</center></th>
			<th><center>Client Name</center></th>
			<th><center>Mobile No.</center></th>
			<th><center>Inquiry For</center></th>
			<th colspan="3"><center>Remark</center></th>
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
				<td colspan="3">{{ $val->regret_remark }}</td>
			</tr>
		@endforeach
		@else
			<tr>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<td colspan="3"></td>
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
	$('#datatable1').DataTable({
		'paging'      : true,
		'lengthChange': true,
		'searching'   : true,
		'ordering'    : true,
		'info'        : true,
		'autoWidth'   : true,
		"pageLength":  100,
		dom: 'Bfrtip',
		 buttons: [
            {
                extend: 'excel',
				text:'Export To Excel',
                title: 'Inquiry & Quotation Report',
				footer: true
            },
            {
                extend: 'csv',
				text:'Export To CSV',
                title: 'Inquiry & Quotation Report',
				footer: true
            }
        ],
		"columnDefs": [ {
        "orderable": false
        } ],
	});
});

</script>