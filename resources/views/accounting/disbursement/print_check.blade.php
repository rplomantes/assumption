<style>
    .tables, .td, .ths {
        border-collapse: collapse;
        border: 1px solid black;
        font-size:11pt;
    }
    .container-fluid{
        position:relative;
        font-family: Helvetica;
    }
    .payee_name{
        position: absolute; 
        right: 14cm; 
        top: 3.3cm; 
        font-size:13pt;
    }
    .date{
        position: absolute; 
        right: 2cm; 
        top: 3.5cm; 
        font-size:10pt;
    }
    .voucher_no{
        position: absolute; 
        right: 1.8cm; 
        top: 2.9cm; 
        font-size:10pt;
    }
    .remarks{
        position: absolute; 
        right: 13cm; 
        top: 4.0cm; 
        font-size:13pt;
    }
    .nameamount{
        position: absolute; 
        right: 6cm;
        left: 3.5cm;
        top:5.5cm; 
        font-size:10pt;
    }
    #upper{
        position: absolute; 
        top:7.7cm; 
        font-size:12pt;
        padding:15px;
    }
    .check_number{
        position: absolute; 
        right: 3.1cm;
        top:14.2cm; 
        font-size:10pt;
    }
</style>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page { size: 8.5inch 6.5inch; margin: 0px;  }
    </style>
</head>
<body>
    <div class="container-fluid">
        <p class="payee_name">{{$disbursement->payee_name}}</p>
        <p class="date">{{date('M d, Y',strtotime($disbursement->transaction_date))}}</p>
        <!--<p class="voucher_no">{{str_pad($disbursement->voucher_no,5,"0",STR_PAD_LEFT)}}</p>-->
        <p class="remarks">{{$disbursement->remarks}}</p>
        <p class="nameamount">{{getWords($disbursement->amount)}}</p>
        <p class="check_number">{{$disbursement->check_no}}</p>
    <table width="100%" id="upper">
        @foreach($accountings as $accounting)
        <tr>
            <td width="20%" style="text-align: center">{{$accounting->accounting_code}}</td>
            <td width="60%" style="text-align: center">{{$accounting->accounting_name}}</td>
            <td width="15%" style="text-align: right">@if($accounting->debit != 0){{number_format($accounting->debit,2)}} @endif</td>
            <td width="15%" style="text-align: right">@if($accounting->credit != 0){{number_format($accounting->credit,2)}} @endif</td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        @endforeach
    </table>
    </div>
</body>
<?php
function getWords($number) {
    $amount = number_format($number, 2, '.', ','); // put it in decimal format, rounded 
    $printnumber = convert_number($number);  //convert to words (see function above) 
    $x = $amount;
    $explode = explode('.', $x);   //separate the cents 
    $number_word = $printnumber . ' Pesos and ' . $explode[1] . ' Cents ***';
    echo $number_word;
}

function convert_number($number) {
    if (($number < 0) || ($number > 999999999)) {
        return "$number";
    }
    $Gn = floor($number / 1000000);  /* Millions (giga) */
    $number -= $Gn * 1000000;
    $kn = floor($number / 1000);     /* Thousands (kilo) */
    $number -= $kn * 1000;
    $Hn = floor($number / 100);      /* Hundreds (hecto) */
    $number -= $Hn * 100;
    $Dn = floor($number / 10);       /* Tens (deca) */
    $n = $number % 10;               /* Ones */

    $res = "";

    if ($Gn) {
        $res .= convert_number($Gn) . " Million";
    }

    if ($kn) {
        $res .= (empty($res) ? "" : " ") .
                convert_number($kn) . " Thousand";
    }

    if ($Hn) {
        $res .= (empty($res) ? "" : " ") .
                convert_number($Hn) . " Hundred";
    }

    $ones = array("", "One", "Two", "Three", "Four", "Five", "Six",
        "Seven", "Eight", "Nine", "Ten", "Eleven", "Twelve", "Thirteen",
        "Fourteen", "Fifteen", "Sixteen", "Seventeen", "Eightteen",
        "Nineteen");
    $tens = array("", "", "Twenty", "Thirty", "Fourty", "Fifty", "Sixty",
        "Seventy", "Eigthy", "Ninety");

    if ($Dn || $n) {
        if (!empty($res)) {
//            $res .= " and "; 
            $res .= " ";
        }

        if ($Dn < 2) {
            $res .= $ones[$Dn * 10 + $n];
        } else {
            $res .= $tens[$Dn];

            if ($n) {
                $res .= "-" . $ones[$n];
            }
        }
    }
    if (empty($res)) {
        $res = "zero";
    }
    return $res;
}
?>
