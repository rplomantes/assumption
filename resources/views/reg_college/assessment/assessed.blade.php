<?php
$user = \App\User::where('idno', $idno)->first();
$status = \App\Status::where('idno', $idno)->first();
$student_info = \App\StudentInfo::where('idno', $idno)->first();
?>
<?php
//$school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first();
?>
<?php
$file_exist = 0;
if (file_exists(public_path("images/" . $user->idno . ".jpg"))) {
    $file_exist = 1;
}
?>
<?php
$ledger = \App\Ledger::SelectRaw('category,category_switch, sum(amount)as amount, sum(discount) as discount,
    sum(debit_memo) as debit_memo, sum(payment) as payment')->where('idno',$idno)->groupBy('category_switch','category')->orderBy('category_switch')->get();
$totaldm=0;
?>
@foreach ($ledger as $main)
<?php
$totaldm=$totaldm+$main->debit_memo;
?>
@endforeach
<?php
$grade_colleges = \App\GradeCollege::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->get();
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
        <small>A.Y. {{$school_year}} - {{$school_year+1}} {{$period}}</small>
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
            
            @if (Auth::user()->accesslevel==env("REG_COLLEGE"))
                @if ($status->status == env('ASSESSED'))
                    <div class="col-sm-12"><a role="button" class="col-md-12 btn btn-danger" href="{{url('/accounting',array('manual_marking',$user->idno))}}" onclick="return confirm('This process cannot be undone. Do you wish to continue?')"><b>Mark student as Enrolled</b></a></div>
                @endif
            @endif
        </div>
        <div class="col-md-8">
            
                            @if(count($grade_colleges)>0)
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Courses Assessed</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class='box-body'>
                    <div class='table-responsive'>
                    <table class='table table-striped'>
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Course Name</th>
                                <th>Units</th>
                                <th>Schedule</th>
                                <th>Instructor</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($grade_colleges as $grade_college)
                            <?php
                            $units = $units + $grade_college->lec + $grade_college->lab;
                            $offering_ids = \App\CourseOffering::find($grade_college->course_offering_id);
                            ?>
                            <tr>
                                <td>{{$grade_college->course_code}}</td>
                                <td>{{$grade_college->course_name}}</td>
                                <td>{{$grade_college->lec+$grade_college->lab}}</td>
                                    @if($grade_college->course_offering_id!=NULL)
                                <td>
                                    <?php
                                    $schedule3s = \App\ScheduleCollege::distinct()->where('schedule_id', $offering_ids->schedule_id)->get(['time_start', 'time_end', 'room']);
                                    ?>   
                                    @foreach ($schedule3s as $schedule3)
                                    {{$schedule3->room}}
                                    @endforeach
                                    <?php
                                    $schedule2s = \App\ScheduleCollege::distinct()->where('schedule_id', $offering_ids->schedule_id)->get(['time_start', 'time_end', 'room']);
                                    ?>
                                    @foreach ($schedule2s as $schedule2)
                                    <?php
                                    $days = \App\ScheduleCollege::where('schedule_id', $offering_ids->schedule_id)->where('time_start', $schedule2->time_start)->where('time_end', $schedule2->time_end)->where('room', $schedule2->room)->get(['day']);
                                    ?>
                                    <!--                @foreach ($days as $day){{$day->day}}@endforeach {{$schedule2->time}} <br>-->
                                    [@foreach ($days as $day){{$day->day}}@endforeach
                                    <?php $is_tba = \App\ScheduleCollege::where('schedule_id', $offering_ids->schedule_id)->first()->is_tba; ?>
                                        @if ($is_tba == 0)
                                        {{date('g:i A', strtotime($schedule2->time_start))}} - {{date('g:i A', strtotime($schedule2->time_end))}}<br>
                                        @else
                                        
                                        @endif
                                    @endforeach
                                </td>
                <td>
                <?php
                $offering_id = \App\CourseOffering::find($grade_college->course_offering_id);
                    $schedule_instructor = \App\ScheduleCollege::distinct()->where('schedule_id', $offering_id->schedule_id)->get(['instructor_id']);

                    foreach($schedule_instructor as $get){
                        if ($get->instructor_id != NULL){
                            $instructor = \App\User::where('idno', $get->instructor_id)->first();
                            echo "$instructor->firstname $instructor->lastname $instructor->extensionname";
                        } else {
                        echo "";
                        }
                    }
                ?>
                </td>
                @else
                <td>TBA</td>
                <td>TBA</td>
                @endif
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="2"><strong>Total Units</strong></td>
                                <td><strong>{{$units}}</strong></td>
                                <td colspan="2"></td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
            @if ($totaldm <= 0)
            <div class="col-sm-6">
                <a href="{{url('registrar_college', array('reassess',$school_year,$period,$idno))}}"  onclick="return confirm('Are you sure you want to re-assess?')"><button class="col-sm-12 btn btn-warning">RE-ASSESS</button>
            </div>
            @else
            <div class="col-sm-6">
                <a href="{{url('registrar_college', array('reassess_reservations',$idno, $status->levels_reference_id, $school_year, $period))}}"  onclick="return confirm('Are you sure you want to re-assess?')"><button class="col-sm-12 btn btn-warning">RE-ASSESS</button>
            </div>
            @endif
            <div class="col-sm-6">
                <a href='{{url('registrar_college', array('print_registration_form', $user->idno, $school_year, $period))}}' target="_blank"><button class="col-sm-12 btn btn-primary">PRINT REGISTRATION FORM</button></a>
            </div>
                            
                            @else
                                No Courses Assessed!
                            @endif
        </div>
    </div>
</section>

@endsection
@section('footerscript')
@endsection