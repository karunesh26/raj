@extends('template.template')

@section('content')
<?php
error_reporting(0);
if($action == 'insert')
{
	$url = $controller_name.'@insert';
	$duplicate_url = URL::action($controller_name.'@duplicate');
	$btn = "Save";
}
else
{
	$url = $controller_name.'@update';
	$duplicate_url = URL::action($controller_name.'@duplicate_update');
	$btn= "Update";
}

$back_link = URL::to($controller_name);
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
    
	
	 
    	<div class="row">
        	<a class="btn bg-navy btn-flat margin" href="<?php echo $back_link;?>">Back</a>
        </div>
        
        <?php
		if($action == 'insert')
		{
		?>
        <div class="row">
   			 <div class="alert alert-info" style="color:#010101;text-align:left;"><i class="fa fa-info-circle"></i> NOTE! When a new employee is added  his default password will be his Mobile No. Employee can login and change his password.</div>
             
              <div class="alert alert-info" style="color:#010101;text-align:left;"><i class="fa fa-info-circle"></i> Username is for login.</div>
   		 </div>
      <?php
		}
		?>
      <div class="row">
       <div class="box box-primary">
      	
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $btn." ".$msgName;?></h3>
            </div>
            
              {!! Form::open(array('action' => $url, 'method' => 'post' , 'files' => true ,'id'=>"frm",'name'=>"frm",'class'=>"form"))!!}
				 
         		  {{ csrf_field() }}
             	 <div class="box-body">
                 
                 	
                   {!! Form::hidden('id',$result[0]->$primary_id, array('id'=>"id")) !!}
                 
                 	  <div class="form-group col-sm-12">
                     	<div class="form-group col-sm-6">
                       	{!! Form::label('Employee Designation') !!} <span class="required">*</span>
                             <select name="role_id" id="role_id" class="select2" style="width:100%">
                                    <option  value="">Select</option>
                                    
                                    @foreach($role as $k=> $v)
                                    	
                                         @if ($v->role_id == $result[0]->role_id)
                                 			    <option value="{{ $v->role_id}}" selected="selected" > {{ $v->role_name}}</option>
                                		 @else
                                         
                                      			<option value="{{ $v->role_id}}" > {{ $v->role_name}}</option> 
                                      @endif
                                    @endforeach
           				</select>
                        <label id="role_id-error" class="error" for="role_id"></label>
                       
                        </div>
                        <div class="form-group col-sm-6">
                          {!! Form::label('User Name') !!} <span class="required">*</span>
                         {!! Form::text('username',$result[0]->username, array('class' => 'form-control ' ,'id'=>"username",'placeholder'=>'Enter User Name','required' => 'required')) !!}
                        </div>
                        
                      
                    </div>
                    
                     <div class="form-group col-sm-12">
                     	<div class="form-group col-sm-6">
                         {!! Form::label('Employee Name') !!} <span class="required">*</span>
                         {!! Form::text('name',$result[0]->name, array('class' => 'form-control ' ,'id'=>"name",'placeholder'=>'Enter Name','required' => 'required')) !!}
                        </div>
                        <div class="form-group col-sm-6">
                      	 {!! Form::label('Mobile') !!} <span class="required">*</span>
                         {!! Form::text('mobile',$result[0]->mobile, array('class' => 'form-control numberonly' ,'id'=>"mobile",'placeholder'=>'Enter Mobile No','required' => 'required','maxlength'=>'10')) !!}
                        </div>
                    </div>
                    
                       <div class="form-group col-sm-12">
                     	<div class="form-group col-sm-6">
                      	 {!! Form::label('Email') !!} <span class="required">*</span>
                         {!! Form::email('email',$result[0]->email, array('class' => 'form-control ' ,'id'=>"email",'placeholder'=>'Enter Email')) !!}
                        </div>
                        <div class="form-group col-sm-6">
                      
                        {!! Form::label('Zone') !!} <!--<span class="required">*</span>-->
                          <select name="zone_id[]" id="zone_id" class="select2" multiple=multiple style="width:100%">
                                 
									<?php
										$zone_id_arr=explode(',',$result[0]->zone_id);
									?>
                                    @foreach($zone as $k=> $v)
                                    	
                                         @if (in_array($v->zone_id,$zone_id_arr))
                                 			    <option value="{{ $v->zone_id}}" selected="selected" > {{ $v->zone_name}}</option>
                                		 @else
                                      			<option value="{{ $v->zone_id}}" > {{ $v->zone_name}}</option>
                                      @endif
                                    @endforeach
           				</select>
       					 <label id="zone_id-error" class="error" for="zone_id"></label>
                        </div>
                        
                    </div>
                  
					@if($action == 'update')
						@if($role_id == 1)
						 <div class="form-group col-sm-12">
							<div class="form-group col-sm-6">
							 {!! Form::label('Employee Password') !!} <span class="required">*</span>
							 {!! Form::text('pwd',$result[0]->password, array('class' => 'form-control ' ,'id'=>"pwd",'placeholder'=>'Enter Password','required' => 'required')) !!}
							</div>
						</div>	
						@endif
					@endif
					
                  
                <?php /*?>     <div class="form-group col-sm-12">
                     	<div class="form-group col-sm-6">
                      	 {!! Form::label('State') !!} <span class="required">*</span>
                          <select name="state_id" id="state_id" class="form-control">
                                    <option  value="">Select</option>
                                    
                                    @foreach($state as $k=> $v)
                                    	
                                         @if ($v->state_id == $result[0]->state_id)
                                 			    <option value="{{ $v->state_id}}" selected="selected" > {{ $v->state_name}}</option>
                                		 @else
                                         
                                      			<option value="{{ $v->state_id}}" > {{ $v->state_name}}</option> 
                                      @endif
                                    @endforeach
           				</select>
       					<label id="state_id-error" class="error" for="state_id"></label>
      				
                        </div>
                        <div class="form-group col-sm-6">
                       
        				  {!! Form::label('City') !!} <span class="required">*</span>
                          <select name="city_id" id="city_id" class="form-control">
                                    <option  value="">Select</option>
                                    
                                    @foreach($city as $k=> $v)
                                    	
                                         @if ($v->city_id == $result[0]->city_id)
                                 			    <option value="{{ $v->city_id}}" selected="selected" > {{ $v->city_name}}</option>
                                		 @else
                                         
                                      			<option value="{{ $v->city_id}}" > {{ $v->city_name}}</option> 
                                      @endif
                                    @endforeach
           				</select>
                        <label id="city_id-error" class="error" for="city_id"></label>
                        </div>
                        
                      
                    </div><?php */?>
                    
                      
                    </div>
                
                </div>
                <div class="box-footer">
                	<div class="col-sm-6 col-sm-offset-5">
               			 <?php
                                if($action == 'insert')
                                {
                                    ?>
                              
                                {{ Form::reset('Reset', ['class' => 'btn btn-danger']) }}
                                <?php
                                }
                                else
                                {
                                    ?>
                                    
                                    <a class="btn btn-danger" type="reset" href="<?php echo $utility->encode($result[0]->$primary_id);?>">Reset</a>
                                   <?php
                                }
                                ?>
                
                  {!! Form::submit($btn, ['class' => 'btn bg-olive']) !!}
               	 		
                    </div>
                </div>
               {!!Form::close()!!}
             
		</div>
       </div>
     </section>
 

