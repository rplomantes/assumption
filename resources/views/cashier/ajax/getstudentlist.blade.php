@if(count($lists)>0)
<table class="table table-responsive table-striped">
    <tr><th>Student ID</th><th>Student Name</th><th>View Ledger</th></tr>
    @foreach($lists as $list)
    @if($list->accesslevel == '0')
    <tr><td>{{$list->idno}}</td><td>{{$list->lastname}}, {{$list->firstname}} {{$list->middlename}}</td><td><a  href="{{url('/cashier',array('viewledger',$list->idno))}}">View Student Ledger</a></td>
    </tr>
    @endif
    @endforeach
</table>    
@else
<h1> Record Not Found</h1>
@endif

