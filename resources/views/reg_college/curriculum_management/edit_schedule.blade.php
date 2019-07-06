<?php
$school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first()->school_year;
$period = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first()->period;
$list_schedules = \App\ScheduleCollege::distinct()->where('school_year', $school_year)->where('period', $period)->get(['schedule_id']);
?>
@extends('layouts.appreg_college')
<!-- DataTables -->
<link rel="stylesheet" href="{{asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">
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
        Edit Schedule
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="#">Curriculum Management</a></li>
        <li class="active"><a href="{{ url ('/registrar_college', array('curriculum_management','edit_schedule'))}}"> Edit Schedule</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<section class="content">
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                  <h3 class="box-title">Course Schedules</h3>
                </div>
                <div class="box-body">
                    <table id="list_schedules" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                              <th>Course</th>
                              <th>Schedule</th>
                              <th>Room</th>
                              <th>Instructor</th>
                              <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($list_schedules as $list_schedule)
                        <?php 
                        $course_code = \App\ScheduleCollege::where('schedule_id',$list_schedule->schedule_id)->first();
                        if ($course_code->instructor_id !=NULL){
                        $instructor_name = \App\User::where('idno', $course_code->instructor_id)->first();
                        }
                        ?>
                            <tr>
                                <td>{{$course_code->course_code}}</td>
                                <td>
                                    <?php
                                    $schedule2s = \App\ScheduleCollege::distinct()->where('school_year', $school_year)->where('period', $period)->where('schedule_id', $list_schedule->schedule_id)->get(['time_start', 'time_end', 'room']);
                                    ?>
                                    @foreach ($schedule2s as $schedule2)
                                    <?php
                                    $days = \App\ScheduleCollege::where('schedule_id', $list_schedule->schedule_id)->where('time_start', $schedule2->time_start)->where('time_end', $schedule2->time_end)->where('room', $schedule2->room)->get(['day']);
                                    ?>
                                    @foreach ($days as $day){{$day->day}}@endforeach
                                    <?php $is_tba = \App\ScheduleCollege::where('schedule_id', $list_schedule->schedule_id)->first()->is_tba; ?>
                                        @if ($is_tba == 0)
                                        {{date('g:i A', strtotime($schedule2->time_start))}} - {{date('g:i A', strtotime($schedule2->time_end))}}<br>
                                        @else
                                        
                                        @endif
                                    <!--{{$schedule2->day}} {{$schedule2->time_start}} - {{$schedule2->time_end}}<br>-->
                                    @endforeach
                                </td>
                                <td>
                                    <?php
                                    $schedule3s = \App\ScheduleCollege::distinct()->where('schedule_id', $list_schedule->schedule_id)->get(['time_start', 'time_end', 'room']);
                                    ?>
                                    @foreach ($schedule3s as $schedule3)
                                    {{$schedule3->room}}<br>
                                    @endforeach
                                </td>
                                @if($course_code->instructor_id!=NULL)
                                <td>{{$instructor_name->firstname}} {{$instructor_name->lastname}}</td>
                                @else
                                <td></td>
                                @endif
                                <td><button class="btn btn-primary"><span class="fa fa-pencil"></span></button></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('footerscript')

<!-- DataTables -->
<script src="{{asset('bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
<!-- page script -->
<script>
  $(function () {
    $('#list_schedules').DataTable()
  })
</script>
@endsection