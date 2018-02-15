<div style="margin-left: 20px; margin-right: 15px;">
    <table width="100%" style="margin-top: 10px; margin-right: 30px">
        <tr>
            <td align="right"><b>{{$payment->receipt_no}}</b></td>
        </tr>
    </table>
    <table width='100%' style='font-size: 10pt; margin-top: 30px;' border='0'>
        <tr>
            <th width='5%'>ID No:</th>
            <th width='20%'>{{$payment->idno}}</th>
            <th width='5%' align='right'>Date:</th>
            <td width='8%' align="right">{{date('M d, Y',strtotime($payment->transaction_date))}}</td>
        </tr>
        <tr>
            <th>Name:</th>
            <td colspan="3"><b>{{strtoupper($payment->paid_by)}}</b></td>
        </tr>
    </table>


    <hr>


    <?php $totalreceipt = 0; ?>
    <table width="100%" style="font-size: 10pt;">
        <thead>
            <tr>
                <th>Particular</th>
                <th width='30%' align='right'>Amount</th>
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
                <th>TOTAL AMOUNT</th>
                <th align="right"><span class="totalreceipt">{{number_format($totalreceipt,2)}}</span></th>
            <tr>
        </tbody>    
    </table>

    <hr>

    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px; font-size: 10pt;">
        <b>Explanation:</b><br>{{$payment->remarks}}
    </p>
    <hr>
    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px; font-size: 10pt;">
        <b>Payment Rendered:</b><br>  
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

    <p style="position: fixed; bottom: 60px; margin-top: 15px; margin-right: 70px ;font-size: 10pt;" align="right">
        <b>{{\App\User::where('idno',$payment->posted_by)->first()->firstname}} {{\App\User::where('idno',$payment->posted_by)->first()->lastname}}</b>
    </p>
</div>
