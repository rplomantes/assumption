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
<?php
$school_year = \App\CtrAdvisingSchoolYear::where('academic_type', "College")->first();
$user = \App\User::where('idno', $idno)->first();
$status = \App\Status::where('idno', $idno)->first();
?>
<div>    
    <div style='float: left; margin-left: 150px;'><img src="{{public_path('/images/assumption-logo.png')}}"></div>
    <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><br><b>ADVISING SLIP</b><br><small>A.Y. {{$school_year->school_year}} - {{$school_year->school_year+1}} {{$school_year->period}}</small></div>
</div>
<div>
    <table class='table' border="1" width="100%" cellspacing='0' cellpadding='0' style='margin-top: 145px;'>
        <tr>
            <td class='no-border td' width='10%'>ID Number:</td>
            <td class='underline td' colspan='3'>{{$user->idno}}</td>
        </tr>
        <tr>
            <td class='no-border td'>Name:</td>
            <td class='underline td' colspan='3'>{{$user->firstname}} {{$user->middlename}} {{$user->lastname}}</td>
        </tr>
        <tr>
            <td class='no-border td'>Program:</td>
            <td class='underline td' width='40%'>{{$status->program_name}}</td>
            <td class='no-border td' width='5%'>Level:</td>
            <td class='underline td'>{{$status->level}}</td>
        </tr>
    </table>
    <?php
    $grade_colleges = \App\GradeCollege::where('idno', $idno)->where('school_year', $school_year->school_year)->where('period', $school_year->period)->get();
    $units = 0;
    ?>
    @if(count($grade_colleges)>0)
    <table class='table' border="1" width="100%" cellspacing='0' cellpadding='3px' style='margin-top: 12px;'>
        <thead>
            <tr style='background: #a0a0a0'>
                <th class='td'><b>Course Code</b></th>
                <th class='td'><b>Course Name</b></th>
                <th class='td' align="center"><b>Lec</b></th>
                <th class='td' align="center"><b>Lab</b></th>
            </tr>    
        </thead>
        <tbody>
            @foreach($grade_colleges as $grade_college)
            <?php
            $units = $units + $grade_college->lec + $grade_college->lab;
            $offering_ids = \App\CourseOffering::find($grade_college->course_offering_id);
            ?>
            <tr>
                <td class='td'>{{$grade_college->course_code}}</td>
                <td class='td'>{{$grade_college->course_name}}</td>
                <td class='td' align='center'>{{$grade_college->lec}}</td>
                <td class='td' align='center'>{{$grade_college->lab}}</td>
            </tr>
            @endforeach
            <tr style="background: #a0a0a0">
                <td class='td' colspan="2"><strong>Total Units</strong></td>
                <td class='td' colspan="2" align='center'><strong>{{$units}}</strong></td>
            </tr>
        </tbody>
    </table>
    @else
    <div class="alert alert-danger">No Courses Advised!!</div>
    @endif
    <table class='table2' border="0" width="100%" cellspacing='0' cellpadding='0' style='margin-top: 30px; border-spacing: 20px; border-collapse: separate;'>
        <tbody>
        <tr>
            <td align="center"><div style="border-top: 1px solid black">Adviser</div></td>
            <td width="10%"><td>
            <td align="center"><div style="border-top: 1px solid black">{{$user->firstname}} {{$user->middlename}} {{$user->lastname}}</div></td>
            <td><small>DATE PRINTED</small><br>{{date('F d, Y')}}</td>
        </tr>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td align="center"><div style="border-top: 1px solid black">Dean</div></td>

        </tr>
        </tbody>
    </table>
</div>