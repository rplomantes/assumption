<?php
$credit=0;
$gpa=0;
$count = 0;
?>
<style>
    body {
        font-family: Courier New, Courier, Lucida Sans Typewriter, Lucida Typewriter, monospace;
        font-size: 9pt;
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
                bottom: 6.3cm; 
                left: 0px; 
                right: 0px;
                height: 0px; 
                
                margin: 0cm 1cm cm 1cm;

            }
            img {
        display: block;
        max-width:3.5cm;
        max-height:3.5cm;
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
<!--    
    <div style='float: left; margin-left:630px; margin-top:-110px;'></div>-->
    
    <table class="table table-condensed" width="100%">
        <tr>
            <td valign='top' width='24%'>STUDENT NAME:</td>
            <td><b>{{strtoupper($user->lastname)}}, {{strtoupper($user->firstname)}} {{strtoupper($user->middlename)}}</b></td>
            <td width='10%' valign='top' align='center' rowspan="17">
                <img src="{{public_path('/images/PICTURES/'.$user->idno.'.jpg')}}" alt=' '>
            </td>
        </tr>
        <tr>
            <td>STUDENT NUMBER:</td>
            <td>{{$user->idno}}</td>
        </tr>
        <tr>
            <td valign='top'>COURSE:</td>
            <td>{{strtoupper($level->program_name)}}</td>
        </tr>
        <tr>
            <td>DATE OF ADMISSION:</td>
            <td>{{strtoupper(date('F d, Y',strtotime($info->date_of_admission)))}}</td>
        </tr>
        <tr>
            <td valign='top'>DATE AND PLACE OF BIRTH:</td>
            <td>{{strtoupper(date('F d, Y',strtotime($info->birthdate)))}}, {{strtoupper($info->place_of_birth)}}</td>
        </tr>
        <tr>
            <td>CITIZENSHIP:</td>
            <td>{{strtoupper($info->nationality)}}</td>
        </tr>
        <tr>
            <td valign='top'>FATHER'S NAME:</td>
            <td>{{strtoupper($info->father)}}</td>
        </tr>
        <tr>
            <td valign='top'>MOTHER'S NAME:</td>
            <td>{{strtoupper($info->mother)}}</td>
        </tr>
        <tr>
            <td valign='top'>ADDRESS:</td>
            <td>{{strtoupper($info->street)}} {{strtoupper($info->barangay)}} {{strtoupper($info->municipality)}}</td>
        </tr>
        <tr>
            <td valign='top'>GRADE SCHOOL:</td>
            <td>{{strtoupper($info->gradeschool)}} {{strtoupper($info->gradeschool_address)}}</td>
        </tr>
        <tr>
            <td valign='top'>HIGH SCHOOL:</td>
            <td>{{strtoupper($info->highschool)}} {{strtoupper($info->highschool_address)}}</td>
        </tr>
        <tr>
            <td valign='top'>TERTIARY SCHOOL:</td>
            <td></td>
        </tr>
        <tr>
            <td valign='top'>DEGREE EARNED:</td>
            <td>{{strtoupper($level->program_name)}}</td>
        </tr>
        <tr>
            <td valign='top'>AWARD:</td>
            <td>{{strtoupper($info->award)}}</td>
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
            <td valign='top'>REMARKS:</td>
            <td>{{$info->remarks}}</td>
        </tr>
    </table>
    <hr>
    <table width='100%' cellpadding="2" style=" border-collapse: collapse">
        <tr>
            <th width='12%' align='center' style="border:2px solid black;"><b>COURSE CODE</b></th>
            <th width='60%' align='center' style="border:2px solid black;"><b>DESCRIPTIVE TITLE</b></th>
            <th width='10%' align='center' style="border:2px solid black;"><b>GRADE</b></th>
            <th width='10%' align='center' style="border:2px solid black;"><b>COMPLETION</b></th>
            <th width='10%' align='center' style="border:2px solid black;"><b>CREDITS</b></th>
        </tr>
        
        
        
    <?php $pinnacle_sy = \App\CollegeGrades2018::distinct()->where('idno', $idno)->orderBy('school_year', 'asc')->get(['school_year']); ?>
    @if(count($pinnacle_sy)>0)
    @foreach ($pinnacle_sy as $pin_sy)
    <?php $pinnacle_period = \App\CollegeGrades2018::distinct()->where('idno', $idno)->where('school_year', $pin_sy->school_year)->orderBy('period', 'asc')->get(['period']); ?>
    @foreach($pinnacle_period as $pin_pr)
    <?php $pinnacle_grades = \App\CollegeGrades2018::where('idno', $idno)->where('school_year', $pin_sy->school_year)->where('period', $pin_pr->period)->get(); ?>
    @if (count($pinnacle_grades)==1)
            @foreach($pinnacle_grades as $pin_grades)
            @if (stripos($pin_grades->course_code, "+") !== FALSE)
            @else
                <tr>
                    <td></td>
                    <td align='center'><b>{{strtoupper($pin_pr->period)}}, S.Y. {{$pin_sy->school_year}}-{{$pin_sy->school_year+1}}</b></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endif
            @endforeach
    @else
    <tr>
        <td></td>
        <td align='center'><b>{{strtoupper($pin_pr->period)}}, S.Y. {{$pin_sy->school_year}}-{{$pin_sy->school_year+1}}</b></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    @endif
            @foreach($pinnacle_grades as $pin_grades)
            @if (stripos($pin_grades->course_code, "+") !== FALSE)

            @else
            <?php
            $display_final_grade = $pin_grades->finals;
            if($pin_grades->finals == "" || $pin_grades->finals == "AUDIT" ||$pin_grades->finals == "NA" ||$pin_grades->finals == "NG" ||$pin_grades->finals == "W" ||$pin_grades->finals == "FAILED" ||$pin_grades->finals == "PASSED"){
             $gpa = $gpa;
            }else if($pin_grades->finals == "INC" ){
                $gpa=$gpa+($pin_grades->completion* $pin_grades->lec + $pin_grades->lab);
                $count = $count +  $pin_grades->lec + $pin_grades->lab;
            } else {
                if($pin_grades->finals == "FA" ||$pin_grades->finals == "UD"){
                    $pin_grades->finals = "4.00";
                }
                $gpa = $gpa + ($pin_grades->finals* $pin_grades->lec + $pin_grades->lab);
                $count = $count + $pin_grades->lec + $pin_grades->lab;
            }
            ?>
            <tr>
                <td valign='top'>{{strtoupper($pin_grades->course_code)}}</td>
                <td valign='top'>{{strtoupper($pin_grades->course_name)}}</td>
                <td valign='top' align='center'>{{$display_final_grade}}</td>
                <td valign='top' align='center'>{{$pin_grades->completion}}</td>
                <td valign='top' align='center'>
            <?php
            if(stripos($pin_grades->course_code, "MME") !== FALSE || stripos($pin_grades->course_code, "THEO") !== FALSE || stripos($pin_grades->course_code, "NSTP") !== FALSE || stripos($pin_grades->course_code, "PE") !== FALSE){
                $credit=$pin_grades->lec + $pin_grades->lab;
                $credit="(".$credit.")";
            }else{
                $credit=$pin_grades->lec + $pin_grades->lab;
            }
            ?>
                {{$credit}}</td>
            </tr>
            @endif
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
            
            $display_final_grade = $grade->finals;
            if($grade->finals == "" || $grade->finals == "AUDIT" ||$grade->finals == "NA" ||$grade->finals == "NG" ||$grade->finals == "W" ||$grade->finals == "FAILED" ||$grade->finals == "PASSED"){
                $gpa=$gpa;
                $credit = $credit;
            }else if($grade->finals == "INC" ){
                $gpa=$gpa+($grade->completion * ($grade->lec+$grade->lab));
                $count = $count + $grade->lec + $grade->lab;
            }else{
                if($grade->finals == "FA" ||$grade->finals == "UD"){
                    $grade->finals = "4.00";
                }
                $gpa = $gpa + ($grade->finals * ($grade->lec+$grade->lab));
                $count = $count + $grade->lec + $grade->lab;
            }
            ?>
            <?php
            if(stripos($grade->course_code, "MME") !== FALSE || stripos($grade->course_code, "THEO") !== FALSE || stripos($grade->course_code, "NSTP") !== FALSE || stripos($grade->course_code, "PE") !== FALSE){
                $credit=$grade->lec + $grade->lab;
                $credit="(".$credit.")";
            }else{
                $credit=$grade->lec + $grade->lab;
            }
            ?>
            <tr>
                <td valign='top'>{{strtoupper($grade->course_code)}}</td>
                <td valign='top'>{{strtoupper($grade->course_name)}}</td>
                <td valign='top' align='center'>{{$display_final_grade}}</td>
                <td valign='top' align='center'>{{$grade->completion}}</td>
                <td valign='top' align='center'>{{$credit}}</td>
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
            
            $display_final_grade = $grade->finals;
            if($grade->finals == "" || $grade->finals == "AUDIT" ||$grade->finals == "NA" ||$grade->finals == "NG" ||$grade->finals == "W" ||$grade->finals == "FAILED" ||$grade->finals == "PASSED"){
                $gpa=$gpa;
            }else if($grade->finals == "INC" ){
                $gpa=$gpa+($grade->completion * ($grade->lec+$grade->lab));
                $count = $count + $grade->lec + $grade->lab;
            }else{
                if($grade->finals == "FA" ||$grade->finals == "UD"){
                    $grade->finals = "4.00";
                }
                $gpa = $gpa + ($grade->finals * ($grade->lec+$grade->lab));
                $count = $count + $grade->lec + $grade->lab;
            }
            ?>
            <?php
            if(stripos($grade->course_code, "MME") !== FALSE || stripos($grade->course_code, "THEO") !== FALSE || stripos($grade->course_code, "NSTP") !== FALSE || stripos($grade->course_code, "PE") !== FALSE){
                $credit=$grade->lec + $grade->lab;
                $credit="(".$credit.")";
            }else{
                $credit=$grade->lec + $grade->lab;
            }
            ?>
            <tr>
                <td valign='top'>{{strtoupper($grade->course_code)}}</td>
                <td valign='top'>{{strtoupper($grade->course_name)}}</td>
                <td valign='top' align='center'>{{$display_final_grade}}</td>
                <td valign='top' align='center'>{{$grade->completion}}</td>
                <td valign='top' align='center'>{{$credit}}</td>
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
