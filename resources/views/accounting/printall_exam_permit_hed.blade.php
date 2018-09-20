<?php
$tdcounter = 1;
$trcounter = 1;
$tbcounter = 1;
?>
<style>
    body {
        font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
        font-size: 8pt;
    }
    #bold {
        font-weight: bold;
    }
    .page_break { page-break-before: always; }

</style>
<style>
    @page { margin: .2cm; }
    body { margin: .2cm; }
</style>
<body>
        @foreach ($lists as $list)
        <?php
        $user = \App\User::where('idno',$list->idno)->first();
        $status = \App\Status::where('idno',$list->idno)->first();
        $grade_colleges = \App\GradeCollege::where('school_year', $school_year)->where('period', $period)->where('idno', $list->idno)->where('is_dropped', 0)->get();
        ?>
            @if($tbcounter == 1)
                    @if($tdcounter == 1)
            <table width="100%" border="0">
                <tr>
                    <td valign="top">
                    @elseif($tdcounter == 2)
                    <td valign="top"><div style="margin-left:1cm">
                    @endif
            @elseif($tbcounter == 2)
                    @if($tdcounter == 1)
            <table width="100%" border="0" style="margin-top:2.5cm" border="0">
                <tr>
                    <td valign="top">
                    @elseif($tdcounter == 2)
                    <td valign="top"><div style="margin-left:1cm">
                    @endif
            @endif
    
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
            

            @if($tbcounter == 1)
                    @if($tdcounter == 1)
                    </td>
                    <?php $tdcounter = 2; ?>
                    @elseif($tdcounter == 2)
                    </div></td>
                    <?php $tdcounter = 1; ?>
                    <?php $tbcounter = 2; ?>
                </tr>
            </table>
                    @endif
            @elseif($tbcounter == 2)
                    @if($tdcounter == 1)
                    </td>
                    <?php $tdcounter = 2; ?>
                    @elseif($tdcounter == 2)
                    </div></td>
                    <?php $tdcounter = 1; ?>
                    <?php $tbcounter = 1; ?>
                </tr>
            </table>
            <div class="page_break"></div>
                    @endif
            @endif
        
        @endforeach
</body>