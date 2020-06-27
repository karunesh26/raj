<link href="{{ URL::asset('external/css/datatable/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('external/css/datatable/buttons.dataTables.min.css') }}" rel="stylesheet">
<div class="table-responsive">
@if($report_type == 'date')
<table id="datatable1" class="table table-bordered table-striped" style="width:100%">
<thead>
<tr class="success">
	<th>Sr. No</th>
	<th>Date</th>
	<th>Quotation No.</th>
	<th>Client Name</th>
	<th>Mobile No.</th>
	<th>Email Id</th>
	<th>Country</th>
	<th>State</th>
	<th>City</th>
	<th>Inquiry For</th>
	<th>Project Value</th>
	<th>User Remark</th>
	<th>Authorized Remark</th>
</tr>
</thead>
<tbody>
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
		$get_remark = App\Models\Data_model::db_query("select * from `follow_up` where `inquiry_id`='".$val->inquiry_id."' AND `hot_list_follow_up_status`='0' AND `regret_follow_up_status` = '0'");
		
		foreach($get_remark as $key=>$value)
		{
			$remarks[] = $value->call_receive_remark;
		}
		
		$regret_remarks = array();
		$get_remark = App\Models\Data_model::db_query("select * from `follow_up` where `inquiry_id`='".$val->inquiry_id."' AND `regret_follow_up_status` = 1 ");
		
		foreach($get_remark as $key=>$value)
		{
			$regret_remarks[] = $value->call_receive_remark;
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
		<td>{{ date("d-m-Y",strtotime($val->date)) }}</td>
		<td>{{ $val->quatation_no }}</td>
		<td>{{ $val->prefix.' '.$val->name }}</td>
		<td>{!! implode('<br />',$mobile) !!}</td>
		<td>{!! implode('<br />',$email) !!}</td>
		<td>{{ $val->country_name }}</td>
		<td>{{ $val->state_name }}</td>
		<td>{{ $val->city_name }}</td>
		<td>{{ $val->product_name }}</td>
		<td>{{ $project_value }}</td>
		<td>{!! implode('<br />',$remarks) !!}</td>
		<td>{!! implode('<br />',$regret_remarks) !!}</td>
	</tr>
@endforeach
</tbody>
</table>
@elseif($report_type == 'month')
<table id="datatable1" class="table table-bordered table-striped" style="width:100%">
<thead>
<tr class="success">
	<th>Sr. No</th>
	<th>Date</th>
	<th>Quotation No.</th>
	<th>Client Name</th>
	<th>Mobile No.</th>
	<th>Email Id</th>
	<th>Country</th>
	<th>State</th>
	<th>City</th>
	<th>Inquiry For</th>
	<th>Project Value</th>
	<th>User Remark</th>
	<th>Authorized Remark</th>
</tr>
</thead>
<tbody>
@foreach($month as $key=>$value)
	@php
		if($from_date != ''){
			$f_date = date('Y-m-d',strtotime($from_date));
			$t_date = date('Y-m-d',strtotime($to_date));
			
			$result = App\Models\Data_model::db_query("select `followup_regret`.*,`inquiry`.project_value,`quatation`.quatation_no,`quatation`.quatation_id,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`customer_master`.email,`customer_master`.email_2,`country_master`.country_name,`state_master`.state_name,`city_master`.city_name,`product_master`.product_name from `followup_regret` INNER JOIN `inquiry` ON `inquiry`.inquiry_id = `followup_regret`.inquiry_id INNER JOIN `quatation` ON `quatation`.inquiry_id = `inquiry`.inquiry_id INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id LEFT JOIN `country_master` ON `country_master`.country_id = `customer_master`.country_id LEFT JOIN `state_master` ON `state_master`.state_id = `customer_master`.state_id LEFT JOIN `city_master` ON `city_master`.city_id = `customer_master`.city_id INNER JOIN `product_master` ON `product_master`.product_id = `inquiry`.product_id where `followup_regret`.date >= '".$f_date."' AND `followup_regret`.date <= '".$t_date."' AND MONTH(date) = '".$value->month."' ");
		}else{
			$result = App\Models\Data_model::db_query("select `followup_regret`.*,`inquiry`.project_value,`quatation`.quatation_no,`quatation`.quatation_id,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`customer_master`.email,`customer_master`.email_2,`country_master`.country_name,`state_master`.state_name,`city_master`.city_name,`product_master`.product_name from `followup_regret` INNER JOIN `inquiry` ON `inquiry`.inquiry_id = `followup_regret`.inquiry_id INNER JOIN `quatation` ON `quatation`.inquiry_id = `inquiry`.inquiry_id INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id LEFT JOIN `country_master` ON `country_master`.country_id = `customer_master`.country_id LEFT JOIN `state_master` ON `state_master`.state_id = `customer_master`.state_id LEFT JOIN `city_master` ON `city_master`.city_id = `customer_master`.city_id INNER JOIN `product_master` ON `product_master`.product_id = `inquiry`.product_id where MONTH(date) = '".$value->month."'  ");
		}
	@endphp
	@if(! empty($result))
	<tr class="danger">
		<td colspan="13"><center><b>{{ date("F", mktime(0, 0, 0, $value->month, 1)) }} - {{ date('Y') }}</b></center></td>
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
		$get_remark = App\Models\Data_model::db_query("select * from `follow_up` where `inquiry_id`='".$val->inquiry_id."' AND `hot_list_follow_up_status`='0' AND `regret_follow_up_status` = '0'");
		
		foreach($get_remark as $key=>$value)
		{
			$remarks[] = $value->call_receive_remark;
		}
		
		$regret_remarks = array();
		$get_remark = App\Models\Data_model::db_query("select * from `follow_up` where `inquiry_id`='".$val->inquiry_id."' AND `regret_follow_up_status` = 1 ");
		
		foreach($get_remark as $key=>$value)
		{
			$regret_remarks[] = $value->call_receive_remark;
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
		<td>{{ date("d-m-Y",strtotime($val->date)) }}</td>
		<td>{{ $val->quatation_no }}</td>
		<td>{{ $val->prefix.' '.$val->name }}</td>
		<td>{!! implode('<br />',$mobile) !!}</td>
		<td>{!! implode('<br />',$email) !!}</td>
		<td>{{ $val->country_name }}</td>
		<td>{{ $val->state_name }}</td>
		<td>{{ $val->city_name }}</td>
		<td>{{ $val->product_name }}</td>
		<td>{{ $project_value }}</td>
		<td>{!! implode('<br />',$remarks) !!}</td>
		<td>{!! implode('<br />',$regret_remarks) !!}</td>
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
	<th>Date</th>
	<th>Quotation No.</th>
	<th>Client Name</th>
	<th>Mobile No.</th>
	<th>Email Id</th>
	<th>Country</th>
	<th>State</th>
	<th>City</th>
	<th>Inquiry For</th>
	<th>Project Value</th>
	<th>User Remark</th>
	<th>Authorized Remark</th>
</tr>
</thead>
<tbody>
@foreach($state as $key => $value)
	@php
		if($from_date != ''){
			$f_date = date('Y-m-d',strtotime($from_date));
			$t_date = date('Y-m-d',strtotime($to_date));
			
			$result = App\Models\Data_model::db_query("select `followup_regret`.*,`inquiry`.project_value,`quatation`.quatation_no,`quatation`.quatation_id,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`customer_master`.email,`customer_master`.email_2,`country_master`.country_name,`state_master`.state_name,`city_master`.city_name,`product_master`.product_name from `followup_regret` INNER JOIN `inquiry` ON `inquiry`.inquiry_id = `followup_regret`.inquiry_id INNER JOIN `quatation` ON `quatation`.inquiry_id = `inquiry`.inquiry_id INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id LEFT JOIN `country_master` ON `country_master`.country_id = `customer_master`.country_id LEFT JOIN `state_master` ON `state_master`.state_id = `customer_master`.state_id LEFT JOIN `city_master` ON `city_master`.city_id = `customer_master`.city_id INNER JOIN `product_master` ON `product_master`.product_id = `inquiry`.product_id where `followup_regret`.date >= '".$f_date."' AND `followup_regret`.date <= '".$t_date."' AND `customer_master`.state_id = '".$value->state_id."'  ");
		}else{
			$result = App\Models\Data_model::db_query("select `followup_regret`.*,`inquiry`.project_value,`quatation`.quatation_no,`quatation`.quatation_id,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`customer_master`.email,`customer_master`.email_2,`country_master`.country_name,`state_master`.state_name,`city_master`.city_name,`product_master`.product_name from `followup_regret` INNER JOIN `inquiry` ON `inquiry`.inquiry_id = `followup_regret`.inquiry_id INNER JOIN `quatation` ON `quatation`.inquiry_id = `inquiry`.inquiry_id INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id LEFT JOIN `country_master` ON `country_master`.country_id = `customer_master`.country_id LEFT JOIN `state_master` ON `state_master`.state_id = `customer_master`.state_id LEFT JOIN `city_master` ON `city_master`.city_id = `customer_master`.city_id INNER JOIN `product_master` ON `product_master`.product_id = `inquiry`.product_id where `customer_master`.state_id = '".$value->state_id."' ");
		}
	@endphp
	@if(! empty($result))
	<tr class="danger">
		<td colspan="13"><center><b>{{ $value->state_name }}</b></center></td>
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
		$get_remark = App\Models\Data_model::db_query("select * from `follow_up` where `inquiry_id`='".$val->inquiry_id."' AND `hot_list_follow_up_status`='0' AND `regret_follow_up_status` = '0'");
		
		foreach($get_remark as $key=>$value)
		{
			$remarks[] = $value->call_receive_remark;
		}
		
		$regret_remarks = array();
		$get_remark = App\Models\Data_model::db_query("select * from `follow_up` where `inquiry_id`='".$val->inquiry_id."' AND `regret_follow_up_status` = 1 ");
		
		foreach($get_remark as $key=>$value)
		{
			$regret_remarks[] = $value->call_receive_remark;
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
		<td>{{ date("d-m-Y",strtotime($val->date)) }}</td>
		<td>{{ $val->quatation_no }}</td>
		<td>{{ $val->prefix.' '.$val->name }}</td>
		<td>{!! implode('<br />',$mobile) !!}</td>
		<td>{!! implode('<br />',$email) !!}</td>
		<td>{{ $val->country_name }}</td>
		<td>{{ $val->state_name }}</td>
		<td>{{ $val->city_name }}</td>
		<td>{{ $val->product_name }}</td>
		<td>{{ $project_value }}</td>
		<td>{!! implode('<br />',$remarks) !!}</td>
		<td>{!! implode('<br />',$regret_remarks) !!}</td>
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
                title: 'Regret Report',
				footer: true
            },
            {
                extend: 'csv',
				text:'Export To CSV',
                title: 'Regret Report',
				footer: true
            }
        ], 
		"columnDefs": [ {
        "orderable": false
        } ], 
	}); 
});
			
</script>