@extends('template.template')
@section('content')
<?php
//error_reporting(0);
$url = $controller_name.'@add_power_data';
$btn = 'Generate';
$back_link = URL::to($controller_name);

$get_hp_power_url = URL::action($controller_name.'@get_hp_power');
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
				 <input type="hidden" name="quot_id" id="quot_id" value="{{ $quot_id }}" />
				 <input type="hidden" name="type" id="type" value="{{ $type }}" />
				 <input type="hidden" name="inquiry_id" id="inquiry_id" value="{{ $result[0]->inquiry_id }}" />
				{{ csrf_field() }}
				<div class="box-body">
					<div class="form-group col-sm-12">
                     	<div class="form-group col-sm-12">
							{!! Form::label('Power calculation') !!}<span class="required"> *</span>
                            <table width="100%"  class="table table-bordered" >
									<tr>
										<th width="5%">Sr No</th>
										<th width="42%">Equipment Name<span class="required"> *</span></th>
										<th width="15%">Qty <span class="required"> *</span></th>
										<th width="15%">Power in HP <span class="required"> *</span></th>
										<th width="15%">Power in KW <span class="required"> *</span></th>
									</tr>
									<tbody  id="addrow">
									<?php
									$p_id_arr= explode(",",$result[0]->quatation_product_id);
									$rate_arr = explode(",",$result[0]->rate);
									$qty_arr = explode(",",$result[0]->qty);
									$amount_arr = explode(",",$result[0]->amount);

										$r=1;
										$amount_sum = 0;
										$power_sum = 0;
										$power_total = 0;
										$power_kw = 0;
										for($i=0; $i<count($p_id_arr);$i++)
										{
											$get_power = App\Models\Data_model::db_query("select * from `quatation_product` where `p_id`='".$p_id_arr[$i]."' ");
											
											if($get_power[0]->power_value != '')
											{
												$power = $get_power[0]->power_value;
												$power_sum = $get_power[0]->power_value;
											}
											else{
												$power = 0;
												$power_sum = 0;
											}
											
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
												{!! Form::text('qty[]',$qty_arr[$i], array('class' => 'form-control qty' ,'id'=>"qty",'numberonly'=>'numberonly')) !!}
												<label class=""></label>
											  </td>

											  <td>
											  	<input type="hidden" name="hp_power[]" id="hp_power" value="{{ $power }}" >
												<input type="text" readonly name="power_hp[]" id="power_hp" value="{{ $power }}" class="form-control" >
												<label class=""></label>
											  </td>
											  @php
													$power_k = floatval(0.7457)*floatval($power);
											  @endphp
											   <td>
												<input readonly type="text" name="power_kw[]" id="power_kw" value="{{ $power_k }}" class="form-control" />
												<label class=""></label>
											  </td>

										</tr>
											<?php
											$power_total += $power_sum;
											$power_kw += $power_k;
											$r++;
										}
										?>

										<tr>
											<td></td>
											<td><b><p class="text-right">Total :</p></b></td>
											<td><input type="text" readonly class="form-control" name="qty_total" id="qty_total" value="{{ array_sum($qty_arr) }}" /></td>
											<td><input type="text" readonly class="form-control" name="power_total_hp" id="power_total_hp" value="{{ $power_total }}" ></td>
											<td><input type="text" readonly class="form-control" name="power_total_kw" id="power_total_kw" value="{{ $power_kw }}" ></td>
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

	function get_price() // new function date 13/feb/14
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
		$('#qty_total').val(total_qty);

		var power_hp =$("input[id=power_hp]").map(function(){return $(this).val();}).get().join(",");
		var strpower = power_hp;
		arr = strpower.split(',');
		var gross_power=0;
		for(i=0; i < arr.length; i++)
		{
			if(arr[i]!='')
			{
				gross_power += parseFloat(arr[i]);
			}
		}
		$('#power_total_hp').val(gross_power.toFixed(2));

		var power_kw =$("input[id=power_kw]").map(function(){return $(this).val();}).get().join(",");
		var strpower_kw = power_kw;
		arr = strpower_kw.split(',');
		var gross_power_kw=0;
		for(i=0; i < arr.length; i++)
		{
			if(arr[i]!='')
			{
				gross_power_kw += parseFloat(arr[i]);
			}
		}
		$('#power_total_kw').val(gross_power_kw.toFixed(2));
	}


	$('body').on('change',"#qty",function(e){
		var qty = $(this).val();
		var power = $(this).closest('tr').find('#hp_power').val();

		if(qty=='')
		{
			qty=0;
		}
		if(power=='')
		{
			power=0;
		}
		var hp = parseFloat(qty) * parseFloat(power);
		$(this).closest('tr').find('#power_hp').val(hp.toFixed(2));

		var kw = parseFloat(0.7457)*parseFloat(hp);
		$(this).closest('tr').find('#power_kw').val(kw.toFixed(3));
		get_price();
	});

	$('body').on('change','#quatation_product_id',function(){
		var product_id = $(this).val();
		var th = $(this);

		$.ajax({
			type: "POST",
			url: "<?php echo $get_hp_power_url; ?>",
			data: { "_token": "{{ csrf_token() }}",product_id:product_id},
			success:function(res)
			{
				th.closest('tr').find('#hp_power').val(res);
				th.closest('tr').find('#power_hp').val(res);
				th.closest('tr').find('#qty').val('');
				th.closest('tr').find('#qty').focus();
			}
		});

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
@endsection