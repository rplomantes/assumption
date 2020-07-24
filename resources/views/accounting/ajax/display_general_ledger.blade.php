<?php $x = $total_credit = $total_debit = $balance = $account_type = 0;
    if ($account->category == "Assets" || $account->category == "Expenses") {
        $account_type = 0;
    } else {
        $account_type = 1;
    }
    ?>
<h4>Account Code: {{$account->accounting_code}}</h4>
<h4>Account Name: {{$account->accounting_name}}</h4>
@if($finalStartDate == $finalEndDate)
<small style="font-size:11pt;">Date Covered : {{date_format(date_create($finalStartDate),"F d, Y")}}</small><br>
@else
<small style="font-size:11pt;">Date Covered : {{date_format(date_create($finalStartDate),"F d, Y")}} - {{date_format(date_create($finalEndDate),"F d, Y")}}</small><br>
@endif
<a class="btn btn-sm btn-primary" href="{{url("print/general_ledger",array($account->accounting_code,$finalStartDate,$finalEndDate))}}"><b><span class="fa fa-print"></span> Print</b></a>
<br>
<table class="table table-condensed col-md-6">
    <thead>
        <tr>
            <th></th>
            <th>Date</th>
            <th class="col-sm-3" >Particulars</th>
            <th>Type</th>
            <th>Post. Ref</th>
            <th style="text-align:right">Debit</th>
            <th style="text-align:right">Credit</th>
            <th style="text-align:right">Balance</th>
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
            <td><a href="{{url('cashier',array('viewreceipt',$list->reference_id))}}">{{$list->reference_id}}</a></td>
            @elseif($list->accounting_type ==  env('DEBIT_MEMO'))
            <td>DM</td>
            <td><a href="{{url('view_debit_memo',array($list->reference_id))}}">{{$list->reference_id}}</a></td>
            @elseif($list->accounting_type ==  env('DISBURSEMENT'))
            <td>D</td>
            <td><a href="{{url('view/disbursement',array($list->reference_id))}}">{{$list->reference_id}}</a></td>
            @elseif($list->accounting_type ==  env('JOURNAL'))
            <td>JV</td>
            <td><a href="{{url('view/journal_voucher',array($list->reference_id))}}">{{$list->reference_id}}</a></td>
            @else
            <td>BG</td>
            <td>&nbsp;</td>
            @endif
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