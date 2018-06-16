<?php
$layout="";
if(Auth::user()->accesslevel == env("CASHIER")){
    $layout = "layouts.appcashier";
} else if(Auth::user()->accesslevel == env("ACCTNG_STAFF")){
    $layout="layouts.appaccountingstaff";
}
?>
@extends($layout)
@section('messagemenu')
<li class="dropdown messages-menu">
            <!-- Menu toggle button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success"></span>
            </a>
</li>
<li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning"></span>
            </a>
</li>
          
<li class="dropdown tasks-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-flag-o"></i>
              <span class="label label-danger"></span>
            </a>
</li>
@endsection
@section('header')
<style>
   
</style>

<section class="content-header">
      <h1>
        Student Ledger
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Student ledger</li>
      </ol>
</section>
@endsection
@section('maincontent')
<div class="container-fluid">
    <div class="col-md-3 form-horizontal">
        
    </div>    
    <div class="col-md-9">
        <div class="col-md-6">
       
            </div>
        <div class="col-md-6">
        
        </div>    
    </div> 
    <div class="clearfix"></div>
    <div class="col-md-8">
        <div class="col-md-6"> 
             <table class="table table-responsive">
            <tr><td>Student Number : </td><td align="left">{{$user->idno}}</td></tr>
            <tr><td>Student Name : </td><td align="left"><b>{{$user->lastname}}, {{$user->firstname}}</b></td></tr>   
            @if(count($status)>0)
            
            <?php
            switch($status->status){
            case 0:
                echo "<tr><td>Status : </td><td>Not Yet Advised or Assessed For This School Year</td><tr>";
                break;
            case env("ADVISING"):
                echo "<tr><td>Status : </td><td>Alreary Advised but Not Assessed Yet</td><tr>";
                break;
            case env("ASSESSED"):
                echo "<tr><td>Status : </td><td>Assessed</td><tr>";
                    if($status->academic_type=="College"){     
                        if(count($levels)>0){
                        echo "<tr><td>Program/Level : </td><td>".$levels->program_code ." - ".$levels->level."</td><tr>";    
                        }
                        
                    } else {
                    
                    if($levels->academic_type=="SHS"){
                        echo "<tr><td>Strand : </td><td>".$levels->track."</td><tr>";    
                    }
                    echo "<tr><td>Level/Section : </td><td>".$levels->level ." - ".$levels->section."</td><tr>";
                    
                    
                    }
                    
                break;
            case env("ENROLLED"):
                 echo "<tr><td>Status : </td><td>Enrolled</td><tr>";
                if($status->academic_type=="College"){
                 echo "<tr><td>Level/Section : </td><td>".$levels->program_code ." - ".$levels->level."</td><tr>";
                } elseif($status->academic_type=="BED" || $status->academic_type=="SHS") {
                 echo "<tr><td>Level : </td><td>".$levels->level."</td><tr>";
                }
                 break;
            case 4:
                 echo "<tr><td>Status : </td><td>Dropped</td><tr>";
            }
            ?></b>
            
            @else
            <tr><td>Status : </td><td align="left"><span style="color:#f00">Not Enrolled</span></td></tr>
            @endif
            @if ($status->status == env('ASSESSED') || $status->status == env('ENROLLED'))
            @if($status->academic_type == "BED" || $status->academic_type == "SHS")
            <tr>
                <td colspan="2"><a href="{{url('/bedregistrar',array('print_assessment',$user->idno))}}" class="btn btn-success form form-control">Print Assessment Form</a></td>
            </tr>
            @endif
            @endif
        </table> 
        </div>  
    <div class="col-md-12">    
    <div class="accordion">
    <div class="accordion-section">
        <a class="accordion-section-title active" href="#accordion-1">Main Fees</a>
         
        <div id="accordion-1" class="accordion-section-content open">
            @if(count($ledger_main)>0)
            <table class="table table-bordered"><tr><th>Description</th><th>Amount</th><th>Discount</th><th>Debit Memo</th><th>Payment</th><th>Balance</th></tr>
           <?php
           $totalamount=0;$totaldiscount=0;$totaldm=0;$totalpayment=0;$balance=0;$totalbalance=0;
           ?>
           @foreach($ledger_main as $main)
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
               <tr><td>Total</td>
               <td align="right">{{number_format($totalamount,2)}}</td>
               <td align="right">{{number_format($totaldiscount,2)}}</td>
               <td align="right">{{number_format($totaldm,2)}}</td>
               <td align="right"><span class="payment">{{number_format($totalpayment,2)}}</span></td>
               <td align="right"><b>{{number_format($totalbalance,2)}}</b></td></tr>
            </table> 
            @else
            <h5>This Student Is Not Yet Assessed</h5>
            @endif
        </div><!--end .accordion-section-content-->
    </div><!--end .accordion-section-->
    
    <div class="accordion-section">
        <a class="accordion-section-title active" href="#accordion-2">Other Fees</a>
         
        <div id="accordion-2" class="accordion-section-content open">
            @if(count($ledger_others)>0)
            <table class="table table-bordered"><tr><th>Description</th><th>Amount</th><th>Discount</th><th>Debit Memo</th><th>Payment</th><th>Balance</th></tr>
           <?php
           $totalamount=0;$totaldiscount=0;$totaldm=0;$totalpayment=0;$balance=0;$totalbalance=0;
           ?>
           @foreach($ledger_others as $main)
           <?php
               $totalamount=$totalamount+$main->amount;
               $totaldiscount=$totaldiscount+$main->discount;
               $totaldm=$totaldm+$main->debit_memo;
               $totalpayment=$totalpayment+$main->payment;
               $balance=+$main->amount-$main->discount-$main->debit_memo-$main->payment;
               $totalbalance=$totalbalance+$balance;
               ?>
               <tr><td>{{$main->receipt_details}}</td>
               <td align="right">{{number_format($main->amount,2)}}</td>
               <td align="right">{{number_format($main->discount,2)}}</td>
               <td align="right">{{number_format($main->debit_memo,2)}}</td>
               <td align="right"><span class="payment">{{number_format($main->payment,2)}}</span></td>
               <td align="right"><b>{{number_format($balance,2)}}</b></td></tr>
           @endforeach
               <tr><td>Total</td>
               <td align="right">{{number_format($totalamount,2)}}</td>
               <td align="right">{{number_format($totaldiscount,2)}}</td>
               <td align="right">{{number_format($totaldm,2)}}</td>
               <td align="right"><span class="payment">{{number_format($totalpayment,2)}}</span></td>
               <td align="right"><b>{{number_format($totalbalance,2)}}</b></td></tr>
            </table>  
            @else
            <h5>No Other Payment</h5>
            @endif 
        </div><!--end .accordion-section-content-->
    </div><!--end .accordion-section-->
    
    
    <div class="accordion-section">
        <a class="accordion-section-title active" href="#accordion-4">Previous Balance</a>
    <div id="accordion-4" class="accordion-section-content">
            @if(count($previous)>0)
            <table class="table table-bordered"><tr><th>Description</th><th>Amount</th><th>Discount</th><th>Debit Memo</th><th>Payment</th><th>Balance</th></tr>
           <?php
           $totalamount=0;$totaldiscount=0;$totaldm=0;$totalpayment=0;$balance=0;$totalbalance=0;
           ?>
           @foreach($previous as $main)
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
               <tr><td>Total</td>
               <td align="right">{{number_format($totalamount,2)}}</td>
               <td align="right">{{number_format($totaldiscount,2)}}</td>
               <td align="right">{{number_format($totaldm,2)}}</td>
               <td align="right"><span class="payment">{{number_format($totalpayment,2)}}</span></td>
               <td align="right"><b>{{number_format($totalbalance,2)}}</b></td></tr>
            </table>  
            @else
            <h5>No Previous Balance</h5>
            @endif 
        </div><!--end .accordion-section-content-->
    </div><!--end .accordion-section-->
    
     </div>
        <hr>
     
         <a class="accordion-section-title" href="javascript:void(0)">Payment History</a>
         <div class="history">
         @if(count($payments)>0)
         <table class="table table-responsive"><tr><td>Date</td><td>Receipt No</td><td>Explanation</td><td>Amount</td><td>Status</td><td>View Receipt</td></tr>
          @foreach($payments as $payment)
          <tr><td>{{$payment->transaction_date}}</td>
              <td>{{$payment->receipt_no}}</td>
              <td>{{$payment->remarks}}</td>
              <td align='right'>{{number_format($payment->cash_amount+$payment->check_amount+$payment->credit_card_amount+$payment->deposit_amount,2)}}</td>
              <td>@if($payment->is_reverse=='0') Ok @else Canceled @endif</td>
              <td><a href="{{url('/cashier',array('viewreceipt',$payment->reference_id))}}">View receipt</a></td>
              </tr>
          @endforeach
         </table>    
         
         @else
         <h5>No Payment Has Been Made Yet</h5>
         @endif
        </div> 
        
         <a class="accordion-section-title" href="javascript:void(0)">Debit Memo</a>
         <div class="history">
         @if(count($debit_memos)>0)
        
         <table class="table table-responsive"><tr><td>Date</td><td>DM No</td><td>Explanation</td><td>Amount</td><td>Status</td><td>View DM</td></tr>
          @foreach($debit_memos as $payment)
          <tr><td>{{$payment->transaction_date}}</td>
              <td>{{$payment->dm_no}}</td>
              <td>{{$payment->explanation}}</td>
              <td align='right'>{{number_format($payment->amount,2)}}</td>
              <td>@if($payment->is_reverse=='0') Ok @else Canceled @endif</td>
              <td><a  href="{{url('/accounting',array('view_debit_memo',$payment->reference_id))}}">View DM</a></td>
              </tr>
          @endforeach
         </table>    
         
         @else
         <h5>No Debit Memo For This Account</h5>
         @endif
     </div>  
    </div>  
    </div>    
    <div class="col-md-4">
        <div class="form-group">
        <label>Total Due Today:</label>
        <div class="form form-control" id="due_display">
            @if($totaldue>0)
            {{number_format($totaldue,2)}}
            @else
            0.00
            @endif
        </div>
        </div>
        @if(Auth::user()->accesslevel==env("CASHIER"))
        <div class="form-group">
        <a href="{{url('/cashier',array('main_payment',$user->idno))}}" class="form form-control btn btn-primary">Process Payment</a>
        </div>
        <div class="form-group">
        <a href="{{url('/cashier',array('other_payment',$user->idno))}}" class="form form-control btn btn-success">Other Payment</a>
        </div>
         <div class="form-group">
        <a class="form form-control btn btn-success" href="{{url('cashier',array('reservation',$user->idno))}}">Reservation</a>
        </div>
        @elseif(Auth::user()->accesslevel==env("ACCTNG_STAFF"))
        <div class="form-group">
        <a href="{{url('/accounting',array('debit_memo',$user->idno))}}" class="form form-control btn btn-primary">DEBIT MEMO</a>
        </div>
        <div class="form-group">
        <a href="{{url('/accounting',array('add_to_account',$user->idno))}}" class="form form-control btn btn-primary">OTHER PAYMENT</a>
        </div>
        @if($status->academic_type == "BED" && $status->status==env("ENROLLED"))
        <div class="form-group">
        <a href="{{url('/accounting',array('change_plan',$user->idno))}}" class="form form-control btn btn-primary">CHANGE PLAN</a>
        </div>
        @endif
        
        @endif
        @if(count($due_dates)>0)
        <label>Schedule of Payment</label>
        <div class="form-group">
            <?php $totalpay = $totalmainpayment; $display=""; $remark="";?>
            <table class="table table-striped"><tr><td>Due Date</td><td align="right">Due Amount</td><td>Remarks</td></tr>
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
            <?php $duedate = "Upon Enrollment";?>
            @else
            <?php $duedate = $due_date->due_date;?>
            @endif
            <tr><td>{{$duedate}}</td><td align="right">{!!$display!!}</td><td align="center">{!!$remark!!}</td></tr>
            @endforeach
            </table>    
        </div>
        @endif
    </div>
     
