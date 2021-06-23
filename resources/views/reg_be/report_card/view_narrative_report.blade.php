<?php
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
</style>
<div>    
    <div style='float: left; margin-left: 100px;'><img src="{{public_path('/images/assumption-logo.png')}}"></div>
    <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village, Makati City</small>
        <br><br><b>Basic Education Division</b><br>
        First Evaluation Narrative Report<br>S.Y. {{$school_year}} - {{$school_year + 1}}</div>
</div>

<div style="position:absolute; top:180px; bottom:0; left:50px; right:0;">
    <table width="40" height="40" border="1" cellpadding="30" cellspacing =0>
        <tr>
            <td align="center">Picture of Student</td>
        </tr>
    </table>
</div>
<div style="position:absolute; top:200px; bottom:0; left:170px; right:0;">
    <table width="100%">
        <tr>
            <td width="30%">Name</td><td style="border-bottom: 1px solid black">{{$user->getFullNameAttribute()}}</td>
        </tr>
        <tr>
            <td>Grade & Section</td><td style="border-bottom: 1px solid black">{{$status->level}} - {{$status->section}}</td>
        </tr>
    </table>
</div>
<div style="position:absolute; top:320px; bottom: 0; left:0; right:0;text-align: justify; font:10pt">
    @if(count($narrative_report)>0)
    {!!nl2br($narrative_report->narrative)!!}
    @endif
</div>

@if($school_year != 2019)
<div style='position:absolute; top:500px; bottom: 0; left:0; right:0;'>

    <table width="100%">     
        <tr>
            <td align="center" width="50%">@if($adviser){{$adviser->getFullNameAttribute()}} @endif</td>
            <td align="center">Añonuevo, Sheryll V.</td>
        </tr>

        <tr>
            <td align="center">Homeroom Teacher</td>
            <td align="center">Pre School Team Leader</td>
        </tr>
    </table>
@else
<div style='position:absolute; top:650px; bottom: 0; left:0; right:0;'>

    <table width="100%">     
        <tr>
            <td align="center" width="50%">@if($adviser){{$adviser->getFullNameAttribute()}} @endif</td>
            <td align="center">Añonuevo, Sheryll V.</td>
        </tr>

        <tr>
            <td align="center">Homeroom Teacher</td>
            <td align="center">Pre School Team Leader</td>
        </tr>
    </table>
    <br>
    <table width="100%">
        <tr>
            <td align="center"><strong><br>Sr. Mary Ignatius G. Vedua, ra</strong></td>
        </tr>
        <tr>
            <td align="center">Principal</td>
        </tr>
    </table>  
    <br>
    <br>
    <div style="font:10pt">
    <strong>CERTIFICATE OF TRANSFER</strong><br>
    The bearer <strong>{{$user->getFullNameAttribute()}}</strong> was our student for school year 
    <strong>{{$status->school_year}}-{{$status->school_year+1}}</strong>.<br>
    She is eligible for transfer and should be admitted to <strong>{{getPromotion($status->level)}}</strong>.
    
    <br>
    <br>
    <br>
    <p>Date: June 8, 2020<p>Not valid without</p>
    <p>School Seal</p>
    </div>
@endif