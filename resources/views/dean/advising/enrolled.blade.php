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
$grade_colleges = \App\GradeCollege::where('idno', $idno)->where('school_year', $school_year->school_year)->where('period', $school_year->period)->get();
$units = 0;
?>

    <?php
    if(Auth::user()->accesslevel == env('DEAN')){
    $layout = "layouts.appdean_college";
    } else {
    $layout = "layouts.appreg_college";
    }
?>

@extends($layout)
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
        Advising
        <small>A.Y. {{$school_year->school_year}} - {{$school_year->school_year+1}} {{$school_year->period}}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="#"></i> Advising</a></li>
        <li class="active"><a href="{{ url ('/dean', array('advising',$idno))}}"></i> {{$idno}}</a></li>
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
                        @if($status->status=="3")
                            @if($status->is_new == "0")
                                <li><a href="#">Enrollment Status <span class="pull-right">Enrolled</span></a></li>
                                <li><a href="#">Status <span class="pull-right">Old Student</span></a></li>
                                <li><a href="#">Program <span class="pull-right">{{$status->program_code}}</span></a></li>
                                <li><a href="#">Level <span class="pull-right">{{$status->level}}</span></a></li>
                                <!--<li><a href="#">Section <span class="pull-right">{{$status->section}}</span></a></li>-->
                            @else    
                                <li><a href="#">Enrollment Status <span class="pull-right">Enrolled</span></a></li>
                                <li><a href="#">Status <span class="pull-right">New Student</span></a></li>
                                <li><a href="#">Program <span class="pull-right">{{$status->program_code}}</span></a></li>
                                <li><a href="#">Level <span class="pull-right">{{$status->level}}</span></a></li>
                            @endif
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Courses Enrolled</h3>
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
                                <th>Schedule</th>
                                <th>Instructor</th>
                                <th>Units</th>
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
                                    [@foreach ($days as $day){{$day->day}}@endforeach {{date('g:iA', strtotime($schedule2->time_start))}}-{{date('g:iA', strtotime($schedule2->time_end))}}]<br>
                                    @endforeach
                                </td>
                                <?php
                                $offering_id = \App\CourseOffering::find($grade_college->course_offering_id);
                                $instructor = \App\User::where('idno', $offering_id->instructor_id)->first();

                                if (count($instructor) > 0) {
                                    $data = $instructor->firstname . " " . $instructor->lastname . " " . $instructor->extensionname;
                                } else {
                                    $data = "";
                                }
                                ?>
                                <td >{{$data}}</td>
                @else
                <td>TBA</td>
                <td>TBA</td>
                @endif
                                <td>{{$grade_college->lec+$grade_college->lab}}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="4"><strong>Total Units</strong></td>
                                <td><strong>{{$units}}</strong></td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('footerscript')
@endsection