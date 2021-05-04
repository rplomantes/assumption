<table class="table" cellspacing="2" cellpadding="0" style="width:100%" border="1">
    <tr>
        <th>ASSUMPTION COLLEGE</th>
    </tr>
    <tr>
        <th>CASH RECEIPT BOOK</th>
    </tr>
    <tr>
        <th>{{$date_from}} - {{$date_to}}</th>
    </tr>
    <tr>
        <th>Page {{$accountings->currentPage()}} of {{$accountings->lastPage()}}</th>
    </tr>
</table>

<table class="table" cellspacing="2" cellpadding="0" style="width:100%" border="1">
    <thead>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            @foreach($accounting_codes as $accounting_code)
            <th>{{$accounting_code->accountingName()}}</th>
            @endforeach
            <th colspan="3" style="text-align:center">SUNDRIES</th>
        </tr>
        <tr>
            <th>DATE</th>
            <th>RECEIVED FROM</th>
            <th>PARTICULARS</th>
            <th>OR#</th>
            @foreach($accounting_codes as $accounting_code)
            <th>{{strtoupper($accounting_code->debit_or_credit)}}</th>
            @endforeach
            <th>ACCT TITLE</th>
            <th>DEBIT</th>
            <th>CREDIT</th>
        </tr>
    </thead>
    <tbody>
        @foreach($accountings->unique("reference_id") as $reference)
        <?php $payment = \App\Payment::where("reference_id", $reference->reference_id)->first(); ?>
        <tr>
            <td>{{date("d/m/y", strtotime($payment->transaction_date))}}</td>
            <td>{{strtoupper($payment->paid_by)}}</td>
            <td>{{strtoupper($payment->remarks)}}</td>
            <td>{{$payment->receipt_no}}</td>
            @foreach($accounting_codes as $accounting_code)
            <td>
                @if($accountings->where("reference_id", $reference->reference_id)->where("accounting_code","$accounting_code->accounting_code")->sum("$accounting_code->debit_or_credit") > 0)
                {{number_format($accountings->where("reference_id", $reference->reference_id)->where("accounting_code",$accounting_code->accounting_code)->sum($accounting_code->debit_or_credit),2)}}
                @endif 
            </td>
            @endforeach
            <td>
                @foreach($sundries->where("reference_id", $reference->reference_id)->unique("accounting_name") as $sundry)
                {{$sundry->accounting_name}}<br>
                @endforeach
            </td>
            <td>
                @foreach($sundries->where("reference_id", $reference->reference_id)->unique("accounting_name") as $sundry)
                    {{number_format($sundries->where("reference_id", $reference->reference_id)->where("accounting_name", $sundry->accounting_name)->sum("debit"),2)}}<br>
                @endforeach
            </td>
            <td>
                @foreach($sundries->where("reference_id", $reference->reference_id)->unique("accounting_name") as $sundry)
                {{number_format($sundries->where("reference_id", $reference->reference_id)->where("accounting_name", $sundry->accounting_name)->sum("credit"),2)}}<br>
                @endforeach
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

