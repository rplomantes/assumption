<?php
$school_year = \App\CtrAdvisingSchoolYear::where('academic_type', 'College')->first();
?>
<?php
$courses = \App\GradeCollege::where('idno', $idno)->where('school_year', $school_year->school_year)->where('period', $school_year->period)->get();
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
        Student Already Assessed
        <small>A.Y. {{$school_year->school_year}} - {{$school_year->school_year+1}} {{$school_year->period}}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-home"></i> Home</a></li>
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
                    <div class='table-responsive'>
                @if(count($grade_colleges)>0)
                <table class="table table-hover table-condensed"><thead><tr><th>Code</th><th>Course</th><th>Schedule/Room</th><th>Instructor</th><th>Units</th></tr></thead><tbody>
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
                                [@foreach ($days as $day){{$day->day}}@endforeach {{date('g:i A', strtotime($schedule2->time_start))}} - {{date('g:i A', strtotime($schedule2->time_end))}}]<br>
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
                            <td>{{$grade_college->lec+$grade_college->lab}}</td>
                       </tr>
                        @endforeach
                        <tr><td colspan="4"><strong>Total Units</strong></td><td><strong>{{$units}}</strong></td></tr>
                    </tbody></table>
                @else
                <div class="alert alert-danger">No Course Selected Yet!!</div>
                @endif
                    </div>
            </div>
        </div>
        <div class='col-sm-12'>
            <a href='{{url('/')}}'><button class='btn btn-warning col-sm-12'><span class='fa fa-home'></span> RETURN HOME</button></a>
        </div>
    </div>
</div>
@endsection
@section("footerscript")
@endsection