<?php
if (Auth::user()->accesslevel == env("ACCTNG_STAFF")) {
    $layout = "layouts.appaccountingstaff";
} else if (Auth::user()->accesslevel == env("ACCTNG_HEAD")) {
    $layout = "layouts.appaccountinghead";
}
$accounting_entry = \App\ChartOfAccount::get();
?>
@extends($layout)
@section('css')
<link href="{{asset('css/select2.min.css')}}" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap.min.css') }}">
@stop
@section('maincontent')
<style>
    .title-head{
        background-color: black;
        color:white;
        text-align: center;
        font-size:13pt;
        font-weight: bold;
    }
</style>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12" style="background-color:white;padding:10px;">
            <form class="form form-horizontal" method="post" action="{{url("/accounting/edit_disbursement")}}">
                {{csrf_field()}}
                <input type="hidden" name="reference_id" value="{{$reference}}">
                <center>
                    <div class="col-md-12">
                        <b style="font-size:14pt">ASSUMPTION COLLEGE</b><br>
                        <small style="margin-top: 0px;">San Lorenzo Drive, San Lorenzo Village Makati City</small><br/>
                        </br>
                    </div>
                </center>
                
                <table width="100%" class="table table-bordered table-responsive table-striped">
                    <tr>
                        <td class="title-head" colspan="4">CHECK VOUCHER</td>
                    </tr>
                    <tr>
                        <td width="70%" rowspan="2">PAY TO:<br>
                            <input type="text" name="payee" id="payee" class="form-control" value="{{$disbursement->payee_name}}">
                            <center><div style="margin-top:15px;" class="container-fluid" id="display_payee"></div></center>
                        </td>
                        <td width="30%">
                            C.V. No.: 
                            <input type="text" name="voucher_no" class="form-control" value="{{str_pad($disbursement->voucher_no,5,"0",STR_PAD_LEFT)}}">
                        </td>
                    </tr>
                    <tr>
                        <td>Date: {{date_format(date_create($disbursement->transaction_date),"F d, Y")}}</td>
                    </tr>
                    <tr>
                        <td colspan="2" rowspan="2">TO COVER:<br>
                            <textarea name="remarks" class="form-control">{{$disbursement->remarks}}</textarea>
                        </td> 
                    </tr>
                </table>
                
               @if($errors->count())
               <div class="col-sm-12">
               <div class="form-group">
                   <div class="alert alert-danger">
                       <ul>
                           @foreach($errors->all() as $error)
                           <li>{{$error}}</li>
                           @endforeach
                       </ul>
                   </div>
               </div>
               </div>
               @endif
               
               @if(Session::has("success"))
               <div class="col-sm-12">
               <div class="form-group">
                   <div class="alert alert-success">
                       {{Session::get("success")}}
                   </div>
               </div>
               </div>
               @endif
               
               <div class="row" style="margin-bottom:15px;">
                   <div class="col-sm-4">
                       <button id="addrow" class="btn btn-block btn-flat btn-primary"><i class='fa fa-plus-circle'></i> Add Row</button>
                   </div>
               </div>

                <div id="entries_table">
                    <table id="table_accounting" class="table table-bordered table-responsive table-striped">
                        <thead>
                        <th colspan="2">&nbsp;</th>
                        <th colspan="2" style="text-align:center">Amount</th>
                        <tr>
                            <th style="width:70%">Account No. - Account Title</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($accountings as $accounting)
                            <tr id="row{{$loop->iteration}}">
                                <td>
                                    <select name="accounting_codes[]" class="form-control select2" style="width:100%">
                                        @foreach($accounting_entry as $accounting_entries)
                                        <option @if($accounting->accounting_code == $accounting_entries->accounting_code) selected @endif value="{{$accounting_entries->accounting_code}}">{{$accounting_entries->accounting_name}}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td><input name="debit[]" type="number" class="form-control" value="{{$accounting->debit}}"></td>
                                <td><input name="credit[]" type="number" class="form-control" value="{{$accounting->credit}}"></td>
                                <td><a id='{{$loop->iteration}}' onclick='removerow(this)' class="btn btn-danger btn-sm"><i class="fa fa-close"></i></a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <table width="100%" class="table table-bordered table-responsive table-striped">
                    <tr>
                        <td><b>ACCOUNT NAME:</b>
                        <select name="account_name" class="form-control select2" style="width:100%">
                            @foreach($accounting_entry as $accounting_entries)
                            <option  value="{{$accounting_entries->accounting_code}}">{{$accounting_entries->accounting_name}}</option>
                            @endforeach
                        </select>
                        </td>
                        <td><b>CHECK NUMBER:</b> <input name="check_number" type="text" value="{{$disbursement->check_no}}" class="form-control"></td>
                        <td><b>BANK:</b><input name="bank" type="text" value="{{$disbursement->bank}}" class="form-control"></td>
                        <td><b>CHECK AMOUNT:</b><input readonly name="check_amount" type="text" value="{{number_format($disbursement->amount,2)}}" class="form-control"></td>
                    </tr>
                </table>
                <div class="col-sm-12">
                    <div class="form-group">
                        <button onclick="return confirm('Do you wish to Continue?')" class="btn btn-flat btn-block btn-warning">Update Entries</button>
                    </div>
                </div>
               <div class="form-group row">
                    <div class="col-md-6">
                        <a role="button" class="form-control btn-warning btn" href="{{url('/disbursement')}}"><span class="fa fa-arrow-circle-left"></span> <b>Back</b></a>
                   </div>
                   <div class="col-md-6">
                       <a role="button" class="form-control btn-success btn" href="{{url('/print/check_voucher_labels',$disbursement->reference_id)}}" target="_blank"><span class="fa fa-print"></span> <b>Print Check Voucher</b></a>
                   </div>

               </div>
               <div class="form-group row">
                   <div class="col-md-6">
                       <a role="button" class="form-control btn-success btn" href="{{url('/accounting/disbursement/print_check_disbursement',$disbursement->reference_id)}}" target="_blank"><span class="fa fa-print"></span> <b>Print Check</b></a>
                   </div>
               </div>
            </form>
        </div>
    </div>
    <hr>
