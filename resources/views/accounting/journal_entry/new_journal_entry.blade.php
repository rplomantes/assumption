<?php
if (Auth::user()->accesslevel == env("ACCTNG_STAFF")) {
    $layout = "layouts.appaccountingstaff";
} else if (Auth::user()->accesslevel == env("ACCTNG_HEAD")) {
    $layout = "layouts.appaccountinghead";
}
?>
@extends($layout)
@section('maincontent')
<link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/daterangepicker.css') }}" />
<div class="container-fluid">
    <div class="row">
        <h3 class="display">New Journal Voucher</h3>
        <div class="col-md-6">
            <strong> JV NO:   </strong>
            <span style="font-size:20pt;font-weight:bold;color:red">&nbsp;{{$jv_voucher}}</span>
        </div>
    </div>               
    <div class="row">
        <div class="col-md-12" style="background-color:white;padding:10px;">
            <form class="form form-horizontal" action="{{ url('/process_journal_entry') }}" method="POST">
                {{csrf_field()}}
               <input type="hidden" name="reference" id="reference" value="{{uniqid()}}"/> 
               <input type="hidden" name="voucher_no" id="voucher_no" value="{{$jv_voucher}}"/> 
               <div class="form-group">
                   <div class="col-md-4">
                       <label>Entry Date</label>
                           <input type="date" class="form form-control" width="100%" id='entry_date' name='entry_date' value="{{date("Y-m-d")}}" required>
                   </div>
               </div>
                <div class="form-group">
                    <div class="col-md-5">
                        <label>Accounting Name</label>
                        <select class="form form-control select2" width="100%" id='accounting_name' name='accounting_name'>
                            @foreach($accounting_entry as $accounting_entries)
                            <option value="{{$accounting_entries->accounting_code}}">{{$accounting_entries->accounting_code}} - {{$accounting_entries->accounting_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label>Subsidiary</label>
                        <input type="text" class="form form-control" width="100%" id='particular' name='particular'>
                    </div>
                    <div class="col-md-2">
                        <label>Debit/Credit</label>
                        <select class="form form-control select2" width="100%" id='type' name='type'>
                            <option value="">Choose One</option>
                            <option value="Debit">Debit</option>
                            <option value="Credit">Credit</option>
                        </select>
                    </div>
                    
                    <div class="col-md-2">
                        <label>Amount</label>
                        <input type="text" class="form-control" id="amount" name="amount" onkeypress="addEntry()" style="text-align:right">
                    </div>
                </div>
                <div id="entries_table">
                    
                </div>
            </form>
        </div>
    </div>
    <hr>
</div>
@stop
@section('footerscript')
<script src="{{asset('js/select2.min.js')}}"></script>
<script>

        $('.select2').select2();
        
        $("#particular").keypress(function (e) {
            var ev = e.keyCode || event.which
            if (ev == 13) {
                e.preventDefault();
                return false;
            }
        });

        $("#amount").keypress(function (e) {
            var ev = e.keyCode || event.which
            if (ev == 13) {
                var array = {};
                array['voucher_no'] = $("#voucher_no").val();
                array['reference'] = $("#reference").val();
                array['code'] = $("#accounting_name").val();
                array['type'] = $("#type").val();
                array['particular'] = $("#particular").val();
                array['amount'] = $("#amount").val();
                $.ajax({
                    type: 'GET',
                    url: '/accounting/ajax/journal_set_entries',
                    data: array,
                    success: function (data) {
                        $('#entries_table').html(data);
                        $('#amount').val("");
                        $('#account_name').select2();
                        $("#amount").maskMoney();
                    }
                });

                e.preventDefault();
                return false;
            }
        });
        
        function removeEntry(id){
            var array = {};
            array['reference'] = $("#reference").val();
            array['id'] = id;
                $.ajax({
                    type: 'GET',
                    url: '/accounting/ajax/journal_remove_entries',
                    data: array,
                    success: function (data) {
                        $('#entries_table').html(data);
                        $('#amount').val("");
                    }
                });
        }
</script>
@stop