<?php
$other=0;
$miscellaneous=0;
$depository=0;
$optional=0;
$srf=0;
$tuition=0;

if($other_fee_total->balance>0)
    $other = $other_fee_total->balance;

if($optional_fee_total->balance>0)
    $optional = $optional_fee_total->balance;

if($miscellaneous_fee_total->balance>0)
    $miscellaneous=$miscellaneous_fee_total->balance;

if($depository_fee_total->balance>0)
    $depository=$depository_fee_total->balance;

if($srf_total->balance>0)
    $srf=$srf_total->balance;

if($tuition_fee_total->balance>0)
    $tuition=$tuition_fee_total->balance;

$total_max = $other+$miscellaneous+$depository+$srf+$tuition+$optional;
$accountings = \App\ChartOfAccount::orderBy('accounting_code')->get();
?>
<?php 
    if (Auth::user()->accesslevel==env("ACCTNG_STAFF")){
        $layout = "layouts.appaccountingstaff";    
    }else if (Auth::user()->accesslevel==env("ACCTNG_HEAD")){
        $layout = "layouts.appaccountinghead";    
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
<?php $sy = \App\CtrAcademicSchoolYear::where('academic_type', 'BED')->first()->school_year; ?>
<section class="content-header">
      <h1>
        Debit Memo
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url("/cashier",array('viewledger',$sy,$user->idno))}}"> Debit Memo</a></li>
        <li class="active">Main Payment</li>
      </ol>
