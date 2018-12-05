<?php
$instructor_id = \App\ScheduleCollege::where('schedule_id', $schedule_id)->first()->instructor_id;
$close = \App\CtrCollegeGrading::where('academic_type', "College")->where('idno', $instructor_id)->first();
?>
<?php $number = 1; $raw = ""; $allsection=""; ?>
@foreach ($courses_id as $key => $course_id)
<?php 
if ($key == 0){
$raw = $raw. " course_offering_id = ".$course_id->id;
$allsection = $allsection. "$course_id->section_name";
} else {
$raw = $raw. " or course_offering_id = ".$course_id->id;
$allsection = $allsection. "/$course_id->section_name";
}
?>
@endforeach
<?php
$students = \App\GradeCollege::whereRaw('('.$raw.')')->join('college_levels','college_levels.idno', '=', 'grade_colleges.idno')->join('users', 'users.idno', '=', 'grade_colleges.idno')->where('college_levels.status', 3)->where('college_levels.school_year', $school_year)->where('college_levels.period', $period)->select('users.idno', 'users.firstname', 'users.lastname', 'grade_colleges.id', 'grade_colleges.midterm', 'grade_colleges.midterm_absences','grade_colleges.finals', 'grade_colleges.finals_absences','grade_colleges.grade_point', 'grade_colleges.is_lock', 'grade_colleges.midterm_status', 'grade_colleges.finals_status', 'grade_colleges.grade_point_status')->orderBy('users.lastname')->get();
$checkstatus_midterm1 = \App\GradeCollege::whereRaw('('.$raw.')')->join('college_levels', 'college_levels.idno', '=', 'grade_colleges.idno')->join('users', 'users.idno', '=', 'grade_colleges.idno')->where('college_levels.status', 3)->where('college_levels.school_year', $school_year)->where('college_levels.period', $period)->select('users.idno', 'users.firstname', 'users.lastname', 'grade_colleges.id', 'grade_colleges.midterm', 'grade_colleges.finals', 'grade_colleges.midterm_absences', 'grade_colleges.finals_absences', 'grade_colleges.grade_point', 'grade_colleges.is_lock', 'grade_colleges.midterm_status', 'grade_colleges.finals_status', 'grade_colleges.grade_point_status')->where('grade_colleges.midterm_status', 1)->get();
$checkstatus_midterm = \App\GradeCollege::whereRaw('('.$raw.')')->join('college_levels', 'college_levels.idno', '=', 'grade_colleges.idno')->join('users', 'users.idno', '=', 'grade_colleges.idno')->where('college_levels.status', 3)->where('college_levels.school_year', $school_year)->where('college_levels.period', $period)->select('users.idno', 'users.firstname', 'users.lastname', 'grade_colleges.id', 'grade_colleges.midterm', 'grade_colleges.finals', 'grade_colleges.midterm_absences', 'grade_colleges.finals_absences', 'grade_colleges.grade_point', 'grade_colleges.is_lock', 'grade_colleges.midterm_status', 'grade_colleges.finals_status', 'grade_colleges.grade_point_status')->where('grade_colleges.midterm_status', 2)->get();
$checkstatus_midterm3 = \App\GradeCollege::whereRaw('('.$raw.')')->join('college_levels', 'college_levels.idno', '=', 'grade_colleges.idno')->join('users', 'users.idno', '=', 'grade_colleges.idno')->where('college_levels.status', 3)->where('college_levels.school_year', $school_year)->where('college_levels.period', $period)->select('users.idno', 'users.firstname', 'users.lastname', 'grade_colleges.id', 'grade_colleges.midterm', 'grade_colleges.finals', 'grade_colleges.midterm_absences', 'grade_colleges.finals_absences', 'grade_colleges.grade_point', 'grade_colleges.is_lock', 'grade_colleges.midterm_status', 'grade_colleges.finals_status', 'grade_colleges.grade_point_status')->where('grade_colleges.midterm_status', 3)->get();

