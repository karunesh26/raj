@extends('template.template')

@section('content')


 <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1> <?php echo $msgName;?> Details</h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('Welcome') }}"><i class="fa fa-dashboard"></i> Home</a></li>
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
                          <th>Employee Name</th>
                          <th>Transfered Employee Name</th>
                          <th>From Date</th>
                          <th>To Date</th>
							            <th>Manage</th>
                        </tr>
                      </thead>

                       <tbody>

                            @foreach ($result as $key=>$value)
                            <tr>
                              <td>{{ $key+1}}</td>
                              <td>
                                @php
                                  $zones = explode(",",$value->zone_id);
                                @endphp
                                @foreach($zones as $key => $zone)
                                  @if ($key+1 == 1)
                                    {{ $zone_detail[$zone] }}
                                  @else
                                    {{ ' / '.$zone_detail[$zone] }}
                                  @endif
                                @endforeach
                              </td>
                              <td>{{ $emp_detail[$value->transfer_emp_id]}}</td>
                              <td>{{ date("d-m-Y",strtotime($value->transfer_from_date))}}</td>
                              <td>{{ date("d-m-Y",strtotime($value->transfer_to_date))}}</td>
                              <td>
                                @if($role_id == 1)
                                  <a class="btn bg-maroon btn-flat btn-sm" onclick="return confirm('Are You Sure To Delete?')" href="<?php echo $controller_name;?>/delete/<?php echo $utility->encode($value->$primary_id); ?>"><i class="glyphicon glyphicon-trash icon-white"></i></a>
                                @else
                                  @if($edit_permission == 1)
                                   <a class="btn bg-maroon btn-flat btn-sm" onclick="return confirm('Are You Sure To Delete?')" href="<?php echo $controller_name;?>/delete/<?php echo $utility->encode($value->$primary_id); ?>"><i class="glyphicon glyphicon-trash icon-white"></i></a>
                                  @endif
                                @endif

                              </td>

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
