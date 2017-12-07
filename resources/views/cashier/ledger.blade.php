@extends('layouts.appcashier')
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
    <div class="col-md-3 pull-right form-horizontal">
        <div class="form-group">
        <label>Total Due Today:</label>
        <div class="form form-control" id="due_display">146,500.00</div>
        </div>
        <div class="form-group">
        <div class="form form-control btn btn-primary">Process Payment</div>
        </div>
        <div class="form-group">
        <div class="form form-control btn btn-success">Other Payment</div>
        </div>
         <div class="form-group">
        <a class="form form-control btn btn-success" href="{{url('cashier',array('reservation',$user->idno))}}">Reservation</a>
        </div>
    </div>    
    <div class="col-md-9">
        <div class="col-md-6">
        <table class="table table-responsive">
            <tr><td>Student Number : </td><td align="left">{{$user->idno}}</td></tr>
            <tr><td>Student Name : </td><td align="left"><b>{{$user->lastname}}, {{$user->firstname}}</b></td></tr>   
            @if(count($status)>0)
            
            <?php
            switch($status->status){
            case 0:
                echo "<tr><td>Status : </td><td>Not Registered For This School Year</td><tr>";
                break;
            case 1:
                if($status->academic_type=="College"){
                 echo "<tr><td>Ptrogram/Level : </td><td>".$status->program_code ." - ".$status->level."</td><tr>";    
                } else {
                echo "<tr><td>Status : </td><td>Assessed</td><tr>";
                if($status->department=="Senior High School"){
                echo "<tr><td>Tracks : </td><td>".$status->track."</td><tr>";    
                }
                echo "<tr><td>Level/Section : </td><td>".$status->level ." - ".$status->section."</td><tr>";
                }
                break;
            case 2:
                 echo "<tr><td>Status : </td><td>Enrolled</td><tr>";
                 echo "<tr><td>Level/Section : </td><td>".$status->level ." - ".$status->section."</td><tr>";
                break;
            case 3:
                 echo "<tr><td>Status : </td><td>Dropped</td><tr>";
            }
            ?></b>
            
            @else
            <tr><td>Status : </td><td align="left"><span style="color:#f00">Not Enrolled</span></td></tr>
            @endif
        </table> 
            </div>
    </div> 
    <div class="clearfix"></div>
    <div class="col-md-9">
    <div class="accordion">
    <div class="accordion-section">
        <a class="accordion-section-title active" href="#accordion-1">Main Ledger</a>
         
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
        <a class="accordion-section-title" href="#accordion-2">Other Payment</a>
         
        <div id="accordion-2" class="accordion-section-content">
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
            <h5>No Other Payment</h5>
            @endif 
        </div><!--end .accordion-section-content-->
    </div><!--end .accordion-section-->
    <div class="accordion-section">
        <a class="accordion-section-title" href="#accordion-3">Previous Balance</a>
    <div id="accordion-3" class="accordion-section-content">
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
              <td><a class="btn btn-success" href="{{url('/cashier',array('viewreceipt',$payment->reference_id))}}">View receipt</a></td>
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
              <td><a class="btn btn-success" href="{{url('/cashier',array('viewdebitmemo',$payment->reference_id))}}">View DM</a></td>
              </tr>
          @endforeach
         </table>    
         
         @else
         <h5>No Debit Memo For This Account</h5>
         @endif
     </div>  
    </div>  
    <div class="col-md-3">
        <label>Schedule of Payment</label>
        <div class="form-group">
            <table class="table table-striped"><tr><td>Due Date</td><td align="right">Due Amount</td></tr>
            </table>    
        </div>    
    </div>
<div class="col-md-3">
        <label>Reservation</label>
        <div class="form-group">
            <table class="table table-striped"><tr><td>Date</td><td align="right">Amount</td><td>Is Consumed</td></tr>
            </table>    
        </div>    
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
</style>
<script>
   $(document).ready(function() {
    function close_accordion_section() {
        $('.accordion .accordion-section-title').removeClass('active');
        $('.accordion .accordion-section-content').slideUp(300).removeClass('open');
    }
    $('#accordion-1').slideDown(300).addClass('open'); 
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