<?php $i=0 ?>
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
        Assigning of Schedules
        <small>A.Y. {{$advising_school_year->school_year}} - {{$advising_school_year->school_year+1}} {{$advising_school_year->period}}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="#"> Schedule</a></li>
        <li class="active"><a href="{{ url ('/registrar_college', array('advising','assigning_of_schedules', $idno))}}"> Assign Schedule</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<section class="content">
    <div class="row">
        <div class="col-sm-12">
            @if (Session::has('message'))
            <div class="alert alert-success">{{ Session::get('message') }}</div>
        @endif
            @if (Session::has('danger'))
            <div class="alert alert-danger">{{ Session::get('danger') }}</div>
        @endif
            <div class="box">
                <div class="box-header">
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <table class='table'>
                        <thead>
                            <tr>
                                <th>Course Code</th>
                                <th>Course Name</th>
                                <th>Schedule</th>
                                <th>Room</th>
                                <th>Instructor</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(count($grades)>0)
                            @foreach ($grades as $grade)
                            <?php $i=$i+1; ?>
                            <?php $schedule_ids = \App\CourseOffering::distinct()->where('course_code', $grade->course_code)->where('school_year', $advising_school_year->school_year)->where('period', $advising_school_year->period)->where('schedule_id', '!=', null)->get(['schedule_id']); ?>
                            <tr>
                                <td>{{$grade->course_code}}</td>
                                <td>{{$grade->course_name}}</td>
                                <?php $course = \App\CourseOffering::where('id', $grade->course_offering_id)->first(); ?>
                                <td colspan="3">
                                    @if(count($schedule_ids)>0)
                                    <select id='schedule{{$i}}' class='form form-control'>
                                        <option>Select Available Schedule</option>
                                        <option value="dna">Do not Assign</option>
                                        @foreach ($schedule_ids as $schedule_id)
                                        
                                        <?php $get_student=0; ?>
                                        <?php $cofferings = \App\CourseOffering::where('schedule_id', $schedule_id->schedule_id)->get(); ?>
                                        @foreach ($cofferings as $coffering)
                                        <?php $get_number = \App\GradeCollege::where('id', $coffering->id)->get(); ?>
                                            <?php $get_student = $get_student + count($get_number); ?>
                                        @endforeach
                                        
                                        @if(count($course)>0)
                                        <?php $grade_schedule_id = \App\CourseOffering::where('id', $grade->course_offering_id)->first();?>
                                        <option value='{{$schedule_id->schedule_id}}' @if($schedule_id->schedule_id == $grade_schedule_id->schedule_id) selected='' @endif>
                                        @else
                                        <option value='{{$schedule_id->schedule_id}}'>
                                        @endif
                                        [{{$get_student}}]
                                            <?php
                                            $schedule2s = \App\ScheduleCollege::distinct()->where('schedule_id', $schedule_id->schedule_id)->get(['time_start', 'time_end', 'room']);
                                            ?>
                                            @foreach ($schedule2s as $schedule2)
                                            <?php
                                            $days = \App\ScheduleCollege::where('schedule_id', $schedule_id->schedule_id)->where('time_start', $schedule2->time_start)->where('time_end', $schedule2->time_end)->where('room', $schedule2->room)->get(['day']);
                                            ?>
                                            <!--                @foreach ($days as $day){{$day->day}}@endforeach {{$schedule2->time}} <br>-->
                                            @foreach ($days as $day){{$day->day}}@endforeach 
                                            <?php $is_tba = \App\ScheduleCollege::where('schedule_id', $schedule_id->schedule_id)->first()->is_tba; ?>
                                            @if ($is_tba == 0)
                                            {{date('g:i A', strtotime($schedule2->time_start))}} - {{date('g:i A', strtotime($schedule2->time_end))}}<br>
                                            @else

                                            @endif
                                            @endforeach
                                            <?php
                                            $schedule3s = \App\ScheduleCollege::distinct()->where('schedule_id', $schedule_id->schedule_id)->get(['time_start', 'time_end', 'room']);
                                            ?>
                                            @foreach ($schedule3s as $schedule3)
                                            {{$schedule3->room}}<br>
                                            @endforeach
                                            <?php
                                            $schedule_instructor = \App\ScheduleCollege::distinct()->where('schedule_id', $schedule_id->schedule_id)->get(['instructor_id']);

                                            foreach ($schedule_instructor as $get) {
                                                if ($get->instructor_id != NULL) {
                                                    $instructor = \App\User::where('idno', $get->instructor_id)->first();
                                                    echo "$instructor->firstname $instructor->lastname $instructor->extensionname";
                                                } else {
                                                    echo "";
                                                }
                                            }
                                            ?>

                                        </option>
                                        @endforeach
                                    </select>
                                    @else
                                    No Schedule Available!!!
                                    @endif
                                </td>
                                <td><button class='btn btn-primary' onclick='get_sections(schedule{{$i}}.value,"{{$grade->id}}")' data-toggle="modal" data-target="#show_sections">Sections</td>
                            </tr>
                            @endforeach
                            @else
                            <tr>
                                <td>No Courses Loaded!!!</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade" id="show_sections">

</div>

@endsection
@section('footerscript')
<script>
    function get_sections(schedule_id, course_id){
        array = {};
        array['schedule_id'] = schedule_id;
        array['course_id'] = course_id;
        array['idno'] = "{{$idno}}";
        
        $.ajax({
        type: "GET",
            url: "/ajax/registrar_college/advising/get_section",
            data: array,
            success: function (data) {
            $('#show_sections').html(data);
            }
        });
    }
</script>
@endsection