@if(count($lists)>0)
<div class='table-responsive'>
<table class="table table-striped table-condensed">
    <tr>
        <th>Student ID</th>
        <th>Student Name</th>
        <th>View Info</th>
        <th>Student Record</th>
        <th>Assessment</th>
    </tr>
    @foreach($lists as $list)
    @if($list->accesslevel == '0')
    <tr>
        <td>{{$list->idno}}</td>
        <td>{{$list->lastname}}, {{$list->firstname}}</td>
        <td><a href="{{url('registrar_college', array('view_info', $list->idno))}}">View Info</a></td>
        <td><a href="{{url('registrar_college', array('student_record', $list->idno))}}">Student Record</a></td>
        <td><a href="{{url('registrar_college', array('assessment',$list->idno))}}">Assessment</a></td>
        
    </tr>
    @endif
    @endforeach
</table>    
</div>
@else
<h1> Record Not Found</h1>
@endif

