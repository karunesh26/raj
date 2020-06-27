<link href="{{ URL::asset('external/css/datatable/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('external/css/datatable/buttons.dataTables.min.css') }}" rel="stylesheet">
<div class="table-responsive">
@if($report_type == 'week')
<table id="datatable1" class="table table-bordered table-striped" style="width:100%">
<thead>
<tr class="success">
	<th>Sr. No</th>
	<th>Quotation No.</th>
	<th>Client Name</th>
	<th>Mobile No.</th>
	<th>Email Id</th>
	<th>Country</th>
	<th>State</th>
	<th>City</th>
	<th>Inquiry For</th>
	<th>Project Value</th>
	<th>Visit Detail</th>
	<th>Remark</th>
</tr>
</thead>
<tbody>
@foreach($week_num as $key=>$value)
	@php
		$result = App\Models\Data_model::db_query("select `hot_list_notify`.*,`inquiry`.project_value,`quatation`.quatation_no,`quatation`.quatation_id,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`customer_master`.email,`customer_master`.email_2,`country_master`.country_name,`state_master`.state_name,`city_master`.city_name,`product_master`.product_name from `hot_list_notify` INNER JOIN `inquiry` ON `inquiry`.inquiry_id = `hot_list_notify`.inquiry_id INNER JOIN `quatation` ON `quatation`.inquiry_id = `inquiry`.inquiry_id INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id LEFT JOIN `country_master` ON `country_master`.country_id = `customer_master`.country_id LEFT JOIN `state_master` ON `state_master`.state_id = `customer_master`.state_id LEFT JOIN `city_master` ON `city_master`.city_id = `customer_master`.city_id INNER JOIN `product_master` ON `product_master`.product_id = `inquiry`.product_id where `hot_list_notify`.week_no = '".$value->week_no."' ");
	@endphp
	<tr class="danger">
		<td colspan="3"><center><b>Hot List Week</b></center></td>
		<td colspan="2"><center><b>{{ $value->week_no }}</b></center></td>
		<td><center><b>Date</b></center></td>
		<td><center><b>From</b></center></td>
		<td colspan="2"><center><b>{{ date('d-m-Y',strtotime($value->from_date)) }}</b></center></td>
		<td><center><b>To</b></center></td>
		<td colspan="2"><center><b>{{ date('d-m-Y',strtotime($value->to_date)) }}</b></center></td>
	</tr>
	@foreach($result as $k=>$val)
	@php
		$mobile = array();
		$email = array();
		if($val->mobile != '')
		{
			$mobile[] = $val->mobile;
		}
		if($val->mobile_2 != '')
		{
			$mobile[] = $val->mobile_2;
		}
		if($val->mobile_3 != '')
		{
			$mobile[] = $val->mobile_3;
		}
		if($val->email != '')
		{
			$email[] = $val->email;
		}
		if($val->email_2 != '')
		{
			$email[] = $val->email_2;
		}

		$remarks = array();
		$get_remark = App\Models\Data_model::db_query("select * from `follow_up` where `inquiry_id`='".$val->inquiry_id."' AND `hot_list_follow_up_status`='1'");

		foreach($get_remark as $key=>$value)
		{
			$remarks[] = $value->call_receive_remark;
		}

		$get_visit_detail = App\Models\Data_model::db_query("select `visitor_detail`.*,`address_master`.office_name from `visitor_detail` INNER JOIN `address_master` ON `address_master`.id = `visitor_detail`.visit_at where `inquiry_id`='".$val->inquiry_id."' ");

		if(! empty($get_visit_detail))
		{
			$visit_at = '('.$get_visit_detail[0]->office_name.')';
		}
		else
		{
			$visit_at = '';
		}
		$get_revise_q=DB::table('revise_quatation')->where('quatation_id',$val->quatation_id)->select('total_amount')->orderBy('rq_no','DESC')->limit(1)->get();
		if(count($get_revise_q)){
			$project_value = number_format((float)$get_revise_q[0]->total_amount,2,'.','');
		}
		else{
			$get_q=DB::table('quatation')->where('quatation_id',$val->quatation_id)->select('total_amount')->orderBy('q_no','DESC')->limit(1)->get();
			$project_value = number_format((float)$get_q[0]->total_amount,2,'.','');
		}
		
	@endphp
	<tr class="info">
		<td>{{ $k+1 }}</td>
		<td>{{ $val->quatation_no }}</td>
		<td>{{ $val->prefix.' '.$val->name }}</td>
		<td>{!! implode('<br />',$mobile) !!}</td>
		<td>{!! implode('<br />',$email) !!}</td>
		<td>{{ $val->country_name }}</td>
		<td>{{ $val->state_name }}</td>
		<td>{{ $val->city_name }}</td>
		<td>{{ $val->product_name }}</td>
		<td>{{ $project_value }}</td>
		<td>{{ $visit_at }}</td>
		<td>{!! implode('-->',$remarks) !!}</td>
	</tr>
	@endforeach
