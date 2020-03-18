@extends('layouts.appaccountinghead')
@section('maincontent')
<div class="container-fluid">
    <div class="row">
        <h3 class="display">Check Disbursement</h3>
        <div class="col-md-6">
            <strong> VOUCHER NO:   </strong>
            <span style="font-size:20pt;font-weight:bold;color:red">&nbsp;{{$voucher_no}}</span>
        </div>
    </div>               
    <div class="row">
        <div class="col-md-12" style="background-color:white;padding:10px;">
            <form class="form form-horizontal" action="{{ url('/process_disbursement') }}" method="POST">
                {{csrf_field()}}
               <input type="hidden" name="reference" id="reference" value="{{uniqid()}}"/> 
               <input type="hidden" name="voucher_no" id="voucher_no" value="{{$voucher_no}}"/> 
               <div class="form-group">
                    <div class="col-md-12">
                        <label>Payee</label>
                        <input type="text" id="payee" name="payee" class="form-control">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-3">
                        <label>Accounting Name</label>
                        <select class="form form-control select2" width="100%" id='accounting_name' name='accounting_name'>
                            @foreach($accounting_entry as $accounting_entries)
                            <option value="{{$accounting_entries->accounting_code}}">{{$accounting_entries->accounting_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Particular</label>
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
                    
                    <div class="col-md-3">
                        <label>Amount</label>
                        <input type="text" class="form-control" id="amount" name="amount" style="text-align:right">
                    </div>
                </div>
                <div id="entries_table">
                    
                </div>
            </form>
        </div>
    </div>
    <hr>
</div>
@endsection
@section('footerscript')
<script>

        $('.select2').select2();

   $(document).ready(function(){
        $("#amount").keypress(function(e){
           var theEvent = e || window.event;
           var key = theEvent.keyCode || theEvent.which;
           if(key==13){
                var array = {};
                array['voucher_no'] = $("#voucher_no").val();
                array['category'] = $("#category").val();
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
                    }
                });

                e.preventDefault();
                return false;
            }
        });
   })
        
        function removeEntry(id){
            var array = {};
            array['reference'] = $("#reference").val();
            array['category'] = $("#category").val();
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
@endsection