</section>
@endsection
@section('maincontent')
<div class="container-fluid">
    <div class="col-md-12">
        <div class="col-md-6">
            <table class="table table-responsive"><tr><td>Date : </td><td>{{date("M d, Y")}}</td></tr>
                    <tr><td>Student ID : </td><td>{{$user->idno}}</td></tr>
                    <tr><td>Student Name : </td><td>{{$user->lastname}}, {{$user->firstname}} {{$user->middlename}}</td></tr>
                    </table>
        </div>  
        <div class="col-md-6"><div class="nav navbar pull-right"> DM No: <span style="font-size:20pt;font-weight:bold;color:red">{{$receipt_number}}</span></div></div>
    </div>    
   <hr />  
  <form id="paymentform" class="form-horizontal" method="POST" action="{{url('/accounting','debit_memo')}}">
  
      {{csrf_field()}}
           <input type="hidden" name="idno" value="{{$user->idno}}">
           <input type="hidden" name="receipt_no" value="{{$receipt_number}}">

           
    <div class="col-md-6">
        <div id="detailed_form">   
            <div class="form form-group">    
                <div class="crcform">
                    @if(count($previous_total)>0)
                    @if($previous_total->balance > 0)
                    <div class="form form-group">
                    <div class="col-md-6">
                        <span class="label_collected">Previous Balance :</span>
                    </div> 
                     <div class="col-md-6">
                         <!--<input type="hidden" id="previous_balance" name="previous_balance" value="{{$previous_total->balance}}">-->
                        <input type="text" class="form form-control number" name="previous_balance" id="previous_balance" value="{{$previous_total->balance}}" >
                     </div>
                     </div>   
                    @else
                        <input type="hidden" id="previous_balance" name="previous_balance" value="0.00">
                    @endif
                    @else
                        <input type="hidden" id="previous_balance" name="previous_balance" value="0.00">
                    @endif
                    <div class="form form-group">
                    <div class="col-md-6">
                        <span class="label_collected">Main Account :</span>
                    </div> 
                    <div class="col-md-6">
                        <input type="text" class="form form-control number" name="main_due" id="main_due" value="{{$total_max}}" >
                    </div> 
                    </div>
                    
                    <div class="col-md-12">
                        
                        <table class="table table-bordered fees">
                            <tr><td width="33%" align="right">Fees</td><td width="33%" align="right">Balance</td><td align="right">Amount</td></tr>
                            <tr><td align="right">Miscellaneous Fee</td><td align="right">{{number_format($miscellaneous_fee_total->balance,2)}}</td><td><input onkeypress="do_main(event,{{$miscellaneous_fee_total->balance}},this.value,this)" type="text" name="miscellaneous" id="miscellaneous" class="form form-control number"></tr>
                            <tr><td align="right">Other Fee</td><td align="right">{{number_format($other_fee_total->balance,2)}}</td><td><input onkeypress="do_main(event,{{$other_fee_total->balance}},this.value,this)" type="text" name="other_fee" id="other_fee" class="form form-control number"></tr>
                            <tr><td align="right">Depository Fee</td><td align="right">{{number_format($depository_fee_total->balance,2)}}</td><td><input onkeypress="do_main(event,{{$depository_fee_total->balance}},this.value,this)" type="text" name="depository" id="depository" class="form form-control number"></tr>
                            <tr><td align="right">SRF/Additional Fee</td><td align="right">{{number_format($srf_total->balance,2)}}</td><td><input onkeypress="do_main(event,{{$srf_total->balance}},this.value,this)" type="text" name="srf" id="srf" class="form form-control number"></tr>
                            <tr><td align="right">Optional Fee</td><td align="right">{{number_format($optional_fee_total->balance,2)}}</td><td><input onkeypress="do_main(event,{{$optional_fee_total->balance}},this.value,this)" type="text" name="optional" id="optional" class="form form-control number"></tr>
                            <tr><td align="right">Tuition Fee</td><td align="right">{{number_format($tuition_fee_total->balance,2)}}</td><td><input onkeypress="do_main(event,{{$tuition_fee_total->balance}},this.value,this)" type="text" name="tuition" id="tuition" class="form form-control number"></tr>
                        </table>        
                    </div>  
                    
                    @if(count($other_misc)>0)
                    
                    <div class="form form-group">
                    <div class="col-md-12">
                        <span class="label_collected">Other Payment :</span>
                    </div> 
                    </div>    
                   <div class="col-md-12">    
                        <table class="table table-bordered fees"><tr><td align="right">Particular</td><td></td><td align="right">Amount</td></tr>
                           @foreach($other_misc as $om)
                           <tr><td  width="33%" align="right">{{$om->receipt_details}}</td><td width="33%"></td><td><input class="form form-control number other_misc" type="text"  id="other_misc[]" name="other_misc[{{$om->id}}]" onkeypress="do_other(event,{{$om->amount-$om->discount-$om->debit_memo-$om->payment}},this.value,this)" value="{{$om->amount-$om->discount-$om->debit_memo-$om->payment}}"></td></tr>
                             @endforeach
                        </table>     
                    </div> 
  
                     
                   
                   
                    @endif
                   
                    <div class="form form-group">
                    <div class="col-md-6">
                        <span class="label_collected">Amount To Be Debit :</span>
                    </div> 
                    <div class="col-md-6">
                        <input type="text" class="form form-control number" id="collected_amount" name="collected_amount" readonly="readonly">
                    </div> 
                    </div> 
                    <div id="donereg">
                    <div class="form form-group">
                        <div class="col-md-6">
                            <span class="label_collected">Explanation :</span>
                        </div>
                        <div class="col-md-6">
                            <input type="text" class="form form-control" name="remark" id="explanation">
                        </div>
                    </div>
                    </div>        
                </div>
            </div>  
        </div>    
   </div>
   <div class="col-md-6">
   <div id="payment_pad"> 
       <div  id="dynamic_field">
                        <!--div class="top-row"-->
                        <div class="form form-group">
                        <div class="col-md-4">
                            <label>Accounting</label>
                            <select name="accounting[]" id="accounting1" class="form form-control select2" onkeypress="gotoother_amount(1,event)">
                            <option>Select Accounting Name</option>
                            @if(count($accountings)>0)
                                @foreach($accountings as $accounting)
                                    <option value="{{$accounting->accounting_code}}">{{$accounting->accounting_name}}</option>
                                @endforeach
                            @endif
                            </select>
                            
                        </div>

                        <div class="col-md-3">
                            <label>Particular</label>
                            <input class="form form-control debit_particular" type="text" name="debit_particular[]" id='debit_particular1'/>
                        </div>
                        <div class="col-md-3">
                            <label>Amount</label>
                            <input class="form form-control number debit_amount" type="text" onkeypress="totalOther(event)" name="debit_amount[]" id="debit_amount1"/>
                        </div>
                        <div class="col-md-2">
                            <label class='col-sm-12'>&nbsp;</label>
                        <button type="button" name="add" id="add" class="btn btn-success"> + </button>
                        </div>
                        </div>    
            </div>
       <div class="form-group">
           <input type="submit" name="submit" id="submit" class="form form-control btn btn-warning" value="Process Debit Memo">
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
</style>

