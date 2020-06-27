<?php
	$mobile_check = URL::action($controller_name.'@mobile_check');
	$email_check = URL::action($controller_name.'@email_check');
	$customer_url = URL::action($controller_name.'@customer_update');
	$follow_up_url = URL::action($controller_name.'@follow_up_add');
	$document_url =  URL::action($controller_name.'@document_add');
	$visitor_url = URL::action($controller_name.'@visitor_add');
	$send_revise_url = URL::action($controller_name.'@revise_send');
	$prise_issue_url = URL::action($controller_name.'@prise_issue');
	$hot_list_url = URL::action($controller_name.'@hot_list');
	$send_address_url = URL::action($controller_name.'@send_address');
	$order_book_url = URL::action($controller_name.'@order_book');
	$regret_url = URL::action($controller_name.'@regret_add');
	$get_quatation_date = URL::action($controller_name.'@get_quatation_date');
	$send_mail_url = URL::action($controller_name.'@send_mail');
	$send_reminder_mail_url = URL::action($controller_name.'@send_reminder_mail');
	$visitor_form_no_url = URL::action($controller_name.'@visitor_form_no');
	$visitor_form_url = URL::action($controller_name.'@visitor_form_data');
	$get_state_url = URL::action($controller_name.'@get_state');
	$get_city_url = URL::action($controller_name.'@get_city');
	error_reporting(0);
?>
<style>
.mybtn
{
	width:100%;
}

