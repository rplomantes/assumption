<style>
    body {
        font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
        font-size: 9pt;
                margin-top: 3.5cm;
                margin-left: 0cm;
                margin-right: 0cm;
                margin-bottom: 4cm;
    }
    footer {
        font-size: 8pt;
    }
            header {
                position: fixed; 
                top: 0cm; 
                left: 0px; 
                right: 0px;
                height: 0px; 
                
                margin: 0cm 0cm 0cm 0cm;
            }  
            footer {
                position: fixed; 
                bottom: 3.5cm; 
                left: 0px; 
                right: 0px;
                height: 0px; 
                
                margin: 0cm 1cm cm 1cm;

            }
    #schoolname{
        font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
        font-size: 18pt; 
        font-weight: bolder;
    }
    
    img {
        display: block;
        max-width:230px;
        max-height:95px;
        width: auto;
        height: auto;
    }
    .table, .th, .td {
        font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
        border-collapse: collapse;
        font: 9pt;
    }

</style>
<body>
<header>
        <table class="table table-condensed" width="100%" border="0">
            <tbody>        
                <div>    
                    <?php $sy = \App\CtrGradeSchoolYear::where('academic_type', 'College')->first(); ?>
                    @if(Auth::user()->accesslevel == env('INSTRUCTOR'))
                    <?php $sy = \App\CtrGradeSchoolYear::where('academic_type', 'College')->first(); ?>
                    @else
                    <?php $sy->school_year = $school_year;
                    $sy->period = $period;?>
                    @endif
                    <div style='float: left; margin-left: 150px;'><img src="{{public_path('/images/assumption-logo.png')}}"></div>
                    <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br>San Lorenzo Drive, San Lorenzo Village<br> Makati City<br><br><b>Higher Education Department<br>OFFICIAL GRADING SHEET</b><br><b>A.Y. {{$sy->school_year}} - {{$sy->school_year + 1}}, {{$sy->period}}</b></div>
                </div>
            </tbody>
        </table>
    </header>
    
        <footer>
    <table class='table' style='font-family: Arial, Helvetica Neue, Helvetica, sans-serif;' border="0" width="100%" cellspacing='1' cellpadding='1'>
        <tbody>
            <tr>
                <td><b>Grading System:</b></td>
            </tr>
            <tr>
                <td>1.0(95 & above)</td>
                <td>2.5(80-82)</td>
                <td>FA - Failure due to absences</td>
                <td></td>
            </tr>
            <tr>
                <td>1.2(93-94)</td>
                <td>2.7(78-79)</td>
                <td>INC - Incomplete</td>
                <td align='center'><div style='border-top: 1pm solid black'>Professor's Signature</div></td>
            </tr>
            <tr>
                <td>1.5(90-92)</td>
                <td>3.0(75-77)</td>
                <td>NA - Not Applicable</td>
                <td></td>
            </tr>
            <tr>
                <td>1.7(88-89)</td>
                <td>3.5(73-74)</td>
                <td>NG - No Grade</td>
                <td></td>
            </tr>
            <tr>
                <td>2.0(85-87)</td>
                <td>4.0(72 & below)</td>
                <td>UD - Unofficially Dropped</td>
                <td align='center'><div style='border-top: 1pm solid black'>Dean's Signature<div></td>
            </tr>
            <tr>
                <td>2.2(83-84)</td>
                <td></td>
                <td>W - Officially Withdrawn</td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td align='center'><small>Printed:</small> {{date('m/d/Y')}}</td>
            </tr>
        </tbody>
    </table>   
    </footer>