<script src="{{url('/',array('bower_components','select2','dist','js','select2.full.min.js'))}}"></script>

<script>
    var i=1;
    var main_total_max={{$total_max}};
    var other_total_max={{$other}};
    var misc_total_max={{$miscellaneous}};
    var depository_total_max={{$depository}};
    var optional_total_max={{$optional}};
    var srf_total_max={{$srf}};
    var tuition_total_max={{$tuition}};
    
    $(document).ready(function(){ 
        $("#submit_button").fadeOut(300);
        $("#donereg").fadeOut(300);
        $("#payment_pad").fadeOut(300);
        $("#main_due").focus();
        $("#submit").fadeOut(300);
        computeSubaccount();
        
        $("#previous_balance").on('keypress',function(e){
            if(e.keyCode==13){
                if($("#previous_balance").val()==""){
                    $("#previous_balance").val("0.00");
                }else if(parseFloat($("#previous_balance").val()) > parseFloat("{{$previous_total->balance}}")){
                    alert("Amount Should Not Be Greater Than " + "{{number_format($previous_total->balance,2)}}" )
                    $("#previous_balance").val("{{$previous_total->balance}}");
                }
                $("#main_due").focus();
                e.preventDefault();
            }
        });
        
        $(".debit_particular").on("keypress", function (e) {
            if (e.keyCode == 13) {
                if ($(".debit_particular").val() == "") {
                    alert("Please enter Particular!!!")
                    $(".debit_particular").focus();
                } else {
                    $(".debit_amount").focus();
                }
                e.preventDefault();
            }
        });
        
        //var i = 1;
        $('.select2').select2();
        $('#add').click(function(){
         if($("#accounting" + i +" option:selected").val()=="" || $("#debit_amount" + i).val()==""){
         alert("Please Fill-up Required Fields ");
           } else { 
               
        i++;
        $('#dynamic_field').append('<div id="row'+i+'" class="form form-group">\n\
        <div class="col-md-4">\n\
        <select class="form form-control select2" onkeypress = "gotoother_amount('+i+',event)" name="accounting[]" id="accounting'+i+'">'
         @foreach($accountings as $accounting) + '<option value="{{$accounting->accounting_code}}">{{$accounting->accounting_name}}</option>'  @endforeach 
         + '</select></div>\n\
        <div class="col-md-3"><input class="form form-control debit_particular"        type="text" name="debit_particular[]" id="debit_particular'+i+'"/></div>\n\
        <div class="col-md-3"><input class="form form-control number debit_amount" type="text" onkeypress="totalOther(event)"  name="debit_amount[]" id="debit_amount'+i+'"/></div>\n\
        <div class="col-md-2"><a href="javascript:void()" name="remove"  id="'+i+'" class="btn btn-danger btn_remove">X</a></div></div>');
        
        //$("#donereg").fadeOut();
        updatefunction();
        $("#accounting"+i).focus();
        }});
        
        $('#dynamic_field').on('click','.btn_remove', function(){
                //alert($(this).attr("id"))
                var button_id = $(this).attr("id");
                $("#row"+button_id+"").remove();
                i--;
                totalamount =0;
                other_amount = document.getElementsByName('debit_amount[]');
                for(var i = 0; i < debit_amount.length; i++){
                if(debit_amount[i].value != ""){    
                totalamount = totalamount+parseFloat(debit_amount[i].value)
                }
                }
                $("#other_total").val(totalamount.toFixed(2))
                $("#donereg").fadeIn(300);
            }); 
        
        
        $("#main_due").on('keypress',function(e){
            if(e.keyCode==13){
                if($("#main_due").val()==""){
                    $("#main_due").val("0.00");
                }
                if($("#main_due").val() > main_total_max){
                    alert("Amount Should Not Be Greater Than " + "{{number_format($total_max,2)}}" )
                } else {
                    computeSubaccount();
                    computeToBePaid();
                }
                
                e.preventDefault();
            }
        });
        
        $("#explanation").on('keypress',function(e){
            if(e.keyCode==13){
                if($("#explanation").val() == ""){
                    alert("Please Fillup Details");
                }else{
                    computeToBePaid()
                    $("#payment_pad").fadeIn();
                    $("#accounting"+i).focus();
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
    })
    function computeSubaccount(){
        var total = parseFloat($("#main_due").val());
        if(total >= misc_total_max){
        $("#miscellaneous").val(misc_total_max)
        total = total - misc_total_max
        } else {
         $("#miscellaneous").val(total.toFixed(2))
         total=0;
        }
        
        if(total >= other_total_max){
        $("#other_fee").val(other_total_max)
        total = total - other_total_max
        } else {
         $("#other_fee").val(total.toFixed(2))
         total=0;
        }
        
        
        
        if(total >= depository_total_max){
        $("#depository").val(depository_total_max)
        total = total - depository_total_max
        } else {
         $("#depository").val(total.toFixed(2))
         total=0;
        }
        
        if(total >= srf_total_max){
        $("#srf").val(srf_total_max)
        total = total - srf_total_max
        } else {
         $("#srf").val(total.toFixed(2))
         total=0;
        }
        
        if(total >= optional_total_max){
        $("#optional").val(optional_total_max)
        total = total - optional_total_max
        } else {
         $("#optional").val(total.toFixed(2))
         total=0;
        }
        
        if(total >= tuition_total_max){
        $("#tuition").val(tuition_total_max)
        total = total - tuition_total_max
        } else {
         $("#tuition").val(total.toFixed(2))
         total=0;
        }
        
        
    }
    
    function updatefunction(){
        $(".debit_particular").on("keypress", function (e) {
            if (e.keyCode == 13) {
                if ($(".debit_particular").val() == "") {
                    alert("Please enter Particular!!!")
                    $(".debit_particular").focus();
                } else {
                    $(".debit_amount").focus();
                }
                e.preventDefault();
            }
        });
    $('.select2').select2();
    $(".number").on('keypress',function(e){
        var theEvent = e || window.event;
        var key = theEvent.keyCode || theEvent.which;
        if ((key < 48 || key > 57) && !(key == 8 || key == 9 || key == 13 || key == 37 || key == 39 || key == 46) ){ 
        theEvent.returnValue = false;
        if (theEvent.preventDefault) theEvent.preventDefault();
        }});
    }
    
    
    
    
    function computeToBePaid(){
        total_payment=0;
        if($('input.other_misc')){
        $('input.other_misc').each(function(){
            total_payment=parseFloat(total_payment) + parseFloat(this.value)
        })
        total_payment = parseFloat(total_payment) + parseFloat($("#main_due").val()) + parseFloat($("#previous_balance").val())
        $("#collected_amount").val(total_payment.toFixed(2));
        $("#payment_pad").fadeOut(300);
        $("#donereg").fadeIn(300)
        $("#explanation").focus()
        }
       
    }
    function gotoother_amount(i,evt){
        if(evt.keyCode==13){
            $("#debit_amount" + i).focus()
            evt.preventDefault()
            return false;
        }
    }
    
   function totalOther(e){
           if(e.keyCode == 13){
                        totalamount =0;
                        debit_amount = document.getElementsByName('debit_amount[]');
                        for(var i = 0; i < debit_amount.length; i++){
                        totalamount = totalamount+parseFloat(debit_amount[i].value)
                        }
                        if(totalamount==$("#collected_amount").val()){
                            $("#submit").fadeIn(300);
                            $("#submit").focus();
                        } else {
                            if(totalamount > $("#collected_amount").val()){
                              alert("Debit Entry Invalid")  
                            }
                            $("#submit").fadeOut(300);
                             $("#add").focus();
                        }
                        //$("#other_total").val(totalamount.toFixed(2))
                       
                        //$("#donereg").fadeIn(300)
                        //$("#remark").focus()
                         e.preventDefault();
                         return false;
                 }
        }
        
   function do_other(event,amount,value,obj){
       if(event.keyCode==13 || event.keyCode==9){
           if(parseFloat(value)>parseFloat(amount)){
               alert("Invalid amount")
               obj.value=amount;
           }else{
               computeToBePaid();
           }
           event.preventDefault();
       }
   }   
   function do_main(event,amount,value,obj){
       if(event.keyCode==13 || event.keyCode==9){
           if(parseFloat(value)>parseFloat(amount)){
               alert("Invalid amount")
               obj.value=amount;
           }else{
               totalmain = parseFloat($("#other_fee").val()) + parseFloat($("#miscellaneous").val())
               + parseFloat($("#depository").val()) + parseFloat($("#srf").val()) + parseFloat($("#tuition").val());
               $("#main_due").val(totalmain)
               computeToBePaid();
           }
           event.preventDefault();
       }     
   }
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
</script>    
@endsection



