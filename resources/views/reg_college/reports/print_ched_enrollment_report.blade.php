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

</style> 
            <div style='float: left; margin-left: 300px;'><img src="{{public_path('/images/assumption-logo.png')}}"></div>
            <!--<div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><br><b>CHED ENROLLMENT REPORTS</b><br></div>-->    
            <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><br><b>CHED ENROLLMENT REPORTS</b><br><b>{{$request->period}}, {{$request->school_year}} - {{$request->school_year + 1}}</b></div>
        
            <table  style='margin-top: 155px;'>
            <thead>
            <tr>
            <td>  
                <b>Institutional Identifier:</b> 13022<br>
                <b>Name of Institution:</b> Assumption College<br>
                <b>Address:</b> San Lorenzo Village, Makati City<br>
                <b>Program Name:</b><?php $program_name = \App\CtrAcademicProgram::where('program_code', $request->program_code)->first()->program_name; ?>{{$program_name}}<br>
                <b>Tel No.:</b> 817-0757 loc. 2030<br>
            </td>
            </tr>    
            </thead>
        </table>
<div>
    <table class='table' border="1" width="100%" cellspacing='0' cellpadding='0' style='margin-top:25px;'>
        <thead>
            <tr>
                <th style="text-align: left;">ID Number</th>
                <th style="text-align: left;">Student Name</th>
                <th style="text-align: left;">Level</th>
                <th style="text-align: left;">Gender</th>
                <th style="text-align: left;">Course Code</th>
                <th style="text-align: left; padding-left: 50px">Course Description</th>
                <th style="text-align: left;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Units</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($students as $student)
            <?php $user = \App\User::where('idno', $student->idno)->first(); ?>
            <tr>
                <td valign="top">{{$student->idno}}</td>
                <td valign="top">{{strtoupper($user->lastname)}}, {{strtoupper($user->firstname)}} {{strtoupper($user->middlename)}}</td>
                <td valign="top">{{$student->level}}</td>
                <td valign="top">F</td>
                <td colspan="3">
                    <?php 
                    $units = 0;
                    $totalunits = 0;
                    $grades = \App\GradeCollege::where('idno', $student->idno)->where('school_year', $student->school_year)->where('period',$student->period)->where('level', $student->level)->get(); ?>
                    <table width="100%">
                        @foreach ($grades as $grade)
                        <tr>
                            <td>{{$grade->course_code}}</td>
                            <td>{{$grade->course_name}}</td>
                            <td>{{$units = $grade->lec + $grade->lab}}</td>
                        </tr>
                        <?php $totalunits=$totalunits + $units; ?>
                        @endforeach
                        <tr>
                            <td colspan="2" align="right">TOTAL UNITS:</td>
                            <td><b>{{$totalunits}}</b></td>
                        </tr>
                    </table>                    
                </td>
            </tr>    
            @endforeach
                        <tr>
                            <td colspan="7"><b>Total Number of Students: {{count($students)}}</b></td>
                        </tr>                    
        </tbody>
    </table><br><br><br>
    <table width="100%">
        <tr>
            <td><b>Certified Correct:<br><br><br><br></b></td>
        </tr>
        <tr>
            <td><b>ROSIE B. SOMERA</b></td>
        </tr>
        <tr>
            <td>Registrar</td>
        </tr>
        <tr>
            <td align="right">Date Printed: {{ date('Y-m-d H:i:s') }}</td>
        </tr>
    </table>
</div>