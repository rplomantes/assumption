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
        Pre Registration Payment
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class='active'><a href="javascript:void(0)"> Pre Registration Payment</a></li>
      </ol>
</section>
@endsection
@section('maincontent')
<div class="container-fluid">
  <form id="paymentform" class="form-horizontal" method="POST" action="{{url('/cashier','pre_registration_payment')}}">
    <div class="col-md-12">
        <div class="col-md-6">
            <table class="table table-responsive"><tr><td>Date(YYYY-MM-DD) : </td><td><input type="text" name="date" value="{{date("Y-m-d")}}" placeholder="YYYY-MM-DD" id="date" class="form form-control"></td></tr>     
            </table>
        </div>  
        <div class="col-md-6"><div class="nav navbar pull-right"> Receipt No: <span style="font-size:20pt;font-weight:bold;color:red">{{$receipt_number}}</span></div></div>
    </div>    
   <hr />  
  
      {{csrf_field()}}
 
    <div class="col-md-6">
     <div id="detailed_form">   
        <div class="form form-group">    
            <div class="crcform">
                <h3>Pre Registration Payment Details</h3>
                <div class="form form-group">
                    <div class="col-md-12">
                        <label>Paid By :</label>
                        <select class='form form-control select2' id='paid_by' name='paid_by'>
                            <option>Select Applicant</option>
                            @foreach($applicants as $applicant)
                            <option value='{{$applicant->idno}}'>{{$applicant->idno}}-{{$applicant->lastname}}, {{$applicant->firstname}} {{$applicant->middlename}}</option>
                            @endforeach
                        </select>
                     </div>   
                </div>    
                <div class="form form-group">
                        
                        <div class="col-md-5">   
                            Particular
                        </div>

                        <div class="col-md-5">
                            Amount
                        </div>
                        <div class="col-md-2">
                        
                        </div>
                </div> 
                
             <div  id="dynamic_field">
                        <!--div class="top-row"-->
                        <div class="form form-group">
                        <div class="col-md-5">
                            <input type='text' name="particular[]" id="particular1" class="form form-control" readonly="" value='Testing Fee'>
                        </div>

                        <div class="col-md-5">
                            <input class="form form-control number" type="text" onkeypress="totalOther(event)" onkeyup = "toNumeric(this)" name="other_amount[]" id="other_amount1" />
                        </div>
                        </div>    
            </div>
                
        <div class="form form-group">
        <div class="col-md-5 col-md-offset-5">
           Total : <input disabled="disabled" type="text" class="form form-control number" name="other_total" id="other_total" value="0.00">
        </div>
        </div> 
        <div class="form form-group">
        <div class="col-md-10">
            <div id="donereg">
            <label>Details:</label>
            <input type="text" name="remark" id="remark" class="form form-control">
           <!-- <buton class="btn btn-primary form-control" id="donereg">Next <i class="fa fa-chevron-right"></i><i class="fa fa-chevron-right"></i></buton>-->
            </div>
            </div>     
        </div>   
            
            
      </div>
    
     </div>  
   </div>    
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
                   <option>Debit</option>
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
    <div class="over_payment">
        <div class="form form-group">
        
        <div class="col-md-12">
            <label>Over Payment</label>
            <input type="text" name="over_payment" id="over_payment" class="form form-control number" value="0" readonly="readonly">
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
<link rel="stylesheet" href="{{url('/',array('bower_components','select2','dist','css','select2.min.css'))}}">
<style>
    .fees td input{
        background-color: #ccc;
    }
    .label_collected{
        font-size:15pt;
        font-weight: bold;
    }
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
    #collected_amount{
        color:#f00;
        font-weight: bold;
        font-size: 12pt;
    }
    .over_payment{
        background-color: #B995A9;
        padding: 10px
    }
</style>

<script src="{{url('/',array('bower_components','select2','dist','js','select2.full.min.js'))}}"></script>

