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
        Dashboard
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li class="active"><a href="/"><i class="fa fa-home"></i> Dashboard</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <form method="post" action="https://start.engagespark.com/api/v1/messages/sms">
            <!--<form method="post" action="{{url('send_sms')}}">-->
                {{ csrf_field() }}
                <input type="text" name="token" value="b2b48b24624ad811f47632de4155de9b9a98c95b">
                <input type="text" name="organization_id" value="7405">
                <input type="text" name="recepient_type" value="mobile_number">
                <input type="text" name="mobile_numbers" value="639150438781">
                <input type="text" name="message" value="TEST!">
                <input type="text" name="sender_id" value="TEST">
                <input type="submit" value="send test">
            </form>
        </div>
    </div>
</section>

@endsection
@section('footerscript')
@endsection