<style>
    body {
        font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
        font-size: 9pt;
    }
    
    img {
        display: block;
        max-width:230px;
        max-height:95px;
        width: auto;
        height: auto;
    }
    #schoolname{
        font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
        font-size: 18pt; 
        font-weight: bolder;
    }
    .table, .th, .td {
        font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
        border-collapse: collapse;
        font: 9pt;
    }
    .table2 {
        font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
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
<body>
<div>    
    <div style='float: left; margin-left: 150px;'><img src="{{public_path('/images/assumption-logo.png')}}"></div>
    <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><br><b>OFFICIAL GRADING SHEET</b></div>
</div>
<div>
    <div style='margin-top:130px'>
        <?php $number = 1; ?>
        <table border="1" width="100%" cellspacing='1' cellpadding='1'>
            <thead>
                <?php $infos = \App\CourseOffering::where('schedule_id',$schedule_id)->get(['course_code', 'course_name']);?>                                
                
                <tr>
                    <td width="11%">Course:</td>
                    <td>{{$infos->course_code}}</td>
                    <td align="right">Professor:</td>
                    <td>{{strtoupper(Auth::user()->lastname)}}, {{strtoupper(Auth::user()->firstname)}} {{strtoupper(Auth::user()->middlename)}}</td>
                </tr>
                <tr>
                    <td>Desc. Title:</td>
                    <td>{{$infos->course_name}}</td>
                    
                    <td align="right">Room:</td>
                    <td></td>
                </tr>              
                <tr>
                    <td>Schedule:</td>
                    <td></td>
                    <td align="right">Block:</td>
                    <td>{{$infos->section_name}}</td>
                </tr>
                <tr>
                    <td>Program:</td>
                    <td></td>
                    <td></td>
                    <td></td>
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
                    <th width="3%"><div align="center">#</div></th>
                    <th width="10%">ID number</div></th>
                    <th>Name</th>
                    <th width="10%" align='center'>Midterm </th>
                    <th width="10%" align='center'>Finals</th>
                    <th width='3%'></th>
                    <th width="10%" align='center'>Midterm</th>
                    <th width="10%" align='center'>Finals</th>
                </tr>
            </thead>         
        </table>          
        @foreach ($courses_id as $course_id)
        <?php
        $students = \App\GradeCollege::where('course_offering_id', $course_id->id)->join('statuses', 'statuses.idno', '=', 'grade_colleges.idno')->join('users', 'users.idno', '=', 'grade_colleges.idno')->where('statuses.status', 3)->select('users.idno', 'users.firstname', 'users.lastname', 'grade_colleges.id', 'grade_colleges.midterm', 'grade_colleges.finals', 'grade_colleges.grade_point', 'grade_colleges.is_lock', 'grade_colleges.midterm_status', 'grade_colleges.finals_status', 'grade_colleges.grade_point_status')->orderBy('users.lastname')->get();
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
                    <td><div align="right">{{$number}}.<?php $number = $number + 1; ?></div></td>
                    <td>{{$student->idno}}</td>
                    <td>{{strtoupper($student->lastname)}}, {{strtoupper($student->firstname)}} </td>
                    <td align='center'>{{$student->midterm}}</td>
                    <td align='center'>{{$student->finals}}</td>
                    <td></td>
                    <td align='center'></td>
                    <td align='center'></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    @endforeach
    <div align='center'>*****NOTHING FOLLOWS*****</div>    
    <table class='table' style='margin-top:40px; font-family: Arial, Helvetica Neue, Helvetica, sans-serif;' border="0" width="100%" cellspacing='1' cellpadding='1'>
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
                <td align='center'><div style='border-top: 1pm solid black'>Professor Signature</div></td>
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
                <td>UD - Unoficially Dropped</td>
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
</div>
</body>