<?php
if (Auth::user()->accesslevel == env("ACCTNG_STAFF")) {
    $layout = "layouts.appaccountingstaff";
} else if (Auth::user()->accesslevel == env("ACCTNG_HEAD")) {
    $layout = "layouts.appaccountinghead";
}
?>
@extends($layout)
@section('maincontent')
<?php $debit = $credit = 0?>
<div class="container-fluid">
    <div class="row">
        <h3 class="display">New Journal Voucher</h3>
        <div class="col-md-6">
            <strong> JV NO:   </strong>
            <span style="font-size:20pt;font-weight:bold;color:red">&nbsp;{{str_pad($voucher->voucher_no,5,"0",STR_PAD_LEFT)}}</span>
        </div>
    </div>               
    <div class="row">
        <div class="col-md-12" style="background-color:white;padding:10px;">
            <form class="form form-horizontal" action="{{ url('/update_journal_entry') }}" method="POST">
                {{csrf_field()}}
               <input type="hidden" name="reference" id="reference" value="{{$voucher->reference_id}}"/> 
               <input type="hidden" name="voucher_no" id="voucher_no" value="{{$voucher->voucher_no}}"/> 
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
                        <label>Debit</label>
                        <input type="number" value="0" onkeypress="addentry(event)" id="debit" class="form-control">
                    </div>
                    
                    <div class="col-md-2">
                        <label>Credit</label>
                        <input type="number" value="0" onkeypress="addentry(event)" id="credit" class="form-control">
                    </div>
                </div>
                <div id="entries_table">
                    <h4>Accounting Entries</h4>
                    @if($accountings->isEmpty())
                    <h5>No entries yet.</h5>
                    @else
                    <table class="table table-bordered table-responsive table-striped">
                        <thead>
                        <th colspan="3">&nbsp;</th>
                        <th colspan="2" align="center">Amount</th>
                        <th></th>
                        <tr>
                            <th>Account No.</th>
                            <th>Account Title</th>
                            <th>Subsidiary</th>
                            <th>Debit</th>
                            <th>Credit</th>
                            <th>Remove</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($accountings as $accounting)
                            <tr>
                                <td>{{$accounting->accounting_code}}</td>
                                <td>{{$accounting->category}}</td>
                                <td>{{$accounting->subsidiary}}</td>
                                <td>@if($accounting->debit != 0){{number_format($accounting->debit,2)}} @endif</td>
                                <td>@if($accounting->credit != 0){{number_format($accounting->credit,2)}} @endif</td>
                                <td><a role="button" class="btn btn-danger btn-sm" onclick="removeEntry('{{$accounting->id}}')">Remove</a></td>
                            </tr>
                            <?php
                            $debit += $accounting->debit;
                            $credit += $accounting->credit;
                            ?>
                            @endforeach
                            <tr @if($debit == $credit) style="color:green" @else style="color:red" @endif>
                                 <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td><b>TOTAL</b></td>
                                <td><b>{{number_format($debit,2)}}</b></td>
                                <td><b>{{number_format($credit,2)}}</b></td>
                                <td>&nbsp;</td>
                            </tr>
                        </tbody>
                    </table>
                    @if($debit == $credit)
                    <div class="form-group">
                        <div class="col-md-12">
                            <label>Particular</label>
                            <textarea class="form form-control" id='description' name='description' row="3" required>{{$voucher->particular}}</textarea>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-6">
                            <a class="form-control btn-danger btn" href='{{url('cancel_edit_voucher',array($reference))}}'><b><span>X</span> Cancel Editing</b></a>
                        </div>
                        <div class="col-md-6">    
                            <button type="submit" class="form-control btn-success btn"><span class="fa fa-save"></span> <b>Save Updates</b></button>
                        </div>
                    </div>
                    @endif
                    @endif
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
        $(document).ready(function () {
            $("#amount").maskMoney();
        });

        function addentry(e){
            if(e.keyCode == 13){
                var array = {};
                array['is_update'] = 1;
                array['voucher_no'] = $("#voucher_no").val();
                array['reference'] = $("#reference").val();
                array['code'] = $("#accounting_name").val();
                array['debit'] = $("#debit").val();
                array['credit'] = $("#credit").val();
                array['particular'] = $("#particular").val();
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
        }
        
        function removeEntry(id){
            var array = {};
            array['is_update'] = 1;
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