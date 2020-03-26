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
        <h4 class="display">Summary of Journal Entries<br><small>Date Covered: {{date_format(date_create($finalStartDate),"M d, Y")}} - {{date_format(date_create($finalEndDate),"M d, Y")}}</small></h4>
        <table width="100%" class="tables" cellpadding="2">
            <thead>
                <tr>
                    <th class="col-md-2">Date</th>
                    <th class="col-md-3">Particular</th>
                    <th class="col-md-2">Voucher No.</th>
                    <th class="col-md-2">Debit</th>
                    <th class="col-md-2">Credit</th>
                    <th class="col-md-2">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lists as $list)
                <?php $entry = \App\Accounting::selectRaw("sum(debit) as debit, sum(credit) as credit")
                            ->where('reference_id',$list->reference_id)->groupBy('reference_id')->first();?>
                    <tr>
                        <td>{{date_format(date_create($list->transaction_date),"M d, Y")}}</td>
                        <td>{{$list->particular}}</td>
                        <td>{{str_pad($list->voucher_no,4,"0",STR_PAD_LEFT)}}</td>
                        <td>{{number_format($entry->debit,2)}}</td>
                        <td>{{number_format($entry->credit,2)}}</td>
                        <td>@if($list->is_reverse == 1) Cancelled @else OK @endif</td>
                    </tr>
                @endforeach
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
