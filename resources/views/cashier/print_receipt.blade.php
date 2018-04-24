<html>
    <head>
        <style>
            body {
                font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
                font-size: 10pt;
            }
        </style>
    </head>
    <body>
        <table width="311" border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td align="right">{{$payment->receipt_no}}</td>
        </tr>
    </table><br><br><br>
    <table width='298' border="0" cellpadding="0" cellspacing="0">
        <tr>
            <td width="10">ID No:</td>
            <td>&nbsp;{{$payment->idno}}</td>
            <td align='right'>Date:</td>
            <td align="right">{{date('M d, Y',strtotime($payment->transaction_date))}}</td>
        </tr>
        <tr>
            <td>Name:</td>
            <td colspan="3">&nbsp;{{strtoupper($payment->paid_by)}}</td>
        </tr>
        @if(count($status)>0)
        @if($status->status==3)
            @if($status->academic_type=="College")
            <tr><td colspan="2">Course/Level: {{$status->program_code}} / {{$status->level}} - {{$status->department}}</td></tr>
            @else
            <tr><td colspan="4">Level/Section: {{$status->level}} - {{$status->department}}</td></tr>
            @endif
        @endif
        @endif
    </table>


    <hr>


    <?php $totalreceipt = 0; ?>
    <table width='311' border="0" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <td>Particular</td>
                <td align='right'>Amount</td>
            </tr>
        </thead>
        <tbody>
            @if(count($receipt_details)>0)
            @foreach($receipt_details as $receipt_detail)
            <?php $totalreceipt = $totalreceipt + $receipt_detail->credit; ?>
            <tr>
                <td>{{$receipt_detail->receipt_details}}</td>
                <td align="right">{{number_format($receipt_detail->credit,2)}}</td>
            </tr>
            @endforeach
            @endif
            @if(count($receipt_less)>0)
            <tr>
                <td colspan="2">Less:</td>
            </tr>
            @foreach($receipt_less as $less)
            <?php $totalreceipt = $totalreceipt - $less->debit; ?>
            <tr>
                <td>{{$less->receipt_details}}</td>
                <td>({{number_format($less->debit,2)}})</td>
            </tr>
            @endforeach
            @endif
            <tr>
                <td>Total Amount</td>
                <td align="right"><span class="totalreceipt">{{number_format($totalreceipt,2)}}</span></td>
            <tr>
        </tbody>    
    </table>

    <hr>

    <p class="text-muted well well-sm no-shadow">
        Explanation:<br>{{$payment->remarks}}
    </p>
    <hr>
    <p class="text-muted well well-sm no-shadow">
        Payment Rendered:<br>  
        @if($payment->cash_amount>0) 
        Cash Received : {{number_format($payment->amount_received,2)}}<br>
        Change : {{number_format($payment->amount_received-$payment->cash_amount,2)}}<br>
        @endif
        @if($payment->check_amount>0)
        Bank : {{$payment->bank_name}}<br>
        Check No : {{$payment->check_number}}<br>
        Check Amount : {{number_format($payment->check_amount)}}<br>
        @endif
        @if($payment->credit_card_amount>0)
        Credit Card : {{$payment->credit_card_bank}} {{$payment->credit_card_type}}<br>
        Credit Card No : {{substr_replace($payment->credit_card_number,"***********",0,12)}}<br>
        Approval No : {{$payment->approval_number}}<br>
        Amount : {{number_format($payment->credit_card_amount,2)}}<br>
        @endif
        @if($payment->deposit_amount>0)
        Deposit Ref : {{$payment->deposit_reference}}<br>
        Deposit Amount : {{number_format($payment->deposit_amount,2)}}<br>
        @endif
    </p>

    <p style="position: fixed; bottom: 60px; margin-top: 15px; margin-right: 70px ;" align="right">
        {{\App\User::where('idno',$payment->posted_by)->first()->firstname}} {{\App\User::where('idno',$payment->posted_by)->first()->lastname}}
    </p>
    </body>
</html>
