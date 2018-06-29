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
        font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
        font-size: 9pt;
    }

</style>
<style>
    body{
        font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
        font-size: 9pt;
    }
    
    header {
        position: fixed; 
        bottom: 6cm; 
        left: 0px; 
        right: 0px;
        height: 0px; 

        margin: 0cm 1cm cm 1cm;

    }
</style>
<body>
    <div style='float: left; margin-left: 270px;'><img src="{{public_path('/images/assumption-logo.png')}}"></div>
    <!--<div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><br><b>CHED ENROLLMENT REPORTS</b><br></div>-->    
    <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><br>OFFICE OF THE REGISTRAR<br><b>CHED ENROLLMENT LIST REPORT</b><br><b>{{$request->period}}, {{$request->school_year}} - {{$request->school_year + 1}}</b></div>

    <table  style='margin-top: 135px;' width="100%">
        <thead>
            <tr>
                <td width="15%"><b>Institutional Identifier:</b><td><b>13022</b></td>
            </tr>
            <tr>
                <td><b>Name of Institution:</b></td><td><b>Assumption College</b></td>
            </tr>
            <tr>
                <td><b>Address:</b></td><td><b>San Lorenzo Village, Makati City</b></td>
            </tr>
            <tr>
                <td><b>Program:</b></td><td><b><?php $program_name = \App\CtrAcademicProgram::where('program_code', $request->program_code)->first()->program_name; ?>{{$program_name}}</b></td>
            </tr>
            <tr>
                <td><b>Tel. No.:</b></td><td><b>817-0757 loc. 2030</b></td>
            </tr>
        </thead>
    </table>
    
    <table class='table' width="100%" cellspacing='0' cellpadding='0' style='margin-top:25px;'>
        <thead>
            <tr>
                <th style="border-top: 1pt dotted black;border-bottom: 1pt dotted black"><?php $counter = 1; ?></th>
                <th style="text-align: left;border-top: 1pt dotted black;border-bottom: 1pt dotted black">Student#</th>
                <th style="text-align: left;border-top: 1pt dotted black;border-bottom: 1pt dotted black">Student Name</th>
                <th style="text-align: left;border-top: 1pt dotted black;border-bottom: 1pt dotted black">Yr</th>
                <th style="text-align: center;border-top: 1pt dotted black;border-bottom: 1pt dotted black">Gender</th>
                <th style="text-align: center;border-top: 1pt dotted black;border-bottom: 1pt dotted black">Course Code</th>
                <th style="text-align: center;border-top: 1pt dotted black;border-bottom: 1pt dotted black">Course Description</th>
                <th style="text-align: center;border-top: 1pt dotted black;border-bottom: 1pt dotted black">Units</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $student)
            <?php $user = \App\User::where('idno', $student->idno)->first(); ?>
            <tr>
                <td valign="top" style="text-align: left">{{$counter}}.<?php $counter = $counter + 1; ?></td>
                <td valign="top">{{$student->idno}}</td>
                <td valign="top">{{strtoupper($user->lastname)}}, {{strtoupper($user->firstname)}} {{strtoupper($user->middlename)}}</td>
                <td valign="top">{{$student->level}}</td>
                <td valign="top" align="center">F</td>
                <td colspan="3">
                    <?php
                    $units = 0;
                    $totalunits = 0;
                    $grades = \App\GradeCollege::where('idno', $student->idno)->where('school_year', $student->school_year)->where('period', $student->period)->get();
                    ?>
                    <table width="100%">
                        @foreach ($grades as $grade)
                        <tr>
                            <td>{{$grade->course_code}}</td>
                            <td>{{$grade->course_name}}</td>
                            <td>{{$units = $grade->lec + $grade->lab}}</td>
                        </tr>
                        <?php $totalunits = $totalunits + $units; ?>
                        @endforeach
                        <tr>
                            <td style="border-top: 1pt double dotted black" colspan="2" align="right">TOTAL UNITS:</td>
                            <td style="border-top: 1pt double dotted black"><b>{{$totalunits}}</b></td>
                        </tr>
                        <tr>
                            <td colspan="3"><br></td>
                        </tr>
                    </table>                    
                </td>
            </tr>    
            @endforeach
            <tr>
                <td colspan="8"><b>Total Number of Students: {{count($students)}}</b></td>
            </tr>                    
        </tbody>
    </table>
    <br><br><br>
    <table width="100%">
        <tbody>
            <tr>
                <td><b>Certified Correct:<br><br><br><br></b>
                    <b>ROSIE B. SOMERA</b><br>
                    Registrar<br>

                    <div align="right">Date Printed: {{ date('Y-m-d H:i:s') }}</div></td>
            </tr>
        </tbody>
    </table>
</body>