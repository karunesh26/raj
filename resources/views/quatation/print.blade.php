<?php
$mobile = '';
if ($result->mobile != ''){$mobile .= $result->mobile;}
if ($result->mobile_2 != ''){$mobile .= ' / ' . $result->mobile_2;}
if ($result->mobile_3 != ''){$mobile .= ' / ' . $result->mobile_3;}
$email = '';
if ($result->email != '') {$email .= $result->email;}
if ($result->email_2 != ''){$email .= ' / ' . $result->email_2;}

function number_to_words($x) {
    $nwords = array("", "One", "Two", "Three", "Four", "Five", "Six",
        "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen",
        "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen",
        "Nineteen", "Twenty", 30 => "Thirty", 40 => "Fourty",
        50 => "Fifty", 60 => "Sixty", 70 => "Seventy", 80 => "Eigthy",
        90 => "Ninety");
    if (!is_numeric($x)) {
        $w = '#';
    } else if (fmod($x, 1) != 0) {
        $w = '#';
    } else {
        if ($x < 0) {
            $w = 'minus ';
            $x = -$x;
        } else {
            $w = '';
        }
        if ($x < 21) {
            $w .= $nwords[$x];
        } else if ($x < 100) {
            $w .= $nwords[10 * floor($x / 10)];
            $r = fmod($x, 10);
            if ($r > 0) {
                $w .= ' ' . $nwords[$r];
            }
        } else if ($x < 1000) {
            $w .= $nwords[floor($x / 100)] . ' Hundred';
            $r = fmod($x, 100);
            if ($r > 0) {
                $w .= ' ' . number_to_words($r);
            }
        } else if ($x < 100000) {
            $w .= number_to_words(floor($x / 1000)) . ' Thousand';
            $r = fmod($x, 1000);
            if ($r > 0) {
                $w .= ' ';
                if ($r < 100) {
                    $w .= ' ';
                }
                $w .= number_to_words($r);
            }
        } else {
            $w .= number_to_words(floor($x / 100000)) . ' Lakh';
            $r = fmod($x, 100000);
            if ($r > 0) {
                $w .= ' ';
                if ($r < 100) {
                    $word .= ' ';
                }
                $w .= number_to_words($r);
            }
        }
    }
    return $w;
}

function number_to_words_decimal($num) {
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
    $num = number_format($num, 2, ".", ",");
    $num_arr = explode(".", $num);
    $wholenum = $num_arr[0];
    $decnum = $num_arr[1];
    $whole_arr = array_reverse(explode(",", $wholenum));
    krsort($whole_arr);
    $rettxt = "";
    foreach ($whole_arr as $key => $i) {
        if ($i < 20) {
            $rettxt .= $ones[$i];
        } elseif ($i < 100) {
            $rettxt .= $tens[substr($i, 0, 1)];
            $rettxt .= " " . $ones[substr($i, 1, 1)];
        } else {
            $rettxt .= $ones[substr($i, 0, 1)] . " " . $hundreds[0];
            $rettxt .= " " . $tens[substr($i, 1, 1)];
            $rettxt .= " " . $ones[substr($i, 2, 1)];
        }
        if ($key > 0) {
            $rettxt .= " " . $hundreds[$key] . " ";
        }
    }
//$rettxt = $rettxt." Rupees";
    if ($decnum > 0) {
        $rettxt .= " and ";
        if ($decnum < 20) {
            $rettxt .= $decones[$decnum];
        } elseif ($decnum < 100) {
            $rettxt .= $tens[substr($decnum, 0, 1)];
            $rettxt .= " " . $ones[substr($decnum, 1, 1)];
        }
        $rettxt = $rettxt . " Paise";
    }
    return $rettxt;
}

function decimal_to_words($x) {
    $x = str_replace(',', '', $x);
    $pos = strpos((string) $x, ".");
    if ($pos !== false) {
        $decimalpart = substr($x, $pos + 1, 3);
        $x = substr($x, 0, $pos);
    }
    $tmp_str_rtn = number_to_words($x);
    if (!empty($decimalpart))
    //	$tmp_str_rtn .= ' and '  . number_to_words_decimal ($decimalpart) . ' paise';
        return $tmp_str_rtn;
}

