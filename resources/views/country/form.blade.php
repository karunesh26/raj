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
                        {!! Form::label('Country Name') !!} <span class="required">*</span>
                        </div>
                        <div class="form-group col-sm-8">
                        {!! Form::text('name',$result[0]->$field, array('class' => 'form-control ' ,'id'=>"name",'placeholder'=>'Enter Country Name','required' => 'required')) !!}
                        </div>
                    </div>
					
					<div class="form-group col-sm-12">
						<div class="form-group col-sm-4">
                            {!! Form::label('Currency Type') !!} <span class="required">*</span>
						</div>
						<div class="form-group col-sm-8">
                             <select name="cur_type" id="cur_type" class="select2" style="width:100%">
                                    <option value=''>Select</option>
									<option value="inr">INR</option>
									<option value="dollar">Dollar</option>
							</select>
						</div>
					</div>

					
                    <div class="form-group col-sm-12">
						<div class="form-group col-sm-4">
                            {!! Form::label('Zone') !!}
						</div>
						<div class="form-group col-sm-8">
                             <select name="zone_id" id="zone_id" class="select2" style="width:100%">
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
	
	
	// $('body').on('change','#zone_id',function(){
		// $('#name').val('');
	// });
	$('body').on('change','#country_id',function(){
		$('#name').val('');
	});
	
	var id= $("#id").val();
	$('#frm').validate({
		rules:{
			cur_type : {required : true,},
			name: {required: true,
				remote:	{
					url: '<?php echo $duplicate_url;?>',
					type: "post",
					data:
					{	
						"_token": "{{ csrf_token() }}",
						id:id,
						name: function()
						{
							return $('#frm :input[name="name"]').val();
						},
					},
				},
			},
		},
		messages:{
			cur_type :{required:"Please Select Currency Type"},
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