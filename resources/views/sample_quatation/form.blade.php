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
$duplicate_url = URL::action($controller_name.'@duplicate');
$get_product_rate = URL::action($controller_name.'@get_rate');
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

                 	<div class="form-group col-sm-12">
                     	<div class="form-group col-sm-3">
                        {!! Form::label('Name') !!} <span class="required">*</span>
                        </div>
                        <div class="form-group col-sm-6">
							{!! Form::text('name',$result[0]->name, array('class' => 'form-control ' ,'id'=>"name",'placeholder'=>'Enter Name','required' => 'required')) !!}
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
										<th width="5%">Manage</th>
									</tr>
									<tbody  id="addrow">
									<?php

									$p_id_arr= explode(",",$result[0]->quatation_product_id);
									$rate_arr = explode(",",$result[0]->rate);
									$qty_arr = explode(",",$result[0]->qty);
									$amount_arr = explode(",",$result[0]->amount);

									for($i=0; $i<count($p_id_arr);$i++)
									{
										?>
											<tr class="pending-user">
											  <td>{{$i+1}}</td>
											  <td>
												<select style="width:100%" id="quatation_product_id" name="quatation_product_id[]"  class="select2 col-md-2 quatation_product_id select2">
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
										}
										?>
										<?php /* <tr>
											<td></td>
											<td></td>
											<td></td>
											<td>{!! Form::hidden('total_qty',0, array('class' => 'form-control' ,'id'=>"total_qty",'required' => 'required','readonly'=>'readonly')) !!} <b>Gross Amount</b></td>
											<td>{!! Form::text('gross_amount',number_format((float)array_sum($amount_arr),2,'.',''), array('class' => 'form-control' ,'id'=>"gross_amount",'required' => 'required','readonly'=>'readonly')) !!}</td>
											<td></td>
										</tr>

										<tr>
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
											<td><b>Total Amount</b></td>
											<td>{!! Form::text('total_amount',number_format((float)array_sum($amount_arr),2,'.',''), array('class' => 'form-control' ,'id'=>"total_amount",'required' => 'required','readonly'=>'readonly')) !!}</td>
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
						 <?php
							$new_specification_id_arr = explode('*****',$result[0]->specification_id);
							$new_spe_name_arr =explode('*****',$result[0]->spe_name);
							$new_spe_value_arr = explode('*****',$result[0]->spe_value);

							 foreach($specification as $k=> $v)
							 {
								 $is_specification=0;
								 if(in_array($v->specification_id,$new_specification_id_arr))
								 {
									 $is_specification=1;
								 }
								 ?>
								 <div class="form-group col-sm-4 ">
								 {{ Form::checkbox('specification_id[]', $v->specification_id, $is_specification, ['id' => 'specification_id','class'=>'specification_id']) }}&nbsp;{{ $v->specification}}
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
						<?php
							if(in_array($v->specification_id,$new_specification_id_arr))
							{
								$display_style='';
								$display_id = array_search($v->specification_id, $new_specification_id_arr);
								$spe_name_arr = explode("+++++",$new_spe_name_arr[$display_id]);
								$spe_value_arr = explode("+++++",$new_spe_value_arr[$display_id]);
							}
							else
							{
								$display_style='display:none';
								$spe_name_arr = explode("+++++",$v->spe_name);
								$spe_value_arr = explode("+++++",$v->spe_value);
							}
						?>
						<div class="form-group col-sm-12" id="spec_<?php echo $v->specification_id;?>" style="{{$display_style}}">

						<h3><?php echo $v->specification;?></h3>

							<table width="100%"  class="table table-bordered" >

							<tr>
								<th width="30%">{!! Form::label('Specification') !!} </th>
								<th width="70%">{!! Form::label('Description') !!}</th>
							</tr>

							<?php

							for($i=0; $i<count($spe_name_arr);$i++)
							{
							?>
							  <tr>
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
					@endforeach
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


 <div class="modal fade" id="modal-state">
  <div class="modal-dialog">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">Add State</h4>
	  </div>

	  {!! Form::open(array('action' => $state_url, 'method' => 'post' , 'files' => true ,'id'=>"statefrm",'name'=>"statefrm",'class'=>"form"))!!}
	   <div class="modal-body">

			 {{ csrf_field() }}


				{!! Form::label('Country') !!} <span class="required">*</span>

				<select name="country" id="country" class="select2" style="width:100%">
							<option  value="">Select</option>

							@foreach($country as $k=> $v)
								<option value="{{ $v->country_id}}" > {{ $v->country_name}}</option>
							@endforeach
				</select>
				<label id="country-error" class="error" for="country"></label>

				<br />
				{!! Form::label('Zone') !!} <span class="required">*</span>

				<select name="zone" id="zone" class="select2" style="width:100%">
							<option  value="">Select</option>

							@foreach($zone as $k=> $v)

								 @if ($v->zone_id == $result[0]->zone_id)
										<option value="{{ $v->zone_id}}" selected="selected" > {{ $v->zone_name}}</option>
								 @else

										<option value="{{ $v->zone_id}}" > {{ $v->zone_name}}</option>
							  @endif
							@endforeach
				</select>
				<label id="zone-error" class="error" for="zone"></label>


			  <br />

				{!! Form::label('State Name') !!} <span class="required">*</span>

				 {!! Form::text('statename','', array('class' => 'form-control ' ,'id'=>"statename",'placeholder'=>'Enter State Name','required' => 'required')) !!}
				<div id="state_data">

				</div>

				 {!! Form::hidden('stateredirect',$state_redirect, array('class' => 'form-control ' ,'id'=>"stateredirect",'required' => 'required')) !!}



	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary">Save</button>
	  </div>

	  {!!Form::close()!!}
	</div>
	<!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

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
		$('#total_amount').val(gross_amount.toFixed(2));

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

	$.validator.setDefaults({ ignore: ":hidden:not(.select2)" });

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
		get_price();
		sr_change();
		return false;
	});

	$('body').on('click',".btn-danger",function()
	{
		var count=$('.pending-user').length;
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

 	$('input[type="checkbox"]').click(function(){
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
				get_price();
			}
		});
	});

	var id= $("#id").val();
	$('#frm').validate({
		rules:{
			name: {required: true,
				remote: {
					url: '<?php echo $duplicate_url;?>',
					type: "post",
					data:
					{
						"_token": "{{ csrf_token() }}",
						id:id,
						action:'{{$action}}',
						name: function()
						{
							return $('#frm :input[name="name"]').val();
						},
					},
				},
			},
		},
		messages:{
			name: {required:"Please Enter Name.",remote:"Name Already Exist!"},

		},
		submitHandler: function(form){
			if(check_validation())
			{
				$(':input[type="submit"]').prop('disabled', true);
				get_price();
				form.submit();
			}
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
				status=0;
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
				status=0;
			}
		});
		//focused.focus();

		if(status==0){
			return true;
		}
		else{
			return false;
		}
	}

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