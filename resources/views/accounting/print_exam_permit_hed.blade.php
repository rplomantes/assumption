<style>
    body {
        font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
        font-size: 8pt;
    }
    #bold {
        font-weight: bold;
    }

</style>
<style>
    @page { margin: .2cm; }
    body { margin: .2cm; }
</style>
<body>
    <br>
    <br>
    <br>
    <br>
<center>
    HED DEPARTMENT<br>
    San Lorenzo Village, Makati City<br><br>
{{strtoupper($exam_period)}}
</center>

<table border="0" width="100%" style="margin-top: 1.8cm;">
    <tr>
        <th colspan="3">{{strtoupper($user->lastname)}}, {{strtoupper($user->firstname)}} {{strtoupper($user->middlename)}} {{strtoupper($user->extensionname)}}</th>
    </tr>
    <tr>
        <td>Period: {{$period}}</td> 
        <td colspan="2">School Year: {{$school_year}}-{{$school_year+1}}</td>
    </tr>
    <tr>
        <td>LEVEL: {{$status->level}}</td>
        <td>PROGRAM: {{$status->program_code}}</td>
        <td>PAYMENT: {{$status->type_of_plan}}</td>
    </tr>
    <tr>
        <td colspan="3">DATE ISSUED: {{date('Y/m/d')}}</td>
    </tr>
    <tr>
        <td colspan="3">AUTHORIZED SIGNATURE:__________________________</td>
    </tr>
</table>

<table border="0" width="100%" cellpadding="0" cellspacing="0" style="margin-top:1.3cm;">
    @foreach ($grade_colleges as $grade)
    <tr>
        <td><small>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{{$grade->course_code}}</small></td>
    </tr>
    @endforeach
</table>

<!--<p style="position: fixed; bottom: 1cm;" align="center">
    {{strtoupper($exam_period)}}
</p>-->

</body>