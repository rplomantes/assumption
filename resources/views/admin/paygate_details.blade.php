@extends('layouts.admin')
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
        Paynamics Credentials
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li class="active"><a href="/"><i class="fa fa-home"></i> Paynamics Credentials</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<div class='col-sm-6'>
    <div class='box'>
        <div class='box-body'>
            <form method="post" action='{{url('/admin/update_paygate_details')}}'>
                {{csrf_field()}}
                <div class='form-group'>
                    <label>Merchant ID</label>
                    <input type='text' class="form-control" name='merchantid' value="{{$paygate_details->merchantid}}" required="">
                </div>
                <div class='form-group'>
                    <label>Merchant Key</label>
                    <input type='text' class="form-control" name='merchantkey' value="{{$paygate_details->merchantkey}}" required="">
                </div>
                <div class='form-group'>
                    <label>Merchant IP</label>
                    <input type='text' class="form-control" name='merchantip' value="{{$paygate_details->merchantip}}" required="">
                </div>
                <div class='form-group'>
                    <label>Merchant Security Type</label>
                    <input type='text' class="form-control" name='merchantsec' value="{{$paygate_details->merchantsec}}" required="" readonly="">
                </div>
                <div class='form-group'>
                    <input type='submit' value='Update Details' class='btn btn-success col-sm-12'>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('footerscript')
@endsection