<?php
function getAge($dob,$condate){ 
            $birthdate = new DateTime(date("Y-m-d",  strtotime($dob)));
            $today= new DateTime(date("Y-m-d",  strtotime($condate)));           
            $age = $birthdate->diff($today)->y;
            return $age;

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
    <div style='float: left; margin-left: 380px;'><img src="{{public_path('/images/assumption-logo.png')}}"></div>
    <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><b>A.Y. {{$request->school_year}} - {{$request->school_year + 1}}, {{$request->period}}</b><br><br></div>
</div>
<div>    
    <table width="100%" style='margin-top:150px'>
        <thead>
            <tr>
                <td width="17%"><b>Institutional Identifier:</b><td><b>13022</b></td>
            </tr>
            <tr>
                <td><b>Name of Institution:</b></td><td><b>Assumption College</b></td>
            </tr>
            <tr>
                <td><b>Address:</b></td><td><b>San Lorenzo Village, Makati City</b></td>
            </tr>
            <tr>
                <td><b>Tel. No.:</b></td><td><b>817-0757 loc. 2030</b></td>
            </tr>
        </thead>
    </table>
    <table width="100%">
        <thead>
            <tr>
                <td align='center'><b>LIST OF NSTP GRADUATES </b></td>
            </tr>
            
            <tr>
                <td align='center'><b>A.Y. {{$request->school_year}} - {{$request->school_year + 1}}, {{$request->period}} </b></td>
            </tr>
        </thead>
    </table>    
    <table class='table' border="1" width="100%" style="border-left: white; border-bottom: white; border-right: white; border-top: white;">
        <thead>
            <tr>
                <th rowspan="2" align='center' width='1%' style="border-bottom: white; border-left: white; border-top: white"></th>
                <th rowspan="2" align='center' width='10%'>SERIAL NO.</th>
                <th colspan="3" align='center' width='15%'>STUDENT NAME</th>
                <th rowspan="2" align='center' width='20%'>COURSE</th>
                <th rowspan="2" align='center' width='7%'>DATE OF BIRTH</th>
                <th rowspan="2" align='center' width='4%'>AGE</th>
                <th rowspan="2" align='center' width='4%'>GENDER</th>
                <th rowspan="2" align='center' width='20%'>ADDRESS</th>
                <th rowspan="2" align='center' width='15%'>EMAIL</th>
                <th rowspan="2" align='center' width='4%'>TEL. NO</th>
            </tr>
            <tr>
                <td align='center' width='10%'>FAMILY NAME</td>
                <td align='center' width='10%'>FIRST NAME</td>
                <td align='center' width='10%'>MIDDLE NAME</td>
            </tr>
        </thead>
        
        <tbody>
            <?php $count = 0;?>
            @foreach($students as $student)
            <?php $user = \App\User::where('idno', $student->idno)->first(); ?>
            <?php $info = \App\StudentInfo::where('idno', $student->idno)->first(); ?>
            <?php $count = $count +1?>
            <tr>
                <td style="border-bottom: white; border-left: white; border-top: white" align='center'>{{$count}}</td>
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
                <th>Male:</th>
                <th rowspan="2" colspan="9" style="border-bottom: none; border-right: none"></th>
            </tr>
            <tr>
                <th>Female: {{$count}}</th>
            </tr>
        </tbody>
    </table>
    <br>
    <table width="100%">
        <thead>
            <tr>
                <td>Prepared By:<br><br><br><br></td>
                <td>Approved By:<br><br><br><br></td>
                <td><div align="right">Date Printed: {{ date('Y-m-d H:i:s') }}</div></td>
            </tr>
        </thead>
        <tbody>
            <tr>               
                <td><b>{{strtoupper(Auth::user()->lastname)}}, {{strtoupper(Auth::user()->firstname)}} {{strtoupper(Auth::user()->middlename)}}</b></td>
                <td><b>{{strtoupper(env("HED_REGISTRAR"))}}<br></b></td>
                <td></td>
            </tr>
            <tr>
                <td></td>
                <td>Registrar</td>
                <td></td>
            </tr>
        </tbody>
    </table>  
</div>  
    