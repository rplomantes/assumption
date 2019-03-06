<?php
$credit = 0;
$gpa = 0;
$count = 0;
?>
<style>
    body {
        font-size: 9.1pt;
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
        margin-bottom: 1cm;
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
    </footer>    
    <!--    
        <div style='float: left; margin-left:630px; margin-top:-110px;'></div>-->

    <header>    
        <div>    
            <div style='float: left; margin-left: 150px;'><img src="{{public_path('/images/assumption-logo.png')}}"></div>
            <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><br><b style="border:1px solid black;">&nbsp;CREDITED COURSES SUMMARY&nbsp;</b><br></div>
        </div>
        <br><br><br><br><br><br><br><br>
        <table class="table table-condensed" width="100%" border="0">
            <tbody>
                <tr>
                    <td width='15%'>PROGRAM NAME:</td>
                    <td width="52%" colspan="2">
                        <div style="border-bottom: 1px solid black">
                            <?php $array = explode(' ', $info->program_name) ;?>
                            @foreach ($array as $key=>$k)
                                @if($array[$key] == "Major")
                                    <br>{{$array[$key]}}
                                @elseif($array[$key] == "Specialization")
                                    <br>{{$array[$key]}}
                                @else
                                    {{$array[$key]}}
                                @endif
                            @endforeach
                        </div>
                    </td>
                    <td width='13%' align='right'>STUDENT NO.:</td>
                    <td width='20%'><div style="border-bottom: 1px solid black">{{$user->idno}}&nbsp;</div></td> 
                </tr>
                <tr>
                    <td valign='top'>STUDENT NAME:</td>
                    <td colspan='2'><div style="border-bottom: 1px solid black"><b>{{mb_strtoupper($user->lastname)}}, {{mb_strtoupper($user->firstname)}} {{mb_strtoupper($user->middlename)}}&nbsp;</div></b></td>   
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
    <table width='100%' cellpadding="2" style=" border-collapse: collapse" border="0">
        <tr>
            <th width='12%' align='center' style="border:1px solid black;"><b>COURSE CODE</b></th>
            <th width='60%' align='center' style="border:1px solid black;"><b>DESCRIPTIVE TITLE</b></th>
            <th width='10%' align='center' style="border:1px solid black;"><b>GRADE</b></th>
            <th width='10%' align='center' style="border:1px solid black;"><b>COMPLETION</b></th>
            <th width='10%' align='center' style="border:1px solid black;"><b>UNITS</b></th>
        </tr>

        <?php $with_credit=0; ?>
        <?php $school_name = ""; ?>
        <?php $credit_sy = \App\CollegeCredit::distinct()->where('idno', $idno)->orderBy('school_year', 'asc')->get(['school_year']); ?>
        @if(count($credit_sy)>0)
        @foreach($credit_sy as $sy)
        <?php $credit_pr = \App\CollegeCredit::distinct()->where('idno', $idno)->where('school_year', $sy->school_year)->orderBy('period', 'asc')->get(['period']); ?>
        @foreach ($credit_pr as $pr)
        <?php $with_credit = 1; $credit_school = \App\CollegeCredit::distinct()->where('idno', $idno)->where('school_year', $sy->school_year)->where('period', $pr->period)->orderBy('school_name', 'asc')->get(['school_name']); ?>
        @foreach ($credit_school as $sr)
        @if($school_name == $sr->school_name)
        <?php $school_name = $school_name;?>
        <?php $with_credit = 0; ?>
        @else
        <?php $with_credit = 1; ?>
        <?php $school_name = $sr->school_name; ?>
        @endif
        <?php $grades = \App\CollegeCredit::where('idno', $idno)->where('school_year', $sy->school_year)->where('period', $pr->period)->where('school_name', $sr->school_name)->get(); ?>
        <tr>
            <td></td>
            <td align='center'>
                <b>
                    @if($with_credit == 1){{strtoupper($school_name)}}<br> @endif 
                        @if($pr->period == "1st Semester") FIRST SEMESTER 
                        @elseif($pr->period == "2nd Semester") SECOND SEMESTER 
                        @elseif($pr->period == "Summer") SUMMER  
                        @elseif($pr->period == "1st Quarter") FIRST QUARTER 
                        @elseif($pr->period == "2nd Quarter") SECOND QUARTER 
                        @elseif($pr->period == "3rd Quarter") THIRD QUARTER
                        @elseif($pr->period == "4th Quarter") FOURTH QUARTER
                        @elseif($pr->period == "1st Term") FIRST TERM 
                        @elseif($pr->period == "2nd Term") SECOND TERM 
                        @elseif($pr->period == "3rd Term") THIRD TERM
                    @endif, S.Y. {{$sy->school_year}}-{{$sy->school_year+1}}
                </b>
            </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @foreach ($grades as $grade)
        <?php
        $is_x=0;
        $display_final_grade = $grade->finals;
        $display_final_completion = $grade->completion;
        if(stripos($grade->course_code, "NSTP") !== FALSE){
            $gpa = $gpa;
            $count = $count;
            $credit = $credit;
        if($grade->finals == "FAILED" || $grade->finals == "FA" || $grade->finals == "UD"  || $grade->finals == "4.00"){
                $is_x = 1;
            }else{
                $is_x = 0;
            }
        }else{
            if ($grade->finals == "" || $grade->finals == "AUDIT" || $grade->finals == "NA" || $grade->finals == "NG" || $grade->finals == "W" || $grade->finals == "PASSED") {
                $gpa = $gpa;
                $count = $count + $grade->lec;
                $credit = $credit;
            } else if ($grade->finals == "INC") {
                if ($grade->completion == "" || $grade->completion == "AUDIT" || $grade->completion == "NA" || $grade->completion == "NG" || $grade->completion == "W" || $grade->completion == "PASSED") {
                    $gpa = $gpa;
                    $credit = $credit;
                    $count = $count + $grade->lec;
                } else {

                    if ($grade->completion == "FA" || $grade->completion == "UD" || $grade->completion == "FAILED" || $grade->completion == "4.00") {
                        $grade->completion = "4.00";
                        $is_x = 1;
                    }

                    $count = $count + $grade->lec;
                }
            } else {
                if ($grade->finals == "FA" || $grade->finals == "UD" || $grade->finals == "FAILED" || $grade->finals == "4.00") {
                    $grade->finals = "4.00";
                        $is_x = 1;
                }
                $count = $count + $grade->lec;
            }
        }
        ?>
        <?php
        if (stripos($grade->course_code, "MME") !== FALSE || stripos($grade->course_code, "THEO") !== FALSE || stripos($grade->course_code, "NSTP") !== FALSE || stripos($grade->course_code, "PE") !== FALSE) {
            $credit = $grade->lec;
            $credit = "(" . $credit . ")";
                    if($is_x == 1){
                        $credit = "(x)";
                    }
        } else {
            $credit = $grade->lec;
                    if($is_x == 1){
                        $credit = "x";
                    }
        }
        ?>
        <tr>
            <td valign='top'>{{strtoupper($grade->credit_code)}}</td>
            <td valign='top'>{{strtoupper($grade->credit_name)}}</td>
            <td valign='top' align='center'>{{$display_final_grade}}</td>
            <td valign='top' align='center'>{{$display_final_completion}}</td>
            <td valign='top' align='center'>{{$credit}}</td>
        </tr>
        @endforeach
        @endforeach
        @endforeach
        @endforeach
        <?php $with_credit = 1; ?>
        @endif
        

        <tr>
            <td colspan='5' align='center'><b>******************************NOTHING FOLLOWS******************************</b></td>
        </tr>
        <tr>
            <td style="border-top: 1px solid black"></td>
            <td style="border-top: 1px solid black" align='center'><b>TOTAL CREDITED UNITS</b></td>
            <td style="border-top: 1px solid black"></td>
            <td style="border-top: 1px solid black"></td>
            <td style="border-top: 1px solid black" align='center'><b>{{$count}}</b></td>
        </tr>
    </table> 
    <br>
        <table width='100%' border="0">
            <tr>
                <td valign='bottom' colspan='2' width="50%"><i><br><small>PREPARED BY:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</small></i> {{mb_strtoupper(Auth::user()->lastname).", ". mb_strtoupper(Auth::user()->firstname)}}</td>
                <td align='center' colspan="2"><small><i>DATE PRINTED:</i></small>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{date('F d, Y')}}</td>
            </tr>
            <tr>
                <td valign='bottom' colspan='2'><i><br><small>CERTIFIED BY:</small><br></i></td>
            </tr>
            <tr>
                <td align='center' colspan="2">{{strtoupper(env("HED_REGISTRAR"))}}<br>REGISTRAR</td>
                <td align='center' valign="top"><small><i>RECEIVED BY:</i></small></td>
                <td align='center' valign="top"><small><i>DATE RECEIVED:</i></small><br></td>
            </tr>
        </table>
</body>
