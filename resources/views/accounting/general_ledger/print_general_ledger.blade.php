<?php $x = $total_credit = $total_debit = $balance = $account_type =  0; ?>
<style>   
    th {
        border-collapse: collapse;
        border-bottom: 2px solid black;
    }
    td{
        border-collapse: collapse;
        border-bottom: 1px solid #696969;
    }
    .page_break { 
        page-break-before: always;
    }
    .header_image 
    {
        position: absolute;
        bottom: 898px;
        right: 0;
        left: -350;
        z-index: -1;
    }
</style>
<div align="left">
    <strong>Assumption College</strong><br>
    <small>San Lorenzo Drive, San Lorenzo Village Makati City</small><br><br>
    <small style="font-size:12pt;"><strong>General Ledger</strong></small><br>
</div>
<br>
<strong>Account Code: {{$account->accounting_code}}<br>
    Account Name: {{$account->accounting_name}}<br>
    <?php
    if ($account->category == "Assets" || $account->category == "Expenses") {
        $account_type = 0;
    } else {
        $account_type = 1;
    }
    ?>
    @if($finalStartDate == $finalEndDate)
    <small style="font-size:10pt;">Date Covered : {{date_format(date_create($finalStartDate),"M d, Y")}}</small><br>
    @else
    <small style="font-size:10pt;">Date Covered : {{date_format(date_create($finalStartDate),"M d, Y")}} - {{date_format(date_create($finalEndDate),"M d, Y")}}</small><br>
    @endif
</strong>
<br>
<div style='width:100%;font-size:10pt;'>
    <div id='table' style='width:100%'>
        <table cellspacing="1" cellpadding="2" class="table table-responsive table-striped" style='width:100%'>

            <thead>
                <tr>
                    <th width="3%"></th>
                    <th width="15%">Date</th>
                    <th width="30%">Particulars</th>
                    <th width="10%">Type</th>
                    <th width="10%">Post. Ref</th>
                    <th width="10%" style="text-align:right">Debit</th>
                    <th width="10%" style="text-align:right">Credit</th>
                    <th width="12%" style="text-align:right">Balance</th>
                </tr>
            </thead>
            <tbody>
                @foreach($entries as $list)

                <?php
                if ($account_type == 0) {
                    if ($list->debit > 0) {
                        $balance += $list->debit;
                    } else {
                        $balance -= $list->credit;
                    }
                } else {
                    if ($list->debit > 0) {
                        $balance -= $list->debit;
                    } else {
                        $balance += $list->credit;
                    }
                }
                ?>
                <tr>
                    <td> {{++$x}} </td>
                    <td>{{$list->transaction_date}}</td>
                    <td>{{$list->description}} {{$list->particular}}&nbsp;</td>
                    @if($list->accounting_type == env('CASH'))
                    <td>CR</td>
                    @elseif($list->accounting_type ==  env('DEBIT_MEMO'))
                    <td>DM</td>
                    @elseif($list->accounting_type ==  env('DISBURSEMENT'))
                    <td>D</td>
                    @elseif($list->accounting_type ==  env('JOURNAL'))
                    <td>JV</td>
                    @else
                    <td>BG</td>
                    <td>&nbsp;</td>
                            @endif
                    
                    <td>{{$list->reference_id}}</td>
                    <td align="right">{{number_format(abs($list->debit),2)}}&nbsp;</td>
                    <td align="right">{{number_format(abs($list->credit),2)}}&nbsp;</td>
                    <td align="right">{{number_format($balance,2)}}&nbsp;</td>
                    <?php
                    $total_credit += abs($list->credit);
                    $total_debit += abs($list->debit);
                    ?>
                </tr>
                @endforeach
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><b>TOTAL</b> </td>
                    <td align="right" ><b>{{number_format($total_debit,2)}}&nbsp;</b></td>
                    <td align="right" ><b>{{number_format($total_credit,2)}}&nbsp;</b></td>
                    @if($account_type == 0 )
                    <td align="right" ><b>{{number_format(abs($total_debit-$total_credit),2)}}&nbsp;</b></td>
                    @else
                    <td align="right" ><b>{{number_format(abs($total_credit-$total_debit),2)}}&nbsp;</b></td>
                    @endif
                </tr>
            </tbody>
        </table>
    </div>
</div>
<br><br>
    @if (Auth::user()->accesslevel == env('ACCTNG_STAFF'))
    Prepared by:<br><br>
    <strong>{{Auth::user()->lastname}}, {{Auth::user()->firstname}}</strong><br>
    Accounting Staff
    @else (Auth::user()->accesslevel == env('ACCTNG_STAFF'))
    Prepared by:<br><br>
    <strong>{{Auth::user()->lastname}}, {{Auth::user()->firstname}}</strong><br>
    Accounting Head
    @endif
<br><small style="font-size:7pt;">{{date("m/d/Y H:m:s")}}</small>
</div>