@endforeach
</tbody>
</table>
@elseif($report_type == 'zone')
<table id="datatable1" class="table table-bordered table-striped" style="width:100%">
<thead>
<tr class="success">
	<th>Sr. No</th>
	<th>Quotation No.</th>
	<th>Client Name</th>
	<th>Mobile No.</th>
	<th>Email Id</th>
	<th>Country</th>
	<th>State</th>
	<th>City</th>
	<th>Inquiry For</th>
	<th>Project Value</th>
	<th>Visit Detail</th>
	<th>Remark</th>
</tr>
</thead>
<tbody>
@foreach($zone as $key=>$value)
	@php
		if($from_date != ''){
			$f_date = date('Y-m-d',strtotime($from_date));
			$t_date = date('Y-m-d',strtotime($to_date));

			$result = App\Models\Data_model::db_query("select `hot_list_notify`.*,`inquiry`.project_value,`quatation`.quatation_no,`quatation`.quatation_id,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`customer_master`.email,`customer_master`.email_2,`country_master`.country_name,`state_master`.state_name,`city_master`.city_name,`product_master`.product_name from `hot_list_notify` INNER JOIN `inquiry` ON `inquiry`.inquiry_id = `hot_list_notify`.inquiry_id INNER JOIN `quatation` ON `quatation`.inquiry_id = `inquiry`.inquiry_id INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id LEFT JOIN `country_master` ON `country_master`.country_id = `customer_master`.country_id LEFT JOIN `state_master` ON `state_master`.state_id = `customer_master`.state_id LEFT JOIN `city_master` ON `city_master`.city_id = `customer_master`.city_id INNER JOIN `product_master` ON `product_master`.product_id = `inquiry`.product_id where `hot_list_notify`.from_date >= '".$f_date."' AND  `hot_list_notify`.from_date <= '".$t_date."' AND `inquiry`.project_zone = '".$value->project_zone."' ");
		}else{
			$result = App\Models\Data_model::db_query("select `hot_list_notify`.*,`inquiry`.project_value,`quatation`.quatation_no,`quatation`.quatation_id,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`customer_master`.email,`customer_master`.email_2,`country_master`.country_name,`state_master`.state_name,`city_master`.city_name,`product_master`.product_name from `hot_list_notify` INNER JOIN `inquiry` ON `inquiry`.inquiry_id = `hot_list_notify`.inquiry_id INNER JOIN `quatation` ON `quatation`.inquiry_id = `inquiry`.inquiry_id INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id LEFT JOIN `country_master` ON `country_master`.country_id = `customer_master`.country_id LEFT JOIN `state_master` ON `state_master`.state_id = `customer_master`.state_id LEFT JOIN `city_master` ON `city_master`.city_id = `customer_master`.city_id INNER JOIN `product_master` ON `product_master`.product_id = `inquiry`.product_id where `inquiry`.project_zone = '".$value->project_zone."' ");
		}
	@endphp
	@if(! empty($result))
	<tr class="danger">
		<td colspan="12"><center><b>{{ $value->zone_name }}</b></center></td>
	</tr>
	@endif
	@foreach($result as $k=>$val)
	@php
		$mobile = array();
		$email = array();
		if($val->mobile != ''){
			$mobile[] = $val->mobile;
		}
		if($val->mobile_2 != ''){
			$mobile[] = $val->mobile_2;
		}
		if($val->mobile_3 != ''){
			$mobile[] = $val->mobile_3;
		}
		if($val->email != ''){
			$email[] = $val->email;
		}
		if($val->email_2 != ''){
			$email[] = $val->email_2;
		}

		$remarks = array();
		$get_remark = App\Models\Data_model::db_query("select * from `follow_up` where `inquiry_id`='".$val->inquiry_id."' AND `hot_list_follow_up_status`='1'");

		foreach($get_remark as $key=>$value)
		{
			$remarks[] = $value->call_receive_remark;
		}

		$get_visit_detail = App\Models\Data_model::db_query("select `visitor_detail`.*,`address_master`.office_name from `visitor_detail` INNER JOIN `address_master` ON `address_master`.id = `visitor_detail`.visit_at where `inquiry_id`='".$val->inquiry_id."' ");

		if(! empty($get_visit_detail))
		{
			$visit_at = '('.$get_visit_detail[0]->office_name.')';
		}
		else
		{
			$visit_at = '';
		}
		
		$get_revise_q=DB::table('revise_quatation')->where('quatation_id',$val->quatation_id)->select('total_amount')->orderBy('rq_no','DESC')->limit(1)->get();
		if(count($get_revise_q)){
			$project_value = number_format((float)$get_revise_q[0]->total_amount,2,'.','');
		}
		else{
			$get_q=DB::table('quatation')->where('quatation_id',$val->quatation_id)->select('total_amount')->orderBy('q_no','DESC')->limit(1)->get();
			$project_value = number_format((float)$get_q[0]->total_amount,2,'.','');
		}
		
	@endphp
	<tr class="info">
		<td>{{ $k+1 }}</td>
		<td>{{ $val->quatation_no }}</td>
		<td>{{ $val->prefix.' '.$val->name }}</td>
		<td>{!! implode('<br />',$mobile) !!}</td>
		<td>{!! implode('<br />',$email) !!}</td>
		<td>{{ $val->country_name }}</td>
		<td>{{ $val->state_name }}</td>
		<td>{{ $val->city_name }}</td>
		<td>{{ $val->product_name }}</td>
		<td>{{ $project_value }}</td>
		<td>{{ $visit_at }}</td>
		<td>{!! implode('-->',$remarks) !!}</td>
	</tr>
	@endforeach
