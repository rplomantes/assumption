<?php

function get_name($idno) {
    $names = \App\User::where('idno', $idno)->first();
    return $names->lastname . ", " . $names->firstname . " " . $names->middlename;
}

$i = 1;
$y = 1;
?>

<h3>Assumption College</h3>
<div>Level : {{$level}}</div>
@if($level=="Grade 11" || $level=="Grade 12")
<div>Strand : {{$strand}}</div>
@endif
@if($section=="All")
<table border="1" class="table table-responsive table-striped">
    <tr><th>#</th><th>Student ID</th><th>Student Name</th><th>Section</th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>
    @if(count($status)>0)
    @foreach($status as $name)
    <tr><td>{{$i++}}</td><td>{{$name->idno}}</td><td>{{get_name($name->idno)}}</td><td>{{$name->section}}</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>
    @endforeach
    @else
    <tr><td colspan="8">No List For This Level</td></tr>
    @endif

</table>
@else

<div>Section : {{$section}}</div>

<table border="1" class="table table-responsive table-striped table-bordered">
    <tr><th>#</th><th>Student ID</th><th>Student Name</th><th></th><th></th></tr>
    @if(count($status)>0)
    @foreach($status as $name)

    @if($level == "Pre-Kinder" || $level == "Kinder")
    <tr><td>{{$i++}}</td><td>{{$name->idno}}</td><td>{{get_name($name->idno)}}</td>
        <td><a target="_blank" href="/view_narrative_report/{{$name->idno}}/{{$schoolyear}}">View Narrative Report</a></td>
        <td><a target="_blank" href="/view_indicator_report/{{$name->idno}}/{{$schoolyear}}">View Indicators Report</a></td>
    </tr>
    @else
    <tr><td>{{$i++}}</td><td>{{$name->idno}}</td><td>{{get_name($name->idno)}}</td>
        <td><a target="_blank" href="{{url('view_report_card', array($name->idno,'0',$schoolyear,$period))}}">View Report Card</a></td>
        <td><a target="_blank" href="{{url('view_report_card', array($name->idno,'1',$schoolyear,$period))}}">View Report Card /w Numeric</a></td>
    </tr>
    @endif
    @endforeach
    @else
    <tr><td colspan="8">No List For This Level</td></tr>
    @endif
</table>
@endif