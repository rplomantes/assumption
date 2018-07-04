@if(count($lists)>0)
<div class='table-responsive'>
<table class="table table-striped table-condensed">
    <tr>
        <th>Student ID</th>
        <th>Student Name</th>
        <th>Promotions</th>
    </tr>
    @foreach($lists as $list)
    @if($list->accesslevel == '0')
    <tr>
        <td>{{$list->idno}}</td>
        <td>{{$list->lastname}}, {{$list->firstname}}</td>
        <td><a href="{{url('/guidance_bed', array('promotions',$list->idno))}}">Promotions</a></td>
    </tr>
    @endif
    @endforeach
</table>    
</div>
@else
<h1> Record Not Found</h1>
@endif