function moneyFormatIndia($number) {
    $number_arr = explode(".", $number);
    $num = $number_arr[0];
    $explrestunits = "";
    if (strlen($num) > 3) {
        $lastthree = substr($num, strlen($num) - 3, strlen($num));
        $restunits = substr($num, 0, strlen($num) - 3); // extracts the last three digits
        $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
        $expunit = str_split($restunits, 2);
        for ($i = 0; $i < sizeof($expunit); $i++) {
            // creates each of the 2's group and adds a comma to the end
            if ($i == 0) {
                $explrestunits .= (int) $expunit[$i] . ","; // if is first value , convert into integer
            } else {
                $explrestunits .= $expunit[$i] . ",";
            }
        }
        $thecash = $explrestunits . $lastthree;
    } else {
        $thecash = $num;
    }
    return $thecash . ".00";
}
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
        <style>
            /* Ensure that the demo table scrolls */
            .page-break{page-break-after: always;}
            .fa{display: inline;font-style: normal;font-variant: normal;font-weight: normal;font-size: 14px;line-height: 1;font-family: FontAwesome;font-size: inherit;text-rendering: auto;-webkit-font-smoothing: antialiased;-moz-osx-font-smoothing: grayscale;}
            @page{margin:15px !important;}
            .dataTables_wrapper{margin:30px !important;}
            th, td {border : 1px solid black; font-size:14px}
            div.dataTables_wrapper{width: 100%;margin: 0 auto;}
            body{font-family: sans-serif;}
            a {
                color: #000066;
                text-decoration: none;
            }
            table {
                border : 1px solid black;
                border-collapse: collapse;
            }
            thead {
                vertical-align: bottom;
                text-align: center;
                font-weight: bold;
            }
            tfoot {
                text-align: center;
                font-weight: bold;
            }
            th {
                text-align: left;
                padding-left: 0.35em;
                padding-right: 0.35em;
                padding-top: 0.35em;
                padding-bottom: 0.35em;
                vertical-align: top;
            }
            td {
                border : 1px solid black;
                padding-left: 0.35em;
                padding-right: 0.35em;
                padding-top: 0.35em;
                padding-bottom: 0.35em;
                vertical-align: top;
            }
            .left_right{
                border-left:none;
                border-right:none;
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
            #watermark{
                position: fixed;
                bottom:   6cm;
                left:     7cm;
                width:    7cm;
                height:   10cm;
                z-index:  -1000;
            }
            .extra_margin
            {
                margin-top:200px;
            }
        </style>
    </head>
    <body>
        @if($with_latterhead=='yes')
        <div id="watermark">
            <img src="{{ URL::asset('external/id_photo/bak.jpg') }}" height="100%" width="100%" />
        </div>
        @endif
        @if($with_latterhead=='yes')
                        <?php /*<img style="height:200px;" src="{{ URL::asset('external/quatation_formate/'.$letter_head[0]->letterhead_name) }}" width="100%" />*/ ?>
                        <img style="height:200px;" src="{{ URL::asset('external/quatation_formate/header.png') }}" width="100%" />
        @endif
        @if($with_latterhead != 'yes')
        <div class="extra_margin"></div>
        @endif
        <div class="dataTables_wrapper">
            <table class="no_border" width="100%">
                <tr>
                    <td colspan="3" align="right" class="no_border">Date :- {{ date('jS F Y',strtotime($result->quatation_date)) }}</td>
                </tr>
                <tr>
                    <td colspan="3" align="right" class="no_border">{{ date('l',strtotime($result->quatation_date))}}</td>
                </tr>
                <tr>
                    <td class="no_border" width="19%">Quotation No</td>
                    <td class="no_border" align="right" width="1%">:-</td>
                    <td class="no_border" width="80%">{{ $result->quatation_no }}</td>
                </tr>
                <tr>
                    <td class="no_border">Concern Person</td>
                    <td class="no_border" align="right">:-</td>
                    <td class="no_border">{{ $result->prefix.' '.$result->customer_name }}</td>
                </tr>
                <tr>
                    <td class="no_border">Concern Number</td>
                    <td class="no_border" align="right">:-</td>
                    <td class="no_border">{{ $mobile }}</td>
                </tr>
                <tr>
                    <td class="no_border">Email Id</td>
                    <td class="no_border" align="right">:-</td>
                    <td class="no_border">{{ $email }}</td>
                </tr>
                <tr>
                    <td class="no_border">Company Name</td>
                    <td class="no_border" align="right">:-</td>
                    <td class="no_border">{{ $result->company }}</td>
                </tr>
                <tr>
                    <td class="no_border">Address</td>
                    <td class="no_border" align="right">:-</td>
                    <td class="no_border">
                        <?php
                        if ($result->address == '') {
                            echo "<b>Country : </b>" . $result->country_name . "<br/>";
                            echo "<b>State : </b>" . $result->state_name . "<br/>";
                            echo "<b>City : </b>" . $result->city_name . "<br/>";
                        } else {
                            echo $address = $result->address;
                        }
                        ?>
                    </td>
                </tr>
            </table>
            <p>
            <center><h2><u>Quotation For {{ $result->product_name}}</u></h2></center>
        </p>
        <b>Dear Sir ,</b>
        <br><br>
        We are pleased to enclose our detailed Techno-Commercial offer, based on your requirement.

        <br><Br>



        <img src="{{ URL::asset('external/quatation_formate/bullet.png') }}"/> &nbsp;<span style="margin-left:1%"><b>Contents of the  Quotation</b></span>

        <br />

        <img src="{{ URL::asset('external/quatation_formate/bullet2.png') }}" style="margin-left:5%"/> &nbsp;Commercial Proposal

        <br>

        <img src="{{ URL::asset('external/quatation_formate/bullet2.png') }}" style="margin-left:5%"/> &nbsp;Terms & Condition

        <br>

        <img src="{{ URL::asset('external/quatation_formate/bullet2.png') }}" style="margin-left:5%"/> &nbsp;Technical Specification for each product

        <br><br>



        <img src="{{ URL::asset('external/quatation_formate/bullet.png') }}"/> &nbsp;<span style="margin-left:1%"><b>Machinery Offer</b></span>

        <br />

        <img src="{{ URL::asset('external/quatation_formate/bullet2.png') }}" style="margin-left:5%"/> &nbsp;{{ $result->product_name}}

        <br><br>





        We hope the same is in line with your requirement. We shall gladly assist you in case you need any further clarification or information.

        <br>

        <br>With warm Regards,</b>

        <br><br>



        <b>For , {{ $company[0]->name }}</b>

        <br /><br />

        <b>

            @if($result->employee_name != '')

            {{ $result->employee_name }}

            @else

            admin

            @endif

        </b><br />

        <b>Designation : {{ $result->role_name }}</b><br />

        <b>Mobile : {{ $result->user_mobile }}</b>



        <!-- For Page Break -->

        <!-- <div class="page-break"></div> -->



        <img src="{{ URL::asset('external/quatation_formate/img_2.png') }}" width="100%" style="height:995px;" />



        <!-- For Page Break

        <div class="page-break"></div> -->





        <h2><center><u>Commercial Proposal</u></center></h2>

        <table width="100%">

            <thead>

                <tr>

                    <td align="center" width="5%"><b>Sr No.</b></td>

                    <td align="center" width="50%"><b>Equipment Name</b></td>

                    <td align="center" width="5%"><b>Qty</b></td>

                    <td align="center" width="20%"><b>Basic Rate</b></td>

                    <td align="center" width="20%"><b>Amount</b></td>

                </tr>

            </thead>

            <tbody>

                <?php
                $quatation_product_id_arr = explode(',', $result->quatation_product_id);

                $rate_arr = explode(',', $result->rate);

                $qty_arr = explode(',', $result->qty);

                $amount_arr = explode(',', $result->amount);
                ?>

