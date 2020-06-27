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
                  <div class="box box-warning">
                    <div class="box-body">
                      <table id="datatable" class="table table-bordered table-striped">
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
                     
                            @foreach ($result as $key=>$value)
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
    </section>
@endsection
