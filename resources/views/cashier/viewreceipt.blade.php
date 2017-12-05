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
        View Receipt
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url("/cashier",array('viewledger',$payment->idno))}}"> Student Ledger</a></li>
        <li class="active">View Receipt</li>
      </ol>
</section>
@endsection
@section('maincontent')
<div class="container-fluid">
    <div class="col-md-6">
        <div class="box-body">
        <div>Assumption College</div>
        <div>San Lorenzo, Makati</div>
        <div>Name: {{$payment->paid_by}}</div>
        <?php $totalreceipt=0;?>
        <table class="table table-bordered table-hover"><tr><td>Particular</td><td align="right">Amount</td></tr>
        @if(count($receipt_details)>0)
            @foreach($receipt_details as $receipt_detail)
            <?php $totalreceipt=$totalreceipt+$receipt_detail->credit;?>
            <tr><td>{{$receipt_detail->receipt_details}}</td>
                <td align="right">{{number_format($receipt_detail->credit,2)}}</td></tr>
            @endforeach
        @endif
        @if(count($receipt_less)>0)
        <tr><td colspan="2">Less:</td></tr>
            @foreach($receipt_less as $less)
            <?php $totalreceipt=$totalreceipt-$less->debit;?>
            <tr><td>{{$less->receipt_details}}</td>
                <td>({{number_format($less->debit,2)}})</td></tr>
            @endforeach
        @endif
        <tr><td>Total</td><td align="right">{{number_format($totalreceipt,2)}}</td><tr>
        </table>
        <h5>Details</h5>
        <div>{{$payment->remarks}}</div>
        @if($payment->cash_amount>0)
        <div>Cash Received : {{number_format($payment->amount_received,2)}}</div>
        <div>Change : {{number_format($payment->amount_received-$payment->cash_amount,2)}}</div>
        @endif
        @if($payment->check_amount>0)
        <div>Bank : {{$payment->bank_name}}</div>
        <div>Check No : {{$payment->check_number}}</div>
        <div>Check Amount : {{number_format($payment->check_amount)}}</div>
        @endif
        @if($payment->credit_card_amount>0)
        <div>Credit Card : {{$payment->credit_card_bank}} {{$payment->credit_card_type}}</div>
        <div>Credit Card No : {{substr_replace($payment->credit_card_number,"***********",0,12)}}</div>
        <div>Approval No : {{$payment->approval_number}}</div>
        <div>Amount : {{number_format($payment->credit_card_amount,2)}}</div>
        @endif
        @if($payment->deposit_amount>0)
        <div>Deposit Ref : {{$payment->deposit_reference}}</div>
        <div>Deposit Amount : {{number_format($payment->deposit_amount,2)}}</div>
        @endif
        </div> 
        <div class="col-md-12">
            <div class="form form-group">
            <a class="btn btn-danger pull-right" target="_blank" href="{{url("/cashier",array("printreceipt",$payment->reference_id))}}">Print Receipt</a>
            @if(Auth::user()->idno == $payment->posted_by && $payment->transaction_date == date('Y-m-d'))
            <a class="btn btn-primary" id="cancelrestore" href="{{url("/cashier",array("reverserestore",$payment->reference_id))}}">
            @if($payment->is_reverse=="0")    
                Cancel
            @else
                Restore
            @endif
            </a>
            @endif
            
            <a class="btn btn-primary"  href="{{url("/cashier",array("viewledger",$payment->idno))}}">Back To Ledger</a>
           
            </div>
            
            </div>  
    </div>
    <div class="col-md-6">
        <div class="box-body">
        <h5>Accounting Entry Details</h5>
        @if(count($accountings)>0)
        <?php $totaldebit=0; $totalcredit=0;?>
            <table class="table table-striped table-responsive">
                <tr><td>Entry Date</td><td>Accounting Code</td><td>Accounting Name</td><td align="center">Debit</td><td align="center">Credit</td><td>Status</td></tr>
                @foreach($accountings as $accounting)
                <?php $totalcredit=$totalcredit+$accounting->credit;
                      $totaldebit=$totaldebit+$accounting->debit;  
                ?>
                <tr><td>{{$accounting->transaction_date}}</td>
                    <td>{{$accounting->accounting_code}}</td>
                    <td>{{$accounting->accounting_name}}</td>
                    <td align="right">{{number_format($accounting->debit,2)}}</td>
                    <td align="right">{{number_format($accounting->credit,2)}}</td>
                    <td>@if($accounting->is_reverse==0)OK @else Canceled @endif</td>
                    </tr>
                @endforeach
                <tr><td colspan="3">Total</td><td align="right">{{number_format($totaldebit,2)}}</td>
                    <td>{{number_format($totalcredit,2)}}</td><td></td></tr>
            </table> 
        @endif
        </div>
          
    </div>    
</div>    
@endsection
@section('footerscript')
<style>
 .table{border-color: #000;}
</style>
<script>
  $(document).ready(function(){
     $("#cancelrestore").on('click',function(e){
         if(confirm("Are You Sure ?")){
             return true;
         }else{
             return false;
             e.preventDefault();
         }
     }) 
  }); 
</script>    
@endsection


