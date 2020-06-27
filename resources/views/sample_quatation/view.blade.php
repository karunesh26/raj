@extends('template.template')
@section('content')

<?php
function number_to_words ($x)
{
     $nwords = array( "", "One", "Two", "Three", "Four", "Five", "Six", 
	      	  "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen", 
	      	  "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen", 
	     	  "Nineteen", "Twenty", 30 => "Thirty", 40 => "Fourty",
                     50 => "Fifty", 60 => "Sixty", 70 => "Seventy", 80 => "Eigthy",
                     90 => "Ninety" );
							
     if(!is_numeric($x))
     {
         $w = '#';
     }else if(fmod($x, 1) != 0)
     {
         $w = '#';
     }else{
         if($x < 0)
         {
             $w = 'minus ';
             $x = -$x;
         }else{
             $w = '';
         }
         if($x < 21)
         {
             $w .= $nwords[$x];
         }else if($x < 100)
         {
             $w .= $nwords[10 * floor($x/10)];
             $r = fmod($x, 10);
             if($r > 0)
             {
                 $w .= ' '. $nwords[$r];
             }
         } else if($x < 1000)
         {
		
             $w .= $nwords[floor($x/100)] .' Hundred';
             $r = fmod($x, 100);
             if($r > 0)
             {
                 $w .= ' '. number_to_words($r);
             }
         } else if($x < 100000)
         {
         	$w .= number_to_words(floor($x/1000)) .' Thousand';
             $r = fmod($x, 1000);
             if($r > 0)
             {
                 $w .= ' ';
                 if($r < 100)
                 {
                     $w .= ' ';
                 }
                 $w .= number_to_words($r);
             }
         } else {
             $w .= number_to_words(floor($x/100000)) .' Lakh';
             $r = fmod($x, 100000);
             if($r > 0)
             {
                 $w .= ' ';
                 if($r < 100)
                 {
                     $word .= ' ';
                 }
                 $w .= number_to_words($r);
             }
         }
     }
     return $w;
} 



function number_to_words_decimal($num){ 
$decones = array( 
            '01' => "One", 
            '02' => "Two", 
            '03' => "Three", 
            '04' => "Four", 
            '05' => "Five", 
            '06' => "Six", 
            '07' => "Seven", 
            '08' => "Eight", 
            '09' => "Nine", 
            10 => "Ten", 
            11 => "Eleven", 
            12 => "Twelve", 
            13 => "Thirteen", 
            14 => "Fourteen", 
            15 => "Fifteen", 
            16 => "Sixteen", 
            17 => "Seventeen", 
            18 => "Eighteen", 
            19 => "Nineteen" 
            );
$ones = array( 
            0 => " ",
            1 => "One",     
            2 => "Two", 
            3 => "Three", 
            4 => "Four", 
            5 => "Five", 
            6 => "Six", 
            7 => "Seven", 
            8 => "Eight", 
            9 => "Nine", 
            10 => "Ten", 
            11 => "Eleven", 
            12 => "Twelve", 
            13 => "Thirteen", 
            14 => "Fourteen", 
            15 => "Fifteen", 
            16 => "Sixteen", 
            17 => "Seventeen", 
            18 => "Eighteen", 
            19 => "Nineteen" 
            ); 
$tens = array( 
            0 => "",
            2 => "Twenty", 
            3 => "Thirty", 
            4 => "Forty", 
            5 => "Fifty", 
            6 => "Sixty", 
            7 => "Seventy", 
            8 => "Eighty", 
            9 => "Ninety" 
            ); 
$hundreds = array( 
            "Hundred", 
            "Thousand", 
            "Million", 
            "Billion", 
            "Trillion", 
            "Quadrillion" 
            ); 
			//limit t quadrillion 
$num = number_format($num,2,".",","); 
$num_arr = explode(".",$num); 
$wholenum = $num_arr[0]; 
$decnum = $num_arr[1]; 
$whole_arr = array_reverse(explode(",",$wholenum)); 
krsort($whole_arr); 
$rettxt = ""; 
foreach($whole_arr as $key => $i){ 
    if($i < 20)
	{ 
        $rettxt .= $ones[$i]; 
    }
    elseif($i < 100)
	{ 
        $rettxt .= $tens[substr($i,0,1)]; 
        $rettxt .= " ".$ones[substr($i,1,1)]; 
    }
    else
	{ 
        $rettxt .= $ones[substr($i,0,1)]." ".$hundreds[0]; 
       $rettxt .= " ".$tens[substr($i,1,1)]; 
        $rettxt .= " ".$ones[substr($i,2,1)]; 
    } 
    if($key > 0)
	{ 
        $rettxt .= " ".$hundreds[$key]." "; 
    } 

} 
//$rettxt = $rettxt." Rupees";

if($decnum > 0)
{ 
    $rettxt .= " and "; 
    if($decnum < 20)
	{ 
        $rettxt .= $decones[$decnum]; 
    }
    elseif($decnum < 100)
	{ 
        $rettxt .= $tens[substr($decnum,0,1)]; 
        $rettxt .= " ".$ones[substr($decnum,1,1)]; 
    }
    $rettxt = $rettxt." Paise"; 
} 
return $rettxt;
}


