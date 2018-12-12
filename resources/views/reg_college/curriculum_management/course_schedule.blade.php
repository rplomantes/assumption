<?php
$school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first();
$program_codes = \App\CourseOffering::distinct()->where('school_year', $school_year->school_year)->where('period', $school_year->period)->where('program_code','!=', 'FS')->where('program_code', '!=', 'TUT')->get(['program_code']);
$program_codes_fs = \App\CourseOffering::distinct()->where('school_year', $school_year->school_year)->where('period', $school_year->period)->where('program_code', 'FS')->get(['program_code']);
$program_codes_tutorials = \App\CourseOffering::distinct()->where('school_year', $school_year->school_year)->where('period', $school_year->period)->where('program_code', 'TUT')->get(['program_code']);

$levels = \App\CourseOffering::distinct()->orderBy('level')->get(['level']);
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
        Course Scheduling
        <small>A.Y. {{$school_year->school_year}} - {{$school_year->school_year+1}} {{$school_year->period}}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="#"> Curriculum Management</a></li>
        <li class="active"><a href="{{ url ('/registrar_college', array('curriculum_management','course_schedule'))}}"> Course Schedule</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<section class="content">
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Academic Program</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <input type="hidden" id="school_year" value="{{$school_year->school_year}}">
                    <input type="hidden" id="period" value="{{$school_year->period}}">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group" id="program-form">
                                <label>Program</label>
                                <select id="program_code" class="form-control select2" style="width: 100%;">
                                    <option value=" ">Select Program</option>
                                    @foreach ($program_codes_fs as $program_code_fs)
                                    <option value="{{$program_code_fs->program_code}}">{{$program_code_fs->program_code}} - Free Section</option>
                                    @endforeach
                                    @foreach ($program_codes_tutorials as $program_code_tutorial)
                                    <option value="{{$program_code_tutorial->program_code}}">{{$program_code_tutorial->program_code}} - Tutorials</option>
                                    @endforeach
                                    @foreach ($program_codes as $program_code)
                                    <option value="{{$program_code->program_code}}">{{$program_code->program_code}} - <?php $program_name = \App\CtrAcademicProgram::where('program_code', $program_code->program_code)->first()->program_name;?>{{$program_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group" id="level-form">
                                <label>Level</label>
                                <select id="level" class="form-control select2" style="width: 100%;" onchange="getsection(this.value, program_code.value)">
                                    <option value=" ">Select Level</option>
                                    @foreach ($levels as $level)
                                    <option value="{{$level->level}}">{{$level->level}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div id="section-form">
                                
                            </div>
                        </div>
                    </div>                    
                </div>
            </div>
            <div class="box" id="courses_offered">
            </div>
        </div>
    </div>
</section>

@endsection
@section('footerscript')
<script>
    $("#level-form").hide();
    $("#section-form").hide();
    $("#courses_offered").hide();
    $("#mod_button").hide();

    $("#program-form").change(function () {
        $("#courses_offered").hide();
        $("#level-form").hide();
        $("#section-form").hide();
        $("#level-form").fadeIn();
    });
    $("#section-form").change(function () {
        $("#courses_offered").fadeIn();
    });
</script>
<script>

    function courses_offered(program_code) {
        array = {};
        array['school_year'] = $("#school_year").val();
        array['period'] = $("#period").val();
        array['section'] = $("#level").val();
        array['level'] = $("#section").val();
        $.ajax({
            type: "GET",
            url: "/ajax/registrar_college/curriculum_management/course_to_schedule/" + program_code,
            data: array,
            success: function (data) {
                $('#courses_offered').fadeIn().html(data);
            }

        });
    }
    
    function getsection(level, program_code) {
        $("#courses_offered").hide();
        array = {}
        array['level'] = level;
        array['program_code'] = program_code;
        array['school_year'] = $("#school_year").val();
        array['period'] = $("#period").val();
        $.ajax({
            type: "GET",
            url: "/ajax/registrar_college/curriculum_management/get_section/",
            data: array,
            success: function (data) {
                $('#section-form').fadeIn().html(data);
            }

        });
    }
</script>

@endsection