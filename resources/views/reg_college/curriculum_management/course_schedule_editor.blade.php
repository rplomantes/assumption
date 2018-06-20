<?php
$school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first();
$schedules = \App\ScheduleCollege::where('schedule_id', $course_offering->schedule_id)->get();
$merged_schedules = \App\CourseOffering::where('schedule_id',$course_offering->schedule_id)->where('schedule_id', '!=',NULL)->get();
?>
<link rel="stylesheet" href="{{ asset ('plugins/timepicker/bootstrap-timepicker.min.css')}}">
<link rel="stylesheet" href="{{ asset ('bower_components/select2/dist/css/select2.min.css')}}">
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
        {{$course_offering->course_code}} - {{$course_offering->course_name}}
        <small>A.Y. {{$school_year->school_year}} - {{$school_year->school_year+1}} {{$school_year->period}}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="#"> Curriculum Management</a></li>
        <li class="active"><a href="{{ url ('/registrar_college', array('curriculum_management','course_schedule'))}}"> Course Schedule</a></li>
        <li><a href="#"> Course Schedule Editor</a></li>
        <li><a href="{{ url ('/registrar_college', array('curriculum_management','edit_course_schedule',$course_offering->id))}}"> {{$course_offering->course_code}}</a></li>
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
                    
                    <div class='row'>
                        <?php $check_tba = \App\ScheduleCollege::where('course_offering_id', $course_offering->id)->where('is_tba', 1)->get();?>
                        @if(count($check_tba)<=0)
                        <div class="col-md-3">
                            <a href='{{url('registrar_college', array('curriculum_management','add_tba',$course_offering->id))}}'><button type="button" class="btn btn-default">
                                Set as TBA
                                </button></a>
                        </div>
                        @endif
                    </div>
                    <div class='table-responsive'>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Schedule</th>
                                <th>Room</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                                <?php
                                $schedules = \App\ScheduleCollege::distinct()->where('schedule_id', $course_offering->schedule_id)->get();
                                ?>
                                @foreach($schedules as $schedule)
                            <tr>
                                <td>{{$schedule->day}} {{date('g:i A', strtotime($schedule->time_start))}} - {{date('g:i A', strtotime($schedule->time_end))}}</td>
                                <td>{{$schedule->room}}</td>
                                <td>
                                    <button id="edit-schedule-form" type="button" class="btn btn-warning" onclick="edit_schedule({{$schedule->id}})" >
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                </td>
                            </tr>
                                @endforeach
                        </tbody>
                    </table>
                    </div>
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
                    
                    <div  id='edit-schedule'>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Merge Schedule</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="col-sm-6">
                    <table class="table table-striped">
                        <h4>Available Schedules</h4>
                        <thead>
                            <tr>
                                <th>Schedule</th>
                                <th>Room</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $distincts = \App\ScheduleCollege::distinct()->where('course_code', $course_offering->course_code)->where('schedule_id', '!=', $course_offering->schedule_id)->where('school_year', $school_year->school_year)->where('period', $school_year->period)->get(['schedule_id']);
                            ?>
                            @foreach($distincts as $distinct)
                            <tr>
                                <td>
                                    <?php
                                    $schedule2s = \App\ScheduleCollege::distinct()->where('schedule_id', $distinct->schedule_id)->get(['time_start', 'time_end', 'room']);
                                    ?>
                                    @foreach ($schedule2s as $schedule2)
                                    <?php
                                    $days = \App\ScheduleCollege::where('schedule_id', $distinct->schedule_id)->where('time_start', $schedule2->time_start)->where('time_end', $schedule2->time_end)->where('room', $schedule2->room)->get(['day']);
                                    ?>
                                    <!--                @foreach ($days as $day){{$day->day}}@endforeach {{$schedule2->time}} <br>-->
                                    @foreach ($days as $day){{$day->day}}@endforeach
                                    <?php $is_tba = \App\ScheduleCollege::where('schedule_id', $distinct->schedule_id)->first()->is_tba; ?>
                                    @if (count($is_tba)>0)    
                                    @if ($is_tba == 0)
                                        {{date('g:i A', strtotime($schedule2->time_start))}} - {{date('g:i A', strtotime($schedule2->time_end))}}<br>
                                        @else
                                        
                                        @endif
                                        @else
                                        @endif
                                    @endforeach

                                </td>
                                <td>
                                    <?php
                                    $schedule3s = \App\ScheduleCollege::distinct()->where('schedule_id', $distinct->schedule_id)->get(['time_start', 'time_end', 'room']);
                                    ?>
                                    @foreach ($schedule3s as $schedule3)
                                    {{$schedule3->room}}<br>
                                    @endforeach
                                </td>
                                <td><a href="{{url('registrar_college',array('curriculum_management','merge_schedule',$distinct->schedule_id,$course_offering->id))}}"><button class="btn btn-success"><span class="fa fa-compress"></span></button></a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                        
                    </div>
                    <div class="col-sm-6 ss">
                        <table class='table table-striped'>
                            <h4>Merged Schedule</h4>
                            <thead>
                                <tr>
                                    <th>Schedule</th>
                                    <th>Room</th>
                                    <th>Merged to</th>
                                    <th>Unmerged</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @if(count($merged_schedules)>0)
                                    <td>
                                        <?php
                                        $schedule2s = \App\ScheduleCollege::distinct()->where('schedule_id', $course_offering->schedule_id)->get(['time_start', 'time_end', 'room']);
                                        ?>
                                        @foreach ($schedule2s as $schedule2)
                                        <?php
                                        $days = \App\ScheduleCollege::where('schedule_id', $course_offering->schedule_id)->where('time_start', $schedule2->time_start)->where('time_end', $schedule2->time_end)->where('room', $schedule2->room)->get(['day']);
                                        ?>
                                        <!--                @foreach ($days as $day){{$day->day}}@endforeach {{$schedule2->time}} <br>-->
                                        @foreach ($days as $day){{$day->day}}@endforeach
                                        <?php $is_tba = \App\ScheduleCollege::where('schedule_id', $course_offering->schedule_id)->first()->is_tba; ?>
                                        @if ($is_tba == 0)
                                        {{date('g:i A', strtotime($schedule2->time_start))}} - {{date('g:i A', strtotime($schedule2->time_end))}}<br>
                                        @else
                                        
                                        @endif
                                        @endforeach
                                    </td>
                                    <td>
                                        <?php
                                        $schedule3s = \App\ScheduleCollege::distinct()->where('schedule_id', $course_offering->schedule_id)->get(['time_start', 'time_end', 'room']);
                                        ?>
                                        @foreach ($schedule3s as $schedule3)
                                        {{$schedule3->room}}<br>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($merged_schedules as $merged_schedule)
                                        {{$merged_schedule->section_name}}<br>
                                        @endforeach
                                    </td>
                                    <td>
                                        <a href="{{ url ('registrar_college', array('curriculum_management','unmerged_schedule',$course_offering->id))}}"><button class="btn btn-danger"><span class="fa fa-minus"></span></button></a>
                                    </td>
                                    @endif
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="show_rooms">
    
</div>

<div class="modal fade" id="show_rooms2">
    
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

function edit_schedule(schedule_id){
    array = {};
    array['schedule_id'] = schedule_id;
    array['course_offering_id'] = {{$course_offering->id}};
    $.ajax({
        type: "GET",
        url: "/ajax/registrar_college/curriculum_management/edit_schedule",
        data: array,
        success: function (data){
            $('#edit-schedule').html(data);
        }
    });
}

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

function edit_available_rooms(schedule_id,day, time_start, time_end) {
    array = {};
    array['schedule_id'] = schedule_id
    array['day'] = day;
    array['time_start'] = time_start;
    array['time_end'] = time_end;
    array['course_offering_id'] = {{$course_offering->id}};
    $.ajax({
    type: "GET",
            url: "/ajax/registrar_college/curriculum_management/show_available_rooms2/",
            data: array,
            success: function (data) {
            $('#show_rooms2').html(data);
            }

    });
}
</script>
@endsection