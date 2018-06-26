<style>
    .table, .th, .td {
        border: 1px solid black;
        border-collapse: collapse;
        font: 9pt;        
    }
    body {
	font-family: Courier New;
	font-size: 13px;
	font-style: normal;
	font-variant: normal;
	font-weight: 400;
	line-height: 14.3px;
}
</style>  
        <body>
        <input type='hidden' name='idno' value='{{$user->idno}}'>
        <div class="col-md-12">
             <!--Widget: user widget style 1--> 
            <div class="box box-widget widget-user-2">
                <!-- Add the bg color to the header using any of the bg-* classes -->
                <div class="widget-user-header bg-yellow">
                    <table style='margin-top:140px; ' class="table table-condensed" width="100%">
                    <tr>
                        <td width='20%'>Student Name:</td>
                        <td><b>{{strtoupper($user->lastname)}}, {{strtoupper($user->firstname)}} {{strtoupper($user->middlename)}}</b></td>
                    </tr>
                    <tr>
                        <td>Student Number:</td>
                        <td><b>{{$user->idno}}</b></td>
                    </tr>
                    <tr>
                        <td>Course:</td>
                        <td><b>{{strtoupper($level->program_name)}}</b></td>
                    </tr>
                    <tr>
                        <td>Date of Admission:</td>
                        <td><b><input type="date" name="date_of_admission" value='{{old('date_of_admission', $info->date_of_admission)}}'></b></td>
                    </tr>
                    <tr>
                        <td>Date and Place of Birth:</td>
                        <td><b>{{strtoupper($info->birthdate)}}, {{strtoupper($info->place_of_birth)}}</b></td>
                    </tr>
                    <tr>
                        <td>Citizenship:</td>
                        <td><b>{{strtoupper($info->nationality)}}</b></td>
                    </tr>
                    <tr>
                        <td>Father's Name:</td>
                        <td><b>{{strtoupper($info->father)}}</b></td>
                    </tr>
                    <tr>
                        <td>Mother's Name:</td>
                        <td><b>{{strtoupper($info->mother)}}</b></td>
                    </tr>
                    <tr>
                        <td>Address:</td>
                        <td><b>{{strtoupper($info->street)}} {{strtoupper($info->barangay)}} {{strtoupper($info->municipality)}}</br></td>
                    </tr>
                    <tr>
                        <td>Grade School:</td>
                        <td><b>{{strtoupper($info->gradeschool)}} {{strtoupper($info->gradeschool_address)}}</br></td>
                    </tr>
                    <tr>
                        <td>High School:</td>
                        <td><b>{{strtoupper($info->highschool)}} {{strtoupper($info->highschool_address)}}</br></td>
                    </tr>
                    <tr>
                        <td>Tertiary School:</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Degree Earned:</td>
                        <td><b>{{strtoupper($level->program_name)}}</b></td>
                    </tr>
                    <tr>
                        <td>Award:</td>
                        <td><b><input type="text" name='award' value="{{old('award', $info->award)}}" ></b></td>
                    </tr>
                    <tr>
                        <td>Date of Graduation:</td>
                        <td><b><input type="date" name="date_of_grad" value="{{old('date_of_grad', $info->date_of_grad)}}" ></b></td>
                    </tr>
                    <tr>
                        <td>S.O Number:</td>
                        <td><b>EXEMPTED</b></td>
                    </tr>
                    <tr>
                        <td>Remarks:</td>
                        <td><b><input type="text" name='remarks'value="{{old('remarks', $info->remarks)}}" ></b></td>
                    </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-sm-12">
            <table>                
                <thead>
                    <tr>
                        <th width='5%'>Course Code</th>
                        <th width='40%'>Course Name</th>
                        <th width='10%'>Final Grade</th>                        
                        <th width='10%'>Completion</th>
                    </tr>
                </thead>
            </table>
            <?php $pinnacle_sy = \App\CollegeGrades2018::distinct()->where('idno', $idno)->orderBy('school_year', 'asc')->get(['school_year']); ?>
            @if(count($pinnacle_sy)>0)
            @foreach ($pinnacle_sy as $pin_sy)
            <?php $pinnacle_period = \App\CollegeGrades2018::distinct()->where('idno', $idno)->where('school_year', $pin_sy->school_year)->orderBy('period', 'asc')->get(['period']); ?>
            @foreach($pinnacle_period as $pin_pr)
            <?php $pinnacle_grades = \App\CollegeGrades2018::where('idno', $idno)->where('school_year', $pin_sy->school_year)->where('period', $pin_pr->period)->get(); ?>
            <h4>{{$pin_sy->school_year}}-{{$pin_sy->school_year+1}}, {{$pin_pr->period}}</h4>
            <table class="table table-striped table-condensed" width="100%">

                <tbody>
                    @foreach($pinnacle_grades as $pin_grades)
                    <tr>
                        <td>{{$pin_grades->course_code}}</td>
                        <td><?php $get_course_name = \App\Curriculum::where('course_code', $pin_grades->course_code)->first(); ?>
                            @if(count($get_course_name)>0)
                            {{$get_course_name->course_name}}
                            @else
                            <i style="color: red;">Course name not found</i>
                            @endif</td>
                        <td>{{$pin_grades->finals}}</td>
                        <td>{{$pin_grades->completion}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endforeach
            @endforeach
            <?php $grades_sy = \App\GradeCollege::distinct()->where('idno', $idno)->orderBy('school_year', 'asc')->get(['school_year']); ?>
            @if(count($grades_sy)>0)
            @foreach($grades_sy as $sy)
            <?php $grades_pr = \App\GradeCollege::distinct()->where('idno', $idno)->where('school_year', $sy->school_year)->orderBy('period', 'asc')->get(['period']); ?>
            @foreach ($grades_pr as $pr)
            <?php $grades = \App\GradeCollege::where('idno', $idno)->where('school_year', $sy->school_year)->where('period', $pr->period)->get(); ?><h4>{{$sy->school_year}}-{{$sy->school_year+1}}, {{$pr->period}}</h4>
            <table class="table table-striped table-condensed" width="100%">
                <tbody>
                    @foreach ($grades as $grade)
                    <tr>
                        <td>{{$grade->course_code}}</td>
                        <td>{{$grade->course_name}}</td>
                        <td>{{$grade->finals}}</td>
                        <td>{{$grade->completion}}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endforeach
            @endforeach
            @endif
            @else

            <?php $grades_sy = \App\GradeCollege::distinct()->where('idno', $idno)->orderBy('school_year', 'asc')->get(['school_year']); ?>
            @if(count($grades_sy)>0)
            @foreach($grades_sy as $sy)
            <?php $grades_pr = \App\GradeCollege::distinct()->where('idno', $idno)->where('school_year', $sy->school_year)->orderBy('period', 'asc')->get(['period']); ?>
            @foreach ($grades_pr as $pr)
            <?php $grades = \App\GradeCollege::where('idno', $idno)->where('school_year', $sy->school_year)->where('period', $pr->period)->get(); ?><h4>{{$sy->school_year}}-{{$sy->school_year+1}}, {{$pr->period}}</h4>
            <table class="table table-striped table-condensed" width="100%">
                <tbody>
                    @foreach ($grades as $grade)
                    <tr>
                        <td>{{$grade->course_code}}</td>
                        <td>{{$grade->course_name}}</td>
                        <td>{{$grade->finals}} </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endforeach
            @endforeach
            @endif
            @endif  
        </body>