<div class="col-sm-12">
    <div class="box">
            @if (count($list_per_courses)>0)
        <div class='box-header'>
            <h3 class="box-title">Search Results</h3>
            <a onclick="print_per_course(course.value, schedule_id.value, school_year.value, period.value, level.value, academic_program.value)"><button class='btn btn-default pull-right'><span class='fa fa-print'></span> Print</button></a>
        </div>
        <div class="box-body">
                    <div class='table-responsive'>
            <table class='table table-hover table-striped table-condensed'>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student ID</th>
                        <th>Student Name</th>
                        <th>Level</th>
                        <th>Program</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $counter = 0; ?>
                    @foreach ($list_per_courses as $list_per_course)
                    <?php $status = \App\CollegeLevel::where('school_year', $sy)->where('period', $pr)->where('idno', $list_per_course->idno)->first(); ?>
                    @if(count($status)>0)
                    <?php $counter = $counter + 1; ?>
                    <?php $user = \App\User::where('idno', $list_per_course->idno)->first(); ?>
                    <?php $student_info = \App\StudentInfo::where('idno', $list_per_course->idno)->first(); ?>
                    <tr>
                        <td>{{$counter}}</td>
                        <td>{{$list_per_course->idno}}</td>
                        <td>{{$user->lastname}}, {{$user->firstname}}</td>
                        <td>{{$status->level}}</td>
                        <td>{{$status->program_code}}</td>
                    </tr>
                    @endif
                    @endforeach
                </tbody>
            </table>
                    </div>
            @else
            <div class='box-header'>
            <h3 class="box-title">No Result!!!</h3>
        </div>
            @endif
        </div>
    </div>
</div>