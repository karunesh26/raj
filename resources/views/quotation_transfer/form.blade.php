@extends('template.template')
@section('content')
<?php
error_reporting(0);

if($action == 'update')
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
                 
                 	<div class="form-group col-sm-12">
                     	<div class="form-group col-sm-4">
                        {!! Form::label('Quotation Number') !!} <span class="required">*</span>
                        </div>
                        <div class="form-group col-sm-8">
                        {!! Form::text('quot_num',$result[0]->$field, array('class' => 'form-control ' ,'id'=>"quot_num",'readonly'=>'readonly')) !!}
        
                        </div>
                        
                      
                    </div>
                   {!! Form::hidden('id',$result[0]->inquiry_id, array('id'=>"id")) !!}
                 
                 	<div class="form-group col-sm-12">
                     	<div class="form-group col-sm-4">
							{!! Form::label('Zone') !!} <span class="required">*</span>
                        </div>
                        <div class="form-group col-sm-8">
                        <select name="zone" id="zone" class="select2" style="width:100%">
							<option  value="">Select</option>
							@foreach($zone as $k=> $v)
								<option <?php echo ($v->zone_id == $result[0]->zone_id) ? 'selected' : ''; ?> value="{{ $v->zone_id}}" > {{ $v->zone_name}}</option> 
							@endforeach
           				</select>
                        </div>
                    </div>
                    </div>
                </div>
                <div class="box-footer">
                	<div class="col-sm-6 col-sm-offset-5">
               		<a class="btn btn-danger" type="reset" href="<?php echo $utility->encode($result[0]->$primary_id);?>">Reset</a>
                    
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
	$('#frm').validate({
		rules:{
			zone: {required: true,},
		},
		messages:{
			zone: {required: "Please Select Zone",},
		},
		submitHandler: function(form) {
			$(':input[type="submit"]').prop('disabled', true);
			form.submit();
		},
	});
});
</script>
			
@endsection