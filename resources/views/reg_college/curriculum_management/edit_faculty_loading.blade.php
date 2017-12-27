<?php
$school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first();
$faculty = \App\User::where('idno', $idno)->first();
?>
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
        {{$faculty->firstname}} {{$faculty->lastname}}
        <small>A.Y. {{$school_year->school_year}} - {{$school_year->school_year+1}} {{$school_year->period}}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="#"> Curriculum Management</a></li>
        <li class="active"><a href="{{ url ('/registrar_college', array('curriculum_management','faculty_loading'))}}"> Faculty Loading</a></li>
        <li><a href="#"> Faculty Loading Editor</a></li>
        <li class="active"><a href="{{ url ('/registrar_college', array('curriculum_management','edit_faculty_loading', $idno))}}"> {{$idno}}</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<section class="content">
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Schedule</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <?php
                    $user = \App\User::where('id', $idno)->first();
                    $school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first();
                    $loads = \App\ScheduleCollege::distinct()->where('instructor_id', $idno)->where('school_year', $school_year->school_year)->where('period', $school_year->period)->get(['schedule_id', 'course_code']);

                    $courses = \App\CourseOffering::distinct()->where('school_year', $school_year->school_year)->where('period', $school_year->period)->get(['course_name', 'course_code']);
                    ?>
                    @if (count($loads)>0)
                    <div class='table-responsive'>
                    <table class="table table-striped">
                        <thead>
                        <th class="col-sm-2">Course Code</th>
                        <th class="col-sm-4">Sections</th>
                        <th class="col-sm-3">Schedule</th>
                        <th class="col-sm-2">Room</th>
                        <th class="col-sm-1"></th>
                        </thead>
                        <tbody>
                            @foreach($loads as $load)
                            <tr>
                                <td>
                                    <?php
                                    $schedules = \App\ScheduleCollege::where('schedule_id', $load->schedule_id)->get();
                                    $details = \App\CourseOffering::where('schedule_id', $load->schedule_id)->get();
                                    ?>
                                    {{$load->course_code}}
                                </td>
                                <td>
                                    @foreach ($details as $detail)
                                    {{$detail->program_code}} - {{$detail->level}}  - {{$detail->section}}<br>
                                    @endforeach
                                </td>
                                <td>
                                    <?php
                                    $schedule2s = \App\ScheduleCollege::distinct()->where('school_year', $school_year->school_year)->where('period', $school_year->period)->where('schedule_id', $load->schedule_id)->get(['time_start', 'time_end', 'room']);
                                    ?>
                                    @foreach ($schedule2s as $schedule2)
                                    <?php
                                    $days = \App\ScheduleCollege::where('schedule_id', $load->schedule_id)->where('time_start', $schedule2->time_start)->where('time_end', $schedule2->time_end)->where('room', $schedule2->room)->get(['day']);
                                    ?>
                                    @foreach ($days as $day){{$day->day}}@endforeach {{date('g:i A', strtotime($schedule2->time_start))}} - {{date('g:i A', strtotime($schedule2->time_end))}} <br>
                                    <!--{{$schedule2->day}} {{$schedule2->time_start}} - {{$schedule2->time_end}}<br>-->
                                    @endforeach
                                </td>
                                <td>
                                    <?php
                                    $schedule3s = \App\ScheduleCollege::distinct()->where('schedule_id', $load->schedule_id)->get(['time_start', 'time_end', 'room']);
                                    ?>
                                    @foreach ($schedule3s as $schedule3)
                                    {{$schedule3->room}}<br>
                                    @endforeach
                                </td>
                                <td><a href="{{url ('registrar_college',array('curriculum_management','remove_faculty_loading',$load->schedule_id, $idno))}}"><button class="btn btn-danger"><span class="fa fa-minus"></span></button></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                    @else
                    <div class="alert alert-danger">No Courses Loaded!!</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Load a Course</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group" id="selected_course-form">
                                <label>Courses</label>
                                <select id="selected_course" class="form-control select2" style="width: 100%;">
                                    <option value=""></option>
                                    @foreach($courses as $course)
                                    <option value="{{$course->course_code}}">{{$course->course_code}} - {{$course->course_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="col-md-12">&nbsp;</label>
                            <button id="loads-form" type="button" class="btn btn-success" onclick="show_available_loads(selected_course.value)"  data-toggle="modal" data-target="#show_loads">
                                Select Schedule
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</section>

<div class="modal fade" id="show_loads">

</div>

@endsection
@section('footerscript')
<script>
    $("#loads-form").hide();

    $("#selected_course-form").change(function () {
        $("#loads-form").fadeIn();
    });
</script>
<script src="{{asset('bower_components/select2/dist/js/select2.full.min.js')}}"></script>
<script>
    $(function () {
        $('.select2').select2();
    });
</script>
<script>
    function show_available_loads(course) {
        array = {};
        array['course_code'] = course;
        array['instructor_id'] = "{{$idno}}";
        $.ajax({
            type: "GET",
            url: "/ajax/registrar_college/curriculum_management/show_available_loads/",
            data: array,
            success: function (data) {
                $('#show_loads').html(data);
            }

        });
    }
</script>
@endsection