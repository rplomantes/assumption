<?php $ctr = 1; ?>
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
    .table2, .th, .td {
        border: 1px solid black;
        border-collapse: collapse;
        font: 9pt;
    }

    .page_break { page-break-before: always; }
</style>
<div>    
    <div style='float: left; margin-left: 150px;'><img src="{{public_path('/images/assumption-logo.png')}}"></div>
    <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><br><b>RERPORT CARD</b><br><b>A.Y. {{$request->school_year}} - {{$request->school_year + 1}}, {{$request->period}}</b></div>
</div>  
<div>
    <table class='table' border="1" width="100%" cellspacing='0' cellpadding='0' style='margin-top: 155px;'>
        <thead>
            <tr>
                <th>#</th>
                <th>ID Number</th>
                <th>Name</th>
                <th>Program</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $student)
            <tr>
                <td>{{$ctr++}}</td>
                <td>{{$student->idno}}</td>
                <td>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}}</td>
                <td>{{$student->program_code}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@foreach ($students as $list)
<?php
$units = 0;
?>
<?php
$credit = 0;
$gpa = 0;
$count = 0;
?>
<div class="page_break"></div>

<div>
    <div>    
        <div style='float: left; margin-left: 150px;'><img src="{{public_path('/images/assumption-logo.png')}}"></div>
        <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><br><b>RERPORT CARD</b></div>
    </div>
    <div>
        <table style='margin-top:150px' class='table' border="0" width="100%">
            <tr>
                <td width="20%">STUDENT NUMBER</td>
                <td style="border-bottom:  1px solid black">{{$list->idno}}</td>
            </tr>
            <tr>
                <td>NAME OF STUDENT</td>
                <td style="border-bottom:  1px solid black">{{strtoupper($list->lastname)}}, {{strtoupper($list->firstname)}} {{strtoupper($list->middlename)}}</td>
            </tr>
            <tr>
                <td>PROGRAM & LEVEL</td>
                <td style="border-bottom:  1px solid black">{{strtoupper($list->program_name) ." - ".strtoupper($list->level)}}</td>
            </tr>
            <tr>
                <td>YEAR / PERIOD</td>
                <td style="border-bottom:  1px solid black">{{$school_year}}-{{$school_year+1}} / {{$period}}</td>
            </tr>
        </table>
        <hr>
        <table class='table2' border="1" width="100%">
            <tr>
                <th align="center">CODE</th>
                <th align="center">COURSE NAME</th>
                <th align="center">INSTRUCTOR</th>
                <th align="center">UNITS</th>
                <th align="center">MIDTERM</th>
                <th align="center">FINAL</th>
                <th align="center">REMARKS</th>
            </tr>
            <?php $grade_colleges = \App\GradeCollege::where('idno', $list->idno)->where('school_year', $school_year)->where('period', $period)->get(); ?>
            @foreach ($grade_colleges as $grade)

            <?php
            $remarks = "";
            $is_x = 0;
            $display_final_grade = $grade->finals;
            $display_final_completion = $grade->completion;
            if (stripos($grade->course_code, "NSTP") !== FALSE) {
                $gpa = $gpa;
                $count = $count;
                $credit = $credit;
            } else {
                if ($grade->finals == "" || $grade->finals == "AUDIT" || $grade->finals == "NA" || $grade->finals == "NG" || $grade->finals == "W" || $grade->finals == "PASSED") {
                    $remarks = $grade->finals;
                    $gpa = $gpa;
                    $count = $count;
                    $credit = $credit;
                } else if ($grade->finals == "INC") {
                    if ($grade->completion == "" || $grade->completion == "AUDIT" || $grade->completion == "NA" || $grade->completion == "NG" || $grade->completion == "W" || $grade->completion == "PASSED") {
                        $remarks = $grade->completion;
                        $gpa = $gpa;
                        $credit = $credit;
                        $count = $count;
                    } else {

                        if ($grade->completion == "FA" || $grade->completion == "UD" || $grade->completion == "FAILED") {
                            $grade->completion = "4.00";
                            $is_x = 1;
                        }

                        $remarks = "PASSED";
                        $gpa = $gpa + ($grade->completion * ($grade->lec + $grade->lab));
                        $count = $count + $grade->lec + $grade->lab;
                    }
                } else {
                    if ($grade->finals == "FA" || $grade->finals == "UD" || $grade->finals == "FAILED") {
                        $grade->finals = "4.00";
                        $is_x = 1;
                    }
                    $remarks = "PASSED";
                    $gpa = $gpa + ($grade->finals * ($grade->lec + $grade->lab));
                    $count = $count + $grade->lec + $grade->lab;
                }
            }
            ?>

            <?php
            $units = $units + $grade->lec + $grade->lab;
            ?>
            <tr>
                <td align="center">{{$grade->course_code}}</td>
                <td align="center">{{$grade->course_name}}</td>
                <?php $offering = \App\CourseOffering::where('id', $grade->course_offering_id)->first(); ?>
                <?php $schedule = \App\ScheduleCollege::where('schedule_id', $offering->schedule_id)->first(); ?>
                @if (isset($schedule))
                <?php $instructor_info = \App\User::where('idno', $schedule->instructor_id)->first(); ?>
                @endif
                @if (isset($instructor_info))
                <td align="center">{{$instructor_info->firstname}} {{$instructor_info->lastname}}</td>
                @else
                <td align="center"></td>
                @endif
                <td align="center">{{$grade->lec+$grade->lab}}</td>
                <td align="center">{{$grade->midterm}}</td>
                <td align="center">{{$grade->finals}}</td>
                <td align="center">{{strtoupper($remarks)}}</td>
            </tr>
            @endforeach
            <tr>
                <td align="right" colspan="3"><strong>UNITS &nbsp;</strong></td>
                <td align="center"><strong>{{$units}}</strong></td>
                <td></td>
                @if($gpa == 0)
                <td></td>
                @else
                <td align="center"><strong>{{number_format($gpa/$count,4)}}</strong></td>
                @endif
                <td></td>
            </tr>
        </table>
        <br>
        <div style="font-size: 9pt;">
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
        </div>
    </div>
</div>
@endforeach