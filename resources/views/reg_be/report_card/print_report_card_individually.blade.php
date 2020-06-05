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
        return "";
    }
}

//get grades for grouping like mapeh and tle
function getGrades($subject, $idno, $school_year, $period) {
    $getsubjects = \App\GradeBasicEd::where('idno', $idno)->where('school_year', $school_year)->where('report_card_grouping', $subject->subject_name)->get();
    $final_grade = 0;
    foreach ($getsubjects as $get) {
        switch ($period) {
            case "1":
                if ($get->subject_code != "COMP1" && $get->subject_code != "COMP2" && $get->subject_code != "COMP3" && $get->subject_code != "COMP4" && $get->subject_code != "COMP5" && $get->subject_code != "COMP6" && $get->subject_code != "COMP7" && $get->subject_code != "COMP8" ) {
                    $final_grade += $get->first_grading * ($get->units);
                } else {
                    $final_grade += 100 * ($get->units);
                }
                break;
            case "2":
                if ($get->subject_code != "COMP1" && $get->subject_code != "COMP2" && $get->subject_code != "COMP3" && $get->subject_code != "COMP4" && $get->subject_code != "COMP5" && $get->subject_code != "COMP6" && $get->subject_code != "COMP7" && $get->subject_code != "COMP8" && $get->subject_code != "COMP9" && $get->subject_code != "COMP10") {
                    $final_grade += $get->second_grading * ($get->units);
                } else {
                    $final_grade += 100 * ($get->units);
                }
                break;
            case "3":
                $final_grade += $get->third_grading * ($get->units);
                break;
            case "4":
                $final_grade += $get->fourth_grading * ($get->units);
                break;
        }
    }
    return $final_grade;
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
        case "Grade 7":
            return "College";
            break;
    }
}
?>

<table width="50%">
    <tr>
        <td>Name</td><td colspan="3" style="border-bottom: 1px solid black">{{$user->getFullNameAttribute()}}</td>
    </tr>
    <tr>
        <td>Grade & Section</td><td style="border-bottom: 1px solid black">{{$status->level}} - {{$status->section}}</td><td align="right">School Year</td><td align="center"style="border-bottom: 1px solid black">{{$status->school_year}}-{{$status->school_year+1}}</td>
    </tr>
    <tr>
        <td>Class Adviser</td><td colspan="3" style="border-bottom: 1px solid black">{{$adviser->getFullNameAttribute()}}</td>
    </tr>
