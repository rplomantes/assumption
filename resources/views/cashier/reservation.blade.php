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
        <li><a href="{{url("/cashier",array('viewledger',$user->idno))}}"> Student Ledger</a></li>
        <li class="active">Reservation</li>
      </ol>
</section>
@endsection
@section('maincontent')
<div class="container-fluid">
   <form method="post" action="{{url('cashier','reservation')}}" class="form-horizontal">
    {{csrf_field()}}
    <input type="hidden" name="idno" value="{{$user->idno}}">
    <div class="col-md-12">
    <div class="form form-group">  
        <table class="table table-bordered">
            <tr><td>Student ID</td><td>{{$user->idno}}</td><td align="right"> Receipt No: <span style="font-size:14pt;font-weight:bold;color:red">{{$receipt_no}}</span></td></tr>
            <tr><td>Student Name</td><td><b>{{$user->lastname}}, {{$user->firstname}} {{$user->middlename}}</b></td><td></td></tr>
        </table>
        <hr/>
    </div>
    </div>    
    <div class="col-md-6">
       <div class="top-payment">
        <div class="form form-group">
            <div class="col-md-6">
            <label>Reservation</label>
                <input type="text" name="reservation" id="reservation" class="form form-control number" />
            </div> 
            <div class="col-md-6">
            <label>Student Deposit</label>
                <input type="text" name="deposit" id="deposit" class="form form-control reservation number" />
            </div> 
            <div class="col-md-12">
            <label>Explanation</label>
                <input type="text" name="remark" id="explanation" class="form form-control reservation" />
            </div> 
        </div>    
    </div> 
        
        @if(count($reservations)>0)
        <label>Reservation</label>
        <table class="table table-striped">
            <tr><td>Date</td><td>Amount</td><td>Status</td></tr>
            @foreach($reservations as $reservation)
            <tr><td>{{$reservation->transaction_date}}</td>
                <td align="right">{{number_format($reservation->amount,2)}}</td>
                <td>@if($reservation->is_consumed=="1")
                    <i class="fa fa-times"></i>
                    @else
                    <i class="fa fa-check"></i>
                    @endif
                    </td>
                </tr>
            @endforeach
        </table>    
        @else
        <h5> No Reservation Has Been Made Yet!!!!!</h5>
        <hr/>
        @endif
        @if(count($deposits)>0)
        <label>Student Deposit</label>
        <table class="table table-striped">
            <tr><td>Date</td><td>Amount</td><td>Status</td></tr>
            @foreach($deposits as $reservation)
            <tr><td>{{$reservation->transaction_date}}</td>
                <td align="right">{{number_format($reservation->amount,2)}}</td>
                <td>@if($reservation->is_consumed=="1")
                    <i class="fa fa-times"></i>
                    @else
                    <i class="fa fa-check"></i>
                    @endif
                    </td>
                </tr>
            @endforeach
        </table>    
        @else
        <h5> No Student Deposit Has Been Made Yet!!!!!</h5>
        <hr>
        @endif
    </div> 
    
 <div class="col-md-6">
   <div id="payment_pad">  
    <div class="cash-payment">
        <div class="form form-group">
            <div class="col-md-6"> 
            <label>Cash Receive</label>
                <input type="text" name="cash_receive" id="cash_receive" class="form form-control number" />
            </div>
            <div class="col-md-6"> 
            <label>Change</label>
            <input type="text" name="change" id="change" class="form form-control change" readonly="readonly" />
            </div>
        </div> 
   </div>
    
    
   <div class="check_payment">
       <label>Check Payment</label>
        <div class="form form-group">
            <div class="col-md-6">
                <label>Bank</label>
                <input type="text" name="bank" id="bank" class="form form-control" />
            </div>
            
            <div class="col-md-6">
                <label>Check Number</label>
                <input type="text" name="check_number" id="check_number" class="form form-control" />
            </div>
            
            <div class="col-md-12">
                <label>Check Amount</label>
                <input type="text" name="check_amount" id="check_amount" class="form form-control number" />
            </div>
        </div>    
   </div>
    
    <div class="credit_card">
        <label>Credit Card Payment</label> 
        <div class="form form-group">
            <div class="col-md-2">
               <label>Bank</label>
               <input type="text" name="credit_card_bank" id="credit_card_bank" class="form form-control" />
            </div>
            <div class="col-md-3">
                <label>Type</label>
               <select class="form-control" name="credit_card_type" id="credit_card_type">
                   <option>Visa</option>
                   <option>Mastercard</option>
                   <option>AMEX</option>
               </select>    
            </div>    
            <div class="col-md-4">    
            <label>Card Number</label>
            <input type="text" name="card_number" id="card_number" class="form form-control" />
            </div>
            <div class="col-md-3">
            <label>Approval No</label>
            <input type="text" name="approval_number" id="approval_number" class="form form-control" />
            </div>
            <div class="col-md-12">
            <label>Credit Card Amount</label>
            <input type="text" name="credit_card_amount" id="credit_card_amount" class="form form-control number" />
            </div>
            </div>
     </div> 
    <div class="bank_deposit">
        <label>Bank Deposit</label>
        <div class="form form-group">
            <div class="col-md-6">
               <label>Deposit Reference No</label>
               <input type="text" name="deposit_reference" id="deposit_reference" class="form form-control" />
            </div>
            <div class="col-md-6">
            <label>Deposit Amount</label>
            <input type="text" name="deposit_amount" id="deposit_amount" class="form form-control number" />
            </div>
        </div>    
    </div>    
     <div class="submit_button">
        <div class="form form-group">
            <div class="col-md-12"> 
                <input type="submit" name="submit" id="submit_button" value="Process Payment" class="btn btn-warning form form-control">
            </div>
        </div>
     </div>    
    </div>
    </div> 
      </form>
