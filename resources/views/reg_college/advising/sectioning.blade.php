<?php $student_lists = \App\GradeCollege::where('course_code', $course_code)->where('school_year', $advising_school_year->school_year)->where('period', $advising_school_year->period)->get(); ?>

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
        Advising Statistics
        <small>A.Y. {{$advising_school_year->school_year}} - {{$advising_school_year->school_year+1}} {{$advising_school_year->period}}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="#"> Advising</a></li>
        <li class="active"><a href="{{ url ('/registrar_college', array('advising','advising_statistics'))}}"> Advising Statistics</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<section class="content">
    <div class="row">
        <div class="col-sm-12">

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Sectioning</h3>
                </div>
                <div class="box-body">
                    <!--students list-->
                    <div class="col-sm-6" id='studentlist'>
                        <h4>Student List</h4>
<table class="table table-condensed">
    <tr>
        <th>No.</th>
        <th>ID Number</th>
        <th>Name</th>
        <th>Program</th>
        <th></th>
    </tr>
</table>
                    </div>
                    <!--assigned section-->
                    <div class="col-sm-6" id='student_schedule_list'>
                        <h4>Schedule</h4>
                        <div class="col-sm-6">
                        <select id='schedule_id' class='form form-control select2' onchange="getsection(this.value);">
                            <option value="">Select Schedule</option>
                            <?php $enrollment_school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first();?>
                            <?php $sections = \App\CourseOffering::distinct()->where('course_code', $course_code)->where('school_year', $enrollment_school_year->school_year)->where('period', $enrollment_school_year->period)->where('schedule_id', '!=', null)->get(['schedule_id']); ?>
                            @foreach ($sections as $available_class)
                            <option value="{{$available_class->schedule_id}}">
                        
                                    <?php
                                    $schedule2s = \App\ScheduleCollege::distinct()->where('schedule_id', $available_class->schedule_id)->get(['time_start', 'time_end', 'room']);
                                    ?>
                                    @foreach ($schedule2s as $schedule2)
                                    <?php
                                    $days = \App\ScheduleCollege::where('schedule_id', $available_class->schedule_id)->where('time_start', $schedule2->time_start)->where('time_end', $schedule2->time_end)->where('room', $schedule2->room)->get(['day']);
                                    ?>
                                    <!--                @foreach ($days as $day){{$day->day}}@endforeach {{$schedule2->time}} <br>-->
                                    @foreach ($days as $day){{$day->day}}@endforeach 
                                    <?php $is_tba = \App\ScheduleCollege::where('schedule_id', $available_class->schedule_id)->first()->is_tba; ?>
                                        @if ($is_tba == 0)
                                        {{date('g:i A', strtotime($schedule2->time_start))}} - {{date('g:i A', strtotime($schedule2->time_end))}}<br>
                                        @else
                                        
                                        @endif
                                    @endforeach
                                    <?php
                                    $schedule3s = \App\ScheduleCollege::distinct()->where('schedule_id', $available_class->schedule_id)->get(['time_start', 'time_end', 'room']);
                                    ?>
                                    @foreach ($schedule3s as $schedule3)
                                    {{$schedule3->room}}<br>
                                    @endforeach
                            </option>
                            @endforeach
                        </select>
                        </div>
                        
                        <div class="col-sm-6">
                        <select id='section' class='form form-control select2' onchange="get_schedule_student_list('{{$course_code}}', schedule_id.value, this.value)">
                            <option value="">Select Section</option>
                        </select>
                        </div>
                        <div class='col-sm-12' id='student_list'>
                            Student List
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

@endsection
@section('footerscript')
<script>
    function getsection(schedule_id){
        array = {};
        array['schedule_id'] = schedule_id;
        $.ajax({
            type: "GET",
            url: "/ajax/registrar_college/advising/getsection",
            data: array,
            success: function (data) {
                $('#section').html(data);
            }

        });
    }
    function get_schedule_student_list(course_code,schedule_id,section){
        array = {};
        array['schedule_id'] = schedule_id;
        array['course_code'] = course_code;
        array['section'] = section;
        $.ajax({
            type: "GET",
            url: "/ajax/registrar_college/advising/getschedulestudentlist",
            data: array,
            success: function (data) {
                $('#student_list').html(data);
            }

        });
        studentlist(course_code, schedule_id, section)
    }
    function studentlist(course_code, schedule_id, section){
        array = {};
        array['course_code'] = course_code;
        array['schedule_id'] = schedule_id;
        array['section'] = section;
        $.ajax({
            type: "GET",
            url: "/ajax/registrar_college/advising/getstudentlist",
            data: array,
            success: function (data) {
                $('#studentlist').html(data);
            }

        });
    }
    function addtosection(idno, course_code, schedule_id, section) {
        array = {};
        array['course_code'] = course_code;
        array['idno'] = idno;
        array['schedule_id'] = schedule_id;
        array['section'] = section;
        $.ajax({
            type: "GET",
            url: "/ajax/registrar_college/advising/addtosection",
            data: array,
            success: function (data) {
                get_schedule_student_list(course_code, schedule_id, section);
            }

        });
        studentlist(course_code, schedule_id, section)
    }
    function removetosection(idno, course_code, schedule_id, section){
        array = {};
        array['idno'] = idno;
        array['course_code'] = course_code;
        $.ajax({
            type: "GET",
            url: "/ajax/registrar_college/advising/removetosection",
            data: array,
            success: function (data) {
                get_schedule_student_list(course_code, schedule_id, section);
            }

        });
        studentlist(course_code, schedule_id, section)
    }
</script>
@endsection