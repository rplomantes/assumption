@extends('layouts.appreg_college')
@section('messagemenu')
<link rel="stylesheet" href="{{ asset ('plugins/timepicker/bootstrap-timepicker.min.css')}}">
<link rel="stylesheet" href="{{ asset ('bower_components/select2/dist/css/select2.min.css')}}">

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
<?php
$school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first();
?>
<section class="content-header">
    <h1>
        {{$course_offering->course_code}} - {{$course_offering->course_name}}
        <small>A.Y. {{$school_year->school_year}} - {{$school_year->school_year+1}} {{$school_year->period}}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="#"></i> Curriculum Management</a></li>
        <li class="active"><a href="{{ url ('/registrar_college', array('curriculum_management','course_schedule'))}}"></i> Course Schedule</a></li>
        <li><a href="#"></i> Course Schedule Editor</a></li>
        <li><a href="{{ url ('/registrar_college', array('curriculum_management','edit_course_schedule',$course_offering->id))}}"></i> {{$course_offering->course_code}}</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<section class="content">
    <div class="row">
        <div class="col-sm-5">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Schedule</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Schedule</th>
                                <th>Room</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <?php
                                $schedules = \App\ScheduleCollege::where('course_offering_id', $course_offering->id)->get();
                                ?>
                                <td>
                                    <?php
                                    $schedule2s = \App\ScheduleCollege::distinct()->where('course_offering_id', $course_offering->id)->get(['time_start', 'time_end', 'room']);
                                    ?>
                                    @foreach ($schedule2s as $schedule2)
                                    <?php
                                    $days = \App\ScheduleCollege::where('course_offering_id', $course_offering->id)->where('time_start', $schedule2->time_start)->where('time_end', $schedule2->time_end)->where('room', $schedule2->room)->get(['day']);
                                    ?>
                                    <!--                @foreach ($days as $day){{$day->day}}@endforeach {{$schedule2->time}} <br>-->
                                    @foreach ($days as $day){{$day->day}}@endforeach {{date('g:i A', strtotime($schedule2->time_start))}} - {{date('g:i A', strtotime($schedule2->time_end))}}<br>
                                    @endforeach
                                </td>
                                <td>
                                    <?php
                                    $schedule3s = \App\ScheduleCollege::distinct()->where('course_offering_id', $course_offering->id)->get(['time_start', 'time_end', 'room']);
                                    ?>
                                    @foreach ($schedule3s as $schedule3)
                                    {{$schedule3->room}}<br>
                                    @endforeach
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-sm-7">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Add Schedule</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group" id="day-form">
                                <label>Day</label>
                                <select id="day" class="form-control" style="width: 100%;">
                                    <option value="">Day</option>
                                    <option value="M">Monday</option>
                                    <option value="T">Tuesday</option>
                                    <option value="W">Wednesday</option>
                                    <option value="Th">Thursday</option>
                                    <option value="F">Friday</option>
                                    <option value="Sa">Saturday</option>
                                    <option value="Su">Sunday</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" id="time_start-form">
                                <label>Time Start</label>
                                <div class="bootstrap-timepicker">
                                    <div class="input-group">
                                        <input type="text" class="form-control timepicker" id="time_start">

                                        <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group" id="time_end-form">
                                <label>Time End</label>
                                <div class="bootstrap-timepicker">
                                    <div class="input-group">
                                        <input type="text" class="form-control timepicker" id="time_end">

                                        <div class="input-group-addon">
                                            <i class="fa fa-clock-o"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-1">
                            <label class="col-md-12">&nbsp;</label>
                            <button id="room-form" type="button" class="btn btn-default" onclick="show_available_rooms(day.value, time_start.value, time_end.value)"  data-toggle="modal" data-target="#show_rooms">
                                Room
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Delete Schedule</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Day</th>
                                <th>Schedule</th>
                                <th>Room</th>
                                <th>Delete</th>
                        </thead>
                        <tbody>
                            @foreach($schedules as $schedule)
                                <tr>
                                    <td>{{$schedule->day}}</td>
                                    <td>{{date("g:i A", strtotime($schedule->time_start))}} - {{date("g:i A", strtotime($schedule->time_end))}}</td>
                                    <td>{{$schedule->room}}</td>
                                    <td><a href="{{ url ('registrar_college', array('curriculum_management','delete_course_schedule',$course_offering->id,$schedule->id))}}"><button class="btn btn-danger"><span class="fa fa-minus"></span></button></a></td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="show_rooms">
    
</div>

@endsection
@section('footerscript')
<script src="{{asset('plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
<script src="{{asset('bower_components/select2/dist/js/select2.full.min.js')}}"></script>
<script>
    $('#time_start-form').hide();
    $('#time_end-form').hide();
    $('#room-form').hide();
    
    $("#day-form").change(function(){
        $("#time_start-form").fadeIn();
        $("#time_end-form").fadeIn();
        $("#room-form").fadeIn();
    });
$(function () {
    $('.timepicker').timepicker({
        showInputs: false
    });
    $('.select2').select2();
});

function show_available_rooms(day, time_start, time_end) {
    array = {};
    array['day'] = day;
    array['time_start'] = time_start;
    array['time_end'] = time_end;
    array['course_offering_id'] = {{$course_offering->id}};
    $.ajax({
    type: "GET",
            url: "/ajax/registrar_college/curriculum_management/show_available_rooms/",
            data: array,
            success: function (data) {
            $('#show_rooms').html(data);
            }

    });
}
</script>
@endsection