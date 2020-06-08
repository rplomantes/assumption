<style>
    table tr td {
        font: 9pt !important;
    }
    .legend td {
        font: 12pt !important;
    }
</style>
<?php

function getAttendances($month, $school_year, $idno, $type) {
    if ($type == 'absences') {
        $getAttendances = \App\Absent::SelectRaw('sum(is_absent)as total')->where('school_year', $school_year)->where('idno', $idno)->where('date', 'like', "%-$month-%")->first();
    } else {
        $getAttendances = \App\Absent::SelectRaw('sum(is_tardy)as total')->where('school_year', $school_year)->where('idno', $idno)->where('date', 'like', "%-$month-%")->first();
    }
    if ($getAttendances->total > 0) {
        return $getAttendances->total;
    } else {
        return 0;
    }
}

function getUnits($subject, $idno, $school_year) {
    $getsubjects = \App\GradeBasicEd::selectRaw('sum(units) as units')->where('idno', $idno)->where('school_year', $school_year)->where('report_card_grouping', $subject->subject_name)->first();

    return $getsubjects->units;
}

//get final rating for grouping
function getFinalRating($grade, $letter_grade_type) {
    $round = round($grade);
    $round2 = round($grade, 2);
    $final_letter_grade = \App\CtrTransmuLetter::where('grade', $round)->where('letter_grade_type', $letter_grade_type)->first();
    $letter = $final_letter_grade['letter_grade'];
    return "$letter($round2)";
}

//get letter grade transmutation
function getLetterGrade($grade, $letter_grade_type) {
    $round = round($grade);
    $final_letter_grade = \App\CtrTransmuLetter::where('grade', $round)->where('letter_grade_type', $letter_grade_type)->first();
    $letter = $final_letter_grade['letter_grade'];
    return "$letter";
}

function getPromotion($level) {
    switch ($level) {
        case "Pre Kinder":
            return "Kinder";
            break;
        case "Kinder":
            return "Grade 1";
            break;
        case "Grade 1":
            return "Grade 2";
            break;
        case "Grade 2":
            return "Grade 3";
            break;
        case "Grade 3":
            return "Grade 4";
            break;
        case "Grade 4":
            return "Grade 5";
            break;
        case "Grade 5":
            return "Grade 6";
            break;
        case "Grade 6":
            return "Grade 7";
            break;
        case "Grade 7":
            return "Grade 8";
            break;
        case "Grade 8":
            return "Grade 9";
            break;
        case "Grade 9":
            return "Grade 10";
            break;
        case "Grade 10":
            return "Grade 11";
            break;
        case "Grade 11":
            return "Grade 12";
            break;
        case "Grade 12":
            return "1st Year College";
            break;
    }
}
?>

<table width="50%">
    <tr>
        <td>Name</td><td colspan="5" style="border-bottom: 1px solid black">{{$user->getFullNameAttribute()}}</td>
    </tr>
    <tr>
        <td>Grade & Section</td><td colspan="2" style="border-bottom: 1px solid black">{{$status->level}} - {{$status->section}}</td>
        <td align="right">Strand</td><td colspan="2" align="center" style="border-bottom: 1px solid black">@if($status->strand == "PA") Arts and Design @else{{$status->strand}}@endif</td>
    </tr>
    <tr>
        <td>Class Adviser</td><td colspan="2" style="border-bottom: 1px solid black">{{$adviser->getFullNameAttribute()}}</td>
        <td align="right">School Year</td><td  colspan="2" align="center"style="border-bottom: 1px solid black">{{$status->school_year}}-{{$status->school_year+1}}, {{$status->period}}</td>
    </tr>
