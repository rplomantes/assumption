<?php
$school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first()->school_year;
$period = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first()->period;
?>
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
        Grades
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="/"> Grade Management</a></li>
        <li class="active"><a href="{{url('registrar_college', array('grade_management','view_grades'))}}">View Grades</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<div class='col-sm-12'>
    <div class='box'>
        <div class='box-body'>
            <?php $courses = \App\CourseOffering::distinct()->where('school_year', $school_year)->where('period', $period)->get(['course_code', 'course_name']); ?>
            <div class="col-sm-6">
                <div class="form-group">
                    <label>Select Course</label>
                    <select class="form form-control select2" onchange="selectSchedule(this.value)">
                        <option value="">Select Course</option>
                        @foreach($courses as $course)
                        <option value="{{$course->course_code}}">{{$course->course_code}} - {{$course->course_name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-sm-4" id="sched">
            </div>
            <div class="col-sm-2" id="search">
                <label>&nbsp;</label>
                <button class="btn btn-primary col-sm-12">Search</button>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footerscript')
<script>
    $('#search').hide();
    function selectSchedule(course_code){
        array = {};
        array['course_code'] = course_code;
        $.ajax({
            type: "GET",
            url: "/ajax/registrar_college/grade_management/get_schedules",
            data: array,
            success: function (data) {
                $('#sched').hide().html(data).fadeIn();
                $('#search').fadeIn();
            }

        });
    }
</script>
@endsection