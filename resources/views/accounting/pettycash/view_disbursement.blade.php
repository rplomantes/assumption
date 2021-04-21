<?php
if (Auth::user()->accesslevel == env("ACCTNG_STAFF")) {
    $layout = "layouts.appaccountingstaff";
} else if (Auth::user()->accesslevel == env("ACCTNG_HEAD")) {
    $layout = "layouts.appaccountinghead";
}
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
            <form class="form form-horizontal">

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
                        <td width="70%" rowspan="2">PAY TO:<br>{{$disbursement->payee_name}}</td>
                        <td width="30%">C.V. No.: {{str_pad($disbursement->voucher_no,5,"0",STR_PAD_LEFT)}}</td>
                    </tr>
                    <tr>
                        <td>Date: {{date_format(date_create($disbursement->transaction_date),"F d, Y")}}</td>
                    </tr>
                    <tr>
                        <td colspan="2" rowspan="2">TO COVER:<br>{{$disbursement->remarks}}</td> 
                    </tr>
                </table>
                <div id="entries_table">
                    <table class="table table-bordered table-responsive table-striped">
                        <thead>
                        <th colspan="3">&nbsp;</th>
                        <th colspan="2" style="text-align:center">Amount</th>
                        <tr>
                            <th>Account No.</th>
                            <th>Account Title</th>
                            <th>Debit</th>
                            <th>Credit</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($accountings as $accounting)
                            <tr>
                                <td>{{$accounting->accounting_code}}</td>
                                <td>{{$accounting->category}}</td>
                                <td>@if($accounting->debit != 0){{number_format($accounting->debit,2)}} @endif</td>
                                <td>@if($accounting->credit != 0){{number_format($accounting->credit,2)}} @endif</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <table width="100%" class="table table-bordered table-responsive table-striped">
                    <tr>
                        <td><b>CHECK NUMBER:</b> {{$disbursement->check_no}}</td>
                        <td><b>CHECK AMOUNT:</b> {{number_format($disbursement->amount,2)}}</td>
                    </tr>
                </table>
            </form>
            <div class="form-group row">
                 <div class="col-md-6">
                     <a role="button" class="form-control btn-warning btn" href="{{url('/pettycash')}}"><span class="fa fa-arrow-circle-left"></span> <b>Back</b></a>
                </div>
                <div class="col-md-6">
                    <a role="button" class="form-control btn-success btn" href="{{url('/print/petty_cash_voucher',$disbursement->reference_id)}}" target="_blank"><span class="fa fa-print"></span> <b>Print Petty Cash Voucher</b></a>
                </div>
                                
            </div>
        </div>
    </div>
    <hr>
</div>
<script src="{{asset('js/select2.min.js')}}"></script>
<script>

$('.select2').select2();
$(document).ready(function () {
    $("#amount").maskMoney();
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
</script>
@stop