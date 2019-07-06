<style>
    img {
        display: block;
        max-width:230px;
        max-height:95px;
        width: auto;
        height: auto;
    }
    #schoolname{
        font-size: 18pt; 
        font-weight: bolder;
    }
    .table, .th, .td {
        border: 1px solid black;
        border-collapse: collapse;
        font: 9pt;
    }
    .table2 {
        border: 1px solid black transparent;
        border-collapse: collapse;
        font: 9pt;
    }
    .underline {
        border-top: 1px solid transparent;
        border-left: 1px solid transparent;
        border-right: 1px solid transparent;
    }
    .top-line {
        border-bottom: 1px solid transparent;
        border-left: 1px solid transparent;
        border-right: 1px solid transparent;
        text-align: center;
    }
    .no-border {
        border-top: 1px solid transparent;
        border-left: 1px solid transparent;
        border-right: 1px solid transparent;
        border-bottom: 1px solid transparent;
    }

</style>
<div>   <?php $sy = \App\CtrGradeSchoolYear::where('academic_type', 'College')->first(); ?> 
    <div style='float: left; margin-left: 150px;'><img src="{{public_path('/images/assumption-logo.png')}}"></div>
    <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><br><b>CLASS LIST</b><br><b>A.Y. {{$sy->school_year}} - {{$sy->school_year + 1}}, {{$sy->period}}</b></div><br>
</div>
<div>
<div style='margin-top:130px'>
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
$school_year = \App\CtrGradeSchoolYear::where('academic_type', 'College')->first()->school_year;
$period = \App\CtrGradeSchoolYear::where('academic_type', 'College')->first()->period;
$students = \App\GradeCollege::whereRaw('('.$raw.')')->join('college_levels', 'college_levels.idno', '=', 'grade_colleges.idno')->join('users', 'users.idno', '=', 'grade_colleges.idno')->where('college_levels.status', 3)->where('college_levels.school_year', $school_year)->where('college_levels.period', $period)->select('users.idno', 'users.firstname', 'users.lastname', 'grade_colleges.id', 'grade_colleges.midterm', 'grade_colleges.finals', 'grade_colleges.midterm_absences', 'grade_colleges.finals_absences', 'grade_colleges.grade_point', 'grade_colleges.is_lock', 'grade_colleges.midterm_status', 'grade_colleges.finals_status', 'grade_colleges.grade_point_status')->orderBy('users.lastname')->get();
$instructor = \App\User::where('idno', Auth::user()->idno)->first();
?>

<table width="100%">
    <tr>
        <td width="10%">Course:</td><td style="border-bottom:1px solid black">{{$course_code}} - {{$course_name}}</td>
    </tr>
    <tr>
        <td>Instructor:</td><td style="border-bottom:1px solid black">{{$instructor->firstname}} {{$instructor->lastname}} {{$instructor->extensionname}}</td>
    </tr>
    <tr>
        <td>Schedule:</td>
        <td style="border-bottom:1px solid black">
            
            <?php $is_tba = \App\ScheduleCollege::where('schedule_id', $schedule_id)->first()->is_tba; ?>

            @if($is_tba == 0)
                <?php
                $schedule3s = \App\ScheduleCollege::distinct()->where('schedule_id', $schedule_id)->get(['time_start', 'time_end', 'room']);
                ?>   
                @foreach ($schedule3s as $schedule3)
                {{$schedule3->room}}
                @endforeach
                <?php
                $schedule2s = \App\ScheduleCollege::distinct()->where('schedule_id', $schedule_id)->get(['time_start', 'time_end', 'room']);
                ?>
                @foreach ($schedule2s as $schedule2)
                <?php
                $days = \App\ScheduleCollege::where('schedule_id', $schedule_id)->where('time_start', $schedule2->time_start)->where('time_end', $schedule2->time_end)->where('room', $schedule2->room)->get(['day']);
                ?>
                <!--                @foreach ($days as $day){{$day->day}}@endforeach {{$schedule2->time}} <br>-->
                [@foreach ($days as $day){{$day->day}}@endforeach {{date('g:iA', strtotime($schedule2->time_start))}}-{{date('g:iA', strtotime($schedule2->time_end))}}]<br>
                @endforeach
            @else
            TBA
            @endif
            
        </td>
    </tr>
</table>

@if (count($students)>0)

    Section: {{$allsection}}
    <table class='table' border="1" width="100%" cellspacing='1' cellpadding='1'>
        <thead>
            <tr>
                <th width="3%"><div align="center">#</div></th>
                <th width="10%">ID number</th>
                <th width="50%">Name</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
            <tr>
                <td><div align="right">{{$number}}.<?php $number = $number + 1; ?></div></td>
                <td>{{$student->idno}}</td>
                <td>{{$student->getFullNameAttribute()}}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @endforeach
        </tbody>
    </table>
@endif
</div>
</div>