<?php
function getAge($dob,$condate){ 
            $birthdate = new DateTime(date("Y-m-d",  strtotime($dob)));
            $today= new DateTime(date("Y-m-d",  strtotime($condate)));           
            $age = $birthdate->diff($today)->y;
            return $age;

}
?>
<table>
    <thead>
        <tr>
            <th rowspan="2" align='center' width='4%'>#</th>
            <th rowspan="2" align='center' width='13%'>SERIAL NO.</th>
            <th align='center' width='25%' colspan="3">NAME</th>
            <th rowspan="2" align='center' width='60%'>COURSE</th>
            <th rowspan="2" align='center' width='15%'>DATE OF BIRTH</th>
            <th rowspan="2" align='center' width='5%'>AGE</th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <th width='25%'>FAMILY NAME</th>
            <th width='25%'>FIRST NAME</th>
            <th width='25%'>MIDDLE NAME</th>
        </tr>
    </thead>

    <tbody>
        <?php $count = 0; ?>
        @foreach($students as $student)
        <?php $user = \App\User::where('idno', $student->idno)->first(); ?>
        <?php $info = \App\StudentInfo::where('idno', $student->idno)->first(); ?>
        <?php $count = $count + 1 ?>
        <tr>
            <td align="right">{{$count}}.</td>
            <td></td>
            <td>{{strtoupper($user->lastname)}}</td>
            <td>{{strtoupper($user->firstname)}}</td>
            <td>{{strtoupper($user->middlename)}}</td>
            <td>{{strtoupper($info->program_name)}}</td>
            <td align='center'>{{date('m/d/Y',strtotime($info->birthdate))}}</td>
            <td align='center'>{{getAge($info->birthdate, date('Y-m-d'))}}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th rowspan="2"></th>
            <th rowspan="2">Total</th>
            <th>Male:</th>
            <th rowspan="2" colspan="5" style="border-bottom: none; border-right: none"></th>
        </tr>
        <tr>
            <th>Female: {{$count}}</th>
        </tr>
    </tfoot>
</table>  
