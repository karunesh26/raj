<?php
$btn = 'Update Data';
$url = $controller.'@update_data';
?>
<style>
.user_right_checkbox{
	width:25px;
	height:25px;
}

</style>
{!! Form::open(array('action' => $url, 'method' => 'post' , 'files' => true ,'id'=>"frm",'name'=>"frm",'class'=>"form"))!!}
{{ csrf_field() }}

	<input type="hidden" name="role_id" value="<?php echo $role_id;?>">
	<input type="hidden" name="user_id" value="<?php echo $user_id;?>">

<?php
	$id = array(
		"company_master",
		"marketing_master",
		"sales_master",
		"marketing",
		"sales",
		"report");

	$module = array(
		"company_master",
		"marketing_master",
		"sales_master",
		"marketing",
		"sales",
		"report");

	$name = array(
		"Company Master",
		"Marketing Master",
		"Sales Master",
		"Marketing",
		"Sales",
		"Report");
?>
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">

            <div class="x_content">
				<div class="col-xs-12">
					<ul class="nav nav-tabs tabs-right">
						<?php
						for($i=0; $i<count($id); $i++)
						{
							if($i == 0)
							{
						?>
							<li class="active">
						<?php
							}
							else
							{
							?>
								<li>
							<?php
							}
							?>
								<a href="#<?php echo $id[$i];?>" data-toggle="tab" aria-expanded="false"><b><?php echo $name[$i];?></b></a>
							</li>
						<?php
						}
						?>
					</ul>
				</div>
				<div class="col-xs-12">
					<!-- Tab panes -->
					<div class="tab-content">
						<?php
						for($i=0; $i<count($id); $i++)
						{
							if($i == 0)
							{
							?>
							<div class="tab-pane active" id="<?php echo $id[$i];?>">
							<?php
							}
							else
							{
							?>
							<div class="tab-pane" id="<?php echo $id[$i];?>">
							<?php
							}
							?>
							<h3><?php echo $name[$i];?></h3>

							<table  class="table table-striped table-bordered">
								<thead>
									<tr>
										<th width="15%">All Permission</th>
										<th>Module Name</th>
										<th>View </th>
										<th>Add</th>
										<th>Edit</th>
										<th>Delete</th>
										<th>Active</th>
									</tr>
								</thead>
							<tbody>
							<?php

							$get_master =  DB::select("select * from module where display_menu = '".$module[$i]."' ORDER BY display_sequence");

							if(count($get_master) != 0)
							{
								foreach($get_master as $key=>$value)
								{
									$inc = $key + 1;
									$check = DB::select("select * from ".$table." where role_id = ".$role_id." AND user_id =".$user_id." AND module_id = ".$value->module_id." ");

									if(count($check) != 0)
									{
										if($check[0]->view == 1 && $check[0]->add == 1 && $check[0]->edit == 1 && $check[0]->delete == 1 )
										{
											$main_check = 1;
										}
										else
										{
											$main_check = 0;
										}
									}
									else
									{
										$main_check = 0;
									}
								?>
								<tr id="table_data">

									<td ><input class="user_right_checkbox check_data" type="checkbox" <?php echo ($main_check == 1) ? 'checked' : ''; ?> /></td>
									<th><label><?php echo $value->display_name;?></label><input type="hidden"  name="module_id[]" id="module_id"  value="<?php echo $value->module_id; ?>"> </th>

									<?php
									if(count($check) == 0)
									{
									?>
										<th><label><input type="checkbox" class="js-switch user_right_checkbox"  name="view[<?php echo $value->module_id; ?>]" id="view" /></label></th>
										<th><label><input type="checkbox" class="js-switch user_right_checkbox"  name="add[<?php echo $value->module_id; ?>]" id="add"/></label></th>
										<th><label><input type="checkbox" class="js-switch user_right_checkbox"  name="edit[<?php echo $value->module_id; ?>]" id="edit"/></label></th>
										<th><label><input type="checkbox" class="js-switch user_right_checkbox"  name="delete[<?php echo $value->module_id; ?>]" id="delete"/></label></th>
										<th><label><input type="checkbox" class="js-switch user_right_checkbox"  name="active[<?php echo $value->module_id; ?>]" id="active"/></label></th>
									<?php
									}
									else
									{
									?>
										<th><label><input type="checkbox" class="js-switch user_right_checkbox"  name="view[<?php echo $value->module_id; ?>]" id="view"  <?php if($check[0]->view == 1){ echo "checked=checked";}?>/></label></th>

										<th><label><input type="checkbox" class="js-switch user_right_checkbox"  name="add[<?php echo $value->module_id; ?>]" id="add" <?php if($check[0]->add == 1){ echo "checked=checked";}?>/></label></th>

										<th><label><input type="checkbox" class="js-switch user_right_checkbox"  name="edit[<?php echo $value->module_id; ?>]" id="edit" <?php if($check[0]->edit == 1){ echo "checked=checked";}?>/></label></th>

										<th><label><input type="checkbox" class="js-switch user_right_checkbox"  name="delete[<?php echo $value->module_id; ?>]" id="delete" <?php if($check[0]->delete == 1){ echo "checked=checked";}?>/></label></th>

										<th><label><input type="checkbox" class="js-switch user_right_checkbox"  name="active[<?php echo $value->module_id; ?>]" id="active" <?php if($check[0]->active == 1){ echo "checked=checked";}?>/></label></th>

								<?php
								}
								?>
								</tr>
								<?php
								}
							}
							?>
							</tbody>
						</table>
						</div>
						<?php
						}
						?>
						</div>
					</div>
				<div class="clearfix"></div>
				</div>
                </div>
              </div>
				<div class="box-footer">
                	<div class="col-sm-6 col-sm-offset-5">
						{!! Form::submit($btn, ['class' => 'btn bg-olive']) !!}
                    </div>
                </div>
	{!!Form::close()!!}
</form>
<script>
$(document).ready(function() {
	$(".check_data").click(function () {
		  $(this).closest('tr').find("#view").prop('checked', $(this).prop('checked'));
		  $(this).closest('tr').find("#add").prop('checked', $(this).prop('checked'));
		  $(this).closest('tr').find("#edit").prop('checked', $(this).prop('checked'));
		  $(this).closest('tr').find("#delete").prop('checked', $(this).prop('checked'));
		  $(this).closest('tr').find("#active").prop('checked', $(this).prop('checked'));
	});
});
</script>