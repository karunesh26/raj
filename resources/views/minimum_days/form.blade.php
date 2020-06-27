@extends('template.template')

@section('content')
<?php
error_reporting(0);

	$url = $controller_name.'@update';
	$btn= "Update";

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

	<bR />
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
				 foreach($result as $key=>$value)
				 {
					 ?>
                 	<div class="form-group col-sm-12">
                      <?php
							if($value->type != 'holiday' && $value->type != 'followup' && $value->type != 'daily')
							{
						?>
                     	<div class="form-group col-sm-4">
                        {!! Form::label($value->label) !!} <span class="required">*</span>
                        </div>
                        <div class="form-group col-sm-8">
                          {!! Form::text($value->type,$value->days, array('class' => 'form-control ' ,'id'=>$value->type,'required' => 'required')) !!}
                        </div>
                        <?php
							}
						?>

						@if($value->type == 'followup' || $value->type == 'daily')
							<input type="hidden" name="<?php echo $value->type; ?>" value="2" />
						@endif


						<?php
							if($value->type == 'holiday')
							{
						?>		<div class="form-group col-sm-4">
								 {!! Form::label($value->label) !!} <span class="required">*</span>
								</div>
								<div class="form-group col-sm-8">
									<select id="<?php echo $value->type; ?>" name="<?php echo $value->type; ?>" class="select2" style="width:100%" >
										<option value=''></option>
										<option <?php echo ($value->days == 0) ? 'selected' : ''; ?> value="0">Sunday</option>
										<option <?php echo ($value->days == 1) ? 'selected' : ''; ?> value="1">Monday</option>
										<option <?php echo ($value->days == 2) ? 'selected' : ''; ?> value="2">Tuesday</option>
										<option <?php echo ($value->days == 3) ? 'selected' : ''; ?> value="3">Wednesday</option>
										<option <?php echo ($value->days == 4) ? 'selected' : ''; ?> value="4">Thursday</option>
										<option <?php echo ($value->days == 5) ? 'selected' : ''; ?> value="5">Friday</option>
										<option <?php echo ($value->days == 6) ? 'selected' : ''; ?> value="6">Saturday</option>
									</select>
								</div>
						<?php
							}
						?>

                    </div>
                  <?php
				 }
				 ?>




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



	$('#statefrm').validate({
		rules: {
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