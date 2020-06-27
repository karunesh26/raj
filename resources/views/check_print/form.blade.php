@extends('template.template')
@section('content')
<?php
error_reporting(0);
if($action == 'insert')
{
	$url = $controller_name.'@insert';
	$btn = "Print";
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
                        {!! Form::label('Name :') !!} <span class="required">*</span>
						<select class="select2" id="party_id" name="party_id" style="width:100%;">
							<option value="">Select</option>
							@foreach($party as $key=>$val)
								<option value="{{ $val->party_id }}">{{ $val->party_name }}</option>
							@endforeach
						</select>
                        </div>

                     	<div class="form-group col-sm-6">
                        {!! Form::label('Amount :') !!} <span class="required">*</span>
                        {!! Form::text('amount',$result[0]->amount, array('class' => 'form-control amountonly' ,'id'=>"amount",'placeholder'=>'Enter Amount','required' => 'required')) !!}
                        </div>
                    </div>
					<div class="form-group col-sm-12">
                     	<div class="form-group col-sm-6">
							{!! Form::label('Check Date :') !!} <span class="required">*</span>
							{!! Form::text('date',date('d-m-Y'), array('class' => 'form-control datepicker','id'=>"date",'placeholder'=>'Select Date','readonly'=>'readonly')) !!}
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
	$.validator.setDefaults({ ignore: ":hidden:not(.select2)" })
	var id= $("#id").val();
	$('#frm').validate({
		rules:{
			party_id: {required: true,},
			amount: {required: true,},
			date: {required: true,},
		},
		messages:{
			party_id: {required:"Please Select Party"},
			amount: {required:"Please Enter Amount"},
			date: {required:"Please Select Date"},
		},
		submitHandler: function(form) {
			$(':input[type="submit"]').prop('disabled', true);
			form.submit();
		},
	});

	$('.datepicker').datepicker({
      autoclose: true,
	  format: 'dd-mm-yyyy',
    });

});
</script>

@endsection