$checkstatus_finals1 = \App\GradeCollege::whereRaw('('.$raw.')')->join('college_levels', 'college_levels.idno', '=', 'grade_colleges.idno')->join('users', 'users.idno', '=', 'grade_colleges.idno')->where('college_levels.status', 3)->where('college_levels.school_year', $school_year)->where('college_levels.period', $period)->select('users.idno', 'users.firstname', 'users.lastname', 'grade_colleges.id', 'grade_colleges.midterm', 'grade_colleges.finals', 'grade_colleges.midterm_absences', 'grade_colleges.finals_absences', 'grade_colleges.grade_point', 'grade_colleges.is_lock', 'grade_colleges.midterm_status', 'grade_colleges.finals_status', 'grade_colleges.grade_point_status')->where('grade_colleges.finals_status', 1)->get();
$checkstatus_finals = \App\GradeCollege::whereRaw('('.$raw.')')->join('college_levels', 'college_levels.idno', '=', 'grade_colleges.idno')->join('users', 'users.idno', '=', 'grade_colleges.idno')->where('college_levels.status', 3)->where('college_levels.school_year', $school_year)->where('college_levels.period', $period)->select('users.idno', 'users.firstname', 'users.lastname', 'grade_colleges.id', 'grade_colleges.midterm', 'grade_colleges.finals', 'grade_colleges.midterm_absences', 'grade_colleges.finals_absences', 'grade_colleges.grade_point', 'grade_colleges.is_lock', 'grade_colleges.midterm_status', 'grade_colleges.finals_status', 'grade_colleges.grade_point_status')->where('grade_colleges.finals_status', 2)->get();
$checkstatus_finals3 = \App\GradeCollege::whereRaw('('.$raw.')')->join('college_levels', 'college_levels.idno', '=', 'grade_colleges.idno')->join('users', 'users.idno', '=', 'grade_colleges.idno')->where('college_levels.status', 3)->where('college_levels.school_year', $school_year)->where('college_levels.period', $period)->select('users.idno', 'users.firstname', 'users.lastname', 'grade_colleges.id', 'grade_colleges.midterm', 'grade_colleges.finals', 'grade_colleges.midterm_absences', 'grade_colleges.finals_absences', 'grade_colleges.grade_point', 'grade_colleges.is_lock', 'grade_colleges.midterm_status', 'grade_colleges.finals_status', 'grade_colleges.grade_point_status')->where('grade_colleges.finals_status', 3)->get();
?>
@if (count($students)>0)
<div class='col-sm-12'><div class='pull-right'><a target='_blank' href="{{url('registrar_college',array('print_grade_list', $schedule_id))}}"><button class='btn btn-primary'>Print Grade</button></a></div></div>
<form class="form form-horizontal" method="post" action="{{url('college_instructor', array('grades','save_submit'))}}">
    {{csrf_field()}}
    <input type="hidden" name="schedule_id" value="{{$schedule_id}}">
    <input type="hidden" name="midterm_status" value="{{$close->midterm}}">
    <input type="hidden" name="finals_status" value="{{$close->finals}}">
    <input type="hidden" name="grade_point_status" value="{{$close->grade_point}}">
    <div class="col-sm-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Section: {{$allsection}}</h3>
            </div>
            <div class="box-body">
                <table class="table table-bordered table-condensed">
                    <thead>
                        <tr>
                            <th width="3%">#</th>
                            <th width="8%">ID number</th>
                            <th>Name</th>
                            <th width="10%">Midterm Absences</th>
                            <th width="10%">Midterm</th>
                            <th>Status</th>
                            <th width="10%">Finals Absences</th>
                            <th width="10%">Finals</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        <tr>
                            <td>{{$number}}<?php $number = $number + 1; ?></td>
                            <td>{{$student->idno}}</td>
                            <td>{{$student->lastname}}, {{$student->firstname}}</td>
                            <td><input class='grade' type="text" value="{{$student->midterm_absences}}" size=3 readonly=""></td>
                            <td><input class='grade' type="text" name="midterm[{{$student->id}}]" id="midterm" value="{{$student->midterm}}" size=3 readonly=""></td>
                            @if(auth::user()->accesslevel == env('DEAN'))
                            <td>
                                @if($student->midterm_status == 0)
                                <i class="label label-info">Not yet submitted</i>
                                @elseif ($student->midterm_status == 1)
                                <div class="btn btn-danger col-sm-12" onclick="unlock_midterm('{{$student->idno}}', schedule_id.value, '{{$student->id}}')">Back this grade to Instructor</div>
                                @elseif ($student->midterm_status == 2)
                                <i class="label label-warning">For Submission to Records by the Instructor</i>
                                @else
                                <i class="label label-danger">Finalized</i>
                                @endif
                            </td>
                            @endif
                            @if(auth::user()->accesslevel == env('REG_COLLEGE'))
                            <td>
                                @if($student->midterm_status == 0)
                                <i class="label label-info">Not yet submitted</i>
                                @elseif ($student->midterm_status == 1)
                                <i class="label label-primary">For checking</i>
                                @elseif ($student->midterm_status == 2)
                                <i class="label label-warning">For Submission to Records by the Instructor</i>
                                @else
                                <i class="label label-danger">Finalized</i>
                                @endif
                            </td>
                            @endif
                            <td><input class='grade' type="text" value="{{$student->finals_absences}}" size=3 readonly=""></td>
                            <td><input class='grade' type="text" name="finals[{{$student->id}}]" id="finals" value="{{$student->finals}}" size=3 readonly=""></td>
                            @if(auth::user()->accesslevel == env('DEAN'))
                            <td>
                                @if($student->finals_status == 0)
                                <i class="label label-info">Not yet submitted</i>
                                <!--<div class="btn btn-warning col-sm-12" onclick="lock({{$student->idno}}, schedule_id.value, {{$student->id}})">Lock</div>-->
                                @elseif ($student->finals_status == 1)
                                <div class="btn btn-danger col-sm-12" onclick="unlock_finals('{{$student->idno}}', schedule_id.value, '{{$student->id}}')">Backs this grade to Instructor</div>
                                @elseif ($student->finals_status == 2)
                                <i class="label label-warning">For Submission to Records by the Instructor</i>
                                @else
                                <i class="label label-danger">Finalized</i>
                                @endif
                            </td>
                            @endif
                            @if(auth::user()->accesslevel == env('REG_COLLEGE'))
                            <td>
                                @if($student->finals_status == 0)
                                <i class="label label-info">Not yet submitted</i>
                                @elseif ($student->finals_status == 1)
                                <i class="label label-primary">For checking</i>
                                @elseif ($student->finals_status == 2)
                                <i class="label label-warning">For Submission to Records by the Instructor</i>
                                @else
                                <i class="label label-danger">Finalized</i>
                                @endif
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
    
