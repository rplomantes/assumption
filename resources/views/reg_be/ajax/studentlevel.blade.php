<?php
function get_name($idno){
    $names = \App\User::where('idno',$idno)->first();
    return $names->lastname.", ".$names->firstname." ".$names->middlename;
}
$i=1;
?>


<table border="1" class="table table-responsive table-striped">
    <tr><th>#</th><th>Student Id</th><th>Student Name</th><th>Section</th></tr>
    @if(count($students)>0)
    @foreach($students as $name)
    <tr><td>{{$i++}}</td><td>{{$name->idno}}</td><td>{{get_name($name->idno)}}</td><td>{{$name->section}}</td></tr>
    @endforeach
    @else
    <tr><td colspan="8">No List For This Level</td></tr>
    @endif
    
</table> 


