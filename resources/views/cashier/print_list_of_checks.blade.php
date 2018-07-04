<html>
    <head>
        <style>
        table td .schoolname{
        font-size: 18pt; 
        font-weight: bolder;
    }
        </style>    
    </head>
    <body>
        <table border='0' cellspacing="0" cellpadding ="0" width="100%">
            <tr><td><span class="schoolname">Assumption College </span><br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small></td>
            <tr><td><br><b>LIST OF CHECKS</b></td></tr>
            <tr><td>Period Covered : {{date('M d, Y',strtotime($date_from))}} - {{date('M d, Y',strtotime($date_to))}}    
        </table>  
        @if(count($payments)>0)
        <?php $totalAmount=0;?>
            <table boder="1" cellspacing="0" cellpadding="0" width="100%">
                <tr><td>Bank</td><td>Check Number</td><td>Amount</td></tr>
                @foreach($payments as $payment)
                    <?php $totalAmount = $totalAmount + $payment->check_amount;?>
                    <tr><td>{{$payment->bank_name}}</td><td>{{$payment->check_number}}</td><td align="right">{{number_format($payment->check_amount,2)}}</td></tr>
                @endforeach
                <tr><td colspan="2">Total Amount</td><td>{{number_format($totalAmount,2)}}</td></tr>
            </table>    
        @else
        <h5>No Checks Collection Within Those Dates</h5>
        @endif
        <table boeder="0">
            <tr><td>Prepared By:</td></tr>
            <tr><td>{{Auth::user()->firstname}} {{Auth::user()->lastname}}</td></tr>
        </table>    
    </body>
</html>    
