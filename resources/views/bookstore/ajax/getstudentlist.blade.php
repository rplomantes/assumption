@if(count($lists)>0)
<table class="table table-responsive table-striped">
    <tr><th>Student ID</th><th>Student Name</th><th>Status</th><th>View Orders</th><th>Place Orders</th></tr>
    @foreach($lists as $list)
    <?php $status = \App\Status::where('idno', $list->idno)->first(); ?>
    @if($list->accesslevel == '0' && $list->academic_type=="BED" && $status->status<=3 || $list->academic_type=="SHS")
    <tr><td>{{$list->idno}}</td><td>{{$list->lastname}}, {{$list->firstname}}</td>
        <td>
            @if($status->status == 3)Enrolled
            @elseif($status->status == 2) Assessed
            @elseif($status->status == 10) Pre-Registered
            @elseif($status->status == 11) For Approval
            @else Not Yet Enrolled @endif
        </td>
        <td><a  href="{{url('/bookstore',array('view_order',$list->idno))}}">View Orders</a></td>
        <td><a  href="{{url('/bookstore',array('place_order',$list->idno))}}">Place Orders</a></td>
        
    </tr>
    @endif
    @endforeach
</table>    
@else
<h1> Record Not Found</h1>
@endif

