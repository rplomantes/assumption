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


function getPromotion($level) {
    switch ($level) {
        case "Pre-Kinder":
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
<style type="text/css">
    @font-face {
        font-family: cambria_bold;
        src: "{{public_path('/font/Cambria_Bold.ttf')}}"
    }
    img {
        display: block;
        max-width:1.5cm;
        max-height:1.5cm;
        width: auto;
        height: auto;
        margin-top: 0.25px;
    }

    #table2 {
        border-collapse: collapse;
    }

    #table2, #th2, #td2{
        border: 1.5px solid black;
        margin: 5px;
        font-size: 12px
    }

    .td5{
        border-left:1.5px solid;
    }    
    th6{
        border-right:1.5px solid;
    }    

    #table, #th, #td {
        font-size: 11px
    }
    body{
        margin-left: 40px;
        margin-right: 40px;
    }
    th{
        font-size: 13px;
    }
    td{
        padding-bottom: 0.3em;
        padding-top: 0px;
    }
    .absolute{
        position: initial;
    }
    .table-margin1{
        padding-left: 40px;
    }
    .table-margin2{
        padding-right: 40px;
    }
    .td-margin{
        padding-left: 10px;
        padding-top: 5px;
    }
    p{
        font-size: 10pt;
        font-family: sans-serif;
        font-weight: bold;
        text-align:center;
    }
    .table-margin3{
        font-size: 10pt;

    }
    div.tbl-margin3{
        border-bottom:1px solid black;
        text-align:center;

    }
    .table-margin4{
        font-size: 10pt;

    }
    div.tbl-margin4{
        border-bottom:1px solid black;
        text-align:center;

    }
    #school_year{
        font-size: 10pt;
        float: right;
        /*margin-right: 15px;*/
    }
    #schoolname{
        font-size: 18pt;
        /*font-weight: bold;*/
        margin-top: -10px;
        /*font-family: Cambria;*/
    }
    #address{
        font-size: 10.5pt;

    }
    #report_card{
        /*font-family: cambria_bold;*/
        font-size: 16pt;
        /*font-weight: bold;*/
        float: right;
    }
    #school_year2{
        font-size: 10pt;
        float: right;
        font-family: Cambria;
        /*margin-right: -1.7cm;*/
        /*padding-top: 0.6cm;*/
    }
</style> 
<table border="0" width="100%">
    <tr>
        <td style='font-family: Cambria;'>
            <img style="margin-left: -.5cm" src="{{public_path('/images/assumption-logo.png')}}">
            <div style="margin-top:-60px;margin-left: 1.3cm;">
                <span id="schoolname">Assumption College</span><br>
                <span id="address">San Lorenzo Village, Makati City 1223</span>
            </div>
        </td>
        <td style='font-family: Cambria;' >
            <span id="report_card" style="margin-right:6px">REPORT CARD</span><br><br>
            <span id="school_year2" style="margin-top:-3px;margin-left:-5px" >School Year {{$school_year}} - {{$school_year + 1}}</span>
        </td>
    </tr>
</table>

<table border="0" style="width:100%; margin-top: 1em; font-family: sans-serif" class="table table-bordered table-striped"  border="0">
    <thead>
        <tr align="left">
            <th width="60%">Name: {{$user->getFullNameAttribute()}}</th>
            <th width="40%" align="right"><span  style="margin-right: 10px">Adviser: {{$adviser->getFullNameAttribute()}}</span></th>
        </tr>
        <tr align="left">
            <th width="15%">Level/Section: {{$status->level}} - {{$status->section}} </th>
        </tr>
    </thead>
</table>
<hr>

<table align="center"  width="100%" id="table" border="0" style="font-family: sans-serif">
    <tr>
        <th align="center"><u>LEGEND</u></th>
    </tr>
    <tr>
        <td align="center"><strong>O - Observed<br>NO - Not Observed</strong></td>
    </tr>