</table>
<br>
<table border = 1 cellpadding = 6 cellspacing =0 width="50%">
    <tr style = "background: darkblue; color: white; font:bold">
        <td rowspan="2" align="center" width="60%">LEARNING AREAS</td><td colspan="4" align="center">QUARTER</td><td rowspan="2" align="center">FINAL<br>RATING</td>
        @if($status->level == "Grade 7" || $status->level == "Grade 8" || $status->level == "Grade 9" || $status->level == "Grade 10")
        <td rowspan="2" align="center">REMARKS</td>
        @endif
    </tr>
    <tr style = "background: darkblue; color: white; font:bold">
        <td align="center">1</td>
        <td align="center">2</td>
        <td align="center">3</td>
        <td align="center">4</td>
    </tr>
    <?php
    $total_units = 0;
    $total_final_grade = 0;
    ?>
    @if(count($get_regular_subjects)>0)
    @foreach($get_regular_subjects as $subject)
    <?php $total_units += $subject->units; ?>
    <tr>
        <td>{{$subject->subject_name}}</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->first_remarks}}@else{{$subject->first_grading_letter}}@endif</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->second_remarks}}@else{{$subject->second_grading_letter}}@endif</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->third_remarks}}@else{{$subject->third_grading_letter}}@endif</td>
        <!--<td align="center">@if($subject->is_alpha == 0){{round($subject->fourth_grading,2)}}@else{{$subject->fourth_grading_letter}}@endif</td>-->
        <td align="center" style="font:10pt">Pass</td>

        @if($subject->final_grade != "")
        <td align="center">{{$subject->final_remarks}}({{$subject->final_grade}})</td>
        @if($status->level == "Grade 7" || $status->level == "Grade 8" || $status->level == "Grade 9" || $status->level == "Grade 10")
        <td align="center">Promoted</td>
        @endif
        @else
        <td></td>
        @if($status->level == "Grade 7" || $status->level == "Grade 8" || $status->level == "Grade 9" || $status->level == "Grade 10")
        <td></td>
        @endif
        @endif
        @if($subject->units>0)
        <?php $total_final_grade += $subject->final_grade; ?>
        @endif
    </tr>
    @endforeach
    @endif

    @if(count($get_group_subjects)>0)
    @foreach($get_group_subjects as $subject)
    <?php $total_units += $subject->units; ?>
    <tr>
        <td>{{$subject->subject_name}}</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->first_remarks}}@else{{$subject->first_grading_letter}}@endif</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->second_remarks}}@else{{$subject->second_grading_letter}}@endif</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->third_remarks}}@else{{$subject->third_grading_letter}}@endif</td>
        <!--<td align="center">@if($subject->is_alpha == 0){{round($subject->fourth_grading,2)}}@else{{$subject->fourth_grading_letter}}@endif</td>-->
        <td align="center" style="font:10pt">Pass</td>

        @if($subject->final_grade != "")
        <td align="center">{{$subject->final_remarks}}({{$subject->final_grade}})</td>
        @if($status->level == "Grade 7" || $status->level == "Grade 8" || $status->level == "Grade 9" || $status->level == "Grade 10")
        <td align="center">Promoted</td>
        @endif
        @else
        <td></td>
        @if($status->level == "Grade 7" || $status->level == "Grade 8" || $status->level == "Grade 9" || $status->level == "Grade 10")
        <td></td>
        @endif
        @endif
        @if($subject->units>0)
        <?php $total_final_grade += $subject->final_grade; ?>
        @endif
    </tr>
    @endforeach
    @endif

    @if(count($get_split_subjects)>0)
    @foreach($get_split_subjects as $subject)
    <?php $total_units += $subject->units; ?>
    <tr>
        <td>{{$subject->subject_name}}</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->first_remarks}}@else{{$subject->first_grading_letter}}@endif</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->second_remarks}}@else{{$subject->second_grading_letter}}@endif</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->third_remarks}}@else{{$subject->third_grading_letter}}@endif</td>
        <!--<td align="center">@if($subject->is_alpha == 0){{round($subject->fourth_grading,2)}}@else{{$subject->fourth_grading_letter}}@endif</td>-->
        @if(!fnmatch("SA[1,2,3,4,5,6,7,8,9,10]", $subject->group_code))<td align="center" style="font:10pt">Pass</td>
        @else<td align="center" style="font:10pt"></td>

        @endif

        @if($subject->final_grade != "")
        <td align="center">{{$subject->final_remarks}}({{$subject->final_grade}})</td>
        @if($status->level == "Grade 7" || $status->level == "Grade 8" || $status->level == "Grade 9" || $status->level == "Grade 10")
        <td align="center">Promoted</td>
        @endif
        @elseif($subject->final_remarks != "")
        <td align="center">{{$subject->final_remarks}}</td>
        @if($status->level == "Grade 7" || $status->level == "Grade 8" || $status->level == "Grade 9" || $status->level == "Grade 10")
        <td align="center">Promoted</td>
        @else
        <td></td>
        @if($status->level == "Grade 7" || $status->level == "Grade 8" || $status->level == "Grade 9" || $status->level == "Grade 10")
        <td></td>
        @endif
        @endif
        @if($subject->units>0)
        <?php $total_final_grade += $subject->final_grade; ?>
        @endif
    </tr>
    @endforeach
    @endif

    @if(count($get_grouping_subjects))
    <?php
    $grade1 = 0;
    $grade2 = 0;
    $grade3 = 0;
    ?>
    @foreach($get_grouping_subjects as $subject)
    <?php $total_units += getUnits($subject,$idno,$school_year); ?>
    <tr>
        <td>{{$subject->subject_name}}</td>
        <td align="center">{{getLetterGrade($grade1=getGrades($subject,$idno,$school_year,'1'),$subject->letter_grade_type)}}</td>
        <td align="center">{{getLetterGrade($grade2=getGrades($subject,$idno,$school_year,'2'),$subject->letter_grade_type)}}</td>
        <td align="center">{{getLetterGrade($grade3=getGrades($subject,$idno,$school_year,'3'),$subject->letter_grade_type)}}</td>
        <!--<td align="center">{{getGrades($subject,$idno,$school_year,'4')}}</td>-->
        <td align="center" style="font:10pt">Pass</td>
        
        
<?php $grade = ($grade1 + $grade2 + $grade3 + 100) / 4; ?>
        
        
        <td align="center">{{getFinalRating($grade, $subject->letter_grade_type)}}</td>
        @if($status->level == "Grade 7" || $status->level == "Grade 8" || $status->level == "Grade 9" || $status->level == "Grade 10")
        <td align="center">Promoted</td>
        @endif
        @if($total_units>0)
<?php $total_final_grade += $grade; ?>
        @endif
    </tr>
    @if($subject->subject_name != "Technology and Livelihood Education" && $subject->subject_name != "Edukasyong Pantahanan At Pangkabuhayan")
