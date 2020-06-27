<?php
function number_to_words ($x)
{
     $nwords = array(  "", "One", "Two", "Three", "Four", "Five", "Six",
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
         }
		 else
		 {
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
	//	$tmp_str_rtn .= ' and '  . number_to_words_decimal ($decimalpart) . ' paise';
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

      return $thecash;


}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<style>
/* Ensure that the demo table scrolls */
	@page{
		margin:0;
		padding:0;
		size: 8.29in 3.5in  portrait;
		font-family:courier-new;
		font-size:12px;
		font-weight:900;
		text-transform:uppercase;
	}
	@media print
	{
		body,html{
			margin:0px !important;
			font-family:courier-new;
			font-size:12px;
		}
		@page{
			margin:0px !important;
			size: 8.29in 3.5in  portrait;
			font-family:courier-new;
			font-size:12px;
		}
	}
	
</style>
</head>
<body style="margin:0px;!important">
	<div style="position:absolute; left: 74.00%; top: 9.24%;letter-spacing:10.10px;font-size:16px;">{{ $check_date }}</div>
	<div style="position:absolute; left: 8.20%; top: 24.15%;">{{ $party_name }}</div>
	<!--<div style="position:absolute; left: 9.70%; top: 34.00%;">{{ decimal_to_words(number_format((float)$amount,2,'.','')).' Only.' }}</div>-->
	<?php 
	$amount_word = decimal_to_words(number_format((float)$amount,2,'.','')).' Only.';
	$top = 34.00;
	$to_add = 9.85;
	if(strlen($amount_word) > 40)
	{
		$word_first_line = Array();
		$word_second_line = Array();
		$word_explode = explode(" ",$amount_word);
		// print_r($word_explode);
		$str_wrd = null;
		foreach($word_explode as $w)
		{
			$str_wrd .= $w;
			if(strlen($str_wrd) < 40)
			{
				$word_first_line[count($word_first_line)] = $w;
			}else{
				$word_second_line[count($word_second_line)] = $w;
			}
		}
	?>
		<div style="position:absolute; left: 9.70%; top: 34.00%;"><?php echo implode(" ",$word_first_line); ?></div>
		<div style="position:absolute; left: 9.70%; top: 43.85%;"><?php echo implode(" ",$word_second_line); ?></div>
	<?php
	}else{
	?>
		<div style="position:absolute; left: 9.70%; top: 34.00%;"><?php echo $amount_word; ?></div>
	<?php } ?>
	<div style="position:absolute; left: 73.50%; top: 42.40%;letter-spacing:2px;font-size:14px;">{{ moneyFormatIndia($amount).'/-' }}</div>
</body>
</html>