</table>
@foreach($records->groupBy('group_code') as $consRecord=>$groups)
<table style="font-family: sans-serif; font-size: 13px; margin-top: 5px">
    <tr><th>{{$consRecord}}</th></tr>
</table>
<table id="table2" width="100%" border="" style="font-family: sans-serif; margin-top: -4px">
    <tr>
        <td id="th2" width="75%"><b>Learning Indicators</b></td>
        <td id="th2" align="center"><b>1st Evaluation</b></td>
        <td id="th2" align="center"><b>2nd Evaluation</b></td>
        <td id="th2" align="center"><b>3rd Evaluation</b></td>
    </tr>    
    @foreach($groups as $group)
    <tr>
        <td id="th2" >{{$group->subject_name}}</td>
        <td id="th2" align="center">{{$group->qtr2}}</td>
        <td id="th2" align="center">{{$group->qtr3}}</td>
        <td id="th2" align="center">{{$group->qtr4}}</td>
    </tr>
    @endforeach
</table>
@endforeach

<hr>
<table id="table2" border = 1 cellpadding = 1 cellspacing =0 width="100%" style="font-family: sans-serif; font-size: 13px; margin-top: 5px">
    <tr>
        <th id="th2" align="center" rowspan="2">ATTENDANCE</th>
        <th id="th2" align="center" rowspan="2">Aug</th>
        <th id="th2" align="center" rowspan="2">Sep</th>
        <th id="th2" align="center" rowspan="2">Oct</th>
        <th id="th2" align="center" rowspan="2">Nov</th>
        <th id="th2" align="center" rowspan="2">Dec</th>
        <th id="th2" align="center" rowspan="2">Jan</th>
        <th id="th2" align="center" rowspan="2">Feb</th>
        <th id="th2" align="center" rowspan="2">Mar</th>
        <th id="th2" align="center" rowspan="2">Apr</th>
        <th id="th2" align="center" rowspan="2">May</th>
        <th id="th2" align="center" style="font:7pt !important;" width="16%">Days of School</th>
    </tr>
    <tr>
        <td id="th2" align="center">206</td>
    </tr><?php $total_absent = 0; ?>
</tr><?php $totalab = 0; ?>
<tr>
    <td align="center">Absences</td>
    <td align="center"><?php $total_absent += $totalab = getAttendances('08', $school_year, $idno, 'absences') ?>@if($totalab == 0)@else {{$totalab}}@endif</td>
    <td align="center"><?php $total_absent += $totalab = getAttendances('09', $school_year, $idno, 'absences') ?>@if($totalab == 0)@else {{$totalab}}@endif</td>
    <td align="center"><?php $total_absent += $totalab = getAttendances('10', $school_year, $idno, 'absences') ?>@if($totalab == 0)@else {{$totalab}}@endif</td>
    <td align="center"><?php $total_absent += $totalab = getAttendances('11', $school_year, $idno, 'absences') ?>@if($totalab == 0)@else {{$totalab}}@endif</td>
    <td align="center"><?php $total_absent += $totalab = getAttendances('12', $school_year, $idno, 'absences') ?>@if($totalab == 0)@else {{$totalab}}@endif</td>
    <td align="center"><?php $total_absent += $totalab = getAttendances('01', $school_year, $idno, 'absences') ?>@if($totalab == 0)@else {{$totalab}}@endif</td>
    <td align="center"><?php $total_absent += $totalab = getAttendances('02', $school_year, $idno, 'absences') ?>@if($totalab == 0)@else {{$totalab}}@endif</td>
    <td align="center"><?php $total_absent += $totalab = getAttendances('03', $school_year, $idno, 'absences') ?>@if($totalab == 0)@else {{$totalab}}@endif</td>
    <td align="center"><?php $total_absent += $totalab = getAttendances('04', $school_year, $idno, 'absences') ?>@if($totalab == 0)@else {{$totalab}}@endif</td>
    <td align="center"><?php $total_absent += $totalab = getAttendances('05', $school_year, $idno, 'absences') ?>@if($totalab == 0)@else {{$totalab}}@endif</td>
    <td align="center" valign="top" rowspan="2"><span style="font:7pt !important;">Days Present</span><br>{{206-$total_absent}}</td>
