@extends('template.template')

@section('content')
<?php
error_reporting(0);
	$url = $controller_name.'@update';
	$btn= "Update";
	

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
                 
                 	
                   {!! Form::hidden('id',$result[0]->inquiry_id, array('id'=>"id")) !!}
                 
                 	
                      <div class="form-group col-sm-12">
                            <div class="form-group col-sm-6">
                          
                            {!! Form::label('Inquiry No') !!} 
                           
                                 {!! Form::text('inquiry_no',$result[0]->inquiry_no, array('class' => 'form-control ' ,'id'=>"inquiry_no",'required' => 'required','readonly'=>'readonly')) !!}
                              
                           
                            </div>
                            <div class="form-group col-sm-6">
                      
                        {!! Form::label('Inquiry For') !!} <span class="required">*</span>
                     	<select disabled="disabled" name="product_id" id="product_id" class="form-control">
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
                          
                        </div>
                        
                    <div class="form-group col-sm-12">
                     	
                        <div class="form-group col-sm-6">
                       	{!! Form::label('Inquiry Type') !!} <span class="required">*</span>
       					 <select disabled="disabled" name="category_id" id="category_id" class="form-control">
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
                        
                        	<div class="form-group col-sm-6" id="new_customer">
                        	{!! Form::label('Customer Name') !!} <span class="required">*</span>
                            {!! Form::text('name',$result[0]->name, array('class' => 'form-control ' ,'id'=>"name",'placeholder'=>'Enter Name','required' => 'required','readonly'=>'readonly')) !!}
                            {!! Form::hidden('customer_id',$result[0]->customer_id, array('class' => 'form-control ' ,'id'=>"customer_id")) !!}
                         </div>
                    </div>
                    
                 
                          
                         
                    
                       <div class="form-group col-sm-12">
                     
                           		
                           
                            
                            <div class="form-group col-sm-6">
                            {!! Form::label('Address') !!} <span class="required">*</span>
                            {!! Form::textarea('address',$result[0]->address, array('class' => 'form-control ' ,'id'=>"address",'placeholder'=>'Enter Address','required' => 'required','size'=>'20*3','readonly'=>'readonly')) !!}
                        
                            </div>
                             <div class="form-group col-sm-6">
                          
                            {!! Form::label('State') !!} <span class="required">*</span>
                             <select disabled="disabled" name="state_id" id="state_id" class="form-control">
                                    <option  value="">Select</option>
                                    
                                    @foreach($state as $k=> $v)
                                    	
                                         @if ($v->state_id == $result[0]->state_id)
                                 			    <option value="{{ $v->state_id}}" selected="selected" > {{ $v->state_name}}</option>
                                		 @else
                                         
                                      			<option value="{{ $v->state_id}}" > {{ $v->state_name}}</option> 
                                      @endif
                                    @endforeach
           					</select>
                       	 <label id="state_id-error" class="error" for="state_id"></label>
                            </div>
                        </div>
                        
                         <div class="form-group col-sm-12">
                           
                            <div class="form-group col-sm-6">
                            {!! Form::label('City') !!} <span class="required">*</span>
                             <select disabled="disabled" name="city_id" id="city_id" class="form-control">
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
                             <div class="form-group col-sm-6">
                          
                            {!! Form::label('Mobile') !!} <span class="required">*</span>
                             {!! Form::text('mobile',$result[0]->mobile, array('class' => 'form-control numberonly' ,'id'=>"mobile",'placeholder'=>'Enter Mobile No','required' => 'required','maxlength'=>'10','readonly'=>'readonly')) !!}
                            </div>
                        </div>
                        
                         <div class="form-group col-sm-12">
                           
                            <div class="form-group col-sm-6">
                            {!! Form::label('Office Address') !!} 
                          {!! Form::textarea('office_address',$result[0]->office_address, array('class' => 'form-control ' ,'id'=>"office_address",'placeholder'=>'Enter Office Address','size'=>'20*3','readonly'=>'readonly')) !!}
                            </div>
                             <div class="form-group col-sm-6">
                          
                            {!! Form::label('Landline No') !!}
                            {!! Form::text('landline',$result[0]->landline, array('class' => 'form-control numberonly' ,'id'=>"landline",'placeholder'=>'Enter Landline','readonly'=>'readonly')) !!}
                            </div>
                        </div>
                        
                          <div class="form-group col-sm-12">
                           
                            <div class="form-group col-sm-6">
                            {!! Form::label('Company') !!} 
                        	{!! Form::text('company',$result[0]->company, array('class' => 'form-control ' ,'id'=>"company",'placeholder'=>'Enter Company','readonly'=>'readonly')) !!}
                            </div>
                            <div class="form-group col-sm-6">
                          
                            {!! Form::label('Phone No') !!} 
                            {!! Form::text('phone_no',$result[0]->phone_no, array('class' => 'form-control numberonly' ,'id'=>"phone_no",'placeholder'=>'Enter Phone No','readonly'=>'readonly')) !!}
                            </div>
                        </div>
                        
                         <div class="form-group col-sm-12">
                            
                            <div class="form-group col-sm-6">
                            {!! Form::label('Email Address') !!} 
                        	{!! Form::email('email',$result[0]->email, array('class' => 'form-control ' ,'id'=>"email",'placeholder'=>'Enter Email','readonly'=>'readonly')) !!}
                            </div>
                              <div class="form-group col-sm-6">
                               {!! Form::label('Source') !!}
                               <select disabled="disabled" name="source_id" id="source_id" class="form-control">
                                        <option  value="">Select</option>
                                        
                                        @foreach($source as $k=> $v)
                                            
                                             @if ($v->source_id == $result[0]->source_id)
                                                    <option value="{{ $v->source_id}}" selected="selected" > {{ $v->source_name}}</option>
                                             @else
                                             
                                                    <option value="{{ $v->source_id}}" > {{ $v->source_name}}</option> 
                                          @endif
                                        @endforeach
                            </select>
                             <label id="source_id-error" class="error" for="source_id"></label> 
                            </div>
                        </div>
                        
                        
                          <div class="form-group col-sm-12">
                            
                          
                            <div class="form-group col-sm-6"> 
                            {!! Form::label('Remarks') !!} 
                          
                           
                          
                             {!! Form::textarea('remarks',$result[0]->remarks, array('class' => 'form-control ' ,'id'=>"remarks",'placeholder'=>'Enter Remarks','size'=>'20*3','readonly'=>'readonly')) !!}
                            </div>
                             <div class="form-group col-sm-6">
                          
                            {!! Form::label('Plant Detail') !!} 
                            {!! Form::text('plant_detail',$result[0]->plant_detail, array('class' => 'form-control ' ,'id'=>"plant_detail",'placeholder'=>'Enter Plant Detail')) !!}
                            </div>
                        </div>
                        
                      
                        
                          <div class="form-group col-sm-12">
                           
                            <div class="form-group col-sm-6">
                            {!! Form::label('Auth. Person') !!} 
                         	{!! Form::text('auth_person',$result[0]->auth_person, array('class' => 'form-control ' ,'id'=>"auth_person",'placeholder'=>'Enter Auth Person')) !!}
                            </div>
                             <div class="form-group col-sm-6">
                          
                            {!! Form::label('Client Category') !!} 
                          	 	 <select name="client_category_id" id="client_category_id" class="form-control">
                                    <option  value="">Select</option>
                                    
                                    @foreach($client_category as $k=> $v)
                                    	
                                         @if ($v->client_category_id == $result[0]->client_category_id)
                                 			    <option value="{{ $v->client_category_id}}" selected="selected" > {{ $v->client_category_name}}</option>
                                		 @else
                                         
                                      			<option value="{{ $v->client_category_id}}" > {{ $v->client_category_name}}</option> 
                                      @endif
                                    @endforeach
           				</select>
                         <label id="client_category_id-error" class="error" for="client_category_id"></label>
                            </div>
                        </div>
                        
                           <div class="form-group col-sm-12">
                           
                           <div class="form-group col-sm-6">
                          
                            {!! Form::label('Payment Mode') !!} 
                           	<bR />
                            {!! Form::select('payment_mode', array(''=>'Select','Loan/Subsidy' => 'Loan/Subsidy', 'Self Finance' => 'Self Finance'),$result[0]->payment_mode,array('class' => 'form-control'))!!}
                            
                            </div>
                              <div class="form-group col-sm-6">
                            {!! Form::label('Project Division') !!} 
                            <br />
                         	 {!! Form::select('project_division', array(''=>'Select','Domestic' => 'Domestic', 'Trader' => 'Trader','Project'=>'Project'),$result[0]->project_division,array('class' => 'form-control'))!!}
                            </div>
                        </div>
                        
                         <div class="form-group col-sm-12">
                           
                          
                             <div class="form-group col-sm-6">
                            {!! Form::label('Raw Water Source') !!}
                            {!! Form::select('raw_water_source', array(''=>'Select','Bore Well Water' => 'Bore Well Water', 'Natural Water' => 'Natural Water','Project'=>'Ay Other Soure'),$result[0]->raw_water_source,array('class' => 'form-control'))!!} 
                            </div>
                             <div class="form-group col-sm-6">
                            {!! Form::label('Project Planing State') !!} 
                        	{!! Form::select('project_planing_stage', array(''=>'Select','Fast/Immediate' => 'Fast/Immediate', '2 Months' => '2 Months','3 Months'=>'3 Months','6 Months'=>'6 Months'),$result[0]->project_planing_stage,array('class' => 'form-control'))!!} 
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
	
	
	$('.customer_type').change(function() {
        if (this.value == 'new') 
		{
           $("#new_customer").show();
		    $("#existing_customer").hide();
        }
        else if (this.value == 'existing') {
            $("#new_customer").hide();
		    $("#existing_customer").show();
        }
    });
	
	var id= $("#id").val();
	$('#frm').validate({
		rules: {
			product_id: {required: true,},
			category_id: {required: true,},
			customer_id: {required: true,},
			name: {required: true,},
			address: {required: true,},
			state_id: {required: true,},
			city_id: {required: true,},
			mobile: {required: true,},
		},
		messages:{
			product_id: {required:"Please Select Inquiry For"},
			category_id: {required:"Please Select Inquirt type "},
			customer_id: {required: "Please Select Customer"},
			name: {required: "Please Enter Name"},
			address: {required: "Please Enter Address"},
			state_id: {required:"Please Select State"},
			city_id: {required: "Please Select City"},
			mobile: {required: "Please Enter Mobile No"},
		},
	});
		
		
	
		
});
</script>
			
@endsection