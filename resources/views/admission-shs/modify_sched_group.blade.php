<link rel="stylesheet" href="{{ asset ('bower_components/select2/dist/css/select2.min.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset ('jquery.datetimepicker.css')}}">
@extends('layouts.appadmission-bed')
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
        BED Admission Interview Schedule
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-home"></i> Home</li>
        <li>Group Interview Schedule</li>
        <li class="active"><a href="{{url('admissionbed', array('edit_group_schedule', $id))}}"> {{$id}}</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<div class="col-md-4">
    <div class="box">
        <div class="box-header">
            <h3 class="box-title">Edit Group Interview Schedule</h3>
            <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
            </div>
        </div>
        <div class="box-body">
            <div class="col-sm-12">
                <form class="form-horizontal" method="post" action="{{url('admissionbed', array('edit_group_schedule_now'))}}">
                    <div class="form-group">
                        <label>Date & Time</label>
                        {{ csrf_field() }}
                        <input type="hidden" value="{{$id}}" name="id">
                    <label>Date & Time</label>
                    <div class="input-group stylish-input-group">
                        <input name="datetime" type='text' id='datetimepicker' value="{{$schedule->datetime}}" class='form form-control' placeholder='yyyy-mm-dd hh:mm:ss'>
                        <span class="input-group-addon">
                            <span class="fa fa-calendar"></span> 
                        </span>
                    </div>
                    </div>
                    <div class="form-group">
                        <input type="submit" value="Update Schedule" class="col-sm-12 btn btn-success">
                    </div>
                </form>
            </div>
        </div>
    </div>        
</div>
@endsection
@section('footerscript')
<script src="{{ asset('build/jquery.datetimepicker.full.js')}}"></script>
<script>
$('#datetimepicker').datetimepicker({
    dayOfWeekStart: 1,
    lang: 'en'
});
$('#datetimepicker').datetimepicker();

</script>
@endsection
