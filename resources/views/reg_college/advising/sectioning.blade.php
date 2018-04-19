<?php $course_name = \App\GradeCollege::distinct()->where('course_code', $course_code)->first(['course_name'])->course_name; ?>
<?php $student_count = \App\GradeCollege::where('course_code', $course_code)->where('school_year', $advising_school_year->school_year)->where('period', $advising_school_year->period)->where('is_advising', 1)->get(); ?>
<?php $lec = \App\GradeCollege::distinct()->where('course_code', $course_code)->first(['lec'])->lec; ?>
<?php $lab = \App\GradeCollege::distinct()->where('course_code', $course_code)->first(['lab'])->lab; ?>
<?php $details = \App\GradeCollege::where('course_code', $course_code)->where('school_year', $advising_school_year->school_year)->where('period', $advising_school_year->period)->where('is_advising', 1)->get(); ?>
<?php $student_lists = \App\GradeCollege::where('course_code', $course_code)->where('school_year', $advising_school_year->school_year)->where('period', $advising_school_year->period)->where('is_advising', 1)->get(); ?>

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
                <div class="box-header">
                    <h3 class="box-title">Sectioning</h3>
                </div>
                <div class="box-body">
                    <!--students list-->
                    <div class="col-sm-6" id='studentlist'>
                        <h4>Student List</h4>
                        <table class="table table-condensed">
                            <tr>
                                <th>No.</th>
                                <th>ID Number</th>
                                <th>Name</th>
                                <th>Program</th>
                                <th></th>
                            </tr>
                            <?php $counter = 1; ?>
                            @foreach ($student_lists as $student_list)
                            <?php $user = \App\User::where('idno', $student_list->idno)->first(); ?>
                            <?php $status = \App\Status::where('idno', $student_list->idno)->first(); ?>
                            <tr>
                                <td>{{$counter}} <?php $counter = $counter + 1; ?></td>
                                <td>{{$student_list->idno}}</td>
                                <td>{{$user->lastname}}, {{$user->firstname}}</td>
                                <td>{{$status->program_code}}</td>
                                <td><button onclick='addtosection("{{$student_list->idno}}","{{$course_code}}", schedule_id.value, section.value)'><i class="fa fa-arrow-right"></i></button></td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                    <!--assigned section-->
                    <div class="col-sm-6" id='student_schedule_list'>
                        <h4>Schedule</h4>
                        <div class="col-sm-6">
                        <select id='schedule_id' class='form form-control select2' onchange="getsection(this.value);">
                            <option value="">Select Schedule</option>
                            <?php $enrollment_school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first();?>
                            <?php $sections = \App\CourseOffering::distinct()->where('course_code', $course_code)->where('school_year', $enrollment_school_year->school_year)->where('period', $enrollment_school_year->period)->get(['schedule_id']); ?>
                            @foreach ($sections as $available_class)
                            <option value="{{$available_class->schedule_id}}">
                        
                                    <?php
                                    $schedule2s = \App\ScheduleCollege::distinct()->where('schedule_id', $available_class->schedule_id)->get(['time_start', 'time_end', 'room']);
                                    ?>
                                    @foreach ($schedule2s as $schedule2)
                                    <?php
                                    $days = \App\ScheduleCollege::where('schedule_id', $available_class->schedule_id)->where('time_start', $schedule2->time_start)->where('time_end', $schedule2->time_end)->where('room', $schedule2->room)->get(['day']);
                                    ?>
                                    <!--                @foreach ($days as $day){{$day->day}}@endforeach {{$schedule2->time}} <br>-->
                                    @foreach ($days as $day){{$day->day}}@endforeach {{date('g:i A', strtotime($schedule2->time_start))}} - {{date('g:i A', strtotime($schedule2->time_end))}}<br>
                                    @endforeach
                                    <?php
                                    $schedule3s = \App\ScheduleCollege::distinct()->where('schedule_id', $available_class->schedule_id)->get(['time_start', 'time_end', 'room']);
                                    ?>
                                    @foreach ($schedule3s as $schedule3)
                                    {{$schedule3->room}}<br>
                                    @endforeach
                            </option>
                            @endforeach
                        </select>
                        </div>
                        
                        <div class="col-sm-6">
                        <select id='section' class='form form-control select2' onchange="get_schedule_student_list('{{$course_code}}', schedule_id.value, this.value)">
                            <option value="">Select Section</option>
                        </select>
                        </div>
                        <div class='col-sm-12' id='student_list'>
                            Student List
                        </div>
                    </div>
                </div>
            </div>
        </div>
</section>

@endsection
@section('footerscript')
<script>
    function addtosection(idno, course_code, schedule_id, section) {
        array = {};
        array['course_code'] = course_code;
        array['idno'] = idno;
        array['schedule_id'] = schedule_id;
        array['section'] = section;
        $.ajax({
            type: "GET",
            url: "/ajax/registrar_college/advising/addtosection",
            data: array,
            success: function (data) {
                get_schedule_student_list(course_code, schedule_id, section);
            }

        });
    }
    function getsection(schedule_id){
        array = {};
        array['schedule_id'] = schedule_id;
        $.ajax({
            type: "GET",
            url: "/ajax/registrar_college/advising/get_section",
            data: array,
            success: function (data) {
                $('#section').html(data);
            }

        });
    }
    function get_schedule_student_list(course_code,schedule_id,section){
        array = {};
        array['schedule_id'] = schedule_id;
        array['course_code'] = course_code;
        array['section'] = section;
        $.ajax({
            type: "GET",
            url: "/ajax/registrar_college/advising/getschedulestudentlist",
            data: array,
            success: function (data) {
                $('#student_list').html(data);
            }

        });
    }
</script>
@endsection