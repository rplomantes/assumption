<?php
function get_name($idno){
    $names = \App\User::where('idno',$idno)->first();
    return $names->lastname.", ".$names->firstname." ".$names->middlename;
}
$i=1;
?>


<table border="1" class="table table-responsive table-striped">
    <tr>
        <th>#</th>
        <th>Student ID</th>
        <th>Student Name</th>
        <th>Section</th>
        <th>Status</th>
    </tr>
    @if(count($students)>0)
    @foreach($students as $name)
    <?php $status = \App\Status::where('idno', $name->idno)->first(); ?>
    <tr>
        <td>{{$i++}}</td>
        <td>{{$name->idno}}</td>
        <td>{{get_name($name->idno)}}</td>
        <td>{{$name->section}}</td>
        <td>
            @if($status->status == 3)Enrolled
            @elseif($status->status == 2) <strong>Assessed</strong>
            @elseif($status->status == 10) Pre-Registered
            @elseif($status->status == 11) For Approval
            @elseif($status->status == 4) Withdrawn-{{$status->date_dropped}}
            @else <strong>Not Yet Enrolled</strong> @endif
        </td>
        <!--<td><a href="javascript:void(0)" onclick="change_section('{{$name->idno}}')">>></a></td>-->
    </tr>
    @endforeach
    @else
    <tr>
        <td colspan="8">No List For This Level</td>
    </tr>
    @endif
    
</table> 


