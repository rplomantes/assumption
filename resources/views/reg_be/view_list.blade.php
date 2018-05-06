<?php
function get_name($idno){
    $names = \App\User::where('idno',$idno)->first();
    return $names->lastname.", ".$names->firstname." ".$names->middlename;
}
$i=1;
?>

<h3>Assumption College</h3>
<div>Student List</div>
<div>Level : {{$level}}</div>
@if($level=="Grade 11" || $level=="Grade 12")
<div>Strand : {{$strand}}</div>
@endif

@if($section=="All")
<hr>
<table border="1" cellspacing="0" cellpadding="0" width="100%">
    <tr><th width="5%">#</th><th width="10%">Student Id</th><th width="45%">Student Name</th><th width="5%">Sect</th><th width="9%"></th><th width="9%"></th><th width="9%"></th><th width="9%"></th><th width="9%"></th></tr>
   
    @if(count($status)>0)
    @foreach($status as $name)
    <tr><td>{{$i++}}</td><td>{{$name->idno}}</td><td>{{get_name($name->idno)}}</td><td align="center">{{$name->section}}</td><td></td><td></td><td></td><td></td><td></td></tr>
    @endforeach
    @else
    <tr><td colspan="8">No List For This Level</td></tr>
    @endif
    
</table> 

@else

<div>Section : {{$section}}</div>
<hr>
<table border="1" cellspacing="0" cellpadding="0" width="100%">
    <tr><th width="5%">#</th><th width="10%">Student Id</th><th width="45%">Student Name</th><th width="10%"></th><th width="10%"></th><th width="10%"></th><th width="10%"></th><th width="10%"></th></tr>
    @if(count($status)>0)
    @foreach($status as $name)
    <tr><td>{{$i++}}</td><td>{{$name->idno}}</td><td>{{get_name($name->idno)}}</td><td>&nbsp;</td><td></td><td></td><td></td><td></td></tr>
    @endforeach
    @else
    <tr><td colspan="8">No List For This Level</td></tr>
    @endif
    
</table>    

 
@endif