</tr>
<tr>
    <td align="center">Tardiness</td>
    <td align="center"><?php $tardy = getAttendances('08', $school_year, $idno, 'tardiness') ?>@if($tardy!=0) {{$tardy}} @endif</td>
    <td align="center"><?php $tardy = getAttendances('09', $school_year, $idno, 'tardiness') ?>@if($tardy!=0) {{$tardy}} @endif</td>
    <td align="center"><?php $tardy = getAttendances('10', $school_year, $idno, 'tardiness') ?>@if($tardy!=0) {{$tardy}} @endif</td>
    <td align="center"><?php $tardy = getAttendances('11', $school_year, $idno, 'tardiness') ?>@if($tardy!=0) {{$tardy}} @endif</td>
    <td align="center"><?php $tardy = getAttendances('12', $school_year, $idno, 'tardiness') ?>@if($tardy!=0) {{$tardy}} @endif</td>
    <td align="center"><?php $tardy = getAttendances('01', $school_year, $idno, 'tardiness') ?>@if($tardy!=0) {{$tardy}} @endif</td>
    <td align="center"><?php $tardy = getAttendances('02', $school_year, $idno, 'tardiness') ?>@if($tardy!=0) {{$tardy}} @endif</td>
    <td align="center"><?php $tardy = getAttendances('03', $school_year, $idno, 'tardiness') ?>@if($tardy!=0) {{$tardy}} @endif</td>
    <td align="center"><?php $tardy = getAttendances('04', $school_year, $idno, 'tardiness') ?>@if($tardy!=0) {{$tardy}} @endif</td>
    <td align="center"><?php $tardy = getAttendances('05', $school_year, $idno, 'tardiness') ?>@if($tardy!=0) {{$tardy}} @endif</td>
</tr>
</table>
<br>
<br>
<div style="text-align: justify; font-family: sans-serif; font-size: 13px; margin-top: 5px">
    <strong>CERTIFICATE OF TRANSFER</strong><br>
<!--    The bearer <strong>{{$user->getFullNameAttribute()}}</strong> was our student for school year 
    <strong>{{$status->school_year}}-{{$status->school_year+1}}</strong>.<br>
    She is eligible for transfer and should be admitted to <strong>{{getPromotion($status->level)}}</strong>.-->
    
    
    The bearer <strong>{{$user->getFullNameAttribute()}}</strong> was our student for school year 
    _________________.<br>
    She is eligible for transfer and should be admitted to _______________.
    <br>
    <br>
    <br>
    <strong>Sr. Mary Ignatius G. Vedua, r.a.</strong><br>
    Principal<br><br>
    <!--June 8, 2020-->
    <i>dd/mm/yyyy</i>
    <br>
    Date
</div>
<br>
<hr>
<br>
<div style="text-align: justify; font-family: sans-serif; font-size: 13px; margin-top: 5px">
    <strong>CANCELLATION OF TRANSFER ELIGIBILITY</strong><br>
    Has been admitted to _____________________________________________________________________<br>
    ____________________________________________________.
    <br>
    <br>
    <strong>Sr. Mary Ignatius G. Vedua, r.a.</strong><br>
    Principal<br><br>
    <!--June 8, 2020-->
    <i>dd/mm/yyyy</i>
    <br>
    Date
</div>
<!--<br>
<table align="center"  width="100%" id="table" border="0" style="font-family: sans-serif">
    <tr>
        <td align="center" style="border-top: dotted black 1px"></td>
    </tr>
    <tr>
        <th align="center"><u>LEGEND</u></th>
    </tr>
    <tr>
        <td align="center">O - Observed<br>NO - Not Observed</td>
    </tr>
</table>-->