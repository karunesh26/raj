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
                        {!! Form::label('Name :') !!} <span class="required">*</span>
                        {!! Form::text('party_name',$result[0]->$field, array('class' => 'form-control ' ,'id'=>"party_name",'placeholder'=>'Enter Party Name','required' => 'required')) !!}
                        </div>

                     	<div class="form-group col-sm-6">
                        {!! Form::label('Mobile No :') !!} <span class="required">*</span>
                        {!! Form::text('mobile_no',$result[0]->mobile_no, array('class' => 'form-control ' ,'id'=>"mobile_no",'placeholder'=>'Enter Mobile Number','required' => 'required')) !!}
                        </div>
                    </div>
					<div class="form-group col-sm-12">
                     	<div class="form-group col-sm-6">
							{!! Form::label('Email :') !!}
							{!! Form::text('email',$result[0]->email, array('class' => 'form-control ' ,'id'=>"email",'placeholder'=>'Enter Email')) !!}
                        </div>
						<div class="form-group col-sm-6">
							{!! Form::label('Company Name :') !!}
							{!! Form::text('company_name',$result[0]->company_name, array('class' => 'form-control ' ,'id'=>"company_name",'placeholder'=>'Enter Company Name')) !!}
						</div>
                    </div>

					<div class="form-group col-sm-12">
                     	<div class="form-group col-sm-6">
							{!! Form::label('Bank Name :') !!} <span class="required">*</span>
							{!! Form::text('bank_name',$result[0]->bank_name, array('class' => 'form-control ' ,'id'=>"bank_name",'placeholder'=>'Enter Bank Name','required' => 'required')) !!}
                        </div>
						<div class="form-group col-sm-6">
							{!! Form::label('Account Name :') !!} <span class="required">*</span>
							{!! Form::text('account_name',$result[0]->account_name, array('class' => 'form-control ' ,'id'=>"account_name",'placeholder'=>'Enter Account Name','required' => 'required')) !!}
						</div>
                    </div>
					<div class="form-group col-sm-12">
						<div class="form-group col-sm-6">
							{!! Form::label('IFSC Code :') !!}
							{!! Form::text('ifsc_code',$result[0]->ifsc_code, array('class' => 'form-control ' ,'id'=>"ifsc_code",'placeholder'=>'Enter IFSC Code')) !!}
						</div>
                     	<div class="form-group col-sm-6">
                        {!! Form::label('Office Address :') !!}
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
			party_name: {required: true,},
			mobile_no: {required: true,},
			bank_name: {required: true,},
			account_name: {required: true,},
		},
		messages:{
			party_name: {required:"Please Enter Party Name"},
			mobile_no: {required:"Please Enter Mobile Number"},
			bank_name: {required:"Please Enter Bank Name"},
			account_name: {required:"Please Enter Account Name"},
		},
		submitHandler: function(form) {
			$(':input[type="submit"]').prop('disabled', true);
			form.submit();
		},
	});




});
</script>

@endsection