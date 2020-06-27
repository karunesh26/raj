@extends('template.template')

@section('content')
<?php
error_reporting(0);

	$url = $controller_name.'@update';
	$btn= "Change";
	

?>

 <!-- Content Header (Page header) -->
    <section class="content-header">
    
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('Dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li ><?php echo $msgName;?></li>
        <li class="active"><?php echo $btn;?></li>
      </ol>
    </section>
    
    <section class="content">
    
	
		 
    <br />
     
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
       <div class="box box-primary">
      	
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $btn." ".$msgName;?></h3>
            </div>
            
              {!! Form::open(array('action' => $url, 'method' => 'post' , 'files' => true ,'id'=>"frm",'name'=>"frm",'class'=>"form"))!!}
				 
         		  {{ csrf_field() }}
             	 <div class="box-body">
                 
                 	<?php
					  $user_id = Session::get('raj_user_id');
					  ?>
                   {!! Form::hidden('user_id',$user_id, array('id'=>"user_id")) !!}
                 
                 	
                    
                    <div class="form-group col-sm-12">
                     
                       {!! Form::password('old_password', array('class'=> 'form-control' ,'id'=>"old_password",'placeholder'=>'Old Password','required' => 'required'))!!}
                        
                    </div>
                    
                      <div class="form-group col-sm-12">
                     {!! Form::password('new_password', array('class'=> 'form-control' ,'id'=>"new_password",'placeholder'=>'New Password','required' => 'required'))!!}
                        
                    </div>
                    
                      <div class="form-group col-sm-12">
                     
                      {!! Form::password('confirm_password', array('class'=> 'form-control' ,'id'=>"confirm_password",'placeholder'=>'Re-Type Password','required' => 'required'))!!} 
                    </div>
                    
                      
                    </div>
                
                </div>
                <div class="box-footer">
                	<div class="col-sm-6 col-sm-offset-5">
               			
                              
                                {{ Form::reset('Reset', ['class' => 'btn btn-danger']) }}
                               
                
                  {!! Form::submit($btn, ['class' => 'btn bg-olive']) !!}
               	 		
                    </div>
                </div>
               {!!Form::close()!!}
             
		</div>
       </div>
     </section>
 

<script type="text/javascript">
jQuery(document).ready(function($){
	
	
	
	$('#frm').validate({
		rules: {
			old_password: {required: true,},
			new_password: {required: true,},
			confirm_password: {required: true,equalTo: "#new_password"},
		},
		messages:{
			old_password: {required:"Please Enter Old Password",},
			new_password: {required:"Please Enter New Password",},
			confirm_password: {required:"Please Re-Type New Password",equalTo: "Password Do Not Match"},
		},
	});
	
	
		
});
</script>
			
@endsection