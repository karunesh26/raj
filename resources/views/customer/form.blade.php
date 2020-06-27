@extends('template.template')

@section('content')
<?php
error_reporting(0);
if($action == 'update')
{
	$mobile_check = URL::action($controller_name.'@mobile_check');
	$email_check = URL::action($controller_name.'@email_check');
	$get_city = URL::action($controller_name.'@get_city');
	$get_country_zone = URL::action($controller_name.'@get_country_zone');

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
                   {!! Form::hidden('customer_id',$result[0]->$primary_id, array('id'=>"customer_id")) !!}

                 	  <div class="form-group col-sm-12">
						<div class="form-group col-sm-1">
							{!! Form::label('Prefix') !!} <span class="required">*</span>
							<select name="customer_prefix" id="customer_prefix" class="select2" style="width:100%">
								<option <?php echo ($result[0]->prefix == 'Mr.') ? 'selected' : ''; ?> value="Mr.">Mr.</option>
								<option <?php echo ($result[0]->prefix == 'Miss.') ? 'selected' : ''; ?> value="Miss.">Miss.</option>
								<option <?php echo ($result[0]->prefix == 'Mrs.') ? 'selected' : ''; ?> value="Mrs.">Mrs.</option>
							</select>
						</div>
						<div class="form-group col-sm-3">
                      	 {!! Form::label('Customer Name') !!} <span class="required">*</span>
                         {!! Form::text('name',$result[0]->name, array('class' => 'form-control ' ,'id'=>"email",'placeholder'=>'Enter Name')) !!}
                        </div>
						<div class="form-group col-sm-4">
                      	 {!! Form::label('Company Name') !!} <span class="required">*</span>
                         {!! Form::text('company',$result[0]->company, array('class' => 'form-control ' ,'id'=>"company",'placeholder'=>'Enter Company')) !!}
                        </div>
                    </div>

					<div class="form-group col-sm-12">
						<div class="form-group col-sm-4">
							{!! Form::label('Country') !!} <span class="required">*</span>
							<select name="country_id" id="country_id" class="select2" style="width:100%">
								<option  value="">Select</option>
								@foreach($country as $k=> $v)
									@if ($v->country_id == $result[0]->country_id)
										<?php $country_zone_id = $v->zone_id;?>
										<option value="{{ $v->country_id}}" selected="selected" > {{ $v->country_name }}</option>
									@else
										<option value="{{ $v->country_id}}" > {{ $v->country_name}}</option>
									@endif
								@endforeach
							</select>
							<label id="country_id-error" class="error" for="country_id"></label>
						</div>
					   <div class="form-group col-sm-4 state_hide" <?php echo ($action=='update' && $country_zone_id != 0)?'style="display:none;"':'';?>>
							{!! Form::label('State') !!} <span class="required">*</span>
							<select name="state_id" id="state_id" class="select2" style="width:100%">
								<option  value="">Select</option>
								@foreach($state as $k=> $v)
									@if($v->country_id == $result[0]->country_id)
										@if ($v->state_id == $result[0]->state_id)
											<option value="{{ $v->state_id}}" selected="selected" > {{ $v->state_name}}</option>
										@else
											<option value="{{ $v->state_id}}" > {{ $v->state_name}}</option>
										@endif
									@endif
								@endforeach
							</select>
							<label id="state_err"></label>
						</div>
						<div class="form-group col-sm-4 city_hide" <?php echo ($action=='update' && $country_zone_id!=0)?'style="display:none;"':'';?>>
							{!! Form::label('City') !!}
							<select name="city_id" id="city_id" class="select2" style="width:100%">
								<option  value="">Select</option>

								@foreach($city as $k=> $v)
									@if ($v->state_id == $result[0]->state_id)
										@if ($v->city_id == $result[0]->city_id)
											<option value="{{ $v->city_id}}" selected="selected" > {{ $v->city_name}}</option>
										@else
											<option value="{{ $v->city_id}}" > {{ $v->city_name}}</option>
										@endif
									@endif
								@endforeach
							</select>
							<label id="city_id-error" class="error" for="city_id"></label>
						</div>
					</div>
                    <div class="form-group col-sm-12">
						<div class="form-group col-sm-3">
							{!! Form::label('Mobile 1') !!}
							{!! Form::text('mobile',$result[0]->mobile, array('class' => 'form-control ' ,'id'=>"mobile",'placeholder'=>'Enter Mobile No 1')) !!}
						</div>
						<div class="col-sm-1">
							{!! Form::label('Type') !!} <span class="required">*</span>
							<select name="mtype1" id="mtype1" class="select2" style="width:100%">
								<option value="">Select</option>
								<option <?php echo ($result[0]->mobile_type1 == 'W') ? 'selected' : ''; ?> value="W">W</option>
								<option <?php echo ($result[0]->mobile_type1 == 'W/C') ? 'selected' : ''; ?> value="W/C">W/C</option>
								<option <?php echo ($result[0]->mobile_type1 == 'C') ? 'selected' : ''; ?> value="C">C</option>
							</select>
						 </div>
						<div class="form-group col-sm-3">

						{!! Form::label('Mobile 2') !!}
						 {!! Form::text('mobile_2',$result[0]->mobile_2, array('class' => 'form-control' ,'id'=>"mobile_2",'placeholder'=>'Enter Mobile No 2')) !!}
						</div>
						<div class="col-sm-1">
							{!! Form::label('Type') !!}
							<select name="mtype2" id="mtype2" class="select2" style="width:100%">
								<option value="">Select</option>
								<option <?php echo ($result[0]->mobile_type2 == 'W') ? 'selected' : ''; ?> value="W">W</option>
								<option <?php echo ($result[0]->mobile_type2 == 'W/C') ? 'selected' : ''; ?> value="W/C">W/C</option>
								<option <?php echo ($result[0]->mobile_type2 == 'C') ? 'selected' : ''; ?> value="C">C</option>
							</select>
						 </div>

						 <div class="form-group col-sm-3">
							{!! Form::label('Mobile 3') !!}
							{!! Form::text('mobile_3',$result[0]->mobile_3, array('class' => 'form-control ' ,'id'=>"mobile_3",'placeholder'=>'Enter Mobile No 3')) !!}
						</div>
						<div class="col-sm-1">
							{!! Form::label('Type') !!}
							<select name="mtype3" id="mtype3" class="select2" style="width:100%">
								<option value="">Select</option>
								<option <?php echo ($result[0]->mobile_type3 == 'W') ? 'selected' : ''; ?> value="W">W</option>
								<option <?php echo ($result[0]->mobile_type3 == 'W/C') ? 'selected' : ''; ?> value="W/C">W/C</option>
								<option <?php echo ($result[0]->mobile_type3 == 'C') ? 'selected' : ''; ?> value="C">C</option>
							</select>
						 </div>
					</div>

					<div class="form-group col-sm-12">
						<div class="form-group col-sm-4">
						{!! Form::label('Landline No') !!}
						{!! Form::text('landline',$result[0]->landline, array('class' => 'form-control' ,'id'=>"landline",'placeholder'=>'Enter Landline')) !!}
						</div>
						<div class="form-group col-sm-4">
						{!! Form::label('Email 1') !!}
						{!! Form::email('email',$result[0]->email, array('class' => 'form-control ' ,'id'=>"email",'placeholder'=>'Enter Email')) !!}
						</div>
						<div class="form-group col-sm-4">
						{!! Form::label('Email 2') !!}
						{!! Form::email('email_2',$result[0]->email_2, array('class' => 'form-control ' ,'id'=>"email_2",'placeholder'=>'Enter Email 2')) !!}
						</div>
					</div>

					<div class="form-group col-sm-12">
						<div class="form-group col-sm-6">
						{!! Form::label('Address') !!}
						{!! Form::textarea('address',$result[0]->address, array('class' => 'form-control ' ,'id'=>"address",'placeholder'=>'Enter Address','size'=>'20*3')) !!}

						</div>

						<div class="form-group col-sm-6">
						{!! Form::label('Office Address') !!}
						{!! Form::textarea('office_address',$result[0]->office_address, array('class' => 'form-control ' ,'id'=>"office_address",'placeholder'=>'Enter Office Address','size'=>'20*3')) !!}
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
		rules: {
			customer_prefix: {required: true,},
			name: {required: true,},
			country_id: {required: true,},
			email: {email: true,},
		},
		messages:{
			customer_prefix: {required: "Please Select Prefix Type",},
			name: {required:"Please Enter Customer Name",},
			country_id: {required:"Please Select Country",},
			email: {remote:"Email Id Already Exist!"},
		},

	});

	$('body').on('change','#country_id', function(){
		var country_id = $(this).val();
		$('.blockUI').show();
		$.ajax({
				type: "POST",
				url: "<?php echo $get_country_zone;?>",
				dataType:'json',
				data: { "_token": "{{ csrf_token() }}",country_id:country_id},
				success:function (res)
				{
					$("#state_id").html(res[1]).trigger('change.select2');
					if(res[0]==0)
					{
						$('.city_hide').show();
						$('.state_hide').show();
					}
					else
					{
						$('.city_hide').hide();
						$('.state_hide').hide();
					}
					$('.blockUI').hide();
				}
			});
	});

	$('#state_id').change(function() {

		var state_id = $("#state_id").val();
		$('.blockUI').show();
		$.ajax({
				type: "POST",
				url: "<?php echo $get_city;?>",
				data: { "_token": "{{ csrf_token() }}",state_id:state_id},
				success:function (res)
				{

					$("#city_id").empty().append().html(res).trigger('change.select2');
					$('.blockUI').hide();
				}
			});
	});
	$('body').on('change','#mobile', function(){
		var mobile = $("#mobile").val();
		if(mobile=='')
			return false;
		var mobile_2 = $("#mobile_2").val();
		var mobile_3 = $("#mobile_3").val();

		var customer_id = $('#customer_id').val();
		if(mobile==mobile_2)
		{
			alert('This Mobile No Allready Enter in Mobile No 2.');
			$(this).val('');
			return false;
		}
		else if(mobile==mobile_3)
		{
			alert('This Mobile No Allready Enter in Mobile No 3.');
			$(this).val('');
			return false;
		}
		$('.blockUI').show();
		$.ajax({
			type: "POST",
			url: "<?php echo $mobile_check;?>",
			data: { "_token": "{{ csrf_token() }}",mobile:mobile,customer_id:customer_id},
			success:function(res)
			{
				$('.blockUI').hide();
				if(res==1)
				{
					$('#mobile').val('');
					alert('This Mobile No Allready Exist.');

					return false;
				}
			}
		});
	});


	$('body').on('change','#mobile_2', function(){
		var mobile_2 = $("#mobile_2").val();
		if(mobile_2=='')
			return false;
		var mobile = $("#mobile").val();
		var mobile_3 = $("#mobile_3").val();

		var customer_id = $('#customer_id').val();
		if(mobile==mobile_2)
		{
			alert('This Mobile No Allready Enter in Mobile No 1.');
			$(this).val('');
			return false;
		}
		else if(mobile_2==mobile_3)
		{
			alert('This Mobile No Allready Enter in Mobile No 3.');
			$(this).val('');
			return false;
		}
		$('.blockUI').show();
		$.ajax({
			type: "POST",
			url: "<?php echo $mobile_check;?>",
			data: { "_token": "{{ csrf_token() }}",mobile:mobile_2,customer_id:customer_id},
			success:function(res)
			{
				$('.blockUI').hide();
				if(res==1)
				{
					$('#mobile_2').val('');
					alert('This Mobile No Allready Exist.');
					return false;
				}
			}
		});
	});

	$('body').on('change','#mobile_3', function(){
		var mobile_3 = $("#mobile_3").val();
		if(mobile_3=='')
			return false;
		var mobile = $("#mobile").val();
		var mobile_2 = $("#mobile_2").val();

		var customer_id = $('#customer_id').val();
		if(mobile_3==mobile_2)
		{
			alert('This Mobile No Allready Enter in Mobile No 2.');
			$(this).val('');
			return false;
		}
		else if(mobile_3==mobile)
		{
			alert('This Mobile No Allready Enter in Mobile No 1.');
			$(this).val('');
			return false;
		}
		$('.blockUI').show();
		$.ajax({
			type: "POST",
			url: "<?php echo $mobile_check;?>",
			data: { "_token": "{{ csrf_token() }}",mobile:mobile_3,customer_id:customer_id},
			success:function(res)
			{
				$('.blockUI').hide();
				if(res==1)
				{
					$('#mobile_3').val('');
					alert('This Mobile No Allready Exist.');
					return false;
				}
			}
		});
	});

	$('body').on('change','#email', function(){
		var email = $("#email").val();
		if(email=='')
			return false;
		var email_2 = $("#email_2").val();

		var customer_id = $('#customer_id').val();
		if(email==email_2)
		{
			alert('This Email Address Allready Enter in Email Address 2.');
			$(this).val('');
			return false;
		}
		$('.blockUI').show();
		$.ajax({
			type: "POST",
			url: "<?php echo $email_check;?>",
			data: { "_token": "{{ csrf_token() }}",email:email,customer_id:customer_id},
			success:function(res)
			{
				$('.blockUI').hide();
				if(res==1)
				{
					$('#email').val('');
					alert('This Email Address Allready Exist.');
					return false;
				}
			}
		});
	});

	/* $('body').on('change','#email_2', function(){
		var email_2 = $("#email_2").val();
		if(email_2=='')
			return false;
		var email = $("#email").val();
		var customer_id = $('#customer_id').val();
		if(email==email_2)
		{
			alert('This Email Address Allready Enter in Email Address 1.');
			$(this).val('');
			return false;
		}
		$('.blockUI').show();
		$.ajax({
			type: "POST",
			url: "<?php echo $email_check;?>",
			data: { "_token": "{{ csrf_token() }}",email:email_2,customer_id:customer_id},
			success:function(res)
			{
				$('.blockUI').hide();
				if(res==1)
				{
					$('#email_2').val('');
					alert('This Email Address Allready Exist.');
					return false;
				}
			}
		});
	});	*/


});
</script>

@endsection