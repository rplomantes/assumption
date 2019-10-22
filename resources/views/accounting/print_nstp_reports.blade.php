<?php
$number = 1;
$total_amount = 0;

function getTF($level, $lec) {
    $per_unit = \App\CtrCollegeTuitionFee::where('level', $level)->first()->per_unit;
    $amount = $per_unit * ($lec / 2);
    return $amount;
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
    .table, .th, .td {       
        border-collapse: collapse;
        font: 6pt;
    }

</style>
<div>    
    <div style='float: left; margin-left: 135px;'><img src="{{public_path('/images/assumption-logo.png')}}"></div>
    <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small></div>
</div>
<div>
    <table style='margin-top:120px' width="100%">
        <thead>
            <tr>
                <td align='center'><b>LIST OF ENROLLED IN NSTP</b></td>
            </tr>

            <tr>
                <td align='center'><b>A.Y. {{$request->school_year}} - {{$request->school_year + 1}}, {{$request->period}} </b></td>
            </tr>
        </thead>
    </table>    
    <table class='table' border="1" width="100%">
        <tr>
            <td>#</td>
            <td>ID Number</td>
            <td>Name</td>
            <td>Level</td>
            <td>Status</td>
            <td>Course Code</td>
            <td>Units</td>
            <td>Amount</td>
        </tr>
        @foreach($students as $student) 
        <?php $user = \App\User::where('idno', $student->idno)->first(); ?>
        <?php $status = \App\CollegeLevel::where('idno', $student->idno)->where('school_year',$request->school_year)->where('period', $request->period)->first(); ?>
        <?php $info = \App\StudentInfo::where('idno', $student->idno)->first(); ?>
        <tr>
            <td>{{$number++}}.</td>
            <td>{{strtoupper($student->idno)}}</td>
            <td>{{strtoupper($user->lastname)}}, {{strtoupper($user->firstname)}} {{strtoupper($user->middlename)}}</td>
            <td>{{$status->level}}</td>
            <td>@if($status->status == 3) Enrolled @elseif($status->status == 4) Dropped/Withdrawn @endif</td>
            <td>{{$student->course_code}}</td>
            <td>{{$student->lec}}</td>
            <td align="right"><?php $amount = getTF($status->level,$student->lec)?> {{number_format($amount,2)}}</td>
            <?php $total_amount = $total_amount + $amount; ?>
        </tr>
        @endforeach
        <tr>
            <td colspan = 7 align="right">Total Amount</td>
            <td align =right>{{number_format($total_amount,2)}}</td>
        </tr>
    </table>
</div>  
