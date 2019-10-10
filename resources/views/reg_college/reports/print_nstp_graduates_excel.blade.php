<?php

function getAge($dob, $condate) {
    $birthdate = new DateTime(date("Y-m-d", strtotime($dob)));
    $today = new DateTime(date("Y-m-d", strtotime($condate)));
    $age = $birthdate->diff($today)->y;
    return $age;
}
?>

<table>
    <tr>
        <th align='center'>#</th>
        <th align='center'>SERIAL NO.</th>
        <th align='center'>ID NUMBER</th>
        <th align='center'>STUDENT NAME</th>
        <th align='center'>COURSE</th>
        <th align='center'>ADDRESS</th>
        <th align='center'>DATE OF BIRTH</th>
        <th align='center'>GENDER</th>
        <th align='center'>TEL. NO</th>
    </tr>
    <?php $count = 0; ?>
    @foreach($students as $student)
    <?php $user = \App\User::where('idno', $student->idno)->first(); ?>
    <?php $info = \App\StudentInfo::where('idno', $student->idno)->first(); ?>
    <?php $count = $count + 1 ?>
    <tr>
        <td align="right">{{$count}}.</td>
        <td></td>
        <td>{{strtoupper($student->idno)}}</td>
        <td>{{strtoupper($user->lastname)}}, {{strtoupper($user->firstname)}} {{strtoupper($user->middlename)}}</td>
        <td>{{strtoupper($info->program_name)}}</td>
        <td align='center'>{{$info->street}} {{$info->barangay}} {{$info->municipality}} {{$info->province}}</td>
        <td align='center'>{{date('m/d/Y',strtotime($info->birthdate))}}</td>
        <td align='center'>F</td>
        <td align='center'>{{$info->tel_no}}</td>

    </tr>
    @endforeach
    <tr>
        <th rowspan="2"></th>
        <th rowspan="2">Total</th>
        <th>Male:</th>
        <th rowspan="2" colspan="6" style="border-bottom: none; border-right: none"></th>
    </tr>
    <tr>
        <th>Female: {{$count}}</th>
    </tr>
</table>