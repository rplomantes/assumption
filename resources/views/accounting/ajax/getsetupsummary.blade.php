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
<table class="table table-condensed">
    <thead>
        <tr>
            <th>Accounting Code</th>
            <th>Accounting Name</th>
            <th>Amount</th>
        </tr>
    </thead>
    <tbody>
        @foreach($ledgers as $ledger)
        <?php $total = $total + $ledger->amount; ?>
        <tr>
            <td>{{$ledger->accounting_code}}</td>
            <td>{{$ledger->subsidiary}}</td>
            <td align='right'>{{number_format($ledger->amount,2)}}</td>
        </tr>
        @endforeach
        @foreach($tuitions as $tuition)
        <?php $total = $total + $tuition->amount; ?>
        <tr>
            <td>{{$tuition->accounting_code}}</td>
            <td>{{$tuition->subsidiary}}</td>
            <td align='right'>{{number_format($tuition->amount,2)}}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="2">Total</th>
            <td align='right'><strong>{{number_format($total,2)}}</strong></td>
        </tr>
    </tfoot>
</table>
@else
@endif

