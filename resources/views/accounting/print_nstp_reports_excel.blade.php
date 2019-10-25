<?php
$total_amount = 0;

function getTF($level, $lec) {
    $per_unit = \App\CtrCollegeTuitionFee::where('level', $level)->first()->per_unit;
    $amount = $per_unit * ($lec / 2);
    return $amount;
}
?>   
<table>
    @foreach ($levels as $level)
    <?php
    $students = \App\GradeCollege::
            join('college_levels', 'college_levels.idno', '=', 'grade_colleges.idno')
            ->whereRaw('(college_levels.status > 2)')
            ->where('college_levels.level', $level)
            ->where('college_levels.school_year', $request->school_year)
            ->where('college_levels.period', $request->period)
            ->where('grade_colleges.school_year', $request->school_year)
            ->where('grade_colleges.period', $request->period)
            ->where('grade_colleges.course_code', 'like', '%NSTP%')
            ->join('users', 'users.idno', '=', 'grade_colleges.idno')
            ->orderBy('users.lastname', 'asc')
            ->get();
    $totalstudents = \App\GradeCollege::
            join('college_levels', 'college_levels.idno', '=', 'grade_colleges.idno')
            ->whereRaw('(college_levels.status > 2)')
            ->where('college_levels.school_year', $request->school_year)
            ->where('college_levels.period', $request->period)
            ->where('grade_colleges.school_year', $request->school_year)
            ->where('grade_colleges.period', $request->period)
            ->where('grade_colleges.course_code', 'like', '%NSTP%')
            ->join('users', 'users.idno', '=', 'grade_colleges.idno')
            ->orderBy('users.lastname', 'asc')
            ->get();

    $number = 1;
    ?>
    @if(count($students)>0)
    <?php $sub_amount = 0; ?>
    <tr><td colspan="8"><strong>{{$level}}</strong></td></tr>
    <tr>
        <td>#</td>
        <td>ID Number</td>
        <td>Name</td>
        <td>Level</td>
        <td>Status</td>
        <td>Course Code</td>
        <td>Units</td>
        <td>Amount</td>
    </tr>
    @foreach($students as $student)
    <?php $user = \App\User::where('idno', $student->idno)->first(); ?>
    <?php $status = \App\CollegeLevel::where('idno', $student->idno)->where('school_year', $request->school_year)->where('period', $request->period)->first(); ?>
    <?php $info = \App\StudentInfo::where('idno', $student->idno)->first(); ?>
    <tr>
        <td>{{$number++}}.</td>
        <td>{{strtoupper($student->idno)}}</td>
        <td>{{strtoupper($user->lastname)}}, {{strtoupper($user->firstname)}} {{strtoupper($user->middlename)}}</td>
        <td>{{$status->level}}</td>
        <td>@if($status->status == 3) Enrolled @elseif($status->status == 4) Dropped/Withdrawn @endif</td>
        <td>{{$student->course_code}}</td>
        <td align="center">{{$student->lec}}</td>
        <td align="right"><?php $amount = getTF($status->level, $student->lec) ?> {{$amount}}</td>
        <?php $total_amount = $total_amount + $amount; ?>
        <?php $sub_amount = $sub_amount + $amount; ?>
    </tr>
    @endforeach
    <tr>
        <td colspan = 7 align="right">Subtotal</td>
        <td align =right>{{$sub_amount}}</td>
    </tr>
    @endif
    @endforeach
    <tr>
        <td></td>
        <td><strong>Total Number of Students: {{count($totalstudents)}}</strong></td>
        <td colspan = 5 align="right"><strong>Grand Total</strong></td>
        <td align =right>{{$total_amount}}</td>
    </tr>
</table>
