<?php
$programs = \App\CtrAcademicProgram::distinct()->where('academic_type', 'College')->get(array('program_code', 'program_name'));
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
        View Curriculum
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="#"> Curriculum Management</a></li>
        <li class="active"><a href="{{ url ('/registrar_college', array('curriculum_management','add_electives'))}}"> Electives</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<section class="content">
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Add Electives</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form form-group">
                                <label>Program</label>
                                <select class="form form-control select2" id="program_code" onchange="getElectives(this.value)">
                                    <option>Select Program</option>
                                    @foreach($programs as $program)
                                    <option value="{{$program->program_code}}">{{$program->program_code}} - {{$program->program_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-6" id="show_electives">

        </div>
        <div class="col-sm-6" id="add">
            <div class="box">
                <div class="box-header">
                    <b>Add Electives</b>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form form-group">
                                <label>Course Code</label>
                                <input type="text" id="course_code" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="form form-group">
                                <label>Course Name</label>
                                <input type="text" id="course_name" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form form-group">
                                <label>Curriculum Year</label>
                                <input type="text" id="curriculum_year" class="form-control">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form form-group">
                                <label>Lec</label>
                                <input type="text" id="lec" class="form-control" value="0">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form form-group">
                                <label>Lab</label>
                                <input type="text" id="lab" class="form-control" value="0">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form form-group">
                                <label>Subject Related Fee</label>
                                <input type="text" id="srf" class="form-control" value="0">
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form form-group">
                                <button class="col-sm-12 btn btn-success" onclick="addElectives(course_code.value, course_name.value, lec.value, lab.value, curriculum_year.value, program_code.value, srf.value)">Add Electives</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('footerscript')
<script>
    $('#add').hide();
    $('#show_electives').hide();
</script>
<script>
    function getElectives(program_code) {
        array = {};
        array['program_code'] = program_code;
        $.ajax({
            type: "GET",
            url: "/ajax/registrar_college/curriculum_management/get_electives/",
            data: array,
            success: function (data) {
                $('#show_electives').fadeIn().html(data);
                $('#add').fadeIn().html();
            }
        });
    }

    function addElectives(course_code, course_name, lec, lab, curriculum_year, program_code, srf) {
        array = {};
        array['course_code'] = course_code;
        array['course_name'] = course_name;
        array['lec'] = lec;
        array['lab'] = lab;
        array['srf'] = srf;
        array['curriculum_year'] = curriculum_year;
        array['program_code'] = program_code;
        $.ajax({
            type: "GET",
            url: "/ajax/registrar_college/curriculum_management/add_electives/",
            data: array,
            success: function () {
                getElectives(program_code);
            }
        });
    }
    
    function remove_electives(id, program_code){
        array = {};
        array['id'] = id;
        $.ajax({
            type: "GET",
            url: "/ajax/registrar_college/curriculum_management/remove_electives",
            data: array,
            success: function () {
                getElectives(program_code);
            }
        });
    }
</script>

@endsection