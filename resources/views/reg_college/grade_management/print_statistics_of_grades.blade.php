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

function getCount($course_code, $school_year, $period, $grade) {
    if ($grade == "TOTAL") {
        $count = \App\GradeCollege::distinct()->where('course_code', $course_code)->where('school_year', $school_year)->where('period', $period)->get(array('idno'));
    } else {
        $count = \App\GradeCollege::where('course_code', $course_code)->where('school_year', $school_year)->where('period', $period)->where('finals', $grade)->get();
    }
    if(count($count)==0){
        return "";
    }else{
        return (count($count));
    }
}
?>
<div>    
    <div style='float: left; margin-left: 150px;'><img src="{{public_path('/images/assumption-logo.png')}}"></div>
    <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><br><b>STATISTICS OF GRADES</b></div>
</div>
<div>
    <table class='table' border="1" width="100%" cellspacing='0' cellpadding='0' style='margin-top: 145px;'>
        <tr>
            <td class='no-border td' width='4%'>Academic Year:</td>
            <td class='underline td' width='30%'>&nbsp;&nbsp;&nbsp;{{$school_year}}-{{$school_year+1}}, {{$period}}</td>
        </tr>
    </table>
    @if (count($subjects)>0)
    @foreach($subjects as $subject)
    <label>{{$subject->course_code}} - {{$subject->course_name}}</label>
    <table class='table' border="1" width="100%" cellspacing='0' cellpadding='0'>
        <tr>
            <td align='center'>PASSED</td>
            <td align='center'>1.00</td>
            <td align='center'>1.20</td>
            <td align='center'>1.50</td>
            <td align='center'>1.70</td>
            <td align='center'>2.00</td>
            <td align='center'>2.20</td>
            <td align='center'>2.50</td>
            <td align='center'>2.70</td>
            <td align='center'>3.00</td>
            <td align='center'>3.50</td>
            <td align='center'>4.00</td>
            <td align='center'>FA</td>
            <td align='center'>INC</td>
            <td align='center'>NA</td>
            <td align='center'>NG</td>
            <td align='center'>UD</td>
            <td align='center'>W</td>
            <td align='center'>AUDIT</td>
            <td align='center'><strong>TOTAL</strong></td>
        </tr>
        <tr>
            <td align='center'>{{getCount($subject->course_code,$school_year,$period,"PASSED")}}</td>
            <td align='center'>{{getCount($subject->course_code,$school_year,$period,"1.00")}}</td>
            <td align='center'>{{getCount($subject->course_code,$school_year,$period,"1.20")}}</td>
            <td align='center'>{{getCount($subject->course_code,$school_year,$period,"1.50")}}</td>
            <td align='center'>{{getCount($subject->course_code,$school_year,$period,"1.70")}}</td>
            <td align='center'>{{getCount($subject->course_code,$school_year,$period,"2.00")}}</td>
            <td align='center'>{{getCount($subject->course_code,$school_year,$period,"2.20")}}</td>
            <td align='center'>{{getCount($subject->course_code,$school_year,$period,"2.50")}}</td>
            <td align='center'>{{getCount($subject->course_code,$school_year,$period,"2.70")}}</td>
            <td align='center'>{{getCount($subject->course_code,$school_year,$period,"3.00")}}</td>
            <td align='center'>{{getCount($subject->course_code,$school_year,$period,"3.50")}}</td>
            <td align='center'>{{getCount($subject->course_code,$school_year,$period,"4.00")}}</td>
            <td align='center'>{{getCount($subject->course_code,$school_year,$period,"FA")}}</td>
            <td align='center'>{{getCount($subject->course_code,$school_year,$period,"INC")}}</td>
            <td align='center'>{{getCount($subject->course_code,$school_year,$period,"NA")}}</td>
            <td align='center'>{{getCount($subject->course_code,$school_year,$period,"NG")}}</td>
            <td align='center'>{{getCount($subject->course_code,$school_year,$period,"UD")}}</td>
            <td align='center'>{{getCount($subject->course_code,$school_year,$period,"W")}}</td>
            <td align='center'>{{getCount($subject->course_code,$school_year,$period,"AUDIT")}}</td>
            <td align='center'><strong>{{getCount($subject->course_code,$school_year,$period,"TOTAL")}}</strong></td>
        </tr>
    </table><br>
    @endforeach
    @endif
</div>