<?php
$final_gpa=0;
$gpa=0;
$count = 0;
?>
<style>
    body {
        font-family: Courier New, Courier, Lucida Sans Typewriter, Lucida Typewriter, monospace;
        font-size: 8pt;
    }
    footer {
        font-family: Courier New, Courier, Lucida Sans Typewriter, Lucida Typewriter, monospace;
        font-size: 8pt;
    }
</style>  
<style>
@page {
                margin: 0cm 0cm;
            }

            /** Define now the real margins of every page in the PDF **/
            body {
                margin-top: 4cm;
                margin-left: 1cm;
                margin-right: 1cm;
                margin-bottom: 6.4cm;
            }
            footer {
                position: fixed; 
                bottom: 6cm; 
                left: 0px; 
                right: 0px;
                height: 0px; 
                
                margin: 0cm 1cm cm 1cm;

            }
            img {
        display: block;
        max-width:230px;
        max-height:95px;
        width: auto;
        height: auto;
    }
        </style>
<body>
        <footer>
            <table width='100%'>
                <tr>
                    <td valign='top' width='30%'><i>PREPARED BY</i></td>
                    <td valign='top' width='30%'><i>VERIFIED BY</i></td>
                    <td valign='top' width='40%' rowspan="2" colspan="2">
                        <b>I HEREBY CERTIFY THAT THE FOREGOING RECORDS HAVE BEEN DULY VERIFIED BY ME AND THAT THE TRUE COPIES OF THE OFFICIAL RECORDS SUBSTANTIATING THE SAME ARE KEPT IN THE FILES OF THE COLLEGE.</b>
                    </td>
                </tr>
                <tr>
                    <td valign='bottom'>{{strtoupper(Auth::user()->firstname)}} {{strtoupper(Auth::user()->lastname)}}</td>
                    <td valign='bottom'>MA. IMELDA T. VALBUENA</td>
                </tr>
                <tr>
                    <td  valign='top' colspan='2' rowspan="2">
                    </td>
                    <td valign='bottom' colspan='2'><i><br>APPROVED BY<br><br><br></i></td>
                </tr>
                <tr>
                    <td align='center'>ROSIE B. SOMERA<br>REGISTRAR</td>
                    <td align='center'><small>DATE PRINTED</small><br>{{date('F d, Y')}}</td>
                </tr>
            </table>
        </footer>
    
    <div style='float: left; margin-left:630px; margin-top:-110px;'><img src="{{public_path('/images/'.$user->idno.'.JPG')}}" alt=' '></div>
    
    <table class="table table-condensed" width="100%">
        <tr>
            <td width='22%'>STUDENT NAME:</td>
            <td><b>{{strtoupper($user->lastname)}}, {{strtoupper($user->firstname)}} {{strtoupper($user->middlename)}}</b></td>
        </tr>
        <tr>
            <td>STUDENT NUMBER:</td>
            <td>{{$user->idno}}</td>
        </tr>
        <tr>
            <td>COURSE:</td>
            <td>{{strtoupper($level->program_name)}}</td>
        </tr>
        <tr>
            <td>DATE OF ADMISSION:</td>
            <td>{{$info->award}}</td>
        </tr>
        <tr>
            <td>DATE AND PLACE OF BIRTH:</td>
            <td>{{strtoupper(date('F d, Y',strtotime($info->birthdate)))}}, {{strtoupper($info->place_of_birth)}}</td>
        </tr>
        <tr>
            <td>CITIZENSHIP:</td>
            <td>{{strtoupper($info->nationality)}}</td>
        </tr>
        <tr>
            <td>FATHER'S NAME:</td>
            <td>{{strtoupper($info->father)}}</td>
        </tr>
        <tr>
            <td>MOTHER'S NAME:</td>
            <td>{{strtoupper($info->mother)}}</td>
        </tr>
        <tr>
            <td>ADDRESS:</td>
            <td>{{strtoupper($info->street)}} {{strtoupper($info->barangay)}} {{strtoupper($info->municipality)}}</td>
        </tr>
        <tr>
            <td>GRADE SCHOOL:</td>
            <td>{{strtoupper($info->gradeschool)}} {{strtoupper($info->gradeschool_address)}}</td>
        </tr>
        <tr>
            <td>HIGH SCHOOL:</td>
            <td>{{strtoupper($info->highschool)}} {{strtoupper($info->highschool_address)}}</td>
        </tr>
        <tr>
            <td>TERTIARY SCHOOL:</td>
            <td></td>
        </tr>
        <tr>
            <td>DEGREE EARNED:</td>
            <td>{{strtoupper($level->program_name)}}</td>
        </tr>
        <tr>
            <td>AWARD:</td>
            <td>{{$info->award}}</td>
        </tr>
        <tr>
            <td>DATE OF GRADUATION:</td>
            <td>{{strtoupper(date('F d, Y', strtotime($info->date_of_grad)))}}</td>
        </tr>
        <tr>
            <td>S.O. NUMBER:</td>
            <td>EXEMPTED</td>
        </tr>
        <tr>
            <td>REMARKS:</td>
            <td>{{$info->remarks}}</td>
        </tr>
    </table>
    <hr>
    <table width='100%'>
        <tr>
            <th width='11%' align='left'><b>COURSE CODE</b></th>
            <th width='60%' align='center'><b>DESCRIPTIVE TITLE</b></th>
            <th width='10%' align='center'><b>GRADE</b></th>
            <th width='10%' align='center'><b>COMPLETION</b></th>
            <th width='10%' align='center'><b>CREDITS</b></th>
        </tr>
        
        
        
    <?php $pinnacle_sy = \App\CollegeGrades2018::distinct()->where('idno', $idno)->orderBy('school_year', 'asc')->get(['school_year']); ?>
    @if(count($pinnacle_sy)>0)
    @foreach ($pinnacle_sy as $pin_sy)
    <?php $pinnacle_period = \App\CollegeGrades2018::distinct()->where('idno', $idno)->where('school_year', $pin_sy->school_year)->orderBy('period', 'asc')->get(['period']); ?>
    @foreach($pinnacle_period as $pin_pr)
    <?php $pinnacle_grades = \App\CollegeGrades2018::where('idno', $idno)->where('school_year', $pin_sy->school_year)->where('period', $pin_pr->period)->get(); ?>
    <tr>
        <td></td>
        <td align='center'><b>{{strtoupper($pin_pr->period)}}, S.Y. {{$pin_sy->school_year}}-{{$pin_sy->school_year+1}}</b></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
            @foreach($pinnacle_grades as $pin_grades)
            <?php
            if($pin_grades->finals == "" || $pin_grades->finals == "AUDIT" ||$pin_grades->finals == "INC" ||$pin_grades->finals == "NA" ||$pin_grades->finals == "NG" ||$pin_grades->finals == "W" ||$pin_grades->finals == "FAILED" ||$pin_grades->finals == "PASSED"){
             $gpa = $gpa;   
            } else {
                if($pin_grades->finals == "FA" ||$pin_grades->finals == "UD"){
                    $pin_grades->finals = 4.00;
                }
            $gpa = $gpa + $pin_grades->finals;
            $count = $count +1;
            }
            ?>
            <tr>
                <td>{{$pin_grades->course_code}}</td>
                <td><?php $get_course_name = \App\Curriculum::where('course_code', $pin_grades->course_code)->first(); ?>
                    @if(count($get_course_name)>0)
                    {{$get_course_name->course_name}}
                    @else
                    <i style="color: red;">Course name not found</i>
                    @endif</td>
                <td align='center'>{{$pin_grades->finals}}</td>
                <td align='center'>{{$pin_grades->completion}}</td>
                <td align='center'></td>
            </tr>
            @endforeach
    @endforeach
    @endforeach
    
    
    
    <?php $grades_sy = \App\GradeCollege::distinct()->where('idno', $idno)->orderBy('school_year', 'asc')->get(['school_year']); ?>
    @if(count($grades_sy)>0)
    @foreach($grades_sy as $sy)
    <?php $grades_pr = \App\GradeCollege::distinct()->where('idno', $idno)->where('school_year', $sy->school_year)->orderBy('period', 'asc')->get(['period']); ?>
    @foreach ($grades_pr as $pr)
    <?php $grades = \App\GradeCollege::where('idno', $idno)->where('school_year', $sy->school_year)->where('period', $pr->period)->get(); ?>
    <tr>
        <td></td>
        <td align='center'><b>{{strtoupper($pr->period)}}, S.Y. {{$sy->school_year}}-{{$sy->school_year+1}}</b></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
            @foreach ($grades as $grade)
            <?php
            if($grade->finals == "" || $grade->finals == "AUDIT" ||$grade->finals == "INC" ||$grade->finals == "NA" ||$grade->finals == "NG" ||$grade->finals == "W" ||$grade->finals == "FAILED" ||$grade->finals == "PASSED"){
                $gpa=$gpa;
            }else{
                if($grade->finals == "FA" ||$grade->finals == "UD"){
                    $grade->finals = 4.00;
                }
            $gpa = $gpa + $grade->finals;
            $count = $count +1;
            }
            ?>
            <tr>
                <td>{{$grade->course_code}}</td>
                <td>{{$grade->course_name}}</td>
                <td align='center'>{{$grade->finals}}</td>
                <td align='center'>{{$grade->completion}}</td>
                <td align='center'>{{$grade->lec + $grade->lab}}</td>
            </tr>
            @endforeach
    @endforeach
    @endforeach
    @endif
    @else

    <?php $grades_sy = \App\GradeCollege::distinct()->where('idno', $idno)->orderBy('school_year', 'asc')->get(['school_year']); ?>
    @if(count($grades_sy)>0)
    @foreach($grades_sy as $sy)
    <?php $grades_pr = \App\GradeCollege::distinct()->where('idno', $idno)->where('school_year', $sy->school_year)->orderBy('period', 'asc')->get(['period']); ?>
    @foreach ($grades_pr as $pr)
    <?php $grades = \App\GradeCollege::where('idno', $idno)->where('school_year', $sy->school_year)->where('period', $pr->period)->get(); ?>
    <tr>
        <td></td>
        <td align='center'><b>{{strtoupper($pr->period)}}, S.Y. {{$sy->school_year}}-{{$sy->school_year+1}}</b></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
            @foreach ($grades as $grade)
            <?php
            if($grade->finals == "" || $grade->finals == "AUDIT" ||$grade->finals == "INC" ||$grade->finals == "NA" ||$grade->finals == "NG" ||$grade->finals == "W" ||$grade->finals == "FAILED" ||$grade->finals == "PASSED"){
                $gpa=$gpa;
            }else{
                if($grade->finals == "FA" ||$grade->finals == "UD"){
                    $grade->finals = 4.00;
                }
            $gpa = $gpa + $grade->finals;
            $count = $count +1;
            }
            ?>
            <tr>
                <td>{{$grade->course_code}}</td>
                <td>{{$grade->course_name}}</td>
                <td align='center'>{{$grade->finals}}</td>
                <td align='center'>{{$grade->completion}}</td>
                <td align='center'>{{$grade->lec + $grade->lab}}</td>
            </tr>
            @endforeach
    @endforeach
    @endforeach
    @endif
    @endif
    <tr>
        <td colspan='5' align='center'><b>******************************NOTHING FOLLOWS******************************</b></td>
    </tr>
    <tr>
        <td></td>
        <td align='center'><b>GPA</b></td>
        <td align='center'><b>{{number_format($gpa/$count,4)}}</b></td>
        <td></td>
        <td></td>
    </tr>
    </table>
</body>