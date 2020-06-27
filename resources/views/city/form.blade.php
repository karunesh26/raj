@extends('template.template')
@section('content')
<?php
error_reporting(0);
$state_url = $controller_name.'@state_add';
$state_duplicate = URL::action($controller_name.'@state_duplicate');
$get_state = URL::action($controller_name.'@get_state');
$check_state = URL::action($controller_name.'@check_state');

if($action == 'insert')
{
	$url = $controller_name.'@insert';
	$duplicate_url = URL::action($controller_name.'@duplicate');
	$btn = "Save";
	$state_redirect = $controller_name.'/add';

	
}
else
{
	$url = $controller_name.'@update';
	$duplicate_url = URL::action($controller_name.'@duplicate_update');
	$btn= "Update";
	$state_redirect = $controller_name.'/edit/'.$utility->encode($result[0]->$primary_id);
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
                     	<div class="form-group col-sm-4">
                      
                        {!! Form::label('Zone') !!} <span class="required">*</span>
                        </div>
                        <div class="form-group col-sm-8">
                        <select name="zone" id="zone" class="select2" style="width:100%">
                                    <option  value="">Select</option>
                                    
                                    @foreach($zone as $k=> $v)
                                    	
                                         @if ($v->zone_id == $result[0]->zone_id)
                                 			    <option value="{{ $v->zone_id}}" selected="selected" > {{ $v->zone_name}}</option>
                                		 @else
                                         
                                      			<option value="{{ $v->zone_id}}" > {{ $v->zone_name}}</option> 
                                      @endif
                                    @endforeach
           				</select>
                        </div>
                    </div>
                    
                     <div class="form-group col-sm-12">
                     	<div class="form-group col-sm-4">
                      
                        {!! Form::label('State') !!} <span class="required">*</span>
                        </div>
                        <div class="form-group col-sm-6" id="state_cls">
                        <select name="state" id="state" class=" select2 " style="width:100%">
                                    <option  value="">Select</option>
                                    
                                    @foreach($state as $k=> $v)
                                    	
                                         @if ($v->state_id == $result[0]->state_id)
                                 			    <option value="{{ $v->state_id}}" selected="selected" > {{ $v->state_name}}</option>
                                		 @else
                                         
                                      			<option value="{{ $v->state_id}}" > {{ $v->state_name}}</option> 
                                      @endif
                                    @endforeach
           				</select>
                        </div>
                        <div class="form-group col-sm-2">
							<button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-state">Add State</button>
                        </div>
                    </div>
                    
                    <div class="form-group col-sm-12">
                     	<div class="form-group col-sm-4">
                        {!! Form::label('City') !!} <span class="required">*</span>
                        </div>
                        <div class="form-group col-sm-8">
                        {!! Form::text('name',$result[0]->$field, array('class' => 'form-control ' ,'id'=>"name",'placeholder'=>'Enter Name','required' => 'required')) !!}
        
                        </div>
                        
                      
                    </div>
                    
                    
                      
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
 

 <div class="modal fade" id="modal-state">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">Add State</h4>
	  </div>
	 
	  {!! Form::open(array('action' => $state_url, 'method' => 'post' , 'files' => true ,'id'=>"statefrm",'name'=>"statefrm",'class'=>"form"))!!} 									
	   <div class="modal-body">
		 
			 {{ csrf_field() }}
			
			  
				{!! Form::label('Country') !!} <span class="required">*</span>
			   
				<select name="country" id="country" class="select2" style="width:100%">
							<option  value="">Select</option>
							
							@foreach($country as $k=> $v)
								<option value="{{ $v->country_id}}" > {{ $v->country_name}}</option> 
							@endforeach
				</select>
				
				<br />
				{!! Form::label('Zone') !!} <span class="required">*</span>
			   
				<select name="zone" id="zone" class="select2" style="width:100%">
							<option  value="">Select</option>
							
							@foreach($zone as $k=> $v)
								
								 @if ($v->zone_id == $result[0]->zone_id)
										<option value="{{ $v->zone_id}}" selected="selected" > {{ $v->zone_name}}</option>
								 @else
								 
										<option value="{{ $v->zone_id}}" > {{ $v->zone_name}}</option> 
							  @endif
							@endforeach
				</select>
				
			  <br />
			
				{!! Form::label('State Name') !!} <span class="required">*</span>
				
				 {!! Form::text('statename','', array('class' => 'form-control ' ,'id'=>"statename",'placeholder'=>'Enter State Name','required' => 'required')) !!}
				<div id="state_data">
				
				</div>
			
				 {!! Form::hidden('stateredirect',$state_redirect, array('class' => 'form-control ' ,'id'=>"stateredirect",'required' => 'required')) !!}
			

		  
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary">Save</button>
	  </div>
	  
	  {!!Form::close()!!}
	</div>
	<!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
                            
<script type="text/javascript">
jQuery(document).ready(function($){
	
	$.validator.setDefaults({ ignore: ":hidden:not(.select2)" }) 	
	$('#zone').change(function() {
		
			var zone = $("#zone").val();
		
			$.ajax({
					type: "POST",
					url: "<?php echo $get_state;?>",
					data: { "_token": "{{ csrf_token() }}",zone:zone},
					success:function (res)
					{
						//$("#state_cls").empty().append().html(res);
						$("#state").empty().append().html(res).trigger('change.select2');
					}
				});
	});	
	
	$('body').on('keyup',"#statename",function(e){
		var statename = $("#statename").val();
		if(statename!='')
		{
			$.ajax({
						type: "POST",
						url: "<?php echo $check_state;?>",
						data: { "_token": "{{ csrf_token() }}",statename:statename},
						success:function (res)
						{
							$("#state_data").empty().append().html(res);
						}
					}); 
		}
		else
		{
		$("#state_data").empty();
		}
	});
	
	$('#statefrm').validate({
		rules:{
			country: {required: true,},
			zone: {required: true,},
			statename: {required: true,
					remote:{
							url: '<?php echo $state_duplicate;?>',
							type: "post",
							data:
							{	
								 "_token": "{{ csrf_token() }}",
								country:function ()
								{
									return $('#country').val();
								},
								name: function()
								{
									return $('#statefrm :input[name="statename"]').val();
								},
							},
							
					},
				},
		},
		messages:{
			country: {required: "Please Select Country",},
			zone: {required: "Please Select Zone",},
			statename: {required:"Please Enter State Name",remote:"State Already Exist!"},
		},
	});
	
	var id= $("#id").val();
	$('#frm').validate({
		rules: {
			zone: {required: true,},
			state: {required: true,},
			name: {required: true,
					remote: {	
									url: '<?php echo $duplicate_url;?>', 
									
									type: "post",
									data:
									{	
										 "_token": "{{ csrf_token() }}",
										id:id,	
										state: function()
										{
											return $('#frm :input[name="state"]').val();
										},
										name: function()
										{
											return $('#frm :input[name="name"]').val();
										},
									},
									
							},
				},
		
		},
		messages:{
			zone: {required: "Please Select Zone",},
			state: {required:"Please Select State",},
			name: {required:"Please Enter <?php echo $msgName;?>",remote:"<?php echo $msgName;?> Already Exist!"},
		
		},
		submitHandler: function(form) {
				$(':input[type="submit"]').prop('disabled', true);
				form.submit();
		},
	});
		
		
	
		
});
</script>
			
@endsection