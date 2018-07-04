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
    .table2 {
        border: 1px solid black transparent;
        border-collapse: collapse;
        font: 9pt;
    }
    .underline {
        border-top: 1px solid transparent;
        border-left: 1px solid transparent;
        border-right: 1px solid transparent;
    }
    .top-line {
        border-bottom: 1px solid transparent;
        border-left: 1px solid transparent;
        border-right: 1px solid transparent;
        text-align: center;
    }
    .no-border {
        border-top: 1px solid transparent;
        border-left: 1px solid transparent;
        border-right: 1px solid transparent;
        border-bottom: 1px solid transparent;
    }

</style>
<div>    
    <div style='float: left; margin-left: 150px;'><img src="{{public_path('/images/assumption-logo.png')}}"></div>
    <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><br><b>GRADE RECORD</b></div>
</div>
<div>
    <div style='margin-top:130px'>
        <?php $number = 1; ?>
        @foreach ($courses_id as $course_id)
        <?php
        $students = \App\GradeCollege::where('course_offering_id', $course_id->id)->join('statuses', 'statuses.idno', '=', 'grade_colleges.idno')->join('users', 'users.idno', '=', 'grade_colleges.idno')->where('statuses.status', 3)->select('users.idno', 'users.firstname', 'users.lastname', 'grade_colleges.id', 'grade_colleges.midterm', 'grade_colleges.finals', 'grade_colleges.grade_point', 'grade_colleges.is_lock', 'grade_colleges.midterm_status', 'grade_colleges.finals_status', 'grade_colleges.grade_point_status')->orderBy('users.lastname')->get();
        ?>
        @if (count($students)>0)
        Section: {{$course_id->section_name}}
        <table class='table' border="1" width="100%" cellspacing='1' cellpadding='1'>
            <thead>
                <tr>
                    <th width="3%"><div align="center">#</div></th>
                    <th width="10%">ID number</th>
                    <th>Name</th>
                    <th width="5%">Midterm</th>
                    <th width="5%">Finals</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                <tr>
                    <td><div align="right">{{$number}}.<?php $number = $number + 1; ?></div></td>
                    <td>{{$student->idno}}</td>
                    <td>{{$student->lastname}}, {{$student->firstname}}</td>
                    <td>{{$student->midterm}}</td>
                    <td>{{$student->finals}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
    @endforeach
</div>
</div>