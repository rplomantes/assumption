<?php $unit = 0; ?>
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
    <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><br><b>MASTER SCHEDULE PER ROOM</b><br><b>A.Y. {{$request->school_year}} - {{$request->school_year + 1}}, {{$request->period}}</b><br></div>
</div>
<div>
    <table class="table table-condensed" width="100%"  style='margin-top: 155px;'>
        <tr>
            <th style="font-size:30px" align='left'>Room: {{strtoupper($request->room)}}</th>
        </tr>    
    </table>
    @if (count($courses)>0)
    <table class="table table-striped" width='100%' border='1'>
            <thead>
                <tr>
                    <th>Course Code</th>
                    <th>Course Name</th>
                    <th>Unit</th>
                    <th>Schedule</th>
                    
                    <th>Instructor</th>
                </tr>
            </thead>
            <tbody>
                <?php $totalunits = 0; ?>
                @foreach($courses as $course)
                <?php $course_name = \App\Curriculum::where('course_code', $course->course_code)->first(); ?>
                <tr>
                <?php ?>    
                    <td>{{$course->course_code}}</td>
                    <td>
                        <?php
                        $schedules = \App\ScheduleCollege::where('schedule_id', $course->schedule_id)->get();
                        ?>
                        {{$course_name->course_name}}

                    </td>
                    <td>
                        <?php $units = \App\CourseOffering::where('id', $course->course_offering_id)->first();
                        ?>
                        @if (count($units)>0)
                        {{$unit= $units->lec + $units->lab}}
                        @else
                         <?php $unit = $units + 0;?>   
                        @endif
                        <?php $totalunits = $totalunits + $unit?>
                    </td>
                    <td>
                        <?php
                        $schedule2s = \App\ScheduleCollege::distinct()->where('schedule_id', $course->schedule_id)->get(['time_start', 'time_end', 'room']);
                        ?>
                        @foreach ($schedule2s as $schedule2)
                        <?php
                        $days = \App\ScheduleCollege::where('schedule_id', $course->schedule_id)->where('time_start', $schedule2->time_start)->where('time_end', $schedule2->time_end)->where('room', $schedule2->room)->get(['day']);
                        ?>
                        <!--                @foreach ($days as $day){{$day->day}}@endforeach {{$schedule2->time}} <br>-->
                        @foreach ($days as $day){{$day->day}}@endforeach 
                        <?php $is_tba = \App\ScheduleCollege::where('schedule_id', $course->schedule_id)->first()->is_tba; ?>
                        @if ($is_tba == 0)
                        {{date('g:i A', strtotime($schedule2->time_start))}} - {{date('g:i A', strtotime($schedule2->time_end))}}<br>
                        @else

                        @endif
                        @endforeach
                    </td>
                    <td>
                        <?php
                        $offering_id = \App\ScheduleCollege::where('schedule_id', $course->schedule_id)->first();
                        $schedule_instructor = \App\ScheduleCollege::distinct()->where('schedule_id', $offering_id->schedule_id)->get(['instructor_id']);

                        foreach ($schedule_instructor as $get) {
                            if ($get->instructor_id != NULL) {
                                $instructor = \App\User::where('idno', $get->instructor_id)->first();
                                echo "$instructor->firstname $instructor->lastname $instructor->extensionname";
                            } else {
                                echo "";
                            }
                        }
                        ?>
                    </td>
                </tr>             
                @endforeach 
        <tr>
            <td align="left"><b>TOTAL UNITS:</b></td>
            <td><b></b></td>
            <td><b>{{$totalunits}}</b></td>
            <td><b></b></td>
            <td><b></b></td>
        </tr>                
            </tbody>         
        </table>   
    @else
    <div class="alert alert-info alert-dismissible">
        <h4><i class="icon fa fa-info"></i> Alert!</h4>
        No Courses Offered for this room!!!
    </div>
    @endif
    @if(Auth::user()->accesslevel == env("REG_COLLEGE"))
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