<?php
for ($i = 0; $i < count($quatation_product_id_arr); $i++) {
    ?>

                    <tr height="16" >

                        <td height="16"  align="center">{{ $i+1 }}</td>

                        <td height="16" style="text-align:justify !important;" >

                            @foreach($quatation_product as $value)

                            @if($value->p_id==$quatation_product_id_arr[$i])

                            {{ $value->name }}

                            <br>

    <?php echo $value->remark; ?>

                            @endif

                            @endforeach

                        </td>

                        <td height="16"  align="center">

                            {{ $qty_arr[$i] }}

                        </td>

                        <td height="16"  align="center">

                            <?php
                            $rate = $rate_arr[$i];

                            /*   echo number_format((float)$rate,2,'.',''); */

                            /* $rate = moneyFormatIndia( $rate ); */

                            echo $rate;
                            ?>

                        </td>

                        <td height="16"  align="center">

                            <?php
                            $amount = $amount_arr[$i];

                            /*  echo number_format((float)$amount,2,'.',''); */

                            /* $amount = moneyFormatIndia( $amount ); */

                            echo $amount;
                            ?>

                        </td>

                    </tr>

                    <?php
                }



                $used = count($quatation_product_id_arr);

                for ($a = 0; $a < 10 - $used; $a++) {
                    ?>

                    <tr height="16" class="bottom top">

                        <td height="16" class="bottom top"></td>

                        <td height="16"  class="bottom top"></td>

                        <td height="16" class="bottom top"></td>

                        <td height="16" class="bottom top"></td>

                        <td height="16" class="bottom top"></td>

                    </tr>

                    <?php
                }
                ?>

                <?php /* <tr>

                  <td colspan="4" align="right"><b>Gross Amount</b></td>

                  <td align="center"><b>  <?php

                  $grossamount =$result->gross_amount;

                  $grossamount = moneyFormatIndia( $grossamount );

                  echo $grossamount;

                  ?></b></td>

                  </tr>

                  <tr>

                  <td colspan="4" align="right"><b>GST @ 18%</b></td>

                  <td align="center"><b><?php

                  $gst =$result->gst_amount;

                  $gst = moneyFormatIndia( $gst);

                  echo $gst;

                  ?></b></td>

                  </tr> */ ?>

