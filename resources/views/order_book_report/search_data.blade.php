<link href="{{ URL::asset('external/css/datatable/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('external/css/datatable/buttons.dataTables.min.css') }}" rel="stylesheet">
<div class="table-responsive">
@if($report_type == 'state')
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
	<th>Order Book By</th>
	<th>Quotation By</th>
</tr>
</thead>
<tbody>
@foreach($state as $key=>$value)
	@php
		if($from_date != ''){
			$f_date = date('Y-m-d',strtotime($from_date));
			$t_date = date('Y-m-d',strtotime($to_date));

			$result = App\Models\Data_model::db_query("select `order_book`.*,`inquiry`.project_value,`quatation`.quatation_no,`quatation`.quatation_id,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`customer_master`.email,`customer_master`.email_2,`country_master`.country_name,`state_master`.state_name,`city_master`.city_name,`product_master`.product_name,`employee`.name as employee_name,`users`.username as quot_person from `order_book` INNER JOIN `inquiry` ON `inquiry`.inquiry_id = `order_book`.inquiry_id INNER JOIN `quatation` ON `quatation`.inquiry_id = `inquiry`.inquiry_id INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id LEFT JOIN `country_master` ON `country_master`.country_id = `customer_master`.country_id LEFT JOIN `state_master` ON `state_master`.state_id = `customer_master`.state_id LEFT JOIN `city_master` ON `city_master`.city_id = `customer_master`.city_id INNER JOIN `product_master` ON `product_master`.product_id = `inquiry`.product_id INNER JOIN `employee` ON `employee`.emp_id = `order_book`.order_by INNER JOIN `users` ON `users`.id = `quatation`.added_by where `order_book`.order_book_date >= '".$f_date."' AND `order_book`.order_book_date <= '".$t_date."' AND `customer_master`.state_id = '".$value->state_id."' AND `order_book`.cancel_by = 0");
		}else{
			$result = App\Models\Data_model::db_query("select `order_book`.*,`inquiry`.project_value,`quatation`.quatation_no,`quatation`.quatation_id,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`customer_master`.email,`customer_master`.email_2,`country_master`.country_name,`state_master`.state_name,`city_master`.city_name,`product_master`.product_name,`employee`.name as employee_name,`users`.username as quot_person from `order_book` INNER JOIN `inquiry` ON `inquiry`.inquiry_id = `order_book`.inquiry_id INNER JOIN `quatation` ON `quatation`.inquiry_id = `inquiry`.inquiry_id INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id LEFT JOIN `country_master` ON `country_master`.country_id = `customer_master`.country_id LEFT JOIN `state_master` ON `state_master`.state_id = `customer_master`.state_id LEFT JOIN `city_master` ON `city_master`.city_id = `customer_master`.city_id INNER JOIN `product_master` ON `product_master`.product_id = `inquiry`.product_id INNER JOIN `employee` ON `employee`.emp_id = `order_book`.order_by INNER JOIN `users` ON `users`.id = `quatation`.added_by where `customer_master`.state_id = '".$value->state_id."' AND `order_book`.cancel_by = 0 ");
		}
	@endphp
	@if(! empty($result))
	<tr class="danger">
		<td colspan="12"><center><b>{{ $value->state_name }}</b></center></td>
	</tr>
	@endif
	@foreach($result as $key=>$val)
	@php
		$order_detail = App\Models\Data_model::db_query("select `quot_id`,`rquot_id` from `order_book` where `order_id` = '".$val->order_id."' ");

		if($order_detail[0]->quot_id != 0)
		{
			$quot_num = App\Models\Data_model::db_query("select `quatation_no` from `quatation` where `quatation_id` = '".$order_detail[0]->quot_id."' ");
			$q_no = $quot_num[0]->quatation_no;
		}
		else
		{
			$quot_num = App\Models\Data_model::db_query("select `revise_quatation_no` from `revise_quatation` where `revise_id` = '".$order_detail[0]->rquot_id."' ");
			$q_no = $quot_num[0]->revise_quatation_no;
		}
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
			$get_q=DB::table('quatation')->where('quatation_id',$val->quatation_id)->select('total_amount')->orderBy('q_no','DESC')->limit(1)->get();
			$project_value = number_format((float)$get_q[0]->total_amount,2,'.','');

	@endphp
	<tr class="info">
		<td>{{ $key+1 }}</td>
		<td>{{ $q_no }}</td>
		<td>{{ $val->prefix.' '.$val->name }}</td>
		<td>{!! implode('<br />',$mobile) !!}</td>
		<td>{!! implode('<br />',$email) !!}</td>
		<td>{{ $val->country_name }}</td>
		<td>{{ $val->state_name }}</td>
		<td>{{ $val->city_name }}</td>
		<td>{{ $val->product_name }}</td>
		<td>{{ $project_value }}</td>
		<td>{{ $val->employee_name }}</td>
		<td>{{ $val->quot_person }}</td>
	</tr>
	@endforeach