</div>    
@endsection
@section('footerscript')
<style>
     #due_display{
        text-align:right;
        font-size:30pt; 
        font-weight: bold; 
        color:#9F0053;
        height:70px;
    }
    .payment{
        color:#f00;
        font-weight: bold;
    }
    .history{
        background-color: #ccc;
        padding: 10px;
    }
    
    .accordion, .accordion * {
    -webkit-box-sizing:border-box; 
    -moz-box-sizing:border-box; 
    box-sizing:border-box;
    
}
 
.accordion {
    overflow:hidden;
    box-shadow:0px 1px 3px rgba(0,0,0,0.25);
    border-radius:3px;
    background:#f7f7f7;
}
 
/*----- Section Titles -----*/
.accordion-section-title {
    width:100%;
    padding:5px;
    display:inline-block;
    border-bottom:1px solid #1a1a1a;
    background:goldenrod;
    transition:all linear 0.15s;
    /* Type */
    font-size:1.200em;
    text-shadow:0px 1px 0px #1a1a1a;
    color:#fff;
}
 
.accordion-section-title.active, .accordion-section-title:hover {
    background:goldenrod;
    /* Type */
    text-decoration:none;
    color:#fff;
}
 
.accordion-section:last-child .accordion-section-title {
    border-bottom:none;
}
 
/*----- Section Content -----*/
.accordion-section-content {
    padding:15px;
    display:none;
}

.text_through{
    text-decoration: line-through;
    color: #aaa;
}
</style>
<script>
   $(document).ready(function() {
    function close_accordion_section() {
        $('.accordion .accordion-section-title').removeClass('active');
        $('.accordion .accordion-section-content').slideUp(300).removeClass('open');
    }
    
    $('#accordion-1').slideDown(300).addClass('open');
    $('#accordion-2').slideDown(300).addClass('open'); 
    $('#accordion-4').slideDown(300).addClass('open'); 
    
    $('.accordion-section-title').click(function(e) {
        // Grab current anchor value
        var currentAttrValue = $(this).attr('href');
 
        if($(e.target).is('.active')) {
            $(this).removeClass('active');
             $('.accordion ' + currentAttrValue).slideUp(300).addClass('open');
            //close_accordion_section();
        }else {
            //close_accordion_section();
 
            // Add active class to section title
            $(this).addClass('active');
            // Open up the hidden content panel
            $('.accordion ' + currentAttrValue).slideDown(300).addClass('open'); 
        }
 
        e.preventDefault();
    });
});
</script>    
@endsection