</table>
<br>
<table border = 1 cellpadding = 6 cellspacing =0 width="50%">
    <tr style = "font:bold">
        <td rowspan="2" align="center" width="60%">SUBJECTS</td>
        <td colspan="2" align="center">QUARTER</td>
        <td rowspan="2" align="center">FINAL<br>RATING</td>
        <td rowspan="2" align="center">REMARKS</td>
    </tr>
    <tr style = "font:bold">
        <td align="center">3</td>
        <td align="center">4</td>
    </tr>
    <?php
    $total_units = 0;
    $total_final_grade = 0;
    ?>

    @if(count($get_subjects_heads)>0)
    @foreach($get_subjects_heads as $subject_heads)
        <tr>
            <td colspan="5" style="background: darkblue; color: white; font:bold">{{$subject_heads->report_card_grouping}}</td>
        </tr>
        <?php $get_subjects = \App\GradeBasicEd::where('report_card_grouping', $subject_heads->report_card_grouping)->where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('subject_code', 'not like', "%SA%")->where('subject_code', 'not like', "%PEH%")->where('is_alpha',0)->orderBy('report_card_grouping', 'desc')->orderBy('sort_to', 'asc')->get(); ?>
        <?php $get_pe_2nd = \App\GradeBasicEd::where('report_card_grouping', $subject_heads->report_card_grouping)->where('idno', $idno)->where('school_year', $school_year)->where('period', "2nd Semester")->where('subject_code', 'like', "%PEH%")->where('is_alpha',0)->orderBy('report_card_grouping', 'desc')->orderBy('sort_to', 'asc')->get(); ?>
        <?php $get_pe_1st = \App\GradeBasicEd::where('report_card_grouping', $subject_heads->report_card_grouping)->where('idno', $idno)->where('school_year', $school_year)->where('period', "1st Semester")->where('subject_code', 'like', "%PEH%")->where('is_alpha',0)->orderBy('report_card_grouping', 'desc')->orderBy('sort_to', 'asc')->first(); ?>
        <?php $get_sa = \App\GradeBasicEd::where('report_card_grouping', $subject_heads->report_card_grouping)->where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('subject_name', 'like', "%Student Activit%")->orderBy('report_card_grouping', 'desc')->orderBy('sort_to', 'asc')->get(); ?>
        <?php $get_conduct = \App\GradeBasicEd::where('report_card_grouping', $subject_heads->report_card_grouping)->where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('is_alpha',1)->orderBy('report_card_grouping', 'desc')->orderBy('sort_to', 'asc')->get(); ?>
        
        @if(count($get_subjects)>0)
            @foreach($get_subjects as $subject)
            <?php $total_units += $subject->units; ?>
            <tr>
                <td>{{$subject->display_subject_code}}</td>
                <td align="center">@if($subject->is_alpha == 0){{$subject->third_remarks}}@else{{$subject->third_grading_letter}}@endif</td>
                <td align="center" style="font:10pt">
                @if($idno == "1920262")
                {{$subject->fourth_remarks}}
                @else
                    Pass
                @endif
                </td>
                <td align="center" style="font:10pt">Pass</td>
                <td align="center" style="font:10pt">Pass</td>

                @if($subject->units>0)
                <?php $total_final_grade += $subject->final_grade; ?>
                @endif
            </tr>
            @endforeach
        @endif
        
        @if(count($get_pe_2nd)>0)
            @foreach($get_pe_2nd as $subject)
            @if(count($get_pe_1st)>0)
                <?php $pe_average = ($subject->third_grading+$get_pe_1st->first_grading+$get_pe_1st->second_grading)/3; ?>
            @else
                <?php $pe_average = ($subject->first_grading+$subject->second_grading+$subject->third_grading)/3; ?>
            @endif
            <?php $total_units += $subject->units; ?>
            <tr>
                <td>{{$subject->display_subject_code}}</td>
                <td align="center">@if($subject->is_alpha == 0){{$subject->third_remarks}}@else{{$subject->third_grading_letter}}@endif </td>
                <td align="center" style="font:10pt">
                    
                @if($idno == "1920262")
                {{$subject->fourth_remarks}}
                @else
                    Pass
                @endif
                </td>
                <td align="center" style="font:10pt">Pass</td>
                <td align="center" style="font:10pt">Pass</td>

                @if($subject->units>0)
                <?php $total_final_grade += $pe_average; ?>
                @endif
            </tr>
            @endforeach
        @endif
        
        
        @if(count($get_sa)>0)
            @foreach($get_sa as $subject)
            <?php $total_units += $subject->units; ?>
            <tr>
                <td>{{$subject->display_subject_code}}</td>
                <td align="center">{{$subject->third_remarks}}</td>
                <td align="center" style="font:10pt"></td>
                <td align="center" style="font:10pt">{{$subject->third_remarks}}</td>
                <td align="center" style="font:10pt">Pass</td>

                @if($subject->units>0)
                <?php $total_final_grade += $subject->final_grade; ?>
                @endif
            </tr>
            @endforeach
        @endif
        
        @if(count($get_conduct)>0)
            @foreach($get_conduct as $subject)
            <?php $total_units += $subject->units; ?>
            <tr>
                <td>{{$subject->display_subject_code}}</td>
                <td align="center">{{$subject->third_grading_letter}}</td>
                <td align="center" style="font:10pt"></td>
                <td align="center" style="font:10pt">{{$subject->third_grading_letter}}</td>
                <td align="center" style="font:10pt">Pass</td>

                @if($subject->units>0)
                <?php $total_final_grade += $subject->final_grade; ?>
                @endif
            </tr>
            @endforeach
        @endif
        
    @endforeach
    @endif


    <tr>
        <td align="right" style = "font:bold" colspan="3">General Average for the First Semester:</td><td align="center" colspan="2"><strong>@if($get_first_sem_final_ave){{$get_first_sem_final_ave->final_letter_grade}}({{$get_first_sem_final_ave->final_grade}})@endif</strong></td>
    </tr>
    <tr>
        <td align="right" style = "font:bold" colspan="3">Second Semester MidTerm Grade Average:</td><td align="center" colspan="2"><strong>{{getLetterGrade(round($total_final_grade/$total_units,2),"SHS")}}({{round($total_final_grade/$total_units,3)}})</strong></td>
        <!--<td align="right" style = "font:bold" colspan="3">General Average for the Second Semester:</td><td align="center" colspan="2"><strong>{{round($total_final_grade,3)}}/{{$total_units}}</strong></td>-->
    </tr>
    <tr>
        <td align="right" style = "font:bold" colspan="3">General Average for the Whole School Year:</td><td align="center" colspan="2"><strong>
                @if($get_first_sem_final_ave)
                {{getLetterGrade(round(($get_first_sem_final_ave->final_grade+round($total_final_grade/$total_units,3))/2,3),'SHS')}}({{round(($get_first_sem_final_ave->final_grade+round($total_final_grade/$total_units,3))/2,3)}})
            @else
            {{getLetterGrade(round($total_final_grade/$total_units,3),'SHS')}}({{round($total_final_grade/$total_units,3)}})
            @endif</strong></td>
    </tr>
