@extends('template.template')
@section('content')
 <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1> <?php echo $msgName;?> Details</h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('Dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><?php echo $msgName;?></li>
      </ol>
    </section>

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

		@if($role_id != '1')
			@if($add_permission == 1)
			<div class="row">
				<div class="col-xs-12">
					<a style="float:right"class="btn bg-orange btn-flat" href="<?php echo $controller_name;?>/add"> <i class="glyphicon glyphicon-plus icon-white"></i>  New</a>
				</div>
			</div>
			@endif
		 @else
			<div class="row">
				<div class="col-xs-12">
					<a style="float:right"class="btn bg-orange btn-flat" href="<?php echo $controller_name;?>/add"> <i class="glyphicon glyphicon-plus icon-white"></i>  New</a>
				</div>
			 </div>
		 @endif
        <div class="row">
            <div class="col-xs-12">
				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#active_employee" data-toggle="tab" >Active Employee</a></li>
						<li class=""><a href="#deactive_employee" data-toggle="tab" >Deactive Employee</a></li>
					</ul>

					<div class="tab-content">
						<div class="tab-pane active" id="active_employee">
							<div class="box box-success">
								<div class="box-body">
									<table class="table table-bordered table-striped employeeTable">
										<thead>
											<tr>
												<th>Sr</th>
												<th>Employee</th>
												<th>User Name</th>
												<th>Password</th>
												<th>Designation</th>
												<th>Mobile No</th>
												<th>Email Id</th>
												<th>Zone</th>
												<th>Manage</th>
											</tr>
										</thead>
										<tbody>
											@foreach ($activeEmployee as $key=>$value)
											<tr>
												<td>{{ $key+1}}</td>
												<td>{{ $value->name}}</td>
												<td>{{ $value->username}}</td>
												<td>{{ $value->password}}</td>
												<td>{{ $value->role_name}}</td>
												<td>{{ $value->mobile}}</td>
												<td>{{ $value->email}}</td>
												<td>
													<?php
													if($value->zone_id != 0)
													{
														$zone_arr = explode(",",$value->zone_id);
														foreach($zone as $k=>$v)
														{
															if(in_array($v->zone_id, $zone_arr) )
															{
																echo $v->zone_name;
															echo "<BR>";
															}
														}
													}
													?>
												</td>
												<?php
													if($role_id == 1)
													{
												?>
														<td>
															<a title="Edit" class="btn bg-purple btn-flat btn-sm" href="<?php echo $controller_name;?>/edit/<?php echo $utility->encode($value->$primary_id); ?>"><i class="glyphicon glyphicon-edit icon-white"></i></a>
															<?php
															if($value->delete_status == 0)
															{
																?>
																	<a class="btn bg-maroon btn-flat btn-sm"  href="<?php echo $controller_name;?>/delete/<?php echo $utility->encode($value->$primary_id); ?>">Deactivate</i></a>
																<?php
															}
															else
															{
																?>
																	<a class="btn btn-primary btn-flat btn-sm" href="<?php echo $controller_name;?>/delete/<?php echo $utility->encode($value->$primary_id); ?>">Activate</i></a>
																<?php
															}
															?>
															<?php
															?>
																<button type="button" class="btn btn-info btn-flat btn-sm change_password" id="<?php echo $value->$primary_id; ?>" data-toggle="modal" data-target="#myModal">Change Password</button>
														</td>
												<?php
													}
													else
													{
												?>
														<td>
															<?php
																if($edit_permission == 1)
																{
															?>
																<a title="Edit" class="btn bg-purple btn-flat btn-sm" href="<?php echo $controller_name;?>/edit/<?php echo $utility->encode($value->$primary_id); ?>"><i class="glyphicon glyphicon-edit icon-white"></i></a>
															<?php
																}
															?>
															<?php
															if($delete_permission == 1)
															{
																if($value->delete_status == 0)
																{
																?>
																		<a class="btn bg-maroon btn-flat btn-sm"  href="<?php echo $controller_name;?>/delete/<?php echo $utility->encode($value->$primary_id); ?>">Deactivate</i></a>
																	<?php
																}
																else
																{
																	?>
																		<a class="btn btn-primary btn-flat btn-sm" href="<?php echo $controller_name;?>/delete/<?php echo $utility->encode($value->$primary_id); ?>">Activate</i></a>
																	<?php
																}
															}
															?>
														</td>
												<?php
													}
												?>
											</tr>
										@endforeach
										</tbody>
									</table>
								</div>
							</div>
						</div>

						<div class="tab-pane" id="deactive_employee">
							<div class="box box-warning">
								<div class="box-body">
									<table class="table table-bordered table-striped employeeTable">
										<thead>
											<tr>
												<th>Sr</th>
												<th>Employee</th>
												<th>User Name</th>
												<th>Password</th>
												<th>Designation</th>
												<th>Mobile No</th>
												<th>Email Id</th>
												<th>Zone</th>
												<th>Manage</th>
											</tr>
										</thead>
										<tbody>
										@foreach ($deactiveEmployee as $key=>$value)
										<tr>
											<td>{{ $key+1}}</td>
											<td>{{ $value->name}}</td>
											<td>{{ $value->username}}</td>
											<td>{{ $value->password}}</td>
											<td>{{ $value->role_name}}</td>
											<td>{{ $value->mobile}}</td>
											<td>{{ $value->email}}</td>
											<td>
												<?php
												if($value->zone_id != 0)
												{
													$zone_arr = explode(",",$value->zone_id);
													foreach($zone as $k=>$v)
													{
														if(in_array($v->zone_id, $zone_arr) )
														{
															echo $v->zone_name;
														echo "<BR>";
														}
													}
												}
												?>
											</td>
											<?php
												if($role_id == 1)
												{
											?>
													<td>
														<a title="Edit" class="btn bg-purple btn-flat btn-sm" href="<?php echo $controller_name;?>/edit/<?php echo $utility->encode($value->$primary_id); ?>"><i class="glyphicon glyphicon-edit icon-white"></i></a>
														<?php
														if($value->delete_status == 0)
														{
															?>
																<a class="btn bg-maroon btn-flat btn-sm"  href="<?php echo $controller_name;?>/delete/<?php echo $utility->encode($value->$primary_id); ?>">Deactivate</i></a>
															<?php
														}
														else
														{
															?>
																<a class="btn btn-primary btn-flat btn-sm" href="<?php echo $controller_name;?>/delete/<?php echo $utility->encode($value->$primary_id); ?>">Activate</i></a>
															<?php
														}
														?>
														<?php
														?>
															<button type="button" class="btn btn-info btn-flat btn-sm change_password" id="<?php echo $value->$primary_id; ?>" data-toggle="modal" data-target="#myModal">Change Password</button>
													</td>
											<?php
												}
												else
												{
											?>
													<td>
														<?php
															if($edit_permission == 1)
															{
														?>
															<a title="Edit" class="btn bg-purple btn-flat btn-sm" href="<?php echo $controller_name;?>/edit/<?php echo $utility->encode($value->$primary_id); ?>"><i class="glyphicon glyphicon-edit icon-white"></i></a>
														<?php
															}
														?>
														<?php
														if($delete_permission == 1)
														{
															if($value->delete_status == 0)
															{
															?>
																	<a class="btn bg-maroon btn-flat btn-sm"  href="<?php echo $controller_name;?>/delete/<?php echo $utility->encode($value->$primary_id); ?>">Deactivate</i></a>
																<?php
															}
															else
															{
																?>
																	<a class="btn btn-primary btn-flat btn-sm" href="<?php echo $controller_name;?>/delete/<?php echo $utility->encode($value->$primary_id); ?>">Activate</i></a>
																<?php
															}
														}
														?>
													</td>
											<?php
												}
											?>
										</tr>
									@endforeach
									</tbody>
								</table>
								</div>
							</div>
							</div>
						</div>
					</div>
				</div>
        </div>
    </section>
<?php
// Sneha Doshi , 08-05-2018 , for admin to change all emp password
?>
 <!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">

      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Change Password</h4>
        </div>
         <?php
					$url = $controller_name.'@change_emp_password';
					?>
          {!! Form::open(array('action' => $url, 'method' => 'post' ,'id'=>"frm",'name'=>"frm",'class'=>"form"))!!}
        <div class="modal-body">
        	<div class="row">
            	<div class="box box-primary">
                      <div class="box-body">
                      <input type="hidden" name="emp_id" id="emp_id" />
                        <div class="form-group col-sm-12">
                        	<div class="form-group col-sm-4">
                            	{!! Form::label('New Password') !!} <span class="required">*</span>
                            </div>
                            <div class="form-group col-sm-6">
                            {!! Form::password('emp_password', array('class' => 'form-control ' ,'id'=>"emp_password",'placeholder'=>'Enter New Password','required' => 'required')) !!}

                            </div>
                        </div>

                        <div class="form-group col-sm-12">
                        	<div class="form-group col-sm-4">
                            	{!! Form::label('Conform Password') !!} <span class="required">*</span>
                            </div>
                            <div class="form-group col-sm-6">
                            {!! Form::password('emp_con_password', array('class' => 'form-control ' ,'id'=>"emp_con_password",'placeholder'=>'Re - Enter New Password','required' => 'required')) !!}
                            </div>
                        </div>
                      </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
          {!! Form::submit('Change', ['class' => 'btn bg-olive']) !!}
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
         {!!Form::close()!!}
      </div>
    </div>
  </div>
<script type="text/javascript">

jQuery(document).ready(function($){

	$('.employeeTable').DataTable({
		'paging'      : true,
		'lengthChange': true,
		'searching'   : true,
		'ordering'    : true,
		'info'        : true,
		'autoWidth'   : true,
		"pageLength":  100,
		"pagingType": "full_numbers",
		"ordering": false,
		"sDom": '<"H"lfrp>t<"F"ip>',
	});

	$(document).on("click", ".change_password", function() {
		var emp_id = $(this).attr('id');
		$("#emp_id").val(emp_id);
		$("#emp_password").val('');
		$("#emp_con_password").val('');
		$(".error").empty();
	});

	$('#frm').validate({
		  rules: {
               emp_password: "required",
               emp_con_password:
			   {
                    equalTo: "#emp_password"
               }
            },
            messages: {
                emp_password: " Enter Password",
                emp_con_password: " Enter Confirm Password Same as Password"
            }
	});
});
</script>
@endsection