@if (count($checkstatus_midterm3) == count($students))
@else    
@if(auth::user()->accesslevel == env('DEAN'))
@if (count($students) == count($checkstatus_midterm1))
    <div class="col-sm-6">
        <span onclick="if (confirm('Do you really want to approve MIDTERM grades?'))
                    return approveall_midterm(schedule_id.value);
                else
                    return false;" class='btn btn-success col-sm-12' >Approve MIDTERM grades</span>
    </div>
@endif
@if (count($students) == count($checkstatus_midterm))
    <div class="col-sm-6">
        <span onclick="if (confirm('Do you really want to cancel submission of midterm grades?'))
                    return cancelall_midterm(schedule_id.value);
                else
                    return false;" class='btn btn-danger col-sm-12' >Cancel submission of MIDTERM grades</span>
    </div>
@endif
@endif
@endif


@if (count($checkstatus_finals3) == count($students))
@else    
@if(auth::user()->accesslevel == env('DEAN'))
@if (count($students) == count($checkstatus_finals1))
    <div class="col-sm-6">
        <span onclick="if (confirm('Do you really want to approve FINAL grades?'))
                    return approveall_finals(schedule_id.value);
                else
                    return false;" class='btn btn-success col-sm-12' >Approve FINAL Grades</span>
    </div>
@endif
@if (count($students) == count($checkstatus_finals))
    <div class="col-sm-6">
        <span onclick="if (confirm('Do you really want to cancel submission of final grades?'))
                    return cancelall_finals(schedule_id.value);
                else
                    return false;" class='btn btn-danger col-sm-12' >Cancel submission of FINAL grades</span>
    </div>
@endif
@endif
@endif
</form>