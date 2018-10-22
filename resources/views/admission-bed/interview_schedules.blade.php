@extends('layouts.appadmission-bed')
@section('messagemenu')

<link rel="stylesheet" type="text/css" href="{{asset ('jquery.datetimepicker.css')}}">
<li class="dropdown messages-menu">
    <!-- Menu toggle button -->
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-envelope-o"></i>
        <span class="label label-success">4</span>
    </a>
    <ul class="dropdown-menu">
        <li class="header">You have 4 messages</li>
        <li>
            <!-- inner menu: contains the messages -->
            <ul class="menu">
                <li><!-- start message -->
                    <a href="#">
                        <div class="pull-left">
                            <!-- User Image -->

                        </div>
                        <!-- Message title and timestamp -->
                        <h4>
                            Support Team
                            <small><i class="fa fa-clock-o"></i> 5 mins</small>
                        </h4>
                        <!-- The message -->
                        <p>Why not buy a new awesome theme?</p>
                    </a>
                </li>
                <!-- end message -->
            </ul>
            <!-- /.menu -->
        </li>
        <li class="footer"><a href="#">See All Messages</a></li>
    </ul>
</li>
@endsection
@section('header')
<section class="content-header">
    <h1>
        Parent Interview Schedules
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Here</li>
    </ol>
</section>
@endsection
@section('maincontent')
<?php $counter = 1; ?>
<div class="col-md-12 box box-body">
    
        @if (Session::has('message'))
            <div class="alert alert-success">{{ Session::get('message') }}</div>
        @endif  
    <button class="btn btn-success" data-toggle="modal" data-target="#show_adding_schedule">Create New Schedule</button>
    <table class="table table-condensed">
        <thead>
            <tr>
                <th>#</th>
                <th>Schedule</th>
                <th>Status</th>
                <th>View List</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($schedules as $schedule)
            <tr>
                <td>{{$counter++}}</td>
                <td>{{date('l, F j, Y - g:i A',strtotime($schedule->datetime))}}</td>
                <td>@if ($schedule->is_remove==1) Inactive @else Active @endif</td>
                <td><a href='{{url('/admissionbed', array('view_interview_list', $schedule->id))}}'>View List</a></td>
                <td><a href="{{url('/admissionbed', array('edit_interview_schedule', $schedule->id))}}">Edit</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>    
<div class="modal fade" id="show_adding_schedule">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add New Parent Interview Schedule</h4>
            </div>
        
        <form method="post" action="{{url('admissionbed',array('add_interview_schedule'))}}">
            <div class="modal-body">
                {{ csrf_field() }}
                    <label>Date & Time</label>
                    <div class="input-group stylish-input-group">
                        <input name="datetime" type='text' id='datetimepicker' class='form form-control' placeholder='yyyy-mm-dd hh:mm:ss'>
                        <span class="input-group-addon">
                            <span class="fa fa-calendar"></span> 
                        </span>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-primary" value="Save schedule">
            </div>
        </form>
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
