<?php
$user = \App\User::where('idno', $idno)->first();
?>
<?php
$school_year = \App\CtrAdvisingSchoolYear::where('academic_type', 'College')->first();
?>
@extends("layouts.appdean_college")
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
        <small>A.Y. {{$school_year->school_year}} - {{$school_year->school_year+1}} {{$school_year->period}}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-home"></i> Home</a></li>
        <li>View Grades</li>
        <li class="active">{{$user->idno}}</li>
    </ol>
</section>
@endsection
@section("maincontent")
<?php $levels = \App\Curriculum::distinct()->where('curriculum_year', $student_info->curriculum_year)->where('program_code', $student_info->program_code)->orderBy('level')->get(['level']); ?>
@foreach ($levels as $level)
<?php $periods = \App\Curriculum::distinct()->where('level', $level->level)->where('curriculum_year', $student_info->curriculum_year)->where('program_code', $student_info->program_code)->orderBy('period')->get(['period']); ?>
@foreach ($periods as $period)
<?php $curricula = \App\Curriculum::where('curriculum_year', $student_info->curriculum_year)->where('program_code', $student_info->program_code)->where('level', $level->level)->where('period', $period->period)->get(); ?>
<table class="table table-striped" width="100%">
    <br><b>{{$level->level}} - {{$period->period}}</b>
    <thead>
        <tr>
            <th width="10%">Code</th>
            <th width="50%">Description</th>
            <th width="5%">Lec</th>
            <th width="5%">Lab</th>
            <th width="8%">Grade</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($curricula as $curriculum)
<?php $grades = \App\GradeCollege::where('idno', $idno)->where('course_code', $curriculum->course_code)->first(); ?>
        <tr>
            <td>{{$curriculum->course_code}}</td>
            <td>{{$curriculum->course_name}}</td>
            <td>{{$curriculum->lec}}</td>
            <td>{{$curriculum->lab}}</td>
            <td>@if (count($grades)>0) {{$grades->grade_point}} @else Not Yet Taken @endif</td>
        </tr>
        @endforeach
        @endforeach
        @endforeach
    </tbody>
</table>
@endsection
@section("footerscript")
@endsection