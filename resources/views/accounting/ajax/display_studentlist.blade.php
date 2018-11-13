<div class="col-sm-12">
    <div class="box">
            @if (count($lists)>0)
        <div class='box-header'>
            <h3 class="box-title">Search Results</h3>
            @if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD'))
            <a onclick='print_search(school_year.value, level.value, period.value)'><button class='btn btn-default pull-right'><span class='fa fa-print'></span> Print</button></a>
            @endif
        </div>
        <div class="box-body">
                    <div class='table-responsive'>
            <table class='table table-hover table-striped table-condensed'>
                <thead>
                    <tr>
                        <th class width='8%'>#</th>
                        <th class width='25%'>Student ID</th>
                        <th class width='35%'>Student Name</th>
                        <th class width='20'>Plan</th>
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
                        <td>{{$user->lastname}}, {{$user->firstname}}</td>
                        <td>{{$status->type_of_plan}}</td>
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