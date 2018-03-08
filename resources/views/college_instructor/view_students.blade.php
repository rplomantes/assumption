@extends('layouts.appcollege_instructor')
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
<?php
$close = \App\CtrCollegeGrading::where('academic_type', "College")->first();
?>
<section class="content-header">
    <h1>
        {{$course_name}}
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-home"></i> Dashboard</a></li>
        <li class="active"><a href="{{url('/college_instructor', array('grades', $schedule_id))}}">View List</a></li>
    </ol>
    
    @if ($close->midterm == 1 && $close->finals == 1 && $close->grade_point == 1)
    <br>
    <div class="alert alert-info alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h4><i class="icon fa fa-info"></i> Note!</h4>Giving of grades is not yet open.
    </div>
    @endif
</section>
@endsection
@section('maincontent')
<?php $number = 1; ?>
@foreach ($courses_id as $course_id)
<?php
$students = \App\GradeCollege::where('course_offering_id', $course_id->id)->join('users', 'users.idno', '=', 'grade_colleges.idno')->select('users.idno', 'users.firstname', 'users.lastname', 'grade_colleges.id', 'grade_colleges.midterm', 'grade_colleges.finals', 'grade_colleges.grade_point', 'grade_colleges.is_lock', 'grade_colleges.midterm_status', 'grade_colleges.finals_status', 'grade_colleges.grade_point_status')->orderBy('users.lastname')->get();
?>
@if (count($students)>0)

<form class="form form-horizontal" method="post" action="{{url('college_instructor', array('grades','save_submit'))}}">
    {{csrf_field()}}
    <input type="hidden" name="schedule_id" value="{{$schedule_id}}">
    <input type="hidden" name="midterm_status" value="{{$close->midterm}}">
    <input type="hidden" name="finals_status" value="{{$close->finals}}">
    <input type="hidden" name="grade_point_status" value="{{$close->grade_point}}">
    <div class="col-sm-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Section: {{$course_id->section_name}}</h3>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th width="3%">#</th>
                            <th width="8%">ID number</th>
                            <th>Name</th>
                            <th width="5%">Midterm</th>
                            <th width="5%">Finals</th>
                            <th width="5%">Grade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        <tr>
                            <td>{{$number}}<?php $number = $number + 1; ?></td>
                            <td>{{$student->idno}}</td>
                            <td>{{$student->lastname}}, {{$student->firstname}}</td>
                            <td><input class='grade' type="text" name="midterm[{{$student->id}}]" id="midterm" value="{{$student->midterm}}" size=1 oninput="change_midterm(this.value, {{$student->id}}, {{$student->idno}})"
                                @if($student->midterm_status == 0 && $close->midterm == 0)
                                
                                @elseif($student->midterm_status == 1 && $close->midterm >= 0)
                                readonly='' style="color:green"
                                @elseif($student->midterm_status == 0 && $close->midterm == 1)
                                readonly=''
                                @elseif($student->midterm_status == 2 && $close->midterm >= 0)
                                readonly='' style="color:blue"
                                @endif
                            ></td>
                            <td><input class='grade' type="text" name="finals[{{$student->id}}]" id="finals" value="{{$student->finals}}" size=1 oninput="change_finals(this.value, {{$student->id}}, {{$student->idno}})"
                                 @if($student->finals_status == 0 && $close->finals == 0)
                                
                                @elseif($student->finals_status == 1 && $close->finals >= 0)
                                readonly='' style="color:green"
                                @elseif($student->finals_status == 0 && $close->finals == 1)
                                readonly=''
                                @elseif($student->finals_status == 2 && $close->finals >= 0)
                                readonly='' style="color:blue"
                                @endif      
                            ></td>
                            <td><input class='grade' type="text" name="grade_point[{{$student->id}}]" id="grade_point" value="{{$student->grade_point}}" size=1 oninput="change_grade_point(this.value, {{$student->id}}, {{$student->idno}})"
                                @if($student->grade_point_status == 0 && $close->grade_point == 0)
                                
                                @elseif($student->grade_point_status == 1 && $close->grade_point >= 0)
                                readonly='' style="color:green"
                                @elseif($student->grade_point_status == 0 && $close->grade_point == 1)
                                readonly=''
                                @elseif($student->grade_point_status == 2 && $close->grade_point >= 0)
                                readonly='' style="color:blue"
                                @endif    
                            ></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
    @endforeach
    <div class="col-sm-2">
        <a href='{{url('/')}}'><div class="btn btn-warning col-sm-12">Return Dashboard</div></a>
    </div>
    <div class="col-sm-2">
        <a href='{{url('college_instructor', array('print_list', $schedule_id))}}' target="_blank"><div class="btn btn-info col-sm-12">Print Class List</div></a>
    </div>
    <div class="col-sm-2">
        <a href='{{url('college_instructor', array('print_grade', $schedule_id))}}' target="_blank"><div class="btn btn-info col-sm-12">Print Grade Record</div></a>
    </div>
    
    @if ($close->midterm != 1 || $close->finals != 1 || $close->grade_point != 1)
    <div class="col-sm-2">
        <a href='{{url('college_instructor', array('grades', $schedule_id))}}'><div class="btn btn-primary col-sm-12">Save</div></a>
    </div>
    <div class="col-sm-4">
        <input type='submit' onclick="if (confirm('Do you really want to save and submit grades?'))
                    return true;
                else
                    return false;" class='btn btn-success col-sm-12' value="Save & Submit">
    </div>
    @else <div class="col-sm-6"></div>
        @endif
</form>
@endsection
@section('footerscript')  

<script>
    function change_midterm(grade, grade_id, idno) {
        array = {};
        array['grade'] = grade;
        array['grade_id'] = grade_id;
        array['idno'] = idno;
        $.ajax({
            type: "GET",
            url: "/ajax/college_instructor/grades/change_midterm/" + idno,
            data: array,
            success: function () {
            }
        });
    }
    function change_finals(grade, grade_id, idno) {
        array = {};
        array['grade'] = grade;
        array['grade_id'] = grade_id;
        array['idno'] = idno;
        $.ajax({
            type: "GET",
            url: "/ajax/college_instructor/grades/change_finals/" + idno,
            data: array,
            success: function () {
            }
        });
    }
    function change_grade_point(grade, grade_id, idno) {
        array = {};
        array['grade'] = grade;
        array['grade_id'] = grade_id;
        array['idno'] = idno;
        $.ajax({
            type: "GET",
            url: "/ajax/college_instructor/grades/change_grade_point/" + idno,
            data: array,
            success: function () {
            }
        });
    }

    $(document).ready(function () {
        $(".grade").on('keypress', function (e) {
            var theEvent = e || window.event;
            var key = theEvent.keyCode || theEvent.which;
            if ((key < 48 || key > 57) && !(key == 8 || key == 9 || key == 13 || key == 37 || key == 39 || key == 46)) {
                theEvent.returnValue = false;
                if (theEvent.preventDefault)
                    theEvent.preventDefault();
            }
        });

    });
</script>
@endsection
