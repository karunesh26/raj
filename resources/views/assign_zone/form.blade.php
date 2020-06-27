@extends('template.template')

@section('content')
<?php
error_reporting(0);
if($action == 'insert')
{
	$url = $controller_name.'@insert';
	$btn = "Save";
}
else
{
	$url = $controller_name.'@update';
	$btn= "Update";
}
$back_link = URL::to($controller_name);
?>

 <!-- Content Header (Page header) -->
    <section class="content-header">
    
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('Dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li ><?php echo $msgName;?></li>
      </ol>
    </section>
    <br />
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
       <div class="box box-primary">
      	
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $msgName;?></h3>
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
                        <select name="zone" id="zone" class="select2" style="width:100%" >
                                    <option  value="">Select</option>
                                    
                                    @foreach($zone as $k=> $v)
                                    	
                                         @if ($v->zone_id == $result[0]->zone_id)
                                 			    <option value="{{ $v->zone_id}}" selected="selected" > {{ $v->zone_name}}</option>
                                		 @else
                                         
                                      			<option value="{{ $v->zone_id}}" > {{ $v->zone_name}}</option> 
                                      @endif
                                    @endforeach
           				</select>
        				<label id="zone-error" class="error" for="zone"></label>
                        </div>
                        
                      
                    </div>
                    
                     <div class="form-group col-sm-12">
                     	<div class="form-group col-sm-4">
                      
                        {!! Form::label('State') !!} <span class="required">*</span>
                        </div>
                        <div class="form-group col-sm-8">
                        <select name="state[]" id="state" class="select2 state" style="width:100%" multiple="multiple">
							
							@foreach($state as $k=> $v)
								<option value="{{ $v->state_id}}" > {{ $v->state_name}}</option> 
							@endforeach
           				</select>
        				<label id="state-error" class="error" for="state"></label>
                        </div>
                    </div>
					
                    </div>
                      
                <div class="box-footer">
                	<div class="col-sm-6 col-sm-offset-5">
						<a class="btn btn-danger" type="reset" href="">Reset</a>
                
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
			zone: {required: true,},
			'state[]': {required: true,},
		},
		messages:{
			zone: {required: "Please Select Zone",},
			'state[]': {required:"Please Select State",},
		},
		submitHandler: function(form) {
			$(':input[type="submit"]').prop('disabled', true);
			form.submit();
		},
	});
});
</script>
@endsection