<?php $get_group_split_subjects = \App\GradeBasicEd::where('idno', $idno)->where('school_year', $school_year)->where('subject_type', '>', 2)->orderBy('sort_to', 'asc')->where('report_card_grouping', $subject->subject_name)->get(); ?>
    @if(count($get_group_split_subjects)>0)
    @foreach($get_group_split_subjects as $splitsubject)
    <tr>
        <td>{{$splitsubject->subject_name}}</td>
        <td align="center">@if($splitsubject->is_alpha == 0){{$splitsubject->first_remarks}}@else{{$splitsubject->first_grading_letter}}@endif</td>
        <td align="center">@if($splitsubject->is_alpha == 0){{$splitsubject->second_remarks}}@else{{$splitsubject->second_grading_letter}}@endif</td>
        <td align="center">@if($splitsubject->is_alpha == 0){{$splitsubject->third_remarks}}@else{{$splitsubject->third_grading_letter}}@endif</td>
        <!--<td align="center">@if($subject->is_alpha == 0){{round($subject->fourth_grading,2)}}@else{{$subject->fourth_grading_letter}}@endif</td>-->
        <td align="center" style="font:10pt">Pass</td>

        @if($splitsubject->final_grade != "")
        <td align="center">{{$splitsubject->final_remarks}}({{$splitsubject->final_grade}})</td>
        @if($status->level == "Grade 7" || $status->level == "Grade 8" || $status->level == "Grade 9" || $status->level == "Grade 10")
        <td align="center">Promoted</td>
        @endif
        @else
        <td></td>
        @if($status->level == "Grade 7" || $status->level == "Grade 8" || $status->level == "Grade 9" || $status->level == "Grade 10")
        <td></td>
        @endif
        @endif
    </tr>
    @endforeach
    @endif
    @endif

    @endforeach
    @endif


    @if(count($get_regular_alpha_subjects)>0)
    @foreach($get_regular_alpha_subjects as $subject)
<?php $total_units += $subject->units; ?>
    <tr>
        <td>{{$subject->subject_name}}</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->first_remarks}}@else{{$subject->first_grading_letter}}@endif</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->second_remarks}}@else{{$subject->second_grading_letter}}@endif</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->third_remarks}}@else{{$subject->third_grading_letter}}@endif</td>
        <!--<td align="center">@if($subject->is_alpha == 0){{round($subject->fourth_grading,2)}}@else{{$subject->fourth_grading_letter}}@endif</td>-->
        <td align="center" style="font:10pt"></td>

        @if($subject->final_grade_letter != "")
        <td align="center">{{$subject->final_grade_letter}}</td>
        @if($status->level == "Grade 7" || $status->level == "Grade 8" || $status->level == "Grade 9" || $status->level == "Grade 10")
        <td align="center">Promoted</td>
        @endif
        @else
        <td></td>
        @if($status->level == "Grade 7" || $status->level == "Grade 8" || $status->level == "Grade 9" || $status->level == "Grade 10")
        <td></td>
        @endif
        @endif
        @if($subject->units>0)
<?php $total_final_grade += $subject->final_grade; ?>
        @endif
    </tr>
    @endforeach
    @endif

    <tr>
        @if($status->level == "Grade 7" || $status->level == "Grade 8" || $status->level == "Grade 9" || $status->level == "Grade 10")
        <td style = "background: darkblue; color: white; font:bold" colspan="5">GENERAL AVERAGE</td><td align="center" colspan="2"><strong>{{getLetterGrade(round($total_final_grade/$total_units,2),"Regular")}}({{round($total_final_grade/$total_units,2)}})</strong></td>
        @else
        <td style = "background: darkblue; color: white; font:bold" colspan="5">GENERAL AVERAGE</td><td align="center"><strong>{{getLetterGrade(round($total_final_grade/$total_units,2),"Regular")}}({{round($total_final_grade/$total_units,2)}})</strong></td>
        @endif
    </tr>
</table>
<br>

<table border = 1 cellpadding = 2 cellspacing =0 width="30%">
    <tr><td><span style="font-style: italic !important">
                "Due to the declaration of Enhanced Community Quarantine(ECQ) because of the COVID-19 Pandemic, 
                the grades for the second half of the second semester / 4th quarter are Pass or Fail."
            </span></td></tr>
