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
	$get_product_rate = URL::action($controller_name.'@get_rate');
	$mobile_check = URL::action($controller_name.'@mobile_check');
	$email_check = URL::action($controller_name.'@email_check');
	$get_customer_data = URL::action($controller_name.'@get_customer_data');
	$get_city = URL::action($controller_name.'@get_city');
	$get_country_zone = URL::action($controller_name.'@get_country_zone');
	$back_link=URL::to($controller_name);
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
							<?php
							if($action == 'insert')
							{
							?>
								<input type="hidden" name="inquiry_no" id="inquiry_no" class="form-control inquiry_no" value="<?php echo $inq_no; ?>" />
							<?php
							}
							else
							{
							?>
								<input type="hidden" name="inquiry_no" id="inquiry_no" class="form-control inquiry_no" value="<?php echo $result[0]->inquiry_no; ?>" />
							<?php
							}
							?>

                           <?php  /* <div class="form-group col-sm-3">

                            {!! Form::label('Inquiry No') !!}
                             <?php
							if($action == 'insert')
							{
								?>
                          	 {!! Form::text('inquiry_no',$inq_no, array('class' => 'form-control ' ,'id'=>"inquiry_no",'required' => 'required','readonly'=>'readonly')) !!}
                             <?php
							}
							else
							{
							?>
                                {!! Form::text('inquiry_no',$result[0]->inquiry_no, array('class' => 'form-control ' ,'id'=>"inquiry_no",'required' => 'required','readonly'=>'readonly')) !!}
                            <?php
							}
							?>
                            </div> */ ?>
                            <div class="form-group col-sm-3">
                             {!! Form::label('Inquiry Date') !!}
                            <?php
							if($action == 'insert')
							{
								?>
                          	 {!! Form::text('inquiry_data',date("d-m-Y"), array('class' => 'form-control ' ,'id'=>"inquiry_date",'required' => 'required','readonly'=>'readonly')) !!}
                             <?php
							}
							else
							{
								?>
                                 {!! Form::text('inquiry_date',date('d-m-Y', strtotime($result[0]->inquiry_date)), array('class' => 'form-control ' ,'id'=>"inquiry_date",'required' => 'required','readonly'=>'readonly')) !!}
                                <?php
							}
							?>
                            </div>
							 <div class="form-group col-sm-3">
							  {!! Form::label('Inquiry Time') !!}
							   <?php
								if($action == 'insert')
								{
									?>
								{!! Form::text('inquiry_time',date("h:i a"), array('class' => 'form-control ' ,'id'=>"inquiry_time",'required' => 'required','readonly'=>'readonly')) !!}
								<?php
								}
								else
								{
									?>
									{!! Form::text('inquiry_date',date('h:i a', strtotime($result[0]->inquiry_time)), array('class' => 'form-control ' ,'id'=>"inquiry_time",'required' => 'required','readonly'=>'readonly')) !!}
									<?php
								}
								?>

							 </div>

							  <div class="form-group col-sm-3">
							  {!! Form::label('Inquiry Person') !!}
							   <?php
								if($action == 'insert')
								{
									$user = Session::get('raj_user');
									?>
								{!! Form::text('inquiry_person',$user, array('class' => 'form-control ' ,'id'=>"inquiry_person",'required' => 'required','readonly'=>'readonly')) !!}
								<?php
								}
								else
								{
									?>
									{!! Form::text('inquiry_person','', array('class' => 'form-control ' ,'id'=>"inquiry_person",'required' => 'required','readonly'=>'readonly')) !!}
									<?php
								}
								?>

							 </div>
                        </div>

                    <div class="form-group col-sm-12">
						<div class="form-group col-sm-3">
                               {!! Form::label('Inquiry Source') !!} <span class="required">*</span>

                               <select name="source_id" id="source_id" class="select2" style="width:100%">
                                        <option  value="">Select</option>
										<?php $source_name=''; ?>
                                        @foreach($source as $k=> $v)
                                            <?php
											if ($v->source_id == $result[0]->source_id)
											{
												$source_name=strtolower($v->source_name);

											?>
                                                    <option value="{{ $v->source_id}}" selected="selected" > {{ $v->source_name}}</option>
                                             <?php
											 }
											 else
											 {
												 ?>
                                                    <option value="{{ $v->source_id}}" > {{ $v->source_name}}</option>
										     <?php
											 }
											 ?>
                                        @endforeach
                            </select>
                            <label id="source_id-error" class="error" for="source_id"></label>

                       </div>
					   <div class="form-group col-sm-3" style="<?php echo (strpos($source_name,'visitor') !== false)?'':'display:none';?>" id="insentive_div">
					    <?php
							if($action == 'insert')
							{
								?>

								 {!! Form::label('Incentive') !!} <span class="required">*</span>
								 <br />
								 {{ Form::radio('insentive', 'no', true, ['class' => 'insentive','id' => 'insentive_no']) }}No
								 {{ Form::radio('insentive', 'yes','', ['class' => 'insentive']) }}Yes
								 <?php
							 }
							 else
							 {
								 ?>
								 {!! Form::label('Incentive') !!} <span class="required">*</span>
								  <br />
								  <?php
								   	if($result[0]->insentive == 'no')
								  	{
									    ?>
										{{ Form::radio('insentive', 'no', true, ['class' => 'insentive','id' => 'insentive_no']) }}No
										{{ Form::radio('insentive', 'yes','', ['class' => 'insentive' ]) }}Yes
										 <?php
										 }
										 else
										 {
											 ?>
										{{ Form::radio('insentive', 'no', '', ['class' => 'insentive','id' => 'insentive_no']) }}No
										{{ Form::radio('insentive', 'yes',true, ['class' => 'insentive' ]) }}Yes
										<?php
										 }
							 }
							?>
					   </div>

						<div class="form-group col-sm-3" style="<?php echo ($result[0]->insentive == 'yes')?'':'display:none';?>" id="attended_div">

							 {!! Form::label('Attended By') !!}
                                     <br />
                                    <select name="attended_by" id="attended_by" class="select2 " style="width:100%">
                                        <option value="">Select</option>

                                        @foreach($employee as $k=> $v)

                                             @if ($v->emp_id == $result[0]->attended_by)
                                                    <option value="{{ $v->emp_id}}" selected="selected" > {{ $v->name}}</option>
                                             @else

                                                    <option value="{{ $v->emp_id}}" > {{ $v->name}}</option>
                                          @endif
                                        @endforeach
           							</select>
					   </div>
					   <div class="form-group col-sm-3">
                             {!! Form::label('Customer Type') !!} <span class="required">*</span>
                             <BR />
                             <?php
							 if($action == 'insert')
							 {
							 ?>
                             	{{ Form::radio('customer_type', 'new', true, ['class' => 'customer_type']) }}New
                                {{ Form::radio('customer_type', 'existing','', ['class' => 'customer_type']) }}Existing
							<?php
							 }
							 else
							 {
								 if($result[0]->customer_type == 'new')
								 {
							 ?>
									{{ Form::radio('customer_type', 'new', true, ['class' => 'customer_type','disabled'=>'disabled']) }}New
									{{ Form::radio('customer_type', 'existing','', ['class' => 'customer_type' ,'disabled'=>'disabled']) }}Existing

                             <?php
								 }
								 else
								 {
							 ?>
									{{ Form::radio('customer_type', 'new', '', ['class' => 'customer_type','disabled'=>'disabled']) }}New
									{{ Form::radio('customer_type', 'existing',true, ['class' => 'customer_type','disabled'=>'disabled']) }}Existing
							<?php
								 }
							?>
                                   {!! Form::hidden('customer_type',$result[0]->customer_type, array('class' => 'form-control ' ,'id'=>"customer_type")) !!}
							<?php
							}
							?>

                          </div>
					 </div>

					<div class="form-groyp col-sm-12">
						<div class="form-group col-sm-3">

                        {!! Form::label('Inquiry For') !!} <span class="required">*</span>
                     	<select name="product_id" id="product_id" class="select2" style="width:100%">
								<option  value="">Select</option>

								@foreach($product as $k=> $v)

									 @if ($v->product_id == $result[0]->product_id)
											<option value="{{ $v->product_id}}" selected="selected" > {{ $v->product_name}}</option>
									 @else

											<option value="{{ $v->product_id}}" > {{ $v->product_name}}</option>
								  @endif
								@endforeach
           				</select>
       					 <label id="product_id-error" class="error" for="product_id"></label>
                        </div>
						 <div class="form-group col-sm-3">
						 </div>
						 <div class="form-group col-sm-3">
                            	{!! Form::label('Inquiry Type') !!} <span class="required">*</span>
								 <select name="category_id" id="category_id" class="select2" style="width:100%">
											<option  value="">Select</option>

											@foreach($category as $k=> $v)
												 @if ($v->category_id == $result[0]->category_id)
														<option value="{{ $v->category_id}}" selected="selected" > {{ $v->category_name}}</option>
												 @else
														<option value="{{ $v->category_id}}" > {{ $v->category_name}}</option>
											     @endif
											@endforeach
								</select>
								<label id="category_id-error" class="error" for="category_id"></label>
                            </div>
							<div class="form-group col-sm-3">
							</div>
					</div>

						<div class="form-group col-sm-12">
							<?php
							 if($action == 'insert')
							 {
							?>

								  <div class="col-sm-1" id="new_customer_prefix">
									{!! Form::label('Prefix') !!} <span class="required">*</span>
									<select name="customer_prefix" id="customer_prefix" class="select2" style="width:100%">
                                        <option <?php echo ($result[0]->prefix == 'Mr.') ? 'selected' : ''; ?> value="Mr.">Mr.</option>
                                        <option <?php echo ($result[0]->prefix == 'Miss.') ? 'selected' : ''; ?> value="Miss.">Miss.</option>
                                        <option <?php echo ($result[0]->prefix == 'Mrs.') ? 'selected' : ''; ?> value="Mrs.">Mrs.</option>
									</select>
								  </div>
                                  <div class="form-group col-sm-3" id="new_customer">
                           		 {!! Form::label('Customer Name') !!} <span class="required">*</span>

								 {!! Form::text('name',$result[0]->name, array('class' => 'form-control input-sm' ,'id'=>"name",'placeholder'=>'Enter Name',)) !!}

								  <label id="name_err"></label>
                                 </div>
                                 <div class="form-group col-sm-3" id="existing_customer" style="display:none">

                                 {!! Form::label('Customer ') !!} <span class="required">*</span>

                                 <select name="customer_id" id="customer_id" class="select2" style="width:100%">
                                        <option value="">Select</option>

                                        @foreach($customer as $k=> $v)

                                             @if ($v->customer_id == $result[0]->customer_id)
                                                    <option value="{{ $v->customer_id}}" selected="selected" > {{ $v->prefix.' '.$v->name}}</option>
                                             @else

                                                    <option value="{{ $v->customer_id}}" > {{ $v->prefix.' '.$v->name}}</option>
                                          @endif
                                        @endforeach
                                </select>
                                <label id="customer_err" ></label>
                                </div>
                             <?php
								 }
								 else
								 {
									 if($result[0]->customer_type == 'new')
									 {
								 ?>
										 <div class="col-sm-1" id="new_customer_prefix">
											{!! Form::label('Prefix') !!} <span class="required">*</span>
											<select name="customer_prefix" id="customer_prefix" class="select2" style="width:100%">
												<option <?php echo ($result[0]->prefix == 'Mr.') ? 'selected' : ''; ?> value="Mr.">Mr.</option>
												<option <?php echo ($result[0]->prefix == 'Miss.') ? 'selected' : ''; ?> value="Miss.">Miss.</option>
												<option <?php echo ($result[0]->prefix == 'Mrs.') ? 'selected' : ''; ?> value="Mrs.">Mrs.</option>
											</select>
										  </div>

                                 		  <div class="form-group col-sm-3" id="new_customer">
                           		 			{!! Form::label('Customer Name') !!} <span class="required">*</span>
                             			 	{!! Form::text('name',$result[0]->name, array('class' => 'form-control ' ,'id'=>"name",'placeholder'=>'Enter Name')) !!}
											   <label id="name_err"></label>
                                                 {!! Form::hidden('customer_id',$result[0]->customer_id, array('class' => 'form-control ' ,'id'=>"customer_id")) !!}
                               			  </div>
                           		 <?php
									 }
									 else
									 {
										 ?>


                                         <div class="form-group col-sm-3" id="existing_customer">

                                         {!! Form::label('Customer ') !!} <span class="required">*</span>
                                         <br />
                                         <select name="customer_id" id="customer_id" class="select2" style="width:100%">


                                                @foreach($customer as $k=> $v)

                                                     @if ($v->customer_id == $result[0]->customer_id)
                                                            <option value="{{ $v->customer_id}}" selected="selected" > {{ $v->prefix.' '.$v->name}}</option>

                                                  @endif
                                                @endforeach
                                        </select>
                                        <label id="customer_err" ></label>
                                        </div>
                                         <?php
									 }
								 }
								 ?>
                             <div class="form-group col-sm-3">
							 </div>
						 	 <div class="form-group col-sm-3">
								{!! Form::label('Company Name') !!}
								{!! Form::text('company',$result[0]->company, array('class' => 'form-control ' ,'id'=>"company",'placeholder'=>'Enter Company')) !!}
                            </div>
						 	<div class="form-group col-sm-3">
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
                             {!! Form::text('mobile',$result[0]->mobile, array('class' => 'form-control input-sm' ,'id'=>"mobile",'placeholder'=>'Enter Mobile No 1')) !!}
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
                             {!! Form::text('mobile_2',$result[0]->mobile_2, array('class' => 'form-control input-sm' ,'id'=>"mobile_2",'placeholder'=>'Enter Mobile No 2')) !!}
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
								{!! Form::text('mobile_3',$result[0]->mobile_3, array('class' => 'form-control input-sm' ,'id'=>"mobile_3",'placeholder'=>'Enter Mobile No 3')) !!}
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
							{!! Form::textarea('office_address',$result[0]->office_address, array('class' => 'form-control' ,'id'=>"office_address",'placeholder'=>'Enter Office Address','size'=>'20*3')) !!}
                            </div>
                        </div>


                         <div class="form-group col-sm-12">
                            <div class="form-group col-sm-6 plant_state_hide">

								{!! Form::label('Plant Location State') !!}
								<select name="project_state" id="project_state" class="select2" style="width:100%">
										<option  value="">Select</option>

										@foreach($state as $k=> $v)

											 @if ($v->state_id == $result[0]->project_state)
													<option value="{{ $v->state_id}}" selected="selected" > {{ $v->state_name}}</option>
											 @else

													<option value="{{ $v->state_id}}" > {{ $v->state_name}}</option>
										  @endif
										@endforeach
								</select>
								<label id="project_state-error" class="error" for="project_state"></label>
                            </div>
                            <div class="form-group col-sm-6 plant_city_hide">
								{!! Form::label('Plant Location City') !!}
								<select name="project_city" id="project_city" class="select2" style="width:100%">
                                    <option  value="">Select</option>

                                    @foreach($city as $k=> $v)
                                    	@if ($v->state_id == $result[0]->project_state)
											@if ($v->city_id == $result[0]->project_city)
                                 			    <option value="{{ $v->city_id}}" selected="selected" > {{ $v->city_name}}</option>
											@else

                                      			<option value="{{ $v->city_id}}" > {{ $v->city_name}}</option>
											@endif
										@endif
                                    @endforeach
								</select>
								<label id="project_city-error" class="error" for="project_city"></label>
                            </div>

                        </div>


                          <div class="form-group col-sm-12">


								<table width="100%"  class="table table-bordered" >
								  <tr>
								  	<th width="30%">{!! Form::label('Product') !!} </th>
									<th width="20%">{!! Form::label('Rate') !!}</th>
									<th width="20%">{!! Form::label('Qty') !!}</th>
									<th width="20%">{!! Form::label('Amount') !!}</th>
									<th width="10%"></th>
								 </tr>
								 <?php
								 if($action == 'insert')
								 {

								 ?>
								  <tr class="pending-user">
									<td>
										<select style="width:100%" id="p_id" name="p_id[]"  class="quatation_product_id select2"  >
										<option value="">Select</option>
										@foreach($quatation_product as $value)
										<option <?php echo ($value->p_id==$result[0]->p_id)?'selected':'';?> value="{{ $value->p_id }}">{{ $value->name }}</option>
										@endforeach
										</select>
										<label class="product_err"></label>
									</td>
									<td>
										{!! Form::text('rate[]','',array('class' => 'form-control rate' ,'id'=>"rate")) !!}
										<label class="rate_err"></label>
									</td>
									<td>
										{!! Form::text('qty[]','',array('class' => 'form-control qty' ,'id'=>"qty")) !!}
										<label class="qty_err"></label>
									</td>
									<td>
										{!! Form::text('amount[]','',array('class' => 'form-control amount' ,'id'=>"amount",'readonly'=>"readonly")) !!}
										<label class=""></label>
									</td>
									 <td>
										<span class="user-actions">
										<button  tabindex="1" type="button" class="btn btn-xs btn-success" onclick="">+</button>
										<button tabindex="1" type="button" class="btn btn-xs btn-danger" >-</button>
										</span>
									 </td>
									</tr>
								<?php
								}
								else
								{
									$p_id_arr= explode(",",$result[0]->p_id);
									$rate_arr = explode(",",$result[0]->p_rate);
									$qty_arr = explode(",",$result[0]->p_qty);
									$amount_arr = explode(",",$result[0]->p_amount);
									for($i=0; $i<count($p_id_arr);$i++)
									{
									?>
									<tr class="pending-user">
									<td>
										<select style="width:100%" id="p_id" name="p_id[]"  class="select2 quatation_product_id"  >
											<option value="">Select</option>
											@foreach($quatation_product as $value)
												<option <?php echo ($value->p_id==$p_id_arr[$i])?'selected':'';?> value="{{ $value->p_id }}">{{ $value->name }}</option>
											@endforeach
										</select>
										<label class="product_err"></label>
									</td>
									<td>
										{!! Form::text('rate[]',$rate_arr[$i],array('class' => 'form-control rate' ,'id'=>"rate")) !!}
										<label class="rate_err"></label>
									</td>
									<td>
										{!! Form::text('qty[]',$qty_arr[$i],array('class' => 'form-control qty' ,'id'=>"qty")) !!}
										<label class="qty_err"></label>
									</td>
									<td>
										{!! Form::text('amount[]',$amount_arr[$i],array('class' => 'form-control amount' ,'id'=>"amount",'readonly'=>"readonly")) !!}
										<label class=""></label>
									</td>
									 <td>
										<span class="user-actions">
										<button  tabindex="1" type="button" class="btn btn-xs btn-success" onclick="">+</button>
										<button tabindex="1" type="button" class="btn btn-xs btn-danger" >-</button>
										</span>
									 </td>
									</tr>
									<?php
									}

								}
								?>
									</table>
                            </div>

                        <div class="form-group col-sm-12">
						<div class="form-group col-sm-8">
                            {!! Form::label('Remarks') !!}

                            {!! Form::textarea('remarks',$result[0]->remarks, array('class' => 'form-control ' ,'id'=>"remarks",'placeholder'=>'Enter Remarks','size'=>'20*3')) !!}
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


	$(".insentive").change(function() {

		if ($(this).val() == 'yes')
		{
			$("#attended_div").show();
		}
		else
		{
			$("#attended_by").val('').trigger('change');
			$("#attended_div").hide();
		}
	});


	$('body').on('change','#source_id', function(){
		var source_name = $.trim($('#source_id option:selected').text().toLowerCase());
		var str2 = "visitor";
		if(source_name.indexOf(str2) != -1){
			$('#insentive_div').show();
			var insentive = $('input[name=insentive]:checked').val();
			if(insentive=='yes')
			{
				$('#attended_div').show();
			}
		}
		else
		{
			$("#insentive_no" ).prop( "checked",true);
			$("#attended_by").val('').trigger('change');
			$('#insentive_div').hide();
			$('#attended_div').hide();
		}

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

	$('#project_state').change(function() {

			var project_state = $("#project_state").val();
			$('.blockUI').show();
			$.ajax({
					type: "POST",
					url: "<?php echo $get_city;?>",
					data: { "_token": "{{ csrf_token() }}",state_id:project_state},
					success:function (res)
					{
						$("#project_city").empty().append().html(res).trigger('change.select2');
						$('.blockUI').hide();
					}
				});
	});

	$('.customer_type').change(function() {
        if (this.value == 'new')
		{
			$("#new_customer").show();
			$("#new_customer_prefix").show();
		    $("#existing_customer").hide();
			$("#address").val('');
			$("#state_id").val('').trigger('change.select2');
			$("#city_id").val('').trigger('change.select2');
			$("#mobile").val('');
			$("#mobile_2").val('');
			$("#mobile_3").val('');
			$("#office_address").val('');
			$("#landline").val('');
			$("#company").val('');
			$("#email").val('');
			$("#email_2").val('');
        }
        else if (this.value == 'existing') {
            $("#new_customer").hide();
            $("#new_customer_prefix").hide();
		    $("#existing_customer").show();
			$("#customer_id").val('').trigger('change.select2');
        }
    });

	$('#customer_id').change(function(){
		var customer_id = $("#customer_id").val();
		if(customer_id !='')
		{
			$('.blockUI').show();
			$.ajax({
					type: "POST",
					url: "<?php echo $get_customer_data;?>",
					dataType:'json',
					data: { "_token": "{{ csrf_token() }}",customer_id:customer_id},
					success:function (res)
					{
						var r = res[0].split("**");
						$("#address").val(r[0]);
						$("#state_id").val(r[1]).trigger('change');
						$("#city_id").val(r[2]).trigger('change');
						$("#mobile").val(r[3]);
						if(r[4]!='null')
						{
							$("#mobile_2").val(r[4]);
						}
						else
						{
							$("#mobile_2").val('');
						}
						if(r[5]!='null')
						{
							$("#mobile_3").val(r[5]);
						}
						else
						{
							$("#mobile_3").val('');
						}
						if(r[6]!='null')
						{
							$("#office_address").val(r[6]);
						}
						if(r[7]!='null')
						{
							$("#landline").val(r[7]);
						}
						if(r[8]!='null')
						{
							$("#company").val(r[8]);
						}
						if(r[10]!='null')
						{
							$("#email").val(r[10]);
						}
						if(r[11]!='null')
						{
							$("#email_2").val(r[11]);
						}
						if(r[12]!='null')
						{
							$("#country_id").val(r[12]).trigger('change');
						}
						if(r[13]!='null')
						{
							$("#mtype1").val(r[13]).trigger('change');
						}
						if(r[14]!='null')
						{
							$("#mtype2").val(r[14]).trigger('change');
						}
						if(r[15]!='null')
						{
							$("#mtype3").val(r[15]).trigger('change');
						}



						//For india contry in show state & city
						if(res[1]!=0)
						{
							$('.state_hide').hide();
							$('.city_hide').hide();
						}
						else
						{
							$('.state_hide').show();
							$('.city_hide').show();
						}
						$('.blockUI').hide();

					}
				});
		}
		else
		{
			alert("Please Select Customer Name")
		}
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
		/* $('.blockUI').show();
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
		}); */
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
	}); */

	$('body').on('change',"#p_id",function(e){
		var quatation_product_id = $(this).val();
		var th = $(this);
		$.ajax({
			type:"POST",
			dataType: "json",
			url:"{{ $get_product_rate }}",
			data:{quatation_product_id:quatation_product_id,_token:'{{ csrf_token() }}'},
			success:function (res)
			{

				th.closest('tr').find('#rate').val(res[0]);
				th.closest('tr').find('#qty').val('');
				th.closest('tr').find('#qty').focus();
				th.closest('tr').find('#amount').val('');
			}
		});
	});
	$('body').on('change',"#qty",function(e){
		var qty = $(this).val();
		var rate = $(this).closest('tr').find('#rate').val();

		if(qty=='')
		{
			qty=0;
		}
		if(rate=='')
		{
			rate=0;
		}
		var amount=parseFloat(qty) * parseFloat(rate);
		$(this).closest('tr').find('#amount').val(amount.toFixed(2));

	});

$('body').on('change',"#rate",function(e){
		var rate = $(this).val();
		var qty = $(this).closest('tr').find('#qty').val();

		if(qty=='')
		{
			qty=0;
		}
		if(rate=='')
		{
			rate=0;
		}
		var amount=parseFloat(qty) * parseFloat(rate);
		$(this).closest('tr').find('#amount').val(amount.toFixed(2));

	});



	_.templateSettings.variable = "element";
	var tpl = _.template($("#form_tpl").html());
	var counter = 1;
	$("body").on("click",".btn-success", function (e) {
		e.preventDefault();
		 var tplData = {
			i: counter
		};
		$(this).closest("tr").after(tpl(tplData));
			counter += 1;

		$("select.quatation_product_id").select2({placeholder: "Select"}).one('select2-focus', select2Focus).on("select2-blur", function () {
      $(this).one('select2-focus', select2Focus)
  	});


		return false;
	});

	$('body').on('click',".btn-danger",function()
	{
		var count= $('.pending-user').length;
		var value=count-1;
		if(value>=1)
		{
			$(this).closest('.pending-user').fadeOut('fast', function(){$(this).closest('.pending-user').remove();check_validation();
			});
		}

	});

	$.validator.setDefaults({ ignore: ":hidden:not(.select2)" })
	var id= $("#id").val();
	$('#frm').validate({
		rules: {
			product_id: {required: true,},
			source_id: {required: true,},
			category_id: {required: true,},
			p_id : {required: true,},
		//	customer_id: {required: true,},
			//name: {required: true,},
			country_id: {required: true,},
			mtype1: {required: true,},
		//	state_id: {required: true,},
			//mobile:{minlength: 10,maxlength: 10,},
			//mobile_2:{minlength: 10,maxlength: 10,},
			//mobile_3:{minlength: 10,maxlength: 10,},
			/* project_state: {required: true,}, */

		},
		messages:{
			source_id: {required:"Please Select Inquiry Source"},
			product_id: {required:"Please Select Inquiry For"},
			category_id: {required:"Please Select Inquiry Type "},
			p_id : {required:"Please Select Product "},
			//customer_id: {required: "Please Select Customer"},
			//name: {required: "Please Enter Name"},
			country_id: {required:"Please Select Country"},
			mtype1: {required:"Please Select Type"},
		//	state_id: {required:"Please Select State"},
			//mobile: {minlength: "Please Enter 10 Digit Mobile No",maxlength: "Please Enter 10 Digit Mobile No",},
			//mobile_2: {minlength: "Please Enter 10 Digit Mobile No",maxlength: "Please Enter 10 Digit Mobile No",},
			//mobile_3: {minlength: "Please Enter 10 Digit Mobile No",maxlength: "Please Enter 10 Digit Mobile No",},
			/* project_state: {required: "Please Select State For Plant Location",}, */

		},
	/*	submitHandler: function(form)
		{
			if(check_validation())
			{
				$(':input[type="submit"]').prop('disabled', true);
				form.submit();
			}
		},
		errorPlacement: function(error, element) {
			error.appendTo(element.parent("div"));
		},*/
	});


	$('#frm').on('submit', function(e){
			e.preventDefault();
			var formData = new FormData(this);
			var form = this;
			if($("#frm").valid() )
			{
				var cust_status = 0;
				var customer_type = $("input[name='customer_type']:checked").val();
				if(customer_type == 'new')
				{
					var name = $("#name").val();
					if(name == '')
					{

						var str = 'Please Enter Name';
						$("#name_err").html(str.fontcolor("red"));
						cust_status = 1;
						return false;
					}
					else
					{
						$("#name_err").html('');
					}
				}
				else
				{
					var customer_id = $("#customer_id").val();
					if(customer_id == '')
					{
						var str = 'Please Select Customer';
						$("#customer_err").html(str.fontcolor("red"));
						 cust_status =1;
						 return false;
					}
					else
					{
						$("#customer_err").html('');
					}
				}

				//For India Contry Mate 10 Digit and Numeric & Other Contry For Any Number Valid
				var country_name = $("#country_id option:selected").text();
				var mobile = $.trim($("#mobile").val());
				var mobile_2 = $.trim($("#mobile_2").val());
				var mobile_3 = $.trim($("#mobile_3").val());

				if($.trim(country_name).toLowerCase()=='india')
				{
					if(mobile.length != 10 && mobile != '')
					{
						alert('Please Enter 10 Digit Mobile Number in Mobile 1.');
						$("#mobile").focus();
						return false;
					}
					else if(! $.isNumeric(mobile) && mobile != '')
					{
						alert('Please Enter Valid Mobile No in Mobile 1.');
						$("#mobile").focus();
						return false;
					}

					if(mobile_2.length != 10 && mobile_2 != '')
					{
						alert('Please Enter 10 Digit Mobile Number in Mobile 2.');
						$("#mobile_2").focus();
						return false;
					}
					else if(! $.isNumeric(mobile_2) && mobile_2 != '')
					{
						alert('Please Enter Valid Mobile No in Mobile 2.');
						$("#mobile_2").focus();
						return false;
					}

					if(mobile_3.length != 10 && mobile_3 != '')
					{
						alert('Please Enter 10 Digit Mobile Number in Mobile 3.');
						$("#mobile_3").focus();
						return false;
					}
					else if(! $.isNumeric(mobile_3) && mobile_3 != '')
					{
						alert('Please Enter Valid Mobile No in Mobile 3.');
						$("#mobile_3").focus();
						return false;
					}
				}
				if( cust_status == 0)
				{
					var state_status = 0;
						if($('.state_hide').is(':visible'))
						{
							var state_data = $("#state_id").val();

							if(state_data == '')
							{

								var str = 'Please Select State';
								$("#state_err").html(str.fontcolor("red"));
								state_status = 1
								return false;
							}
							else
							{

								$("#state_err").html('');
							}
						}
						else
						{

							$("#state_err").html('');
						}

					if(state_status == 0)
					{
						$(':input[type="submit"]').prop('disabled', true);
						form.submit();
					}
					else
					{
						return false;
					}

				}
				else
				{
						return false;
				}

			}
			else
			{
					return false;
			}
	});

});
</script>
<script  type="text/html" id="form_tpl">
	<tr class="pending-user">

	  <td>
		<select style="width:100%" id="p_id" name="p_id[]"  class="quatation_product_id select2">
			<option value="">Select</option>
			@foreach($quatation_product as $value)
				<option  value="{{ $value->p_id }}">{{ $value->name }}</option>
			@endforeach
		</select>
		<label class="product_err"></label>
	  </td>
	 <td>
	 	{!! Form::text('rate[]','',array('class' => 'form-control rate' ,'id'=>"rate")) !!}
		 <label class="rate_err"></label>
	</td>
	 <td>
	 	{!! Form::text('qty[]','',array('class' => 'form-control qty' ,'id'=>"qty")) !!}
		 <label class="qty_err"></label>
	</td>
	<td>
	{!! Form::text('amount[]','',array('class' => 'form-control amount' ,'id'=>"amount",'readonly'=>"readonly")) !!}
	<label class=""></label>
	</td>
	  <td>
		<span class="user-actions">
			<button  tabindex="1" type="button" class="btn btn-xs btn-success" onclick="">+</button>
			<button tabindex="1" type="button" class="btn btn-xs btn-danger" >-</button>
		</span>
	 </td>
	</tr>
</script>
@endsection