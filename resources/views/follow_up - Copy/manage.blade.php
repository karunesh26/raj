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
         
            
        <div class="row">
            <div class="col-xs-12">
              
                  <div class="box box-warning">
                  
                    <div class="box-body">
                     	
                      <table id="datatable" class="table table-bordered table-striped">
                       <thead>
                        <tr>
							<th>Sr</th>
							<th>Inquiry No.</th>
                            <th>Inquiry Date</th>
                            <th>Follow Up Date</th>
                            <th></th>
                            <th>Manage</th>
                        </tr>
                      </thead>
                         
                       <tbody>
                     
                            @foreach ($result as $key=>$value)
                            <tr>
                                <td>{{ $key+1}}</td>
                                <td>{{ $value->inquiry_no}}</td>
                                <td>{{ $value->inquiry_date}}</td>
                                <td>{{ $value->follow_up_date}}</td>
                                <td></td>
                                <td>
                                   
									<a title="Take Follow Up" class="btn bg-purple btn-flat btn-sm" href="<?php echo $controller_name;?>/take_followup/<?php echo $utility->encode($value->$primary_id); ?>"><i class="glyphicon glyphicon-ok icon-white"></i></a>
                                    
                             
							
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