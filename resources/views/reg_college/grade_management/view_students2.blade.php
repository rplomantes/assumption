<?php
$close = \App\CtrCollegeGrading::where('academic_type', "College")->first();
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
$students = \App\GradeCollege::whereRaw('('.$raw.')')->join('college_levels','college_levels.idno', '=', 'grade_colleges.idno')->join('users', 'users.idno', '=', 'grade_colleges.idno')->where('college_levels.status', 3)->where('college_levels.school_year', $school_year)->where('college_levels.period', $period)->select('users.idno', 'users.firstname', 'users.lastname', 'grade_colleges.id', 'grade_colleges.midterm', 'grade_colleges.finals', 'grade_colleges.grade_point', 'grade_colleges.is_lock', 'grade_colleges.midterm_status', 'grade_colleges.finals_status', 'grade_colleges.grade_point_status')->orderBy('users.lastname')->get();
$checkstatus = \App\GradeCollege::whereRaw('('.$raw.')')->join('college_levels', 'college_levels.idno', '=', 'grade_colleges.idno')->join('users', 'users.idno', '=', 'grade_colleges.idno')->where('college_levels.status', 3)->where('college_levels.school_year', $school_year)->where('college_levels.period', $period)->select('users.idno', 'users.firstname', 'users.lastname', 'grade_colleges.id', 'grade_colleges.midterm', 'grade_colleges.finals', 'grade_colleges.midterm_absences', 'grade_colleges.finals_absences', 'grade_colleges.grade_point', 'grade_colleges.is_lock', 'grade_colleges.midterm_status', 'grade_colleges.finals_status', 'grade_colleges.grade_point_status')->where('grade_colleges.is_lock', 2)->get();
$checkstatus3 = \App\GradeCollege::whereRaw('('.$raw.')')->join('college_levels', 'college_levels.idno', '=', 'grade_colleges.idno')->join('users', 'users.idno', '=', 'grade_colleges.idno')->where('college_levels.status', 3)->where('college_levels.school_year', $school_year)->where('college_levels.period', $period)->select('users.idno', 'users.firstname', 'users.lastname', 'grade_colleges.id', 'grade_colleges.midterm', 'grade_colleges.finals', 'grade_colleges.midterm_absences', 'grade_colleges.finals_absences', 'grade_colleges.grade_point', 'grade_colleges.is_lock', 'grade_colleges.midterm_status', 'grade_colleges.finals_status', 'grade_colleges.grade_point_status')->where('grade_colleges.is_lock', 3)->get();
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
                <h3 class="box-title">Section: {{$allsection}}</h3>
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
                            <th>Lock/Unlock</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $student)
                        <tr>
                            <td>{{$number}}<?php $number = $number + 1; ?></td>
                            <td>{{$student->idno}}</td>
                            <td>{{$student->lastname}}, {{$student->firstname}}</td>
                            <td><input class='grade' type="text" name="midterm[{{$student->id}}]" id="midterm" value="{{$student->midterm}}" size=1 readonly=""></td>
                            <td><input class='grade' type="text" name="finals[{{$student->id}}]" id="finals" value="{{$student->finals}}" size=1 readonly=""></td>
                            <td>
                                @if($student->is_lock == 0)
                                <div class="btn btn-warning col-sm-12" onclick="lock({{$student->idno}}, schedule_id.value, {{$student->id}})">Lock</div>
                                @elseif ($student->is_lock == 1)
                                <div class="btn btn-danger col-sm-12" onclick="unlock({{$student->idno}}, schedule_id.value, {{$student->id}})">Unlock</div>
                                @elseif ($student->is_lock == 2)
                                <i class="label label-warning">For Submission to Records by the Instructor</i>
                                @else
                                <i class="label label-danger">Grade Submitted to the Records</i>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
@if (count($checkstatus3) == count($students))
@else    <div class="col-sm-12">
        <span onclick="if (confirm('Do you really want to approve all grades?'))
                    return approveall(schedule_id.value);
                else
                    return false;" class='btn btn-success col-sm-12' >Lock all and Approve</span>
    </div>
@endif
</form>