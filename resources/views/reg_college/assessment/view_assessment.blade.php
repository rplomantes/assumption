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
        <small>A.Y. {{$school_year->school_year}} - {{$school_year->school_year+1}} {{$school_year->period}}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="#"></i> Assessment</a></li>
        <li class="active"><a href="{{ url ('/registrar_college', array('assessment',$idno))}}"></i> {{$idno}}</a></li>
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
                        <li><a href="#">Previous Section <span class="pull-right">{{$status->section}}</span></a></li>
                        @else
                        <li><a href="#">Status <span class="pull-right">New Student</span></a></li>
                        <li><a href="#">Program <span class="pull-right">{{$status->program_code}}</span></a></li>
                        <li><a href="#">Level <span class="pull-right">{{$status->level}}</span></a></li>
                        <li><a href="#">Section <span class="pull-right">{{$status->section}}</span></a></li>
                        @endif
                        @else    
                        <li><a href="#">Status <span class="pull-right">New Student</span></a></li>
                        <li><a href="#">Program <span class="pull-right">{{$status->program_code}}</span></a></li>
                        <li><a href="#">Level <span class="pull-right">{{$status->level}}</span></a></li>
                        <li><a href="#">Section <span class="pull-right">{{$status->section}}</span></a></li>
                        @endif
                    </ul>
                </div>
            </div>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Tuition Fee Quotations</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class='box-body'>
                    <div class='form-group'>
                        <div class='col-sm-12' id='type_account-form'>
                            <label>Type of Account</label>
                            <select class='form-control' id='type_account' name="type_account">
                                <option>Select type of Account</option>
                                <option value="Regular">Regular</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class='col-sm-12' id='plan-form'>
                            <label>Plan</label>
                            <select id="plan" name="plan" class='form-control'>
                                <option>Select Plan</option>
                                <option value='Cash'>Cash</option>
                                <option value='Quarterly'>Quarterly</option>
                                <option value='Monthly'>Monthly</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group" id="compute-form">
                        <div class="col-sm-12">
                            <button class="btn btn-primary col-sm-12"  onclick="get_assessed_payment(plan.value, type_account.value, '{{$user->idno}}')">Compute</button>
                        </div>
                    </div>
                    <div class="col-sm-12 box-body" id="display_result">

                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Courses Assessed</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class='box-body'>
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
    </div>
</section>

@endsection
@section('footerscript')
<script>
    $("#plan-form").hide();
    $("#compute-form").hide();
    $("#type_account-form").change(function () {
    $("#plan-form").fadeIn();
    });
    $("#plan-form").change(function () {
    $("#compute-form").fadeIn();
    });</script>
<script>
    function get_assessed_payment(plan, type_account, idno){
    array = {};
    array['plan'] = plan;
    array['type_of_account'] = type_account;
    array['program_code'] = "{{$status->program_code}}";
    array['level'] = "{{$status->level}}";
    array['idno'] = idno;
    $.ajax({
    type: "GET",
            url: "/ajax/registrar_college/assessment/get_assessed_payment",
            data: array,
            success: function (data) {
            $('#display_result').html(data);
            }

    });
    }
</script>
@endsection