</div>
<script src="{{asset('js/select2.min.js')}}"></script>
<script>

   
</script>
@stop

@section('footerscript')
<script>
    
    
$('.select2').select2();
$(document).ready(function () {
//    $("#amount").maskMoney();
});

$("#amount").keypress(function (e) {
    var ev = e.keyCode || event.which
    if (ev == 13) {
        var array = {};
        array['reference'] = $("#reference").val();
        array['code'] = $("#accounting_name").val();
        array['type'] = $("#type").val();
        array['particular'] = $("#particular").val();
        array['amount'] = $("#amount").val();
        $.ajax({
            type: 'GET',
            url: '/accounting/ajax/set_entries',
            data: array,
            success: function (data) {
                $('#entries_table').html(data);
                $('#particular').val("");
                $('#amount').val("");
                $('#account_name').select2();
                $("#amount").maskMoney();
                $("#check_amount").maskMoney();
            }
        });

        e.preventDefault();
        return false;
    }
});

function removeEntry(id) {
    var array = {};
    array['reference'] = $("#reference").val();
    array['id'] = id;
    $.ajax({
        type: 'GET',
        url: '/accounting/ajax/remove_entries',
        data: array,
        success: function (data) {
            $('#entries_table').html(data);
            $('#particular').val("");
            $('#amount').val("");
        }
    });
}

      $("#payee").on("keyup", function(e){
        var search = $(this).val();
        var array = {};
        array["search"] = search;
        $.ajax({
            type: "GET",
            url: "/ajax/accounting/disbursement/search_payee",
            data: array,
            success: function(data){
                $("#display_payee").html(data).fadeIn();
            }
        })
    })    
    
    function selectpayee(supplier){
        var object = JSON.parse(supplier);
        
        $("#display_payee").hide();
        $("#payee").val(object.supplier_name);
    }
    
    $("#addrow").on("click", function(e){
        var length = $('#table_accounting tbody').length + 1;
        
        $('#table_accounting tbody').append("<tr id='row"+length+"'>\n\
        <td><select name='accounting_codes[]' class='form-control select2' style='width:100%'>@foreach($accounting_entry as $accounting_entries)<option value='{{$accounting_entries->accounting_code}}'>{{$accounting_entries->accounting_name}}</option>@endforeach</select></td>\n\
        <td><input name='particulars[]' type='text' value='{{$accounting->particular}}' class='form-control'></td>\n\
        <td><input name='debit[]' type='number' class='form-control' value='{{$accounting->debit}}'></td>\n\
        <td><input name='credit[]' type='number' class='form-control' value='{{$accounting->credit}}'></td>\n\
        <td><a id='"+length+"' onclick='removerow(this)' class='btn btn-danger btn-sm'><i class='fa fa-close'></i></a></td></tr>");
            
        $(".select2").select2();    
        e.preventDefault();
    })
    
    function removerow(object){
        $("#row"+object.id).remove();
    }
</script>
@stop