<?php
if ($result->discount != 0) {
    ?>

                    <tr>

                        <td colspan="3" ></td>

                        <td align="right"><b>Total</b></td>

                        <td align="center"><b>

                                <?php
                                $tot = $result->total;

                                $tot = moneyFormatIndia($tot);
                                ?>

    <?php echo $tot;
    ?></b>

                        </td>

                    </tr>

                    <tr>

                        <td colspan="3" ></td>

                        <td align="right"><b>Discount</b></td>

                        <td align="center"><b>

                                <?php
                                $discount = $result->discount;

                                $dis = moneyFormatIndia($discount);
                                ?>

                    <?php echo $dis;
                    ?></b>

                        </td>

                    </tr>

    <?php
}
?>



                <tr>

                    <td colspan="3" ><b>Total Amount in Words :- </b><br />

<?php
echo decimal_to_words(number_format((float) $result->total_amount, 2, '.', ''));
?>

                    </td>

                    <td align="right"><b>Total Amount</b></td>

                    <td align="center"><b><?php
$tot = $result->total_amount;

$tot = moneyFormatIndia($tot);
?>



                            <?php
                            if ($cur_type == 'inr') {
                                ?>

                                <img style="margin-top:3px !important;height:15px;" src="{{ URL::asset('external/quatation_formate/rupee.png') }}"/>

                                <?php
                            } else {
                                ?>

                                US <img style="margin-top:3px !important;height:15px;" src="{{ URL::asset('external/quatation_formate/doller.png') }}"/>

    <?php
}
?>

<?php echo $tot;
?></b>

                    </td>

                </tr>

            </tbody>

        </table>

        <!-- For Page Break -->

        <div class="page-break"></div>

        <h2><center><u>Terms & Condition</u></center></h2>

<?php /* <img style="width:100%;height:850px;" src="{{ URL::asset('external/term_condition/Terms_1.jpg') }}" />

  <img style="width:100%;height:700px;" src="{{ URL::asset('external/term_condition/Terms_2.jpg') }}" /> */ ?>

        <br />

        <font size="-1">

        <?= $terms_condition[0]->terms ?>



        </font>

<?php
if ($result->specification_id != '') {
    ?>

            <div class="page-break"></div>

            <h2><center><u>Technical Specification </u></center></h2>



            <?php
            $spec_arr = explode("*****", $result->specification_id);

            $spec_name_arr = explode("*****", $result->spe_name);

            $spec_value_arr = explode("*****", $result->spe_value);

            $a = 1;



            $cnt = 0;

            foreach ($specification as $k => $v) {

                if (in_array($v->specification_id, $spec_arr)) {

                    $key = array_search($v->specification_id, $spec_arr);

                    $name_arr = explode("+++++", $spec_name_arr[$key]);

                    $value_arr = explode("+++++", $spec_value_arr[$key]);

                    if ($cnt != 0) {

                        if ($cnt % 2 == 0) {

                            echo '<div class="page-break"></div>';
                        }
                    }
                    ?>

                    <h4><?php echo $a++ . ". " . $v->specification; ?></h4>

                    <table width="100%"  class="no_border">

                        <tr class="no_border">

                            <td class="no_border" width="70%">

                                <table width="100%" class="no_border">

            <?php
            for ($i = 0; $i < count($name_arr); $i++) {
                ?>

                                        <tr class="no_border">

                                            <td width="30%" class="no_border"><?php echo $name_arr[$i]; ?> : </td>

                                            <td width="70%" class="no_border"><?php echo $value_arr[$i]; ?></td>

                                        </tr>

                <?php
            }
            ?>

                                </table>

                            </td>

                            <td  class="no_border" width="30%">

                                <?php
                                if ($v->image != '') {
                                    ?>

                                                                                                                <!-- <img src="{{ asset('external/specification_image/'.$v->image) }}" height="250" width="200"> -->
                                    <img src="{{ base_path() }}/external/specification_image/{{$v->image}}" height="250" width="200">

                <?php
            }
            ?>

                            </td>

                        </tr>

            <?php
            if ($v->application != '') {
                ?>

                            <tr class="no_border">

                                <td colspan="2" class="no_border">

                                    <b><u>Application :- </u></b>

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
                    if ($cnt % 2 == 0) {

                        echo '<div><hr /></div>';
                    }

                    $cnt++;
                }
            }
        }
        ?>



    </div>

</body>

</html>