</div>    
@endsection
@section('footerscript')
<style>
    .submit_button{
        padding-top:10px;
    }
    .check_payment{
        background-color:#d3d3d3;
        padding: 10px;
        
    }
    .credit_card{
        background-color:#b1dae7;
        padding: 10px;
    }
    .top-payment{
         background-color: #E9C062;
        padding: 10px; 
    }
    .cash-payment{
        background-color: #b1dae7;
        padding: 10px
    }
    .bank_deposit{
        background-color: #d3d3d3;
        padding: 10px
    }
    .number{
        text-align: right;
    }
    .change{
        text-align:right;
        color:#f00;
        font-weight: bold;
    }
</style>
<script>
    $(document).ready(function(){
        $("#submit_button").fadeOut(300);
        $("#payment_pad").fadeOut(300)
        $(".number").on('keypress',function(e){
        var theEvent = e || window.event;
        var key = theEvent.keyCode || theEvent.which;
        if ((key < 48 || key > 57) && !(key == 8 || key == 9 || key == 13 || key == 37 || key == 39 || key == 46) ){ 
        theEvent.returnValue = false;
        if (theEvent.preventDefault) theEvent.preventDefault();
        }
        });
        $("#explanation").on("focusin",function(e){
            $("#payment_pad").fadeOut(300)
        })
        $("#reservation").on("focusin",function(e){
            $("#payment_pad").fadeOut(300)
        })
        $("#deposit").on("focusin",function(e){
            $("#payment_pad").fadeOut(300)
        })
        
        $("#explanation").on("keypress",function(e){
           if(e.keyCode==13){
               if($("#explanation").val()==""){
                   alert("Please enter explanation!!!")
                   $("#explanation").focus();
               }else{
               if($("#reservation").val()=="" && $("#deposit").val()==""){
                   alert("Please Enter Amount on Reservation or Student Deposit")
               }else{
                   $("#payment_pad").fadeIn(300);
                   $("#cash_receive").focus();
               }}
              e.preventDefault(); 
           } 
        });
        $("#reservation").on("keypress",function(e){
            if(e.keyCode==13){
               if($("#reservation").val()==""){
                    $("#deposit").focus();
                }else{   
                $("#explanation").focus();
                }
                e.preventDefault();
            }
        })
        
        $("#deposit").on("keypress",function(e){
            if(e.keyCode==13){
                if($("#deposit").val()==""){
                    alert("Please put amount to Reservation or Student Deposit")
                    $("#deposit").focus();
                }else{
                $("explanation").focus();
                }
                e.preventDefault();
            }
        })
        
       $("#cash_receive").on("keypress",function(e){
           if(e.keyCode==13){
               if(computechange() < 0){
                    $("#bank").focus();
               }
               e.preventDefault();
            }
           
       }) 
       $("#bank").on("keypress",function(e){
           if(e.keyCode==13){
               $("#check_number").focus()
               e.preventDefault();
           }
       })
       
       $("#check_number").on("keypress",function(e){
           if(e.keyCode==13){
               $("#check_amount").focus()
               e.preventDefault();
           }
       })
       $("#check_amount").on("keypress",function(e){
           if(e.keyCode==13){
               if($("#check_amount").val()==""){
                   alert("Inavlid amount")
               }else{
                   if(computechange()<0){
                     $("#credit_card_bank").focus();  
                   }
               }
               e.preventDefault();
           }
       })
       
       $("#credit_card_bank").on('keypress',function(e){
           if(e.keyCode==13){
           $("#credit_card_type").focus();
            e.preventDefault();
           }
       })
       $("#credit_card_type").on('keypress',function(e){
           if(e.keyCode==13){
           $("#card_number").focus();
            e.preventDefault();
           }
       })
       $("#card_number").on('keypress',function(e){
           if(e.keyCode==13){
           $("#approval_number").focus();
            e.preventDefault();
           }
       })
       $("#approval_number").on('keypress',function(e){
           if(e.keyCode==13){
           $("#credit_card_amount").focus();
            e.preventDefault();
           }
       })
       
       $("#credit_card_amount").on("keypress",function(e){
           if(e.keyCode==13){
               if($("#credit_card_amount").val()==""){
                   alert("Invalid Amount")
               }else{
                   if(computechange()<0){
                     $("#deposit_reference").focus();  
                   }
               }
               e.preventDefault();
           }
       })
       
       $("#deposit_reference").on('keypress',function(e){
           if(e.keyCode==13){
           $("#deposit_amount").focus();
            e.preventDefault();
           }
       })
       $("#deposit_amount").on("keypress",function(e){
           if(e.keyCode==13){
              if(computechange()<0){
                   alert("Invalid Amount");  
                   }
               e.preventDefault();
           }
       })
    })
    
    function computechange(){
         totalamount = 0;
         amountreceive= 0;
         noncash=0;
         if($("#reservation").val()!=""){
            totalamount = totalamount + eval($("#reservation").val())
        }
         if($("#deposit").val()!=""){
            totalamount = totalamount + eval($("#deposit").val());
        }
         if($("#check_amount").val()!=""){
            noncash = noncash + eval($("#check_amount").val())
        }
         if($("#credit_card_amount").val()!=""){
            noncash = noncash + eval($("#credit_card_amount").val())
        }
        if($("#deposit_amount").val()!=""){
            noncash = noncash + eval($("#deposit_amount").val())
        }
         
        if(noncash > totalamount){
             alert("Invalid Amount !!!!!")     
         } else {
             if($("#cash_receive").val()!=""){
                   amountreceive = eval($("#cash_receive").val());
               } 
              if(amountreceive+noncash-totalamount >= 0){
                  $("#submit_button").fadeIn(300)
                  $("#submit_button").focus();
              }else{
                  $("#submit_button").fadeOut(300)
              }
              totalchange=amountreceive+noncash-totalamount
               $("#change").val(totalchange.toFixed(2));
               return totalchange.toFixed(2);
         }
    }
</script>    
@endsection

