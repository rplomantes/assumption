<?php 
$totalcreditcard=0; ?>
<html>        
    <style>
        table  .decimal{
            text-align: right;
            padding-right: 10px;
        }
    </style>
    <style>
        body {
            font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
        }
        img {
            display: block;
            max-width:230px;
            max-height:95px;
            width: auto;
            height: auto;
        }
        #schoolname{
            font-size: 18pt; 
            font-weight: bolder;
        }
        .underline {
            border-top: 1px solid transparent;
            border-left: 1px solid transparent;
            border-right: 1px solid transparent;
        }
        .top-line {
            border-bottom: 1px solid transparent;
            border-left: 1px solid transparent;
            border-right: 1px solid transparent;
            text-align: center;
        }
        .no-border {
            border-top: 1px solid transparent;
            border-left: 1px solid transparent;
            border-right: 1px solid transparent;
            border-bottom: 1px solid transparent;
        }
        table td{
            font-size: 10pt;
        }
        table th{
            font-size: 10pt;
        }
    </style>

    <body>
        <div>    
            <!--<div style='float: left; margin-left: 150px;'><img src="{{public_path('/images/assumption-logo.png')}}"></div>-->
            <div style='float: left; margin-top:12px; margin-left: 10px' align='Left'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village, Makati City</small>
                <br><br>
                <b>LIST OF ONLINE PAYMENTS</b>    
                <br>
                Date Covered : {{$date_from}} to {{$date_to}}
            </div>
        </div>
        <p>
            <br><br><br><br><br><br>
        </p>
        <table width="100%" id="example1" border="1" cellspacing="0" cellpadding="2" class="table table-responsive table-striped">
         <thead>
             <tr><th>Date</th><th>Receipt No</th><th>Receive From</th><th>Online Payment</th><th>Reference No.</th><th>Response ID</th><th>Amount</th><th>Status</th>
             
                    <th>Posted By</th>
         </thead>
         <tbody>
             @if(count($payments)>0)
                @foreach($payments as $payment)
                    <?php
                    if($payment->is_reverse==0){
                    $totalcreditcard=$totalcreditcard+$payment->credit_card_amount;
                    }
                    ?>
                    <tr><td>{{$payment->transaction_date}}</td>
                    <td>{{$payment->receipt_no}}</td>
                    <td>{{$payment->paid_by}}</td>
                    
                    <td>{{$payment->credit_card_bank}} - {{$payment->credit_card_type}}</td>
                    <td>{{$payment->credit_card_number}}</td>
                    <td>{{$payment->approval_number}}</td>
                    
                    @if($payment->is_reverse=="0")
                    <td><b>{{number_format($payment->credit_card_amount,2)}}</b></td>
                    <td>Ok</td>
                    @else
                    <?php
                    $totalcanceled=$payment->cash_amount+$payment->check_amount + $payment->credit_card_amount +$payment->deposit_amount;
                    ?>
                    </tr>
  <td><span style='color:red;text-decoration:line-through;'>
  <span style='color:#999'>{{number_format($payment->credit_card_amount,2)}}</span></span></td>
                    <td>Canceled</td>
                    @endif
                    
                    <td>{{$payment->posted_by}}</td>
                @endforeach
             @else
             @endif
         </tbody>
          <tfoot>
                    <tr><th colspan="6">Total</th>
                    
                    <th><b>{{number_format($totalcreditcard,2)}}</b></th>
                    <th></th>
                    <th></th>
                    </tr>        
         </tfoot>    
     </table>    
    </body>
</html>   