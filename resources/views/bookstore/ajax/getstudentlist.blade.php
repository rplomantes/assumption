@if(count($lists)>0)
<table class="table table-responsive table-striped">
    <tr><th>Student ID</th><th>Student Name</th><th>View Orders</th><th>Place Orders</th></tr>
    @foreach($lists as $list)
    @if($list->accesslevel == '0' && $list->academic_type=="BED" || $list->academic_type=="SHS")
    <tr><td>{{$list->idno}}</td><td>{{$list->lastname}}, {{$list->firstname}}</td>
        <td><a  href="{{url('/bookstore',array('view_order',$list->idno))}}">View Orders</a></td>
        <td><a  href="{{url('/bookstore',array('place_order',$list->idno))}}">Place Orders</a></td>
        
    </tr>
    @endif
    @endforeach
</table>    
@else
<h1> Record Not Found</h1>
@endif

