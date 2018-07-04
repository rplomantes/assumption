@extends('layouts.appreg_college')
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
        Open/Close Grading Module
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="/"> Grade Management</a></li>
        <li class="active"><a href="{{url('registrar_college', array('grade_management','open_close'))}}">Open/Close</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<div class='col-sm-12'>
    <div class='box'>
        <div class='box-body'>
            <form class='form-horizontal' action='{{url('registrar_college', array('grade_management','open_close', 'submit'))}}' method='post'>
                {{ csrf_field() }}
                <div class='form-group'>
                    <div class='col-sm-4'>
                        <label>Midterm</label>
                        <select name='midterm' class='form form-control'>
                            <option value='0' @if ($status->midterm == 0) selected='' @endif>Open</option>
                            <option value='1' @if ($status->midterm == 1) selected='' @endif>Close</option>
                        </select>
                    </div>
                    <div class='col-sm-4'>
                        <label>Finals</label>
                        <select name='finals' class='form form-control'>
                            <option value='0' @if ($status->finals == 0) selected='' @endif>Open</option>
                            <option value='1' @if ($status->finals == 1) selected='' @endif>Close</option>
                        </select>
                    </div>
                </div>
                <div class='form-group'>
                    <div class='col-sm-12'>
                        <input type='submit' value='Save' class='btn btn-success col-sm-12' onclick="if (confirm('Do you really want to continue?'))
                    return true;
                else
                    return false;">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('footerscript')
@endsection