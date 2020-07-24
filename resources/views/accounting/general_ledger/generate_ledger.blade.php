<?php
if (Auth::user()->accesslevel == env("ACCTNG_STAFF")) {
    $layout = "layouts.appaccountingstaff";
} else if (Auth::user()->accesslevel == env("ACCTNG_HEAD")) {
    $layout = "layouts.appaccountinghead";
}
?>
@extends($layout)
@section('maincontent')
<?php
$x = $total_credit = $total_debit = $account_type = 0;
$date_from = \Carbon\Carbon::today()->toDateString();
$balance = 0;
?>
<link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/daterangepicker.css') }}" />
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <h3 class="display">General Ledger</h3>
        </div>
    </div>
    <div class="col-md-12">
        <div class="box">
            <div id="display" class="box-body">
            <div id="display">
                <h4>Account Code: {{$account->accounting_code}}</h4>
                <h4>Account Name: {{$account->accounting_name}}</h4>
                <?php 
                if($account->accounting_group == "Assets" || $account->accounting_group == "Expenses"){
                    $account_type = 0;
                }
                else{
                    $account_type = 1;
                }
                ?>
                @if($finalStartDate == $finalEndDate)
                <small style="font-size:11pt;">Date Covered : {{date_format(date_create($finalStartDate),"F d, Y")}}</small><br>
                @else
                <small style="font-size:11pt;">Date Covered : {{date_format(date_create($finalStartDate),"F d, Y")}} - {{date_format(date_create($finalEndDate),"F d, Y")}}</small><br>
                @endif
                <br>
                <a class="btn btn-sm btn-primary" href="{{url("print/general_ledger",array($account->accounting_code,$finalStartDate,$finalEndDate))}}"><b><span class="fa fa-print"></span> Print</b></a>
                <br>
                <table class="table table-condensed col-md-6">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Date</th>
                            <th>Particulars</th>
                            <th>Type</th>
                            <th>Post. Ref</th>
                            <th style="text-align:right">Debit</th>
                            <th style="text-align:right">Credit</th>
                            <th style="text-align:right">Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($entries as $list)
                        <?php
                        if($account_type == 0 ){
                            if($list->debit > 0){
                                $balance += $list->debit;
                            }
                            else{
                                $balance -= $list->credit;
                            }
                        }
                        else{
                            if ($list->debit > 0) {
                                $balance -= $list->debit;
                            } else {
                                $balance += $list->credit;
                            }
                        }
                        ?>
                        <tr>
                            <td> {{++$x}} </td>
                            <td>{{$list->transaction_date}}</td>
                            <td>{{$list->description}}&nbsp;</td>
                            @if($list->accounting_type == env('CASH'))
                            <td>CR</td>
                            <td><a href="{{url('cashier',array('viewreceipt',$list->reference_id))}}">{{$list->reference_id}}</a></td>
                            @elseif($list->accounting_type ==  env('DEBIT_MEMO'))
                            <td>DM</td>
                            <td><a href="{{url('view_debit_memo',array($list->reference_id))}}">{{$list->reference_id}}</a></td>
                            @elseif($list->accounting_type ==  env('DISBURSEMENT'))
                            <td>D</td>
                            <td><a href="{{url('view/disbursement',array($list->reference_id))}}">{{$list->reference_id}}</a></td>
                            @elseif($list->accounting_type ==  env('JOURNAL'))
                            <td>JV</td>
                            <td><a href="{{url('view/journal_voucher',array($list->reference_id))}}">{{$list->reference_id}}</a></td>
                            @else
                            <td>BG</td>
                            <td>&nbsp;</td>
                            @endif
                            <td align="right">{{number_format(abs($list->debit),2)}}&nbsp;</td>
                            <td align="right">{{number_format(abs($list->credit),2)}}&nbsp;</td>
                            <td align="right" colspan="2">{{number_format($balance,2)}}&nbsp;</td>
                            <?php
                            $total_credit += abs($list->credit);
                            $total_debit += abs($list->debit);
                            ?>
                        </tr>
                        @endforeach
                        <tr>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td><b>{{$account_type}} TOTAL</b> </td>
                            <td align="right" ><b>{{number_format($total_debit,2)}}&nbsp;</b></td>
                            <td align="right" ><b>{{number_format($total_credit,2)}}&nbsp;</b></td>
                            @if($account_type == 0 )
                                <td align="right" ><b>{{number_format(abs($total_debit-$total_credit),2)}}&nbsp;</b></td>
                            @else
                                <td align="right" ><b>{{number_format(abs($total_credit-$total_debit),2)}}&nbsp;</b></td>
                            @endif
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>
</div>
@stop

@section('script')
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.bootstrap.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/daterangepicker.min.js') }}"></script>
<script>
$(document).ready(function () {
    var table = $('#example').DataTable({
        "scrollX": true
    });

    var start = moment().subtract(29, 'days');
    var end = moment();

    function cb(start, end) {
        $('#datepicker').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }

    $('#datepicker').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        }
    }, cb);

    cb(start, end);

    $('#view').on('click', function () {
        var array = {};
        array["date"] = $('#datepicker').val();
        $.ajax({
            type: "GET",
            url: "/ajax/get_trial_balance",
            data: array,
            success: function (data) {
                $("#display").html(data);
                $("#display").show();
                $('#new').hide();
            }
        });
    });
});
</script>
@stop