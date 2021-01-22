<?php $discount_list = \App\CtrDiscount::where('is_display', 1)->where('academic_type', "!=", "College")->orderBy('id', 'desc')->get(); ?>
@if(count($lists)>0)
<table class="table table-responsive table-striped">
    <tr><th></th><th>ID No.</th><th>Name</th><th>Level</th><th>Section</th><th>Status</th></tr>
    @foreach($lists as $list)
    <?php $status = \App\Status::where('idno', $list->idno)->first(); ?>
    <tr>
        <td><a href="{{url('add_hold_grade',array($list->idno))}}"><<</a></td>
        <td>{{$list->idno}}</td><td>{{$list->lastname}}, {{$list->firstname}} {{$list->middlename}}</td>
        <td>{{$status->level}}</td>
        <td>{{$status->section}}</td>
        <td>
            @if($status->status == 3)Enrolled
            @elseif($status->status == 2) Assessed
            @elseif($status->status == 10) Pre-Registered
            @elseif($status->status == 11) For Approval
            @elseif($status->status == 4) Withdrawn-{{$status->date_dropped}}
            @else Not Yet Enrolled @endif
        </td>
    </tr>
    @endforeach
</table>    
@else
<h4>Record Not Found</h4>
@endif

