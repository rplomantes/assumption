<?php
$credit = 0;
$gpa = 0;
$count = 0;
?>
<style>
    body {
        font-size: 9pt;
    }
    footer {
        font-size: 8pt;
    }
    #schoolname{
        font-size: 18pt; 
        font-weight: bolder;
    }
</style>
<style>
    @page {
        margin: 0cm 0cm;
    }

    /** Define now the real margins of every page in the PDF **/
    body {
        margin-left: 1cm;
        margin-right: 1cm;
        margin-bottom: 6.4cm;
        margin-top: 6.3cm;

    }
    footer {
        position: fixed; 
        bottom: 6cm; 
        left: 0px; 
        right: 0px;
        height: 0px; 

        margin: 0cm 1cm 0cm 1cm;

    }
    header {
        position: fixed; 
        top: .5cm; 
        left: 0px; 
        right: 0px;
        height: 0px; 

        margin: 0cm 1cm 0cm 1cm;

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
                <td valign='top' width='30%'><i><b>- FOR EVALUATION PURPOSES -</b></i></td>
                <td valign='top' width='30%'><i></i></td>
                <td valign='top' width='40%' rowspan="2" colspan="2">
                </td>
            </tr>
            <tr>
                <td valign='bottom'></td>
                <td valign='bottom'></td>
            </tr>
            <tr>
                <td  valign='top' colspan='2' rowspan="2">
                </td>
                <td valign='bottom' colspan='2'><i><br>CERTIFIED BY:<br><br><br></i></td>
            </tr>
            <tr>
                <td align='center'>{{strtoupper(env("HED_REGISTRAR"))}}<br>REGISTRAR</td>
                <td align='center'><small>DATE PRINTED</small><br>{{date('F d, Y')}}</td>
            </tr>
        </table>
    </footer>    
    <!--    
        <div style='float: left; margin-left:630px; margin-top:-110px;'></div>-->

    <header>    
        <div>    
            <div style='float: left; margin-left: 150px;'><img src="{{public_path('/images/assumption-logo.png')}}"></div>
            <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><br><b style="border:1px solid black;">&nbsp;GRADE FILE&nbsp;</b><br></div>
        </div>
        <br><br><br><br><br><br><br><br>
        <table class="table table-condensed" width="100%" border="0">
            <tbody>
                <tr>
                    <td width='15%'>PROGRAM NAME:</td>
                    <td width="52%" colspan="2"><div style="border-bottom: 1px solid black">{{$info->program_name}}</div></td>
                    <td width='13%' align='right'>STUDENT NO.:</td>
                    <td width='20%'><div style="border-bottom: 1px solid black">{{$user->idno}}&nbsp;</div></td> 
                </tr>
                <tr>
                    <td valign='top'>STUDENT NAME:</td>
                    <td colspan='2'><div style="border-bottom: 1px solid black"><b>{{strtoupper($user->lastname)}}, {{strtoupper($user->firstname)}} {{strtoupper($user->middlename)}}&nbsp;</div></b></td>   
                    <td align='right'>CITIZENSHIP:</td>
                    <td><div style="border-bottom: 1px solid black">{{strtoupper($info->nationality)}}&nbsp;</div></td> 
                </tr>
                <tr>
                    <td valign='top' colspan='5' cellpadding='0' cellspacing='0'>
                        <table width='100%' style="margin-left: -3px;">
                            <tr>
                                <td width='23%'>DATE AND PLACE OF BIRTH:</td>
                                <td><div style="border-bottom: 1px solid black">{{strtoupper(date('F d, Y',strtotime($info->birthdate)))}}, {{strtoupper($info->place_of_birth)}}&nbsp;</div></td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td valign='top'>ADDRESS:</td>
                    <td colspan='4'><div style="border-bottom: 1px solid black">{{strtoupper($info->street)}} {{strtoupper($info->barangay)}} {{strtoupper($info->municipality)}}&nbsp;</div></td>
                </tr>       
            </tbody>
        </table>
    </header> 
    <table width='100%' cellpadding="2" style=" border-collapse: collapse" border="1">
        <tr>
            <th width='12%' align='center' style="border:1px solid black;"><b>COURSE CODE</b></th>
            <th width='60%' align='center' style="border:1px solid black;"><b>DESCRIPTIVE TITLE</b></th>
            <th width='10%' align='center' style="border:1px solid black;"><b>GRADE</b></th>
            <th width='10%' align='center' style="border:1px solid black;"><b>COMPLETION</b></th>
            <th width='10%' align='center' style="border:1px solid black;"><b>CREDITS</b></th>
        </tr>

        
        <?php $grades_sy = \App\CollegeCredit::distinct()->where('idno', $idno)->orderBy('school_year', 'asc')->get(['school_year']); ?>
        @if(count($grades_sy)>0)
        @foreach($grades_sy as $sy)
        <?php $grades_pr = \App\CollegeCredit::distinct()->where('idno', $idno)->where('school_year', $sy->school_year)->orderBy('period', 'asc')->get(['period']); ?>
        @foreach ($grades_pr as $pr)
        <?php $credit_school = \App\CollegeCredit::distinct()->where('idno', $idno)->where('school_year', $sy->school_year)->where('period', $pr->period)->orderBy('school_name', 'asc')->get(['school_name']); ?>
        @foreach ($credit_school as $sr)
        <?php $grades = \App\CollegeCredit::where('idno', $idno)->where('school_year', $sy->school_year)->where('period', $pr->period)->get(); ?>
        <tr>
            <td></td>
            <td align='center'><b>@if($sr->school_name != ""){{strtoupper($sr->school_name)}} : @endif {{strtoupper($pr->period)}}, S.Y. {{$sy->school_year}}-{{$sy->school_year+1}}</b></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @foreach ($grades as $grade)

        <?php
        $display_final_grade = $grade->finals;
        if ($grade->finals == "" || $grade->finals == "AUDIT" || $grade->finals == "NA" || $grade->finals == "NG" || $grade->finals == "W" || $grade->finals == "FAILED" || $grade->finals == "PASSED") {
            $gpa = $gpa;
            $credit = $credit;
            $count = $count;
        } else if ($grade->finals == "INC") {
            if ($grade->completion == "" || $grade->completion == "AUDIT" || $grade->completion == "NA" || $grade->completion == "NG" || $grade->completion == "W" || $grade->completion == "FAILED" || $grade->completion == "PASSED") {
                $gpa = $gpa;
                $credit = $credit;
                $count = $count;
            } else {
                $gpa = $gpa + ($grade->completion * ($grade->lec + $grade->lab));
                $count = $count + $grade->lec + $grade->lab;
            }
        } else {
            if ($grade->finals == "FA" || $grade->finals == "UD") {
                $grade->finals = "4.00";
            }
            $gpa = $gpa + ($grade->finals * ($grade->lec + $grade->lab));
            $count = $count + $grade->lec + $grade->lab;
        }
        ?>
        <?php
        if (stripos($grade->course_code, "MME") !== FALSE || stripos($grade->course_code, "THEO") !== FALSE || stripos($grade->course_code, "NSTP") !== FALSE || stripos($grade->course_code, "PE") !== FALSE) {
            $credit = $grade->lec + $grade->lab;
            $credit = "(" . $credit . ")";
        } else {
            $credit = $grade->lec + $grade->lab;
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
        @endforeach
        @endif


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
        if ($pin_grades->finals == "" || $pin_grades->finals == "AUDIT" || $pin_grades->finals == "NA" || $pin_grades->finals == "NG" || $pin_grades->finals == "W" || $pin_grades->finals == "FAILED" || $pin_grades->finals == "PASSED") {
            $gpa = $gpa;
            $count = $count;
            $credit = $credit;
        } else if ($pin_grades->finals == "INC") {
            if ($pin_grades->completion == "" || $pin_grades->completion == "AUDIT" || $pin_grades->completion == "NA" || $pin_grades->completion == "NG" || $pin_grades->completion == "W" || $pin_grades->completion == "FAILED" || $pin_grades->completion == "PASSED") {
                $gpa = $gpa;
                $credit = $credit;
                $count = $count;
            } else {
                $gpa = $gpa + ($pin_grades->completion * ($pin_grades->lec + $pin_grades->lab));
                $count = $count + $pin_grades->lec + $pin_grades->lab;
            }
        } else {
            if ($pin_grades->finals == "FA" || $pin_grades->finals == "UD") {
                $pin_grades->finals = "4.00";
            }
            $gpa = $gpa + ($pin_grades->finals * ($pin_grades->lec + $pin_grades->lab));
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
                if (stripos($pin_grades->course_code, "MME") !== FALSE || stripos($pin_grades->course_code, "THEO") !== FALSE || stripos($pin_grades->course_code, "NSTP") !== FALSE || stripos($pin_grades->course_code, "PE") !== FALSE) {
                    $credit = $pin_grades->lec + $pin_grades->lab;
                    $credit = "(" . $credit . ")";
                } else {
                    $credit = $pin_grades->lec + $pin_grades->lab;
                }
                ?>
                {{$credit}}</td>
        </tr>
        @endif
        @endforeach
        @endforeach
        @endforeach



        <?php $grades_sy = \App\GradeCollege::distinct()->where('idno', $idno)->orderBy('school_year', 'asc')->where('finals_status', 3)->get(['school_year']); ?>
        @if(count($grades_sy)>0)
        @foreach($grades_sy as $sy)
        <?php $grades_pr = \App\GradeCollege::distinct()->where('idno', $idno)->where('school_year', $sy->school_year)->orderBy('period', 'asc')->where('finals_status', 3)->get(['period']); ?>
        @foreach ($grades_pr as $pr)
        <?php $grades = \App\GradeCollege::where('idno', $idno)->where('school_year', $sy->school_year)->where('period', $pr->period)->where('finals_status', 3)->get(); ?>
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
        if ($grade->finals == "" || $grade->finals == "AUDIT" || $grade->finals == "NA" || $grade->finals == "NG" || $grade->finals == "W" || $grade->finals == "FAILED" || $grade->finals == "PASSED") {
            $gpa = $gpa;
            $credit = $credit;
            $count = $count;
        } else if ($grade->finals == "INC") {
            if ($grade->completion == "" || $grade->completion == "AUDIT" || $grade->completion == "NA" || $grade->completion == "NG" || $grade->completion == "W" || $grade->completion == "FAILED" || $grade->completion == "PASSED") {
                $gpa = $gpa;
                $credit = $credit;
                $count = $count;
            } else {
                $gpa = $gpa + ($grade->completion * ($grade->lec + $grade->lab));
                $count = $count + $grade->lec + $grade->lab;
            }
        } else {
            if ($grade->finals == "FA" || $grade->finals == "UD") {
                $grade->finals = "4.00";
            }
            $gpa = $gpa + ($grade->finals * ($grade->lec + $grade->lab));
            $count = $count + $grade->lec + $grade->lab;
        }
        ?>
        <?php
        if (stripos($grade->course_code, "MME") !== FALSE || stripos($grade->course_code, "THEO") !== FALSE || stripos($grade->course_code, "NSTP") !== FALSE || stripos($grade->course_code, "PE") !== FALSE) {
            $credit = $grade->lec + $grade->lab;
            $credit = "(" . $credit . ")";
        } else {
            $credit = $grade->lec + $grade->lab;
        }
        ?>
        <tr>
            <td valign='top'>{{strtoupper($grade->course_code)}}</td>
            <td valign='top'>{{strtoupper($grade->course_name)}}</td>
            <td valign='top' align='center'>@if($grade->finals_status == 3){{$display_final_grade}}@endif</td>
            <td valign='top' align='center'>{{$grade->completion}}</td>
            <td valign='top' align='center'>{{$credit}}</td>
        </tr>
        @endforeach
        @endforeach
        @endforeach
        @endif
        @else

        <?php $grades_sy = \App\GradeCollege::distinct()->where('finals_status', 3)->where('idno', $idno)->orderBy('school_year', 'asc')->where('finals_status', 3)->get(['school_year']); ?>
        @if(count($grades_sy)>0)
        @foreach($grades_sy as $sy)
        <?php $grades_pr = \App\GradeCollege::distinct()->where('idno', $idno)->where('school_year', $sy->school_year)->orderBy('period', 'asc')->where('finals_status', 3)->get(['period']); ?>
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
        if ($grade->finals == "" || $grade->finals == "AUDIT" || $grade->finals == "NA" || $grade->finals == "NG" || $grade->finals == "W" || $grade->finals == "FAILED" || $grade->finals == "PASSED") {
            $gpa = $gpa;
            $credit = $credit;
            $count = $count;
        } else if ($grade->finals == "INC") {
            if ($grade->completion == "" || $grade->completion == "AUDIT" || $grade->completion == "NA" || $grade->completion == "NG" || $grade->completion == "W" || $grade->completion == "FAILED" || $grade->completion == "PASSED") {
                $gpa = $gpa;
                $credit = $credit;
                $count = $count;
            } else {
                $gpa = $gpa + ($grade->completion * ($grade->lec + $grade->lab));
                $count = $count + $grade->lec + $grade->lab;
            }
        } else {
            if ($grade->finals == "FA" || $grade->finals == "UD") {
                $grade->finals = "4.00";
            }
            $gpa = $gpa + ($grade->finals * ($grade->lec + $grade->lab));
            $count = $count + $grade->lec + $grade->lab;
        }
        ?>
        <?php
        if (stripos($grade->course_code, "MME") !== FALSE || stripos($grade->course_code, "THEO") !== FALSE || stripos($grade->course_code, "NSTP") !== FALSE || stripos($grade->course_code, "PE") !== FALSE) {
            $credit = $grade->lec + $grade->lab;
            $credit = "(" . $credit . ")";
        } else {
            $credit = $grade->lec + $grade->lab;
        }
        ?>
        <tr>
            <td valign='top'>{{strtoupper($grade->course_code)}}</td>
            <td valign='top'>{{strtoupper($grade->course_name)}}</td>
            <td valign='top' align='center'>@if($grade->finals_status == 3){{$display_final_grade}}@endif</td>
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
