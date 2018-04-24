<?php
$totalcash=0;
$totalcheck=0;
$totalcreditcard=0;
$totalbankdeposit=0;
$total=0;
$ntotal=0;
$grandtotal=0;
$totalcanceled=0;

?>
<html>
    
        <style>
            body {
                font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
                
            }
        </style>
    <style>
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
<div>    
    <!--<div style='float: left; margin-left: 150px;'><img src="{{public_path('/images/assumption-logo.png')}}"></div>-->
    <div style='float: left; margin-top:12px; margin-left: 10px' align='Left'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><br><b>COLLECTION REPORT</b>
    
     <br>
     Date Covered : {{$date_from}} to {{$date_to}}</div>
</div>
    <body><p>
     <br>
     <br>
     <br>
     <br>
     <br>
     <br>
     <br>
     </p>
             
     <table width="100%" id="example1" border="1" cellspacing="0" cellpadding="2" class="table table-responsive table-striped">
         <thead>
             <tr><th>Date</th><th>Receipt No</th><th>Receive From</th><th>Cash</th><th>Check No</th><th>Bank</th><th>Check Amount</th><th>Credit Card No</th><th>Desc</th><th>Amount</th>
<!--                 <th>Bank Deposit</th>-->
                 <th>Total</th>
                  @if(Auth::user()->accesslevel==env("ACCTNG_STAFF"))
                    <th>Posted By</th>
                    @endif

             </tr>
         </thead>
         <tbody>
             @if(count($payments)>0)
                @foreach($payments as $payment)
                    <?php
                    if($payment->is_reverse==0){
                    $totalcash=$totalcash+$payment->cash_amount;
                    $totalcheck=$totalcheck+$payment->check_amount;
                    $totalcreditcard=$totalcreditcard+$payment->credit_card_amount;
                    $totalbankdeposit=$totalbankdeposit+$payment->deposit_amount;
                    $total=$payment->cash_amount+$payment->check_amount+$payment->credit_card_amount+$payment->deposit_amount;
                    $ntotal=$totalcash+$totalcheck+$totalcreditcard+$totalbankdeposit;
                    $grandtotal=$grandtotal+$total;
                    }
                    ?>
                <tr><td>{{$payment->transaction_date}}</td>
                    <td>{{$payment->receipt_no}}</td>
                    <td>{{$payment->paid_by}}</td>
                    @if($payment->is_reverse=="0")
                    <td class="decimal">{{number_format($payment->cash_amount,2)}}</td>
                    <td>{{$payment->check_number}}</td>
                    <td>{{$payment->bank_name}}</td>
                    <td class="decimal">{{number_format($payment->check_amount,2)}}</td>
                    <td>{{$payment->credit_card_no}}</td>
                    <td>{{$payment->credit_card_bank}}</td>
                    <td class="decimal">{{number_format($payment->credit_card_amount,2)}}</td>
<!--                    <td class="decimal">{{number_format($payment->deposit_amount,2)}}</td>-->
                    <td class="decimal"><b>{{number_format($total,2)}}</b></td>
                    
                   
                    @else
                    <?php
                    $totalcanceled=$payment->cash_amount+$payment->check_amount + $payment->credit_card_amount +$payment->deposit_amount;
                    ?>
                    <td class="decimal"><span style='color:red;text-decoration:line-through'>
  <span style='color:black'>{{number_format($payment->cash_amount,2)}}</span></span></td>
  <td><span style='color:black'>{{$payment->check_number}}</span></td>
                    <td><span style='color:black'>{{$payment->bank_name}}</span></td>
                    <td class="decimal"><span style='color:red;text-decoration:line-through'>
  <span style='color:black'>{{number_format($payment->check_amount,2)}}</span></span></td>
  <td><span style='color:black'>{{$payment->credit_card_no}}</span></td>
  <td><span style='color:black'>{{$payment->credit_card_bank}}</span></td>
                    <td class="decimal"><span style='color:red;text-decoration:line-through'>
  <span style='color:black'>{{number_format($payment->credit_card_amount,2)}}</span></span></td>
<!--                    <td class="decimal"><span style='color:red;text-decoration:line-through'>
  <span style='color:black'>{{number_format($payment->deposit_amount,2)}}</span></span></td>-->
  <td class="decimal"><span style='color:red;text-decoration:line-through;'>
  <span style='color:#999'>{{number_format($totalcanceled,2)}}</span></span></td>
  
                    
                    @endif
                    @if(Auth::user()->accesslevel==env("ACCTNG_STAFF"))
                    <td>{{$payment->posted_by}}</td>
                    @endif
                    </tr>
                @endforeach
             @else
             @endif
         </tbody>
          <tfoot>
                    <tr><th colspan="3">Total</th>
                        <th class="decimal">{{number_format($totalcash,2)}}</th>
                    <th></th><th></th>
                    <th class="decimal">{{number_format($totalcheck,2)}}</th>
                    <th></th><th></th>
                    <th class="decimal">{{number_format($totalcreditcard,2)}}</th>
<!--                    <th class="decimal">{{number_format($totalbankdeposit,2)}}</th>-->
                    <th class="decimal">{{number_format($grandtotal,2)}}</th>
                     @if(Auth::user()->accesslevel==env("ACCTNG_STAFF"))
                    <th></th>
                    @endif
                    
                    </tr>
        
         </tfoot>    
     </table> 
     @if(count($credits)>0)
     <?php $totalcredit=0;$totaldebit=0;?>
     <span><br>Summary of transactions</span>
     <table border = "1" cellspacing="0" cellpadding="2" width="50%">
         <tr><td>Particular</td><td>Debit</td><td>Credit</td></tr>
         @foreach($debits as $debit)
         <?php $totaldebit= $totaldebit + $debit->debit;?>
         <tr><td>{{$debit->receipt_details}}</td><td align="right">{{number_format($debit->debit,2)}}</td><td></td></tr>
         @endforeach
         @foreach($credits as $credit)
         <?php $totalcredit= $totalcredit + $credit->credit;?>
         <tr><td>{{$credit->receipt_details}}</td><td></td><td align="right">{{number_format($credit->credit,2)}}</td></tr>
         @endforeach
         
         <tr><td>Total</td><td align="right">{{number_format($totaldebit,2)}}</td><td align="right">{{number_format($totalcredit,2)}}
     </table>    
     @endif
     </div>    
     </div> <br><br><br>Prepared by: <br><br><b>{{Auth::user()->firstname}} {{Auth::user()->lastname}}</b>
     <br><br><br><br>Checked by: <br><br><b></b>
         
   </div>
 

<style>
  table  .decimal{
        text-align: right;
        padding-right: 10px;
    }
</style>    

</body>
</html>
 


