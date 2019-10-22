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
<table>
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
    <?php $status = \App\Status::where('idno', $student->idno)->first(); ?>
    <?php $info = \App\StudentInfo::where('idno', $student->idno)->first(); ?>
    <tr>
        <td>{{$number++}}.</td>
        <td>{{strtoupper($student->idno)}}</td>
        <td>{{strtoupper($user->lastname)}}, {{strtoupper($user->firstname)}} {{strtoupper($user->middlename)}}</td>
        <td>{{$status->level}}</td>
        <td>@if($status->status == 3) Enrolled @elseif($status->status == 4) Dropped/Withdrawn @endif</td>
        <td>{{$student->course_code}}</td>
        <td>{{$student->lec}}</td>
        <td align="right"><?php $amount = getTF($status->level, $student->lec) ?> {{$amount}}</td>
        <?php $total_amount = $total_amount + $amount; ?>
    </tr>
    @endforeach
    <tr>
        <td colspan = 7 align="right">Total Amount</td>
        <td align =right>{{$total_amount}}</td>
    </tr>
</table>
