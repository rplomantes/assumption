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
    <table class='table' border="1" width="100%">
        <thead>
            <tr>
                <th rowspan="2" align='center' width='1%'>#</th>
                <th rowspan="2" align='center' width='5%'>SERIAL NO.</th>
                <th align='center' width='20%' colspan="3">NAME</th>
                <th rowspan="2" align='center' width='25%'>COURSE</th>
                <th rowspan="2" align='center' width='4%'>DATE OF BIRTH</th>
                <th rowspan="2" align='center' width='5%'>AGE</th>
            </tr>
            <tr>
                <th>FAMILY NAME</th>
                <th>FIRST NAME</th>
                <th>MIDDLE NAME</th>
            </tr>
        </thead>
        
        <tbody>
            <?php $count = 0;?>
            @foreach($students as $student)
            <?php $user = \App\User::where('idno', $student->idno)->first(); ?>
            <?php $info = \App\StudentInfo::where('idno', $student->idno)->first(); ?>
            <?php $count = $count +1?>
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
    