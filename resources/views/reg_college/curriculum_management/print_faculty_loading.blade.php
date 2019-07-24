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
        border-collapse: collapse;
        font: 9pt;
    }


</style>
<div>    
    <div style='float: left; margin-left: 245px;'><img src="{{public_path('/images/assumption-logo.png')}}"></div>
    <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><br><b>FACULTY LOADING</b><br><b>A.Y. {{$school_year->school_year}} - {{$school_year->school_year + 1}}, {{$school_year->period}}</b><br></div>
</div>
<div>
    <table class="table table-condensed" width="100%"  style='margin-top: 155px;'>
        <tr>
            <th style="font-size:25px" align='left'>Prof. {{$user->firstname}} {{$user->lastname}} {{$user->extensionname}}</th>
        </tr>    
    </table>
    @if (count($loads)>0)
    <table class="table" width='100%' border='1'>
        <thead>
            <tr>
                <th class="col-sm-2">Course Code</th>
                <th class="col-sm-4">Sections</th>
                <th class="col-sm-1" align="center">Units</th>
                <th class="col-sm-3">Schedule</th>
                <th class="col-sm-2">Room</th>
            </tr>
        </thead>
        <tbody>
            @foreach($loads as $load)
            <tr>
                <td>
                    <?php
                    $schedules = \App\ScheduleCollege::where('schedule_id', $load->schedule_id)->get();
                    $details = \App\CourseOffering::where('schedule_id', $load->schedule_id)->get();
                    $unitss = \App\CourseOffering::where('schedule_id', $load->schedule_id)->first();
                    ?>
                    {{$load->course_code}}
                </td>
                <td>
                    @foreach ($details as $detail)
                    {{$detail->program_code}} - {{$detail->level}}  - {{$detail->section_name}}<br>
                    @endforeach
                </td>
                <td align="center">
                    {{$unitss->lec+$unitss->lab}}
                </td>
                <td>
                    <?php
                    $schedule2s = \App\ScheduleCollege::distinct()->where('school_year', $school_year->school_year)->where('period', $school_year->period)->where('schedule_id', $load->schedule_id)->get(['time_start', 'time_end', 'room']);
                    ?>
                    @foreach ($schedule2s as $schedule2)
                    <?php
                    $days = \App\ScheduleCollege::where('schedule_id', $load->schedule_id)->where('time_start', $schedule2->time_start)->where('time_end', $schedule2->time_end)->where('room', $schedule2->room)->get(['day']);
                    ?>
                    @foreach ($days as $day){{$day->day}}@endforeach 
                    <?php $is_tba = \App\ScheduleCollege::where('schedule_id', $load->schedule_id)->first()->is_tba; ?>
                    @if ($is_tba == 0)
                    {{date('g:i A', strtotime($schedule2->time_start))}} - {{date('g:i A', strtotime($schedule2->time_end))}}<br>
                    @else

                    @endif
                    <!--{{$schedule2->day}} {{$schedule2->time_start}} - {{$schedule2->time_end}}<br>-->
                    @endforeach
                </td>
                <td>
                    <?php
                    $schedule3s = \App\ScheduleCollege::distinct()->where('schedule_id', $load->schedule_id)->get(['time_start', 'time_end', 'room']);
                    ?>
                    @foreach ($schedule3s as $schedule3)
                    {{$schedule3->room}}<br>
                    @endforeach
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <h1>No Courses Loaded!!!</h1>
    @endif
    @if(Auth::user()->academic_type == "REGISTRAR_COLLEGE")
    <br>        
    <table width="100%">
        <thead>
            <tr>
                <td>Prepared By:<br><br><br><br></td>
                <td>Approved By:<br><br><br><br></td>
                <td><div align="right">Date Printed: {{ date('Y-m-d H:i:s') }}</div></td>
            </tr>
        </thead>
        <tbody>
            <tr>               
                <td><b>{{strtoupper(Auth::user()->lastname)}}, {{strtoupper(Auth::user()->firstname)}} {{strtoupper(Auth::user()->middlename)}}</b></td>
                <td><b>{{strtoupper(env("HED_REGISTRAR"))}}<br></b></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td>Registrar</td>
                <td></td>
            </tr>
        </tbody>
    </table>
    @endif    
</div>