function decimal_to_words($x)
{
	$x = str_replace(',','',$x);
	$pos = strpos((string)$x, ".");
	if ($pos !== false) { $decimalpart= substr($x, $pos+1, 3); $x = substr($x,0,$pos); }
	$tmp_str_rtn = number_to_words ($x);
	if(!empty($decimalpart))
		//$tmp_str_rtn .= ' and '  . number_to_words_decimal ($decimalpart) . ' paise';
	return   $tmp_str_rtn;
}
function moneyFormatIndia($number) {
	$number_arr = explode(".",$number);
	$num = $number_arr[0];
    $explrestunits = "" ;
    if(strlen($num)>3)
     {
        $lastthree = substr($num, strlen($num)-3, strlen($num));
        $restunits = substr($num, 0, strlen($num)-3); // extracts the last three digits
        $restunits = (strlen($restunits)%2 == 1)?"0".$restunits:$restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
        $expunit = str_split($restunits, 2);
        for($i=0; $i<sizeof($expunit); $i++) {
            // creates each of the 2's group and adds a comma to the end
            if($i==0) {
                $explrestunits .= (int)$expunit[$i].","; // if is first value , convert into integer
            } else {
                $explrestunits .= $expunit[$i].",";
            }
        }
        $thecash = $explrestunits.$lastthree;
    } else {
        $thecash = $num;
    }

      return $thecash.".00";
    
    
}
error_reporting(0);
$btn= "View";
$back_link = URL::to($controller_name);
?>
	<style>
/* Ensure that the demo table scrolls */
	
   
   
	section {
        font-family: sans-serif;
		font-size:18px;
		font-weight:700;
    }
	 td {
		border : 1px solid black;
        padding-left: 0.35em;
        padding-right: 0.35em;
        padding-top: 0.35em;
        padding-bottom: 0.35em;
        vertical-align: top;
		font-family: sans-serif;
		font-size:18px;
    }
	.left{
		border-left:none;
	}
	.right{
		border-right:none;
	}
	.bottom{
		border-bottom:none;
	}
	.top{
		border-top:none;
	}
	.no_border{
		border:none !important;
	}
