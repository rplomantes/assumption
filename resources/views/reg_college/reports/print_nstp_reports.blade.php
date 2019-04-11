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
        font: 6pt;
    }

</style>
<div>    
    <div style='float: left; margin-left: 200px;'><img src="{{public_path('/images/assumption-logo.png')}}"></div>
    <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><b>A.Y. {{$request->school_year}} - {{$request->school_year + 1}}, {{$request->period}}</b><br><br><b><?php $course_name = \App\Curriculum::where('course_code', $request->course_code)->first()->course_name; ?>{{$request->course_code}}: {{$course_name}}</b></div>
</div>
<div>
    <table style='margin-top:150px' class='table' border="1" width="100%">
        <thead>
            <tr>
                <th align='center' width="70%">Program</th>
                <th align='center'>I</th>
                <th align='center'>II</th>
                <th align='center'>III</th>
                <th align='center'>IV</th>
                <th align='center'>Total</th>
            </tr>
        </thead>      
        <tbody>
            <?php 
            $totalcount1 = 0;
            $totalcount2 = 0;
            $totalcount3 = 0;
            $totalcount4 = 0;
            $grandtotal = 0;
            ?>
            @foreach ($programs as $program)
            <tr>
                <td>{{$program->program_name}}</td>
                <td align='center'><?php $count1 = \App\GradeCollege::join('college_levels', 'college_levels.idno','=','grade_colleges.idno')->where('college_levels.program_code', $program->program_code)->where('college_levels.status', '3')->where('grade_colleges.course_code', $request->course_code)->where('grade_colleges.level', "1st Year")->where('grade_colleges.school_year', $request->school_year)->where('grade_colleges.period', $request->period)->where('college_levels.school_year', $request->school_year)->where('college_levels.period', $request->period)->get(); ?>{{count($count1)}}</td></td>
                <td align='center'><?php $count2 = \App\GradeCollege::join('college_levels', 'college_levels.idno','=','grade_colleges.idno')->where('college_levels.program_code', $program->program_code)->where('college_levels.status', '3')->where('grade_colleges.course_code', $request->course_code)->where('grade_colleges.level', "2nd Year")->where('grade_colleges.school_year', $request->school_year)->where('grade_colleges.period', $request->period)->where('college_levels.school_year', $request->school_year)->where('college_levels.period', $request->period)->get(); ?>{{count($count2)}}</td>
                <td align='center'><?php $count3 = \App\GradeCollege::join('college_levels', 'college_levels.idno','=','grade_colleges.idno')->where('college_levels.program_code', $program->program_code)->where('college_levels.status', '3')->where('grade_colleges.course_code', $request->course_code)->where('grade_colleges.level', "3rd Year")->where('grade_colleges.school_year', $request->school_year)->where('grade_colleges.period', $request->period)->where('college_levels.school_year', $request->school_year)->where('college_levels.period', $request->period)->get(); ?>{{count($count3)}}</td>
                <td align='center'><?php $count4 = \App\GradeCollege::join('college_levels', 'college_levels.idno','=','grade_colleges.idno')->where('college_levels.program_code', $program->program_code)->where('college_levels.status', '3')->where('grade_colleges.course_code', $request->course_code)->where('grade_colleges.level', "4th Year")->where('grade_colleges.school_year', $request->school_year)->where('grade_colleges.period', $request->period)->where('college_levels.school_year', $request->school_year)->where('college_levels.period', $request->period)->get(); ?>{{count($count4)}}</td>
                <td align='center'><?php $totalcount = count($count1) + count($count2) + count($count3) + count($count4); ?>{{$totalcount}}</td>
            </tr>
            <?php
            $totalcount1 = $totalcount1 + count($count1);
            $totalcount2 = $totalcount2 + count($count2);
            $totalcount3 = $totalcount3 + count($count3);
            $totalcount4 = $totalcount4 + count($count4);
            ?>            
            @endforeach
            <tr>
                <td align='right'><b>SUB TOTAL</b></td>
                <td align='center'>{{$totalcount1}}</td>
                <td align='center'>{{$totalcount2}}</td>
                <td align='center'>{{$totalcount3}}</td>
                <td align='center'>{{$totalcount4}}</td>
                <td></td>                
            </tr>
           <tr>
                <td align='right'><b>GRAND TOTAL</b></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td align='center'><b>{{$grandtotal = $totalcount1 + $totalcount2 + $totalcount3 + $totalcount4}}</b></td>                
            </tr>            
        </tbody>
    </table>  
</div>
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
                <td><b>ROSIE B. SOMERA<br></b></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td>Registrar</td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <div style="page-break-after: always;"></div> 
<div>    
    <table width="100%">
        <thead>
            <tr>
                <td width="17%"><b>Institutional Identifier:</b><td><b>13022</b></td>
            </tr>
            <tr>
                <td><b>Name of Institution:</b></td><td><b>Assumption College</b></td>
            </tr>
            <tr>
                <td><b>Address:</b></td><td><b>San Lorenzo Village, Makati City</b></td>
            </tr>
            <tr>
                <td><b>Tel. No.:</b></td><td><b>817-0757 loc. 2030</b></td>
            </tr>
        </thead>
    </table>
    <table width="100%">
        <thead>
            <tr>
                <td align='center'><b>LIST OF {{$request->course_code}} ENROLLED </b></td>
            </tr>
            
            <tr>
                <td align='center'><b>A.Y. {{$request->school_year}} - {{$request->school_year + 1}}, {{$request->period}} </b></td>
            </tr>
        </thead>
    </table>    
    <table class='table' border="1" width="100%">
        <thead>
            <tr>
                <th align='center' width='1%'>#</th>
                <th align='center' width='2%'>ID</th>
                <th align='center' width='20%'>Student Name</th>
                <th align='center' width='25%'>Course</th>
                <th align='center' width='25%'>Address</th>
                <th align='center'>Birthdate</th>
                <th align='center' width='5%'>Gender</th>
                <th align='center'>Tel No.</th>
            </tr>
        </thead>
        
        <tbody>
            <?php $count = 0;?>
            @foreach($students as $student)
            <?php $user = \App\User::where('idno', $student->idno)->first(); ?>
            <?php $info = \App\StudentInfo::where('idno', $student->idno)->first(); ?>
            <?php $count = $count +1?>
            <tr>
                <td>{{$count}}.</td>
                <td>{{$user->idno}}</td>
                <td>{{strtoupper($user->lastname)}}, {{strtoupper($user->firstname)}} {{strtoupper($user->middlename)}}</td>
                <td>{{strtoupper($info->program_name)}}</td>
                <td>{{$info->street}}, {{$info->barangay}},{{$info->municipality}},{{$info->province}}</td>
                <td align='center'>{{date('m/d/Y',strtotime($info->birthdate))}}</td>
                <td align='center'>F</td>
                <td align='center'>{{$info->tel_no}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
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
</div>  
    