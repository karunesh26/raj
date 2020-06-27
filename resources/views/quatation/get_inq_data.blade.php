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
					 <td><b>Total Amount</b></td>
					<!--<td>{!! Form::text('total_amount',number_format((float)array_sum($amount_arr),2,'.',''), array('class' => 'form-control ' ,'id'=>"total_amount",'required' => 'required','readonly'=>'readonly')) !!}</td>-->
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