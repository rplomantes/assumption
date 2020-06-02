<?php
function getAttendances($month,$school_year,$idno,$type){
    if($type == 'absences'){
        $getAttendances = \App\Absent::SelectRaw('sum(is_absent)as total')->where('school_year',$school_year)->where('idno', $idno)->where('date', 'like',"%-$month-%")->first();
    }else{
        $getAttendances = \App\Absent::SelectRaw('sum(is_tardy)as total')->where('school_year',$school_year)->where('idno', $idno)->where('date', 'like',"%-$month-%")->first();
    }
    if($getAttendances->total > 0){
        return $getAttendances->total;
    }else{
        return "";
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
        <td rowspan="2" align="center" width="50%">LEARNING AREAS</td><td colspan="4" align="center">QUARTER</td><td rowspan="2" align="center">FINAL<br>RATING</td>
    </tr>
    <tr style = "background: darkblue; color: white; font:bold">
        <td align="center">1</td>
        <td align="center">2</td>
        <td align="center">3</td>
        <td align="center">4</td>
    </tr>
    <?php $total_units = 0; $total_final_grade=0; ?>
    @foreach($get_subjects as $subject)
    <?php $total_units += $subject->units; ?>
    <tr>
        <td>{{$subject->subject_name}}</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->first_remarks}}@else{{$subject->first_grading_letter}}@endif</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->second_remarks}}@else{{$subject->second_grading_letter}}@endif</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->third_remarks}}@else{{$subject->third_grading_letter}}@endif</td>
        <td align="center">@if($subject->is_alpha == 0){{$subject->fourth_remarks}}@else{{$subject->fourth_grading_letter}}@endif</td>
        
        <td align="center">
            @if($subject->is_alpha == 0)
                {{$subject->final_remarks}}({{$subject->final_grade}})
            @else 
            @endif
        </td>
        <?php $total_final_grade += $subject->final_grade; ?>
    </tr>

    @endforeach
    <tr>
        <td style = "background: darkblue; color: white; font:bold" colspan="5">GENERAL AVERAGE</td><td align="center"><strong>({{round($total_final_grade/$total_units,2)}})</strong></td>
    </tr>
</table>
<br>
<table border = 1 cellpadding = 1 cellspacing =0 width="50%">
    <tr>
        <td align="center">ATTENDANCE</td>
        <td align="center">Aug</td>
        <td align="center">Sep</td>
        <td align="center">Oct</td>
        <td align="center">Nov</td>
        <td align="center">Dec</td>
        <td align="center">Jan</td>
        <td align="center">Feb</td>
        <td align="center">Mar</td>
        <td align="center">Apr</td>
        <td align="center">May</td>
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