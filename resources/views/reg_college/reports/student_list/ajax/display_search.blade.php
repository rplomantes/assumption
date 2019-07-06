<div class="col-sm-12">
    <div class="box">
            @if (count($lists)>0)
        <div class='box-header'>
            <h3 class="box-title">Search Results</h3>
            @if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('ADMISSION_HED'))
            <a onclick='print_search(school_year.value, period.value, level.value, academic_program.value)'><button class='btn btn-default pull-right'><span class='fa fa-print'></span> Print</button></a>
            @endif
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
                        @if(Auth::user()->accesslevel == env("ADMISSION_HED"))
                        <th>Scholar</th>
                        @endif
                        <th>Program</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $counter = 0; ?>
                    @foreach ($lists as $list)
                    <?php $counter = $counter + 1; ?>
                    <?php $user = \App\User::where('idno', $list->idno)->first(); ?>
                    <?php $student_info = \App\StudentInfo::where('idno', $list->idno)->first(); ?>
                    <?php $status = \App\Status::where('idno', $list->idno)->first(); ?>
                    <tr>
                        <td>{{$counter}}</td>
                        <td>{{$list->idno}}</td>
                        <td>{{$user->getFullNameAttribute()}}</td>
                        <td>{{$status->level}}</td>
                        @if(Auth::user()->accesslevel == env("ADMISSION_HED"))
                        <?php $scholar = \App\CollegeScholarship::where('idno', $list->idno)->first(); ?>
                        <td>{{$scholar->discount_description}}</td>
                        @endif
                        <td>{{$status->program_code}}</td>
                    </tr>
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