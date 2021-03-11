<?php
if (Auth::user()->accesslevel == env("ACCTNG_STAFF")) {
    $layout = "layouts.appaccountingstaff";
} else if (Auth::user()->accesslevel == env("ACCTNG_HEAD")) {
    $layout = "layouts.appaccountinghead";
}
?>
@extends($layout)
@section('maincontent')
<div class="container-fluid">
    <div class="row">
        <h3 class="display">Petty Cash</h3>
        <div class="col-md-6">
            <strong> VOUCHER NO:   </strong>
            <span style="font-size:20pt;font-weight:bold;color:red">&nbsp;{{$voucher_no}}</span>
        </div>
    </div>               
    <div class="row">
        <div class="col-md-12" style="background-color:white;padding:10px;">
            <form class="form form-horizontal" action="{{ url('/process_pettycash') }}" method="POST">
                {{csrf_field()}}
               <input type="hidden" name="reference" id="reference" value="{{uniqid()}}"/> 
               <input type="hidden" name="voucher_no" id="voucher_no" value="{{$voucher_no}}"/> 
               <div class="form-group">
                    <div class="col-md-12">
                        <label>Payee</label>
                        <input type="text" id="payee" name="payee" class="form-control">
                    </div>
                </div>
               
               <div id="display_payee">
               </div>
               
                <div class="form-group">
                    <div class="col-md-8">
                        <label>Accounting Name</label>
                        <select class="form form-control select2" width="100%" id='accounting_name' name='accounting_name'>
                            @foreach($accounting_entry as $accounting_entries)
                            <option value="{{$accounting_entries->accounting_code}}">{{$accounting_entries->accounting_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label>Debit</label>
                        <input type="number" name="debit[]" onkeypress="addentry(event)" id="debit" value="0" class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label>Credit</label>
                        <input type="number" name="credit[]" onkeypress="addentry(event)" id="credit" value="0" class="form-control">
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

   function addentry(e){
       if(e.keyCode == 13){
           var array = {};
            array['voucher_no'] = $("#voucher_no").val();
            array['category'] = $("#category").val();
            array['reference'] = $("#reference").val();
            array['code'] = $("#accounting_name").val();
            array['debit'] = $("#debit").val();
            array['credit'] = $("#credit").val();
            array['particular'] = $("#particular").val();
            $.ajax({
                type: 'GET',
                url: '/accounting/pettycash/ajax/set_entries',
                data: array,
                success: function (data) {
                    $('#entries_table').html(data);
                    $('#particular').val("");
                    $('#debit').val(0);
                    $('#credit').val(0);
                    $('#account_name').select2();
                }
            });
       }
   }

   $(document).ready(function(){
        $("#amount").keypress(function(e){
           var theEvent = e || window.event;
           var key = theEvent.keyCode || theEvent.which;
           if(key==13){
                
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
                    url: '/accounting/pettycash/ajax/remove_entries',
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
</script>
@endsection