</table>
<br>
<div style="position:absolute; top:660px; bottom:0; left:0; right:0;">
    <table border = 1 cellpadding = 1 cellspacing =0 width="50%">
        <tr>
            <td align="center" rowspan="2">ATTENDANCE</td>
            <td align="center" rowspan="2">Aug</td>
            <td align="center" rowspan="2">Sep</td>
            <td align="center" rowspan="2">Oct</td>
            <td align="center" rowspan="2">Nov</td>
            <td align="center" rowspan="2">Dec</td>
            <td align="center" rowspan="2">Jan</td>
            <td align="center" rowspan="2">Feb</td>
            <td align="center" rowspan="2">Mar</td>
            <td align="center" rowspan="2">Apr</td>
            <td align="center" rowspan="2">May</td>
            <td align="center" style="font:7pt !important;" width="16%">Days of School</td>
        </tr>
        <tr>
            <td align="center">209</td>
        </tr>
        <tr>
            <td align="center">Absences</td>
            <td align="center">{{getAttendances('08',$school_year,$idno,'absences')}}</td>
            <td align="center">{{getAttendances('09',$school_year,$idno,'absences')}}</td>
            <td align="center">{{getAttendances('10',$school_year,$idno,'absences')}}</td>
            <td align="center">{{getAttendances('11',$school_year,$idno,'absences')}}</td>
            <td align="center">{{getAttendances('12',$school_year,$idno,'absences')}}</td>
            <td align="center">{{getAttendances('01',$school_year,$idno,'absences')}}</td>
            <td align="center">{{getAttendances('02',$school_year,$idno,'absences')}}</td>
            <td align="center">{{getAttendances('03',$school_year,$idno,'absences')}}</td>
            <td align="center">{{getAttendances('04',$school_year,$idno,'absences')}}</td>
            <td align="center">{{getAttendances('05',$school_year,$idno,'absences')}}</td>
            <td align="center" valign="top" rowspan="2"><span style="font:7pt !important;">Days Present</span><br>199</td>
        </tr>
        <tr>
            <td align="center">Tardiness</td>
            <td align="center">{{getAttendances('08',$school_year,$idno,'tardiness')}}</td>
            <td align="center">{{getAttendances('09',$school_year,$idno,'tardiness')}}</td>
            <td align="center">{{getAttendances('10',$school_year,$idno,'tardiness')}}</td>
            <td align="center">{{getAttendances('11',$school_year,$idno,'tardiness')}}</td>
            <td align="center">{{getAttendances('12',$school_year,$idno,'tardiness')}}</td>
            <td align="center">{{getAttendances('01',$school_year,$idno,'tardiness')}}</td>
            <td align="center">{{getAttendances('02',$school_year,$idno,'tardiness')}}</td>
            <td align="center">{{getAttendances('03',$school_year,$idno,'tardiness')}}</td>
            <td align="center">{{getAttendances('04',$school_year,$idno,'tardiness')}}</td>
            <td align="center">{{getAttendances('05',$school_year,$idno,'tardiness')}}</td>
        </tr>
    </table>
</div>
<!--LEGEND-->
<div style="position:absolute; top:0px; bottom:0; left:540px; right:0;">
    LEGEND:
    <ul style="list-style: none;">
        <li><span>A(Advance)</span>....................................................<span>90% and more</span>
        <li><span>P(Proficient)</span>...................................................<span>85% - 89%</span>
        <li><span>AP(Advance Proficiency)</span>..............................<span>80% - 84%</span>
        <li><span>D(Developing)</span>...............................................<span>75% - 79</span>
        <li><span>B(Beginning)</span>.................................................<span>74% and below</span>
    </ul>
</div>

<div style="position:absolute; top:140px; bottom:0; left:650px; right:0;">
    <table>
        <tr class="legend"><td>O</td><td>-</td><td>Outstanding</td></tr>
        <tr class="legend"><td>HS</td><td>-</td><td>Highly Satisfactory</td></tr>
        <tr class="legend"><td>S</td><td>-</td><td>Satisfactory</td></tr>
        <tr class="legend"><td>MS</td><td>-</td><td>Moderately Satisfactory</td></tr>
        <tr class="legend"><td>NI</td><td>-</td><td>Need Improvement</td></tr>
        <tr class="legend"><td>U</td><td>-</td><td>Unsatisfactory</td></tr>
    </table>
</div>

<div style="position:absolute; top:270px; bottom:0; left:540px; right:0; font:12pt; text-align: justify">
    <h3>CERTIFICATE OF TRANSFER</h3>
    The bearer <strong>{{$user->getFullNameAttribute()}}</strong> was our student for school year 
    <strong>{{$status->school_year}}-{{$status->school_year+1}}</strong>.<br>
    She is eligible for transfer and should be admitted to <strong>{{getPromotion($status->level)}}</strong>.
    <br>
    <br>
    <br>
    <strong>Sr. Mary Ignatius G. Vedua, r.a.</strong><br>
    Principal<br><br>
    {{date('M j, Y')}}<br>
    Date
</div>

<div style="position:absolute; top:520px; bottom:0; left:540px; right:0; font:12pt; text-align: justify">
    <h3>CANCELLATION OF TRANSFER ELIGIBILITY</h3>
    Has been admitted to___________________________________<br>
    ____________________________________________________.
    <br>
    <br>
    <strong>Sr. Mary Ignatius G. Vedua, r.a.</strong><br>
    Principal<br><br>
    {{date('M j, Y')}}<br>
    Date
</div>
