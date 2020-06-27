@extends('template.template')
@section('content')
<?php
error_reporting(0);
$url = $controller_name.'@add_invoice';
$get_product_rate = URL::action($controller_name.'@get_rate');
$btn = 'Generate';
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
				 <input type="hidden" name="order_id" id="order_id" value="{{ $order_id }}" />
				 <input type="hidden" name="type" id="type" value="{{ $type }}" />
				 <input type="hidden" name="inquiry_id" id="inquiry_id" value="{{ $result[0]->inquiry_id }}" />

				 <input type="hidden" name="country_id" id="country_id" value="{{$result[0]->country_id}}" />
				{{ csrf_field() }}
				<div class="box-body">
					<div class="form-group col-sm-12">
						<div class="form-group col-sm-3">
						{!! Form::label('Customer Name') !!} <span class="required"> *</span>
							 {!! Form::text('customer_name',$result[0]->name, array('class' => 'form-control ' ,'id'=>"customer_name",'required' => 'required')) !!}
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
										<th></th>
									</tr>
									<tbody id="addrow">
									<?php
									$p_id_arr= explode(",",$result[0]->quatation_product_id);
									$rate_arr = explode(",",$result[0]->rate);
									$qty_arr = explode(",",$result[0]->qty);
									$amount_arr = explode(",",$result[0]->amount);
										$r=1;
										$amount_sum = 0;
										for($i=0; $i<count($p_id_arr);$i++)
										{
											$amount_sum += $amount_arr[$i];
											?>
										<tr class="pending-user">
											  <td>{{ $r }}</td>
											  <td>
												<select style="width:100%" id="quatation_product_id" name="quatation_product_id[]" class="select2 quatation_product_id select2">
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
										?>
									<?php
										if($result[0]->discount != 0)
										{
									?>
										<tr>
											<td colspan="3" ></td>
											<td align="right"><b>Total</b></td>
											<td align="center">
												<div class="input-group">
													<span id="cur_change" class="input-group-addon"><i class="<?php echo ($result[0]->cur_type == 'inr') ? 'fa fa-inr' : 'fa fa-dollar'; ?>"></i></span>
													<input type="text" id="total" name="total" value="<?php echo number_format((float)$result[0]->total,2,'.',''); ?>" readonly class="form-control">
												</div>
											</td>
										</tr>
										<tr>
											<td colspan="3" ></td>
											<td align="right"><b>Discount</b></td>
											<td align="center">
												<div class="input-group">
													<span id="cur_change" class="input-group-addon"><i class="<?php echo ($result[0]->cur_type == 'inr') ? 'fa fa-inr' : 'fa fa-dollar'; ?>"></i></span>
													<input type="text" id="discount" name="discount" value="<?php echo number_format((float)$result[0]->discount,2,'.',''); ?>" class="form-control amountonly">
												</div>
											</td>
										</tr>
									<?php
										}
									?>
									<?php if($result[0]->project_zone != '6') { ?>
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td align="right"><b>Sub Total</b></td>
											<td>
												<div class="input-group">
													<span id="cur_change" class="input-group-addon"><i class="<?php echo ($result[0]->cur_type == 'inr') ? 'fa fa-inr' : 'fa fa-dollar'; ?>"></i></span>
													<input type="text" id="total_amount" name="total_amount" value="<?php echo number_format((float)$result[0]->total_amount,2,'.',''); ?>" readonly class="form-control">
												</div>
											</td>
										</tr>
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td align="right"><b>GST 18%</b></td>
											<td>
												<div class="input-group">
												<?php
													$gst = ($result[0]->total_amount / 100) * 18;
													$total = $gst+$result[0]->total_amount;
												?>
													<span id="cur_change" class="input-group-addon"><i class="<?php echo ($result[0]->cur_type == 'inr') ? 'fa fa-inr' : 'fa fa-dollar'; ?>"></i></span>
													<input type="text" id="gst_amount" name="gst_amount" value="<?php echo number_format((float)$gst,2,'.',''); ?>" readonly class="form-control">
												</div>
											</td>
										</tr>
									<?php } else {  ?>
										<?php $total = $result[0]->total_amount; ?>
									<?php } ?>
										<tr>
											<td></td>
											<td></td>
											<td></td>
											<td align="right"><b>Total Amount</b></td>
											<td>
											<div class="input-group">
												<span id="cur_change" class="input-group-addon"><i class="<?php echo ($result[0]->cur_type == 'inr') ? 'fa fa-inr' : 'fa fa-dollar'; ?>"></i></span>
												<input type="text" id="grand_total" name="grand_total" value="<?php echo number_format((float)$total,2,'.',''); ?>" readonly class="form-control">
											</div>
											</td>
										</tr>
									</tbody>
								 </table>
                               </div>
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
	var project_zone = "<?php echo $result[0]->project_zone;?>";

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
		$('#total').val(gross_amount.toFixed(2));
		var discount = $('#discount').val();
		if(isNaN(discount) || discount == '') {
			discount = 0;
		}
		var totalAmount = parseFloat(gross_amount) - parseFloat(discount);

		if (project_zone !== '6') {
			$('#total_amount').val(totalAmount.toFixed(2));
			var gst = ((totalAmount.toFixed(2)) / 100) * 18;
			$('#gst_amount').val(gst.toFixed(2));
			var total = parseFloat(totalAmount.toFixed(2)) + parseFloat(gst);
		} else {
			var total = parseFloat(totalAmount.toFixed(2));
		}
		$('#grand_total').val(total.toFixed(2));
	}

	$('body').on('keyup','#discount',function(e){
		get_price();
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
				th.closest('tr').find('#rate').val(res[0]);
				th.closest('tr').find('#qty').val('');
				th.closest('tr').find('#qty').focus();
				th.closest('tr').find('#amount').val('');
			}
		});
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

	$.validator.setDefaults({ ignore: ":hidden:not(.select2)" })
	var id= $("#id").val();
	$('#frm').validate({
		rules: {
			customer_name: {required: true,},
		},
		messages:{
			customer_name: {required:"Please Enter Customer Name"},
		},
	});
	$('#frm').on('submit', function(e){
			e.preventDefault();
			var formData = new FormData(this);
			var form = this;
			if($("#frm").valid() )
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