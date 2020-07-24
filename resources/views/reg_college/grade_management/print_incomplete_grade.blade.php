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
    <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><br><b>INCOMPLETE GRADES</b></div>
</div>
<div>
    <table class='table' border="1" width="100%" cellspacing='0' cellpadding='0' style='margin-top: 145px;'>
        <tr>
            <td class='no-border td' width='4%'>Academic Year:</td>
            <td class='underline td' width='30%'>&nbsp;&nbsp;&nbsp;{{$school_year}}-{{$school_year+1}}, {{$period}}@if($term == "midterm"), Midterm @elseif($term == "finals"), Finals @endif</td>
        </tr>
    </table>
    <table class='table' border="1" width="100%" cellspacing='0' cellpadding='3px' style='margin-top: 10px;'>
        @if (count($incomplete_grades)>0)
        <?php $counter = 1; ?>
        <thead>
        <tr>
            <th>#</th>
            <th>ID Number</th>
            <th>Name</th>
            <th>Course Code</th>
            <th>Course Name</th>
            <th>Grade</th>
            <th>Section</th>
            <th>Instructor</th>
        </tr>
        <thead>
        @foreach($incomplete_grades as $grade)
        <tbody>
        <tr>
            <td>{{$counter++}}.</td>
            <td>{{$grade->idno}}</td>
            <td>{{$grade->lastname}}, {{$grade->firstname}} {{$grade->middlename}}</td>
            <td>{{$grade->course_code}}</td>
            <td>{{$grade->course_name}}</td>
                <td><strong>
                    @if($term == "midterm")
                        {{$grade->midterm}}
                    @elseif($term == "finals")
                        {{$grade->finals}}
                    @endif
                    
                    </strong>
                </td>
            <td>

                <?php
                $offering_id = \App\CourseOffering::find($grade->course_offering_id);
                ?>
                {{$offering_id->section_name}}
            </td>
            <td>
                <?php
                $offering_id = \App\CourseOffering::find($grade->course_offering_id);
                $schedule_instructor = \App\ScheduleCollege::distinct()->where('schedule_id', $offering_id->schedule_id)->get(['instructor_id']);

                foreach ($schedule_instructor as $get) {
                    if ($get->instructor_id != NULL) {
                        $instructor = \App\User::where('idno', $get->instructor_id)->first();
                        echo "$instructor->firstname $instructor->lastname $instructor->extensionname";
                    } else {
                        echo "";
                    }
                }
                ?>
            </td>
        </tr>
        </tbody>
        @endforeach

        @else
        <tr>
            <td>No result found!!!</td>
        </tr>
        @endif
    </table>
</div>