@endforeach
</tbody>
</table>
@elseif($report_type == 'order_by')
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
	<th>Order Book By</th>
	<th>Quotation By</th>
</tr>
</thead>
<tbody>
@foreach($order_by as $key=>$value)
	@php
		if($from_date != ''){
			$f_date = date('Y-m-d',strtotime($from_date));
			$t_date = date('Y-m-d',strtotime($to_date));

			$result = App\Models\Data_model::db_query("select `order_book`.*,`inquiry`.project_value,`quatation`.quatation_no,`quatation`.quatation_id,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`customer_master`.email,`customer_master`.email_2,`country_master`.country_name,`state_master`.state_name,`city_master`.city_name,`product_master`.product_name,`employee`.name as employee_name,`users`.username as quot_person from `order_book` INNER JOIN `inquiry` ON `inquiry`.inquiry_id = `order_book`.inquiry_id INNER JOIN `quatation` ON `quatation`.inquiry_id = `inquiry`.inquiry_id INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id LEFT JOIN `country_master` ON `country_master`.country_id = `customer_master`.country_id LEFT JOIN `state_master` ON `state_master`.state_id = `customer_master`.state_id LEFT JOIN `city_master` ON `city_master`.city_id = `customer_master`.city_id INNER JOIN `product_master` ON `product_master`.product_id = `inquiry`.product_id INNER JOIN `employee` ON `employee`.emp_id = `order_book`.order_by INNER JOIN `users` ON `users`.id = `quatation`.added_by where `order_book`.order_book_date >= '".$f_date."' AND `order_book`.order_book_date <= '".$t_date."' AND `order_book`.order_by = '".$value->order_by."' AND `order_book`.cancel_by = 0 ");
		}else{
			$result = App\Models\Data_model::db_query("select `order_book`.*,`inquiry`.project_value,`quatation`.quatation_no,`quatation`.quatation_id,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`customer_master`.email,`customer_master`.email_2,`country_master`.country_name,`state_master`.state_name,`city_master`.city_name,`product_master`.product_name,`employee`.name as employee_name,`users`.username as quot_person from `order_book` INNER JOIN `inquiry` ON `inquiry`.inquiry_id = `order_book`.inquiry_id INNER JOIN `quatation` ON `quatation`.inquiry_id = `inquiry`.inquiry_id INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id LEFT JOIN `country_master` ON `country_master`.country_id = `customer_master`.country_id LEFT JOIN `state_master` ON `state_master`.state_id = `customer_master`.state_id LEFT JOIN `city_master` ON `city_master`.city_id = `customer_master`.city_id INNER JOIN `product_master` ON `product_master`.product_id = `inquiry`.product_id INNER JOIN `employee` ON `employee`.emp_id = `order_book`.order_by INNER JOIN `users` ON `users`.id = `quatation`.added_by where `order_book`.order_by = '".$value->order_by."' AND `order_book`.cancel_by = 0 ");
		}
	@endphp
	@if(! empty($result))
	<tr class="danger">
		<td colspan="12"><center><b>{{ $value->name }}</b></center></td>
	</tr>
	@endif
	@foreach($result as $key=>$val)
	@php
		$order_detail = App\Models\Data_model::db_query("select `quot_id`,`rquot_id` from `order_book` where `order_id` = '".$val->order_id."' ");

		if($order_detail[0]->quot_id != 0)
		{
			$quot_num = App\Models\Data_model::db_query("select `quatation_no` from `quatation` where `quatation_id` = '".$order_detail[0]->quot_id."' ");
			$q_no = $quot_num[0]->quatation_no;
		}
		else
		{
			$quot_num = App\Models\Data_model::db_query("select `revise_quatation_no` from `revise_quatation` where `revise_id` = '".$order_detail[0]->rquot_id."' ");
			$q_no = $quot_num[0]->revise_quatation_no;
		}

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

			$get_q=DB::table('quatation')->where('quatation_id',$val->quatation_id)->select('total_amount')->orderBy('q_no','DESC')->limit(1)->get();
			$project_value = number_format((float)$get_q[0]->total_amount,2,'.','');

	@endphp
	<tr class="info">
		<td>{{ $key+1 }}</td>
		<td>{{ $q_no }}</td>
		<td>{{ $val->prefix.' '.$val->name }}</td>
		<td>{!! implode('<br />',$mobile) !!}</td>
		<td>{!! implode('<br />',$email) !!}</td>
		<td>{{ $val->country_name }}</td>
		<td>{{ $val->state_name }}</td>
		<td>{{ $val->city_name }}</td>
		<td>{{ $val->product_name }}</td>
		<td>{{ $project_value }}</td>
		<td>{{ $val->employee_name }}</td>
		<td>{{ $val->quot_person }}</td>
	</tr>
	@endforeach
