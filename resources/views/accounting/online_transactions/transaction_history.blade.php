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
<!-- search form (Optional) -->
<div class="container-fluid">
    <div class="box">    
        <div class="box-body">
            <table class="table table-responsive">
                <tr>
                    <td>Request ID</td>
                    <td><strong>{{$transaction->request_id}}</strong></td>
                </tr>
                <tr>
                    <td>Request Date</td>
                    <td>{{$transaction->request_date}}</td>
                </tr>
            </table>
            <hr>
            <table id="example1" class="table table-responsive table-hover">
                <thead>
                    <tr>
                        <td>Timestamp</td>
                        <td>Payment Type</td>
                        <td>Response Code</td>
                        <td>Response Message</td>
                        <td>Response Advise</td>
                        <td>Processor ID(Use for Paypal)</td>
                        <td>Amount</td>
                    </tr>
                </thead>
                <tbody>
                    @if(count($transaction_histories)>0)
                    @foreach($transaction_histories as $transaction_history)
                    <tr>
                        <td>{{$transaction_history->timestamp}}</td>
                        <td>{{$transaction_history->ptype}}</td>
                        <td>{{$transaction_history->response_code}}</td>
                        <td>{{$transaction_history->response_message}}</td>
                        <td>{{$transaction_history->response_advise}}</td>
                        <td>{{$transaction_history->processor_response_id}}</td>
                        <td align='right'>{{number_format($transaction->amount,2)}}</td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table> 
        </div>    
    </div> 
</div>

@endsection
