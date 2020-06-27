@extends('template.template')
@section('content')

<?php
error_reporting(0);
$url = $controller_name.'@cancel_inquiry_update';
$btn= "Cancel Inquiry";
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
                               <select name="source_id" id="source_id" class="select2" <?php if($action == 'update'){echo "disabled";}?> style="width:100%" disabled="disabled">
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
									{{ Form::radio('insentive', 'no', true, ['class' => 'insentive','disabled'=>'disabled']) }}No 
									{{ Form::radio('insentive', 'yes','', ['class' => 'insentive','disabled'=>'disabled']) }}Yes
							  
								<?php
								}
								else
								{
								?>
									{{ Form::radio('insentive', 'no', '', ['class' => 'insentive','disabled'=>'disabled']) }}No 
									{{ Form::radio('insentive', 'yes',true, ['class' => 'insentive','disabled'=>'disabled']) }}Yes
								<?php
								}
								?>
                                    	
							</div>
                               
							<div class="form-group col-sm-3" <?php echo ($result[0]->insentive == 'yes')?'':'style="display:none"';?> id="attended_div">
								
								{!! Form::label('Attended By') !!} 
								<br />
								<select name="attended_by" id="attended_by" class="select2" disabled="disabled">
									<option  value="">Select</option>
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
       					 <label id="product_id-error" class="error" for="product_id"></label>
                        </div>
						<div class="form-group col-sm-3"></div>
						 <div class="form-group col-sm-3">
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
								<label id="category_id-error" class="error" for="category_id"></label>
                        </div>
					</div>
                         
						
						<div class="form-group col-sm-12">
                          	<?php
									 if($result[0]->customer_type == 'new')
									 {
								 ?>
                                 		  <div class="form-group col-sm-3" id="new_customer">
                           		 			{!! Form::label('Customer Name') !!} <span class="required">*</span>
                             			 	{!! Form::text('name',$result[0]->name, array('class' => 'form-control ' ,'id'=>"name",'placeholder'=>'Enter Name','required' => 'required','readonly'=>'readonly')) !!}
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
                                         <select name="customer_id" id="customer_id" class="select2" style="width:100%" disabled="disabled">
                                                
                                                
                                                @foreach($customer as $k=> $v)
                                                    
                                                     @if ($v->customer_id == $result[0]->customer_id)
                                                            <option value="{{ $v->customer_id}}" selected="selected" > {{ $v->name}}</option>
                                                    
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
								{!! Form::text('company',$result[0]->company, array('class' => 'form-control ' ,'id'=>"company",'placeholder'=>'Enter Company','readonly'=>'readonly')) !!}
                            </div>

                        </div>
						
                        <div class="form-group col-sm-12">
							<div class="form-group col-sm-4">
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
								<label id="country_id-error" class="error" for="country_id"></label>
                            </div>
							
							
							<div class="form-group col-sm-4 state_hide" <?php echo ($action=='update' && $country_zone_id != 0)?'style="display:none;"':'';?>>
								{!! Form::label('State') !!} <span class="required">*</span>
								<select name="state_id" id="state_id" class="select2" style="width:100%" disabled="disabled">
											<option  value="">Select</option>
											
											@foreach($state as $k=> $v)
												
												 @if ($v->state_id == $result[0]->state_id)
														<option value="{{ $v->state_id}}" selected="selected" > {{ $v->state_name}}</option>
												 @else
												 
														<option value="{{ $v->state_id}}" > {{ $v->state_name}}</option> 
											  @endif
											@endforeach
								</select>
								<label id="state_err"></label>
                            </div>
                            <div class="form-group col-sm-4 city_hide" <?php echo ($action=='update' && $country_zone_id!=0)?'style="display:none;"':'';?>>
								{!! Form::label('City') !!}
								<select name="city_id" id="city_id" class="select2" style="width:100%" disabled="disabled">
                                    <option  value="">Select</option>
                                    
                                    @foreach($city as $k=> $v)
                                    	
                                         @if ($v->city_id == $result[0]->city_id)
                                 			    <option value="{{ $v->city_id}}" selected="selected" > {{ $v->city_name}}</option>
                                		 @else
                                         
                                      			<option value="{{ $v->city_id}}" > {{ $v->city_name}}</option> 
                                      @endif
                                    @endforeach
								</select>
								<label id="city_id-error" class="error" for="city_id"></label>
                            </div>
                        </div>
                        
                        <div class="form-group col-sm-12">
						
							
                            <div class="form-group col-sm-4">
								{!! Form::label('Mobile 1') !!}
								{!! Form::text('mobile',$result[0]->mobile, array('class' => 'form-control numberonly' ,'id'=>"mobile",'placeholder'=>'Enter Mobile No 1','maxlength'=>'10','readonly'=>'readonly')) !!}
                            </div>
                            <div class="form-group col-sm-4">
                           
								{!! Form::label('Mobile 2') !!}
								{!! Form::text('mobile_2',$result[0]->mobile_2, array('class' => 'form-control numberonly' ,'id'=>"mobile_2",'placeholder'=>'Enter Mobile No 2','maxlength'=>'10','readonly'=>'readonly')) !!}
                            </div>
                            <div class="form-group col-sm-4">
								{!! Form::label('Mobile 3') !!}
								{!! Form::text('mobile_3',$result[0]->mobile_3, array('class' => 'form-control numberonly' ,'id'=>"mobile_3",'placeholder'=>'Enter Mobile No 3','maxlength'=>'10','readonly'=>'readonly')) !!}
                            </div>
						</div>
							
                        <div class="form-group col-sm-12">
                            <div class="form-group col-sm-4">
                            {!! Form::label('Landline No') !!}
                            {!! Form::text('landline',$result[0]->landline, array('class' => 'form-control numberonly' ,'id'=>"landline",'placeholder'=>'Enter Landline','readonly'=>'readonly')) !!}
                            </div>
							 <div class="form-group col-sm-4">
                            {!! Form::label('Email Address 1') !!} 
                        	{!! Form::email('email',$result[0]->email, array('class' => 'form-control ' ,'id'=>"email",'placeholder'=>'Enter Email','readonly'=>'readonly')) !!}
                            </div>
							<div class="form-group col-sm-4">
                            {!! Form::label('Email Address 2') !!} 
                        	{!! Form::email('email_2',$result[0]->email_2, array('class' => 'form-control ' ,'id'=>"email_2",'placeholder'=>'Enter Email 2','readonly'=>'readonly')) !!}
                            </div>
                        </div>
                        
						
                        
						
						
                       <div class="form-group col-sm-12">
                            <div class="form-group col-sm-3">
                            {!! Form::label('Office Address') !!} 
							{!! Form::textarea('office_address',$result[0]->office_address, array('class' => 'form-control ' ,'id'=>"office_address",'placeholder'=>'Enter Office Address','size'=>'20*3','readonly'=>'readonly')) !!}
                            </div>
                            <div class="form-group col-sm-3"></div>
                            <div class="form-group col-sm-3">
                            {!! Form::label('Address') !!} 
                            {!! Form::textarea('address',$result[0]->address, array('class' => 'form-control ' ,'id'=>"address",'placeholder'=>'Enter Address','size'=>'20*3','readonly'=>'readonly')) !!}
                            </div>
                        </div>
						
						
                         <div class="form-group col-sm-12">
                            <div class="form-group col-sm-3 plant_state_hide">
                         
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
								<label id="project_state-error" class="error" for="project_state"></label>
                            </div>
                            <div class="form-group col-sm-3"></div>
                            <div class="form-group col-sm-3 plant_city_hide">
								{!! Form::label('Plant Location City') !!} 
								<select name="project_city" id="project_city" class="select2" style="width:100%" disabled="disabled">
                                    <option  value="">Select</option>
                                    
                                    @foreach($city as $k=> $v)
                                    	
                                         @if ($v->city_id == $result[0]->project_city)
                                 			    <option value="{{ $v->city_id}}" selected="selected" > {{ $v->city_name}}</option>
                                		 @else
                                         
                                      			<option value="{{ $v->city_id}}" > {{ $v->city_name}}</option> 
                                      @endif
                                    @endforeach
								</select>
								<label id="project_city-error" class="error" for="project_city"></label>
                            </div>
                        </div>
                        
                         
                          
                          <div class="form-group col-sm-12">
							
							 <div class="form-group col-sm-12"> 
                            {!! Form::label('Remarks') !!} 
                             
                            {!! Form::textarea('remarks',$result[0]->remarks, array('class' => 'form-control ' ,'id'=>"remarks",'placeholder'=>'Enter Remarks','size'=>'20*3','readonly'=>'readonly')) !!}
                            </div>
                        </div>
					
					
					<div class="form-group col-sm-12">
                     	<div class="form-group col-sm-12">
							{!! Form::label('Commercial Proposal') !!}<span class="required"> *</span>
                            <table width="100%"  class="table table-bordered" >
									<tr>
										<th width="8%">Sr No</th>
										<th width="42%">Equipment Name<span class="required"> *</span></th>
										<th width="15%">Rate <span class="required"> *</span></th>
										<th width="15%">Qty <span class="required"> *</span></th>
										<th width="15%">Amount <span class="required"> *</span></th>
									</tr>
									<tbody  id="addrow">
									<?php
									$p_id_arr= explode(",",$result[0]->p_id);
									$rate_arr = explode(",",$result[0]->p_rate);
									$qty_arr = explode(",",$result[0]->p_qty);
									$amount_arr = explode(",",$result[0]->p_amount);
									for($i=0; $i<count($p_id_arr);$i++)
									{
										?>
											<tr class="pending-user">
											  <td>{{$i+1}}</td>
											  <td> 
												<select style="width:100%" id="quatation_product_id" name="quatation_product_id[]"  class="select2 col-md-2 quatation_product_id select2" tabindex="-1" aria-hidden="true"  disabled="disabled">
													<option value="">Select</option>
													@foreach($quatation_product as $value)
														<option <?php echo ($value->p_id==$p_id_arr[$i])?'selected':'';?> value="{{ $value->p_id }}">{{ $value->name }}</option>
													@endforeach
												</select>
												<label class="product_err"></label>
											  </td>
											  <td>
												{!! Form::text('rate[]',$rate_arr[$i],array('class' => 'form-control rate' ,'id'=>"rate",'readonly'=>'readonly')) !!}
												<label class="rate_err"></label>
											  </td>
											  <td>
												{!! Form::text('qty[]',$qty_arr[$i], array('class' => 'form-control qty' ,'id'=>"qty",'numberonly'=>'numberonly','readonly'=>'readonly')) !!}
												<label class="qty_err"></label>
											  </td>
											  
											  <td>
												{!! Form::text('amount[]',$amount_arr[$i],array('class' => 'form-control amount' ,'id'=>"amount",'readonly'=>'readonly')) !!}
												<label class=""></label>
											  </td>
											  
										</tr>
										<?php
										}
										?>	
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td>{!! Form::hidden('total_qty',0, array('class' => 'form-control' ,'id'=>"total_qty",'required' => 'required','readonly'=>'readonly')) !!} <b>Gross Amount</b></td>
											<td>{!! Form::text('gross_amount',number_format((float)array_sum($amount_arr),2,'.',''), array('class' => 'form-control' ,'id'=>"gross_amount",'required' => 'required','readonly'=>'readonly')) !!}</td>
										</tr>
									</tbody>
								 </table>
                               </div>
                     </div>
				
					<div class="form-group col-sm-12">
						<div class="form-group col-sm-12"> 
								{!! Form::label('Cancel Reason') !!} <span class="error">*</span>
								{!! Form::textarea('cancel_reason','', array('class' => 'form-control ' ,'id'=>"cancel_reason",'placeholder'=>'Enter Cancel Remarks','size'=>'20*3')) !!}
						</div>
					</div>
                </div>
                <div class="box-footer">
                	<div class="col-sm-6 col-sm-offset-5">
						<!--{{ Form::reset('Reset', ['class' => 'btn btn-danger']) }}-->
						{!! Form::submit($btn, ['class' => 'btn bg-olive']) !!}
                    </div>
                </div>
               {!!Form::close()!!}
		</div>
       </div>
     </section>
<script type="text/javascript">
jQuery(document).ready(function($){
	$.validator.setDefaults({ ignore: ":hidden:not(.select2)" });
	var id= $("#id").val();
	$('#frm').validate({
		rules: {
			cancel_reason: {required: true,},
			
		},
		messages:{
			cancel_reason: {required: "Please Enter Cancel Reason.",},
		},
	
	});
});
</script>
@endsection