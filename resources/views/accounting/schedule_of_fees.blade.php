<?php
$levels = \App\CtrAcademicProgram::distinct()->orderBy('level', 'asc')->get(['level']);
$strands = \App\CtrAcademicProgram::selectRaw("distinct strand")->where('academic_code', 'SHS')->get();
$programs = \App\CtrAcademicProgram::selectRaw("distinct program_name")->where('academic_type', 'College')->get();
?>
@extends('layouts.appaccountingstaff')
@section('messagemenu')
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
<section class="content-header">
    <h1>
        Schedule of Fees
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="{{url("/accounting/schedule_of_fees")}}">Schedule of Fees</a></li>
    </ol>
</section>
@endsection
@section('maincontent')

<div class="box">
    <div class="box-header">
        <div class="box-title">Search</div>
    </div>
    <div class="box-body form-horizontal">
        <div class="form-group">
            <div class="col-sm-3">
                <label>Select Level</label>
                <select class="form form-control" name="level" id="level">
                    @foreach ($levels as $level)
                    <option>{{$level->level}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-3" id="strand_control">
                <label>Select Strand</label>
                <Select name="strand" id="strand" class="form form-control">
                    <option value="">Select Strand</option>    
                    @foreach($strands as $strand)
                    <option>{{$strand->strand}}</option>
                    @endforeach
                </select> 
            </div>
            <div class="col-sm-3" id="program_control">
                <label>Select Program</label>
                <Select name="program" id="program" class="form form-control">
                    <option value="">Select Program</option>    
                    @foreach($programs as $program)
                    <option>{{$program->program_name}}</option>
                    @endforeach
                </select> 
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-3">
                <label>School Year</label>
                <select name="school_year" class="form form-control" id="school_year">
                </select>
            </div>
            <div class="col-sm-3" id="period_control">
                <label>Period</label>
                <select name="period" class="form form-control" id="period">
                    <option></option>
                    <option>1st Semester</option>
                    <option>2nd Semester</option>
                    <option>Summer</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <div class="col-sm-3">
                <btn class="btn btn-success form-control">Search</btn>
            </div>
        </div>
    </div>
</div>

@endsection
@section('footerscript')
<script>
    $("#strand_control").hide();
    $("#period_control").hide();
    $("#program_control").hide();
    $("#level").on('change', function (e) {
        if ($("#level").val() == "Grade 11" || $("#level").val() == "Grade 12") {
            $("#strand_control").fadeIn(300);
            $("#period_control").fadeIn(300);
            $("#program_control").fadeOut(300);
        } else if ($("#level").val() == "1st Year" || $("#level").val() == "2nd Year" || $("#level").val() == "3rd Year" || $("#level").val() == "4th Year" || $("#level").val() == "5th Year") {
            $("#period_control").fadeIn(300);
            $("#program_control").fadeIn(300);
            $("#strand_control").fadeOut(300);
        } else {
            $("#program_control").fadeOut(300);
            $("#period_control").fadeOut(300);
            $("#strand_control").fadeOut(300);
        }
    });
</script>
@endsection
