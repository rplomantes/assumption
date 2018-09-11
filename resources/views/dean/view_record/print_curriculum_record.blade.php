
    <?php $student_info = \App\StudentInfo::where('idno', $user->idno)->first(); ?>
<style>
    body {
        font-size: 9pt;
    }
    footer {
        font-size: 8pt;
    }
    #schoolname{
        font-size: 18pt; 
        font-weight: bolder;
    }
</style>
<style>
@page {
                margin: 0cm 0cm;
            }

            /** Define now the real margins of every page in the PDF **/
            body {
                margin-left: 1cm;
                margin-right: 1cm;
                margin-bottom: 6.4cm;
                margin-top: 6.3cm;

            }
            footer {
                position: fixed; 
                bottom: 6cm; 
                left: 0px; 
                right: 0px;
                height: 0px; 
                
                margin: 0cm 1cm 0cm 1cm;

            }
            header {
                position: fixed; 
                top: .5cm; 
                left: 0px; 
                right: 0px;
                height: 0px; 
                
                margin: 0cm 1cm 0cm 1cm;

            }          
    img {
        display: block;
        max-width:230px;
        max-height:95px;
        width: auto;
        height: auto;
    }
        </style>           
<body>     
        <footer>
            <table width='100%'>
                <tr>
                    <td valign='top' width='30%'><i><b>- FOR EVALUATION PURPOSES -</b></i></td>
                    <td valign='top' width='30%'><i></i></td>
                    <td valign='top' width='40%' rowspan="2" colspan="2">
                    </td>
                </tr>
                <tr>
                    <td valign='bottom'></td>
                    <td valign='bottom'></td>
                </tr>
                <tr>
                    <td  valign='top' colspan='2' rowspan="2">
                    </td>
                    <td valign='bottom' colspan='2'><i><br>CERTIFIED BY:<br><br><br></i></td>
                </tr>
                <tr>
                    <td align='center'>{{strtoupper(env("HED_REGISTRAR"))}}<br>REGISTRAR</td>
                    <td align='center'><small>DATE PRINTED</small><br>{{date('F d, Y')}}</td>
                </tr>
            </table>
        </footer>    
<!--    
    <div style='float: left; margin-left:630px; margin-top:-110px;'></div>-->
      
    <header>    
        <div>    
            <div style='float: left; margin-left: 150px;'><img src="{{public_path('/images/assumption-logo.png')}}"></div>
            <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><br><b style="border:1px solid black;">&nbsp;Curriculum Record&nbsp;</b><br></div>
        </div>
<br><br><br><br><br><br><br><br>
        <table class="table table-condensed" width="100%" border="0">
        <tbody>
        <tr>
            <td width='15%'>PROGRAM NAME:</td>
            <td width="52%" colspan="2"><div style="border-bottom: 1px solid black">{{$info->program_name}}</div></td>
            <td width='13%' align='right'>STUDENT NO.:</td>
            <td width='20%'><div style="border-bottom: 1px solid black">{{$user->idno}}&nbsp;</div></td> 
        </tr>
        <tr>
            <td valign='top'>STUDENT NAME:</td>
            <td colspan='2'><div style="border-bottom: 1px solid black"><b>{{strtoupper($user->lastname)}}, {{strtoupper($user->firstname)}} {{strtoupper($user->middlename)}}&nbsp;</div></b></td>   
            <td align='right'>CITIZENSHIP:</td>
            <td><div style="border-bottom: 1px solid black">{{strtoupper($info->nationality)}}&nbsp;</div></td> 
        </tr>
        <tr>
            <td valign='top' colspan='5' cellpadding='0' cellspacing='0'>
                <table width='100%' style="margin-left: -3px;">
                    <tr>
                        <td width='23%'>DATE AND PLACE OF BIRTH:</td>
                        <td><div style="border-bottom: 1px solid black">{{strtoupper(date('F d, Y',strtotime($info->birthdate)))}}, {{strtoupper($info->place_of_birth)}}&nbsp;</div></td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td valign='top'>ADDRESS:</td>
            <td colspan='4'><div style="border-bottom: 1px solid black">{{strtoupper($info->street)}} {{strtoupper($info->barangay)}} {{strtoupper($info->municipality)}}&nbsp;</div></td>
        </tr>       
        </tbody>
    </table>
    <hr>
        </header> 
    <?php $levels = \App\Curriculum::distinct()->where('curriculum_year', $student_info->curriculum_year)->where('program_code', $student_info->program_code)->orderBy('level')->get(['level']); ?>
    @foreach ($levels as $level)
    <?php $periods = \App\Curriculum::distinct()->where('level', $level->level)->where('curriculum_year', $student_info->curriculum_year)->where('program_code', $student_info->program_code)->orderBy('period')->get(['period']); ?>
    @foreach ($periods as $period)
    <?php $curricula = \App\Curriculum::where('curriculum_year', $student_info->curriculum_year)->where('program_code', $student_info->program_code)->where('level', $level->level)->where('period', $period->period)->get(); ?>
    
    <table class="table table-striped" width="100%">
        <tr><td colspan='6' align='center'><b>{{$level->level}} - {{$period->period}}</b></td></tr>
            <tr>
                <th width="10%">Code</th>
                <th width="50%">Description</th>
                <th width="5%">Lec</th>
                <th width="5%">Lab</th>
                <th width="8%">Grade</th>
                <th width="8%">Completion</th>
            </tr>
            @foreach ($curricula as $curriculum)
            <?php //$grades = \App\GradeCollege::where('idno', $idno)->where('course_code', $curriculum->course_code)->first(); ?>
            <?php $old_grades = \App\CollegeGrades2018::where('idno', $idno)->where('course_code', $curriculum->course_code)->orderBy('id', 'desc')->first(); ?>
            <?php $grades = \App\GradeCollege::where('idno', $idno)->where('course_code', $curriculum->course_code)->where('finals_status', 3)->orderBy('created_at', 'asc')->first(); ?>
            <?php
                    $style="";
                    if(count($old_grades)>0){
                        if($old_grades->finals=="Failed" ||$old_grades->finals=="4.00"){
                            $style="style='color:red; font-weight:bold'";
                        }else if($old_grades->finals=="FA"){
                            $style="style='color:orange; font-weight:bold'";
                        }
                    }else{
                        if(count($grades)>0){
                            if($grades->finals=="Failed" || $grades->finals=="4.00"){
                                $style="style='color:red; font-weight:bold'";
                            }else if ($grades->finals == "FA"){
                                $style="style='color:orange; font-weight:bold'";
                            }
                        }else{
                            $style="style='color:green; font-weight:bold'";
                        }
                    }
                    
                    ?>
            <tr>
                <td {!!$style!!}>{{$curriculum->course_code}}</td>
                <td {!!$style!!}>{{$curriculum->course_name}}</td>
                <td {!!$style!!}>{{$curriculum->lec}}</td>
                <td {!!$style!!}>{{$curriculum->lab}}</td>
                <td {!!$style!!}>@if(count($old_grades)>0)
                    {{$old_grades->finals}}
                    @else
                    @if(count($grades)>0)
                    {{$grades->finals}}
                    @else
                    NYT
                    @endif
                    @endif
                </td>
                <td {!!$style!!}>@if(count($old_grades)>0)
                    {{$old_grades->completion}}
                    @else
                    @if(count($grades)>0)
                    {{$grades->completion}}
                    @else
                    @endif
                    @endif
                </td>
            </tr>
            @endforeach
            @endforeach
            @endforeach
    </table>
