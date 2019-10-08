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
            <th rowspan="1" align='center' width='1%'>#</th>
            <th align='center' width='5%'>ID NUMBER</th>
            <th align='center' width='15%'>STUDENT NAME</th>
            <th align='center' width='25%'>COURSE</th>
            <th align='center' width='25%'>ADDRESS</th>
            <th align='center' width='4%'>DATE OF BIRTH</th>
            <th align='center' width='4%'>GENDER</th>
            <th align='center' width='4%'>TEL. NO</th>
        </tr>
        </tr>
        <?php $count = 0; ?>
        @foreach($students as $student)
        <?php $user = \App\User::where('idno', $student->idno)->first(); ?>
        <?php $info = \App\StudentInfo::where('idno', $student->idno)->first(); ?>
        <?php $count = $count + 1 ?>
        <tr>
            <td align="right">{{$count}}.</td>
            <td>{{strtoupper($student->idno)}}</td>
            <td>{{strtoupper($user->lastname)}}, {{strtoupper($user->firstname)}} {{strtoupper($user->middlename)}}</td>
            <td>{{strtoupper($info->program_name)}}</td>
                <td align='center'>{{$info->street}} {{$info->barangay}} {{$info->municipality}} {{$info->province}}</td>
            <td align='center'>{{date('m/d/Y',strtotime($info->birthdate))}}</td>
            <!--<td align='center'>{{getAge($info->birthdate, date('Y-m-d'))}}</td>-->
            <td align='center'>F</td>
            <td align='center'>{{$info->tel_no}}</td>

        </tr>
        @endforeach
        <tr>
            <th rowspan="2"></th>
            <!--<th rowspan="2">Total</th>-->
            <th>Male:</th>
            <th rowspan="2" colspan="6" style="border-bottom: none; border-right: none"></th>
        </tr>
        <tr>
            <th>Female: {{$count}}</th>
        </tr>
</table>  
