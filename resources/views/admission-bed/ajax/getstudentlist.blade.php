<?php 
if(Auth::user()->accesslevel== env("ADMISSION_BED")){
    $auth_type =  "BED";
    $stat = 10;
}else{
    $auth_type = "SHS";
    $stat = 0;
} 
?>

@if(count($lists)>0)
<table class="table table-responsive table-striped">
    <tr><th>Student ID</th><th>Student Name</th><th>Status</th><th>View Info</th></tr>
    @foreach($lists as $list)
    <?php $status = \App\Status::where('idno', $list->idno)->first(); ?>
    @if($list->accesslevel == '0' && $list->academic_type == $auth_type && $status->status>= $stat)
    <tr><td>{{$list->idno}}</td><td>{{$list->lastname}}, {{$list->firstname}}</td>
        <td>
            @if($status->status == 3)Enrolled
            @elseif($status->status == 2) Assessed
            @elseif($status->status == 4) Withdrawn
            @elseif($status->status == 10) Pre-Registered
            @elseif($status->status == 11) For Approval
            @elseif($status->status == 12) Regret
            @elseif($status->status == 13) Regret
            @else Not Yet Enrolled @endif
        </td>
        <td><a  href="{{url('/admissionbed',array('info',$list->idno))}}">View Info</a></td>
    </tr>
    @endif
    @endforeach
</table>    
@else
<h1> Record Not Found</h1>
@endif