<script type="text/javascript">
jQuery(document).ready(function($){
	
		$.validator.setDefaults({ ignore: ":hidden:not(.select2)" }) 
	
	var id= $("#id").val();
	$('#frm').validate({
		rules: {
			role_id: {required: true,},
			username: {required: true,},
			name: {required: true,},
			mobile: {required: true,
					remote: {	
									url: '<?php echo $duplicate_url;?>', 
									
									type: "post",
									data:
									{	
										 "_token": "{{ csrf_token() }}",
										id:id,		
										name: function()
										{
											return $('#frm :input[name="mobile"]').val();
										},
									},
									
							},
				},
			email: {required: true, email: true,},
			//zone_id: {required: true,},
			state_id: {required: true,},
			city_id: {required: true,},
		},
		messages:{
			role_id: {required: "Please Select Employee Type",},
			username: {required:"Please Enter User Name",},
			name: {required:"Please Enter Employee Name",},
			mobile: {required:"Please Enter Mobile No",remote:"Mobile No Already Exist!"},
			email: {required: "Please Enter Email ", email:"Please Enter Proper Email",},
			//zone_id: {required: "Please Select Zone",},
			state_id: {required: "Please Select State",},
			city_id: {required: "Please Select City",},
		
		},
		submitHandler: function(form) {
			$(':input[type="submit"]').prop('disabled', true);
			form.submit();
		},
	});
		
});
</script>
			
@endsection