<script>
    $(document).ready(function(){
        jQuery('input[type=submit]').click(function(){
            if(jQuery.data(this, 'clicked')){
                return false;
            } else{
                jQuery.data(this, 'clicked', true);
                return true;
            }
        });
        
         var i = 1;
         $('.select2').select2();
         $('#add').click(function(){
         if($("#explanation"+i).val()=="" || $("#other_amount" + i).val()==""){
         alert("Please Fill-up Required Fields " + $("#subsidiary" + i).val());
           } else {   
        i++;
        $('#dynamic_field').append('<div id="row'+i+'" class="form form-group">\n\
        <div class="col-md-5">\n\
        <select class="form form-control select2" type="text" onkeypress = "gotoother_amount('+i+',event)" name="particular[]" id="particular'+i+'">'
         @foreach($particulars as $particular) + '<option>{{$particular->subsidiary}}</option>'  @endforeach 
         + '</select></div>\n\
        <div class="col-md-5"><input class="form form-control number" type="text" onkeypress="totalOther(event)" onkeyup = "toNumeric(this)" onkeypress = "totalOther(event)" name="other_amount[]" id="other_amount'+i+'"/></div>\n\
        <div class="col-md-2"><a href="javascript:void()" name="remove"  id="'+i+'" class="btn btn-danger btn_remove">X</a></div></div>');
        $("#particular"+i).focus();
        $("#donereg").fadeOut();
        $("#payment_pad").fadeOut();
        updatefunction();
        }});
            
            $('#dynamic_field').on('click','.btn_remove', function(){
                //alert($(this).attr("id"))
                var button_id = $(this).attr("id");
                $("#row"+button_id+"").remove();
                i--;
                totalamount =0;
                other_amount = document.getElementsByName('other_amount[]');
                for(var i = 0; i < other_amount.length; i++){
                if(other_amount[i].value != ""){    
                totalamount = totalamount+parseFloat(other_amount[i].value)
                }
                }
                $("#other_total").val(totalamount.toFixed(2))
                $("#donereg").fadeIn(300);
            }); 
            
        $("#submit_button").fadeOut(300);
        $("#donereg").fadeOut(300);
        $("#payment_pad").fadeOut(300);
        
        $("#remark").on('keypress',function(e){
            if(e.keyCode==13){
                if($("#remark").val() == ""){
                    alert("Please Fillup Details");
                }else{
                    $("#payment_pad").fadeIn();
                    $("#cash_receive").focus();
                }
                e.preventDefault();
            }
        })
        
        $(".number").on('keypress',function(e){
        var theEvent = e || window.event;
        var key = theEvent.keyCode || theEvent.which;
        if ((key < 48 || key > 57) && !(key == 8 || key == 9 || key == 13 || key == 37 || key == 39 || key == 46) ){ 
        theEvent.returnValue = false;
        if (theEvent.preventDefault) theEvent.preventDefault();
        }
    })
        $("#reservation").on("keypress",function(e){
            if(e.keyCode==13){
               if($("#reservation").val()=="" && $("#deposit").val()==""){
                    alert("Please put amount to Reservation or Student Deposit")
                    $("#reservation").focus();
                }else{
                computechange();    
                $("#cash_receive").focus();
                }
                e.preventDefault();
            }
        })
        
        $("#deposit").on("keypress",function(e){
            if(e.keyCode==13){
                if($("#reservation").val()=="" && $("#deposit").val()==""){
                    alert("Please put amount to Reservation or Student Deposit")
                    $("#deposit").focus();
                }else{
                $("#cash_receive").focus();
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
                   alert("Invalid amount")
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
       $("#date").on('keypress',function(e){
           if(e.keyCode==13){
           $("#other_amount1").focus();
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
       
       $("#paid_by").on("keypress",function(e){
           if(e.keyCode==13){
               e.preventDefault();
           }
       });
    
    });
    
    function updatefunction(){
    $('.select2').select2();
    $(".number").on('keypress',function(e){
        var theEvent = e || window.event;
        var key = theEvent.keyCode || theEvent.which;
        if ((key < 48 || key > 57) && !(key == 8 || key == 9 || key == 13 || key == 37 || key == 39 || key == 46) ){ 
        theEvent.returnValue = false;
        if (theEvent.preventDefault) theEvent.preventDefault();
        }});
    }
    
    function computechange(){
         totalamount = 0;
         amountreceive= 0;
         noncash=0;
         check_amount=0;
         deposit_amount=0;
         if($("#other_total").val()!=""){
            totalamount = totalamount + eval($("#other_total").val())
        }
         
         if($("#check_amount").val()!=""){
            check_amount= eval($("#check_amount").val());
            noncash = noncash + eval($("#check_amount").val())
        }
         if($("#credit_card_amount").val()!=""){
            noncash = noncash + eval($("#credit_card_amount").val())
        }
        if($("#deposit_amount").val()!=""){
            deposit_amount= eval($("#deposit_amount").val());
            noncash = noncash + eval($("#deposit_amount").val())
        }
         
        if(noncash > totalamount){
            if(noncash-(check_amount+deposit_amount)>totalamount){
             alert("Invalid Amount !!!!!")
            } else {
             $("#over_payment").val((check_amount+deposit_amount)-totalamount)
             if($("#cash_receive").val()!=""){
                   amountreceive = eval($("#cash_receive").val());
               } 
              if(amountreceive+noncash-totalamount >= 0){
                  $("#submit_button").fadeIn(300)
                  $("#submit_button").focus();
              }else{
                  $("#submit_button").fadeOut(300)
              }
              $('#cash_receive').val(0);
              $('#credit_card_amount').val(0);
              $("#change").val(0)
              return "0.00";
            }
            } else {
                $("#over_payment").val(0)
             if($("#cash_receive").val()!=""){
                   amountreceive = eval($("#cash_receive").val());
               } 
              if(amountreceive+noncash-totalamount >= 0){
                  $("#submit_button").fadeIn(300)
                  $("#submit_button").focus();
              }else{
                  $("#submit_button").fadeOut(300)
              }
              totalchange = amountreceive+noncash-totalamount
               $("#change").val(totalchange.toFixed(2));
               return totalchange.toFixed(2);
         }
    }
    function gotoother_amount(i,evt){
        if(evt.keyCode==13){
            $("#other_amount" + i).focus()
            evt.preventDefault()
            return false;
        }
    }
    
   function totalOther(e){
           if(e.keyCode == 13){
                        totalamount =0;
                        other_amount = document.getElementsByName('other_amount[]');
                        for(var i = 0; i < other_amount.length; i++){
                        totalamount = totalamount+parseFloat(other_amount[i].value)
                        }
                        $("#other_total").val(totalamount.toFixed(2))
                        //$("#add").focus();
                        $("#donereg").fadeIn(300)
                        $("#remark").focus()
                         e.preventDefault();
                         return false;
                 }
        }
</script>    
@endsection

