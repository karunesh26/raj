@extends('template.template')
@section('content')
<?php
error_reporting(0);
$url = $controller_name.'@update_quatation';
$btn= "Revise";
$get_product_rate = URL::action($controller_name.'@get_rate');	
$back_link = URL::to($controller_name);
?>
 <!-- Content Header (Page header) -->
    <section class="content-header">
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('Dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ URL::to($controller_name) }}"></i>{{ $msgName }}</a></li>
        <li class="active">Revise Quatation View</li>
      </ol>
    </section>
    
    <section class="content">
	  <br />
      <div class="row">
       <div class="box box-primary">
      	
            <div class="box-header with-border">
              <h3 class="box-title"><?php echo $btn." ".$msgName;?></h3>
            </div>
            
              {!! Form::open(array('action' => $url, 'method' => 'post' , 'files' => true ,'id'=>"frm",'name'=>"frm",'class'=>"form"))!!}
				 
         		  {{ csrf_field() }}
             	 <div class="box-body">
                 
                 	
                   {!! Form::hidden('quatation_id',$result[0]->quatation_id, array('id'=>"quatation_id")) !!}
                   
                 
                   <div class="form-group col-sm-12">
                            <div class="form-group col-sm-6">
                          
                            {!! Form::label('Revise Quatation No') !!} 
                           
                                 {!! Form::text('r_q_no',$result[0]->revise_quatation_no, array('class' => 'form-control ' ,'id'=>"r_q_no",'required' => 'required','readonly'=>'readonly')) !!}
                              
                           
                            </div>
                           
                       		<div class="form-group col-sm-6">
                          
                            {!! Form::label('Revise Quatation Date') !!} 
                           
                            {!! Form::text('r_q_date',date('d-m-Y',strtotime($result[0]->revise_date)), array('class' => 'form-control ' ,'id'=>"r_q_date",'required' => 'required','readonly'=>'readonly')) !!}
                              
                           
                            </div>
                        
                         
                        
                    
                    </div>
                    <div class="form-group col-sm-12">
						<div class="form-group col-sm-6">
							{!! Form::label('Quatation No') !!} 
					   
							{!! Form::text('quatation_no',$result[0]->quatation_no, array('class' => 'form-control ' ,'id'=>"quatation_no",'required' => 'required','readonly'=>'readonly')) !!}
						</div>
						<div class="form-group col-sm-6">
						{!! Form::label('Quatation Date') !!} 
					   
						{!! Form::text('quatation_date',date('d-m-Y', strtotime($result[0]->quatation_date)), array('class' => 'form-control ' ,'id'=>"quatation_date",'required' => 'required','readonly'=>'readonly')) !!}
						</div>
			      </div>
                
              	  <div class="form-group col-sm-12">
					<div class="form-group col-sm-6">
					{!! Form::label('Inquiry No') !!} 
				   
						 {!! Form::text('inquiry_no',$result[0]->inquiry_no, array('class' => 'form-control ' ,'id'=>"inquiry_no",'required' => 'required','readonly'=>'readonly')) !!}
					</div>
					<div class="form-group col-sm-6">
					{!! Form::label('Inquiry Date') !!} 
					{!! Form::text('inquiry_date',date('d-m-Y', strtotime($result[0]->inquiry_date)), array('class' => 'form-control ' ,'id'=>"inquiry_date",'required' => 'required','readonly'=>'readonly')) !!}
					</div>
                  </div>
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
											$quatation_product_id_arr = explode(',',$result[0]->quatation_product_id);
											$rate_arr = explode(',',$result[0]->rate);
											$qty_arr = explode(',',$result[0]->qty);
											$amount_arr = explode(',',$result[0]->amount);
										?>
										<?php
											for($i=0 ; $i < count($quatation_product_id_arr) ; $i++)
											{
										?>
											<tr class="pending-user">
											  <td>{{ $i+1 }}</td>
											  <td> 
												<select style="width:100%" id="quatation_product_id" name="quatation_product_id[]"  class="form-control col-md-2 quatation_product_id select2" tabindex="-1" aria-hidden="true" disabled>
													<option value="">Select</option>
													@foreach($quatation_product as $value)
														<option <?php echo ($value->p_id==$quatation_product_id_arr[$i])?'selected':'';?> value="{{ $value->p_id }}">{{ $value->name }}</option>
													@endforeach
												</select>
												<label class=""></label>
											  </td>
											  <td>
												{!! Form::text('rate[]',$rate_arr[$i],array('class' => 'form-control rate' ,'id'=>"rate",'readonly'=>'readonly')) !!}
												<label class=""></label>
											  </td>
											  <td>
												{!! Form::text('qty[]',$qty_arr[$i], array('class' => 'form-control qty' ,'id'=>"qty",'numberonly'=>'numberonly','readonly'=>'readonly')) !!}
												<label class=""></label>
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
											<td>{!! Form::text('gross_amount',number_format((float)$result[0]->gross_amount,2,'.',''), array('class' => 'form-control' ,'id'=>"gross_amount",'required' => 'required','readonly'=>'readonly')) !!}</td>
										</tr>
										
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td><b>CGST @ 9%</b></td>
											<td>{!! Form::text('cgst_amount',number_format((float)$result[0]->cgst_amount,2,'.',''), array('class' => 'form-control' ,'id'=>"cgst_amount",'required' => 'required','readonly'=>'readonly')) !!}</td>
										</tr>
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td><b>SGST @ 9%</b></td>
											<td>{!! Form::text('sgst_amount',number_format((float)$result[0]->sgst_amount,2,'.',''), array('class' => 'form-control' ,'id'=>"sgst_amount",'required' => 'required','readonly'=>'readonly')) !!}</td>
										</tr>
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td><b>Total Amount</b></td>
											<td>{!! Form::text('total_amount',number_format((float)$result[0]->total_amount,2,'.',''), array('class' => 'form-control' ,'id'=>"total_amount",'required' => 'required','readonly'=>'readonly')) !!}</td>
										</tr>
									</tbody>
								 </table>
                               </div>
                     
                    
                </div>
               {!!Form::close()!!}
             
		</div>
       </div>
     </section>

<script type="text/javascript">
jQuery(document).ready(function($){
	function get_price()// new function date 13/feb/14
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
		$('#gross_amount').val(gross_amount.toFixed(2));
		
		var cgst_amount=parseFloat(gross_amount) / 9;
		var sgst_amount=parseFloat(gross_amount) / 9;
		
		if(isNaN(cgst_amount))
		{
			cgst_amount=0;
		}
		$('#cgst_amount').val(cgst_amount.toFixed(2));
		if(isNaN(sgst_amount))
		{
			cgst_amount=0;
		}
		$('#sgst_amount').val(sgst_amount.toFixed(2));
		var total_amount = parseFloat(gross_amount) + parseFloat(cgst_amount) + parseFloat(sgst_amount);
		if(isNaN(total_amount))
		{
			total_amount=0;
		}
		$('#total_amount').val(total_amount.toFixed(2));
	}
	
	var id= $("#id").val();
	$('#frm').validate({
		rules:{
		},
		messages:{
		},
		submitHandler: function(form){
			if(check_validation())
			{
				$(':input[type="submit"]').prop('disabled', true);
				get_price();
				form.submit();
			}
		},
		errorPlacement: function(error, element) {
			error.appendTo(element.parent("div"));
		},
	});
	
	function check_validation()
	{
		status=0;			
		$('.quatation_product_id').each(function (){
			if($(this).val() == ''){
				var str = 'Please Select Product';
				$(this).closest("td").find("label").html(str.fontcolor("red"));
				status=1;
			}else
			{
				$(this).closest("td").find("label").html('');
			}
		});
		$('.qty').each(function (){
			if($(this).val() == ''){
				var str = 'Please Enter Qty';
				$(this).closest("td").find("label").html(str.fontcolor("red"));
				status=1;
			}else
			{
				$(this).closest("td").find("label").html('');
			}
		});
		if(status==0){
			return true;
		}
		else{
			return false;
		}
	}
	
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
		
		$(".select2").select2({
			placeholder: "Select"
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
			$(this).closest('.pending-user').fadeOut('fast', function(){$(this).closest('.pending-user').remove();	sr_change();get_price();check_validation();
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
	
	$('body').on('change',"#quatation_product_id",function(e){
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
		get_price();
	});
	
});
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
		{!! Form::text('rate[]','',array('class' => 'form-control rate' ,'id'=>"rate",'readonly'=>'readonly')) !!}
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