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
        border: 1px solid black;
        border-collapse: collapse;
        font: 9pt;
    }

</style>
<div>    
    <div style='float: left; margin-left: 200px;'><img src="{{public_path('/images/assumption-logo.png')}}"></div>
    <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><br><b>LIST OF TRANSFEREE ENROLLED STUDENT</b><br><b>A.Y. {{$request->school_year}} - {{$request->school_year + 1}}, {{$request->period}}</b></div>
</div>  
<div>
    
    <table class='table' border="1" width="100%" cellspacing='0' cellpadding='0' style='margin-top: 155px;'>
        <thead>
            <tr>
                <th width='2%' style='align-content: center'>#</th>
                <th width='10%'>ID Number</th>
                <th width='25%'>Student Name</th>
                <th width='30%'>Program Name</th>
                <th >Last School Attended</th>
                <th width='8%'>Level</th>
            </tr>
        </thead>
        
        <tbody>
            <?php $count = 0;?>
            @foreach ($students as $student)
            <?php
            $count = $count + 1;
            $user = \App\User::where('idno', $student->idno)->first();?>
            <tr>
                <td >{{$count}}</td>
                <td >{{$student->idno}}</td>
                <td >{{strtoupper($user->lastname)}}, {{strtoupper($user->firstname)}} {{strtoupper($user->middlename)}}</td>
                <td >{{$student->program_name}}</td>
                <td >{{$student->last_school_attended}}</td>
                <td >{{$student->level}}</td>
            </tr>
            @endforeach
        </tbody>
    </table><br>
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
                <td><b>ROSIE B. SOMERA<br></b></td>
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