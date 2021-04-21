<table class="table" cellspacing="2" cellpadding="0" style="width:100%" border="1">
    <tr>
        <th>ASSUMPTION COLLEGE</th>
    </tr>
    <tr>
        <th>GENERAL JOURNAL BOOK</th>
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
            <th>A/R OTHERS</th>
            <th>A/P OTHERS</th>
            <th>A/P DEP FEES</th>
            <th>A/R ADVANCES</th>
            <th>A/R 3RD PARTY FOR EMP</th>
            <th colspan="3" style="text-align:center">SUNDRIES</th>
        </tr>
        <tr>
            <th>DATE</th>
            <th>CV#</th>
            <th>PARTICULARS</th>
            <th>DR</th>
            <th>DR</th>
            <th>DR</th>
            <th>CR</th>
            <th>CR</th>
            <th>ACCT TITLE</th>
            <th>DEBIT</th>
            <th>CREDIT</th>
        </tr>
    </thead>
    <tbody>
        @foreach($accountings->unique("reference_id") as $reference)
        <?php $journal = \App\JournalEntry::where("reference_id", $reference->reference_id)->first(); ?>
        <tr>
            <td>{{date("d/m/y", strtotime($journal->transaction_date))}}</td>
            <td>{{$journal->voucher_no}}</td>
            <td>{{strtoupper($journal->particular)}}</td>
            <td>
                @if($accountings->where("reference_id", $reference->reference_id)->where("accounting_code","1231")->sum("debit") > 0)
                {{number_format($accountings->where("reference_id", $reference->reference_id)->where("accounting_code","1231")->sum("debit"),2)}}</td>
                @endif                                
            <td>
                @if($accountings->where("reference_id", $reference->reference_id)->where("accounting_code","2031")->sum("debit") > 0)
                {{number_format($accountings->where("reference_id", $reference->reference_id)->where("accounting_code","2031")->sum("debit"),2)}}
                @endif
            </td>
            <td>
                @if($accountings->where("reference_id", $reference->reference_id)->where("accounting_code","2011")->sum("debit") > 0)
                {{number_format($accountings->where("reference_id", $reference->reference_id)->where("accounting_code","2011")->sum("debit"),2)}}
                @endif
            </td>
            <td>

            </td>
            <td>
                @if($accountings->where("reference_id", $reference->reference_id)->where("accounting_code","1225")->sum("credit") > 0)
                {{number_format($accountings->where("reference_id", $reference->reference_id)->where("accounting_code","1225")->sum("credit"),2)}}
                @endif
            </td>
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

