@if(count($lists)>0)
<div class='table-responsive'>
    <table class="table table-striped table-condensed">
        <tr><th>ID Number</th><th>Name</th><th>View Information</th></tr>
        @foreach($lists as $list)
        @if($list->accesslevel > '1')
        <tr><td>{{$list->idno}}</td><td>{{$list->lastname}}, {{$list->firstname}}</td><td><a href="{{url('/admin', array('view_information', $list->idno))}}">View</a></td></tr>
        @endif
        @endforeach
    </table>    
</div>
@else
<h1> Record Not Found</h1>
@endif

