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
        <th rowspan="2"  align='center'>#</th>
        <th align='center'>SERIAL NO.</th>
        <th colspan="3"  align='center'>STUDENT NAME</th>
        <th rowspan="2"  align='center'>COURSE</th>
        <th rowspan="2"  align='center'>DATE OF BIRTH</th>
        <th rowspan="2"  align='center'>AGE</th>
        <th rowspan="2"  align='center'>GENDER</th>
        <th rowspan="2"  align='center'>ADDRESS</th>
        <th rowspan="2"  align='center'>EMAIL</th>
        <th rowspan="2"  align='center'>TEL. NO</th>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td align='center'>FAMILY NAME</td>
        <td align='center'>FIRST NAME</td>
        <td align='center'>MIDDLE NAME</td>
    </tr>
    <?php $count = 0; ?>
    @foreach($students as $student)
    <?php $user = \App\User::where('idno', $student->idno)->first(); ?>
    <?php $info = \App\StudentInfo::where('idno', $student->idno)->first(); ?>
    <?php $count = $count + 1 ?>
    <tr>
        <td align='center'>{{$count}}</td>
        <td></td>
        <td align='center'>{{strtoupper($user->lastname)}}</td>
        <td align='center'>{{strtoupper($user->firstname)}}</td>
        <td align='center'>{{strtoupper($user->middlename)}}</td>
        <td align='center'>{{strtoupper($info->program_name)}}</td>
        <td align='center'>{{date('m/d/Y',strtotime($info->birthdate))}}</td>
        <td align='center'>{{getAge($info->birthdate, date('Y-m-d'))}}</td>
        <td align='center'>F</td>
        <td align='center'>{{$info->street}} {{$info->barangay}} {{$info->municipality}} {{$info->province}}</td>
        <td align='center'>{{$user->email}}</td>
        <td align='center'>{{$info->tel_no}}</td>

    </tr>
    @endforeach
    <tr>
        <th style="border-bottom: white; border-left: white; border-top: white" rowspan="2"></th>
        <th align="center" rowspan="2">Total</th>
        <td>Male:</td>
    </tr>
    <tr>
        <td>Female: {{$count}}</td>
    </tr>
</table>