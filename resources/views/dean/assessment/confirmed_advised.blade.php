<?php
$school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first();
?>
<?php
$courses = \App\GradeCollege::where('idno', $idno)->where('school_year', $school_year->school_year)->where('period', $school_year->period)->get();
?>
@extends("layouts.appdean_college")
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
        Courses Assessed
        <small>A.Y. {{$school_year->school_year}} - {{$school_year->school_year+1}} {{$school_year->period}}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Assessment</li>
        <li class="active">{{$idno}}</li>
    </ol>
</section>
@endsection
<?php
$user = \App\User::where('idno', $idno)->first();
?>
@section("maincontent")
<div class="row">
    <div class="col-sm-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">{{$user->firstname}} {{$user->lastname}}</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class='box-body'>
                <?php
                $grade_colleges = \App\GradeCollege::where('idno', $idno)->where('school_year', $school_year->school_year)->where('period', $school_year->period)->get();
                $units = 0;
                ?>
                @if(count($grade_colleges)>0)
                <table class="table table-hover table-condensed"><thead><tr><th>Code</th><th>Course</th><th>Units</th><th>Schedule/Room</th><th>Instructor</th></tr></thead><tbody>
                        @foreach($grade_colleges as $grade_college)
                        <?php
                        $units = $units + $grade_college->lec + $grade_college->lab;
                        ?>
                        <tr>
                            <td>{{$grade_college->course_code}}</td>
                            <td>{{$grade_college->course_name}}</td>
                            <td>{{$grade_college->lec+$grade_college->lab}}</td>
                            <td>
                                <?php
                                $schedule3s = \App\ScheduleCollege::distinct()->where('course_offering_id', $grade_college->course_offering_id)->get(['time_start', 'time_end', 'room']);
                                ?>   
                                @foreach ($schedule3s as $schedule3)
                                {{$schedule3->room}}
                                @endforeach
                                <?php
                                $schedule2s = \App\ScheduleCollege::distinct()->where('course_offering_id', $grade_college->course_offering_id)->get(['time_start', 'time_end', 'room']);
                                ?>
                                @foreach ($schedule2s as $schedule2)
                                <?php
                                $days = \App\ScheduleCollege::where('course_offering_id', $grade_college->course_offering_id)->where('time_start', $schedule2->time_start)->where('time_end', $schedule2->time_end)->where('room', $schedule2->room)->get(['day']);
                                ?>
                                <!--                @foreach ($days as $day){{$day->day}}@endforeach {{$schedule2->time}} <br>-->
                                [@foreach ($days as $day){{$day->day}}@endforeach {{date('g:i A', strtotime($schedule2->time_start))}} - {{date('g:i A', strtotime($schedule2->time_end))}}]<br>
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
                            <td>{{$data}}</td>
                        </tr>
                        @endforeach
                        <tr><td colspan="2"><strong>Total Units</strong></td><td><strong>{{$units}}</strong></td></tr>
                    </tbody></table>
                @else
                <div class="alert alert-danger">No Course Selected Yet!!</div>
                @endif
            </div>
        </div>
        <div class='col-sm-6'>
            <a href='{{url('/')}}'><button class='btn btn-warning col-sm-12'><span class='fa fa-home'></span> RETURN HOME</button></a>
        </div>
        <div class='col-sm-6'>
            <a href='{{url('dean', array('assessment','print_advising_slip',$idno))}}' target="_blank"><button class='btn btn-success col-sm-12'><span class='fa fa-print'></span> PRINT ADIVISING SLIP</button></a>
        </div>
    </div>
</div>
@endsection
@section("footerscript")
@endsection