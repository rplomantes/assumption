<?php
$totalcreditcard = 0;

$layout = "";
if (Auth::user()->accesslevel == env("CASHIER")) {
    $layout = "layouts.appcashier";
} else if (Auth::user()->accesslevel == env("ACCTNG_STAFF")) {
    $layout = "layouts.appaccountingstaff";
} else if (Auth::user()->accesslevel == env("ACCTNG_HEAD")) {
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
@section('header')
<section class="content-header">
    <h1>
        Online Transactions
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Online Transactions</li>
    </ol>
</section>
@endsection
@section('maincontent')
<div class="container-fluid">

    <label>Search Request ID:</label>
    <div class="input-group">
        <form action='{{url('search_online_transaction_history')}}' method='get'>
            <input class='form-control' type='text' name='request_id'>
            <input class='btn btn-success' type='submit' value='Submit'>
        </form>
    </div>
    <br>
    <div class="form-group">
        <label>Date range button:</label>
        <div class="input-group">
            <button type="button" class="btn btn-default pull-left" id="daterange">
                <span>
                    <i class="fa fa-calendar"></i> <span id='range'>{{$date_from}} , {{$date_to}}</span>
                </span>
                <i class="fa fa-caret-down"></i>

            </button>

            <a href="javascript:void(0)" class="btn btn-primary" id="view-button">View Summary</a>

            <input id="date_to" class="form-control" type="hidden" value="{{$date_to}}">
            <input id="date_from" class="form-control" type="hidden" value="{{$date_from}}">
        </div>
    </div>

    <div class="box">    
        <div class="box-body">
            <table id="example1" class="table table-responsive table-hover">
                <thead>
                    <tr>
                        <td>Request ID</td>
                        <td>Request Date</td>
                        <td>Response ID</td>
                        <td>Response Date</td>
                        <td>Response Code</td>
                        <td>Amount</td>
                        <td>Payment Processed?</td>
                        <td>View History</td>
                    </tr>
                </thead>
                <tbody>
                    @if(count($transactions)>0)
                    @foreach($transactions as $transaction)
                    <tr>
                        <td>{{$transaction->request_id}}</td>
                        <td>{{$transaction->request_date}}</td>
                        <td>{{$transaction->response_id}}</td>
                        <td>{{$transaction->response_date}}</td>
                        <td>{{$transaction->response_code}}</td>
                        <td align='right'>{{number_format($transaction->amount,2)}}</td>
                        <td>@if($transaction->is_processed == 1) <span style="color: green; font-weight: bold">Yes</span> @else No @endif</td>
                        <td><a href='{{url('online_transaction_history',array($transaction->request_id))}}'>View History</a></td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table> 
        </div>    
    </div> 
</div>

@endsection
@section('footerscript')
<!-- daterange picker -->
<link rel="stylesheet" href="{{url('/bower_components',array('bootstrap-daterangepicker','daterangepicker.css'))}}">
<script src="{{url('/bower_components',array('datatables.net','js','jquery.dataTables.min.js'))}}"></script>
<script src="{{url('/bower_components',array('datatables.net-bs','js','dataTables.bootstrap.min.js'))}}"></script>
<script src="{{url('/',array('bower_components','moment','min','moment.min.js'))}}"></script>
<script src="{{url('/',array('bower_components','bootstrap-daterangepicker','daterangepicker.js'))}}"></script>
<script>
$(document).ready(function () {
    $('#example1').DataTable();
    $('#daterange').daterangepicker(
            {
                ranges: {
                    'Select': [moment(), moment()],
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment(),
                endDate: moment(),
            },
            function (start, end) {
                $('#daterange span #range').html(start.format('YYYY-MM-DD') + ' , ' + end.format('YYYY-MM-DD'));
                x = $('#range').html();
                splitdate = x.split(',');
                todate = splitdate[1];
                fromdate = splitdate[0];
                to = todate.trim()
                from = fromdate.trim()
                $('#date_to').val(to);
                $('#date_from').val(from);
            });
    $("#view-button").on('click', function (e) {
        document.location = "{{url('/online_transactions')}}" + "/" + $("#date_from").val() + "/" + $("#date_to").val();
    });
});</script>    
<script>
    function issue_or(payment_id) {
        var array = {};
        array['payment_id'] = payment_id;
        array['date_from'] = "{{$date_from}}";
        array['date_to'] = "{{$date_to}}";
        $.ajax({
            type: "get",
            url: "/ajax/issue_or_number",
            data: array,
            success: function (data) {
                $(".modal-content").html(data);
            }
        })
    }
</script>
@endsection
