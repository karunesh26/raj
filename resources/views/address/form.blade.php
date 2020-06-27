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
                        {!! Form::label('Office Name') !!} <span class="required">*</span>
                        </div>
                        <div class="form-group col-sm-8">
                        {!! Form::text('office_name',$result[0]->$field, array('class' => 'form-control ' ,'id'=>"office_name",'placeholder'=>'Enter Office Name','required' => 'required')) !!}
                        </div>
                    </div>
					<div class="form-group col-sm-12">
                     	<div class="form-group col-sm-4">
                        {!! Form::label('Office Address') !!} <span class="required">*</span>
                        </div>
                        <div class="form-group col-sm-8">
                        {!! Form::textarea('address',$result[0]->address, array('class' => 'form-control ' ,'id'=>"address",'placeholder'=>'Enter Address','size'=>'20*3')) !!}
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
 

<script type="text/javascript">
jQuery(document).ready(function($){

	var id= $("#id").val();
	$('#frm').validate({
		rules:{
			address: {required: true,},
			office_name: {required: true,
				remote:	{
					url: '<?php echo $duplicate_url;?>',
					type: "post",
					data:
					{	
						"_token": "{{ csrf_token() }}",
						id:id,
						office_name: function()
						{
							return $('#frm :input[name="office_name"]').val();
						},
					},
				},
			},
		},
		messages:{
			office_name: {required:"Please Enter Office Name",remote:"Office Name Already Exist!"},
			address: {required:"Please Enter Address"},
		},
		submitHandler: function(form) {
			$(':input[type="submit"]').prop('disabled', true);
			form.submit();
		},
	});
		
		
	
		
});
</script>
			
@endsection