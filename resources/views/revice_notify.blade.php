<?php
	if(!empty($revise_quotation_notify))
	{
?>
<div class="col-lg-12">
		<div class="box box-warning">
			<div class="col-xs-12">
				<h3>Revise</h3>
			</div>
            <div class="box-body">
				<table class="table table-bordered table-striped datatable">
					<thead>
						<tr>
							<th>Sr</th>
							<th>Employee</th>
							<th>Quotation No.</th>
							<th>Remarks</th>
							<th>Manage</th>
						</tr>
					</thead>
					<tbody>
					
					@foreach ($revise_quotation_notify as $key=>$value)
						<tr class="<?php echo ($value->read == 1) ? 'bg-green' : ''; ?>">
							<td>{{ $key+1}}</td>
							<td>{{ $value->name}}</td>
							<td>{{ $value->quotation_no}}</td>
							<td>{{ $value->remark}}</td>
							<td>
								<?php
								if($value->clear == 0)
								{	
									if($value->read == 1)
									{
								?>
								<a id="revise_read_clear" class="btn bg-maroon btn-sm" value="<?php echo 'Clear**'.$value->id; ?>">Clear</a>
								<?php 
									}
								}
								if($value->read == 0)
								{
								?>
									<a id="revise_read_clear" class="btn bg-purple btn-sm"  value="<?php echo 'Read**'.$value->id; ?>">Read</a>
								<?php
								}
								?>
							</td>
						</tr>
					@endforeach
					
					</tbody>
				</table>
			</div>
		</div>
	</div>
<?php 
	}
?>