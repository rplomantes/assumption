<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page { size: 8.5inch 6.5inch; margin: 0px;  }
    </style>
    <style>
    .title-head{
        background-color: black;
        color:white;
        text-align: center;
        font-size:15pt;
        font-weight: bold;
    }
    #lower{
        font-size:10pt;
        border-top:none;
    }
    td{
        border-collapse: collapse;
        border: 1px solid black;
    }
    .tables, .tds, .ths {
        border-collapse: collapse;
        border: 1px solid black;
    }
    .entry{
        border-collapse: collapse;
        border: none;
        border-left: 1px solid black;
        border-right: 1px solid black;
        font-size:11pt;
    }
    body{
        margin:0px auto;
        padding:20px;
        padding-top:30px;
        font-family: Helvetica;
    }
</style>
</head>
<body>
<div class="container-fluid">
    <center>
        <div class="col-md-12">
                        <b style="font-size:14pt">ASSUMPTION COLLEGE</b><br>
                        <small style="margin-top: 0px;">San Lorenzo Drive, San Lorenzo Village Makati City</small><br/>
            </br>
        </div>
    </center>
    <br>
    <form class="form form-horizontal">
        <table width="100%" cellpadding="2" class="tables" id="upper">
            <tr>
                <td class="title-head" colspan="4">CHECK VOUCHER</td>
            </tr>
            <tr>
                <td width="70%" colspan="2" rowspan="2">PAY TO:<br>{{$disbursement->payee_name}}</td>
                <td width="30%" colspan="2">C.V. No.: &nbsp;&nbsp;&nbsp;&nbsp;{{str_pad($disbursement->voucher_no,5,"0",STR_PAD_LEFT)}}</td>
            </tr>
            <tr>
                <td colspan="2">Date: &nbsp;&nbsp;&nbsp;&nbsp;{{date_format(date_create($disbursement->transaction_date),"F d, Y")}}</td>
            </tr>
            <tr>
                <td colspan="4">TO COVER:<br>{{$disbursement->remarks}}</td> 
            </tr>
            <tr>
                <td style="font-size:10pt;" colspan="2" width="50%">Amount in words: {{getWords($disbursement->amount)}}</td> 
                <td style="text-align: center" colspan="2" width="25%">AMOUNT</td>
            </tr>
            <tr>
                <th width="15%" style="border: 1px solid black;text-align: center">ACCOUNT NO.</th>
                <th width="55%" style="border: 1px solid black;text-align: center">ACCOUNT TITLE</th>
                <th width="15%" style="border: 1px solid black;text-align: center">DEBIT</th>
                <th width="15%" style="border: 1px solid black;text-align: center">CREDIT</th>
            </tr>
            @foreach($accountings as $accounting)
            <tr>
                <td class="entry">{{$accounting->accounting_code}}</td>
                <td class="entry">{{$accounting->category}}</td>
                <td class="entry" style="text-align: right">@if($accounting->debit != 0){{number_format($accounting->debit,2)}} @endif</td>
                <td class="entry" style="text-align: right">@if($accounting->credit != 0){{number_format($accounting->credit,2)}} @endif</td>
            </tr>
            @endforeach
        </table><br>
        <table id="lower" width="100%" cellpadding="1" class="tables" style="padding-top:-1px">
            <tr>
                <td width="29%">Prepared By:<br><br>&nbsp;</td>
                <td width="29%">Checked By:<br><br>&nbsp;</td>
                <td width="42%" colspan="3" rowspan="2">&nbsp;Received By:<br><br>
                    &nbsp;&nbsp;<u><span style="color:white;">&nbsp;&nbsp;WHITE COLOR HERE&nbsp;&nbsp;&nbsp;&nbsp;</span></u>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <u><span style="color:white;">JULY 23, 2019</span></u>
                    <br>&nbsp;&nbsp;&nbsp;&nbsp;Signature over printed name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <span style="text-align:right">Date Received</span>
                    <br><br><center>Check No.: <u>&nbsp;&nbsp;{{$disbursement->check_no}}&nbsp;&nbsp;</u></center>
                </td>
            </tr>
            <tr>
                <td>Posted By:<br><br>&nbsp;</td>
                <td>Approved By:<br><br>&nbsp;</td>
            </tr>
        </table>
</div>
</form>
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