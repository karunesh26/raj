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
<style>
.cke_contents {
    height: 500px !important;
}
</style>
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
              <h3 class="box-title"><?php echo $msgName;?></h3>
            </div>
            
              {!! Form::open(array('action' => $url, 'method' => 'post' , 'files' => true ,'id'=>"frm",'name'=>"frm",'class'=>"form"))!!}
				 
         		  {{ csrf_field() }}
             	 <div class="box-body">
                   {!! Form::hidden('id',$result[0]->$primary_id, array('id'=>"id")) !!}
                    
                    <div class="form-group col-sm-12">
                        <div class="form-group col-sm-12">
						            {!! Form::textarea('name',$result[0]->$field,['class'=>'form-control','id'=>'name']) !!}
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                	<div class="col-sm-6 col-sm-offset-5">
                
						{!! Form::submit($btn, ['class' => 'btn bg-olive']) !!}
               	 		
                    </div>
                </div>
               {!!Form::close()!!}
             
		</div>
       </div>
     </section>
 

<script type="text/javascript">
jQuery(document).ready(function($){
	CKEDITOR.replace('name');
	var id= $("#id").val();
	$('#frm').validate({
		rules: {
			name: {required: true,},
		},
		messages:{
			name: {required:"Please Enter <?php echo $msgName;?>",},
		},
		submitHandler: function(form) {
			$(':input[type="submit"]').prop('disabled', true);
			form.submit();
		},
	});
});
</script>
			
@endsection