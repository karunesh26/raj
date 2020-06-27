@extends('template.template')
@section('content')
<?php
	//error_reporting(0);
?>
     <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1> Dashboard</h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('Dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Dashboard</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">




	<div class="row">
        	 <div class="col-xs-12">
			   @if(session()->has('success'))
                       <span class="7"><div class="alert alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong>
                        {{ session()->get('success') }}
                   </strong></div></span>
               @endif

                @if(session()->has('error'))
                       <span class="7"><div class="alert alert-danger"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><strong>
                        {{ session()->get('error') }}
                   </strong></div></span>
               @endif
               </div>
         </div>
		@if($role_id != 1)


		<div class="col-lg-12">
			<div class="box box-warning">
				<div class="col-xs-12">
					<h3>Work Detail</h3>
				</div>
            	<div class="box-body">
					<table class="table table-bordered table-striped">
						@if($role_id == 2 || $role_id == 3 || $role_id == 5)
						<thead>
							<tr>
								<th colspan="4"><center>Work Detail :-- {{ $emp_detail[0]->name }} </center></th>
								<th colspan="4"><center>Date :- {{ date('d-m-Y') }}</center></th>
							</tr>
							<tr>
								<th><center>Previous Pending Inquiry</center></th>
								<th><center>Inquiry Entry</center></th>
								<th><center>Inquiry Allot</center></th>
								<th><center>Calling</center></th>
								<th><center>Quotation Generate</center></th>
								<th><center>Revise Quotation</center></th>
								<th><center>Cancel Inquiry</center></th>
								<th><center>Total Pending Inquiry</center></th>
							</tr>
						</thead>
						<tbody>
							<tr align="center">
								<td>{{ $previous_pending_inq }}</td>
								<td>{{ $inquiry_total_per_day }}</td>
								<td>{{ $inquiry_allot }}</td>
								<td>{{ $call_inquiry }}</td>
								<td>{{ $generate_quot }}</td>
								<td>{{ $total_revise_quotation }}</td>
								<td>{{ $cancel_inq }}</td>
								<td>{{ $total_pending_inq }}</td>
							</tr>
						</tbody>
						@elseif($role_id == '4' || $role_id == '6' || $role_id == '11')
						<thead>
							<tr>
								<th colspan="3"><center>Work Detail :-- {{ $emp_detail[0]->name }} </center></th>
								<th colspan="2"><center>Date :- {{ date('d-m-Y') }}</center></th>
							</tr>
							<tr>
								<th><center>Previous Pending Follow-Up</center></th>
								<th><center>Allotment</center></th>
								<th><center>Follow-Up</center></th>
								<th><center>Total Follow-Up</center></th>
								<th><center>Pending Follow-Up</center></th>
							</tr>
						</thead>
						<tbody>
							<tr align="center">
								<td>{{ $previous_pending_followup }}</td>
								<td>{{ $allotment_total }}</td>
								<td>{{ $total_follow_up }}</td>
								<td>{{ $allotment_total+$total_follow_up }}</td>
								<td>{{ $pending_follow_up }}</td>
							</tr>
						</tbody>
						@endif
					</table>
				</div>
			</div>
		</div>
		@else
		<div class="col-lg-12">
			<div class="box box-warning">
				<div class="col-xs-12">
					<h3>Marketing Employee Work Detail ON : {{ date('d-m-Y') }}</h3>
				</div>
            	<div class="box-body">
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th><center>Employee Name</center></th>
								<th><center>Previous Pending Inquiry</center></th>
								<th><center>Inquiry Entry</center></th>
								<th><center>Inquiry Allot</center></th>
								<th><center>Calling</center></th>
								<th><center>Quotation Generate</center></th>
								<th><center>Revise Quotation</center></th>
								<th><center>Cancel Inquiry</center></th>
								<th><center>Total Pending Inquiry</center></th>
							</tr>
						</thead>
						<tbody>
						@foreach($emp_work_detail as $key=>$val)
							@php
								$date = date('Y-m-d');
								$previous_date = date('Y-m-d',strtotime($date." - 1 day"));

								$previous_inq = App\Models\Data_model::db_query("select count(*) as previous_pending_inq from `inquiry` where `first_quatation_id` = 0 AND `project_zone` IN (".$val->zone_id.") AND `delete_status` = 0 AND `remove_status`= 0 AND DATE(added_time) <= '".$previous_date."' ");
								$previous_pending_inq = $previous_inq[0]->previous_pending_inq;


								$total_inq_per_day = App\Models\Data_model::db_query("select count(*) as inquiry_total_per_day from `inquiry` where `added_by` = '".$val->id."' AND DATE(added_time) = '".$date."' ");
								$inquiry_total_per_day = $total_inq_per_day[0]->inquiry_total_per_day;


								$alloted_inquiry = App\Models\Data_model::db_query("select count(*) as inquiry_allot from `inquiry` where  DATE(added_time) = '".$date."' AND `project_zone` IN (".$val->zone_id.") ");
								$inquiry_allot = $alloted_inquiry[0]->inquiry_allot;

								$call_inquiry = App\Models\Data_model::db_query("select count(*) as call_inquiry from `inquiry_remark` where  date = '".$date."' AND `added_by`='".$val->id."' ");
								$call_inquiry = $call_inquiry[0]->call_inquiry;

								$generated_quotation = App\Models\Data_model::db_query("select count(*) as generate_quot from `quatation` where  DATE(added_time) = '".$date."' AND `added_by` = '".$val->id."' ");
								$generate_quot = $generated_quotation[0]->generate_quot;


								$total_pending_inquiry = App\Models\Data_model::db_query("select count(*) as total_pending_inq from `inquiry` where `first_quatation_id` = 0 AND `project_zone` IN (".$val->zone_id.") AND `delete_status` = 0 AND `remove_status`= 0 AND DATE(added_time) <= '".$date."' ");
								$total_pending_inq = $total_pending_inquiry[0]->total_pending_inq;

								$total_revise_quotation = App\Models\Data_model::db_query("select count(*) as total_revise_quotation from `revise_quatation` where DATE(added_time) = '".$date."' AND `added_by`= '".$val->id."' ");
								$total_revise_quotation = $total_revise_quotation[0]->total_revise_quotation;

								$cancel_inq = App\Models\Data_model::db_query("select count(*) as cancel_inq from `inquiry` where `delete_status` = '".$val->id."' AND cancel_date ='".$date."' ");
								$cancel_inq = $cancel_inq[0]->cancel_inq;
							@endphp

							<tr align="center">
								<td>{{ $val->name }}</td>
								<td>{{ $previous_pending_inq }}</td>
								<td>{{ $inquiry_total_per_day }}</td>
								<td>{{ $inquiry_allot }}</td>
								<td>{{ $call_inquiry }}</td>
								<td>{{ $generate_quot }}</td>
								<td>{{ $total_revise_quotation }}</td>
								<td>{{ $cancel_inq }}</td>
								<td>{{ $total_pending_inq }}</td>
							</tr>
						@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
		<div class="col-lg-12">
			<div class="box box-warning">
				<div class="col-xs-12">
					<h3>Sales Employee Work Detail ON : {{ date('d-m-Y') }}</h3>
				</div>
            	<div class="box-body">
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th><center>Employee Name</center></th>
								<th><center>Previous Pending Follow-Up</center></th>
								<th><center>Allotment</center></th>
								<th><center>Follow-Up</center></th>
								<th><center>Total Follow-Up</center></th>
								<th><center>Pending Follow-Up</center></th>
							</tr>
						</thead>
						<tbody>
							@foreach($sales_emp_detail as $key=>$val)
								@php
									$date = date('Y-m-d');
									$previous_date = date('Y-m-d',strtotime($date." - 1 day"));

									$day_array = array(0=>'Sunday',1=>'Monday',2=>'Tuesday',3=>'Wednesday',4=>'Thursday',5=>'Friday',6=>'Saturday');

									$holiday = App\Models\Data_model::retrive('minimum_days','*',array('type'=>'holiday'),'type');
									$h_day = $holiday[0]->days;

									if(date('l',strtotime($previous_date)) == $day_array[$h_day])
									{
										$previous_date = date('Y-m-d',strtotime($date." - 2 day"));
									}
									else
									{
										$previous_date = $previous_date;
									}



									$previous_pending_followup = App\Models\Data_model::db_query("select count(*) as previous_pending_followup ,`inquiry`.`inquiry_id`,  `quatation`.`quatation_id`, `quatation`.`quatation_no`, `follow_up`.`follow_up_status` from `inquiry`  inner join `follow_up` on `follow_up`.`inquiry_id` = `inquiry`.`inquiry_id` inner join `quatation` on `quatation`.`inquiry_id` = `inquiry`.`inquiry_id` where `quatation`.zone_id IN (".$val->zone_id.") AND `follow_up`.next_followup_date <= '".$previous_date."' and `inquiry`.`order_status` = 0 and `inquiry`.`regret_status` = 0 AND `inquiry`.`hot_list` = 0 AND `follow_up`.follow_up_status = 0 order by `follow_up`.`follow_up_id` desc ");

									$previous_pending_followup = $previous_pending_followup[0]->previous_pending_followup;

									$total_follow_up = App\Models\Data_model::db_query("select count(*) as total_follow_up ,`inquiry`.`inquiry_id`,  `quatation`.`quatation_id`, `quatation`.`quatation_no`, `follow_up`.`follow_up_status` from `inquiry`  inner join `follow_up` on `follow_up`.`inquiry_id` = `inquiry`.`inquiry_id` inner join `quatation` on `quatation`.`inquiry_id` = `inquiry`.`inquiry_id` where `quatation`.zone_id IN (".$val->zone_id.") AND `follow_up`.next_followup_date = '".$date."' and `inquiry`.`order_status` = 0 AND `inquiry`.`hot_list` = 0 and `inquiry`.`regret_status` = 0 order by `follow_up`.`follow_up_id` desc");

									$total_follow_up = $total_follow_up[0]->total_follow_up;

									$pending_follow_up = App\Models\Data_model::db_query("select count(*) as pending_follow_up ,`inquiry`.`inquiry_id`,  `quatation`.`quatation_id`, `quatation`.`quatation_no`, `follow_up`.`follow_up_status` from `inquiry` inner join `follow_up` on `follow_up`.`inquiry_id` = `inquiry`.`inquiry_id` inner join `quatation` on `quatation`.`inquiry_id` = `inquiry`.`inquiry_id` where `quatation`.zone_id IN (".$val->zone_id.") AND `follow_up`.next_followup_date = '".$date."'  and `follow_up`.follow_up_status = 0 AND `inquiry`.`hot_list` = 0 and `inquiry`.`order_status` = 0 and `inquiry`.`regret_status` = 0 order by `follow_up`.`follow_up_id` desc");

									$pending_follow_up = $pending_follow_up[0]->pending_follow_up;

									$allotment_total = App\Models\Data_model::db_query("select count(*) as allotment_total,`inquiry`.*, `quatation`.`quatation_id`, `quatation`.`quatation_no`, `quatation`.`follow_up_status` from `inquiry` inner join `quatation` on `quatation`.`inquiry_id` = `inquiry`.`inquiry_id` where `quatation`.zone_id IN (".$val->zone_id.") AND `quatation`.quatation_date = '".$previous_date."'  and `inquiry`.`order_status` = 0 and `inquiry`.`regret_status` = 0");

									$allotment_total = $allotment_total[0]->allotment_total;
								@endphp

							<tr align="center">
								<td>{{ $val->name }}</td>
								<td>{{ $previous_pending_followup }}</td>
								<td>{{ $allotment_total }}</td>
								<td>{{ $total_follow_up }}</td>
								<td>{{ $allotment_total+$total_follow_up }}</td>
								<td>{{ $pending_follow_up }}</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
		@endif


		<div id="prise_issue_notify_data">
	@if(! empty($prise_issue_notify))
    <div class="col-lg-12">
		<div class="box box-warning">
			<div class="col-xs-12">
				<h3>Price / Technical Issue</h3>
			</div>
            <div class="box-body">
				<table class="table table-bordered table-striped datatable">
					<thead>
						<tr>
							<th width="5%">Sr. No.</th>
							<th width="8%">Issue Date</th>
							<th width="10%">Quotation Number</th>
							<th width="15%">Client Name</th>
							<th width="15%">Inquiry For</th>
							<th width="20%">Remark</th>
							<th width="10%">Follow-Up By</th>
							<th width="10%">Allot To</th>
							<th width="15%">Manage</th>
						</tr>
					</thead>
					<tbody>
					@foreach($prise_issue_notify as $key=>$value)
						<tr>
							<td>{{ $key+1}}</td>
							<td>{{ date('d-m-Y',strtotime($value->added_date)) }}</td>
							<td>{{ $value->quotation_no}}</td>
							<td>{{ $value->prefix.' '.$value->name}}</td>
							<td>{{ $value->product_name }}</td>
							<td>{{ $value->remark}}</td>
							<td>{{ $value->follow_up_by}}</td>
							<td>{{ $value->allot_to}}</td>
							<td>
								<a href="{{ 'Follow_up/'.$utility->encode($value->inquiry_id) }}" target="_blank" class="btn bg-maroon btn-sm" ><i class="glyphicon glyphicon-eye-open icon-white"></i> View</a>

								<a href="{{ 'Dashboard/clear/'.$utility->encode($value->id).'/'.$utility->encode('prise') }}"  class="btn bg-purple btn-sm" ><i class="fa fa-window-close" aria-hidden="true"></i> Clear</a>
							</td>
						</tr>
					@endforeach

					</tbody>
				</table>
				</div>
			</div>
		</div>
		@endif
	</div>
	<div id="revise_notify_data">
		@if(! empty($revise_notify))
     <div class="col-lg-12">
		<div class="box box-warning">
			<div class="col-xs-12">
				<h3>Revise</h3>
			</div>
            <div class="box-body">
				<table class="table table-bordered table-striped datatable">
					<thead>
						<tr>
							<th width="5%">Sr. No.</th>
							<th width="8%">Revise Date</th>
							<th width="15%">Quotation Number</th>
							<th width="20%">Client Name</th>
							<th width="15%">Inquiry For</th>
							<th width="15%">Remark</th>
							<th width="10%">Follow-Up By</th>
							<th width="10%">Allot To</th>
							<th width="15%">Manage</th>
						</tr>
					</thead>
					<tbody>
					@foreach ($revise_notify as $key=>$value)
						<tr>
							<td>{{ $key+1}}</td>
							<td>{{ date('d-m-Y',strtotime($value->added_date)) }}</td>
							<td>{{ $value->quotation_no}}</td>
							<td>{{ $value->prefix.' '.$value->name}}</td>
							<td>{{ $value->product_name }}</td>
							<td>{{ $value->remark }}</td>
							<td>{{ $value->follow_up_by }}</td>
							<td>{{ $value->allot_to }}</td>
							<td>
								<a href="{{ 'Follow_up/'.$utility->encode($value->inquiry_id) }}" target="_blank" class="btn bg-maroon btn-sm" ><i class="glyphicon glyphicon-eye-open icon-white"></i> View</a>

								<a href="{{ 'Dashboard/clear/'.$utility->encode($value->id).'/'.$utility->encode('revise') }}"  class="btn bg-purple btn-sm" ><i class="fa fa-window-close" aria-hidden="true"></i> Clear</a>
								<a  target="_blank" href="{{ 'Quatation/revise_quatation_desktop/'.$utility->encode($value->inquiry_id) }}"  class="btn bg-green btn-sm" ><i class="fa circle-notch" aria-hidden="true"></i> Revise</a>
							</td>
						</tr>
					@endforeach
					</tbody>
				</table>
			</div>
		</div>

		</div>
		@endif
	</div>

    </section>
    <!-- /.content -->
<script type="text/javascript">
jQuery(document).ready(function($){

	$('.datatable').DataTable({
	  'paging'      : true,
	  'lengthChange': true,
	  'searching'   : true,
	  'ordering'    : true,
	  'info'        : true,
	  'autoWidth'   : true,
	"pageLength":  100,
	})
});
</script>
@endsection