.bootstrap-timepicker-widget {

    z-index: 3000 !important;
}
</style>
	  <div class="row">

            <div class="col-xs-12">
               <div class="nav-tabs-custom">
           		 <ul class="nav nav-tabs">
                     <li class="active"><a href="#customer_detail" data-toggle="tab" >Customer Detail</a></li>
                     <li class=""><a href="#follow_up" data-toggle="tab" > Follow Up</a></li>
                     <li class=""><a href="#document_detail" data-toggle="tab" > Document Detail</a></li>
                     <li class=""><a href="#visiting_detail" data-toggle="tab">Visiting Detail</a></li>
                 </ul>
            	<div class="tab-content">
              		<div class="tab-pane active" id="customer_detail">
						{!! Form::open(array( 'method' => 'post' , 'files' => true ,'id'=>"customer_frm",'name'=>"customer_frm",'class'=>"customer_frm form-horizontal"))!!}
						{!! Form::hidden('inquiry_id',$result[0]->inquiry_id, array('id'=>"inquiry_id")) !!}
						{!! Form::hidden('quatation_id',$result[0]->quatation_id, array('id'=>"quatation_id")) !!}
						{{ csrf_field() }}
						<div class="box box-warning">
							<div class="box-body">
								<div class="form-group col-sm-12">
									<div class="col-sm-4">
									{!! Form::label('Quotation No') !!}
									{!! Form::text('quatation_no',$result[0]->quatation_no, array('class' => 'form-control ' ,'id'=>"quatation_no",'required' => 'required','readonly'=>'readonly')) !!}
									</div>
									<div class="col-sm-4">
									{!! Form::label('Quotation Date') !!}

									{!! 	Form::text('quatation_date',date('d-m-Y',strtotime($result[0]->quatation_date)), array('class' => 'form-control ' ,'id'=>"quatation_date",'required' => 'required','readonly'=>'readonly')) !!}
									</div>
									<div class="col-sm-4">
									<?php
										$quatation_person = DB::table('users')->where('id',$result[0]->quatation_person)->get();
									?>
									{!! Form::label('Quot. Person') !!}
									{!! Form::text('quatation_person',$quatation_person[0]->username, array('class' => 'form-control ' ,'id'=>"quatation_person",'required' => 'required','readonly'=>'readonly')) !!}
									</div>
								</div>
								   <div class="form-group col-sm-12">
										<div class="col-sm-4">

											{!! Form::label('Inquiry For') !!} <span class="required">*</span>
											<select name="product_id" id="product_id" class="select2" style="width:100%" disabled="disabled">
														<option value="">Select</option>

														@foreach($product as $k=> $v)

															 @if ($v->product_id == $result[0]->product_id)
																<option value="{{ $v->product_id}}" selected="selected" > {{ $v->product_name}}</option>
															 @else
																<option value="{{ $v->product_id}}" > {{ $v->product_name}}</option>
														  @endif
														@endforeach
											</select>
											{!! Form::hidden('product_id',$result[0]->product_id, array('id'=>"product_id")) !!}
											</div>
											 <div class="col-sm-4">
													{!! Form::label('Inquiry Type') !!} <span class="required">*</span>
													 <select name="category_id" id="category_id" class="select2" style="width:100%" disabled="disabled">
																<option  value="">Select</option>

																@foreach($category as $k=> $v)
																	 @if ($v->category_id == $result[0]->category_id)
																			<option value="{{ $v->category_id}}" selected="selected" > {{ $v->category_name}}</option>
																	 @else
																			<option value="{{ $v->category_id}}" > {{ $v->category_name}}</option>
																	 @endif
																@endforeach
													</select>
													{!! Form::hidden('category_id',$result[0]->category_id, array('id'=>"category_id")) !!}
											</div>
											<div class="col-sm-4">
											   {!! Form::label('Inquiry Source') !!} <span class="required">*</span>
											   <select name="source_id" id="source_id" class="select2" style="width:100%" disabled="disabled">
															<option value="">Select</option>
															@foreach($source as $k=> $v)
																 <?php
																if ($v->source_id == $result[0]->source_id)
																{
																	$source_text = $v->source_name;
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
												{!! Form::hidden('source_id',$result[0]->source_id, array('id'=>"source_id")) !!}
												{!! Form::hidden('source_text',$source_text, array('id'=>"source_text")) !!}
											</div>
									</div>

									<div class="form-group col-sm-12">
										<div class="col-sm-4" id="existing_customer">
											 {!! Form::label('Client Name') !!} <span class="required">*</span>
											 <br />
											 <select name="customer_id" id="customer_id" class="select2" style="width:100%" disabled="disabled">
													@foreach($customer as $k=> $v)
														@if ($v->customer_id == $result[0]->customer_id)
																<option value="{{ $v->customer_id}}" selected="selected" > {{ $v->prefix.' '.$v->name }}</option>
													  @endif
													@endforeach
											</select>
											<input type="hidden" name="customer_id" id="customer_id" value="{{$result[0]->customer_id}}" />
										</div>
										<div class="col-sm-4">
											{!! Form::label('Client Category') !!}
											<select name="client_category_id" id="client_category_id" class="select2 customer_category_id" style="width:100%">
												<option  value="">Select</option>
												@foreach($client_category_master as $k=> $v)
													@if($v->client_category_id == $result[0]->client_category_id)
														<option value="{{ $v->client_category_id}}" selected="selected" > {{ $v->client_category_name}}</option>
													@else
														<option value="{{ $v->client_category_id}}" > {{ $v->client_category_name}}</option>
													@endif
												@endforeach
											</select>
										</div>

										<div class="col-sm-4">
											{!! Form::label('Client Type') !!}<br />
											@if($result[0]->customer_type == 'new')
												{{ Form::radio('customer_type', 'new', true, ['class' => 'customer_type','disabled'=>'disabled']) }}New
												{{ Form::radio('customer_type', 'existing','', ['class' => 'customer_type' ,'disabled'=>'disabled']) }}Existing
											@else
												{{ Form::radio('customer_type', 'new', '', ['class' => 'customer_type','disabled'=>'disabled']) }}New
												{{ Form::radio('customer_type', 'existing',true, ['class' => 'customer_type','disabled'=>'disabled']) }}Existing
											@endif
										</div>
									</div>

									<div class="form-group col-sm-12">
										<div class="col-sm-4">
											{!! Form::label('Country') !!} <span class="required">*</span>
											<select name="country_id" id="country_id" class="select2" style="width:100%" disabled="disabled">
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
											<input type="hidden" name="country_id" id="country_id" value="{{$result[0]->country_id}}" />
										</div>


										<div class="col-sm-4 state_hide" <?php echo ($country_zone_id != 0)?'style="display:none;"':'';?>  disabled="disabled">
											{!! Form::label('State') !!} <span class="required">*</span>
											<select name="state_id" id="state_id" class="select2" style="width:100%" disabled="disabled">
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
											<input type="hidden" name="state_id" id="state_id" value="{{$result[0]->state_id}}" />
										</div>
										<div class="col-sm-4 city_hide" <?php echo ($country_zone_id!=0)?'style="display:none;"':'';?>>
											{!! Form::label('City') !!}
											<select name="city_id" id="city_id" class="select2" style="width:100%"  disabled="disabled">
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
											<input type="hidden" name="city_id" id="city_id" value="{{$result[0]->city_id}}" />
										</div>
									</div>

									<div class="form-group col-sm-12">
										<div class="col-sm-3">
											{!! Form::label('Mobile 1') !!}
											{!! Form::text('mobile',$result[0]->mobile, array('class' => 'form-control ' ,'id'=>"mobile",'placeholder'=>'Enter Mobile No 1')) !!}
										</div>
										<div class="col-sm-1" style="padding:0 !important;margin:0 !important;">
											{!! Form::label('Type') !!}
											<select name="mtype1" id="mtype1" class="select2" style="width:100%">
												<option value="">Select</option>
												<option <?php echo ($result[0]->mobile_type1 == 'W') ? 'selected' : ''; ?> value="W">W</option>
												<option <?php echo ($result[0]->mobile_type1 == 'W/C') ? 'selected' : ''; ?> value="W/C">W/C</option>
												<option <?php echo ($result[0]->mobile_type1 == 'C') ? 'selected' : ''; ?> value="C">C</option>
											</select>
										 </div>
										<div class="col-sm-3">

											{!! Form::label('Mobile 2') !!}
											{!! Form::text('mobile_2',$result[0]->mobile_2, array('class' => 'form-control ' ,'id'=>"mobile_2",'placeholder'=>'Enter Mobile No 2')) !!}
										</div>
										<div class="col-sm-1" style="padding:0 !important;margin:0 !important;">
											{!! Form::label('Type') !!}
											<select name="mtype2" id="mtype2" class="select2" style="width:100%">
												<option value="">Select</option>
												<option <?php echo ($result[0]->mobile_type2 == 'W') ? 'selected' : ''; ?> value="W">W</option>
												<option <?php echo ($result[0]->mobile_type2 == 'W/C') ? 'selected' : ''; ?> value="W/C">W/C</option>
												<option <?php echo ($result[0]->mobile_type2 == 'C') ? 'selected' : ''; ?> value="C">C</option>
											</select>
										</div>
										<div class="col-sm-3">
											{!! Form::label('Mobile 3') !!}
											{!! Form::text('mobile_3',$result[0]->mobile_3, array('class' => 'form-control ' ,'id'=>"mobile_3",'placeholder'=>'Enter Mobile No 3')) !!}
										</div>
										<div class="col-sm-1" style="padding:0 !important;margin:0 !important;">
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
										<div class="col-sm-6">
										{!! Form::label('Email Address 1') !!}
										{!! Form::email('email',$result[0]->email, array('class' => 'form-control ' ,'id'=>"email",'placeholder'=>'Enter Email')) !!}
										</div>
										<div class="col-sm-6">
										{!! Form::label('Email Address 2') !!}
										{!! Form::email('email_2',$result[0]->email_2, array('class' => 'form-control ' ,'id'=>"email_2",'placeholder'=>'Enter Email 2')) !!}
										</div>
									</div>

									<div class="form-group col-sm-12">
										<div class="col-sm-4">
											{!! Form::label('Landline No') !!}
											{!! Form::text('landline',$result[0]->landline, array('class' => 'form-control numberonly' ,'id'=>"landline",'placeholder'=>'Enter Landline')) !!}
										</div>
										<div class="col-sm-4 plant_state_hide">

											{!! Form::label('Plant Location State') !!} <span class="required">*</span>
											<select name="project_state" id="project_state" class="select2" style="width:100%" disabled="disabled">
													<option  value="">Select</option>

													@foreach($state as $k=> $v)

															@if ($v->state_id == $result[0]->project_state)
																<option value="{{ $v->state_id}}" selected="selected" > {{ $v->state_name}}</option>
															@else
																<option value="{{ $v->state_id}}" > {{ $v->state_name}}</option>
															@endif
													@endforeach
											</select>
										</div>
										<div class="col-sm-4 plant_city_hide">
											{!! Form::label('Plant Location City') !!}
											<select name="project_city" id="project_city" class="select2" style="width:100%" disabled="disabled">
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
										</div>
									</div>

									<div class="form-group col-sm-12">
										<div class="col-sm-4">
											{!! Form::label('Company Name') !!}
											{!! Form::text('company',$result[0]->company, array('class' => 'form-control ' ,'id'=>"company",'placeholder'=>'Enter Company')) !!}
										</div>
										<div class="col-sm-4">
										{!! Form::label('Office Address') !!}
										{!! Form::textarea('office_address',$result[0]->office_address, array('class' => 'form-control ' ,'id'=>"office_address",'placeholder'=>'Enter Office Address','size'=>'20*3')) !!}
										</div>
										<div class="col-sm-4">
										{!! Form::label('Address') !!}
										{!! Form::textarea('address',$result[0]->address, array('class' => 'form-control ' ,'id'=>"address",'placeholder'=>'Enter Address','size'=>'20*3')) !!}
										</div>
									</div>

									<div class="form-group col-sm-12">
										<div class="col-sm-4">
											{!! Form::label('Quotation Status') !!}
											<select name="quatation_status_id" id="quatation_status_id" class="select2" style="width:100%">
												<option  value="">Select</option>
												@foreach($quatation_status_master as $k=> $v)
													@if($v->quatation_status_id == $result[0]->quatation_status_id)
														<option value="{{ $v->quatation_status_id}}" selected="selected" > {{ $v->quatation_status_name}}</option>
													@else
														<option value="{{ $v->quatation_status_id}}" > {{ $v->quatation_status_name}}</option>
													@endif
												@endforeach
											</select>
										</div>
										<div class="col-sm-4">
											{!! Form::label('Visit Details') !!}
											<select name="visit_details_id" id="visit_details_id" class="select2" style="width:100%">
												<option  value="">Select</option>
												@foreach($visit_details_master as $k=> $v)
													@if($v->visit_details_id == $result[0]->visit_details_id)
														<option value="{{ $v->visit_details_id}}" selected="selected" > {{ $v->visit_details_name}}</option>
													@else
														<option value="{{ $v->visit_details_id}}" > {{ $v->visit_details_name}}</option>
													@endif
												@endforeach
											</select>
										</div>

										<div class="col-sm-4">
											{!! Form::label('Project Divison') !!}
											<select name="project_division_id" id="project_division_id" class="select2" style="width:100%">
												<option  value="">Select</option>
												@foreach($project_division_master as $k=> $v)
													@if($v->project_division_id == $result[0]->project_division_id)
														<option value="{{ $v->project_division_id}}" selected="selected" > {{ $v->project_division_name}}</option>
													@else
														<option value="{{ $v->project_division_id}}" > {{ $v->project_division_name}}</option>
													@endif
												@endforeach
											</select>
										</div>
									</div>

									<div class="form-group col-sm-12">

										<div class="col-sm-4">
											{!! Form::label('Planning Stage') !!}
											<select name="planning_stage_id" id="planning_stage_id" class="select2" style="width:100%">
												<option  value="">Select</option>
												@foreach($planning_stage_master as $k=> $v)
													@if($v->planning_stage_id == $result[0]->planning_stage_id)
														<option value="{{ $v->planning_stage_id}}" selected="selected" > {{ $v->planning_stage_name}}</option>
													@else
														<option value="{{ $v->planning_stage_id}}" > {{ $v->planning_stage_name}}</option>
													@endif
												@endforeach
											</select>
										</div>
										<div class="col-sm-4">
											{!! Form::label('Payment Mode') !!}
											<select name="payment_mode_id" id="payment_mode_id" class="select2" style="width:100%">
												<option  value="">Select</option>
												@foreach($payment_mode_master as $k=> $v)
													@if($v->payment_mode_id == $result[0]->payment_mode_id)
														<option value="{{ $v->payment_mode_id}}" selected="selected" > {{ $v->payment_mode_name}}</option>
													@else
														<option value="{{ $v->payment_mode_id}}" > {{ $v->payment_mode_name}}</option>
													@endif
												@endforeach
											</select>
										</div>
										<div class="col-sm-4">
											<?php
												$get_revise_q=DB::table('revise_quatation')->where('quatation_id',$result[0]->quatation_id)->select('total_amount')->orderBy('rq_no','DESC')->limit(1)->get();
												if(count($get_revise_q))
													$project_value=number_format((float)$get_revise_q[0]->total_amount,2,'.','');
												else
													$project_value=number_format((float)$result[0]->total_amount,2,'.','');
											?>
											{!! Form::label('Project Value') !!}
											{!! Form::text('project_value',$project_value, array('class' => 'form-control' ,'id'=>"project_value",'placeholder'=>'Enter Project Value','readonly'=>'readonly')) !!}
										</div>

									</div>

									<div class="form-group col-sm-12">

										<div class="col-sm-4">
											{!! Form::label('Raw Water Source') !!}
											<select name="raw_water_id" id="raw_water_id" class="select2" style="width:100%">
												<option  value="">Select</option>
												@foreach($raw_water_master as $k=> $v)
													@if($v->raw_water_id == $result[0]->raw_water_id)
														<option value="{{ $v->raw_water_id}}" selected="selected" > {{ $v->raw_water_name}}</option>
													@else
														<option value="{{ $v->raw_water_id}}" > {{ $v->raw_water_name}}</option>
													@endif
												@endforeach
											</select>
										</div>
										<div class="col-sm-4">
											{!! Form::label('Water Report') !!}
											<select name="water_report_id" id="water_report_id" class="select2" style="width:100%">
												<option  value="">Select</option>
												@foreach($water_report_master as $k=> $v)
													@if($v->water_report_id == $result[0]->water_report_id)
														<option value="{{ $v->water_report_id}}" selected="selected" > {{ $v->water_report_name}}</option>
													@else
														<option value="{{ $v->water_report_id}}" > {{ $v->water_report_name}}</option>
													@endif
												@endforeach
											</select>
										</div>
										<div class="col-sm-4">
											{!! Form::label('Power Supply') !!}
											<select name="power_supply_id" id="power_supply_id" class="select2" style="width:100%">
												<option  value="">Select</option>
												@foreach($power_supply_master as $k=>$v)
													@if($v->power_supply_id == $result[0]->power_supply_id)
														<option value="{{ $v->power_supply_id}}" selected="selected" > {{ $v->power_supply_name}}</option>
													@else
														<option value="{{ $v->power_supply_id}}" > {{ $v->power_supply_name}}</option>
													@endif
												@endforeach
											</select>
										</div>
									</div>

									<div class="form-group col-sm-12">
										<div class="col-sm-4">
											{!! Form::label('Site Status') !!}
											<select name="site_status_id" id="site_status_id" class="select2" style="width:100%">
												<option  value="">Select</option>
												@foreach($site_status_master as $k=> $v)
													@if($v->site_status_id == $result[0]->site_status_id)
														<option value="{{ $v->site_status_id}}" selected="selected" > {{ $v->site_status_name}}</option>
													@else
														<option value="{{ $v->site_status_id}}" > {{ $v->site_status_name}}</option>
													@endif
												@endforeach
											</select>
										</div>
										<div class="col-sm-4">
											{!! Form::label('Remarks') !!}
											{!! Form::textarea('inquiry_remarks',$result[0]->remarks, array('class' => 'form-control ' ,'id'=>"inquiry_remarks",'placeholder'=>'Enter Remarks','size'=>'20*3')) !!}
										</div>
										<div class="col-sm-4">
											{!! Form::label('Remarks') !!}
											{!! Form::textarea('customer_remarks',$result[0]->customer_remarks, array('class' => 'form-control ' ,'id'=>"follow_up_remarks",'placeholder'=>'Enter Remarks','size'=>'20*3')) !!}
										</div>
									</div>
									@if($role_id == 1)
									<div class="form-group col-sm-12">
										<center>
										{!! Form::submit('Save', ['class' => 'btn btn-warning all_btn_hide']) !!}
										{!!Form::close()!!}
										</center>
									</div>
									@else
										@if($add_permission == 1)
											<div class="form-group col-sm-12">
												<center>
												{!! Form::submit('Save', ['class' => 'btn btn-warning all_btn_hide']) !!}
												{!!Form::close()!!}
												</center>
											</div>
										@endif
									@endif
							</div>
						</div>
              		</div>
                    <div class="tab-pane " id="follow_up">
						{!! Form::open(array('method' => 'post' , 'files' => true ,'id'=>"follow_up_frm",'name'=>"follow_up_frm",'class'=>"follow_up_frm"))!!}
						{!! Form::hidden('inquiry_id',$result[0]->inquiry_id, array('id'=>"inquiry_id")) !!}
						{!! Form::hidden('quatation_id',$result[0]->quatation_id, array('id'=>"quatation_id")) !!}
						{!! Form::hidden('last_follow_id',$follow_ups[0]->follow_up_id, array('id'=>"last_follow_id")) !!}
						{{ csrf_field() }}
                     	 <div class="box box-primary">
                            <div class="box-body">
								<div class="form-group col-sm-12">
									<div class="form-group col-sm-2">
										{!! Form::label('Quotation No.') !!}
									</div>
									<div class="form-group col-sm-4">
										{!! Form::text('quatation_no',$result[0]->quatation_no, array('class' => 'form-control ' ,'id'=>"quatation_no",'required' => 'required','readonly'=>'readonly')) !!}
									</div>
									<div class="form-group col-sm-2">
										{!! Form::label('Quotation Date') !!}
									</div>
									<div class="form-group col-sm-4">
										{!! Form::text('quatation_date',date('d-m-Y',strtotime($result[0]->quatation_date)), array('class' => 'form-control ' ,'id'=>"quatation_date",'required' => 'required','readonly'=>'readonly')) !!}
									</div>
								</div>


								<div class="form-group col-sm-12">

									<div class="form-group col-sm-2">
										{!! Form::label('Quot. Person') !!}
									</div>
									<div class="form-group col-sm-4">
										{!! Form::text('quatation_person',$quatation_person[0]->username, array('class' => 'form-control ' ,'id'=>"quatation_person",'required' => 'required','readonly'=>'readonly')) !!}
									</div>

									<div class="form-group col-sm-2">
										{!! Form::label('Follow-Up By') !!}
									</div>
									<div class="form-group col-sm-4">
										{!! Form::text('followup_by',Session::get('raj_user'), array('class' => 'form-control ' ,'id'=>"followup_by",'required' => 'required','readonly'=>'readonly')) !!}
									</div>
								</div>
								<div class="form-group col-sm-12">
									<div class="form-group col-sm-2">
										{!! Form::label('Follow-Up Date') !!}
									</div>
									<div class="form-group col-sm-4">
										{!! Form::text('follow_up_date',date('d-m-Y'), array('class' => 'form-control ' ,'id'=>"follow_up_date",'required' => 'required','readonly'=>'readonly')) !!}
									</div>

									<div class="form-group col-sm-2">
										{!! Form::label('Follow-Up Time') !!}
									</div>
									<div class="form-group col-sm-4">
										{!! Form::text('follow_up_time',date('h:i a'), array('class' => 'form-control ' ,'id'=>"follow_up_time",'required' => 'required','readonly'=>'readonly')) !!}
									</div>
								</div>
								<div class="form-group col-sm-12">
									<div class="form-group col-sm-2">
										{!! Form::label('Follow-Up Way') !!}
									</div>
									<div class="form-group col-sm-4">
										<select name="followup_way_id" id="followup_way_id" class="select2" style="width:100%">
											<option  value="">Select</option>
											@foreach($followup_way_master as $k=> $v)
												@if($v->followup_way_id == $result[0]->followup_way_id)
													<option value="{{ $v->followup_way_id}}" selected="selected" > {{ $v->followup_way_name}}</option>
												@else
													<option value="{{ $v->followup_way_id}}" > {{ $v->followup_way_name}}</option>
												@endif
											@endforeach
										</select>
									</div>
									<div class="call_type_hide" style="display:none">
										<div class="form-group col-sm-2 " >
											{!! Form::label('Call Type') !!}
										</div>
										<div class="form-group col-sm-4">
											<select name="call_type" id="call_type" class="select2" style="width:100%">
												<option value="">Select</option>
												<option value="Call Receive">Call Receive</option>
												<option value="Call Receive Then Call Cut">Call Receive Then Call Cut</option>
												<option value="Call Not Receive">Call Not Receive</option>
												<option value="Call Not Connected">Call Not Connected</option>
												<option value="Call Cut">Call Cut</option>
												<option value="Switch Off">Switch Off</option>
												<option value="Number Busy">Number Busy</option>
											</select>
										</div>
									</div>
								</div>
								<div class="form-group col-sm-12">
									<div class="follow_up_mobile_disp" >
										<div class="form-group col-sm-2">
											{!! Form::label('Details') !!}
										</div>
										<div class="form-group col-sm-4">
											<select name="follow_up_detail_mobile" id="follow_up_detail_mobile" class="select2" style="width:100%">
												<option value="">Select</option>
												@if($result[0]->mobile != '')
													<option value="{{ $result[0]->mobile }}" >{{ $result[0]->mobile }}</option>
												@endif
												@if($result[0]->mobile_2 != '')
													<option value="{{ $result[0]->mobile_2 }}" >{{ $result[0]->mobile_2 }}</option>
												@endif
												@if($result[0]->mobile_3 != '')
													<option value="{{ $result[0]->mobile_3 }}" >{{ $result[0]->mobile_3 }}</option>
												@endif
												@if($result[0]->landline != '')
													<option value="{{ $result[0]->landline }}" >{{ $result[0]->landline }}</option>
												@endif
											</select>
										</div>
									</div>
									<div class="follow_up_email_disp">
										<div class="form-group col-sm-2">
											{!! Form::label('Details') !!}
										</div>
										<div class="form-group col-sm-4" >
											<select name="follow_up_detail_email" id="follow_up_detail_email" class="select2" style="width:100%">
												<option value="">Select</option>
												@if($result[0]->email != '')
													<option value="{{ $result[0]->email }}" >{{ $result[0]->email }}</option>
												@endif
												@if($result[0]->email_2 != '')
													<option value="{{ $result[0]->email_2 }}" >{{ $result[0]->email_2 }}</option>
												@endif
											</select>
										</div>
									</div>
								</div>
								<div class="form-group col-sm-12">
									<div class="form-group col-sm-2">
										{!! Form::label('Days For Next Follow') !!}
									</div>
									<div class="form-group col-sm-4">
										{!! Form::number('day_followup_date','', array('class' => 'form-control ' ,'id'=>"day_followup_date",'required' => 'required')) !!}
									</div>
									<div class="form-group col-sm-2">
										{!! Form::label('Next Follow-Up Date') !!}
									</div>
									<div class="form-group col-sm-4">
										{!! Form::text('next_followup_date',date('d-m-Y'), array('class' => 'form-control ' ,'id'=>"next_followup_date",'required' => 'required','readonly'=>'readonly')) !!}
									</div>
								</div>
								<input type="hidden" id="last_remark" name="last_remark" value="<?php echo $follow_ups[0]->call_receive_remark; ?>" />
								<div class="form-group col-sm-12 call_receive_remark_hide" style="display:none">
									 <div class="form-group col-sm-12">
										{!! Form::label('Remark') !!}
										{!! Form::textarea('call_receive_remark','', array('class' => 'form-control ' ,'id'=>"call_receive_remark",'placeholder'=>'Enter Remarks','size'=>'20*3')) !!}
									</div>
								</div>
								@if($role_id == 1)
									<div class="form-group col-sm-12">
										<div class="col-sm-3">
											<center>
												<input type="submit" class="btn btn-primary mybtn all_btn_hide" value="save" />
												<?php /* {!! Form::submit('Save', ['class' => 'btn btn-primary mybtn all_btn_hide']) !!} */ ?>
												{!! Form::close() !!}
											</center>
										</div>
										<div class="col-sm-3">
											<center><a id="reminder_follow_up" data-toggle="modal" data-target="#send_reminder_model" class="btn btn-primary mybtn all_btn_hide">Reminder Mail</a></center>
										</div>
										<div class="col-sm-3">
											<center><a id="revice_follow_up" data-toggle="modal" data-target="#revice_model" class="btn btn-primary mybtn all_btn_disabled all_btn_hide">Revise</a></center>
										</div>
										<div class="col-sm-3">
											<center><a id="price_issue_follow_up" data-toggle="modal" data-target="#price_issue_model" class="btn btn-primary mybtn all_btn_disabled all_btn_hide">Price / Technicle Issue</a></center>
										</div>
									</div>
									<div class="form-group col-sm-12">
										<div class="col-sm-3">
											<center><a id="send_quotation_follow_up" data-toggle="modal" data-target="#send_model" class="btn btn-primary mybtn all_btn_hide">Send Quotation</a></center>
										</div>
										<div class="col-sm-3">
											<center><a id="send_address_follow_up" data-toggle="modal" data-target="#send_address_model" class="btn btn-primary mybtn all_btn_hide">Send Address</a></center>
										</div>
										<div class="col-sm-3">
											<center><a id="order_book_follow_up" data-toggle="modal" data-target="#order_book_model" class="btn btn-primary mybtn all_btn_disabled all_btn_hide">Order Book</a></center>
										</div>
										<div class="col-sm-3">
											<center><a id="regret_follow_up" data-toggle="modal" data-target="#regret_model" class="btn btn-primary mybtn all_btn_disabled all_btn_hide">Regret</a></center>
										</div>
									</div>
								@else
									@if($add_permission == 1)
										<div class="form-group col-sm-12">
											<div class="col-sm-2">
												<center>
													<input type="submit" class="btn btn-primary mybtn all_btn_hide" value="save" />
													<?php /* {!! Form::submit('Save', ['class' => 'btn btn-primary mybtn all_btn_hide']) !!} */ ?>
													{!!Form::close()!!}
												</center>
											</div>
											<div class="col-sm-3">
												<center><a id="reminder_follow_up" data-toggle="modal" data-target="#send_reminder_model" class="btn btn-primary mybtn all_btn_hide">Reminder Mail</a></center>
											</div>
											<div class="col-sm-2">
												<center><a id="revice_follow_up" data-toggle="modal" data-target="#revice_model" class="btn btn-primary mybtn all_btn_disabled all_btn_hide">Revise</a></center>
											</div>
											<div class="col-sm-3">
												<center><a id="price_issue_follow_up" data-toggle="modal" data-target="#price_issue_model" class="btn btn-primary mybtn all_btn_disabled all_btn_hide">Price / Technicle Issue</a></center>
											</div>
											<div class="col-sm-2">
												<center><a id="hot_list_follow_up" data-toggle="modal" data-target="#hot_list_model" class="btn btn-primary mybtn all_btn_disabled all_btn_hide">Hot list</a></center>
											</div>
										</div>
										<div class="form-group col-sm-12">
											<div class="col-sm-3">
												<center><a id="send_quotation_follow_up" data-toggle="modal" data-target="#send_model" class="btn btn-primary mybtn all_btn_hide">Send Quotation</a></center>
											</div>
											<div class="col-sm-3">
												<center><a id="send_address_follow_up" data-toggle="modal" data-target="#send_address_model" class="btn btn-primary mybtn all_btn_hide">Send Address</a></center>
											</div>
											<div class="col-sm-3">
												<center><a id="order_book_follow_up" data-toggle="modal" data-target="#order_book_model" class="btn btn-primary mybtn all_btn_disabled all_btn_hide">Order Book</a></center>
											</div>
											<div class="col-sm-3">
												<center><a id="regret_follow_up" data-toggle="modal" data-target="#regret_model" class="btn btn-primary mybtn all_btn_disabled all_btn_hide">Regret</a></center>
											</div>
										</div>
									@endif
							@endif
								<div class="form-group col-sm-12" id="follow_up_data">
								@if(count($follow_ups))
									<div class="table-responsive">
										<table id="datatable" class="table table-bordered table-striped">
										<thead>
											<tr>
												<th width="5%">Sr</th>
												<th width="18%">Follow-Up Date & Time</th>
												<th width="13%">Follow-Up By</th>
												<th width="10%">Follow-Up Way</th>
												<th width="10%">Detail</th>
												<th width="15%">Next Follow-Up Date</th>
												<th width="25%">Remark</th>
											</tr>
										  </thead>
										   <tbody>

												@foreach ($follow_ups as $key=>$value)
												<tr>
													<td>{{ $key+1}}</td>
													<td>{{ date('d-m-Y h:i A',strtotime($value->follow_up_date.'' .$value->follow_up_time))}}</td>
													<td>{{ $value->username}}</td>
													<td>{{ $value->followup_way_name}}</td>
													<td>{{ $value->detail}}</td>
													<td>{{ date('d-m-Y',strtotime($value->next_followup_date))}}</td>
													<td>{{ $value->call_receive_remark}}</td>
											  @endforeach
										   </tbody>
										  </table>
									</div>
								@endif
								</div>
                             </div>
                          </div>

              		</div>

					<div class="tab-pane" id="document_detail">
							{!! Form::open(array( 'method' => 'post' , 'files' => true ,'id'=>"document_frm",'name'=>"document_frm",'class'=>"document_frm"))!!}
							{!! Form::hidden('inquiry_id',$result[0]->inquiry_id, array('id'=>"inquiry_id")) !!}
							{!! Form::hidden('quatation_id',$result[0]->quatation_id, array('id'=>"quatation_id")) !!}
							{!! Form::hidden('last_follow_id',$follow_ups[0]->follow_up_id, array('id'=>"last_follow_id")) !!}
							{{ csrf_field() }}
                     	 <div class="box box-warning">
                            <div class="box-body">
								<div class="form-group col-sm-12">
									<div class="form-group col-sm-2">
										{!! Form::label('Quotation No.') !!}
									</div>
									<div class="form-group col-sm-4">
										{!! Form::text('quatation_no',$result[0]->quatation_no, array('class' => 'form-control ' ,'id'=>"quatation_no",'required' => 'required','readonly'=>'readonly')) !!}
									</div>
									<div class="form-group col-sm-2">
										{!! Form::label('Quotation Date') !!}
									</div>
									<div class="form-group col-sm-4">
										{!! Form::text('quatation_date',date('d-m-Y',strtotime($result[0]->quatation_date)), array('class' => 'form-control ' ,'id'=>"quatation_date",'required' => 'required','readonly'=>'readonly')) !!}
									</div>
								</div>
								<div class="form-group col-sm-12">
									<div class="form-group col-sm-2">
										{!! Form::label('Document Upload Date') !!}
									</div>
									<div class="form-group col-sm-4">
										{!! Form::text('document_attached_date',date('d-m-Y'), array('class' => 'form-control ' ,'id'=>"document_attached_date",'required' => 'required','readonly'=>'readonly')) !!}
									</div>

									<div class="form-group col-sm-2">
										{!! Form::label('Document Upload Time') !!}
									</div>
									<div class="form-group col-sm-4">
										{!! Form::text('document_attached_time',date('h:i a'), array('class' => 'form-control ' ,'id'=>"document_attached_time",'required' => 'required','readonly'=>'readonly')) !!}
									</div>
								</div>

								<div class="form-group col-sm-12">
									<div class="form-group col-sm-2">
										{!! Form::label('Document Name') !!}
									</div>
									<div class="form-group col-sm-4">
										<select name="doc_detail" id="doc_detail" class="select2" style="width:100%;" >
											<option value=''>Select</option>
											@foreach($document_name_master as $key=>$val)
												<option value="{{ $val->id }}">{{ $val->document_name }}</option>
											@endforeach
										</select>
									</div>
									<div class="form-group col-sm-2">
										{!! Form::label('Document Attached Employee') !!}
									</div>
									<div class="form-group col-sm-4">
									{!! Form::text('document_attached_employee',Session::get('raj_user'), array('class' => 'form-control ' ,'id'=>"document_attached_employee",'required' => 'required','readonly'=>'readonly')) !!}
									</div>
								</div>

								<div class="form-group col-sm-12">
									<div class="form-group col-sm-2">
										{!! Form::label('New Document Attachment ') !!}
									</div>
									<div class="form-group col-sm-4">
										{!! Form::file('attached_document', array('class' => 'form-control ' ,'id'=>"attached_document")) !!}
									</div>
								</div>
								@if($role_id == 1)
									<div class="form-group col-sm-12">
										<center>
										{!! Form::submit('Save', ['class' => 'btn btn-primary all_btn_hide']) !!}
										{!!Form::close()!!}
										</center>
									</div>
								@else
									@if($add_permission == 1)
										<div class="form-group col-sm-12">
											<center>
											{!! Form::submit('Save', ['class' => 'btn btn-primary all_btn_hide']) !!}
											{!!Form::close()!!}
											</center>
										</div>
									@endif
								@endif
								<div class="form-group col-sm-12" id="document_data">
								@if(count($document_detail))
									<div class="table-responsive">
										<table id="datatable" class="table table-bordered table-striped">
											 <thead>
											<tr>
												<th>Sr</th>
												<th>Document Date & Time</th>
												<th>Document Detail</th>
												<th>Added By</th>
												<th>Download</th>
											</tr>
										  </thead>
										   <tbody>
												@foreach ($document_detail as $key=>$value)
													@php
														$download_doc = URL::action('Follow_up@download',$value->document_name);
													@endphp
												<tr>
													<td>{{ $key+1}}</td>
													<td>{{ date('d-m-Y h:i A',strtotime($value->document_attached_date.'' .$value->document_attached_time))}}</td>
													<td>{{ $value->d}}</td>
													<td>{{ $value->username}}</td>
													<td><a href="{{ $download_doc }}" class="btn btn-success" > Download </a></td>
												</tr>
											  @endforeach
										   </tbody>
										  </table>
									</div>
								@endif
								</div>
								<div class="form-group col-sm-12">
									<div class="table-responsive">
										<table id="datatable" class="table table-bordered table-striped">
											<thead>
												<tr>
													<th>Sr</th>
													<th>Quotation Number</th>
													<th>Quotation Date</th>
													<th>View</th>
												</tr>
											</thead>
										   <tbody>
											@foreach ($quotation_no as $key=>$value)
												@php
													$view_quot = URL::action('Quatation@view',$utility->encode($value->quatation_id));

													$print = URL::to('Quatation/print_quatation',array($utility->encode($value->quatation_id),$utility->encode('print'),$utility->encode('yes')),false);

													$download = URL::to('Quatation/print_quatation',array($utility->encode($value->quatation_id),$utility->encode('download'),$utility->encode('yes')),false);
												@endphp
												<tr>
													<td>{{$key+1}}</td>
													<td>{{ $value->quatation_no }}</td>
													<td>{{ date('d-m-Y',strtotime($value->quatation_date)) }}</td>
													<td><a target="_blank" title="View" class="btn bg-olive btn-flat btn-sm" href="{{ $view_quot }}"><i class="glyphicon glyphicon-eye-open icon-white"></i> View Quotation</a>
													<a target="_blank" class="btn btn-danger btn-flat btn-sm" href="{{ $print }}"><i class="glyphicon glyphicon-print icon-white"></i> Print</a>
													<a class="btn btn-info btn-flat btn-sm" href="{{ $download }}"><i class="glyphicon glyphicon-download icon-white"></i>Download</a>
													</td>
												</tr>
											 @endforeach

											 @foreach ($revise_quot_num as $key=>$value)
												@php
													$view_quot = URL::action('Quatation@revise_quatation_view',$utility->encode($value->revise_id));

													$print = URL::to('Quatation/revise_quatation_print',array($utility->encode($value->revise_id),$utility->encode('print'),$utility->encode('yes')),false);

													$download = URL::to('Quatation/revise_quatation_print',array($utility->encode($value->revise_id),$utility->encode('download'),$utility->encode('yes')),false);
												@endphp
												<tr>
													<td>{{$key+1}}</td>
													<td>{{ $value->revise_quatation_no }}</td>
													<td>{{ date('d-m-Y',strtotime($value->revise_date)) }}</td>
													<td><a target="_blank" title="View" class="btn bg-olive btn-flat btn-sm" href="{{ $view_quot }}"><i class="glyphicon glyphicon-eye-open icon-white"></i> View Quotation</a>

													<a target="_blank" class="btn btn-danger btn-flat btn-sm" href="{{ $print }}"> <i class="glyphicon glyphicon-print icon-white"></i> Print</a>

													<a class="btn btn-info btn-flat btn-sm" href="{{ $download }}"> <i class="glyphicon glyphicon-print icon-white"></i>Download</a>
													</td>
												</tr>
											 @endforeach
										   </tbody>
										  </table>
									</div>
								</div>
                             </div>
                          </div>

              		</div>

					 <div class="tab-pane " id="visiting_detail">
                     	 <div class="box box-warning">
                            <div class="box-body">
								<div class="form-group col-sm-12">
									<div class="form-group col-sm-2">
										{!! Form::label('Quotation No.') !!}
									</div>
									<div class="form-group col-sm-4">
										{!! Form::text('quatation_no',$result[0]->quatation_no, array('class' => 'form-control ' ,'id'=>"quatation_no",'required' => 'required','readonly'=>'readonly')) !!}
									</div>
									<div class="form-group col-sm-2">
										{!! Form::label('Quotation Date') !!}
									</div>
									<div class="form-group col-sm-4">
										{!! Form::text('quatation_date',date('d-m-Y',strtotime($result[0]->quatation_date)), array('class' => 'form-control ' ,'id'=>"quatation_date",'required' => 'required','readonly'=>'readonly')) !!}
									</div>
								</div>
								<div class="form-group col-sm-12">
									<div class="form-group col-sm-2">
										{!! Form::label('Visit Form') !!}
									</div>
									@if($role_id == 1)
										<div class="form-group col-sm-4">
											<a id="visiting_form_btn" data-toggle="modal" data-target="#visiting_form_model" class="btn btn-primary ">Browse</a>
										</div>
									@else
										@if($add_permission == 1)
										<div class="form-group col-sm-4">
											<a id="visiting_form_btn" data-toggle="modal" data-target="#visiting_form_model" class="btn btn-primary ">Browse</a>
										</div>
										@endif
									@endif
								</div>
								<?php

								/* <div class="form-group col-sm-12">
									<div class="form-group col-sm-2">
										{!! Form::label('Visit Date') !!}
									</div>
									<div class="form-group col-sm-4">
										{!! Form::text('visit_date',date('d-m-Y'), array('class' => 'form-control ' ,'id'=>"visit_date",'required' => 'required','readonly'=>'readonly')) !!}
									</div>
									<div class="form-group col-sm-2">
										{!! Form::label('Visit Time') !!}
									</div>
									<div class="form-group col-sm-4">
										{!! Form::text('visit_time',date('h:i a'), array('class' => 'form-control timepicker' ,'id'=>"visit_time",'required' => 'required','readonly'=>'readonly')) !!}
									</div>
								</div>

								<div class="form-group col-sm-12">
									<div class="form-group col-sm-2">
										{!! Form::label('Visit In') !!}
									</div>
									<div class="form-group col-sm-4">
										<select class="select2" style="width:100%" id="visit_at" name="visit_at">
											<option value="">Select</option>
											@foreach($visit_at as $key=>$val)
												<option value="{{ $val->id }}">{{ $val->office_name }}</option>
											@endforeach
										</select>
									</div>
										<div class="form-group col-sm-2">
											{!! Form::label('Attend By') !!}
										</div>
										<div class="form-group col-sm-4">
											<select name="visitor_attended_by" id="visitor_attended_by" class="select2 " style="width:100%">
												<option  value=''>Select</option>
												@foreach($employee as $k=> $v)
													@if ($v->emp_id == $result[0]->visitor_attended_by)
														<option value="{{ $v->emp_id}}" selected="selected" > {{ $v->name}}</option>
													@else
														<option value="{{ $v->emp_id}}" > {{ $v->name}}</option>
													@endif
												@endforeach
											</select>
										</div>
								</div>
								<div class="form-group col-sm-12">
									<div class="form-group col-sm-2">
											{!! Form::label('Status') !!}
										</div>
										<div class="form-group col-sm-4">
										{{ Form::select('visit_status',
											[''=>'Select',
											'Pending'=>'Pending',
											'IN progress'=>'IN progress',
											'Order Book'=>'Order Book'],
											null,
											['id'=>'visit_status','class'=>'select2','style'=>'width:100%']
										)}}
									</div>
									<div class="form-group col-sm-2">
											{!! Form::label('Category') !!}
										</div>
										<div class="form-group col-sm-4">
											<select name="visit_category" id="visit_category" class="select2 " style="width:100%">
												<option  value=''>Select</option>
												@foreach($client_category as $k=> $v)
													<option value="{{ $v->client_category_id}}" > {{ $v->client_category_name}}</option>

												@endforeach
											</select>
										</div>
								</div>
								<div class="form-group col-sm-12">
									<div class="form-group col-sm-2">
										{!! Form::label('Visit Form') !!}
									</div>
									<div class="form-group col-sm-4">
										<a id="visiting_form_btn" data-toggle="modal" data-target="#visiting_form_model" class="btn btn-primary ">Browse</a>
									</div>
									<div class="form-group col-sm-2">
										{!! Form::label('Form No.') !!}
									</div>
									<div class="form-group col-sm-4">
										<select name="form_no_visit" id="form_no_visit" class="select2 " style="width:100%">
											<option value=''>Select</option>
										</select>
									</div>
								</div>
								@if($role_id == 1)
									<div class="form-group col-sm-12">
										<center>
										{!! Form::submit('Save', ['class' => 'btn btn-primary all_btn_hide']) !!}
										{!!Form::close()!!}
										</center>
									</div>
								@else
									@if($add_permission == 1)
										<div class="form-group col-sm-12">
											<center>
												{!! Form::submit('Save', ['class' => 'btn btn-primary all_btn_hide']) !!}
												{!!Form::close()!!}
											</center>
										</div>
									@endif
								@endif */

								?>
								<div class="form-group col-sm-12" id="visiting_detail_data">
								@if(count($visiting_detail))
									<div class="table-responsive">
										<table id="datatable" class="table table-bordered table-striped">
											<thead>
												<tr>
													<th>Sr</th>
													<th>Visitor Form No</th>
													<th>Visit Date & Time</th>
													<th>Attended By</th>
													<th>Visit At</th>
													<th>Visit Form</th>
												</tr>
											</thead>
										   <tbody>
												@foreach ($visiting_detail as $key=>$value)

												<tr>
													<td>{{ $key+1}}</td>
													<td>{{ $value->vsf_no }}</td>
													<td>{{ date('d-m-Y h:i A',strtotime($value->visit_date.'' .$value->visit_time))}}</td>
													<td>{{ $value->name }}</td>
													<td>{{ $value->office_name }}</td>
													<td><a href="{{ URL::action('Follow_up@visitor_view',$value->form_no) }}" target="_blank" class="btn btn-success">Form View</a></td>
												</tr>
											  @endforeach
										   </tbody>
										  </table>
									</div>
								@endif
								</div>

                             </div>
                          </div>

              		</div>
                 </div>
               </div>
             </div>

        </div>

<div class="modal fade" id="revice_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<button type="button" class="close"
				   data-dismiss="modal">
					   <span aria-hidden="true">&times;</span>
					   <span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">
					Add Revise
				</h4>
			</div>
			<!-- Modal Body -->
			<div class="modal-body">
				 <div class="tab-pane ">
				{!! Form::open(array('method' => 'post' , 'files' => true ,'id'=>"revice_model_form",'name'=>"revice_model_form",'class'=>"revice_model_form"))!!}
				{{ csrf_field() }}

				<input type="hidden" name="quot_no" value="{{ $result[0]->quatation_no }}" />
				<input type="hidden" name="inquiry_id" value="{{ $inq_id }}" id="inquiry_id" />
				 <div class="box-body">
					<div class="form-group col-sm-12">

						  <label class=" col-sm-3 control-label"  >Quotation Person<span class="required">*</span></label>
						  <div class="col-sm-9">
							<select name="revice_quot_person" id="revice_quot_person" class="select2 " style="width:100%">
								<option  value=''>Select</option>
								@foreach($employee as $k=> $v)
									<option <?php echo ($quotation_pers == $v->emp_id) ? 'selected' : ''; ?> value="{{ $v->emp_id}}" > {{ $v->name}}</option>
								@endforeach
							</select>
						  </div>
					</div>

					<div class="form-group col-sm-12">
						  <label class=" col-sm-3 control-label"  >Remark<span class="required">*</span></label>
						  <div class="col-sm-9">
							<textarea id="revice_remark" name="revice_remark" class="form-control col-md-7">{{ $follow_ups[0]->call_receive_remark }}</textarea>
						  </div>
					</div>

				  <div class="form-group col-sm-12">
					<div class="form-group col-sm-offset-3 col-sm-9">
					  <button type="submit" class="btn btn-default" id="product">Send</button>
					  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				  </div>
				 </div>
				{!!Form::close()!!}
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="price_issue_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<button type="button" class="close"
				   data-dismiss="modal">
					   <span aria-hidden="true">&times;</span>
					   <span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">
					Add Price Issue
				</h4>
			</div>
			<!-- Modal Body -->
			<div class="modal-body">
				 <div class="tab-pane ">
				{!! Form::open(array('method' => 'post' , 'files' => true ,'id'=>"prise_issue_form",'name'=>"prise_issue_form",'class'=>"prise_issue_form"))!!}
				{{ csrf_field() }}

				<input type="hidden" name="quot_no" value="{{ $result[0]->quatation_no }}" />
				 <div class="box-body">
					<input type="hidden" name="inquiry_id" value="{{ $inq_id }}" id="inquiry_id" />
					<div class="form-group col-sm-12">
						<label class=" col-sm-3 control-label" >Allot To<span class="required">*</span></label>
						<div class="col-sm-9">
							<select name="prisse_issue_person" id="prisse_issue_person" class="select2 " style="width:100%">
								<option  value=''>Select</option>
								@foreach($order_by as $k=> $v)
									<option <?php echo ($quotation_pers == $v->emp_id) ? 'selected' : ''; ?> value="{{ $v->emp_id }}" > {{ $v->name}}</option>
								@endforeach
							</select>
						</div>
					</div>

					<div class="form-group col-sm-12">
						  <label class=" col-sm-3 control-label" >Remark<span class="required">*</span></label>
						  <div class="col-sm-9">
								<textarea id="price_remark"  name="price_remark" class="form-control col-md-7">{{ $follow_ups[0]->call_receive_remark }}</textarea>
						  </div>
					</div>

				  <div class="form-group col-sm-12">
					<div class="col-sm-offset-3 col-sm-9">
					  <button type="submit" class="btn btn-default" id="product">Send</button>
					  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				  </div>
				 </div>
				{!!Form::close()!!}
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="hot_list_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<button type="button" class="close"
				   data-dismiss="modal">
					   <span aria-hidden="true">&times;</span>
					   <span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">
					Hot List
				</h4>
			</div>
			<!-- Modal Body -->
			<div class="modal-body">
				 <div class="tab-pane ">
				{!! Form::open(array('method' => 'post' , 'files' => true ,'id'=>"hot_list_form",'name'=>"hot_list_form",'class'=>"hot_list_form"))!!}
				{{ csrf_field() }}
				<input type="hidden" name="inquiry_id" value="{{ $inq_id }}" id="inquiry_id" />
				 <div class="box-body">

					<div class="form-group col-sm-12">
						<label class=" col-sm-3 control-label"  >Week No.<span class="required">*</span></label>
						<div class="col-sm-9">
							<input type="text" name="acc_week" id="acc_week" class="form-control numberonly" />
						</div>
					</div>
					<div class="form-group col-sm-12">
						  <label class=" col-sm-3 control-label" >Date<span class="required">*</span></label>
								<div class="col-sm-4">
									<input readonly type="text" name="hot_list_from_date" id="hot_list_from_date" class="form-control" />
								</div>
								<div class="col-sm-1">
								<label>To:</label>
								</div>
								<div class="col-sm-4">
								<input readonly type="text" name="hot_list_to_date" id="hot_list_to_date" class="form-control" />
								</div>
					</div>
					<div class="form-group col-sm-12">
						  <label class=" col-sm-3 control-label"  >Remark<span class="required">*</span></label>
						  <div class="col-sm-9">
								<textarea id="hot_list_remark" name="hot_list_remark" class="form-control col-md-7">{{ $follow_ups[0]->call_receive_remark }}</textarea>
						  </div>
					</div>

				  <div class="form-group col-sm-12">
					<div class="col-sm-offset-3 col-sm-9">
					  <button type="submit" class="btn btn-default" id="product">Send</button>
					  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				  </div>
				 </div>
				{!!Form::close()!!}
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="send_address_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<button type="button" class="close"
				   data-dismiss="modal">
					   <span aria-hidden="true">&times;</span>
					   <span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">
					Send Address
				</h4>
			</div>
			<!-- Modal Body -->
			<div class="modal-body">
				 <div class="tab-pane ">
				{!! Form::open(array('method' => 'post' , 'files' => true ,'id'=>"send_address_form",'name'=>"send_address_form",'class'=>"send_address_form"))!!}
				{{ csrf_field() }}
				<input type="hidden" name="customer_mno" id="customer_mno" value="{{ $result[0]->mobile }}" />

				<input type="hidden" name="customer_email" id="customer_email" value="{{ $result[0]->email }}" />

				 <div class="box-body">

					<div class="form-group col-sm-12">
						  <label class=" col-sm-3 control-label"  >Mobile No.<span class="required">*</span></label>
						  <div class="col-sm-9">
							<select multiple name="follow_up_mobile[]" id="follow_up_mobile" class="select2 " style="width:100%">
								<option value=''>Select</option>
								@if($result[0]->mobile != '')
									<option value="{{ $result[0]->mobile }}">{{ $result[0]->mobile }}</option>
								@endif
								@if($result[0]->mobile_2 != '')
									<option value="{{ $result[0]->mobile_2 }}">{{ $result[0]->mobile_2 }}</option>
								@endif
							</select>
						  </div>
					</div>

					<div class="form-group col-sm-12">
						<label class=" col-sm-3 control-label"  >Office<span class="required">*</span></label>
						<div class="col-sm-9">
							@foreach($address_master as $k=>$v)
							<div class="checkbox">
								<label>
								  <input type="checkbox" id="follow_up_address" name="follow_up_address[]" value="{{ $v->id }}">
								  {{ $v->office_name }}
								</label>
							</div>
							@endforeach
							<label id="follow_up_address[]-error" class="error" for="follow_up_address[]"></label>
						</div>
					</div>
                     <label id="follow_up_address-error" class="error" for="follow_up_address"></label>
				  <div class="form-group col-sm-12">
					<div class="col-sm-offset-3 col-sm-9">
					  <button type="submit" class="btn btn-default" id="product">Send</button>
					  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				  </div>
				 </div>
				{!!Form::close()!!}
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="order_book_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<button type="button" class="close"
				   data-dismiss="modal">
					   <span aria-hidden="true">&times;</span>
					   <span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">
					Order Book
				</h4>
			</div>
			<!-- Modal Body -->
			<div class="modal-body">
				 <div class="tab-pane ">
				 {!! Form::open(array('method' => 'post' , 'files' => true ,'id'=>"order_book_form",'name'=>"order_book_form",'class'=>"order_book_form"))!!}
				{{ csrf_field() }}

				 <div class="box-body">
					 <input type="hidden" name="inquiry_id" value="{{ $inq_id }}" id="inquiry_id" />
					<div class="form-group col-sm-12">
						<label class=" col-sm-3 control-label" >Quotation No.<span class="required">*</span></label>
						<div class="col-sm-9">
							<select name="order_quot_no" id="order_quot_no" class="select2" style="width:100%">
								<option  value=''>Select</option>
								@foreach($quotation_no as $k=> $v)
									<option value="{{ 'q-'.$v->quatation_id}}" > {{ $v->quatation_no }}</option>
								@endforeach
								@foreach($revise_quot_num as $key=> $val)
									<option value="{{ 'r-'.$val->revise_id}}" > {{ $val->revise_quatation_no}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group col-sm-12">
						  <label class=" col-sm-3 control-label"  >Quotation Date<span class="required">*</span></label>
						  <div class="col-sm-9">
							<input type="text" readonly id="order_quot_date" name="order_quot_date"  class="form-control col-md-7 col-xs-12"  autofocus=autofocus >
						  </div>
					</div>
					<div class="form-group col-sm-12">
						  <label class=" col-sm-3 control-label"  >Order Book Date<span class="required">*</span></label>
						  <div class="col-sm-9">
							<input type="text" readonly id="order_book_date" value="{{ date('d-m-Y') }}" name="order_book_date"  class="form-control col-md-7 col-xs-12 order_date " autofocus=autofocus>
						  </div>
					</div>

					<div class="form-group col-sm-12">
						<label class=" col-sm-3 control-label"  >Order By<span class="required">*</span></label>
						<div class="col-sm-9">
							<select name="order_by" id="order_by" class="select2 " style="width:100%">
								<option  value=''>Select</option>
								@foreach($order_by as $k=> $v)
									<option value="{{ $v->emp_id}}" > {{ $v->name}}</option>
								@endforeach
							</select>
						</div>
					</div>

					<div class="form-group col-sm-12">
						  <label class=" col-sm-3 control-label"  >Order Day<span class="required">*</span></label>
						  <div class="col-sm-9">
							<input type="text" readonly id="order_day" value="{{ date(l) }}" name="order_day"  class="form-control col-md-7 col-xs-12" autofocus=autofocus>
						  </div>
					</div>
					<div class="form-group col-sm-12">
						  <label class=" col-sm-3 control-label"  >Remark<span class="required">*</span></label>
						  <div class="col-sm-9">
								<textarea id="order_remark" name="order_remark" class="form-control col-md-7">{{ $follow_ups[0]->call_receive_remark }}</textarea>
						  </div>
					</div>

				  <div class="form-group col-sm-12">
					<div class="col-sm-offset-3 col-sm-9">
					  <button type="submit" class="btn btn-default" id="product">Send</button>
					  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				  </div>
				 </div>
				{!!Form::close()!!}
				</div>
			</div>
		</div>
	</div>
</div>
<div class="regret_model_hide">
<div class="modal fade" id="regret_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<button type="button" class="close"
				   data-dismiss="modal">
					   <span aria-hidden="true">&times;</span>
					   <span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">
					Add Regret
				</h4>
			</div>
			<!-- Modal Body -->
			<div class="modal-body">
				 <div class="tab-pane ">
				  {!! Form::open(array('method' => 'post' , 'files' => true ,'id'=>"regret_form",'name'=>"regret_form",'class'=>"regret_form"))!!}
				{{ csrf_field() }}

				<input type="hidden" name="quot_no" value="{{ $result[0]->quatation_no }}" />
				 <div class="box-body">
					 <input type="hidden" name="inquiry_id" value="{{ $inq_id }}" id="inquiry_id" />
					<div class="form-group col-sm-12">
						<label class=" col-sm-3 control-label"  >Allot To<span class="required">*</span></label>
						<div class="col-sm-9">
							<select name="regret_allot" id="regret_allot" class="select2 " style="width:100%">
								<option  value=''>Select</option>
								@foreach($employee as $k=> $v)
									<option value="{{ $v->emp_id}}" > {{ $v->name}}</option>
								@endforeach
							</select>
						</div>
					</div>

					<div class="form-group col-sm-12">
						  <label class=" col-sm-3 control-label" >Remark<span class="required">*</span></label>
						  <div class="col-sm-9">
								<textarea id="regret_remark" name="regret_remark" class="form-control col-md-7">{{ $follow_ups[0]->call_receive_remark }}</textarea>
						  </div>
					</div>

				  <div class="form-group col-sm-12">
					<div class="col-sm-offset-3 col-sm-9">
					  <button type="submit" class="btn btn-default" id="product">Send For Approval</button>
					  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				  </div>
				 </div>
				{!!Form::close()!!}
				</div>
			</div>
		</div>
	</div>
</div>
</div>
<div class="modal fade" id="send_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<button type="button" class="close"
				   data-dismiss="modal">
					   <span aria-hidden="true">&times;</span>
					   <span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">
					Send Quatation
				</h4>
			</div>
			<!-- Modal Body -->
			<div class="modal-body">
				 <div class="tab-pane ">
				<form class="form-horizontal" id="send_form_data">
				{{ csrf_field() }}
				 <div class="box-body">
				 <input type="hidden" name="inquiry_id" value="{{ $inq_id }}" id="inquiry_id" />
					<div class="form-group col-sm-12">
						  <label class=" col-sm-3 control-label"  >Title<span class="required">*</span></label>
						  <div class="col-sm-9">
							<select multiple name="title[]" id="title" class="select2" style="width:100%;">
								<?php
									foreach($catalog as $k=>$v)
									{
								?>
										<option value="<?php echo $v->id; ?>"><?php echo $v->catalog_title; ?></option>
								<?php
									}
								?>
							</select>
						  </div>
					</div>
					<div class="form-group col-sm-12">
						  <label class=" col-sm-3 control-label" >Email ID<span class="required">*</span></label>
						  <div class="col-sm-9">
							<select name="send_email_id" id="send_email_id" class="select2" style="width:100%;">
								<option value="">Select</option>
								@if($result[0]->email != '')
									<option value="{{ $result[0]->email }}">{{ $result[0]->email }}</option>
								@endif
								@if($result[0]->email_2 != '')
									<option value="{{ $result[0]->email_2 }}">{{ $result[0]->email_2 }}</option>
								@endif
							</select>
						  </div>
					</div>

				  <div class="form-group col-sm-12">
					<div class="col-sm-offset-3 col-sm-9">
					  <button type="submit" class="btn btn-default" id="product">Send</button>
					  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				  </div>
				 </div>
				</form>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="send_reminder_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<button type="button" class="close"
				   data-dismiss="modal">
					   <span aria-hidden="true">&times;</span>
					   <span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">
					Send Reminder
				</h4>
			</div>
			<!-- Modal Body -->
			<div class="modal-body">
				 <div class="tab-pane ">
				<form class="form-horizontal" id="send_reminder_form">
				{{ csrf_field() }}
				 <div class="box-body">
				 <input type="hidden" name="inquiry_id" value="{{ $inq_id }}" id="inquiry_id" />
				 <input type="hidden" name="next_f_date" id="next_f_date" />
				 <input type="hidden" name="quatation_id" value="{{ $result[0]->quatation_id }}" id="quatation_id" />

				{!! Form::hidden('last_followup_id',$follow_ups[0]->follow_up_id, array('id'=>"last_followup_id")) !!}
					<div class="form-group col-sm-12">
						  <label class=" col-sm-3 control-label" >Email ID<span class="required">*</span></label>
						  <div class="col-sm-9">
							<select name="send_reminder_mail" id="send_reminder_mail" class="select2" style="width:100%;">
								<option value="">Select</option>
								@if($result[0]->email != '')
									<option value="{{ $result[0]->email }}">{{ $result[0]->email }}</option>
								@endif
								@if($result[0]->email_2 != '')
									<option value="{{ $result[0]->email_2 }}">{{ $result[0]->email_2 }}</option>
								@endif
							</select>
						  </div>
					</div>
					<div class="form-group col-sm-12">
						<label class="col-sm-3 control-label">Attachment</label>
						<div class="col-sm-9">
							<input type="file" name="attachment" id="attachment" class="form-control" />
						</div>
					</div>

				  <div class="form-group col-sm-12">
					<div class="col-sm-offset-3 col-sm-9">
					  <button type="submit" class="btn btn-default" id="product">Send</button>
					  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				  </div>
				 </div>
				</form>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade bd-example-modal-lg" id="visiting_form_model" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header">
				<button type="button" class="close"
				   data-dismiss="modal">
					   <span aria-hidden="true">&times;</span>
					   <span class="sr-only">Close</span>
				</button>
				<h4 class="modal-title" id="myModalLabel">
					Visiting Form
				</h4>
			</div>
			<!-- Modal Body -->
			<div class="modal-body">
				 <div class="tab-pane ">
				  {!! Form::open(array('method' => 'post' , 'files' => true ,'id'=>"visitor_form",'name'=>"visitor_form",'class'=>"visitor_form"))!!}
				{{ csrf_field() }}
				<input type="hidden" name="quatation_id" value="{{ $result[0]->quatation_id }}" id="quatation_id" />
				<input type="hidden" name="inquiry_id" value="{{ $inq_id }}" id="inquiry_id" />
				<input type="hidden" name="quot_no" value="{{ $result[0]->quatation_no }}" />
				 <div class="box-body">
				 <div class="row">
					<div class="form-group col-sm-12">
						<div class="col-sm-4">
							<label class="control-label" >Quot. No.<span class="required">*</span></label>
							<input type="text" name="visitor_quot_no" id="visitor_quot_no" readonly value="{{ $result[0]->quatation_no }}" class="form-control input-sm" />
						</div>
						<div class="col-sm-4">
							<label class="control-label" >Quot. Date<span class="required">*</span></label>
							<input type="text" id="visitor_quot_date" name="visitor_quot_date" readonly value="{{ date('d-m-Y',strtotime($result[0]->quatation_date)) }}" class="form-control input-sm" />
						</div>
						<div class="col-sm-4">
							<label class="control-label" >Form No.<span class="required">*</span></label>
							<input type="text" id="visitor_form_no" name="visitor_form_no" readonly  class="form-control input-sm" />
						</div>
					</div>
					<div class="form-group col-sm-12">
						<div class="col-sm-4">
							<label class="control-label" >Visit Date<span class="required">*</span></label>
							<input type="text" name="visitor_visit_date" id="visitor_visit_date" readonly value="{{ date('d-m-Y') }}" class="form-control input-sm" />
						</div>
						<div class="col-sm-4">
							<label class="control-label" >Visit Time<span class="required">*</span></label>
							<input type="text"  name="visitor_visit_time"  id="visitor_visit_time"  class="timepicker form-control input-sm" />
						</div>
						<div class="col-sm-4">
							<label class="control-label" >Visit In<span class="required">*</span></label>
							<select class="select2 " style="width:100%" id="visitor_visit_at" name="visitor_visit_at">
								<option value="">Select</option>
								@foreach($visit_at as $key=>$val)
									<option value="{{ $val->id }}">{{ $val->office_name }}</option>
								@endforeach
							</select>
						</div>
					</div>

					<div class="form-group col-sm-12">
						<div class="col-sm-4">
							<label class="control-label" >Attend By<span class="required">*</span></label>
							<select name="visitor_attended_by" id="visitor_attended_by" class="select2 clear_data_select" style="width:100%">
								<option  value=''>Select</option>
								@foreach($employee as $k=> $v)
									@if ($v->emp_id == $result[0]->visitor_attended_by)
										<option value="{{ $v->emp_id}}" selected="selected" > {{ $v->name}}</option>
									@else
										<option value="{{ $v->emp_id}}" > {{ $v->name}}</option>
									@endif
								@endforeach
							</select>
						</div>
					</div>
				</div>
					<hr>
					<center><h3><u>Client Detail</u></h3></center>
				<div class="row">
					<div class="form-group col-sm-12">
						<div class="col-sm-4">
							<label class="control-label" >Client Name-1<span class="required">*</span></label>
							<input type="text" name="visitor_client_name1" id="visitor_client_name1" class="form-control input-sm clear_data" />
						</div>
						<div class="col-sm-4">
							<label class="control-label" >Mobile-1</label>
							<input type="text" name="visitor_client_mobile1" maxlength="10" id="visitor_client_mobile1" class="form-control input-sm clear_data" />
						</div>
						<div class="col-sm-4">
							<label class="control-label" >Email-1</label>
							<input type="text" name="visitor_client_email1" id="visitor_client_email1" class="form-control input-sm clear_data" />
						</div>
					</div>
					<div class="form-group col-sm-12">
						<div class="col-sm-4">
							<label class="control-label" >Client Name-2</label>
							<input type="text" name="visitor_client_name2" id="visitor_client_name2" class="form-control input-sm clear_data" />
						</div>
						<div class="col-sm-4">
							<label class="control-label" >Mobile-2</label>
							<input type="text" name="visitor_client_mobile2" maxlength="10" id="visitor_client_mobile2" class="form-control input-sm clear_data" />
						</div>
						<div class="col-sm-4">
							<label class="control-label" >Email-2</label>
							<input type="text" name="visitor_client_email2" id="visitor_client_email2" class="form-control input-sm clear_data" />
						</div>
					</div>
					<div class="form-group col-sm-12">
						<div class="col-sm-4">
							<label class="control-label" >Client Name-3</label>
							<input type="text" name="visitor_client_name3" id="visitor_client_name3" class="form-control input-sm clear_data" />
						</div>
						<div class="col-sm-4">
							<label class="control-label" >Mobile-3</label>
							<input type="text" name="visitor_client_mobile3" maxlength="10" id="visitor_client_mobile3" class="form-control input-sm clear_data" />
						</div>
						<div class="col-sm-4">
							<label class="control-label" >Email-3</label>
							<input type="text" name="visitor_client_email3" id="visitor_client_email3" class="form-control input-sm clear_data" />
						</div>
					</div>
					<div class="form-group col-sm-12">
						<div class="col-sm-4">
							<label class="control-label" >Company Name</label>
							<input type="text" name="visitor_company_name" id="visitor_company_name" class="form-control input-sm clear_data" />
						</div>
						<div class="col-sm-4">
							<label class="control-label" >Landline No.</label>
							<input type="text" name="visitor_landline_no" id="visitor_landline_no" class="form-control input-sm clear_data" />
						</div>
					</div>
					<div class="form-group col-sm-12">
						<div class="col-sm-6">
							<label class="control-label" >Address</label>
							<textarea name="visitor_address" id="visitor_address" class="form-control input-sm clear_data" ></textarea>
						</div>
						<div class="col-sm-6">
							<label class="control-label" >Office Address</label>
							<textarea name="visitor_office_address" id="visitor_office_address"  class="form-control input-sm clear_data" ></textarea>
						</div>
					</div>
					<div class="form-group col-sm-12">
						<div class="col-sm-4">
							<label class="control-label" >Country</label>
							<select name="visitor_country" id="visitor_country" class="select2 clear_data_select" style="width:100%">
								<option  value="">Select</option>
								@foreach($country as $k=> $v)
									<option value="{{ $v->country_id}}" > {{ $v->country_name}}</option>
								@endforeach
							</select>
						</div>
						<div class="col-sm-4">
							<label class="control-label" >State</label>
							<select name="visitor_state" id="visitor_state" class="select2 clear_data_select" style="width:100%">
								<option  value="">Select</option>
								@foreach($state as $k=> $v)
									<option value="{{ $v->state_id}}" > {{ $v->state_name}}</option>
								@endforeach
							</select>
						</div>
						<div class="col-sm-4">
							<label class="control-label" >City</label>
							<select name="visitor_city" id="visitor_city" class="select2 clear_data_select" style="width:100%">
								<option  value="">Select</option>
								@foreach($city as $k=> $v)
									<option value="{{ $v->city_id}}" > {{ $v->city_name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group col-sm-12">
						<div class="col-sm-4">
							<label class="control-label" >Land</label>
							<input type="text" name="visitor_land" id="visitor_land" class="form-control input-sm clear_data" />
						</div>
						<div class="col-sm-4">
							<label class="control-label" >Site Status</label>
							<select name="visitor_site_status" id="visitor_site_status" class="select2 clear_data_select" style="width:100%">
								<option  value=''>Select</option>
								@foreach($site_status_master as $k=> $v)
									<option value="{{ $v->site_status_id}}" > {{ $v->site_status_name }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-sm-4">
							<label class="control-label" >Power</label>
							<select name="visitor_power_supply" id="visitor_power_supply" class="select2 clear_data_select" style="width:100%">
								<option  value=''>Select</option>
								@foreach($power_supply_master as $k=> $v)
									<option value="{{ $v->power_supply_id}}" > {{ $v->power_supply_name }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group col-sm-12">
						<div class="col-sm-4">
							<label class="control-label" >Water Source</label>
							<select name="visitor_water_source" id="visitor_water_source" class="select2 clear_data_select" style="width:100%">
								<option  value=''>Select</option>
								@foreach($raw_water_master as $k=>$v)
									<option value="{{ $v->raw_water_id }}" > {{ $v->raw_water_name }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-sm-4">
							<label class="control-label" >Water Report</label>
							<select name="visitor_water_report" id="visitor_water_report" class="select2 clear_data_select" style="width:100%">
								<option  value=''>Select</option>
								@foreach($water_report_master as $k=>$v)
									<option value="{{ $v->water_report_id }}" > {{ $v->water_report_name }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group col-sm-12">
						<div class="col-sm-4">
							<label class="control-label" >Project Value</label>
							<input type="text" name="visitor_project_value" id="visitor_project_value" class="form-control input-sm clear_data" />
						</div>
						<div class="col-sm-4">
							<label class="control-label" >Payment Mode</label>
							<select name="visitor_payment_mode" id="visitor_payment_mode" class="select2 clear_data_select" style="width:100%">
								<option  value=''>Select</option>
								@foreach($payment_mode_master as $k=>$v)
									<option value="{{ $v->payment_mode_id }}" > {{ $v->payment_mode_name }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-sm-4">
							<label class="control-label" >Quotation Status</label>
							<select name="visitor_quatation_status" id="visitor_quatation_status" class="select2 clear_data_select" style="width:100%">
								<option  value=''>Select</option>
								@foreach($quatation_status_master as $k=>$v)
									<option value="{{ $v->quatation_status_id }}" > {{ $v->quatation_status_name }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
				<hr>
					<center><h3><u>Plant Detail</u></h3></center>
				<div class="row">
					<div class="form-group col-sm-12">
						<div class="col-sm-6">
							<label class="control-label" >Inquiry For</label>
							<select name="visitor_inquiry_for" id="visitor_inquiry_for" class="select2 clear_data_select" style="width:100%">
								<option value=''>Select</option>
								@foreach($product as $k=>$v)
									<option value="{{ $v->product_id }}" > {{ $v->product_name }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-sm-6">
							<label class="control-label" >Inquiry Type</label>
							<select name="visitor_inquiry_type" id="visitor_inquiry_type" class="select2 clear_data_select" style="width:100%">
								<option  value=''>Select</option>
								@foreach($category as $k=>$v)
									<option value="{{ $v->category_id }}" > {{ $v->category_name }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group col-sm-12">
						<div class="col-sm-6">
							<label class="control-label" >Product Detail</label>
							<select name="visitor_product_detail" id="visitor_product_detail" class="select2 clear_data_select" style="width:100%">
								<option  value=''>Select</option>
								@foreach($quatation_product as $k=>$v)
									<option value="{{ $v->p_id }}" > {{ $v->name }}</option>
								@endforeach
							</select>
						</div>
					</div>
				</div>
					<hr>

				<center><h3><u>Office Use Only</u></h3></center>
				<div class="row">
					<div class="form-group col-sm-12">
						<div class="col-sm-6">
							<label class="control-label" >Client Category</label>
							<select name="visitor_client_category" id="visitor_client_category" class="select2 clear_data_select" style="width:100%">
								<option value=''>Select</option>
								@foreach($client_category_master as $k=>$v)
									<option value="{{ $v->client_category_id }}" > {{ $v->client_category_name }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-sm-6">
							<label class="control-label" >Day for Next Follow-Up</label>
							<input type="text" name="visitor_follow_up" id="visitor_follow_up" class="form-control input-sm numberonly clear_data" />
						</div>
					</div>
					<div class="form-group col-sm-12">
						<div class="col-sm-6">
							<label class="control-label" >Remark</label>
							<textarea name="visitor_remark" id="visitor_remark" class="form-control input-sm clear_data" >{{ $follow_ups[0]->call_receive_remark }}</textarea>
						</div>
					</div>
				</div>
				  <div class="modal-footer form-group col-sm-12">
					<div class="col-sm-offset-3 col-sm-4">
					  <button type="submit" class="btn btn-default" id="product">Save</button>
					  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				  </div>
				 </div>
				{!!Form::close()!!}
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">

Date.prototype.getWeek = function() {
	var date = new Date(this.getTime());
	date.setHours(0, 0, 0, 0);
	date.setDate(date.getDate() + 3 - (date.getDay() + 6) % 7);
	var week1 = new Date(date.getFullYear(), 3, 1);
	return 1 + Math.round(((date.getTime() - week1.getTime()) / 86400000 - 3 + (week1.getDay() + 6) % 7) / 7);
}

function getDateRangeOfWeek(weekNo, y)
{
    var d1, numOfdaysPastSinceLastMonday, rangeIsFrom, rangeIsTo;
    d1 = new Date(''+y+'');
    numOfdaysPastSinceLastMonday = d1.getDay() - 1;
    d1.setDate(d1.getDate() - numOfdaysPastSinceLastMonday);
    d1.setDate(d1.getDate() + (7 * (weekNo - d1.getWeek())));
    rangeIsFrom = d1.getDate() + "-" + (d1.getMonth() + 1) + "-" + d1.getFullYear();
    d1.setDate(d1.getDate() + 6);
    rangeIsTo = d1.getDate() + "-" + (d1.getMonth() + 1) + "-" + d1.getFullYear() ;
    return rangeIsFrom + "," + rangeIsTo;
};

function get_financial_year()
{
    var today = new Date();
    var cur_month = today.getMonth();
    var year = "";
    if (cur_month => 3)
	{
        year = today.getFullYear().toString();
    }
	else
	{
        year = (today.getFullYear() - 1).toString();
    }
    return year;
}

jQuery(document).ready(function($){



	$('body').on('keyup','#acc_week', function(){
		var weak = $(this).val();
		if(weak == '')
		{
			$('#hot_list_from_date').val('');
			$('#hot_list_to_date').val('');
		}
		else
		{
			var date = getDateRangeOfWeek(weak,get_financial_year());
			var date1 = date.split(",");
			$('#hot_list_from_date').val(date1[0]);
			$('#hot_list_to_date').val(date1[1]);
		}
	});

	$('.order_date').datepicker({
		autoclose: true,
		format: 'dd-mm-yyyy',
	});

	$('.order_date').on('show', function(e){
		if ( e.date )
		{
			 $(this).data('stickyDate', e.date);
		}
		else {
			 $(this).data('stickyDate', null);
		}
	});


	$('#visitor_visit_date').datepicker({
		autoclose: true,
		format: 'dd-mm-yyyy',
	});

	$('#visitor_visit_date').on('show', function(e){
		if ( e.date )
		{
			 $(this).data('stickyDate', e.date);
		}
		else {
			 $(this).data('stickyDate', null);
		}
	});

	$('.timepicker').timepicker({
		showInputs: false
    });


	var last_remark = $('#last_remark').val();

	if(last_remark == '')
	{
		$('.all_btn_disabled').attr('disabled','disabled');
		$('.all_btn_disabled').attr('style','pointer-events: none');
	}
	else
	{
		$('.all_btn_disabled').removeAttr('disabled', 'disabled');
		$('.all_btn_disabled').removeAttr('style','pointer-events: none');
	}

	// order book

	<?php
		if($order_book == 'yes')
		{
	?>
			$('.all_btn_hide').hide();
	<?php
		}
	?>

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
			url: "<?php echo $mobile_check; ?>",
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


	$('body').on('click','#visiting_form_btn', function(){
		$('.blockUI').show();
		$.ajax({
			type: "POST",
			url: "<?php echo $visitor_form_no_url;?>",
			data: { "_token": "{{ csrf_token() }}"},
			success:function(res)
			{
				$('.blockUI').hide();
				$('#visitor_form_no').val(res);
			}
		});
	});
	$('body').on('change','#visitor_country', function(){
		var country_id = $(this).val();
		$('.blockUI').show();
		$.ajax({
			type: "POST",
			url: "<?php echo $get_state_url;?>",
			data: { "_token": "{{ csrf_token() }}",country_id:country_id},
			success:function(res)
			{
				$('.blockUI').hide();
				$("#visitor_state").empty().append().html(res).trigger('change.select2');
			}
		});
	});
	$('body').on('change','#visitor_state', function(){
		var state_id = $(this).val();
		$('.blockUI').show();
		$.ajax({
			type: "POST",
			url: "<?php echo $get_city_url;?>",
			data: { "_token": "{{ csrf_token() }}",state_id:state_id},
			success:function(res)
			{
				$('.blockUI').hide();
				$("#visitor_city").empty().append().html(res).trigger('change.select2');
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


	$('body').on('change','#order_quot_no', function(){
		var order_quot_no = $("#order_quot_no").val();
		if(order_quot_no == '')
			return false;

		$('.blockUI').show();
		$.ajax({
			type: "POST",
			url: "<?php echo $get_quatation_date; ?>",
			data: { "_token": "{{ csrf_token() }}",order_quot_no:order_quot_no},
			success:function(res)
			{
				$('#order_quot_date').val(res);
				$('.blockUI').hide();
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



	$('body').on('change','.customer_category_id', function(){
		var client_category = $(this).val();
		$(".follow_up_category_id").val(client_category).trigger('change');
	});

	$.validator.setDefaults({ ignore: ":hidden:not(.select2)"});
	var id= $("#id").val();
	$('#customer_frm').validate({
		rules: {
			client_category_id: {required: true,},
		},
		messages:{
			client_category_id: {required:"Please Select Client Category"},
		},
	});

	$('#customer_frm').on('submit', function(e){
		e.preventDefault();
		var formData = new FormData(this);
		var form = this;

		if($("#customer_frm").valid())
		{
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
			$(':input[type="submit"]').prop('disabled', true);
			//form.submit();
			$('.blockUI').show();
			$.ajax({
				type: 'post',
				url: '{{$customer_url}}',
				data: $('#customer_frm').serialize(),
				success: function (response)
				{
					if(response=='success')
					{
						alert('Customer Detail Successfully Saved.');
						$(':input[type="submit"]').prop('disabled', false);
						$('.blockUI').hide();
					}
					else
					{
						$('.blockUI').hide();
						$(':input[type="submit"]').prop('disabled', false);
						alert('Customer Detail Not Successfully Updated.');
					}
				},
				error:function()
				{
					$('.blockUI').hide();
					alert('Customer Detail Not Successfully Updated.');
				}
			});

		}
		else
		{
			return false;
		}
	});
	//Customer Validation End


	//Follow Up Validation Start
	function add_date(ad)
	{
		var noOfDaysToAdd=ad;
		var startDate = new Date();
		var endDate = "", count = 0;
		while(count < noOfDaysToAdd){
			endDate = new Date(startDate.setDate(startDate.getDate() + 1));
			if(endDate.getDay() != '{{$holiday_count[0]->days}}'){
			   //Date.getDay() gives weekday starting from 0(Sunday) to 6(Saturday)
			   count++;
			}
		}
		function formatDate(d)
		{
		  date = new Date(d);
		  var dd = date.getDate();
		  var mm = date.getMonth()+1;
		  var yyyy = date.getFullYear();
		  if(dd<10){dd='0'+dd}
		  if(mm<10){mm='0'+mm};
		  return d = dd+'-'+mm+'-'+yyyy
		}
		return formatDate(endDate);
	}
	$('#follow_up_frm').validate({
		rules: {
			day_followup_date: {required: true,min:1,},
			followup_way_id: {required: true,},
		},
		messages:{
			day_followup_date: {required: "Please Enter Days For Next Follow-up",min:'Please Enter Minimum 1 Day'},
			call_type: {required: "Please Select Call Type"},
			follow_up_detail_email: {required: "Please Select Detail"},
			follow_up_detail_mobile: {required: "Please Select Detail"},
			followup_way_id: {required: "Please Select Follow-Up Way",},
			call_receive_remark: {required: "Please Enter Remark.",},
		},
	});

	$('#follow_up_frm').on('submit', function(e){
		e.preventDefault();

		var formData = new FormData(this);
		var form = this;

		if($("#follow_up_frm").valid())
		{
			$(':input[type="submit"]').prop('disabled', true);

			$('.blockUI').show();
			$.ajax({
				type: 'post',
				url: '{{$follow_up_url}}',
				data: formData,
				dataType: "json",
				processData: false,
				contentType: false,
				success: function (response)
				{
					if(response[0]=='success')
					{
						alert('Follow Up Detail Successfully Saved.');
						$('#day_followup_date').val('');
						$('#followup_way_id').select2().val('').trigger('change');
						$('#call_type').select2().val('').trigger('change');

						$('#follow_up_data').empty().append(response[1]);

						if(response[2] != '')
						{
							$('#price_remark').val(response[2]);
							$('#revice_remark').val(response[2]);
							$('#last_remark').val(response[2]);
							$('#hot_list_remark').val(response[2]);
							$('#order_remark').val(response[2]);
							$('#visitor_remark').val(response[2]);
							$('#regret_remark').val(response[2]);
							$('.all_btn_disabled').removeAttr('disabled', 'disabled');
							$('.all_btn_disabled').removeAttr('style','pointer-events: none');
						}
						else
						{
							$('.all_btn_disabled').attr('disabled', 'disabled');
							$('.all_btn_disabled').attr('style','pointer-events: none');

						}
						$(':input[type="submit"]').prop('disabled', false);
						$('.blockUI').hide();
					}
					else
					{
						alert('Follow Up Detail Not Successfully Saved.');
						$(':input[type="submit"]').prop('disabled', false);
						$('.blockUI').hide();
					}
				},
				error:function()
				{
					alert('Follow Up Detail Not Successfully Updated.');
					$(':input[type="submit"]').prop('disabled', false);
					$('.blockUI').hide();
				}
			});
		}
		else
		{
			return false;
		}
	});


	$('#visiting_form').validate({
		rules: {
			visiting_person: {required: true,},
			visit_at: {required: true,},
			visitor_attended_by: {required: true,},
			visit_status: {required: true,},
			form_no_visit: {required: true,},
		},
		messages:{
			visiting_person: {required: "Please Enter Visiting Person",},
			visit_at: {required: "Please Select Visit At"},
			visitor_attended_by: {required: "Please Select Attended By",},
			visit_status: {required: "Please Select Status ",},
			form_no_visit: {required: "Please Select Form Number ",},

		},
	});

	$('#revice_model_form').validate({
		rules: {
			revice_quot_person: {required: true,},
			revice_remark: {required: true,},
		},
		messages:{
			revice_quot_person: {required: "Please Select Quotation Person.",},
			revice_remark: {required: "Please Enter Remarks."},
		},
	});

	$('#revice_model_form').on('submit', function(e){
		e.preventDefault();
		var formData = new FormData(this);
		var form = this;

		if($("#revice_model_form").valid())
		{
			$(':input[type="submit"]').prop('disabled', true);

			$('.blockUI').show();
			$.ajax({
				type: 'post',
				url: '{{$send_revise_url}}',
				data : formData,
				dataType: "json",
				processData: false,
				contentType: false,
				success: function (response)
				{
					if(response[0] == 'success')
					{
						alert('Revise Send Successfully.');
						$(':input[type="submit"]').prop('disabled', false);
						$('#revice_model').modal('hide');
						$('.blockUI').hide();
					}
					else
					{
						alert('Revise Not Successfully Send.');
						$(':input[type="submit"]').prop('disabled', false);
						$('.blockUI').hide();
					}
				},
				error:function()
				{
					alert('Revise Not Successfully Send.');
					$(':input[type="submit"]').prop('disabled', false);
					$('.blockUI').hide();
				}
			});
		}
		else
		{
			return false;
		}
	});

	$('#prise_issue_form').validate({
		rules: {
			prisse_issue_person: {required: true,},
			price_remark: {required: true,},
		},
		messages:{
			prisse_issue_person: {required: "Please Select Name.",},
			price_remark: {required: "Please Enter Remarks."},
		},
	});

	$('#prise_issue_form').on('submit', function(e){
		e.preventDefault();
		var formData = new FormData(this);
		var form = this;

		if($("#prise_issue_form").valid())
		{
			$(':input[type="submit"]').prop('disabled', true);

			$('.blockUI').show();
			$.ajax({
				type: 'post',
				url: '{{$prise_issue_url}}',
				data : formData,
				dataType: "json",
				processData: false,
				contentType: false,
				success: function (response)
				{
					if(response[0] == 'success')
					{
						alert('Prise Issue Send Successfully.');
						$(':input[type="submit"]').prop('disabled', false);
						$('#price_issue_model').modal('hide');
						$('.blockUI').hide();
					}
					else
					{
						alert('Prise Issue Not Successfully Send.');
						$(':input[type="submit"]').prop('disabled', false);
						$('.blockUI').hide();
					}
				},
				error:function()
				{
					alert('Prise Issue Successfully Send.');
					$(':input[type="submit"]').prop('disabled', false);
					$('.blockUI').hide();
				}
			});
		}
		else
		{
			return false;
		}
	});


	$('#send_reminder_form').validate({
		rules: {
			send_reminder_mail: {required: true,},
		},
		messages:{
			send_reminder_mail: {required: "Please Select Email",},
		},
	});
	$('#send_reminder_form').on('submit', function(e){
		var nextfollday = add_date(parseInt('{{$minimum_email[0]->days}}'));
		$('#send_reminder_form').find('#next_f_date').val(nextfollday);

		e.preventDefault();
		var formData = new FormData(this);
		var form = this;

		if($("#send_reminder_form").valid())
		{
			$(':input[type="submit"]').prop('disabled', true);

			$('.blockUI').show();
			$.ajax({
				type: 'post',
				url: '{{$send_reminder_mail_url}}',
				data : formData,
				dataType: "json",
				processData: false,
				contentType: false,
				success: function (response)
				{
					if(response[0] == 'success')
					{
						alert('Mail Send Successfully.');
						$('#follow_up_data').empty().append(response[1]);
						$(':input[type="submit"]').prop('disabled', false);
						$('#send_reminder_model').modal('hide');
						$('.blockUI').hide();
					}
					else
					{
						alert('Mail Not Send.');
						$(':input[type="submit"]').prop('disabled', false);
						$('.blockUI').hide();
					}
				},
				error:function()
				{
					alert('Mail Not Send.');
					$(':input[type="submit"]').prop('disabled', false);
					$('.blockUI').hide();
				}
			});
		}
		else
		{
			return false;
		}
	});
	$('#hot_list_form').validate({
		rules: {
			acc_week: {required: true,},
			hot_list_remark: {required: true,},
		},
		messages:{
			acc_week: {required: "Please Enter Week Number.",},
			hot_list_remark: {required: "Please Enter Remarks."},
		},
	});

	$('#hot_list_form').on('submit', function(e){
		e.preventDefault();
		var formData = new FormData(this);
		var form = this;

		if($("#hot_list_form").valid())
		{
			$(':input[type="submit"]').prop('disabled', true);

			$('.blockUI').show();
			$.ajax({
				type: 'post',
				url: '{{$hot_list_url}}',
				data : formData,
				dataType: "json",
				processData: false,
				contentType: false,
				success: function (response)
				{
					if(response[0] == 'success')
					{
						alert('Hot List Send Successfully.');
						$(':input[type="submit"]').prop('disabled', false);
						$('#hot_list_model').modal('hide');
						$('.blockUI').hide();
					}
					else
					{
						alert('Hot List Not Successfully Send.');
						$(':input[type="submit"]').prop('disabled', false);
						$('.blockUI').hide();
					}
				},
				error:function()
				{
					alert('Hot List Not Successfully Send.');
					$(':input[type="submit"]').prop('disabled', false);
					$('.blockUI').hide();
				}
			});
		}
		else
		{
			return false;
		}
	});
	$('#send_form_data').validate({
		rules: {
			'title[]': {required: true,},
			send_email_id: {required: true,},
		},
		messages:{
			'title[]': {required: "Please Select Catelog",},
			send_email_id: {required: "Please Select Email",},
		},
	});
	$('#send_form_data').on('submit', function(e){
		e.preventDefault();
		var formData = new FormData(this);
		var form = this;

		if($("#send_form_data").valid())
		{
			$(':input[type="submit"]').prop('disabled', true);

			$('.blockUI').show();
			$.ajax({
				type: 'post',
				url: '{{$send_mail_url}}',
				data: formData,
				dataType: "json",
				processData: false,
				contentType: false,
				success: function (response)
				{
					if(response[0] == 'success')
					{
						alert('Mail Sent Successfully');
						$('#title').select2().val('').trigger('change');
						$('#send_email_id').select2().val('').trigger('change');
						$(':input[type="submit"]').prop('disabled', false);
						$('#send_model').modal('hide');
						$('.blockUI').hide();
					}
					else
					{
						alert(response[0]);
						$('#title').select2().val('').trigger('change');
						$(':input[type="submit"]').prop('disabled', false);
						$('#send_model').modal('hide');
						$('.blockUI').hide();
					}
				},
				error:function()
				{
					alert(response[0]);
					$('#title').select2().val('').trigger('change');
					$(':input[type="submit"]').prop('disabled', false);
					$('#send_model').modal('hide');
					$('.blockUI').hide();
				}
			});
		}
		else
		{
			return false;
		}
	});

	$('#send_address_form').validate({
		rules: {
			'follow_up_address[]': {required: true,},
			'follow_up_mobile[]': {required: true,},
		},
		messages:{
			'follow_up_address[]': {required: "Please Select Office Address",},
			'follow_up_mobile[]': {required: "Please Select Mobile Number",},
		},
	});
	$('#send_address_form').on('submit', function(e){
		e.preventDefault();
		var formData = new FormData(this);
		var form = this;

		if($("#send_address_form").valid())
		{
			$(':input[type="submit"]').prop('disabled', true);

			$('.blockUI').show();
			$.ajax({
				type: 'post',
				url: '{{$send_address_url}}',
				data : formData,
				dataType: "json",
				processData: false,
				contentType: false,
				success: function (response)
				{
					if(response[0] == 'success')
					{
						alert('Address Send Successfully.');
						$(':input[type="submit"]').prop('disabled', false);
						$('#hot_list_model').modal('hide');
						$('.blockUI').hide();
					}
					else
					{
						alert('Address Not Successfully Send.');
						$(':input[type="submit"]').prop('disabled', false);
						$('.blockUI').hide();
					}
				},
				error:function()
				{
					alert('Address Not Successfully Send.');
					$(':input[type="submit"]').prop('disabled', false);
					$('.blockUI').hide();
				}
			});
		}
		else
		{
			return false;
		}
	});

	$('#order_book_form').validate({
		rules: {
			order_quot_no: {required: true,},
			order_quot_date: {required: true,},
			order_book_date: {required: true,},
			order_day: {required: true,},
			order_remark: {required: true,},
			order_by: {required: true,},
		},
		messages:{
			order_quot_no: {required: "Please Select Quotation Number",},
			order_quot_date: {required: "Please Select Order Quotation Date",},
			order_book_date: {required: "Please Select Order Book Date",},
			order_day: {required: "Please Enter Order Day.",},
			order_remark: {required: "Please Enter Remarks.",},
			order_by: {required: "Please Select Order Person",},
		},
	});
	$('#order_book_form').on('submit', function(e){
		e.preventDefault();
		var formData = new FormData(this);
		var form = this;

		if($("#order_book_form").valid())
		{
			$(':input[type="submit"]').prop('disabled', true);

			$('.blockUI').show();
			$.ajax({
				type: 'post',
				url: '{{$order_book_url}}',
				data : formData,
				dataType: "json",
				processData: false,
				contentType: false,
				success: function (response)
				{
					if(response[0] == 'success')
					{
						alert('Order Book Successfully.');

						$('#order_quot_no').select2().val('').trigger('change');
						$('#order_quot_date').val('');
						$('#order_book_date').val('');
						$('#order_day').val('');

						$(':input[type="submit"]').prop('disabled', false);
						$('#order_book_model').modal('hide');
						$('.blockUI').hide();
					}
					else
					{
						alert('Order Not Booked.');
						$(':input[type="submit"]').prop('disabled', false);
						$('.blockUI').hide();
					}
				},
				error:function()
				{
					alert('Order Not Booked.');
					$(':input[type="submit"]').prop('disabled', false);
					$('.blockUI').hide();
				}
			});
		}
		else
		{
			return false;
		}
	});

	$('#regret_form').validate({
		rules: {
			regret_allot: {required: true,},
			regret_remark: {required: true,},
		},
		messages:{
			regret_allot: {required: "Please Select Regret Allot Person",},
			regret_remark: {required: "Please Enter Regret Remark",},
		},
	});
	$('#regret_form').on('submit', function(e){
		e.preventDefault();
		var formData = new FormData(this);
		var form = this;

		if($("#regret_form").valid())
		{
			var client_c = $('#client_category_id').val();
			if(client_c != '6')
			{
				alert("Please Select Client Category Regret In Customer Detail");
				$('#regret_model').modal('hide');
				return false;
			}

			$(':input[type="submit"]').prop('disabled', true);

			$('.blockUI').show();
			$.ajax({
				type: 'post',
				url: '{{$regret_url}}',
				data : formData,
				dataType: "json",
				processData: false,
				contentType: false,
				success: function (response)
				{
					if(response[0] == 'success')
					{
						alert('Regret Add Successfully.');

						$('#regret_allot').select2().val('').trigger('change');

						$(':input[type="submit"]').prop('disabled', false);
						$('#regret_model').modal('hide');
						$('.blockUI').hide();
					}
					else
					{
						alert('Regret Not Added.');
						$(':input[type="submit"]').prop('disabled', false);
						$('.blockUI').hide();
					}
				},
				error:function()
				{
					alert('Regret Not Added.');
					$(':input[type="submit"]').prop('disabled', false);
					$('.blockUI').hide();
				}
			});
		}
		else
		{
			return false;
		}
	});

	$('#visiting_form').on('submit', function(e){
		e.preventDefault();
		var formData = new FormData(this);
		var form = this;

		if($("#visiting_form").valid())
		{
			$(':input[type="submit"]').prop('disabled', true);

			$('.blockUI').show();
			$.ajax({
				type: 'post',
				url: '{{$visitor_url}}',
				data: formData,
				dataType: "json",
				processData: false,
				contentType: false,
				success: function (response)
				{
					if(response[0] == 'success')
					{
						alert('Visitor Detail Successfully Saved.');
						$('#visiting_person').val('');
						$('#visit_at').select2().val('').trigger('change');
						$('#visitor_attended_by').select2().val('').trigger('change');
						$('#visit_status').select2().val('').trigger('change');
						$('#form_no_visit').select2().val('').trigger('change');

						$('#visiting_detail_data').empty().append(response[1]);

						$(':input[type="submit"]').prop('disabled', false);
						$('.blockUI').hide();
					}
					else
					{
						alert('Visitor Detail Not Successfully Saved.');
						$(':input[type="submit"]').prop('disabled', false);
						$('.blockUI').hide();
					}
				},
				error:function()
				{
					alert('Visitor Detail Not Successfully Updated.');
					$(':input[type="submit"]').prop('disabled', false);
					$('.blockUI').hide();
				}
			});
		}
		else
		{
			return false;
		}
	});



	$('#document_frm').validate({
		rules: {
			doc_detail: {required: true,},
			attached_document: {required: true,},
		},
		messages:{
			doc_detail: {required: "Please Select Document Detail",},
			attached_document: {required: "Please Select File"},
		},
	});

	$('#document_frm').on('submit', function(e){
		e.preventDefault();
		var formData = new FormData(this);
		var form = this;

		if($("#document_frm").valid())
		{
			$(':input[type="submit"]').prop('disabled', true);

			$('.blockUI').show();
			$.ajax({
				type: 'post',
				url: '{{$document_url}}',
				data: formData,
				dataType: "json",
				processData: false,
				contentType: false,
				success: function (response)
				{
					if(response[0]=='success')
					{
						alert('Document Successfully Saved.');

						$('#doc_detail').select2().val('').trigger('change');
						$('#attached_document').val('');

						$('#document_data').empty().append(response[1]);

						$(':input[type="submit"]').prop('disabled', false);
						$('.blockUI').hide();
					}
					else
					{
						alert('Document Detail Not Successfully Saved.');
						$(':input[type="submit"]').prop('disabled', false);
						$('.blockUI').hide();
					}
				},
				error:function()
				{
					alert('Document Detail Not Successfully Updated.');
					$(':input[type="submit"]').prop('disabled', false);
					$('.blockUI').hide();
				}
			});
		}
		else
		{
			return false;
		}
	});

	$('#visitor_form').validate({
		rules: {
			visitor_visit_date: {required: true,},
			visitor_visit_time: {required: true,},
			visitor_visit_at: {required: true,},
			visitor_attended_by: {required: true,},
			visitor_client_name1: {required: true,},

		},
		messages:{
			visitor_visit_date: {required: "Please Select Date.",},
			visitor_visit_time: {required: "Please Select Time"},
			visitor_visit_at: {required: "Please Select Visit At."},
			visitor_attended_by: {required: "Please Select Attend By."},
			visitor_client_name1: {required: "Please Enter Name"},

		},
	});

	$('#visitor_form').on('submit', function(e){
		e.preventDefault();
		var formData = new FormData(this);
		var form = this;

		if($("#visitor_form").valid())
		{
			$(':input[type="submit"]').prop('disabled', true);

			$('.blockUI').show();
			$.ajax({
				type: 'post',
				url: '{{$visitor_form_url}}',
				data: formData,
				dataType: "json",
				processData: false,
				contentType: false,
				success: function (response)
				{
					if(response[0]=='success')
					{
						alert('Visitor Data Successfully Saved.');

						$('select').select2().val('').trigger('change');
						$('.clear_data').val('');
						$('#visiting_form_model').modal('hide');
						$('#visiting_detail_data').empty().append(response[1]);

						$(':input[type="submit"]').prop('disabled', false);
						$('.blockUI').hide();
					}
					else
					{
						alert('Visitor Data Not Successfully Saved.');
						$(':input[type="submit"]').prop('disabled', false);
						$('#visiting_form_model').modal('hide');
						$('.blockUI').hide();
					}
				},
				error:function()
				{
					alert('Visitor Data Not Successfully Updated.');
					$(':input[type="submit"]').prop('disabled', false);
					$('#visiting_form_model').modal('hide');
					$('.blockUI').hide();
				}
			});
		}
		else
		{
			return false;
		}
	});



	$('body').on('change','#followup_way_id',function (){
		var follow_up_name = $('#followup_way_id option:selected').text().toUpperCase();
		if($.trim(follow_up_name)=='CALL')
		{
			$('.call_type_hide').show();
			$('.call_receive_remark_hide').hide();
			$('.follow_up_email_disp').hide();
			$('.follow_up_mobile_disp').show();
			$('#follow_up_detail_mobile').attr('required',true);
			$('#follow_up_detail_email').select2().val('').trigger('change');
			$('#call_type').attr('required',true);
			$('#call_receive_remark').removeAttr('required',true);
		}
		else if($.trim(follow_up_name)=='EMAIL')
		{
			$('.follow_up_email_disp').show();
			$('#follow_up_detail_email').attr('required',true);
			$('#follow_up_detail_mobile').select2().val('').trigger('change');
			$('.call_type_hide').hide();
			$('.call_receive_remark_hide').show();
			$('.follow_up_mobile_disp').hide();
			$('#call_type').removeAttr('required',true);
			$('#call_receive_remark').attr('required',true);
			$('#day_followup_date').val('{{$minimum_email[0]->days}}');
			$("#day_followup_date").attr("max",'{{$minimum_email[0]->days}}');
			var dat = add_date(parseInt('{{$minimum_email[0]->days}}'));
			$('#next_followup_date').val(dat);
		}
		else if ($.trim(follow_up_name)=='CALL & EMAIL') {

			$('.call_type_hide').hide();
			$('.call_receive_remark_hide').show();
			$('#follow_up_detail_mobile').select2().val('').trigger('change');
			$('#follow_up_detail_email').select2().val('').trigger('change');
			$('.follow_up_email_disp').hide();
			$('.follow_up_mobile_disp').hide();
			$('#follow_up_detail_hide').hide();
			$('#follow_up_detail_email').removeAttr('required',true);
			$('#follow_up_detail_mobile').removeAttr('required',true);
			$('#call_type').removeAttr('required',true);
			$('#call_receive_remark').attr('required',true);

			$('#day_followup_date').val('{{$minimum_call_email[0]->days}}');
			$("#day_followup_date").attr("max",'{{$minimum_call_email[0]->days}}');
			var dat = add_date(parseInt('{{$minimum_call_email[0]->days}}'));
			$('#next_followup_date').val(dat);

		} else if ($.trim(follow_up_name)=='WHATSAPP') {
			$('.call_type_hide').hide();
			$('.call_receive_remark_hide').show();
			$('#follow_up_detail_mobile').select2().val('').trigger('change');
			$('#follow_up_detail_email').select2().val('').trigger('change');
			$('.follow_up_email_disp').hide();
			$('.follow_up_mobile_disp').hide();
			$('#follow_up_detail_hide').hide();
			$('#follow_up_detail_email').removeAttr('required',true);
			$('#follow_up_detail_mobile').removeAttr('required',true);
			$('#call_type').removeAttr('required',true);
			$('#call_receive_remark').attr('required',true);

			$('#day_followup_date').val('{{$minimum_day_whatsapp[0]->days}}');
			$("#day_followup_date").attr("max",'{{$minimum_day_whatsapp[0]->days}}');
			var dat = add_date(parseInt('{{$minimum_day_whatsapp[0]->days}}'));
			$('#next_followup_date').val(dat);
		} else
		{
			$('.call_type_hide').hide();
			$('.call_receive_remark_hide').show();
			$('#follow_up_detail_mobile').select2().val('').trigger('change');
			$('#follow_up_detail_email').select2().val('').trigger('change');
			$('.follow_up_email_disp').hide();
			$('.follow_up_mobile_disp').hide();
			$('#follow_up_detail_hide').hide();
			$('#follow_up_detail_email').removeAttr('required',true);
			$('#follow_up_detail_mobile').removeAttr('required',true);
			$('#call_type').removeAttr('required',true);
			$('#call_receive_remark').attr('required',true);
		}
		//$('#day_followup_date').val('');
		//$('#next_followup_date').val('');
	});

	$('body').on('change','#call_type',function (){
		var call_type = $(this).val().toUpperCase();
		if($.trim(call_type)=='CALL RECEIVE' || $.trim(call_type)=='CALL & EMAIL')
		{
			$('.call_receive_remark_hide').show();
			$('#call_receive_remark').attr('required',true);
			$('#next_followup_date').val('');
			$('#day_followup_date').val('');


		}
		else
		{
			$('#day_followup_date').val('{{$minimum_call[0]->days}}');
			$("#day_followup_date").attr("max",'{{$minimum_call[0]->days}}');
			var dt = add_date(parseInt('{{$minimum_call[0]->days}}'));
			$('#next_followup_date').val(dt);
			$('.call_receive_remark_hide').hide();
			$('#call_receive_remark').removeAttr('required',true);
		}
	});

	$('body').on('change','#day_followup_date',function (){
		var dt = add_date(parseInt($(this).val()));
		$('#next_followup_date').val(dt);
	});

	$('body').on('change',"#order_book_date",function(e){
		var dt = $(this).val();
		$('#order_day').val(Day_name(dt));
	});
	function Day_name(custom_date)
	{
		 var myDate=custom_date;
		 myDate=myDate.split("-");
		 var newDate=myDate[2]+"-"+myDate[1]+"-"+myDate[0];
		 var my_ddate=new Date(newDate).getTime();
		 var currentDate = new Date(newDate);
		 var day_name = currentDate.getDay();
		 var days = new Array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
		 return days[day_name];
	}

	//Follow Up Validation End
});
</script>