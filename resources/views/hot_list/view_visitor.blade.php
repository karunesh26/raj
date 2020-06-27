@extends('template.template')
@section('content')
<?php
error_reporting(0);
$back_link = URL::to($controller_name);
?>
<div class="blockUI" style=""></div>
<div class="blockUI blockOverlay" style="z-index: 1000; border: medium none; margin: 0px; padding: 0px; width: 100%; height: 100%; top: 0px; left: 0px; background-color: rgb(0, 0, 0); opacity: 0.6; cursor: wait; position: fixed;"></div>
<div class="blockUI blockMsg blockPage" style="z-index: 1011; position: fixed; padding: 15px; margin: 0px; width: 30%; top: 40%; left: 35%; text-align: center; color: rgb(255, 255, 255); border: medium none;  cursor: wait; opacity: 0.5;">
	<img alt="loading.." src="{{ URL::asset('external/gif/6.gif')}}">
</div>
 <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1> <?php echo $msgName;?> Details</h1>
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('Dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><?php echo $msgName;?></li>
      </ol>
    </section>
    <br />
    <section class="content">
			<div class="row">
				<div class="box box-primary">
					<div class="box-header with-border">
					  <h3 class="box-title">Visitor Detail</h3>
					</div>
					 <div class="box-body">
					<div class="form-group col-sm-12">
						<div class="col-sm-4">
							<label class="control-label" >Quot. No.<span class="required">*</span></label>
							<input type="text" name="visitor_quot_no" id="visitor_quot_no" readonly value="{{ $result[0]->quotation_no }}" class="form-control input-sm form-control" />
						</div>
						<div class="col-sm-4">
							<label class="control-label" >Quot. Date<span class="required">*</span></label>
							<input  type="text" id="visitor_quot_date" name="visitor_quot_date" readonly value="{{ date('d-m-Y',strtotime($result[0]->quot_date)) }}" class="form-control input-sm form-control" />
						</div>
						<div class="col-sm-4">
							<label class="control-label" >Form No.<span class="required">*</span></label>
							<input type="text" id="visitor_form_no" name="visitor_form_no" readonly value="{{ $result[0]->form_no }}"  class="form-control input-sm" />
						</div>
					</div>
					<div class="form-group col-sm-12">
						<div class="col-sm-4">
							<label class="control-label" >Visit Date<span class="required">*</span></label>
							<input type="text" name="visitor_visit_date" id="visitor_visit_date" readonly value="{{ date('d-m-Y') }}" class="form-control input-sm" />
						</div>
						<div class="col-sm-4">
							<label class="control-label" >Visit Time<span class="required">*</span></label>
							<input type="text" readonly name="visitor_visit_time"  id="visitor_visit_time" value="{{ $result[0]->visit_time }}" class="timepicker form-control input-sm" />
						</div>
						<div class="col-sm-4">
							<label class="control-label" >Visit In<span class="required">*</span></label>
							<select disabled class="select2 " style="width:100%" id="visitor_visit_at" name="visitor_visit_at">
								<option value="">Select</option>
								@foreach($visit_at as $key=>$val)
									<option <?php echo ($result[0]->visit_in == $val->id) ? 'selected' : ''; ?> value="{{ $val->id }}">{{ $val->office_name }}</option>
								@endforeach
							</select>	
						</div>
					</div>
					
					<div class="form-group col-sm-12">
						<div class="col-sm-4">
							<label class="control-label" >Attend By<span class="required">*</span></label>
							<select disabled name="visitor_attended_by" id="visitor_attended_by" class="select2 clear_data_select" style="width:100%">
								<option  value=''>Select</option>
								@foreach($employee as $k=> $v)
									@if ($v->emp_id == $result[0]->attend_by)
										<option value="{{ $v->emp_id}}" selected="selected" > {{ $v->name}}</option>
									@else
										<option value="{{ $v->emp_id}}" > {{ $v->name}}</option> 
									@endif
								@endforeach
							</select>
						</div>
					</div>
					
					<center><h3><u>Client Detail</u></h3></center>
					<div class="form-group col-sm-12">
						<div class="col-sm-4">
							<label class="control-label" >Client Name-1<span class="required">*</span></label>
							<input type="text" readonly value="{{ $result[0]->client_name1 }}" name="visitor_client_name1" id="visitor_client_name1" class="form-control input-sm clear_data" />
						</div>
						<div class="col-sm-4">
							<label class="control-label" >Mobile-1<span class="required">*</span></label>
							<input type="text" readonly value="{{ $result[0]->client_mobile1 }}" name="visitor_client_mobile1" maxlength="10" id="visitor_client_mobile1" class="form-control input-sm clear_data" />
						</div>
						<div class="col-sm-4">
							<label class="control-label" >Email-1<span class="required">*</span></label>
							<input type="text" readonly value="{{ $result[0]->client_email1 }}" name="visitor_client_email1" id="visitor_client_email1" class="form-control input-sm clear_data" />
						</div>
					</div>
					<div class="form-group col-sm-12">
						<div class="col-sm-4">
							<label class="control-label" >Client Name-2</label>
							<input type="text" readonly value="{{ $result[0]->client_name2 }}" name="visitor_client_name2" id="visitor_client_name2" class="form-control input-sm clear_data" />
						</div>
						<div class="col-sm-4">
							<label class="control-label" >Mobile-2</label>
							<input type="text" readonly value="{{ $result[0]->client_mobile2 }}" name="visitor_client_mobile2" maxlength="10" id="visitor_client_mobile2" class="form-control input-sm clear_data" />
						</div>
						<div class="col-sm-4">
							<label class="control-label" >Email-2</label>
							<input type="text" readonly value="{{ $result[0]->client_email2 }}" name="visitor_client_email2" id="visitor_client_email2" class="form-control input-sm clear_data" />
						</div>
					</div>
					<div class="form-group col-sm-12">
						<div class="col-sm-4">
							<label class="control-label" >Client Name-3</label>
							<input type="text" readonly value="{{ $result[0]->client_name3 }}" name="visitor_client_name3" id="visitor_client_name3" class="form-control input-sm clear_data" />
						</div>
						<div class="col-sm-4">
							<label class="control-label" >Mobile-3</label>
							<input type="text" readonly value="{{ $result[0]->client_mobile3 }}" name="visitor_client_mobile3" maxlength="10" id="visitor_client_mobile3" class="form-control input-sm clear_data" />
						</div>
						<div class="col-sm-4">
							<label class="control-label" >Email-3</label>
							<input type="text" readonly value="{{ $result[0]->client_email3 }}" name="visitor_client_email3" id="visitor_client_email3" class="form-control input-sm clear_data" />
						</div>
					</div>
					<div class="form-group col-sm-12">
						<div class="col-sm-4">
							<label class="control-label" >Company Name</label>
							<input type="text" readonly value="{{ $result[0]->company_name }}" name="visitor_company_name" id="visitor_company_name" class="form-control input-sm clear_data" />
						</div>
						<div class="col-sm-4">
							<label class="control-label" >Landline No.</label>
							<input type="text" readonly value="{{ $result[0]->landline_no }}" name="visitor_landline_no" id="visitor_landline_no" class="form-control input-sm clear_data" />
						</div>
					</div>
					<div class="form-group col-sm-12">
						<div class="col-sm-6">
							<label class="control-label" >Address</label>
							<textarea name="visitor_address" readonly  id="visitor_address" class="form-control input-sm clear_data" >{{ $result[0]->address }}</textarea>
						</div>
						<div class="col-sm-6">
							<label class="control-label" >Office Address</label>
							<textarea name="visitor_office_address" readonly  id="visitor_office_address"  class="form-control input-sm clear_data" >{{ $result[0]->office_address }}</textarea>
						</div>
					</div>
					<div class="form-group col-sm-12">
						<div class="col-sm-4">
							<label class="control-label" >Country</label>
							<select disabled name="visitor_country" id="visitor_country" class="select2 clear_data_select" style="width:100%">
								<option  value="">Select</option>
								@foreach($country as $k=> $v)
									<option <?php echo ($result[0]->country == $v->country_id) ? 'selected' : ''; ?> value="{{ $v->country_id}}" > {{ $v->country_name}}</option>
								@endforeach
							</select>
						</div>
						<div class="col-sm-4">
							<label class="control-label" >State</label>
							<select disabled name="visitor_state" id="visitor_state" class="select2 clear_data_select" style="width:100%">
								<option  value="">Select</option>
								@foreach($state as $k=> $v)
									<option <?php echo ($result[0]->state == $v->state_id) ? 'selected' : ''; ?> value="{{ $v->state_id}}" > {{ $v->state_name}}</option>
								@endforeach
							</select>
						</div>
						<div class="col-sm-4">
							<label class="control-label" >City</label>
							<select disabled name="visitor_city" id="visitor_city" class="select2 clear_data_select" style="width:100%">
								<option  value="">Select</option>
								@foreach($city as $k=> $v)
									<option <?php echo ($result[0]->city == $v->city_id) ? 'selected' : ''; ?> value="{{ $v->city_id}}" > {{ $v->city_name}}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group col-sm-12">
						<div class="col-sm-4">
							<label class="control-label" >Land</label>
							<input type="text" readonly value="<?php echo $result[0]->land; ?>" name="visitor_land" id="visitor_land" class="form-control input-sm clear_data" />
						</div>
						<div class="col-sm-4">
							<label class="control-label" >Site Status</label>
							<select name="visitor_site_status" disabled id="visitor_site_status" class="select2 clear_data_select" style="width:100%">
								<option  value=''>Select</option>
								@foreach($site_status_master as $k=> $v)
									<option <?php echo ($result[0]->site_status == $v->site_status_id) ? 'selected' : ''; ?> value="{{ $v->site_status_id}}" > {{ $v->site_status_name }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-sm-4">
							<label class="control-label" >Power</label>
							<select disabled name="visitor_power_supply" id="visitor_power_supply" class="select2 clear_data_select" style="width:100%">
								<option  value=''>Select</option>
								@foreach($power_supply_master as $k=> $v)
									<option <?php echo ($result[0]->power == $v->power_supply_id) ? 'selected' : ''; ?> value="{{ $v->power_supply_id}}" > {{ $v->power_supply_name }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group col-sm-12">
						<div class="col-sm-4">
							<label class="control-label" >Water Source</label>
							<select disabled name="visitor_water_source" id="visitor_water_source" class="select2 clear_data_select" style="width:100%">
								<option  value=''>Select</option>
								@foreach($raw_water_master as $k=>$v)
									<option <?php echo ($result[0]->water_source == $v->raw_water_id) ? 'selected' : ''; ?> value="{{ $v->raw_water_id }}" > {{ $v->raw_water_name }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-sm-4">
							<label class="control-label" >Water Report</label>
							<select disabled name="visitor_water_report" id="visitor_water_report" class="select2 clear_data_select" style="width:100%">
								<option  value=''>Select</option>
								@foreach($water_report_master as $k=>$v)
									<option <?php echo ($result[0]->water_report == $v->water_report_id) ? 'selected' : ''; ?> value="{{ $v->water_report_id }}" > {{ $v->water_report_name }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group col-sm-12">
						<div class="col-sm-4">
							<label class="control-label" >Project Value</label>
							<input type="text" readonly value="<?php echo $result[0]->project_value; ?>" name="visitor_project_value" id="visitor_project_value" class="form-control input-sm clear_data" />
						</div>
						<div class="col-sm-4">
							<label class="control-label" >Payment Mode</label>
							<select disabled  name="visitor_payment_mode" id="visitor_payment_mode" class="select2 clear_data_select" style="width:100%">
								<option  value=''>Select</option>
								@foreach($payment_mode_master as $k=>$v)
									<option <?php echo ($result[0]->payment_mode == $v->payment_mode_id) ? 'selected' : ''; ?> value="{{ $v->payment_mode_id }}" > {{ $v->payment_mode_name }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-sm-4">
							<label class="control-label" >Quotation Status</label>
							<select disabled name="visitor_quatation_status" id="visitor_quatation_status" class="select2 clear_data_select" style="width:100%">
								<option  value=''>Select</option>
								@foreach($quatation_status_master as $k=>$v)
									<option <?php echo ($result[0]->quatation_status == $v->quatation_status_id ) ? 'selected' : ''; ?> value="{{ $v->quatation_status_id }}" > {{ $v->quatation_status_name }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<center><h3><u>Plant Detail</u></h3></center>
					<div class="form-group col-sm-12">
						<div class="col-sm-6">
							<label class="control-label" >Inquiry For</label>
							<select disabled name="visitor_inquiry_for" id="visitor_inquiry_for" class="select2 clear_data_select" style="width:100%">
								<option value=''>Select</option>
								@foreach($product as $k=>$v)
									<option <?php echo ($result[0]->product_id == $v->product_id) ? 'selected' : ''; ?> value="{{ $v->product_id }}" > {{ $v->product_name }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-sm-6">
							<label class="control-label" >Inquiry Type</label>
							<select disabled name="visitor_inquiry_type" id="visitor_inquiry_type" class="select2 clear_data_select" style="width:100%">
								<option  value=''>Select</option>
								@foreach($category as $k=>$v)
									<option <?php echo ($result[0]->inq_type == $v->category_id) ? 'selected' : ''; ?> value="{{ $v->category_id }}" > {{ $v->category_name }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="form-group col-sm-12">
						<div class="col-sm-6">
							<label class="control-label" >Product Detail</label>
							<select disabled name="visitor_product_detail" id="visitor_product_detail" class="select2 clear_data_select" style="width:100%">
								<option  value=''>Select</option>
								@foreach($quatation_product as $k=>$v)
									<option <?php echo ($result[0]->product_detail == $v->p_id) ? 'selected' : ''; ?> value="{{ $v->p_id }}" > {{ $v->name }}</option>
								@endforeach
							</select>
						</div>
					</div>
					
				
					<center><h3><u>Office Use Only</u></h3></center>
					<div class="form-group col-sm-12">
						<div class="col-sm-6">
							<label class="control-label">Client Category</label>
							<select disabled name="visitor_client_category" id="visitor_client_category" class="select2 clear_data_select" style="width:100%">
								<option value=''>Select</option>
								@foreach($client_category_master as $k=>$v)
									<option <?php echo ($result[0]->client_category == $v->client_category_id) ? 'selected' : ''; ?> value="{{ $v->client_category_id }}" > {{ $v->client_category_name }}</option>
								@endforeach
							</select>
						</div>
						<div class="col-sm-6">
							<label class="control-label" >Day for Next Follow-Up</label>
							<input type="text" readonly  value="{{ $result[0]->next_follow_up }}" name="visitor_follow_up" id="visitor_follow_up" class="form-control input-sm numberonly clear_data" />
						</div>
					</div>
					<div class="form-group col-sm-12">
						<div class="col-sm-6">
							<label class="control-label" >Remark</label>
							<textarea readonly name="visitor_remark" id="visitor_remark" class="form-control input-sm clear_data" >{{ $result[0]->remark }}</textarea>
						</div>
					</div>
					</div>
					</div>
				</div>
    </section>

@endsection