</table>
<div style="position:absolute; top:640px; bottom:0; left:0; right:0;">
    <table border = 1 cellpadding = 1 cellspacing =0 width="50%">
        <tr><td colspan="7" align="center"><span style="font-style: italic !important">
                "Due to the declaration of Enhanced Community Quarantine(ECQ) because of the COVID-19 Pandemic, 
                the grades for the second half of the second semester / 4th quarter are Pass or Fail."
            </span></td></tr>
        <tr>
            <td align="center" rowspan="2">ATTENDANCE</td>
            <td align="center" rowspan="2">Jan</td>
            <td align="center" rowspan="2">Feb</td>
            <td align="center" rowspan="2">Mar</td>
            <td align="center" rowspan="2">Apr</td>
            <td align="center" rowspan="2">May</td>
            <td align="center" style="font:7pt !important;" width="16%">Days of School</td>
        </tr>
        <tr>
            <td align="center">106</td>
        </tr><?php $total_absent = 0; ?>
        </tr><?php $totalab = 0; ?>
        <tr>
            <td align="center">Absences</td>
            <td align="center"><?php $total_absent += $totalab = getAttendances('01', $school_year, $idno, 'absences') ?>@if($totalab == 0)@else {{$totalab}}@endif</td>
            <td align="center"><?php $total_absent += $totalab = getAttendances('02', $school_year, $idno, 'absences') ?>@if($totalab == 0)@else {{$totalab}}@endif</td>
            <td align="center"><?php $total_absent += $totalab = getAttendances('03', $school_year, $idno, 'absences') ?>@if($totalab == 0)@else {{$totalab}}@endif</td>
            <td align="center"><?php $total_absent += $totalab = getAttendances('04', $school_year, $idno, 'absences') ?>@if($totalab == 0)@else {{$totalab}}@endif</td>
            <td align="center"><?php $total_absent += $totalab = getAttendances('05', $school_year, $idno, 'absences') ?>@if($totalab == 0)@else {{$totalab}}@endif</td>
            <td align="center" valign="top" rowspan="2"><span style="font:7pt !important;">Days Present</span><br>{{106-$total_absent}}</td>
        </tr>
        <tr>
            <td align="center">Tardiness</td>
            <td align="center"><?php $tardy = getAttendances('01', $school_year, $idno, 'tardiness') ?>@if($tardy!=0) {{$tardy}} @endif</td>
            <td align="center"><?php $tardy = getAttendances('02', $school_year, $idno, 'tardiness') ?>@if($tardy!=0) {{$tardy}} @endif</td>
            <td align="center"><?php $tardy = getAttendances('03', $school_year, $idno, 'tardiness') ?>@if($tardy!=0) {{$tardy}} @endif</td>
            <td align="center"><?php $tardy = getAttendances('04', $school_year, $idno, 'tardiness') ?>@if($tardy!=0) {{$tardy}} @endif</td>
            <td align="center"><?php $tardy = getAttendances('05', $school_year, $idno, 'tardiness') ?>@if($tardy!=0) {{$tardy}} @endif</td>
        </tr>
    </table>
