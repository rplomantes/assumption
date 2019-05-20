<?php
$school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first();
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
        TUTORIAL CLASSES
        <small>A.Y. {{$school_year->school_year}} - {{$school_year->school_year+1}} {{$school_year->period}}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="#"> Curriculum Management</a></li>
        <li class="active"><a href="{{ url ('/registrar_college', array('curriculum_management','course_offering'))}}"> Tutorial Classes</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<section class="content">
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Search Courses</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class="col-md-6">
                            <label>Program</label>
                            <select id="program_code" class="form-control select2" style="width: 100%;" onchange="display_others(this.value)">
                                <option value=" ">Program</option>
                                @foreach ($programs as $program)
                                <option value="{{$program->program_code}}">{{$program->program_code}}-{{$program->program_name}}</option>
                                @endforeach
                            </select>
                    </div>
                </div>
                <div class="box-body">
                    <div class="col-sm-12">
                        <div id="display_others">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div id="course_to_offer">
            </div>
        </div>
        <div class="col-sm-6">
            <div id="course_offered">
            </div>
        </div>
    </div>
</section>

@endsection
@section('footerscript')
<script>
    $("#level-form").hide();
    $("#period-form").hide();
    $("#section-form").hide();
    $("#section_name-form").hide();
    $("#submit_elective-form").hide();
    
    $("#curriculum_year-form").change(function(){
        $("#level-form").fadeIn();
    });
    $("#level-form").change(function(){
        $("#period-form").fadeIn();
    });
    $("#year_level-form").change(function(){
        $("#section-form").fadeIn();
    });
    $("#section-form").change(function(){
        $("#section_name-form").fadeIn();
    });
    $("#elective-form").change(function(){
        $("#submit_elective-form").fadeIn();
    });
</script>
<script>
    function getList_tutorials(program_code){
    array = {};
    array['curriculum_year'] = $("#curriculum_year").val();
    array['level'] = $("#level").val();
    array['period'] = $("#period").val();
    array['section'] = 1;
    $.ajax({
    type: "GET",
            url: "/ajax/registrar_college/curriculum_management/view_offering_tutorials/" + program_code,
            data: array,
            success: function (data) {
            $('#course_to_offer').hide().html(data).fadeIn();
            }
    });
    getCourseOffered_tutorials(array, program_code);
    }

    function getCourseOffered_tutorials(array, program_code){
    array['curriculum_year'];
    array['level'] = $("#year_level").val();
    array['period'];
    array['section'];
    $.ajax({
    type: "GET",
            url: "/ajax/registrar_college/curriculum_management/view_course_offered_tutorials/" + program_code,
            data: array,
            success: function (data) {
            $('#course_offered').hide().html(data).fadeIn();
            }

    });
    }

    function addtocourseoffering_tutorials(course_code) {
    array = {};
    array['curriculum_year'] = $("#curriculum_year").val();
    array['level'] = $("#year_level").val();
    array['period'] = $("#period").val();
    array['section'] = 1;
    array['section_name'] = $("#section_name").val();
    array['program_code'] = $("#program_code").val();
    $.ajax({
    type: "GET",
            url: "/ajax/registrar_college/curriculum_management/add_to_course_offered_tutorials/" + course_code,
            data: array,
            success: function (data) {
            $('#course_offered').html(data);
            }

    });
    }
    
    function removecourse_tutorials(id) {
    array = {};
    array['id'] = id;
    array['program_code'] = $("#program_code").val();
    array['curriculum_year'] = $("#curriculum_year").val();
    array['section'] = 1;
    array['level'] = $("#year_level").val();
    array['period'] = $("#period").val();
    if (confirm("Are You Sure To Remove?")) {
    $.ajax({
    type: "GET",
            url: "/ajax/registrar_college/curriculum_management/remove_course_offered_tutorials/" + id,
            data: array,
            success: function (data) {
            $('#course_offered').html(data);
            }

    });
    }
    }
    
    function add_elective_tutorials(id){
    array = {};
    array['id'] = id;
    array['curriculum_year'] = $("#curriculum_year").val();
    array['level'] = $("#year_level").val();
    array['period'] = $("#period").val();
    array['section'] = 1;
    array['section_name'] = $("#section_name").val();
    array['program_code'] = $("#program_code").val();
    $.ajax({
    type: "GET",
            url: "/ajax/registrar_college/curriculum_management/add_offering_electives_tutorials/",
            data: array,
            success: function (data) {
            $('#course_offered').html(data);
            }

    });
    }
    
    function display_others(program_code){
    array = {};
    array['program_code'] = program_code;
    $.ajax({
    type: "GET",
            url: "/ajax/registrar_college/curriculum_management/display_others_tutorials/",
            data: array,
            success: function (data) {
            $('#display_others').hide().html(data).fadeIn();
            }

    });
    
        
    }

</script>
<script src="{{asset('bower_components/select2/dist/js/select2.full.min.js')}}"></script>
<script>
    $(function () {
        $('.select2').select2();
    });
</script>
@endsection