<strong>Assumption College</strong><br>
{{$department}}<br>
@if($department == "College Department" or $department == "Senior High School")
{{$school_year}} - {{$period}}
@endif
@if($department == "Junior High School" or $department == "Elementary" or $department == "Pre School") 
{{$school_year}}
@endif

<h3>Set Up Summary</h3>
@if(count($ledgers)>0)
<?php $total=0; ?>
<?php $amount=0; ?>
<?php $discount=0; ?>
<table class="table table-condensed">
    <thead>
        <tr>
            <th>Accounting Code</th>
            <th>Accounting Name</th>
            <th>Amount</th>
            <th>Discount</th>
            <th>Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($ledgers as $ledger)
        <?php $amount = $amount + $ledger->amount; ?>
        <?php $discount = $discount + ($ledger->discount); ?>
        <?php $total = $total + ($ledger->amount-$ledger->discount); ?>
        <tr>
            <td>{{$ledger->accounting_code}}</td>
            <td>{{$ledger->subsidiary}}</td>
            <td align='right'>{{number_format($ledger->amount,2)}}</td>
            <td align='right'>{{number_format($ledger->discount,2)}}</td>
            <td align='right'>{{number_format($ledger->amount-$ledger->discount,2)}}</td>
        </tr>
        @endforeach
        @foreach($tuitions as $tuition)
        <?php $amount = $amount + $tuition->amount; ?>
        <?php $discount = $discount + ($tuition->discount); ?>
        <?php $total = $total + ($tuition->amount-$tuition->discount); ?>
        <tr>
            <td>{{$tuition->accounting_code}}</td>
            <td>{{$tuition->subsidiary}}</td>
            <td align='right'>{{number_format($tuition->amount,2)}}</td>
            <td align='right'>{{number_format($tuition->discount,2)}}</td>
            <td align='right'>{{number_format($tuition->amount-$tuition->discount,2)}}</td>
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
            <th colspan="2">Total</th>
            <td align='right'><strong>{{number_format($amount,2)}}</strong></td>
            <td align='right'><strong>{{number_format($discount,2)}}</strong></td>
            <td align='right'><strong>{{number_format($total,2)}}</strong></td>
        </tr>
    </tfoot>
</table>
@else
@endif

