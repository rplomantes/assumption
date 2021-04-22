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
                <?php $i = 0; ?>
                <div  id="dynamic_field">
                    @if(count($accountings)>0)
                    <div class="form form-group">
                        <div class="col-md-7">
                            <label>Account No. - Account Title</label>
                        </div>
                        <div class="col-md-2">
                            <label>Debit</label>
                        </div>
                        <div class="col-md-2">
                            <label>Credit</label>
                        </div>
                        <div class="col-md-1">
                            <label>Add/Remove</label>
                        </div>
                    </div>
                    @foreach($accountings as $accounting)
                    <div id='row{{$i}}' class="form form-group">                                
                        <div class="col-md-7">
                            <select name="accounting_codes[]" class="form-control select2" style="width:100%">
                                @foreach($accounting_entry as $accounting_entries)
                                <option @if($accounting->accounting_code == $accounting_entries->accounting_code) selected @endif value="{{$accounting_entries->accounting_code}}">{{$accounting_entries->accounting_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2"><input name="debit[]" id='debit{{$i}}' type="number" class="form-control" value="{{$accounting->debit}}"></div>
                        <div class="col-md-2"><input name="credit[]" id='credit{{$i}}' type="number" class="form-control" value="{{$accounting->credit}}"></div>
                        <div class="col-md-1">
                            @if($i == 0)
                            <button type="button" name="add" id="add" class="btn btn-success"> + </button>
                            @else
                            <button type='button' name="remove" id="{{$i}}" class="btn btn-danger btn_remove btn_remove_row">X</button>
                            @endif
                        </div>
                    </div>

                    <?php $i = $i + 1; ?>
                    @endforeach
                    @endif
                </div>

                <table width="100%" class="table table-bordered table-responsive table-striped">
                    <tr>
                        <td><b>ACCOUNT NAME:</b>
                            <select name="account_name" class="form-control select2" style="width:100%">
                                @foreach($accounting_entry as $accounting_entries)
                                <option  value="{{$accounting_entries->accounting_code}}" @if($accounting_entries->accounting_code==$check_amount->accounting_code) selected @endif  >{{$accounting_entries->accounting_name}}</option>
                                @endforeach
                            </select>
                        </td>
                        <td><b>CHECK NUMBER:</b> <input name="check_number" type="text" value="{{$disbursement->check_no}}" class="form-control"></td>
                        <td><b>BANK:</b><input name="bank" type="text" value="{{$disbursement->bank}}" class="form-control"></td>
                        <td>
                            <div class='col-sm-12'><b>CHECK AMOUNT:</b></div>
                            <div class='col-sm-8'>
                                <input readonly name="check_amount" id='check_amount' type="text" value="{{number_format($disbursement->amount,2)}}" class="form-control">
                            </div>
                            <div class='col-sm-4'>
                                <input type='button' id='updateAmount' class='btn btn-danger' value='Update Amount'>
                            </div>
                        </td>
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

    var j = "{{$i-1}}";

    $('.select2').select2();

    $('#add').click(function () {

        j++;
        $('#dynamic_field').append("<div id='row" + j + "' class='form form-group'>\n\
           <div class='col-md-7'><select name='accounting_codes[]' class='form-control select2' style='width:100%'>@foreach($accounting_entry as $accounting_entries)<option value='{{$accounting_entries->accounting_code}}'>{{$accounting_entries->accounting_name}}</option>@endforeach</select></div>\n\
           <div class='col-md-2'><input name='debit[]' type='number' class='form-control' value='0.00'></td></div>\n\
           <div class='col-md-2'><input name='credit[]' type='number' class='form-control' value='0.00'></td></div>\n\
           <div class='col-md-1'><a href='javascript:void()' name='remove'  id=" + j + " class='btn btn-danger btn_remove btn_remove_row'>X</a></div></div>");
        computeCheckAmount();
    });

    $('#dynamic_field').on('click', '.btn_remove_row', function () {
        var button_id = $(this).attr("id");
        $("#row" + button_id + "").remove();
        j--;
        computeCheckAmount();
    });


    $("#updateAmount").on("click", function (e) {
        debit = document.getElementsByName('debit[]');
        credit = document.getElementsByName('credit[]');
        totalDebit = 0;
        totalCredit = 0;
        
        for (var i = 0; i < debit.length; i++) {
            var a = debit[i];
            totalDebit += parseFloat(a.value);
        }
            
        for (var j = 0; j < credit.length; j++) {
            var b = credit[j];
            totalCredit += parseFloat(b.value);
        }
        
        total = parseFloat(totalDebit) - parseFloat(totalCredit);
        $("#check_amount").val(total.toFixed(2));
    })
</script>
@stop