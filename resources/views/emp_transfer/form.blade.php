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
							<div class="form-group col-sm-6">
								{!! Form::label('Zone') !!} <span class="required">*</span>
							</div>
							<div class="form-group col-sm-6">
								<select name="zone_id[]" multiple id="zone_id" class="select2 " style="width:100%">
									@foreach($zones as $k=> $v)
										<option value="{{ $v->zone_id}}" > {{ $v->zone_name}}</option>
									@endforeach
								</select>
							</div>
						</div>

					<div class="form-group col-sm-12">
                     	<div class="form-group col-sm-6">
						 {!! Form::label('Transfer Employee') !!} <span class="required">*</span>
                        </div>
                        <div class="form-group col-sm-6">
						 	<select name="transfer_emp_id" id="transfer_emp_id" class="select2 " style="width:100%">
                            <option value="">Select</option>
								@foreach($employee as $k=> $v)
									<option value="{{ $v->emp_id}}" > {{ $v->name}}</option>
								@endforeach
           					</select>
                        </div>
					</div>

					<div class="form-group col-sm-12">
                     	<div class="form-group col-sm-6">
						 {!! Form::label('From Date') !!} <span class="required">*</span>
                        </div>
                        <div class="form-group col-sm-6">
							<input type="text" name="from_date" id="from_date" placeholder="From Date" class="form-control  datepicker" >
                        </div>
					</div>

					<div class="form-group col-sm-12">
                     	<div class="form-group col-sm-6">
						 {!! Form::label('To Date') !!} <span class="required">*</span>
                        </div>
                        <div class="form-group col-sm-6">
							<input type="text" placeholder="To Date" name="to_date" id="to_date" class="form-control  datepicker" >
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
	$('.datepicker').datepicker({
      autoclose: true,
	  format: 'dd-mm-yyyy',
    });

	$.validator.setDefaults({ ignore: ":hidden:not(.select2)" })
	var id= $("#id").val();
	$('#frm').validate({
		rules: {
			'zone_id[]': {required: true,},
			transfer_emp_id: {required: true,},
			from_date: {required: true,},
			to_date: {required: true,},
		},
		messages:{
			'zone_id[]': {required:"Please Select Zone "},
			transfer_emp_id: {required:"Please Select Transfer Employee "},
			from_date: {required: "Please Select From Date "},
			to_date: {required: "Please Select To Date "},
		},
		submitHandler: function(form) {
			$(':input[type="submit"]').prop('disabled', true);
			form.submit();
		},
	});
});
</script>
@endsection