@endforeach
</tbody>
</table>
@elseif($report_type == 'source_wise')
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
	<th>Order Book By</th>
	<th>Quotation By</th>
</tr>
</thead>
<tbody>
@foreach($source as $key=>$value)
	@php
		if($from_date != ''){
			$f_date = date('Y-m-d',strtotime($from_date));
			$t_date = date('Y-m-d',strtotime($to_date));

			$result = App\Models\Data_model::db_query("select `order_book`.*,`inquiry`.project_value,`quatation`.quatation_no,`quatation`.quatation_id,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`customer_master`.email,`customer_master`.email_2,`country_master`.country_name,`state_master`.state_name,`city_master`.city_name,`product_master`.product_name,`employee`.name as employee_name,`users`.username as quot_person from `order_book` INNER JOIN `inquiry` ON `inquiry`.inquiry_id = `order_book`.inquiry_id INNER JOIN `quatation` ON `quatation`.inquiry_id = `inquiry`.inquiry_id INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id LEFT JOIN `country_master` ON `country_master`.country_id = `customer_master`.country_id LEFT JOIN `state_master` ON `state_master`.state_id = `customer_master`.state_id LEFT JOIN `city_master` ON `city_master`.city_id = `customer_master`.city_id INNER JOIN `product_master` ON `product_master`.product_id = `inquiry`.product_id INNER JOIN `employee` ON `employee`.emp_id = `order_book`.order_by INNER JOIN `users` ON `users`.id = `quatation`.added_by where `order_book`.order_book_date >= '".$f_date."' AND `inquiry`.source_id = '".$value->source_id."' AND `order_book`.cancel_by = 0 ");
		}else{
			$result = App\Models\Data_model::db_query("select `order_book`.*,`inquiry`.project_value,`quatation`.quatation_no,`quatation`.quatation_id,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`customer_master`.email,`customer_master`.email_2,`country_master`.country_name,`state_master`.state_name,`city_master`.city_name,`product_master`.product_name,`employee`.name as employee_name,`users`.username as quot_person from `order_book` INNER JOIN `inquiry` ON `inquiry`.inquiry_id = `order_book`.inquiry_id INNER JOIN `quatation` ON `quatation`.inquiry_id = `inquiry`.inquiry_id INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id LEFT JOIN `country_master` ON `country_master`.country_id = `customer_master`.country_id LEFT JOIN `state_master` ON `state_master`.state_id = `customer_master`.state_id LEFT JOIN `city_master` ON `city_master`.city_id = `customer_master`.city_id INNER JOIN `product_master` ON `product_master`.product_id = `inquiry`.product_id INNER JOIN `employee` ON `employee`.emp_id = `order_book`.order_by INNER JOIN `users` ON `users`.id = `quatation`.added_by where `inquiry`.source_id = '".$value->source_id."' AND `order_book`.cancel_by = 0 ");
		}
	@endphp
	@if(! empty($result))
	<tr class="danger">
		<td colspan="12"><center><b>{{ $value->source_name }}</b></center></td>
	</tr>
	@endif
	@foreach($result as $key=>$val)
	@php
		$order_detail = App\Models\Data_model::db_query("select `quot_id`,`rquot_id` from `order_book` where `order_id` = '".$val->order_id."' ");

		if($order_detail[0]->quot_id != 0)
		{
			$quot_num = App\Models\Data_model::db_query("select `quatation_no` from `quatation` where `quatation_id` = '".$order_detail[0]->quot_id."' ");
			$q_no = $quot_num[0]->quatation_no;
		}
		else
		{
			$quot_num = App\Models\Data_model::db_query("select `revise_quatation_no` from `revise_quatation` where `revise_id` = '".$order_detail[0]->rquot_id."' ");
			$q_no = $quot_num[0]->revise_quatation_no;
		}

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

		
			$get_q=DB::table('quatation')->where('quatation_id',$val->quatation_id)->select('total_amount')->orderBy('q_no','DESC')->limit(1)->get();
			$project_value = number_format((float)$get_q[0]->total_amount,2,'.','');
	
	@endphp
	<tr class="info">
		<td>{{ $key+1 }}</td>
		<td>{{ $q_no }}</td>
		<td>{{ $val->prefix.' '.$val->name }}</td>
		<td>{!! implode('<br />',$mobile) !!}</td>
		<td>{!! implode('<br />',$email) !!}</td>
		<td>{{ $val->country_name }}</td>
		<td>{{ $val->state_name }}</td>
		<td>{{ $val->city_name }}</td>
		<td>{{ $val->product_name }}</td>
		<td>{{ $project_value }}</td>
		<td>{{ $val->employee_name }}</td>
		<td>{{ $val->quot_person }}</td>
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
	<th>Order Book By</th>
	<th>Quotation By</th>
