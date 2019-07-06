@if(count($lists)>0)
<div class='table-responsive'>
<table class="table table-striped table-condensed">
    <tr>
        <th>Student ID</th>
        <th>Student Name</th>
        <th>Status</th>
        <th>Promotions</th>
    </tr>
    @foreach($lists as $list)
    <?php $status = \App\Status::where('idno', $list->idno)->first(); ?>
    @if($list->accesslevel == '0' && ($list->academic_type=="BED" || $list->academic_type=="SHS") && $status->status<=3)
    <tr>
        <td>{{$list->idno}}</td>
        <td>{{$list->lastname}}, {{$list->firstname}}</td>
        <td>
            @if($status->status == 3)Enrolled
            @elseif($status->status == 2) Assessed
            @elseif($status->status == 10) Pre-Registered
            @elseif($status->status == 11) For Approval
            @else Not Yet Enrolled @endif
        </td>
        <td><a href="{{url('/guidance_bed', array('promotions',$list->idno))}}">Promotions</a></td>
    </tr>
    @endif
    @endforeach
</table>    
</div>
@else
<h1> Record Not Found</h1>
@endif