<div>
    <div>
        <?php $number = 1; ?>
        <table border="0" width="100%" cellspacing='1' cellpadding='1'>
            <thead>
                <?php $infos = \App\CourseOffering::where('schedule_id',$schedule_id)->first();?>
                <?php $is_tba = \App\ScheduleCollege::where('schedule_id', $schedule_id)->first()->is_tba; ?>
                <tr>
                    <td width="11%">Course:</td>
                    <td width="50%">{{$infos->course_code}}</td>
                    <td width="10%" align="right">Professor:</td>
                    <td>{{strtoupper($instructor->lastname)}} {{strtoupper($instructor->extensionname)}}, {{strtoupper($instructor->firstname)}} {{strtoupper($instructor->middlename)}}</td>
                </tr>
                <tr>
                    <td>Desc. Title:</td>
                    <td>{{$infos->course_name}}</td>
                    
                    <td align="right">Room:</td>
                    <td>  
                        <?php
                        $schedule3s = \App\ScheduleCollege::distinct()->where('schedule_id', $schedule_id)->get(['time_start', 'time_end', 'room']);
                        ?> 
                        @foreach ($schedule3s as $schedule3)
                        {{$schedule3->room}}
                        @endforeach</td>
                </tr>              
                <tr>
                    <td>Schedule:</td>
                    @if($is_tba == 0)
                    <td>
                        <?php
                        $schedule2s = \App\ScheduleCollege::distinct()->where('schedule_id', $schedule_id)->get(['time_start', 'time_end', 'room']);
                        ?>
                        @foreach ($schedule2s as $schedule2)
                        <?php
                        $days = \App\ScheduleCollege::where('schedule_id', $schedule_id)->where('time_start', $schedule2->time_start)->where('time_end', $schedule2->time_end)->where('room', $schedule2->room)->get(['day']);
                        ?>
                        <!--                @foreach ($days as $day){{$day->day}}@endforeach {{$schedule2->time}} <br>-->
                        @foreach ($days as $day){{$day->day}}@endforeach {{date('g:iA', strtotime($schedule2->time_start))}}-{{date('g:iA', strtotime($schedule2->time_end))}}<br>
                        @endforeach
                    </td>
                    @else
                    <td>TBA</td>
                    @endif
                    
                    
                <?php $allsection = ""; $sections = \App\CourseOffering::distinct()->where('schedule_id',$schedule_id)->get(['section_name']);?>
                    <td align="right">Block:</td>
                    <td>
                        @foreach ($sections as $key => $section)
                        @if ($key == 0)
                        <?php $allsection = $allsection . $section->section_name; ?>
                        @else
                        <?php $allsection = $allsection."/".$section->section_name; ?>
                        @endif
                        @endforeach
                        {{$allsection}}
                    </td>
                </tr>
                <tr>
                    <td>Program:</td>
                    <td colspan="3"></td>
                </tr>                                
            </thead>    
        </table>        
        <hr>
        <table style="font-family: Arial, Helvetica Neue, Helvetica, sans-serif;" class='table' border="0" width="100%" cellspacing='1' cellpadding='1'>
               <tr>
                    <th width="62%"></th>
                    <th width="10%"align='center' colspan="2">GRADES</th>
                    <th></th>
                    <th align='center' colspan="2">ABSENCES</th>
                </tr>            
        </table> 
        <table style="font-family: Arial, Helvetica Neue, Helvetica, sans-serif;" class='table' border="0" width="100%" cellspacing='1' cellpadding='1'>
            <thead>
                <tr>
                    <th style="border-bottom:1px solid black" width="3%"><div align="center">#</div></th>
                    <th style="border-bottom:1px solid black" width="10%">ID number</div></th>
                    <th style="border-bottom:1px solid black">Name</th>
                    <th style="border-bottom:1px solid black" width="10%" align='center'>Midterm </th>
                    <th style="border-bottom:1px solid black" width="10%" align='center'>Finals</th>
                    <th style="border-bottom:1px solid black" width='3%'></th>
                    <th style="border-bottom:1px solid black" width="10%" align='center'>Midterm</th>
                    <th style="border-bottom:1px solid black" width="10%" align='center'>Finals</th>
                </tr>
            </thead>         
        </table>
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
if(Auth::user()->accesslevel == env('INSTRUCTOR')){
$school_year = \App\CtrGradeSchoolYear::where('academic_type', 'College')->first()->school_year;
$period = \App\CtrGradeSchoolYear::where('academic_type', 'College')->first()->period;
}else{
    
}
$students = \App\GradeCollege::whereRaw('('.$raw.')')->join('college_levels', 'college_levels.idno', '=', 'grade_colleges.idno')->join('users', 'users.idno', '=', 'grade_colleges.idno')->where('college_levels.status', 3)->where('college_levels.school_year', $school_year)->where('college_levels.period', $period)->select('users.idno', 'users.firstname', 'users.lastname', 'grade_colleges.id', 'grade_colleges.midterm', 'grade_colleges.finals', 'grade_colleges.midterm_absences', 'grade_colleges.finals_absences', 'grade_colleges.grade_point', 'grade_colleges.is_lock', 'grade_colleges.midterm_status', 'grade_colleges.finals_status', 'grade_colleges.grade_point_status')->orderBy('users.lastname')->get();
?>
        
        @if (count($students)>0)
        <table style="font-family: Arial, Helvetica Neue, Helvetica, sans-serif;" class='table' border="0" width="100%" cellspacing='1' cellpadding='1'>
            <thead>
                <tr>
                    <th width="3%"><div align="center"></div></th>
                    <th width="10%"></div></th>
                    <th></th>
                    <th width="10%" align='center'></th>
                    <th width="10%" align='center'></th>
                    <th width='3%'></th>
                    <th width="10%" align='center'></th>
                    <th width="10%" align='center'></th>
                </tr>
            </thead>              
            <tbody>
                @foreach($students as $student)
                <tr>
                    <td style="border-bottom:1px solid black"><div align="right">{{$number}}.<?php $number = $number + 1; ?></div></td>
                    <td style="border-bottom:1px solid black">{{$student->idno}}</td>
                    <td style="border-bottom:1px solid black">{{strtoupper($student->lastname)}}, {{strtoupper($student->firstname)}} </td>
                    <td style="border-bottom:1px solid black" align='center'>{{$student->midterm}}</td>
                    <td style="border-bottom:1px solid black" align='center'>{{$student->finals}}</td>
                    <td style="border-bottom:1px solid black"></td>
                    <td style="border-bottom:1px solid black" align='center'>{{$student->midterm_absences}}</td>
                    <td style="border-bottom:1px solid black" align='center'>{{$student->finals_absences}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    <div align='center'>* * * * * * * * * * NOTHING FOLLOWS * * * * * * * * * *</div>    

</div>
</body>