@if(count($ledgers)>0)
<?php $total=0; ?>
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
        </tr>
    </thead>
    <tbody>
        @foreach($ledgers as $ledger)
        <?php $total = $total + $ledger->amount; ?>
        <tr>
            <td>{{$ledger->accounting_code}}</td>
            <td>{{$ledger->subsidiary}}</td>
            <td align='right'>{{$ledger->amount}}</td>
        </tr>
        @endforeach
        @foreach($tuitions as $tuition)
        <?php $total = $total + $tuition->amount; ?>
        <tr>
            <td>{{$tuition->accounting_code}}</td>
            <td>{{$tuition->subsidiary}}</td>
            <td align='right'>{{$tuition->amount}}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2" style='border-top: 1px solid black'>Total</th>
            <td align='right' style='border-top: 1px solid black'><strong>{{$total}}</strong></td>
        </tr>
    </tfoot>
</table>
@else
@endif