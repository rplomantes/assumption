@if(count($lists)>0)
<table class="table table-responsive table-striped">
    <tr><th>Student ID</th><th>Student Name</th><th>View Info</th></tr>
    @foreach($lists as $list)
    @if($list->accesslevel == '0' && $list->academic_type=="BED")
    <tr><td>{{$list->idno}}</td><td>{{$list->lastname}}, {{$list->firstname}}</td>
        <td><a  href="{{url('/admissionbed',array('info',$list->idno))}}">View Info</a></td>
    </tr>
    @endif
    @endforeach
</table>    
@else
<h1> Record Not Found</h1>
@endif

