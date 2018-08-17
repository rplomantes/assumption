<?php $number = 1;
$raw = "";
$allsection = ""; ?>
@foreach ($courses_id as $key => $course_id)
<?php
if ($key == 0) {
    $raw = $raw . " course_offering_id = " . $course_id->id;
    $allsection = $allsection . "$course_id->section_name";
} else {
    $raw = $raw . " or course_offering_id = " . $course_id->id;
    $allsection = $allsection . "/$course_id->section_name";
}
?>
@endforeach
<?php
$students = \App\GradeCollege::whereRaw('(' . $raw . ')')->join('college_levels', 'college_levels.idno', '=', 'grade_colleges.idno')->join('users', 'users.idno', '=', 'grade_colleges.idno')->where('college_levels.status', 3)->where('college_levels.school_year', $school_year)->where('college_levels.period', $period)->select('users.idno', 'users.firstname', 'users.lastname', 'grade_colleges.id', 'grade_colleges.midterm', 'grade_colleges.finals', 'grade_colleges.midterm_absences', 'grade_colleges.finals_absences', 'grade_colleges.grade_point', 'grade_colleges.is_lock', 'grade_colleges.midterm_status', 'grade_colleges.finals_status', 'grade_colleges.grade_point_status')->orderBy('users.lastname')->get();
?>
@if (count($students)>0)

<div class="box-header">
    <h3 class="box-title">Section: {{$allsection}}</h3>
            <a onclick="print_per_instructor(instructor_id.value, school_year.value, period.value,course_code.value, schedule_id.value)"><button class='btn btn-default pull-right'><span class='fa fa-print'></span> Print</button></a>
</div>
<div class="box-body">
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th width="3%">#</th>
                <th width="8%">Student ID</th>
                <th>Student Name</th>
                <th>Level</th>
                <th>Program</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
<?php $status = \App\CollegeLevel::where('idno', $student->idno)->where('school_year', $school_year)->where('period', $period)->first(); ?>
            <tr>
                <td>{{$number}}<?php $number = $number + 1; ?></td>
                <td>{{$student->idno}}</td>
                <td>{{$student->lastname}}, {{$student->firstname}}</td>
                <td>{{$status->level}}</td>
                <td>{{$status->program_code}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@endif