</div>
<!--LEGEND-->
<div style="position:absolute; top:0px; bottom:0; left:540px; right:0;">
    LEGEND:
    <ul style="list-style: none;">
        <li><span>O(Outstanding)</span>.......................................<span>90% and 100%</span>
        <li><span>VS(Very Satisfactory)</span>............................<span>85% - 89%</span>
        <li><span>S(Satisfactory)</span>........................................<span>80% - 84%</span>
        <li><span>FS(Fairly Satisfactory)</span>...........................<span>75% - 79%</span>
        <li><span>D(Did Not Meet Expectations)</span>...............<span>74% and below</span>
    </ul>
</div>

<div style="position:absolute; top:140px; bottom:0; left:650px; right:0;">
<strong>Conduct & Student Activities</strong>
    <table>
        <tr class="legend"><td>O</td><td>-</td><td>Outstanding</td></tr>
        <tr class="legend"><td>HS</td><td>-</td><td>Highly Satisfactory</td></tr>
        <tr class="legend"><td>S</td><td>-</td><td>Satisfactory</td></tr>
        <tr class="legend"><td>MS</td><td>-</td><td>Moderately Satisfactory</td></tr>
        <tr class="legend"><td>NI</td><td>-</td><td>Need Improvement</td></tr>
        <tr class="legend"><td>U</td><td>-</td><td>Unsatisfactory</td></tr>
    </table>
</div>

<div style="position:absolute; top:285px; bottom:0; left:540px; right:0; font:12pt; text-align: justify">
    <h3>CERTIFICATE OF TRANSFER</h3>
    The bearer <strong>{{$user->getFullNameAttribute()}}</strong> was our student for school year 
    <strong>{{$status->school_year}}-{{$status->school_year+1}}</strong>.<br>
    She is eligible for transfer and should be admitted to <strong>{{getPromotion($status->level)}}</strong>.
    <br>
    <br>
    <strong>Sr. Mary Ignatius G. Vedua, r.a.</strong><br>
    Principal<br><br>
    June 8, 2020<br>
    Date
</div>

<div style="position:absolute; top:525px; bottom:0; left:540px; right:0; font:12pt; text-align: justify">
    <h3>CANCELLATION OF TRANSFER ELIGIBILITY</h3>
    Has been admitted to___________________________________<br>
    ____________________________________________________.
    <br>
    <br>
    <strong>Sr. Mary Ignatius G. Vedua, r.a.</strong><br>
    Principal<br><br>
    June 8, 2020<br>
    Date
</div>

