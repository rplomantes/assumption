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
    
    <div class="col-md-6 official_receipt">
        
        <div class="col-md-2 image img-responsive"> <img width="86"src="{{url('/images','assumption-logo.png')}}" ></div>
        <div class="col-md-10"><div class="logo">Assumption College</div>
        San Lorenzo Drive, San Lorenzo Village<br> Makati City<br> NON VAT REG. TIN 000-662-720-000</div>
        <div class="col-md-4 pull-right">
            <div class="orno">OR No. : {{$payment->receipt_no}}</div>
        </div>
        <div class="col-md-12 orheader">OFFICIAL RECEIPT</div>
        <div class="col-md-12">
        <table class="table">
        <tr><th>Name:</th><td><b> {{$payment->paid_by}}</b></td><td align="right">{{date('M d, Y',strtotime($payment->transaction_date))}}</td><tr>
        @if(count($status)>0)
        @if($status->status==3)
            @if($status->department=="College")
            <tr><th>Course / Level</th><td>{{$status->program_code}} / {{$tatus->level}}</td><td></td></tr>
            @else
            <tr><th>Level / Section</th><td>{{$status->level}}</td><td></td></tr>
            @endif
        @endif 
        @endif
        <tr><th></th><td align="right"></td></tr>
        </table>
       
        
        <?php $totalreceipt=0;?>
        <table class="table table-striped table-hover"><thead><tr><th>Particular</th><th>Amount</th></tr></thead>
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
        <tr><td>Total</td><td align="right"><span class="totalreceipt">{{number_format($totalreceipt,2)}}</span></td><tr>
        </table>
        
        <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
        <b>Explanation:</b><br>{{$payment->remarks}}
        </p>
        <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
        <b>Payment Details:</b><br>  
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
        <p class="" style="margin-top: 10px;">
            Posted by: <b>{{\App\User::where('idno',$payment->posted_by)->first()->firstname}} {{\App\User::where('idno',$payment->posted_by)->first()->lastname}}</b>
        </p>
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
        
    </div>    
    <div class="col-md-6">
        <div class="box-body">
        <label>Accounting Entry Details</label>
        @if(count($accountings)>0)
        <?php $totaldebit=0; $totalcredit=0;?>
            <table class="table table-striped table-responsive">
                <tr><th>Entry Date</th><th>Acctg Code</td><th>Accounting Name</th><th>Particukar</th><th align="center">Debit</th><th align="center">Credit</th><th>Status</td></tr>
                @foreach($accountings as $accounting)
                <?php $totalcredit=$totalcredit+$accounting->credit;
                      $totaldebit=$totaldebit+$accounting->debit;  
                ?>
                <tr><td>{{$accounting->transaction_date}}</td>
                    <td>{{$accounting->accounting_code}}</td>
                    <td>{{$accounting->accounting_name}}</td>
                    <td>{{$accounting->subsidiary}}</td>
                    <td align="right">{{number_format($accounting->debit,2)}}</td>
                    <td align="right">{{number_format($accounting->credit,2)}}</td>
                    <td>@if($accounting->is_reverse==0)OK @else Canceled @endif</td>
                    </tr>
                @endforeach
                <tr><td colspan="4">Total</td><td align="right">{{number_format($totaldebit,2)}}</td>
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
 .orno{text-align: right;
        color:#f00;
        font-size: 15pt;
 }
 .orheader{
     text-align: center;
     font-size: 18pt;
     font-weight: bold;
     text-decoration: underline;
 }
 .totalreceipt{
     color:darkblue;
     font-weight: bold;
     font-size: 12pt;
 }
 .official_receipt{
     padding: 10px;
     background-color: #fff;
     
 }
 .logo{
     font-size: 20pt;
     font-weight: bold;
     color: darkblue;
 }
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


