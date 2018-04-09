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
                <div class="box-body">
                    <div class="form-group col-sm-9">
                        <label>Course</label>
                        <select class="form form-control select2" name="course_code" id='course_code'>
                            <option value="">Select Course</option>
                            @foreach ($courses as $course)
                            <option value='{{$course->course_code}}'>{{$course->course_code}} - {{$course->course_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-sm-3">
                        <label>&nbsp;</label>
                        <button class="btn btn-primary col-sm-12" onclick="get_advising_statistics(course_code.value)">Generate Statistics Report</button>
                    </div>
                </div>
            </div>
            <div id='show_result'>
            </div>
        </div>
</section>

@endsection
@section('footerscript')
<script>
    function get_advising_statistics(course_code) {
        array = {};
        array['course_code'] = course_code;
        array['school_year'] = "{{$advising_school_year->school_year}}";
        array['period'] = "{{$advising_school_year->period}}";
        $.ajax({
            type: "GET",
            url: "/ajax/registrar_college/advising/get_advising_statistics",
            data: array,
            success: function (data) {
                $('#show_result').html(data);
            }

        });
    }
</script>
@endsection