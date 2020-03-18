<head>
    <style>
        td{
            border-collapse: collapse;
            border: 1px solid black;
        }
        th{
            border-collapse: collapse;
            border: 1px solid black;
            text-align:center
        }
        .tables, .tds, .ths {
            border-collapse: collapse;
            border: 1px solid black;
            font-size:10pt;
        }
        body{
            margin:0px auto;
            padding:11px;
            font-size: 11pt;
        }
        small{
            font-size:9pt;
        }
    </style>
</head>
<?php $grandtotal = 0;?>
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
        <h4 class="display">Check Disbursement Summary<br><small>Date Covered: {{date_format(date_create($startDate),"F d, Y")}} - {{date_format(date_create($dateEnd),"F d, Y")}}</small></h4>
        <table width="100%" class="tables" cellpadding="2">
            <thead>
                <tr>
                    <th class="col-md-2">Date</th>
                    <th class="col-md-2">Voucher No.</th>
                    <th class="col-md-2">Check No.</th>
                    <th class="col-md-2">Payee Name</th>
                    <th class="col-md-3">Remarks</th>
                    <th class="col-md-2">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lists as $list)
                <tr>
                    <td>{{date_format(date_create($list->transaction_date),"F d, Y")}}</td>
                    <td style="text-align: center">{{str_pad($list->voucher_no,4,"0",STR_PAD_LEFT)}}</td>
                    <td style="text-align: center">{{$list->check_no}}</td>
                    <td>{{$list->payee_name}}</td>
                    <td>{{$list->remarks}}</td>
                    <td style="text-align: right">{{number_format($list->amount,2)}}</td>
                    <?php $grandtotal += $list->amount;?>
                </tr>
                @endforeach
                <tr><td style="text-align: right" colspan="5"><b>GRAND TOTAL</b></td><td style="text-align: right">{{number_format($grandtotal,2)}}</td></tr>
            </tbody>
        </table>
    </div>
    <br><br>
    @if (Auth::user()->accesslevel == '4')
Prepared by:<br><br>
<strong>{{Auth::user()->lastname}}, {{Auth::user()->firstname}}</strong><br>
Accounting Staff - Cashier
@else (Auth::user()->accesslevel == '5')
Prepared by:<br><br>
<strong>{{Auth::user()->lastname}}, {{Auth::user()->firstname}}</strong><br>
Accounting Manager
@endif
<br><small style="font-size:7pt;">{{date("m/d/Y H:m:s")}}</small>
</body>