</style>

 <!-- Content Header (Page header) -->
    <section class="content-header">
    
      <ol class="breadcrumb">
        <li><a href="{{ URL::to('Dashboard') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{ URL::to($controller_name) }}">{{ $msgName }}</a></li>
        <li class="active"><?php echo $btn;?></li>
      </ol>
    </section>
    
    <section class="content">
	  
      <br />   
	 

      <div class="row">
       <div class="box ">
      	
          
		  
		<table class="no_border" width="100%" style="margin-left: 2%; ">
			<tr>
				
				<td colspan="3" style="padding-right: 2%; "  align="right" class="no_border">Date :- {{ date('jS F Y',strtotime(date('Y-m-d'))) }}
				
			</tr>
			<tr>
				<td colspan="3" style="padding-right: 2%; "   align="right" class="no_border">{{ date('l',strtotime(date('Y-m-d')))}}
			</tr>
			<?php /* <tr>
				<td class="no_border" width="19%">Quotation No</td>
				<td class="no_border" align="right" width="1%">:-</td>
				<td class="no_border" width="80%"></td>
			</tr>
			<tr>
				<td class="no_border">Concern Person</td>
				<td class="no_border" align="right">:-</td>
				<td class="no_border"></td>
			</tr>
			<tr>
				<td class="no_border">Concern Number</td>
				<td class="no_border" align="right">:-</td>
				<td class="no_border"></td>
			</tr>
			<tr>
				<td class="no_border">Email Id</td>
				<td class="no_border" align="right">:-</td>
				<td class="no_border"></td>
			</tr>
			
			<tr>
				<td class="no_border">Company Name</td>
				<td class="no_border" align="right">:-</td>
				<td class="no_border"></td>
			</tr>
			<tr>
				<td class="no_border">Address</td>
				<td class="no_border" align="right">:-</td>
				<td class="no_border"></td>
			</tr> */ ?>
			
		</table> 
		 <div style="margin-left: 2%; ">
		<p>
			<center><h2><u>Sample Quotation For {{ $result[0]->name }}</u></h2></center>
		</p>
		
		Dear Sir ,
		<br><br>
		We are pleased to enclose our detailed Techno-Commercial offer, based on your requirement.
		<br><Br>

		<img src="{{ URL::asset('external/quatation_formate/bullet.png') }}"/> &nbsp;<span style="margin-left:1%">Contents of the  Quotation</span>
		<br />
		<img src="{{ URL::asset('external/quatation_formate/bullet2.png') }}" style="margin-left:5%"/> &nbsp;Commercial Proposal
		<br>
		<img src="{{ URL::asset('external/quatation_formate/bullet2.png') }}" style="margin-left:5%"/> &nbsp;Terms & Condition
		<br>
		<img src="{{ URL::asset('external/quatation_formate/bullet2.png') }}" style="margin-left:5%"/> &nbsp;Technical Specification for each product
		<br><br>
		
		<img src="{{ URL::asset('external/quatation_formate/bullet.png') }}"/> &nbsp;<span style="margin-left:1%"><b>Machinery Offer</b></span>
		<br />
		<img src="{{ URL::asset('external/quatation_formate/bullet2.png') }}" style="margin-left:5%"/> &nbsp;{{ $result[0]->product_name}}
		<br><br>
		
		
		We hope the same is in line with your requirement. We shall gladly assist you in case you need any further clarification or information.
		<br>
		<br>With warm Regards,
		<br><br>

		<b>For , {{ $company[0]->name }}</b>
		<br /><br />
		<br /><br />
		<b>{{ $result[0]->username }}</b><br />
		<b>Designation : {{ $result[0]->role_name }}</b><br />
		<b>Mobile : {{ $result[0]->user_mobile }}</b>
		
		</div>
		
		
		<img src="{{ URL::asset('external/quatation_formate/img_2.png') }}" width="100%" />
		
		
		<h2><center><u>Commercial Proposal</u></center></h2>
		<table width="98%"  style="margin-left: 2%; ">
				<thead>
				<tr>
					<td align="center" width="5%">Sr No.</td>
					<td align="center" width="50%">Equipment Name</td>
					<td align="center" width="5%">Qty</td>
					<td align="center" width="20%">Basic Rate</td>
					<td align="center" width="20%">Amount</td>
				</tr>
				</thead>
				<tbody>
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
					<tr height="24">
						<td height="24" align="center">{{ $i+1 }}</td>
						<td height="24" > 
							@foreach($quatation_product as $value)
								@if($value->p_id==$quatation_product_id_arr[$i])
									{{ $value->name }}
									<br>
									<?php echo $value->remark;?>
								@endif
							@endforeach
						</td>
						<td height="24" align="center">
							{{ $qty_arr[$i] }}
						</td>
						<td height="24" align="center">
						 <?php
						  $rate = $rate_arr[$i];
						  $rate = moneyFormatIndia( $rate );
						  echo $rate;
                                ?>
							
						</td>
						<td height="24"  align="center">
							 <?php
							 $amount =$amount_arr[$i];
							 $amount = moneyFormatIndia( $amount );
							 echo $amount;
                                ?>
								
						</td>
					</tr>
				<?php
				}
					
				$used = count($quatation_product_id_arr);
				for($a=0 ; $a < 20-$used  ; $a++)
				{
					?>
					<tr height="24"   class="bottom top" >
						<td height="24" class="bottom top" ></td>
						<td height="24"   class="bottom top" ></td>
						<td height="24"  class="bottom top" ></td>
						<td height="24"  class="bottom top" ></td>
						<td height="24"  class="bottom top" ></td>
					</tr>
					<?php
				}
				?>
				<?php /* <tr>
					<td colspan="4" align="right">Gross Amount</td>
					<td align="center">
					  <?php
							 $grossamount =$result[0]->gross_amount;
							 $grossamount = moneyFormatIndia( $grossamount );
							 echo $grossamount;
                       ?>
						</td>
				</tr>
				<tr>
					<td colspan="4" align="right">GST @ 18%</td>
					<td align="center">
					<?php
							 $gst =$result[0]->gst_amount;
							 $gst = moneyFormatIndia( $gst);
							 echo $gst;
                       ?>
					  </td>
				</tr> */ ?>
				<tr>
					<td colspan="3" ><b>Total Amount in Words :- </b><br />
						<?php
							echo  decimal_to_words(number_format((float)$result[0]->total_amount,2,'.',''));
						?>
					</td>
					<td  align="right">Total Amount</td>
					<td align="center"><?php
						 $tot =$result[0]->total_amount;
						 $tot = moneyFormatIndia( $tot);
						 echo $tot;
                       ?></td>
				</tr>
				</tbody>
			</table>
		<div style="margin-left: 2%; ">	
			<h2><center><u>Terms & Condition</u></center></h2>
			<br />
				<?php /* <img style="margin-left:10%;width:80%;height:900px;" src="{{ URL::asset('external/term_condition/Terms_1.jpg') }}" />
				<img style="margin-left:10%;width:80%;height:800px;" src="{{ URL::asset('external/term_condition/Terms_2.jpg') }}" /> */ ?>
				
				<?= $terms_condition[0]->terms ?>
		</div>
		<div style="margin-left: 2%; ">
			<?php
			if($result[0]->specification_id != '')
			{
			?>
				<h2><center><u>Technical Specification </u></center></h2>
				<?php
				$spec_arr = explode("*****",$result[0]->specification_id);
				$spec_name_arr = explode("*****",$result[0]->spe_name);
				$spec_value_arr = explode("*****",$result[0]->spe_value);
				$a = 1;
					foreach($specification as $k=> $v)
					{
						if(in_array($v->specification_id,$spec_arr))
						{
							
							$key = array_search($v->specification_id,$spec_arr);
							
							$name_arr = explode("+++++",$spec_name_arr[$key]);
							$value_arr = explode("+++++",$spec_value_arr[$key]); 
							
							/* $name_arr = explode("+++++",$v->spe_name);
							$value_arr = explode("+++++",$v->spe_value);  */
							?>
							
							<h3><b><?php echo $a++.". ".$v->specification;?></b></h3>
							
							
								<table width="100%"  >
									<tr class="no_border">
									<td class="no_border" width="70%">
										<table width="100%" >
											<?php
												for($i=0; $i<count($name_arr); $i++)
												{
													?>
												<tr>
													<td width="30%" class="no_border"><?php echo $name_arr[$i];?> : </td>
													<td width="70%" class="no_border"><?php echo $value_arr[$i];?></td>
												</tr>
												<?php
												}
												?>
										</table>
									</td>
									<td  class="no_border" width="30%">
									<?php
									if($v->image != '')
									{
										?>
										<img src="{{ asset('external/specification_image/'.$v->image) }}" height="300" width="200">
										<?php
									}
									?>
									</td>
									</tr>
									<?php 
										if($v->application != '')
										{
											?>
											<tr>
											<td colspan="2" class="no_border">
											<u>Application :- </u>
											<br>
											<br>
											<?php
											echo $v->application;
											?>
											
											</td>
											</tr>
											<?php
										}
									?>
										
								</table>

							
							<?php
							
						}
					}
			}
			?>
			</div>
		</div>
       </div>
     </section>
@endsection