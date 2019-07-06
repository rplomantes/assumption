<?php
$user = \App\User::where('idno',$idno)->first();
$status =  \App\Status::where('idno',$idno)->first();
if($status->status == env("ENROLLED")){
    $display_status = "ENROLLED";
}else{
    $display_status = "ASSESSED";
}
$ledger = \App\Ledger::SelectRaw('category_switch, category, sum(amount)as amount, sum(discount) as discount,
    sum(debit_memo) as debit_memo, sum(payment) as payment')->where('idno',$idno)
        ->where(function($query){
            $query->where('category_switch', 4)
                    ->orWhere('category_switch', 5);
            })->groupBy('category','category_switch')->orderBy('category_switch')->get();
$ledger_tuition = \App\Ledger::SelectRaw('category_switch, category, sum(amount)as amount, sum(discount) as discount,
    sum(debit_memo) as debit_memo, sum(payment) as payment')->where('idno',$idno)->where('category_switch', 6)->groupBy('category','category_switch')->orderBy('category_switch')->get();
$ledger_misc = \App\Ledger::SelectRaw('category_switch, category, sum(amount)as amount, sum(discount) as discount,
    sum(debit_memo) as debit_memo, sum(payment) as payment')->where('idno',$idno)->where('category_switch', 1)->groupBy('category','category_switch')->orderBy('category_switch')->get();
$ledger_other = \App\Ledger::SelectRaw('category_switch, category, sum(amount)as amount, sum(discount) as discount,
    sum(debit_memo) as debit_memo, sum(payment) as payment')->where('idno',$idno)->where('category_switch', 2)->groupBy('category','category_switch')->orderBy('category_switch')->get();
$ledger_depo = \App\Ledger::SelectRaw('category_switch, category, sum(amount)as amount, sum(discount) as discount,
    sum(debit_memo) as debit_memo, sum(payment) as payment')->where('idno',$idno)->where('category_switch', 3)->groupBy('category','category_switch')->orderBy('category_switch')->get();
$ledger_late = \App\Ledger::SelectRaw('category_switch, category, sum(amount)as amount, sum(discount) as discount,
    sum(debit_memo) as debit_memo, sum(payment) as payment')->where('idno',$idno)->where('category_switch', 7)->where('subsidiary', "Late Payment")->groupBy('category','category_switch')->orderBy('category_switch')->get();
if($status->academic_type == "SHS"){
$due_dates = \App\LedgerDueDate::where('idno',$idno)->where('school_year', $status->school_year)->where('period', $status->period)->get();
}else{
$due_dates = \App\LedgerDueDate::where('idno',$idno)->where('school_year', $status->school_year)->get();
}
$upon_payment=0;
if(count($due_dates)>0) {
    foreach($due_dates as $dt){
        if($dt->due_switch=="0"){
        $upon_payment=$upon_payment = $dt->amount;
        }
    }
}    
$payments =  \App\Payment::where('idno',$idno)->where('is_current','1')->orderBy('transaction_date')->get();   
$debit_memos =  \App\DebitMemo::where('idno',$idno)->where('is_current','1')->orderBy('transaction_date')->get();      
$totalmainpayment=0;
$upon = 0;
?>
<html>
 <head>
     <style>
         .logo{font-size:22pt;}
         .address{font-size:10pt;}
         .title{text-align: center; text-decoration: underline}
         .name{font-size:12;font-weight: bold}
         p{font-style: italic}
         table{font-size:9pt;}
         .upon{color:red}
         .due_amount{font-size:16pt;font-weight: bold;color:red;
           border-color: #000}
         .late_amount{font-size:12pt;font-weight: bold;color:orange;
           border-color: #000}
         
     </style>
 </head>
    <body>
        <table border="0" cellpadding="0" cellspacing="0">
            <tr><td><img width="100"src="{{public_path('/images/assumption-logo.png')}}" ><td>&nbsp;</td></td>
                <td valign="top"><div class="logo">Assumption College</div>
        <div class="address">San Lorenzo Drive, San Lorenzo Village<br> Makati City</div>
        </td></tr>
        </table>
        @if($status->status==env("ASSESSED"))
        <div class="title">ASSESSMENT FORM</div> 
        @else
         <h2 class="title">ACCOUNT DETAILS</h2>
        @endif
       <table><tr><td>A.Y.</td><td> : </td><td>{{$status->school_year}} - {{$status->school_year+1}} {{$status->period}}</td></tr>
           <tr><td>Student ID</td><td> : </td><td>{{$idno}}</td></tr>
           <tr><td>Student Name</td><td> : </td><td><div class="name">{{$user->lastname}}, {{$user->firstname}} {{$user->middlename}}</div></td></tr>
           <tr><td>Enrolled to </td><td> : </td><td>{{$status->level}}</td></tr>
           <tr><td>Plan </td><td> : </td><td>{{$status->type_of_plan}}</td></tr>
           @if($status->level == "Grade 11" || $status->level == "Grade 12")
           <tr><td>Strand</td><td> : </td><td>{{$status->strand}}</td></tr>
           @endif
       </table> 
        
        
   <table width="100%" border="0" cellspacing="0" cellpadding="0"> 
       <tr><td colspan="2">&nbsp;</td></tr>
       <td width="75%">
    <label>Breakdown of Fees:</label>           
    <table border ='1' cellspacing="0" cellpadding="2"  width="100%"class="table table table-striped table-bordered"><tr><th>Description</th><th>Amount</th><th>Discount</th><th>Reservation</th><th>Payment</th><th>Balance</th></tr>
           <?php
           $totalamount=0;$totaldiscount=0;$totaldm=0;$totalpayment=0;$balance=0;$totalbalance=0;
           ?>
           @foreach($ledger_misc as $main)
           <?php
               $totalamount=$totalamount+$main->amount;
               $totaldiscount=$totaldiscount+$main->discount;
               $totaldm=$totaldm+$main->debit_memo;
               $totalpayment=$totalpayment+$main->payment;
               $balance=+$main->amount-$main->discount-$main->debit_memo-$main->payment;
               $totalbalance=$totalbalance+$balance;
               ?>
               <tr><td>{{$main->category}}</td>
               <td align="right">{{number_format($main->amount,2)}}</td>
               <td align="right">{{number_format($main->discount,2)}}</td>
               <td align="right">{{number_format($main->debit_memo,2)}}</td>
               <td align="right"><span class="payment">{{number_format($main->payment,2)}}</span></td>
               <td align="right"><b>{{number_format($balance,2)}}</b></td></tr>
           @endforeach
           @foreach($ledger_other as $main)
           <?php
               $totalamount=$totalamount+$main->amount;
               $totaldiscount=$totaldiscount+$main->discount;
               $totaldm=$totaldm+$main->debit_memo;
               $totalpayment=$totalpayment+$main->payment;
               $balance=+$main->amount-$main->discount-$main->debit_memo-$main->payment;
               $totalbalance=$totalbalance+$balance;
               ?>
               <tr><td>{{$main->category}}</td>
               <td align="right">{{number_format($main->amount,2)}}</td>
               <td align="right">{{number_format($main->discount,2)}}</td>
               <td align="right">{{number_format($main->debit_memo,2)}}</td>
               <td align="right"><span class="payment">{{number_format($main->payment,2)}}</span></td>
               <td align="right"><b>{{number_format($balance,2)}}</b></td></tr>
           @endforeach
           @foreach($ledger_depo as $main)
           <?php
               $totalamount=$totalamount+$main->amount;
               $totaldiscount=$totaldiscount+$main->discount;
               $totaldm=$totaldm+$main->debit_memo;
               $totalpayment=$totalpayment+$main->payment;
               $balance=+$main->amount-$main->discount-$main->debit_memo-$main->payment;
               $totalbalance=$totalbalance+$balance;
               ?>
               <tr><td>{{$main->category}}</td>
               <td align="right">{{number_format($main->amount,2)}}</td>
               <td align="right">{{number_format($main->discount,2)}}</td>
               <td align="right">{{number_format($main->debit_memo,2)}}</td>
               <td align="right"><span class="payment">{{number_format($main->payment,2)}}</span></td>
               <td align="right"><b>{{number_format($balance,2)}}</b></td></tr>
           @endforeach
           @foreach($ledger_tuition as $main)
           <?php
               $totalamount=$totalamount+$main->amount;
               $totaldiscount=$totaldiscount+$main->discount;
               $totaldm=$totaldm+$main->debit_memo;
               $totalpayment=$totalpayment+$main->payment;
               $balance=+$main->amount-$main->discount-$main->debit_memo-$main->payment;
               $totalbalance=$totalbalance+$balance;
               ?>
               <tr><td>{{$main->category}}</td>
               <td align="right">{{number_format($main->amount,2)}}</td>
               <td align="right">{{number_format($main->discount,2)}}</td>
               <td align="right">{{number_format($main->debit_memo,2)}}</td>
               <td align="right"><span class="payment">{{number_format($main->payment,2)}}</span></td>
               <td align="right"><b>{{number_format($balance,2)}}</b></td></tr>
           @endforeach
           
               <tr style="background-color:gainsboro"><td>Total School Fees</td>
               <td align="right"><b>{{number_format($totalamount,2)}}</b></td>
               <td align="right">{{number_format($totaldiscount,2)}}</td>
               <td align="right">{{number_format($totaldm,2)}}</td>
               <td align="right"><span class="payment">{{number_format($totalpayment,2)}}</span></td>
               <td align="right"><b>{{number_format($totalbalance,2)}}</b></td></tr>
               
               
               @foreach($ledger as $main)
           <?php
               $totalamount=$totalamount+$main->amount;
               $totaldiscount=$totaldiscount+$main->discount;
               $totaldm=$totaldm+$main->debit_memo;
               $totalpayment=$totalpayment+$main->payment;
               $balance=+$main->amount-$main->discount-$main->debit_memo-$main->payment;
               $totalbalance=$totalbalance+$balance;
               ?>
               <tr><td>@if($main->category == "Books") Books/Other Items @else {{$main->category}} @endif</td>
               <td align="right">{{number_format($main->amount,2)}}</td>
               <td align="right">{{number_format($main->discount,2)}}</td>
               <td align="right">{{number_format($main->debit_memo,2)}}</td>
               <td align="right"><span class="payment">{{number_format($main->payment,2)}}</span></td>
               <td align="right"><b>{{number_format($balance,2)}}</b></td></tr>
           @endforeach
           <tr style="background-color:gray"><td>Total</td>
               <td align="right"><b>{{number_format($totalamount,2)}}</b></td>
               <td align="right">{{number_format($totaldiscount,2)}}</td>
               <td align="right">{{number_format($totaldm,2)}}</td>
               <td align="right"><span class="payment">{{number_format($totalpayment,2)}}</span></td>
               <td align="right"><b>{{number_format($totalbalance,2)}}</b></td></tr>
            </table>
           </td>
           <td width="25%" valign="top">
               @if(count($due_dates)>0)
        <label>Schedule of Payment:</label>
            <?php $totalpay = $totalpayment; $display=""; $remark="";?>
            @foreach($due_dates as $due_date)
            <?php 
            if($totalpay >= $due_date->amount){
                $display = "<span class=\"text_through\">".number_format($due_date->amount,2)."<span>";  
                $totalpay = $totalpay - $due_date->amount;
                $remark = "<span style=\"font-style:italic;color:#f00\">paid</span>";
            } else {
                $display = number_format($due_date->amount-$totalpay,2);
                $totalpay=0;
                $remark="";
            }
            ?>
            @if($due_date->due_switch=="0")
            <?php $upon=$upon+$due_date->amount; $duedate = "Upon Enrollment";?>
            @else
            <?php $duedate = $due_date->due_date;?>
            @endif
            @endforeach  
        <div class="form-group">
            <table width="100%" border="1" cellspacing="0" cellpadding="2" class="table table-striped"><tr><td>Due Date</td><td align="right">Due Amount</td></tr>
            @foreach($due_dates as $due_date)
            @if($due_date->due_switch=="0")
            <?php $duedate = "Upon Enrollment";?>
            @else
            <?php $duedate = $due_date->due_date;?>
            @endif
            <tr><td>{{$duedate}}</td><td align="right"><b>{{number_format($due_date->amount,2)}}</b></td></tr>
            @endforeach
            </table>   
        </div>
        @endif
           </td>
           </table>
        <br>
              <?php $amounttobepaid=$upon-$totaldm; 
       if($amounttobepaid <= 0){
           $amounttobepaid = 0;
       }
       ?>
        <p> Amount to be paid <span class="due_amount">Php {{number_format($amounttobepaid,2)}}</span>.</p>
        <?php $total_late = 0; ?>
        @if(count($ledger_late)>0)
        @foreach($ledger_late as $late)
        <?php $total_late = $late->amount + $total_late ?>
        @endforeach
        <p> Late Enrollment Fee: <span class="late_amount">Php {{number_format($total_late,2)}}</span>.</p>
        @endif
        <p>*Please print this form and present it to the cashier.<br></p>
        <!--<p><h2><strong>*Please note that tuition and other fees will be adjusted upon approval of the Department of Education.</strong></h2><br>-->
    
    <br>
    <br>
    <div style='border-top: 1px solid black; width: 200px;'>
    Parent's Signature
    </div>
    </p>
    
 </body>
 </html>