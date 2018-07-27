@if(count($lists)>0)
<div class='table-responsive'>
<table class="table table-striped table-condensed">
    <tr>
        <th>Student ID</th>
        <th>Student Name</th>
        <th>Status</th>
        <th>View Info</th>
        <th>Student Record</th>
        <th>Assessment</th>
        <th>Schedule</th>
        <th>Adding/Dropping</th>
    </tr>
    @foreach($lists as $list)
    @if($list->accesslevel == '0')
    <?php $status = \App\Status::where('idno', $list->idno)->first(); ?>
    <tr>
        <td>{{$list->idno}}</td>
        <td>{{$list->lastname}}, {{$list->firstname}} {{$list->middlename}}</td>
        <td>
            @if($status->status == 3)Enrolled
            @elseif($status->status == 2) Assessed
            @elseif($status->status == 1) Advised
            @else Not Yet Enrolled @endif
        </td>
        <td><a href="{{url('registrar_college', array('view_info', $list->idno))}}">View Info</a></td>
        <td><a href="{{url('registrar_college', array('student_record', $list->idno))}}">Student Record</a></td>
        <td><a href="{{url('registrar_college', array('assessment',$list->idno))}}">Assessment</a></td>
        <td><a href="{{url('registrar_college', array('advising','assigning_of_schedules',$list->idno))}}">Schedule</a></td>
        <td><a href="{{url('registrar_college', array('adding_dropping',$list->idno))}}">Adding/Dropping</a></td>
    </tr>
    @endif
    @endforeach
</table>    
</div>
@else
<h1> Record Not Found</h1>
@endif

