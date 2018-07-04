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
<div>    
    <div style='float: left; margin-left: 150px;'><img src="{{public_path('/images/assumption-logo.png')}}"></div>
    <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><br><b>LIST OF STUDENTS</b><br><small>@if($school_years == "all") All Years @else{{$school_years}}-{{$school_years+1}}@endif - @if($periods == "all") All Periods @else{{$periods}} @endif</small></div>
</div>
<div>
   <table class='table' border="1" width="100%" cellspacing='0' cellpadding='0' style='margin-top: 145px;'>
        <tr>
            <td class='no-border td' width='4%'>Section:</td>
            <td class='underline td' width='30%'>{{$section_name}}</td>
            <td class='no-border td' width='12%'>Course:</td>
            <td class='underline td'>{{$course_code}}({{$course_name}})</td>
        </tr>
        <tr>
            <td class='no-border td'>Schedule:</td>
            <td class='underline td'>
                
                    <?php
                    $schedule2s = \App\ScheduleCollege::distinct()->where('schedule_id', $schedule_id)->get(['time_start', 'time_end', 'room']);
                    ?>
                    @foreach ($schedule2s as $schedule2)
                    <?php
                    $days = \App\ScheduleCollege::where('schedule_id', $schedule_id)->where('time_start', $schedule2->time_start)->where('time_end', $schedule2->time_end)->where('room', $schedule2->room)->get(['day']);
                    ?>
                    <!--                @foreach ($days as $day){{$day->day}}@endforeach {{$schedule2->time}} <br>-->
                    @foreach ($days as $day){{$day->day}}@endforeach {{date('g:i A', strtotime($schedule2->time_start))}} - {{date('g:i A', strtotime($schedule2->time_end))}}
                    @endforeach
                    <?php
                    $schedule3s = \App\ScheduleCollege::distinct()->where('schedule_id', $schedule_id)->get(['time_start', 'time_end', 'room']);
                    ?>
                    @foreach ($schedule3s as $schedule3)
                    {{$schedule3->room}}
                    @endforeach
                
            </td>
            <td class='no-border td'>Professor: </td>
            <td class='underline td'> 
            
                <?php
                $schedule_instructor = \App\ScheduleCollege::distinct()->where('schedule_id', $schedule_id)->get(['instructor_id']);
                ?>
                @foreach($schedule_instructor as $get)
                    @if ($get->instructor_id != NULL)
                    <?php $instructor = \App\User::where('idno', $get->instructor_id)->first(); ?>
                    {{$instructor->firstname}} {{$instructor->lastname}} {{$instructor->extensionname}}
                    @endif
                @endforeach
                
            </td>
        </tr>
    </table>
    <table class='table' border="1" width="100%" cellspacing='0' cellpadding='3px' style='margin-top: 50px;'>
        <thead>
            <tr>
                <th width='3%' align='center'>#</th>
                <th width='15%'>ID Number</th>
                <th width='30%'>Student Name</th>
                <th>Program</th>
                <th>Level</th>
            </tr>
        </thead>
        <tbody>
            <?php $counter = 0; ?>
            @foreach ($list_per_courses as $list)
            <?php $status = \App\CollegeLevel::where('idno', $list->idno)->where('status',3)->first(); ?>
            @if (count($status)>0)
            <?php $counter = $counter + 1; ?>
            <?php $user = \App\User::where('idno', $list->idno)->first(); ?>
            <?php $student_info = \App\StudentInfo::where('idno', $list->idno)->first(); ?>
            <tr>
                <td align='center'>{{$counter}}</td>
                <td>{{$list->idno}}</td>
                <td>{{$user->lastname}}, {{$user->firstname}}</td>
                <td>{{$status->program_code}}</td>
                <td>{{$status->level}}</td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>
</div>