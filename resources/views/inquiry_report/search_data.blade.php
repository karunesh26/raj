@php error_reporting(0); @endphp
<link href="{{ URL::asset('external/css/datatable/jquery.dataTables.min.css') }}" rel="stylesheet">
<link href="{{ URL::asset('external/css/datatable/buttons.dataTables.min.css') }}" rel="stylesheet">
@if(empty($state))
	@if(! in_array('all',$source))
		<div class="table-responsive">
			<table id="datatable1" class="table table-bordered table-striped" style="width:100%">
				<thead>
					<tr class="info">
						<th width="4%">Sr No.</th>
						<th width="7%">Date</th>
						@php
							foreach($source as $key=>$val)
							{
								$get_source = App\Models\Data_model::db_query("select * from `source_master` where source_id = ".$val." ");
								if(! empty($get_source))
								{
									echo '<th>'.$get_source[0]->source_name.'</th>';
								}
							}
						@endphp
						<th>Total Inquiry</th>
						<th>Pending Inquiry</th>
						<th>Active Inquiry</th>
						<th>Cancel Inquiry</th>
						<th>Delete Inquiry</th>
					</tr>
				</thead>

				<tbody>
					<?php
						$inq_total = 0;
						$pending_inq_total = 0;
						$active_inq_total = 0;
						$cancel_inq_total = 0;
						$delete_inq_total = 0;

						if (empty($date_arr)) {
							$get_all_date = App\Models\Data_model::db_query("SELECT `inquiry_date` FROM `inquiry` group by `inquiry_date` ");
							$all_date = array();
							foreach($get_all_date as $key=>$val) {
								$all_date[] = $val->inquiry_date;
							}
						} else {
							$all_date = $date_arr;
						}
						$all_source = implode(",",$source);

						foreach($all_date as $key=>$val)
						{
							$pending_inq = App\Models\Data_model::db_query("SELECT count('*') as inq_pending FROM `inquiry` where `source_id` IN(".$all_source.") AND `inquiry_date`='".$val."' AND first_quatation_id = 0 AND delete_status = 0 AND remove_status = 0  ");

							$active_inq = App\Models\Data_model::db_query("SELECT count('*') as inq_active FROM `inquiry` where `source_id` IN(".$all_source.") AND `inquiry_date`='".$val."' AND first_quatation_id != 0 ");

							$cancel_inq = App\Models\Data_model::db_query("SELECT count('*') as inq_cancel FROM `inquiry` where `source_id` IN(".$all_source.") AND `inquiry_date`='".$val."' AND delete_status != 0 ");

							$delete_inq = App\Models\Data_model::db_query("SELECT count('*') as inq_delete FROM `inquiry` where `source_id` IN(".$all_source.") AND `inquiry_date`='".$val."' AND remove_status != 0 ");
						?>
						<tr>
							<td>{{ $key+1 }}</td>
							<td>{{ date("d-m-Y",strtotime($val)) }}</td>
							<?php
							$sub_total = 0;
							foreach($source as $key=>$sou)
							{
								$get_inq_cnt = App\Models\Data_model::db_query("SELECT count('*') as inq_cnt FROM `inquiry` where `source_id`=".$sou." AND `inquiry_date`='".$val."'  ");
							?>
								@if(! empty($get_inq_cnt))
									<td>{{ $get_inq_cnt[0]->inq_cnt }}</td>
									@php  $sub_total += $get_inq_cnt[0]->inq_cnt; @endphp
								@else
									<td>-</td>
								@endif
							<?php
							}
							?>
							<td>{{ $sub_total }}</td>
							@php
								$inq_total += $sub_total;
								$pending_inq_total += $pending_inq[0]->inq_pending;
								$active_inq_total += $active_inq[0]->inq_active;
								$cancel_inq_total += $cancel_inq[0]->inq_cancel;
								$delete_inq_total += $delete_inq[0]->inq_delete;
							@endphp
							<td>{{ $pending_inq[0]->inq_pending }}</td>
							<td>{{ $active_inq[0]->inq_active }}</td>
							<td>{{ $cancel_inq[0]->inq_cancel }}</td>
							<td>{{ $delete_inq[0]->inq_delete }}</td>
						</tr>
						<?php
						}
						?>
				</tbody>

				<tfoot>
					<tr class="success">
						<td></td>
						<td><b>Total :-</b></td>
						<?php
							for($t=0; $t<count($source); $t++)
							{
								if(empty($date_arr))
								{
									$get_sou_tot = App\Models\Data_model::db_query("SELECT count('*') as inq_cnt FROM `inquiry` where `source_id`=".$source[$t]."  ");
								}
								else
								{
									$get_sou_tot = App\Models\Data_model::db_query("SELECT count('*') as inq_cnt FROM `inquiry` where `source_id`=".$source[$t]." AND inquiry_date >= '".$from_date."' AND inquiry_date <= '".$to_date."' ");
								}
								echo '<td>'.$get_sou_tot[0]->inq_cnt.'</td>';
							}
						?>
						<td><b>{{ $inq_total }}</b></td>
						<td><b>{{ $pending_inq_total }}</b></td>
						<td><b>{{ $active_inq_total }}</b></td>
						<td><b>{{ $cancel_inq_total }}</b></td>
						<td><b>{{ $delete_inq_total }}</b></td>
					</tr>
				</tfoot>
			</table>
		</div>
	@else
		<?php
			if (empty($date_arr)) {
				$get_all_date = App\Models\Data_model::db_query("SELECT `inquiry_date` FROM `inquiry` group by `inquiry_date` Order By inquiry_date asc ");
				$all_date = array();
				foreach ($get_all_date as $key=>$val) {
					$all_date[] = $val->inquiry_date;
				}
			} else {
				$all_date = $date_arr;
			}
		?>
		<div class="table-responsive">
			<table id="datatable1" class="table table-bordered table-striped" style="width:100%">
				<thead>
					<tr>
						<th width="4%">Sr No.</th>
						<th width="10%">Date</th>
						@php
						$get_source = App\Models\Data_model::db_query("select * from `source_master` where delete_status = 0 ");
							$source_id = array();
							foreach($get_source as $key=>$val)
							{
								if (empty($date_arr)) {
									$get_inq_cnt = App\Models\Data_model::db_query("SELECT count('*') as inq_cnt FROM `inquiry` where `source_id`=".$val->source_id." ");

								} else {
									$get_inq_cnt = App\Models\Data_model::db_query("SELECT count('*') as inq_cnt FROM `inquiry` where `source_id`=".$val->source_id." AND `inquiry_date` >= '".$from_date."' AND `inquiry_date` <= '".$to_date."'  ");
								}

								if ($get_inq_cnt[0]->inq_cnt != 0) {
									$source_id[]=$val->source_id;
									echo '<th>'.$val->source_name.'</th>';
								}
							}
							$all_source = implode(",",$source_id);
							if($all_source == ''){
								continue;
							}
						@endphp
						<th>Total Inquiry</th>
						<th>Pending Inquiry</th>
						<th>Active Inquiry</th>
						<th>Cancel Inquiry</th>
						<th>Delete Inquiry</th>
					</tr>
				</thead>
				<tbody>
					<?php
						$inq_total = 0;
						$pending_inq_total = 0;
						$active_inq_total = 0;
						$cancel_inq_total = 0;
						$delete_inq_total = 0;

						foreach($all_date as $key=>$val)
						{
							$pending_inq = App\Models\Data_model::db_query("SELECT count('*') as inq_pending FROM `inquiry` where `source_id` IN(".$all_source.") AND `inquiry_date`='".$val."' AND first_quatation_id = 0 AND delete_status = 0 AND remove_status = 0  ");

							$active_inq = App\Models\Data_model::db_query("SELECT count('*') as inq_active FROM `inquiry` where `source_id` IN(".$all_source.") AND `inquiry_date`='".$val."' AND first_quatation_id != 0 ");

							$cancel_inq = App\Models\Data_model::db_query("SELECT count('*') as inq_cancel FROM `inquiry` where `source_id` IN(".$all_source.") AND `inquiry_date`='".$val."' AND delete_status != 0 ");

							$delete_inq = App\Models\Data_model::db_query("SELECT count('*') as inq_delete FROM `inquiry` where `source_id` IN(".$all_source.") AND `inquiry_date`='".$val."' AND remove_status != 0 ");
						?>

							<tr>
								<td>{{ $key+1 }}</td>
								<td>{{ date("d-m-Y",strtotime($val)) }}</td>
								<?php
								$sub_total = 0;
								foreach($source_id as $key=>$sou)
								{
									$get_inq_cnt = App\Models\Data_model::db_query("SELECT count('*') as inq_cnt FROM `inquiry` where `source_id`=".$sou." AND `inquiry_date`='".$val."'  ");
								?>
									@if(! empty($get_inq_cnt))
										<td>{{ $get_inq_cnt[0]->inq_cnt }}</td>
										@php  $sub_total += $get_inq_cnt[0]->inq_cnt; @endphp
									@else
										<td>-</td>
									@endif
								<?php
								}
								?>
								<td>{{ $sub_total }}</td>
								@php
									$inq_total += $sub_total;
									$pending_inq_total += $pending_inq[0]->inq_pending;
									$active_inq_total += $active_inq[0]->inq_active;
									$cancel_inq_total += $cancel_inq[0]->inq_cancel;
									$delete_inq_total += $delete_inq[0]->inq_delete;
								@endphp
								<td>{{ $pending_inq[0]->inq_pending }}</td>
								<td>{{ $active_inq[0]->inq_active }}</td>
								<td>{{ $cancel_inq[0]->inq_cancel }}</td>
								<td>{{ $delete_inq[0]->inq_delete }}</td>
							</tr>
					<?php
						}
					?>
				</tbody>
				<tfoot>
					<tr class="success">
						<td></td>
						<td><b>Total :-</b></td>
						<?php
							for($t=0; $t<count($source_id); $t++)
							{
								if(empty($date_arr))
								{
									$get_sou_tot = App\Models\Data_model::db_query("SELECT count('*') as inq_cnt FROM `inquiry` where `source_id`=".$source_id[$t]."  ");
								}
								else
								{
									$get_sou_tot = App\Models\Data_model::db_query("SELECT count('*') as inq_cnt FROM `inquiry` where `source_id`=".$source_id[$t]." AND inquiry_date >= '".$from_date."' AND inquiry_date <= '".$to_date."' ");
								}
								echo '<td>'.$get_sou_tot[0]->inq_cnt.'</td>';
							}
						?>
						<td><b>{{ $inq_total }}</b></td>
						<td><b>{{ $pending_inq_total }}</b></td>
						<td><b>{{ $active_inq_total }}</b></td>
						<td><b>{{ $cancel_inq_total }}</b></td>
						<td><b>{{ $delete_inq_total }}</b></td>
					</tr>
				</tfoot>
			</table>
		</div>
	@endif
