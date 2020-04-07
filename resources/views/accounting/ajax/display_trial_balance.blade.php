<?php $x = $total_credit = $total_debit = 0; ?>
<table class="table table-condensed col-md-6">
    <thead>
        <tr>
            <th></th>
            <th class="col-sm-3">Account Code</th>
            <th class="col-sm-3">Account Name</th>
            <th class="col-sm-3" style="text-align:right">Debit</th>
            <th class="col-sm-3" style="text-align:right">Credit</th>
        </tr>
    </thead>
    <tbody>
        @foreach($lists as $list)
        <tr>
            <td> {{++$x}} </td>
            <td>{{$list->accounting_code}}</td>
            <td><a target="_blank" href="{{url('general_ledger',array($list->accounting_code,$finalStartDate,$finalEndDate))}}">{{$list->accounting_name}}</a></td>
            <td align="right">{{number_format(abs($list->debit),2)}}&nbsp;</td>
            <td align="right">{{number_format(abs($list->credit),2)}}&nbsp;</td>
            <?php 
                  $total_credit += abs($list->credit);
                  $total_debit  += abs($list->debit);
            ?>
        </tr>
        @endforeach
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><b>TOTAL</b> </td>
            <td align="right" ><b>{{number_format($total_debit,2)}}&nbsp;</b></td>
            <td align="right" ><b>{{number_format($total_credit,2)}}&nbsp;</b></td>
        </tr>
    </tbody>
</table>