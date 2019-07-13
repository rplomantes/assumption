@if(count($lists)>0)
<div class='table-responsive'>
<table class="table table-striped table-condensed">
    <tr>
        @if($is_search != 1)
        <th>Student ID</th>
        <th>Student Name</th>
        <th>Status</th>
        <th>Level/Program</th>
        <th>View Info</th>
        <th>Student Record</th>
        <th>Assessment</th>
        <th>Schedule</th>
        <th>Adding/Dropping</th>
        @else
        <th>Student ID</th>
        <th>Student Name</th>
        <th>Status</th>
        <th>Level/Program</th>
        @endif
    </tr>
    @foreach($lists as $list)
    @if($list->accesslevel == '0')
    <?php $status = \App\Status::where('idno', $list->idno)->first(); ?>
    <tr>
        
        @if($is_search != 1)
        <td>{{$list->idno}}</td>
        <td>{{$status->getFullNameAttribute()}}</td>
        <td>
            @if($status->status == 3)Enrolled
            @elseif($status->status == 2) Assessed
            @elseif($status->status == 1) Advised
            @elseif($status->status == 4) Withdrawn-{{$status->date_dropped}}
            @else Not Yet Enrolled @endif
        </td>
        <td>{{$status->level}} - {{$status->program_code}}</td>
        <td><a href="{{url('registrar_college', array('view_info', $list->idno))}}">View Info</a></td>
        <td><a href="{{url('registrar_college', array('student_record', $list->idno))}}">Student Record</a></td>
        <td><a href="{{url('registrar_college', array('assessment',$list->idno))}}">Assessment</a></td>
        @if($status->is_advised >= 0)
        <td><a href="{{url('registrar_college', array('advising','assigning_of_schedules',$list->idno))}}">Schedule</a></td>
        @else
        <td>Schedule</td>
        @endif
        @if($status->status == 3)
        <td><a href="{{url('registrar_college', array('adding_dropping',$list->idno))}}">Adding/Dropping</a></td>
        @else
        <td>Adding/Dropping</td>
        @endif
        @else
        <td>{{$list->idno}}</td>
        <td>{{$status->getFullNameAttribute()}}</td>
        <td>
            @if($status->status == 3)Enrolled
            @elseif($status->status == 2) Assessed
            @elseif($status->status == 1) Advised
            @elseif($status->status == 4) Withdrawn-{{$status->date_dropped}}
            @else Not Yet Enrolled @endif
        </td>
        <td>{{$status->level}} - {{$status->program_code}}</td>
        @endif
    </tr>
    @endif
    @endforeach
</table>    
</div>
@else
<h1> Record Not Found</h1>
@endif

