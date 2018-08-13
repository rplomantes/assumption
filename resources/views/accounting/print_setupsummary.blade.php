<style>
    
        body {
            font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
            font-size: 10pt;
        }
</style>

<strong>Assumption College</strong><br>
{{$department}}<br>
{{$school_year}} {{$period}}
<h3>Set Up Summary</h3>

@if(count($ledgers)>0)
<?php $total=0; ?>
<table width='100%' cellpadding='0' cellspacing='0'>
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
            <th colspan="2" style='border-top: 1px solid black'>Total</th>
            <td align='right' style='border-top: 1px solid black'><strong>{{number_format($total,2)}}</strong></td>
        </tr>
    </tfoot>
</table>
<br><br>
Run Date: {{date('Y-m-d H:i:s')}}<br><br><br>

Prepared by:<br><br>
<strong>{{Auth::user()->lastname}}, {{Auth::user()->firstname}}</strong>
@else
@endif