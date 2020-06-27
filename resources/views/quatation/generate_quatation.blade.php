@extends('template.template')
@section('content')
<?php
error_reporting(0);
$url = $controller_name.'@add_quatation';
$btn= "Generate";
$get_product_rate = URL::action($controller_name.'@get_rate');
$mobile_check = URL::action($controller_name.'@mobile_check');
$email_check = URL::action($controller_name.'@email_check');
$get_customer_data = URL::action($controller_name.'@get_customer_data');
$get_city = URL::action($controller_name.'@get_city');
$get_country_zone = URL::action($controller_name.'@get_country_zone');
$get_sample_quatation = URL::action($controller_name.'@get_sample_quatation');
$get_cur_type_url = URL::action($controller_name.'@get_cur_type');
$get_inq_data_url = URL::action($controller_name.'@get_inq_data');
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


				{!! Form::hidden('inquiry_id',$inquiry_id, array('id'=>"inquiry_id")) !!}
				{!! Form::hidden('q_master_id',$q_master_id, array('id'=>"q_master_id")) !!}



                      <div class="form-group col-sm-12">
							<input type="hidden" name="quatation_no" id="quatation_no" value="<?php echo $quatation_no; ?>" />
                           <?php
						   /* <div class="form-group col-sm-3">
                            {!! Form::label('Quotation No') !!}

                                 {!! Form::text('quatation_no',$quatation_no, array('class' => 'form-control ' ,'id'=>"quatation_no",'required' => 'required','readonly'=>'readonly')) !!}
                            </div> */
							?>
                       		<div class="form-group col-sm-3">
                            {!! Form::label('Quotation Date') !!}
                            {!! Form::text('quatation_date',date('d-m-Y'), array('class' => 'form-control ' ,'id'=>"quatation_date",'required' => 'required','readonly'=>'readonly')) !!}
                            </div>

							<div class="form-group col-sm-3">
                            {!! Form::label('Quotation Time') !!}
                            {!! Form::text('quatation_time',date('h:i a'), array('class' => 'form-control ' ,'id'=>"quatation_time",'required' => 'required','readonly'=>'readonly')) !!}
                            </div>

							<div class="form-group col-sm-3">
                            {!! Form::label('Generated Person') !!}
                            {!! Form::text('inquiry_person',Session::get('raj_user'), array('class' => 'form-control ' ,'id'=>"inquiry_person",'required' => 'required','readonly'=>'readonly')) !!}
                            </div>
                    </div>

              	   <div class="form-group col-sm-12">
                            <div class="form-group col-sm-3">
                            {!! Form::label('Inquiry No') !!}

                                 {!! Form::text('inquiry_no',$result[0]->inquiry_no, array('class' => 'form-control ' ,'id'=>"inquiry_no",'required' => 'required','readonly'=>'readonly')) !!}
                            </div>
                            <div class="form-group col-sm-3">
                             {!! Form::label('Inquiry Date') !!}

                                 {!! Form::text('inquiry_date',date('d-m-Y', strtotime($result[0]->inquiry_date)), array('class' => 'form-control ' ,'id'=>"inquiry_date",'required' => 'required','readonly'=>'readonly')) !!}
                            </div>
							<div class="form-group col-sm-3">
							  {!! Form::label('Inquiry Time') !!}

									{!! Form::text('inquiry_date',date('h:i a', strtotime($result[0]->inquiry_time)), array('class' => 'form-control ' ,'id'=>"inquiry_time",'required' => 'required','readonly'=>'readonly')) !!}

							</div>
							<div class="form-group col-sm-3">
                            {!! Form::label('Inquiry Person') !!}
                            {!! Form::text('inquiry_person',$result[0]->username, array('class' => 'form-control ' ,'id'=>"inquiry_person",'required' => 'required','readonly'=>'readonly')) !!}
                            </div>
                        </div>

                    <div class="form-group col-sm-12">

						<div class="form-group col-sm-3">
                               {!! Form::label('Inquiry Source') !!} <span class="required">*</span>
								<?php $source_text='';?>
                               <select name="source_id" id="source_id" class="select2" <?php if($action == 'update'){echo "disabled";}?> style="width:100%">
                                        <option value="">Select</option>

                                        @foreach($source as $k=> $v)
                                             <?php
											 if ($v->source_id == $result[0]->source_id)
											{
												$source_text = strtolower($v->source_name);
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

							{!! Form::hidden('source_id',$result[0]->source_id, array('id'=>"source_id")) !!}
							{!! Form::hidden('source_text',$source_text, array('id'=>"source_text")) !!}
                       </div>
					   <div class="form-group col-sm-3" style="<?php echo (strpos($source_text,'visitor') !== false)?'':'display:none';?>" id="insentive_div">

								{!! Form::label('Incentive') !!} <span class="required">*</span>
								<br />
								<?php
								if($result[0]->insentive == 'no')
								{
								?>
									{{ Form::radio('insentive', 'no', true, ['class' => 'insentive']) }}No
									{{ Form::radio('insentive', 'yes','', ['class' => 'insentive' ]) }}Yes

								<?php
								}
								else
								{
								?>
									{{ Form::radio('insentive', 'no', '', ['class' => 'insentive']) }}No
									{{ Form::radio('insentive', 'yes',true, ['class' => 'insentive' ]) }}Yes
								<?php
								}
								?>

							</div>

							<div class="form-group col-sm-3" style="<?php echo ($result[0]->insentive == 'yes')?'':'display:none';?>" id="attended_div">
								{!! Form::label('Attended By') !!}
								<br />
								<select name="attended_by" id="attended_by" class="select2 " style="width:100%">
									<option  value="">Select</option>
									@foreach($employee as $k=> $v)

											@if ($v->emp_id == $result[0]->attended_by)
												<option value="{{ $v->emp_id}}" selected="selected" > {{ $v->name }}</option>
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

							?>
                            </div>

                    </div>

					<div class="form-group col-sm-12">
						<div class="form-group col-sm-3">

                        {!! Form::label('Inquiry For') !!} <span class="required">*</span>
                     	<select name="product_id" id="product_id" class="select2" style="width:100%">
                                    <option value="">Select</option>

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
						<div class="form-group col-sm-3"></div>
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
					</div>


						<div class="form-group col-sm-12">
                          	<?php
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
                             			 	{!! Form::text('name',$result[0]->name, array('class' => 'form-control' ,'id'=>"name",'placeholder'=>'Enter Name','required' => 'required')) !!}
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
                                                            <option value="{{ $v->customer_id}}" selected="selected" > {{$v->prefix.' '.$v->name}}</option>

                                                  @endif
                                                @endforeach
                                        </select>
                                        <label id="customer_err" ></label>
                                        </div>
                                         <?php
									 }

								 ?>


                            <div class="form-group col-sm-3"></div>

                            <div class="form-group col-sm-3">
								{!! Form::label('Company Name') !!}
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
											<option value="{{ $v->country_id}}" selected="selected" > {{ $v->country_name}}</option>
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
													@if($v->state_id == $result[0]->state_id)
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
										@if($v->state_id == $result[0]->state_id)
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
								{!! Form::text('mobile_2',$result[0]->mobile_2, array('class' => 'form-control ' ,'id'=>"mobile_2",'placeholder'=>'Enter Mobile No 2')) !!}
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
                            {!! Form::text('landline',$result[0]->landline, array('class' => 'form-control numberonly' ,'id'=>"landline",'placeholder'=>'Enter Landline')) !!}
                            </div>
							 <div class="form-group col-sm-4">
                            {!! Form::label('Email Address 1') !!}
                        	{!! Form::email('email',$result[0]->email, array('class' => 'form-control ' ,'id'=>"email",'placeholder'=>'Enter Email')) !!}
                            </div>
							<div class="form-group col-sm-4">
                            {!! Form::label('Email Address 2') !!}
                        	{!! Form::email('email_2',$result[0]->email_2, array('class' => 'form-control ' ,'id'=>"email_2",'placeholder'=>'Enter Email 2')) !!}
                            </div>
                        </div>

                       <div class="form-group col-sm-12">
                            <div class="form-group col-sm-3">
                            {!! Form::label('Office Address') !!}
							{!! Form::textarea('office_address',$result[0]->office_address, array('class' => 'form-control ' ,'id'=>"office_address",'placeholder'=>'Enter Office Address','size'=>'20*3')) !!}
                            </div>
                            <div class="form-group col-sm-3"></div>
                            <div class="form-group col-sm-3">
                            {!! Form::label('Address') !!}
                            {!! Form::textarea('address',$result[0]->address, array('class' => 'form-control ' ,'id'=>"address",'placeholder'=>'Enter Address','size'=>'20*3')) !!}
                            </div>
                        </div>


                         <div class="form-group col-sm-12">
                            <div class="form-group col-sm-3 plant_state_hide">

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
                            <div class="form-group col-sm-3"></div>
                            <div class="form-group col-sm-3 plant_city_hide">
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

							 <div class="form-group col-sm-12">
								{!! Form::label('Remarks') !!}

								{!! Form::textarea('remarks',$result[0]->remarks, array('class' => 'form-control ' ,'id'=>"remarks",'placeholder'=>'Enter Remarks','size'=>'20*3')) !!}
                            </div>
                        </div>

                                <div class="form-group col-sm-12">
                                    <div class="form-group col-sm-4">
                                        {!! Form::label('Sample Quatation') !!}
                                        <select style="width:100%" id="sq_id" name="sq_id"  class="select2 sq_id select2">
                                            <option value="">Select</option>
                                            @foreach($sample_quatation as $value)
                                            <option value="{{ $value->sq_id }}">{{ $value->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Discount %</label>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" min="0" step="0.01" name="commercial_proposal_discount_perc" id="commercial_proposal_discount_perc" class="form-control" />
                                    </div>
                                    <div class="col-md-4">
                                        <input onclick="update_value();" type="button" name="commercial_proposal_discount_perc_btn" class="btn btn-warning" value="Update" />
                                    </div>
                                </div>
					<!-- Sample Quatation Change Start -->
					<div class="sample_change">

					<div class="form-group col-sm-12">
                     	<div class="form-group col-sm-12">
							<div class="row">
                                <div class="col-md-4" style="margin-top: 40px !important;">
                                    {!! Form::label('Commercial Proposal') !!}<span class="required"> *</span>
                                </div>
                            </div>
                            <table width="100%"  class="table table-bordered" >
									<tr>
										<th width="8%">Sr No</th>
										<th width="42%">Equipment Name<span class="required"> *</span></th>
										<th width="15%">Rate <span class="required"> *</span></th>
										<th width="15%">Qty <span class="required"> *</span></th>
										<th width="15%">Amount <span class="required"> *</span></th>
										<th width="5%">Manage</th>
									</tr>
									<tbody  id="addrow">
									<?php
									$p_id_arr= explode(",",$result[0]->p_id);
									$rate_arr = explode(",",$result[0]->p_rate);
									$qty_arr = explode(",",$result[0]->p_qty);
									$amount_arr = explode(",",$result[0]->p_amount);
									if($cur_type == 'dollar')
									{
										$r=1;
										$amount_sum = 0;
										for($i=0; $i<count($p_id_arr);$i++)
										{
											$new_rate = number_format((float)floatval($rate_arr[$i])/floatval($doller_rate),2,'.','');
											$new_rate = ceil($new_rate);
											$new_amount = floatval($new_rate)*floatval($qty_arr[$i]);
											$amount_sum += $new_amount;

									?>
											<tr class="pending-user">
											  <td>{{ $r }}</td>
											  <td>
												<select style="width:100%" id="quatation_product_id" name="quatation_product_id[]"  class="select2 quatation_product_id select2">
													<option value="">Select</option>
													@foreach($quatation_product as $value)
														<option <?php echo ($value->p_id==$p_id_arr[$i])?'selected':'';?> value="{{ $value->p_id }}">{{ $value->name }}</option>
													@endforeach
												</select>
												<label class=""></label>
											  </td>
											  <td>
												{!! Form::text('rate[]',$new_rate,array('class' => 'form-control rate' ,'id'=>"rate")) !!}
												<label class=""></label>
											  </td>
											  <td>
												{!! Form::text('qty[]',$qty_arr[$i], array('class' => 'form-control qty' ,'id'=>"qty",'numberonly'=>'numberonly')) !!}
												<label class=""></label>
											  </td>

											  <td>
												{!! Form::text('amount[]',$new_amount,array('class' => 'form-control amount' ,'id'=>"amount",'readonly'=>'readonly')) !!}
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
										$r++;
										}
									}
									else
									{
										$r=1;
										$amount_sum = 0;
										for($i=0; $i<count($p_id_arr);$i++)
										{
											$amount_sum += $amount_arr[$i];
											?>
										<tr class="pending-user">
											  <td>{{ $r }}</td>
											  <td>
												<select style="width:100%" id="quatation_product_id" name="quatation_product_id[]"  class="select2 quatation_product_id select2">
													<option value="">Select</option>
													@foreach($quatation_product as $value)
														<option <?php echo ($value->p_id==$p_id_arr[$i])?'selected':'';?> value="{{ $value->p_id }}">{{ $value->name }}</option>
													@endforeach
												</select>
												<label class=""></label>
											  </td>
											  <td>
												{!! Form::text('rate[]',$rate_arr[$i],array('class' => 'form-control rate' ,'id'=>"rate")) !!}
												<label class=""></label>
											  </td>
											  <td>
												{!! Form::text('qty[]',$qty_arr[$i], array('class' => 'form-control qty' ,'id'=>"qty",'numberonly'=>'numberonly')) !!}
												<label class=""></label>
											  </td>

											  <td>
												{!! Form::text('amount[]',$amount_arr[$i],array('class' => 'form-control amount' ,'id'=>"amount",'readonly'=>'readonly')) !!}
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
											$r++;
										}
									}

										?>
										<?php /* <tr>
											<td></td>
											<td></td>
											<td></td>
											<td>{!! Form::hidden('total_qty',0, array('class' => 'form-control' ,'id'=>"total_qty",'required' => 'required','readonly'=>'readonly')) !!} <b>Gross Amount</b></td>
											<td>{!! Form::text('gross_amount',number_format((float)array_sum($amount_arr),2,'.',''), array('class' => 'form-control' ,'id'=>"gross_amount",'required' => 'required','readonly'=>'readonly')) !!}</td>
											<td></td>
										</tr> */ ?>

										<?php /* <tr>
											<td></td>
											<td></td>
											<td></td>
											<td><b>GST @ 18%</b></td>
											<td>{!! Form::text('gst_amount',number_format((float)(array_sum($amount_arr) * 18)/100,2,'.',''), array('class' => 'form-control' ,'id'=>"gst_amount",'required' => 'required','readonly'=>'readonly')) !!}</td>
											<td></td>
										</tr> */ ?>
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td><b>Total</b></td>
											<td>
												<div class="input-group">
													<input readonly value="<?php echo number_format((float)$amount_sum,2,'.',''); ?>" type="text" id="total" name="total" class="form-control amountonly">
												</div>
											</td>
											<td></td>
										</tr>
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td><b>Discount</b></td>
											<td>
												<div class="input-group">
													<input placeholder="Enter Discount" type="text" id="discount" name="discount" class="form-control amountonly">
												</div>
											</td>
											<td></td>
										</tr>
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td><b>Total Amount</b></td>
											<td>
												<div class="input-group">
													<span id="cur_change" class="input-group-addon"><i class="<?php echo ($cur_type == 'inr') ? 'fa fa-inr' : 'fa fa-dollar'; ?>"></i></span> <!-- 	fa fa-inr -->
													<input type="text" id="total_amount" name="total_amount" value="<?php echo number_format((float)$amount_sum,2,'.',''); ?>" readonly class="form-control">
												</div>
											</td>
											<td></td>
										</tr>
									</tbody>
								 </table>
                               </div>
                     </div>

					<div class="form-group col-sm-12">
                     	<div class="form-group col-sm-12">
						 	{!! Form::label('Specification') !!}
						</div>
						<div class="form-group col-sm-12">
						 <?php /*<select name="specification_id[]" id="specification_id" class="select2" multiple=multiple style="width:100%">

						  @foreach($specification as $k=> $v)
						  <option value="{{ $v->specification_id}}" > {{ $v->specification}}</option>
						   @endforeach
           				</select>*/
							 foreach($specification as $k=> $v)
							 {
								 ?>
								 <div class="form-group col-sm-4 ">
								 {{ Form::checkbox('specification_id[]', $v->specification_id, null, ['id' => 'specification_id','class'=>'specification_id']) }}&nbsp;{{ $v->specification}}
								 </div>
								 <?php
							 }

							?>
						 </div>
					</div>
					<?php
					$spe_id_arr = array();
					?>
					 @foreach($specification as $k=> $v)

						<div class="form-group col-sm-12" id="spec_<?php echo $v->specification_id;?>" style="display:none">

						<h3><?php echo $v->specification;?></h3>

							<table width="100%"  class="table table-bordered" >

							<tr>
								<th width="30%">{!! Form::label('Specification') !!}</th>
								<th width="70%">{!! Form::label('Description') !!}</th>
							</tr>

							<?php
							$spe_name_arr = explode("+++++",$v->spe_name);
                            $spe_value_arr = explode("+++++",$v->spe_value);
                            for($i=0; $i<count($spe_name_arr);$i++)
							{
							?>
							  <tr class="">
								<td >
                               	{!! Form::hidden(''.$v->specification_id.'_spe_name[]',$spe_name_arr[$i],array('class' => 'form-control name' ,'id'=>"name" )) !!}
								  <?php echo  $spe_name_arr[$i];?>
                              </td>
                               <td >
                                {!! Form::text(''.$v->specification_id.'_spe_value[]',$spe_value_arr[$i],array('class' => 'form-control value' ,'id'=>"value")) !!}
                               </td>

                             </tr>
							 <?php
							}
							?>

							 </table>

						</div>
						<?php
						$spe_id_arr[].= $v->specification_id;?>

					@endforeach
					<input type="hidden" id="spec_id_arr" value="<?php echo implode(",",$spe_id_arr);?>">


					<!--End Sample Quatation Change-->
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

	function get_price()
	{
		var qty =$("input[id=qty]").map(function(){return $(this).val();}).get().join(",");
		var strqty = qty;
		arr = strqty.split(',');
		var total_qty=0;
				for(i=0; i < arr.length; i++)
				{
					if(arr[i]!='')
					{
						total_qty += parseFloat(arr[i]);
					}
				}
		$('#total_qty').val(total_qty);

		var amount =$("input[id=amount]").map(function(){return $(this).val();}).get().join(",");
		var stramount = amount;
		arr = stramount.split(',');
		var gross_amount=0;
		for(i=0; i < arr.length; i++)
		{
			if(arr[i]!='')
			{
				gross_amount += parseFloat(arr[i]);
			}
		}
		var discount = $('#discount').val();
		if(isNaN(discount) || discount == '')
		{
			discount = 0;
		}
		$('#total').val(gross_amount.toFixed(2))
		var totalAmount = parseFloat(gross_amount) - parseFloat(discount);

		$('#total_amount').val(totalAmount.toFixed(2));

		/* var gst_amount=(parseFloat(gross_amount) * 18) / 100;

		if(isNaN(gst_amount))
		{
			gst_amount=0;
		}
		$('#gst_amount').val(gst_amount.toFixed(2));

		var total_amount = parseFloat(gross_amount) + parseFloat(gst_amount);
		if(isNaN(total_amount))
		{
			total_amount=0;
		}
		$('#total_amount').val(total_amount.toFixed(2)); */
	}
	$('body').on('keyup','#discount',function(){
		get_price();
	});

	$(".insentive").change(function() {
		if (this.value == 'yes')
		{
			$("#attended_div").show();
		}
		else
		{
			$("#attended_by").val('').trigger('change');
			$("#attended_div").hide();
		}
	});


		$('#customer_id').change(function(){
		var customer_id = $("#customer_id").val();
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
					$("#state_id").val(r[1]).trigger('change.select2');
					$("#city_id").val(r[2]).trigger('change.select2');
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
						$("#country_id").val(r[12]).trigger('change.select2');
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

	$('#country_id').change(function() {
		var country_id = $(this).val();
		var inquiry_id = $('#inquiry_id').val();
		$('.blockUI').show();
		$.ajax({
				type: "POST",
				url: "<?php echo $get_inq_data_url;?>",
				data: { "_token": "{{ csrf_token() }}",country_id:country_id,inquiry_id:inquiry_id},
				success:function(res)
				{
					$(".sample_change").empty().html(res);
					$("select.quatation_product_id").select2({placeholder: "Select"}).one('select2-focus', select2Focus).on("select2-blur", function () {
					$(this).one('select2-focus', select2Focus) });
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

	$('body').on('change','#email_2', function(){
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
	});
	$('body').on('change',"#country_id",function(e){
		var country_id = $(this).val();
		$.ajax({
			type:"POST",
			url:"{{ $get_cur_type_url }}",
			data:{country_id:country_id,_token:'{{ csrf_token() }}'},
			success:function (res)
			{
				$('#cur_change').html(res);
			}
		});
	});
	$('body').on('change',"#sq_id",function(e){
		var sq_id = $(this).val();
		var country_id = $('#country_id').val();

		$.ajax({
			type:"POST",
			url:"{{ $get_sample_quatation }}",
			data:{sq_id:sq_id,country_id:country_id,_token:'{{ csrf_token() }}'},
			success:function (res)
			{
				$('.sample_change').html(res);
				$("select.quatation_product_id").select2({placeholder: "Select"}).one('select2-focus', select2Focus).on("select2-blur", function () {
				  $(this).one('select2-focus', select2Focus)
				});
                                $('input[name="commercial_proposal_discount_perc"]').val('');
			}
		});
	});

	$('body').on('change',"#quatation_product_id",function(e){
		var quatation_product_id = $(this).val();
		var th = $(this);
		var country_id = $('#country_id').val();
		$.ajax({
			type:"POST",
			dataType: "json",
			url:"{{ $get_product_rate }}",
			data:{quatation_product_id:quatation_product_id,country_id:country_id,_token:'{{ csrf_token() }}'},
			success:function (res)
			{
				th.closest('tr').find('#rate').val(res[0].toFixed(2));
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
		get_price();
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
		get_price();
	});

 	$('body').on('click','input[type="checkbox"]',function(){
		var specification_id = $(this).val();
		if($(this).prop("checked") == true)
		{

			$("#spec_"+specification_id).show();
		}
		else if($(this).prop("checked") == false)
		{

			$("#spec_"+specification_id).hide();
		}
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

		sr_change();
		get_price();
		return false;
	});

	$('body').on('click',".btn-danger",function()
	{
		var count= $('.pending-user').length;
		var value=count-1;
		if(value>=1)
		{
			$(this).closest('.pending-user').fadeOut('fast', function(){$(this).closest('.pending-user').remove();	get_price();sr_change();
			});
		}

	});
	function sr_change()
	{
		$(".pending-user").each(function(i)
		{
			$("td", this).eq(0).text(i+1);
		});
	}

	$.validator.setDefaults({ ignore: ":hidden:not(.select2)" })
	var id= $("#id").val();
	$('#frm').validate({
		rules: {
			product_id: {required: true,},
			source_id: {required: true,},
			category_id: {required: true,},
			p_id : {required: true,},
			country_id: {required: true,},
		},
		messages:{
			source_id: {required:"Please Select Inquiry Source"},
			product_id: {required:"Please Select Inquiry For"},
			category_id: {required:"Please Select Inquiry Type "},
			p_id : {required:"Please Select Product "},
			country_id: {required:"Please Select Country"},
		},

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
							var p_a = [];
							var q_a = [];
							var r_a = [];
							$('select.quatation_product_id').each(function ()
							{

								if($(this).val() == '' )
								{
									var str = 'Please Select Product';
									$(this).closest("td").find("label").html(str.fontcolor("red"));
									p_a.push(0);
								}
								else
								{
									$(this).closest("td").find("label").html('');
									p_a.push(1);
								}
							});
							$('.qty').each(function ()
							{

								if($(this).val() == '' )
								{
									var str = 'Please Enter Quantity';
									$(this).closest("td").find("label").html(str.fontcolor("red"));
									q_a.push(0);

								}
								else
								{
									$(this).closest("td").find("label").html('');
									q_a.push(1);
								}

							});
							$('.rate').each(function ()
							{

								if($(this).val() == '')
								{
									var str = 'Please Enter Rate';
									$(this).closest("td").find("label").html(str.fontcolor("red"));
									r_a.push(0);
								}
								else
								{
									$(this).closest("td").find("label").html('');
									r_a.push(1);
								}

							});

							var p = p_a.indexOf(0);
							var q = q_a.indexOf(0);
							var r = r_a.indexOf(0);

							console.log(p);
							console.log(q);
							console.log(r);

							if( p != '-1')
							{

								return false;
							}
							else
							{
								if( r != '-1')
								{
									return false;
								}
								else
								{
									if( q != '-1')
									{
										return false;
									}
									else
									{
										$(':input[type="submit"]').prop('disabled', true);
										form.submit();
									}
								}
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

			}
			else
			{
					return false;
			}
	});

});
    function update_value(){
        var commercial_proposal_discount_perc = $('input[name="commercial_proposal_discount_perc"]').val();
        if(commercial_proposal_discount_perc > 0){
            var discount_amount = 0;
            $('input[name="rate[]"]').each( function(){
                var qty = $(this).closest('tr').find('#qty').val();
                var dis_amt = (($(this).val())*(commercial_proposal_discount_perc))/(100);
                var rate = ($(this).val())-(dis_amt);
                if (qty == ''){
                    qty = 0;
                }
                if (rate == ''){
                    rate = 0;
                }
                $(this).val(rate);
                var amount = parseFloat(qty) * parseFloat(rate);
                $(this).closest('tr').find('#amount').val(amount.toFixed(2));
                discount_amount += parseFloat(qty) * parseFloat(dis_amt);//discount addition
            });
            
            var amount = $("input[id=amount]").map(function () {
                return $(this).val();
            }).get().join(",");
            var stramount = amount;
            arr = stramount.split(',');
            var gross_amount = 0;
            for (i = 0; i < arr.length; i++)
            {
                if (arr[i] != '')
                {
                    gross_amount += parseFloat(arr[i]);
                }
            }
            $('#total').val(gross_amount);
            $('#total_amount').val(gross_amount);
        }
    }
</script>
<script  type="text/html" id="form_tpl">
	<tr class="pending-user">
	  <td></td>
	  <td>
		<select style="width:100%" id="quatation_product_id" name="quatation_product_id[]"  class="form-control col-md-2 quatation_product_id select2">
			<option value=""></option>
			@foreach($quatation_product as $value)
				<option  value="{{ $value->p_id }}">{{ $value->name }}</option>
			@endforeach
		</select>
		<label class=""></label>
	  </td>
	  <td>
		{!! Form::text('rate[]','',array('class' => 'form-control rate' ,'id'=>"rate")) !!}
		<label class=""></label>
	  </td>
	  <td>
		{!! Form::text('qty[]','', array('class' => 'form-control qty' ,'id'=>"qty",'numberonly'=>'numberonly')) !!}
		<label class=""></label>
	  </td>
	  <td>
		{!! Form::text('amount[]','',array('class' => 'form-control amount' ,'id'=>"amount",'readonly'=>'readonly')) !!}
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