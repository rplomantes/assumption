@if(count($ledgers)>0)
<?php $total=0; ?>
<?php $amount=0; ?>
<?php $discount=0; ?>
<table>
    <tr><td><strong colspan="4">Assumption College</strong></td></tr>
    <tr><td colspan="4">{{$department}}</td></tr>
    <tr><td colspan="5">Set Up Summary</td></tr>
    @if($department == "College Department" or $department == "Senior High School")
    <tr><td colspan="5"><h5>{{$school_year}} - {{$period}}</h5>
    @endif
    @if($department == "Junior High School" or $department == "Elementary" or $department == "Pre School")
    <tr><td colspan="5"><h5>{{$school_year}}</h5>
    @endif
</td></tr>
    <tr></tr>
    <thead>
        <tr>
            <th style='border-bottom: 1px solid black'>Accounting Code</th>
            <th style='border-bottom: 1px solid black'>Accounting Name</th>
            <th style='border-bottom: 1px solid black' align='right'>Amount</th>
            <th style='border-bottom: 1px solid black' align='right'>Discount</th>
            <th style='border-bottom: 1px solid black' align='right'>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($ledgers as $ledger)
        <?php $amount = $amount + $ledger->amount; ?>
        <?php $discount = $discount + $ledger->discount; ?>
        <?php $total = $total + $ledger->amount-$ledger->discount; ?>
        <tr>
            <td>{{$ledger->accounting_code}}</td>
            <td>{{$ledger->subsidiary}}</td>
            <td align='right'>{{$ledger->amount}}</td>
            <td align='right'>{{$ledger->discount}}</td>
            <td align='right'>{{$ledger->amount-$ledger->discount}}</td>
        </tr>
        @endforeach
        @foreach($tuitions as $tuition)
        <?php $amount = $amount + $tuition->amount; ?>
        <?php $discount = $discount + ($tuition->discount); ?>
        <?php $total = $total + ($tuition->amount-$tuition->discount); ?>
        <tr>
            <td>{{$tuition->accounting_code}}</td>
            <td>{{$tuition->subsidiary}}</td>
            <td align='right'>{{$tuition->amount}}</td>
            <td align='right'>{{$tuition->discount}}</td>
            <td align='right'>{{$tuition->amount-$tuition->discount}}</td>
        </tr>
        @endforeach
        @if(count($srfs)>0)
        @foreach($srfs as $srf)
        <?php $amount = $amount + $srf->amount; ?>
        <?php $discount = $discount + ($srf->discount); ?>
        <?php $total = $total + ($srf->amount-$srf->discount); ?>
        <tr>
            <td>{{$srf->accounting_code}}</td>
            <td>{{$srf->category}}</td>
            <td align='right'>{{number_format($srf->amount,2)}}</td>
            <td align='right'>{{number_format($srf->discount,2)}}</td>
            <td align='right'>{{number_format($srf->amount-$srf->discount,2)}}</td>
        </tr>
        @endforeach
        @endif
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2" style='border-top: 1px solid black'>Total</th>
            <td align='right' style='border-top: 1px solid black'><strong>{{$amount}}</strong></td>
            <td align='right' style='border-top: 1px solid black'><strong>{{$discount}}</strong></td>
            <td align='right' style='border-top: 1px solid black'><strong>{{$total}}</strong></td>
        </tr>
    </tfoot>
</table>
@else
@endif