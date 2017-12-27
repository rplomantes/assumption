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
        Deposit Slips
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Deposit slips</li>
      </ol>
</section>
@endsection
@section('maincontent')
<div class="container-fluid">
 <div class="col-md-3">   
  <div class="form-group">
                <label>Date:</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" value="{{$transaction_date}}" class="form-control pull-right" id="datepicker">
                </div>
  </div>
 </div>
    <div class="clearfix"></div>
    <div class="box">
        <div class="box-header">    
<h4>Computed Receipt Collection</h4>
        </div>
        <div class="box-body">     
<div class="col-md-2">
    <div class="form form-group">   
        <label>Bank Deposit</label>
        <div class="form-control deposit">{{number_format($payments->deposit_amount,2)}}</div>
    </div>    
</div> 
<div class="col-md-2">
    <div class="form form-group">
        <label>Credit Card</label>
        <div class="form-control deposit">{{number_format($payments->credit_card_amount,2)}}</div>
    </div>    
</div> 
<div class="col-md-2">
    <div class="form form-group">
        <label>Cash</label>
        <div class="form-control deposit">{{number_format($payments->cash_amount,2)}}</div>
    </div>    
</div>
<div class="col-md-2">
    <div class="form form-group">
        <label>Check</label>
        <div class="form-control deposit">{{number_format($payments->check_amount,2)}}</div>
    </div>    
</div> 
<div class="col-md-3">  
    <div class="form form-group">   
        <label>Total Cash and Check Collection</label>
            <div class="form-control deposit cash">{{number_format($payments->cash_amount+$payments->check_amount,2)}}</div>
    </div>    
</div>  
</div>  
</div>        
<div class="clearfix"></div>
<div class="box">
<div class="box-header">
<h4>Add Deposit</h4>
    </div>
<div class="box-body">
@if($transaction_date == date('Y-m-d'))    
<form method="post" action="{{url('/cashier',array('deposit_slip'))}}">
{!!csrf_field()!!}    
<div class="col-md-2">
    <div class="form form-group">
        <label>Type</label>
        <select id="deposit_type" name="deposit_type" class="form form-control">
            <option value="0">Cash</option>
            <option value="1">Check</option>
        </select>    
    </div>
</div>


<div class="col-md-3">
    <div class="form form-group">
        <label>Amount</label>
        <input type="text" id="amount" name="amount" class="from form-control number"> 
    </div>    
</div>
        
<div class="col-md-3">
    <div class="form form-group">
        <label>Particular</label>
        <input type="text" id="particular" name="particular" class="from form-control"> 
    </div>    
</div>
<div class="col-md-2">
    <div class="form form-group">
        <label>&nbsp;</label>
        <input type="submit" id="submit" class="form form-control btn btn-danger" value="Add Deposit"> 
    </div>    
</div>
</form>
@endif    
    <div class="clearfix"></div>
    <?php  $total_checks=0;$total_cash=0;?>
<div class="col-md-12"> 
    @if(count($deposit_cash)>0)
    <?php $count=1;?>
    <table class="table table-responsive"><tr><th width="10%">#</th><th width="30%">Amount</th><th width="50%">Particular</th><th width="10"></th></tr>
    @foreach($deposit_cash as $deposit)
    <?php $total_cash=$total_cash+$deposit->deposit_amount;?>
    <tr><td>{{$count++}}</td><td>{{number_format($deposit->deposit_amount,2)}}</td><td>{{$deposit->particular}}</td><td><a class="remove_deposit" href="{{url('/cashier',array('remove_deposit',$deposit->id))}}">Remove</a></td></tr>
    @endforeach
    </table>
    @else
    <h5>No Cash Deposit Has Been Made yet!!!</h5>
    @endif
    
    
    @if(count($deposit_check)>0)
    <?php $count=1;?>
     <table class="table table-responsive"><tr><th width="10%">#</th><th width="30%">Amount</th><th width="50%">Particular</th><th width="10"></th></tr>
    @foreach($deposit_check as $deposit)
    <?php $total_checks = $total_checks + $deposit->deposit_amount;?>
    <tr><td>{{$count++}}</td><td>{{number_format($deposit->deposit_amount,2)}}</td><td>{{$deposit->particular}}</td><td><a class="remove_deposit" href="{{url('/cashier',array('remove_deposit',$deposit->id))}}">Remove</a></td></tr>
    @endforeach
    </table>
    @else
    <h5>No Check Deposit Has Been Made yet!!!</h5>
    @endif
