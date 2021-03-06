@if(count($lists)>0)
<div class='table-responsive'>
    <table class="table table-striped table-condensed">
        <tr><th>Student ID</th><th>Student Name</th><th>Status</th><th>Scholarship</th><th>Information</th><th>Scholarship</th><th>Schedule</th><th>Grades</th></tr>
        @foreach($lists as $list)
        <?php $status = \App\Status::where('idno',$list->idno)->first(); ?>
        <?php $scholar = \App\CollegeScholarship::where('idno',$list->idno)->first(); ?>
        @if($list->accesslevel == '0' && $status->status != 21)
        <tr>
            <td>{{$list->idno}}</td><td>{{$list->lastname}}, {{$list->firstname}} {{$list->middlename}}</td>
            <td>@if($status->status == 3)Enrolled
            @elseif($status->status == 2) Assessed
            @elseif($status->status == 1) Advised
            @elseif($status->status == 4) Withdrawn-{{$status->date_dropped}}
            @elseif($status->status == 20) Pre-Registered
            @else Not Yet Enrolled @endif</td>
            <td>@if(count($scholar)>0) {{$scholar->discount_description}} @endif</td>
            <td><a href="{{url('/academic',array('view_info',$list->idno))}}">View Information</a></td>
            <td><a href="{{url('/scholarship_college',array('view_scholar',$list->idno))}}">View Scholarship</a></td>
            @if($status->status == env("ENROLLED"))
            <td><a href="{{url('/scholarship_college',array('view_schedule',$list->idno))}}">View Schedule</a></td>
            @else
            <td></td>
            @endif
            <td><a href="{{url('/college', array('view_transcript',$list->idno))}}">View Grades</a></td>
        </tr>
        @endif
        @endforeach
    </table>    
</div>
@else
<h1> Record Not Found</h1>
@endif

