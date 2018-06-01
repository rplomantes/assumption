<?php
$user = \App\User::where('idno', $idno)->first();
$status = \App\Status::where('idno', $idno)->first();
$student_info = \App\StudentInfo::where('idno', $idno)->first();
?>
<?php
$school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first();
?>
<?php
$file_exist = 0;
if (file_exists(public_path("images/" . $user->idno . ".jpg"))) {
    $file_exist = 1;
}
?>
<?php
//$grade_colleges = \App\GradeCollege::where('idno', $idno)->where('school_year', $school_year->school_year)->where('period', $school_year->period)->get();
$units = 0;
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
        Assessment
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="#"> Assessment</a></li>
        <li class="active"><a href="{{ url ('/registrar_college', array('assessment',$idno))}}"> {{$idno}}</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<section class="content">
    <div class="row">
        <div class="col-md-4">
            <!-- Widget: user widget style 1 -->
            <div class="box box-widget widget-user-2">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-yellow">
                    <div class="widget-user-image">
                        @if($file_exist==1)
                        <img src="/images/{{$user->idno}}.jpg"  width="25" height="25" class="img-circle" alt="User Image">
                        @else
                        <img class="img-circle" width="25" height="25" alt="User Image" src="/images/default.png">
                        @endif
                    </div>
                    <h3 class="widget-user-username">{{$user->firstname}} {{$user->lastname}}</h3>
                    <h5 class="widget-user-desc">{{$user->idno}}</h5>
                </div>
                <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                        @if(count($status)>0)
                            @if($status->is_new == "0")
                            <li><a href="#">Previous Status <span class="pull-right">Old Student</span></a></li>
                            <li><a href="#">Previous Program <span class="pull-right">{{$status->program_code}}</span></a></li>
                            <li><a href="#">Previous Level <span class="pull-right">{{$status->level}}</span></a></li>
                            <!--<li><a href="#">Previous Section <span class="pull-right">{{$status->section}}</span></a></li>-->
                            @else
                            <li><a href="#">Status <span class="pull-right">New Student</span></a></li>
                            <li><a href="#">Program <span class="pull-right">{{$status->program_code}}</span></a></li>
                            <li><a href="#">Level <span class="pull-right">{{$status->level}}</span></a></li>
                            <!--<li><a href="#">Section <span class="pull-right">{{$status->section}}</span></a></li>-->
                            @endif
                        @else 
                        <li><a href="#">Status <span class="pull-right">New Student</span></a></li>
                        <li><a href="#">Program <span class="pull-right">{{$status->program_code}}</span></a></li>
                        <li><a href="#">Level <span class="pull-right">{{$status->level}}</span></a></li>
                        <!--<li><a href="#">Section <span class="pull-right">{{$status->section}}</span></a></li>-->
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Select School Year and Period</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class='box-body'>
                    <form class='form-horizontal' action='{{url('registrar_college',array('assessment','set_up_school_year'))}}' method='post'>
                        {{csrf_field()}}
                        <input type='hidden' value='{{$user->idno}}' name='idno'>
                        <label>School Year</label>
                        <select class='form form-control' name='school_year'>
                            <option></option>
                            <option>2017</option>
                            <option>2018</option>
                        </select>
                        <label>Period</label>
                        <select class='form form-control' name='period'>
                            <option></option>
                            <option>1st Semester</option>
                            <option>2nd Semester</option>
                            <option>Summer</option>
                        </select>
                        <label></label>
                        <input class='form form-control btn btn-success' type='submit' value='Set School Year and Period'>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('footerscript')
@endsection