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
<?php
$accountings = \App\ChartOfAccount::orderBy('accounting_code')->get();
?>
<?php $sy = \App\CtrAcademicSchoolYear::where('academic_type', 'BED')->first()->school_year; ?>
@section('header')
<section class="content-header">
    <h1>
        Student Ledger
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url("/cashier",array('viewledger',$sy,$user->idno))}}"> Student Ledger</a></li>
        <li class="active">Reservation</li>
    </ol>
</section>
@endsection
@section('maincontent')
<div class="container-fluid">
    <form method="post" action="{{url('accounting','post_add_to_student_deposit')}}" class="form-horizontal">
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
                    <div class="col-md-12">
                        <label>Student Deposit</label>
                        <input type="text" name="deposit" id="deposit" class="form form-control reservation number" />
                    </div> 
                    <div class="col-md-12">
                        <label>Explanation</label>
                        <input type="text" name="remark" id="explanation" class="form form-control reservation" />
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
                            <select name="accounting[]" id="accounting1" class="form form-control select2" onkeypress="gotoother_amount(1, event)">
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
                    <input type="submit" name="submit" id="submit" class="form form-control btn btn-warning" value="Process Add to Student Deposit">
                </div>    
            </div>
        </div> 
    </form>
</div>    
@endsection
@section('footerscript')
<style>
    .top-payment{
        background-color: #E9C062;
        padding: 10px; 
    }
    .number{
        text-align: right;
    }
</style>
<script>
    var i=1;
    $(document).ready(function () {
        $("#submit").fadeOut(300);
        $("#submit_button").fadeOut(300);
        $("#payment_pad").fadeOut(300)
        $(".number").on('keypress', function (e) {
            var theEvent = e || window.event;
            var key = theEvent.keyCode || theEvent.which;
            if ((key < 48 || key > 57) && !(key == 8 || key == 9 || key == 13 || key == 37 || key == 39 || key == 46)) {
                theEvent.returnValue = false;
                if (theEvent.preventDefault)
                    theEvent.preventDefault();
            }
        });
        $("#explanation").on("focusin", function (e) {
            $("#payment_pad").fadeOut(300)
        })
        $("#deposit").on("focusin", function (e) {
            $("#payment_pad").fadeOut(300)
        })

        $("#explanation").on("keypress", function (e) {
            if (e.keyCode == 13) {
                if ($("#explanation").val() == "") {
                    alert("Please enter explanation!!!")
                    $("#explanation").focus();
                } else {
                    if ($("#deposit").val() == "") {
                        alert("Please Enter Amount on Student Deposit")
                    } else {
                        $("#payment_pad").fadeIn(300);
                    }
                }
                e.preventDefault();
            }
        });

        $("#deposit").on("keypress", function (e) {
            if (e.keyCode == 13) {
                if ($("#deposit").val() == "") {
                    alert("Please put amount to Student Deposit")
                    $("#deposit").focus();
                } else {
                    $("#explanation").focus();
                }
                e.preventDefault();
            }
        })
        
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
    })
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
    function totalOther(e) {
        if (e.keyCode == 13) {
            totalamount = 0;
            debit_amount = document.getElementsByName('debit_amount[]');
            for (var i = 0; i < debit_amount.length; i++) {
                totalamount = totalamount + parseFloat(debit_amount[i].value);
            }
            if (totalamount == $("#deposit").val()) {
                $("#submit").fadeIn(300);
                $("#submit").focus();
            } else {
                if (totalamount > $("#deposit").val()) {
                    alert("Amount Entry Invalid");
                }
                $("#submit").fadeOut(300);
                $("#add").focus();
            }
            e.preventDefault();
            return false;
        }
    }
</script>    
@endsection

