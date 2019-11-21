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
    <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><br><b>MASTER SCHEDULE PER DAY</b><br><b>A.Y. {{$request->school_year}} - {{$request->school_year + 1}}, {{$request->period}}</b><br></div>
</div>
<div>
    <table class="table table-condensed" width="100%"  style='margin-top: 155px;'>
        <tr>
            <th style="font-size:15pt" align='left'></th>
        </tr>    
    </table>
    @if (count($courses)>0)
    <h3>Day: <strong>
            @switch($request->day)
                @case('M')
                    Monday
                    @break

                @case('T')
                    Tuesday
                    @break

                @case('W')
                    Wednesday
                    @break

                @case('Th')
                    Thursday
                    @break

                @case('F')
                    Friday
                    @break

                @case('S')
                    Saturday
                    @break

                @default
                    Default case...
            @endswitch
        </strong></h3>
    <h3>Number of Students: <strong>{{count($number_of_students)}}</strong></h3>
    <table class="table table-striped" width='100%' border='1'>
            <thead>
                <tr>
                    <th>Course Code</th>
                    <th>Course Name</th>
                    <th>Schedule</th>
                    <th>Instructor</th>
                    <th>No. of Students</th>
                </tr>
            </thead>
            <tbody>
                <?php $totalunits = 0; ?>
                @foreach($courses as $course)
                <?php $number_of_students_per_course = \App\ScheduleCollege::distinct()->where('course_offerings.schedule_id',$course->schedule_id)->where('day', $request->day)->where('schedule_colleges.course_code', $course->course_code)->where('schedule_colleges.school_year', $request->school_year)->where('schedule_colleges.period', $request->period)->join('course_offerings', 'course_offerings.schedule_id','schedule_colleges.schedule_id')->join('grade_colleges','grade_colleges.course_offering_id', 'course_offerings.id')->join('statuses', 'statuses.idno', 'grade_colleges.idno')->where('statuses.status', env("ENROLLED"))->get(['grade_colleges.idno']); ?>
                <?php $course_name = \App\Curriculum::where('course_code', $course->course_code)->first(); ?>
                @if(count($course_name)==0)
                <?php $course_name = \App\CtrElective::where('course_code', $course->course_code)->first(); ?>
                @endif
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
                    <td>{{count($number_of_students_per_course)}}</td>
                </tr>             
                @endforeach                
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