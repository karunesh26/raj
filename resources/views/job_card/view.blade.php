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
       <div class="box">
      
		
		<h2><center><u>Job Card</u></center></h2>
			<table class="no_border" style="margin-left:2%;" width="96%">
				<tbody>
					<tr>
						<td width="50%">
							<b>To,</b><br/>
							{{ $result[0]->prefix.' '.$result[0]->name.' - '.$result[0]->mobile }} <br/>
							{{ $result[0]->address }}
						</td>
						<td width="50%">
							<b>Quotation No.    :-</b><?php echo ($quot == 'yes') ? $result[0]->quatation_no : $result[0]->revise_quatation_no;?><br/>
							<b>Quotation Date   :-</b>{{ date('d-m-Y',strtotime($result[0]->quot_date)) }}<br/>
							<b>Order Book Date  :-</b>{{ date('d-m-Y',strtotime($result[0]->order_book_date)) }}<br/>
						</td>
					</tr>
				</tbody>
			</table>
			<table class="no_border" style="margin-left:2%;margin-bottom:2%;" width="96%">
				<thead>
				<tr>
					<td align="center" width="5%"><b>Sr. No.</b></td>
					<td align="center" width="60%"><b>Product Details</b></td>
					<td align="center" width="5%"><b>Qty</b></td>
					<td align="center" width="30%"><b>Remark</b></td>
				</tr>
				</thead>
				<tbody style="border-bottom:1px solid black;">
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
					<tr height="16" >
						<td height="16"  align="center">{{ $i+1 }}</td>
						<td height="16" style="text-align:justify !important;" > 
							@foreach($quatation_product as $value)
								@if($value->p_id==$quatation_product_id_arr[$i])
									{{ $value->name }}
									<br>
									{!! $value->remark !!}
								@endif
							@endforeach
						</td>
						<td height="16"  align="center">
							{{ $qty_arr[$i] }}
						</td>
						<td height="16" align="center" style="text-align:justify !important;">
							
						</td>
						
					</tr>
				<?php
				}
				
				$used = count($quatation_product_id_arr);
				for($a=0 ; $a < 10-$used  ; $a++)
				{
					?>
					<tr height="16" class="">
						<td height="16" class="bottom top"></td>
						<td height="16"  class="bottom top"></td>
						<td height="16" class="bottom top"></td>
						<td height="16" class="bottom top"></td>
					</tr>
					<?php
				}
				?>
				
				</tbody>
			</table>
			<div style="margin-left: 2%; ">	
				<h2><center><u></u></center></h2>
				<br />
			</div>
		</div>
       </div>
     </section>
@endsection