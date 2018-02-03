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
<?php
$school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', "College")->first();
$user = \App\User::where('idno', $idno)->first();
$status = \App\Status::where('idno', $idno)->first();
?>
<div>    
    <div style='float: left; margin-left: 150px;'><img src="{{url('/images','assumption-logo.png')}}"></div>
    <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><br><b>ADVISING SLIP</b><br><small>A.Y. {{$school_year->school_year}} - {{$school_year->school_year+1}} {{$school_year->period}}</small></div>
</div>
<div>
    <table class='table' border="1" width="100%" cellspacing='0' cellpadding='0' style='margin-top: 145px;'>
        <tr>
            <td class='no-border td' width='10%'>ID Number:</td>
            <td class='underline td' colspan='3'>{{$user->idno}}</td>
        </tr>
        <tr>
            <td class='no-border td'>Name:</td>
            <td class='underline td' colspan='3'>{{$user->firstname}} {{$user->lastname}}</td>
        </tr>
        <tr>
            <td class='no-border td'>Program:</td>
            <td class='underline td' width='40%'>{{$status->program_name}}</td>
            <td class='no-border td' width='5%'>Level:</td>
            <td class='underline td'>{{$status->level}}</td>
        </tr>
    </table>
    <?php
    $grade_colleges = \App\GradeCollege::where('idno', $idno)->where('school_year', $school_year->school_year)->where('period', $school_year->period)->get();
    $units = 0;
    ?>
    @if(count($grade_colleges)>0)
    <table class='table' border="1" width="100%" cellspacing='0' cellpadding='3px' style='margin-top: 12px;'>
        <thead>
            <tr style='background: #a0a0a0'>
                <th class='td'><b>Course Code</b></th>
                <th class='td'><b>Course Name</b></th>
                <th class='td' align="center"><b>Schedule</b></th>
                <th class='td' align="center"><b>Instructor</b></th>
                <th class='td' align="center"><b>Units</b></th>
            </tr>    
        </thead>
        <tbody>
            @foreach($grade_colleges as $grade_college)
            <?php
            $units = $units + $grade_college->lec + $grade_college->lab;
            $offering_ids = \App\CourseOffering::find($grade_college->course_offering_id);
            ?>
            <tr>
                <td class='td'>{{$grade_college->course_code}}</td>
                <td class='td'>{{$grade_college->course_name}}</td>
                <td class='td'>
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
                <td class="td">
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
                <td class='td' align='center'>{{$grade_college->lec+$grade_college->lab}}</td>
            </tr>
            @endforeach
            <tr style="background: #a0a0a0">
                <td class='td' colspan="4"><strong>Total Units</strong></td>
                <td class='td' align='center'><strong>{{$units}}</strong></td>
            </tr>
        </tbody>
    </table>
    @else
    <div class="alert alert-danger">No Courses Advised!!</div>
    @endif
    <table class='table2' border="1" width="100%" cellspacing='0' cellpadding='0' style='margin-top: 30px; border-spacing: 20px; border-collapse: separate;'>
        <tr>
            <td class='top-line'>Adviser</td>
            <td class='top-line'>Student's Signature</td>
            <td class='top-line'>Date</td>
        </tr>
    </table>
</div>