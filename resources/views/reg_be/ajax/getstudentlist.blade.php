@if(count($lists)>0)
<table class="table table-responsive table-striped">
    <tr><th>Student ID</th><th>Student Name</th><th>Status</th><th>Assess</th><th>View Info</th><th>View Grades<th></tr>
    @foreach($lists as $list)
    <?php $status = \App\Status::where('idno', $list->idno)->first(); ?>
    @if($list->accesslevel == '0' && $list->academic_type=="BED" || $list->academic_type=="SHS")
    <tr><td>{{$list->idno}}</td><td>{{$list->lastname}}, {{$list->firstname}}</td>
        <td>
            @if($status->status == 3)Enrolled
            @elseif($status->status == 2) Assessed
            @else Not Yet Enrolled @endif
        </td>
        <td><a  href="{{url('/bedregistrar',array('assess',$list->idno))}}">Assess</a></td>
        <td><a  href="{{url('/bedregistrar',array('info',$list->idno))}}">View Info</a></td>
        <td><a  href="{{url('/bedregistrar',array('grades',$list->idno))}}">View Grades</a></td>
    </tr>
    @endif
    @endforeach
</table>    
@else
<h1> Record Not Found</h1>
@endif

