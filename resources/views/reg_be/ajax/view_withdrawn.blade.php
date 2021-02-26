<?php
function get_name($idno){
    $names = \App\User::where('idno',$idno)->first();
    return $names->lastname.", ".$names->firstname." ".$names->middlename;
}
$i=1;
?>

<h3>Assumption College</h3>
<table border="1" class="table table-responsive table-striped">
    <tr><th>#</th><th>Student ID</th><th>Student Name</th><th>Level</th><th>Section</th></tr>
    @if(count($status)>0)
    @foreach($status as $name)
    <tr><td>{{$i++}}</td><td>{{$name->idno}}</td><td>{{get_name($name->idno)}}</td><td>{{$name->level}}</td><td>{{$name->section}}</td></tr>
    @endforeach
    @else
    <tr><td colspan="8">No List For This Level</td></tr>
    @endif
    
</table> 
<div class ="form form-group">
    <a href="javascript:void(0)" onclick = "print_student_list()" class="form btn btn-primary"> Print Student List</a>
    <a href="javascript:void(0)" onclick = "export_student_list()" class="form btn btn-success"> Export Student List</a>
</div>