@endforeach
</tbody>
</table>

@else
<table id="datatable1" class="table table-bordered table-striped" style="width:100%">
<thead>
<tr class="success">
	<th>Sr. No</th>
	<th>Quotation No.</th>
	<th>Client Name</th>
	<th>Mobile No.</th>
	<th>Email Id</th>
	<th>Country</th>
	<th>State</th>
	<th>City</th>
	<th>Inquiry For</th>
	<th>Project Value</th>
	<th>Visit Detail</th>
	<th>Remark</th>
</tr>
</thead>
<tbody>
@foreach($month as $key=>$value)
	@php
		if($from_date != ''){
			$f_date = date('Y-m-d',strtotime($from_date));
			$t_date = date('Y-m-d',strtotime($to_date));

			$result = App\Models\Data_model::db_query("select `hot_list_notify`.*,`inquiry`.project_value,`quatation`.quatation_no,`quatation`.quatation_id,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`customer_master`.email,`customer_master`.email_2,`country_master`.country_name,`state_master`.state_name,`city_master`.city_name,`product_master`.product_name from `hot_list_notify` INNER JOIN `inquiry` ON `inquiry`.inquiry_id = `hot_list_notify`.inquiry_id INNER JOIN `quatation` ON `quatation`.inquiry_id = `inquiry`.inquiry_id INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id LEFT JOIN `country_master` ON `country_master`.country_id = `customer_master`.country_id LEFT JOIN `state_master` ON `state_master`.state_id = `customer_master`.state_id LEFT JOIN `city_master` ON `city_master`.city_id = `customer_master`.city_id INNER JOIN `product_master` ON `product_master`.product_id = `inquiry`.product_id where `hot_list_notify`.from_date >= '".$f_date."' AND  `hot_list_notify`.from_date <= '".$t_date."' AND MONTH(from_date) = '".$value->month."' ");
		}else{
			$result = App\Models\Data_model::db_query("select `hot_list_notify`.*,`inquiry`.project_value,`quatation`.quatation_no,`quatation`.quatation_id,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`customer_master`.email,`customer_master`.email_2,`country_master`.country_name,`state_master`.state_name,`city_master`.city_name,`product_master`.product_name from `hot_list_notify` INNER JOIN `inquiry` ON `inquiry`.inquiry_id = `hot_list_notify`.inquiry_id INNER JOIN `quatation` ON `quatation`.inquiry_id = `inquiry`.inquiry_id INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id LEFT JOIN `country_master` ON `country_master`.country_id = `customer_master`.country_id LEFT JOIN `state_master` ON `state_master`.state_id = `customer_master`.state_id LEFT JOIN `city_master` ON `city_master`.city_id = `customer_master`.city_id INNER JOIN `product_master` ON `product_master`.product_id = `inquiry`.product_id where MONTH(from_date) = '".$value->month."' ");
		}
	@endphp
	@if(! empty($result))
	<tr class="danger">
		<td colspan="12"><center><b>{{ date("F", mktime(0, 0, 0, $value->month, 1)) }} - {{ date('Y') }}</b></center></td>
	</tr>
	@endif
	@foreach($result as $k=>$val)
	@php
		$mobile = array();
		$email = array();
		if($val->mobile != ''){
			$mobile[] = $val->mobile;
		}
		if($val->mobile_2 != ''){
			$mobile[] = $val->mobile_2;
		}
		if($val->mobile_3 != ''){
			$mobile[] = $val->mobile_3;
		}
		if($val->email != ''){
			$email[] = $val->email;
		}
		if($val->email_2 != ''){
			$email[] = $val->email_2;
		}

		$remarks = array();
		$get_remark = App\Models\Data_model::db_query("select * from `follow_up` where `inquiry_id`='".$val->inquiry_id."' AND `hot_list_follow_up_status`='1'");

		foreach($get_remark as $key=>$value)
		{
			$remarks[] = $value->call_receive_remark;
		}

		$get_visit_detail = App\Models\Data_model::db_query("select `visitor_detail`.*,`address_master`.office_name from `visitor_detail` INNER JOIN `address_master` ON `address_master`.id = `visitor_detail`.visit_at where `inquiry_id`='".$val->inquiry_id."' ");

		if(! empty($get_visit_detail))
		{
			$visit_at = '('.$get_visit_detail[0]->office_name.')';
		}
		else
		{
			$visit_at = '';
		}
		$get_revise_q=DB::table('revise_quatation')->where('quatation_id',$val->quatation_id)->select('total_amount')->orderBy('rq_no','DESC')->limit(1)->get();
		if(count($get_revise_q)){
			$project_value = number_format((float)$get_revise_q[0]->total_amount,2,'.','');
		}
		else{
			$get_q=DB::table('quatation')->where('quatation_id',$val->quatation_id)->select('total_amount')->orderBy('q_no','DESC')->limit(1)->get();
			$project_value = number_format((float)$get_q[0]->total_amount,2,'.','');
		}
		
	@endphp
	<tr class="info">
		<td>{{ $k+1 }}</td>
		<td>{{ $val->quatation_no }}</td>
		<td>{{ $val->prefix.' '.$val->name }}</td>
		<td>{!! implode('<br />',$mobile) !!}</td>
		<td>{!! implode('<br />',$email) !!}</td>
		<td>{{ $val->country_name }}</td>
		<td>{{ $val->state_name }}</td>
		<td>{{ $val->city_name }}</td>
		<td>{{ $val->product_name }}</td>
		<td>{{ $project_value }}</td>
		<td>{{ $visit_at }}</td>
		<td>{!! implode('-->',$remarks) !!}</td>
	</tr>
	@endforeach
@endforeach
</tbody>
</table>
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
                title: 'Hot List Report',
				footer: true
            },
            {
                extend: 'csv',
				text:'Export To CSV',
                title: 'Hot List Report',
				footer: true
            }
        ],
		"columnDefs": [ {
        "orderable": false
        } ],
	});
});

</script>