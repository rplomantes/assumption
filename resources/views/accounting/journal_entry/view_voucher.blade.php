<?php
if (Auth::user()->accesslevel == env("ACCTNG_STAFF")) {
    $layout = "layouts.appaccountingstaff";
} else if (Auth::user()->accesslevel == env("ACCTNG_HEAD")) {
    $layout = "layouts.appaccountinghead";
}
?>
@extends($layout)
<link href="{{asset('css/select2.min.css')}}" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap.min.css') }}">
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
<?php $debit = $credit = 0;?>

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
                @if($journal_entry->is_reverse == 1)<span class="label label-danger" style="font-size:14pt;">CANCELLED</span> @endif
                <table width="100%" class="table table-bordered table-responsive table-striped">
                    <tr>
                        <td class="title-head" colspan="5">JOURNAL VOUCHER</td>
                    </tr>
                    <tr>
                        <td width="50%" colspan="5">Date: {{date_format(date_create($journal_entry->transaction_date),"F d, Y")}}</td>
                    </tr>
                    <tr>
                        <td width="50%" colspan="5"><b>J.V. No. : {{str_pad($journal_entry->voucher_no,5,"0",STR_PAD_LEFT)}}</b></td>
                    </tr>
                    <tr>
                        <td colspan="5" rowspan="2">Particulars:<br>{{$journal_entry->particular}}</td> 
                    </tr>
                        <tr>
                        <tr>
                            <th>Account No.</th>
                            <th>Account Title</th>
                            <th>Subsidiary</th>
                            <th>Debit</th>
                            <th>Credit</th>
                        </tr>
                        </r>
                        <tbody>
                            @foreach($accountings as $accounting)
                            <tr>
                                <td>{{$accounting->accounting_code}}</td>
                                <td>{{$accounting->category}}</td>
                                <td>{{$accounting->description}}</td>
                                <td align="right">@if($accounting->debit != 0){{number_format($accounting->debit,2)}} @endif</td>
                                <td align="right">@if($accounting->credit != 0){{number_format($accounting->credit,2)}} @endif</td>
                            </tr>
                            <?php
                            $debit += $accounting->debit;
                            $credit += $accounting->credit;
                            ?>
                            @endforeach
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td align="right"><b>TOTAL</b></td>
                                <td align="right">{{number_format($debit,2)}}</td>
                                <td align="right">{{number_format($credit,2)}}</td>
                            </tr>
                        </tbody>
                        
                    </table>
            </form>
            <p class="" style="margin-top: 10px;">
                    Posted by: <b>{{\App\User::where('idno',$journal_entry->processed_by)->first()->firstname}} {{\App\User::where('idno',$journal_entry->processed_by)->first()->lastname}}</b>
                </p>
            <div class="form-group">
                 <div class="col-md-2">
                     <a role="button" class="form-control btn-warning btn" href="{{url('/journal_entry')}}"><span class="fa fa-arrow-circle-left"></span></a>
                </div>
                <div class="col-md-3">
                    @if($journal_entry->transaction_date == date("Y-m-d"))
                        @if($journal_entry->is_reverse == 1)
                        <a role="button" class="form-control btn-danger btn" href="{{url('/restore_voucher',$journal_entry->reference_id)}}" ><span class="fa fa-recycle"></span> <b>Restore</b></a>
                        @else
                        <a role="button" class="form-control btn-danger btn" href="{{url('/reverse_voucher',$journal_entry->reference_id)}}" ><span class="fa fa-remove"></span> <b>Reverse</b></a>
                        @endif
                    @endif
                </div>
                <div class="col-md-3">
                    @if($journal_entry->transaction_date == date("Y-m-d"))
                    <a role="button" class="form-control btn-success btn" href="{{url('/edit_voucher',$journal_entry->reference_id)}}"><span class="fa fa-arrow-circle-o-up"></span> <b>Update</b></a>
                    @endif
                </div>
                <div class="col-md-4">
                    <a role="button" class="form-control btn-primary btn" href="{{url('/print/journal_voucher',$journal_entry->reference_id)}}"><span class="fa fa-print"></span> <b>Print Journal Voucheer</b></a>
                </div>
            </div>
        </div>
    </div>
    <hr>
</div>
@stop

@section('maincontent')
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