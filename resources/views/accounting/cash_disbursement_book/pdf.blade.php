<head>
    <style>
        td{
            border-collapse: collapse;
            border: 1px solid black;
        }
        th{
            border-collapse: collapse;
            border: 1px solid black;
            text-align:center
        }
        .tables, .tds, .ths {
            border-collapse: collapse;
            border: 1px solid black;
            font-size:10pt;
        }
        body{
            margin:0px auto;
            padding:11px;
            font-size: 11pt;
        }
        small{
            font-size:9pt;
        }
    </style>
</head>

<div class="col-md-12" align='left'>
    <b style="font-size:14pt">ASSUMPTION COLLEGE</b><br>
    <small style="margin-top: 0px;">San Lorenzo Drive, San Lorenzo Village Makati City
        <br>{{$date_from}} - {{$date_to}}<br>Page {{$accountings->currentPage()}} of {{$accountings->lastPage()}}
    </small><br/>
    </br>
</div>

<table class="table" cellspacing="0" cellpadding="2" style="width:100%; font-size:7pt;" border="1">
    <thead>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th>CIB-BPI 1811000716</th>
            <th>PURCHASES</th>
            <th>REPAIRS AND MAINTENANCE</th>
            <th>A/R EMPLOYEE ADVANCES</th>
            <th>OFFICE SUPPLIES</th>
            <th>LIBRARY EXP</th>
            <th>CASH IN BANK - PASONG TAMO</th>
            <th>CASH IN BANK - BPI PAYROLL</th>
            <th>W/T EXP</th>
            <th>W/T COMP</th>
            <th colspan="3" style="text-align:center">SUNDRIES</th>
        </tr>
        <tr>
            <th>DATE</th>
            <th>PAYEE</th>
            <th>PARTICULARS</th>
            <th>CV#</th>
            <th>CHECKNO</th>
            <th>CR</th>
            <th>DR</th>
            <th>DR</th>
            <th>DR</th>
            <th>DR</th>
            <th>DR</th>
            <th>CR</th>
            <th>CR</th>
            <th>CR</th>
            <th>CR</th>
            <th>ACCT TITLE</th>
            <th>DEBIT</th>
            <th>CREDIT</th>
        </tr>
    </thead>
    <tbody>
        @foreach($accountings->unique("reference_id") as $reference)
        <?php $disbursement = \App\Disbursement::where("reference_id", $reference->reference_id)->first(); ?>
        <tr>
            <td>{{date("d/m/y", strtotime($disbursement->transaction_date))}}</td>
            <td>{{strtoupper($disbursement->payee_name)}}</td>
            <td>{{strtoupper($disbursement->remarks)}}</td>
            <td>{{$disbursement->voucher_no}}</td>
            <td>{{$disbursement->check_no}}</td>
            <td>
                @if($accountings->where("reference_id", $reference->reference_id)->where("accounting_code","1046")->sum("credit") > 0)
                {{number_format($accountings->where("reference_id", $reference->reference_id)->where("accounting_code","1046")->sum("credit"),2)}}</td>
                @endif                                
            <td>
                @if($accountings->where("reference_id", $reference->reference_id)->whereIn("accounting_code",["7892","7893"])->sum("debit") > 0)
                {{number_format($accountings->where("reference_id", $reference->reference_id)->where("accounting_code",["7892","7893"])->sum("debit"),2)}}
                @endif
            </td>
            <td>
                @if($accountings->where("reference_id", $reference->reference_id)->where("accounting_code","7331")->sum("debit") > 0)
                {{number_format($accountings->where("reference_id", $reference->reference_id)->where("accounting_code","7331")->sum("debit"),2)}}
                @endif
            </td>
            <td>

            </td>
            <td>
                @if($accountings->where("reference_id", $reference->reference_id)->where("accounting_code","7311")->sum("debit") > 0)
                {{number_format($accountings->where("reference_id", $reference->reference_id)->where("accounting_code","7311")->sum("debit"),2)}}
                @endif
            </td>
            <td>
                @if($accountings->where("reference_id", $reference->reference_id)->where("accounting_code","7127")->sum("debit") > 0)
                {{number_format($accountings->where("reference_id", $reference->reference_id)->where("accounting_code","7127")->sum("debit"),2)}}
                @endif    
            </td>
            <td></td>
            <td>
                @if($accountings->where("reference_id", $reference->reference_id)->where("accounting_code","1061")->sum("credit") > 0)
                {{number_format($accountings->where("reference_id", $reference->reference_id)->where("accounting_code","1061")->sum("credit"),2)}}
                @endif
            </td>
            <td>
                @if($accountings->where("reference_id", $reference->reference_id)->where("accounting_code","2311")->sum("credit") > 0)
                {{number_format($accountings->where("reference_id", $reference->reference_id)->where("accounting_code","2311")->sum("credit"),2)}}
                @endif
            </td>
            <td>
                @if($accountings->where("reference_id", $reference->reference_id)->where("accounting_code","2301")->sum("credit") > 0)
                {{number_format($accountings->where("reference_id", $reference->reference_id)->where("accounting_code","2301")->sum("credit"),2)}}
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