</tr>
</thead>
<tbody>
@foreach($month as $key=>$value)
	@php
		if($from_date != ''){
			$f_date = date('Y-m-d',strtotime($from_date));
			$t_date = date('Y-m-d',strtotime($to_date));

			$result = App\Models\Data_model::db_query("select `order_book`.*,`inquiry`.project_value,`quatation`.quatation_no,`quatation`.quatation_id,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`customer_master`.email,`customer_master`.email_2,`country_master`.country_name,`state_master`.state_name,`city_master`.city_name,`product_master`.product_name,`employee`.name as employee_name,`users`.username as quot_person from `order_book` INNER JOIN `inquiry` ON `inquiry`.inquiry_id = `order_book`.inquiry_id INNER JOIN `quatation` ON `quatation`.inquiry_id = `inquiry`.inquiry_id INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id LEFT JOIN `country_master` ON `country_master`.country_id = `customer_master`.country_id LEFT JOIN `state_master` ON `state_master`.state_id = `customer_master`.state_id LEFT JOIN `city_master` ON `city_master`.city_id = `customer_master`.city_id INNER JOIN `product_master` ON `product_master`.product_id = `inquiry`.product_id INNER JOIN `employee` ON `employee`.emp_id = `order_book`.order_by INNER JOIN `users` ON `users`.id = `quatation`.added_by where `order_book`.order_book_date >= '".$f_date."' AND `order_book`.order_book_date <= '".$t_date."' AND MONTH(order_book_date) = '".$value->month."' AND `order_book`.cancel_by = 0 ");
		}else{
			$result = App\Models\Data_model::db_query("select `order_book`.*,`inquiry`.project_value,`quatation`.quatation_no,`quatation`.quatation_id,`customer_master`.prefix,`customer_master`.name,`customer_master`.mobile,`customer_master`.mobile_2,`customer_master`.mobile_3,`customer_master`.email,`customer_master`.email_2,`country_master`.country_name,`state_master`.state_name,`city_master`.city_name,`product_master`.product_name,`employee`.name as employee_name,`users`.username as quot_person from `order_book` INNER JOIN `inquiry` ON `inquiry`.inquiry_id = `order_book`.inquiry_id INNER JOIN `quatation` ON `quatation`.inquiry_id = `inquiry`.inquiry_id INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id LEFT JOIN `country_master` ON `country_master`.country_id = `customer_master`.country_id LEFT JOIN `state_master` ON `state_master`.state_id = `customer_master`.state_id LEFT JOIN `city_master` ON `city_master`.city_id = `customer_master`.city_id INNER JOIN `product_master` ON `product_master`.product_id = `inquiry`.product_id INNER JOIN `employee` ON `employee`.emp_id = `order_book`.order_by INNER JOIN `users` ON `users`.id = `quatation`.added_by where MONTH(order_book_date) = '".$value->month."' AND `order_book`.cancel_by = 0 ");
		}
	@endphp
	@if(! empty($result))
	<tr class="danger">
		<td colspan="12"><center><b>{{ date("F", mktime(0, 0, 0, $value->month, 1)) }} - {{ date('Y') }}</b></center></td>
	</tr>
	@endif
	@foreach($result as $key=>$val)
	@php
		$order_detail = App\Models\Data_model::db_query("select `quot_id`,`rquot_id` from `order_book` where `order_id` = '".$val->order_id."' ");

		if($order_detail[0]->quot_id != 0)
		{
			$quot_num = App\Models\Data_model::db_query("select `quatation_no` from `quatation` where `quatation_id` = '".$order_detail[0]->quot_id."' ");
			$q_no = $quot_num[0]->quatation_no;
		}
		else
		{
			$quot_num = App\Models\Data_model::db_query("select `revise_quatation_no` from `revise_quatation` where `revise_id` = '".$order_detail[0]->rquot_id."' ");
			$q_no = $quot_num[0]->revise_quatation_no;
		}

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

			$get_q=DB::table('quatation')->where('quatation_id',$val->quatation_id)->select('total_amount')->orderBy('q_no','DESC')->limit(1)->get();
			$project_value = number_format((float)$get_q[0]->total_amount,2,'.','');
		

	@endphp
	<tr class="info">
		<td>{{ $key+1 }}</td>
		<td>{{ $q_no }}</td>
		<td>{{ $val->prefix.' '.$val->name }}</td>
		<td>{!! implode('<br />',$mobile) !!}</td>
		<td>{!! implode('<br />',$email) !!}</td>
		<td>{{ $val->country_name }}</td>
		<td>{{ $val->state_name }}</td>
		<td>{{ $val->city_name }}</td>
		<td>{{ $val->product_name }}</td>
		<td>{{ $project_value }}</td>
		<td>{{ $val->employee_name }}</td>
		<td>{{ $val->quot_person }}</td>
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