</div>     
</div>          
</div>
   
    <div class="box">
        <div class="box-header">
            <h4>Variance</h4>
        </div>
        <div class="box-body">
            <div class="col-md-7">
                <table class="table table-responsive"><tr><th></th><th>Deposit</th><th>Computed Receipt</th><th>Variance</th></tr>
                <tr><th>Cash</th><td>{{number_format($total_cash,2)}}</td><td>{{number_format($payments->cash_amount,2)}}</td><td>{{number_format($total_cash-$payments->cash_amount,2)}}</td></tr>
                <tr><th>Check</th><td>{{number_format($total_checks,2)}}</td><td>{{number_format($payments->check_amount,2)}}</td><td>{{number_format($total_checks-$payments->check_amount,2)}}</td></tr>
                </table>
                <?php $total_variance=$total_cash-$payments->cash_amount+$total_checks-$payments->check_amount;?>
            </div>  
            <div class="col-md-5">
                @if($total_variance >0)
                <label>Total Overages</label>
                <div id="variance_overage">
                    {{number_format($total_variance,2)}}
                </div>
                @elseif($total_variance==0)
                <label>No Variance</label>
                <div id="variance_no">
                    {{number_format($total_variance,2)}}
                </div>
                @else
                <label>Total Shortages</label>
                <div id="variance_shortage">
                    {{number_format($total_variance,2)}}
                </div>
                @endif
            </div>    
        </div>
    </div>    
</div>      
@endsection
@section('footerscript')
<!-- bootstrap datepicker -->
  <link rel="stylesheet" href="{{url('/bower_components',array('bootstrap-datepicker','dist','css','bootstrap-datepicker.min.css'))}}">
<!-- bootstrap datepicker -->
<script src="{{url('/bower_components',array('bootstrap-datepicker','dist','js','bootstrap-datepicker.min.js'))}}"></script>
  <style>
    .deposit{
        text-align: right;
        font-weight: bold;
    }
    
    .cash{
        font-size:14pt;
        color:maroon;
    }
    .number{
        text-align: right;
    }
    #variance_overage{
        font-size:20pt;
        font-weight: bold;
        text-align: center;
        padding: 10px;
        color: green;
        background-color: #FFEFC1;
    }
    #variance_shortage{
        font-size:20pt;
        font-weight: bold;
        text-align: center;
        padding: 10px;
        color: red;
        background-color: #d3d3d3;
    }
    #variance_no{
        font-size:20pt;
        font-weight: bold;
        text-align: center;
        padding: 10px;
        color: darkblue;
        background-color: #B5D1D8;
    }
</style>
<script>
$(document).ready(function(){
     $("#amount").focus();
     $('#datepicker').datepicker({
      autoclose: true,
      format:'yyyy-mm-dd',
    })
    $(".remove_deposit").on("click",function(e){
        if(confirm("Are You Sure To Remove This Deposit slip ?")){
            return true;
        } else {
            return false;
            e.preventDefault();
        }
    })
    $("#datepicker").on('change',function(e){
        document.location = "/cashier/deposit_slip/" + $("#datepicker").val()
    })
    $(".number").on('keypress',function(e){
        var theEvent = e || window.event;
        var key = theEvent.keyCode || theEvent.which;
        if ((key < 48 || key > 57) && !(key == 8 || key == 9 || key == 13 || key == 37 || key == 39 || key == 46) ){ 
        theEvent.returnValue = false;
        if (theEvent.preventDefault) theEvent.preventDefault();
        }
    })
    $("#deposit_type").on('change', function(e){
        $("#amount").focus()
    })
    $("#amount").on('keypress',function(e){
        if(e.keyCode==13){
            if($("#amount").val()==""){
                alert("Invalid amount")
            }else{
            $("#particular").focus();
        }
        e.preventDefault();
        }
    })
    
    $("#particular").on("keypress",function(e){
        if(e.keyCode==13){
            $("#submit").focus()
            e.preventDefault();
        }
    })
})
</script>
@endsection