@else

	<?php
		if (empty($date_arr)) {
			$get_all_date = App\Models\Data_model::db_query("SELECT `inquiry_date` FROM `inquiry` group by `inquiry_date` Order By inquiry_date asc ");
			$all_date = array();
			foreach ($get_all_date as $key=>$val) {
				$all_date[] = $val->inquiry_date;
			}
		} else {
			$all_date = $date_arr;
		}
	?>

	@foreach($state as $key=>$value)
		@if(! in_array('all',$source))
			<div class="table-responsive">
				<table id="datatable1" class="table table-bordered table-striped" style="width:100%">
					<thead>
						<tr class="info">
							<th width="4%">Sr No.</th>
							<th width="7%">Date</th>
							@php
								foreach($source as $key=>$val)
								{
									$get_source = App\Models\Data_model::db_query("select * from `source_master` where source_id = ".$val." ");
									if(! empty($get_source))
									{
										echo '<th>'.$get_source[0]->source_name.'</th>';
									}
								}
							@endphp
							<th>Total Inquiry</th>
							<th>Pending Inquiry</th>
							<th>Active Inquiry</th>
							<th>Cancel Inquiry</th>
							<th>Delete Inquiry</th>
						</tr>
					</thead>

					<tbody>
						<tr class="danger">
							<td colspan="<?php echo count($source)+7 ?>"><b><center>{{ $value->state_name }}</center></b></td>
						</tr>

						<?php
							$inq_total = 0;
							$pending_inq_total = 0;
							$active_inq_total = 0;
							$cancel_inq_total = 0;
							$delete_inq_total = 0;

							$all_source = implode(",",$source);
							foreach($all_date as $key=>$val)
							{
								$pending_inq = App\Models\Data_model::db_query("SELECT count('*') as inq_pending,`customer_master`.state_id FROM `inquiry` INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id where `source_id` IN(".$all_source.") AND `inquiry_date`='".$val."' AND first_quatation_id = 0 AND delete_status = 0 AND remove_status = 0 AND `customer_master`.state_id = '".$value->state_id."' ");

								$active_inq = App\Models\Data_model::db_query("SELECT count('*') as inq_active,`customer_master`.state_id FROM `inquiry` INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id where `source_id` IN(".$all_source.") AND `inquiry_date`='".$val."' AND first_quatation_id != 0 AND `customer_master`.state_id = '".$value->state_id."' ");

								$cancel_inq = App\Models\Data_model::db_query("SELECT count('*') as inq_cancel,`customer_master`.state_id FROM `inquiry` INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id where `source_id` IN(".$all_source.") AND `inquiry_date`='".$val."' AND delete_status != 0 AND `customer_master`.state_id = '".$value->state_id."' ");

								$delete_inq = App\Models\Data_model::db_query("SELECT count('*') as inq_delete,`customer_master`.state_id FROM `inquiry` INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id where `source_id` IN(".$all_source.") AND `inquiry_date`='".$val."' AND remove_status != 0 AND `customer_master`.state_id = '".$value->state_id."' ");
						?>
						<tr>
							<td>{{ $key+1 }}</td>
							<td>{{ date("d-m-Y",strtotime($val)) }}</td>
							<?php
							$sub_total = 0;
							foreach($source as $key=>$sou)
							{
								$get_inq_cnt = App\Models\Data_model::db_query("SELECT count('*') as inq_cnt,`customer_master`.state_id FROM `inquiry` INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id where `source_id`=".$sou." AND `inquiry_date`='".$val."' AND `customer_master`.state_id = '".$value->state_id."'  ");
							?>
								@if(! empty($get_inq_cnt))
									<td>{{ $get_inq_cnt[0]->inq_cnt }}</td>
									@php  $sub_total += $get_inq_cnt[0]->inq_cnt; @endphp
								@else
									<td>-</td>
								@endif
						<?php
							}
						?>
							<td>{{ $sub_total }}</td>
							@php
								$inq_total += $sub_total;
								$pending_inq_total += $pending_inq[0]->inq_pending;
								$active_inq_total += $active_inq[0]->inq_active;
								$cancel_inq_total += $cancel_inq[0]->inq_cancel;
								$delete_inq_total += $delete_inq[0]->inq_delete;
							@endphp
							<td>{{ $pending_inq[0]->inq_pending }}</td>
							<td>{{ $active_inq[0]->inq_active }}</td>
							<td>{{ $cancel_inq[0]->inq_cancel }}</td>
							<td>{{ $delete_inq[0]->inq_delete }}</td>
						</tr>
					<?php
					}
					?>
					</tbody>

					<tfoot>
						<tr class="success">
							<td></td>
							<td><b>Total :-</b></td>
							<?php
								for($t=0; $t<count($source); $t++)
								{
									if(empty($date_arr))
									{
										$get_sou_tot = App\Models\Data_model::db_query("SELECT count('*') as inq_cnt,`customer_master`.state_id FROM `inquiry` INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id where `source_id`=".$source[$t]." AND `customer_master`.state_id = '".$value->state_id."'  ");
									}
									else
									{
										$get_sou_tot = App\Models\Data_model::db_query("SELECT count('*') as inq_cnt,`customer_master`.state_id FROM `inquiry` INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id where `source_id`=".$source[$t]." AND inquiry_date >= '".$from_date."' AND inquiry_date <= '".$to_date."' AND `customer_master`.state_id = '".$value->state_id."' ");
									}
									echo '<td>'.$get_sou_tot[0]->inq_cnt.'</td>';
								}
							?>
							<td><b>{{ $inq_total }}</b></td>
							<td><b>{{ $pending_inq_total }}</b></td>
							<td><b>{{ $active_inq_total }}</b></td>
							<td><b>{{ $cancel_inq_total }}</b></td>
							<td><b>{{ $delete_inq_total }}</b></td>
						</tr>
					</tfoot>
				</table>
			</div>
		@else
			<div class="table-responsive">
				<table id="datatable1" class="table table-bordered table-striped" style="width:100%">
					<thead>
						<tr>
							<th width="4%">Sr No.</th>
							<th width="10%">Date</th>
							@php
							$get_source = App\Models\Data_model::db_query("select * from `source_master` where delete_status = 0 ");
								$source_id = array();
								foreach($get_source as $key=>$val)
								{
									if(empty($date_arr)){
										$get_inq_cnt = App\Models\Data_model::db_query("SELECT count('*') as inq_cnt,`customer_master`.state_id FROM `inquiry` INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id where `source_id`=".$val->source_id." AND `customer_master`.state_id = '".$value->state_id."' ");

									} else {
										$get_inq_cnt = App\Models\Data_model::db_query("SELECT count('*') as inq_cnt,`customer_master`.state_id FROM `inquiry` INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id where `source_id`=".$val->source_id." AND `customer_master`.state_id = '".$value->state_id."' AND inquiry.inquiry_date >= '".$from_date."' AND inquiry.inquiry_date <= '".$to_date."' ");
									}

									if ($get_inq_cnt[0]->inq_cnt != 0) {
										$source_id[]=$val->source_id;
										echo '<th>'.$val->source_name.'</th>';
									}

								}
								$all_source = implode(",",$source_id);
								if($all_source == ''){
									continue;
								}
							@endphp
							<th>Total Inquiry</th>
							<th>Pending Inquiry</th>
							<th>Active Inquiry</th>
							<th>Cancel Inquiry</th>
							<th>Delete Inquiry</th>
						</tr>
					</thead>

					<tbody>
						<tr class="danger">
							<td colspan="<?php echo count($source_id)+7 ?>"><b><center>{{ $value->state_name }}</center></b></td>
						</tr>
						<?php
							$inq_total = 0;
							$pending_inq_total = 0;
							$active_inq_total = 0;
							$cancel_inq_total = 0;
							$delete_inq_total = 0;

							foreach($all_date as $key=>$val)
							{
								$pending_inq = App\Models\Data_model::db_query("SELECT count('*') as inq_pending,`customer_master`.state_id FROM `inquiry` INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id where `source_id` IN(".$all_source.") AND `inquiry_date`='".$val."' AND first_quatation_id = 0 AND delete_status = 0 AND remove_status = 0 AND `customer_master`.state_id = '".$value->state_id."'  ");

								$active_inq = App\Models\Data_model::db_query("SELECT count('*') as inq_active,`customer_master`.state_id FROM `inquiry` INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id where `source_id` IN(".$all_source.") AND `inquiry_date`='".$val."' AND first_quatation_id != 0 AND `customer_master`.state_id = '".$value->state_id."' ");

								$cancel_inq = App\Models\Data_model::db_query("SELECT count('*') as inq_cancel,`customer_master`.state_id FROM `inquiry` INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id where `source_id` IN(".$all_source.") AND `inquiry_date`='".$val."' AND delete_status != 0 AND `customer_master`.state_id = '".$value->state_id."' ");

								$delete_inq = App\Models\Data_model::db_query("SELECT count('*') as inq_delete,`customer_master`.state_id FROM `inquiry` INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id where `source_id` IN(".$all_source.") AND `inquiry_date`='".$val."' AND remove_status != 0 AND `customer_master`.state_id = '".$value->state_id."' ");
						?>
							<tr>
								<td>{{ $key+1 }}</td>
								<td>{{ date("d-m-Y",strtotime($val)) }}</td>
								<?php
									$sub_total = 0;
									foreach($source_id as $key=>$sou)
									{
										$get_inq_cnt = App\Models\Data_model::db_query("SELECT count('*') as inq_cnt,`customer_master`.state_id FROM `inquiry` INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id where `source_id`=".$sou." AND `inquiry_date`='".$val."' AND `customer_master`.state_id = '".$value->state_id."' ");
									?>
										@if(! empty($get_inq_cnt))
											<td>{{ $get_inq_cnt[0]->inq_cnt }}</td>
											@php  $sub_total += $get_inq_cnt[0]->inq_cnt; @endphp
										@else
											<td>-</td>
										@endif
									<?php
									}
									?>
								<td>{{ $sub_total }}</td>
								@php
									$inq_total += $sub_total;
									$pending_inq_total += $pending_inq[0]->inq_pending;
									$active_inq_total += $active_inq[0]->inq_active;
									$cancel_inq_total += $cancel_inq[0]->inq_cancel;
									$delete_inq_total += $delete_inq[0]->inq_delete;
								@endphp
								<td>{{ $pending_inq[0]->inq_pending }}</td>
								<td>{{ $active_inq[0]->inq_active }}</td>
								<td>{{ $cancel_inq[0]->inq_cancel }}</td>
								<td>{{ $delete_inq[0]->inq_delete }}</td>
							</tr>
						<?php
						}
						?>
					</tbody>

					<tfoot>
						<tr class="success">
							<td></td>
							<td><b>Total :-</b></td>
							<?php
								for($t=0; $t<count($source_id); $t++)
								{
									if(empty($date_arr))
									{
										$get_sou_tot = App\Models\Data_model::db_query("SELECT count('*') as inq_cnt,`customer_master`.state_id FROM `inquiry` INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id where `source_id`=".$source_id[$t]." AND `customer_master`.state_id = '".$value->state_id."' ");
									}
									else
									{
										$get_sou_tot = App\Models\Data_model::db_query("SELECT count('*') as inq_cnt,`customer_master`.state_id FROM `inquiry` INNER JOIN `customer_master` ON `customer_master`.customer_id = `inquiry`.customer_id where `source_id`=".$source_id[$t]." AND inquiry_date >= '".$from_date."' AND inquiry_date <= '".$to_date."' AND `customer_master`.state_id = '".$value->state_id."' ");
									}
									echo '<td>'.$get_sou_tot[0]->inq_cnt.'</td>';
								}
							?>
							<td><b>{{ $inq_total }}</b></td>
							<td><b>{{ $pending_inq_total }}</b></td>
							<td><b>{{ $active_inq_total }}</b></td>
							<td><b>{{ $cancel_inq_total }}</b></td>
							<td><b>{{ $delete_inq_total }}</b></td>
						</tr>
					</tfoot>
				</table>
			</div>
		@endif
	@endforeach
@endif

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
                title: 'Inquiry Report',
				footer: true
            },
            {
                extend: 'csv',
				text:'Export To CSV',
                title: 'Inquiry Report',
				footer: true
            }
        ],
		"columnDefs": [ {
        "orderable": false
        } ],
	});
});

</script>