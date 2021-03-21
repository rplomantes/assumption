<?php
function get_name($idno){
    
    $names = \App\User::where('idno',$idno)->first();
    
    return strtoupper($names->lastname).", ".ucwords(strtolower($names->firstname))." ".ucwords(strtolower($names->middlename));

    
    }
$i=1;
?>
<table>
    <tr><td><strong>Assumption College</strong></td></tr>
    <tr><td>Basic Education Division</td></tr>
    <tr><td>S.Y. {{$schoolyear}} @if($department == "Senior High School") {{$period}} @endif</td></tr>
    <tr><td>WITHDRAWN STUDENTS</td></tr>
</table>

<table border="1" cellspacing="0" cellpadding="3" width="100%" style="font-size: 9pt">
    <tr>
        <th width="5%">#</th>
        <th width="15%">ID Number</th>
        <th>Name</th>
        <th>Level</th>
        <th>Section</th>
    </tr>
   
    @if(count($status)>0)
    @foreach($status as $name)
    <tr>
        <td>{{$i++}}.</td>
        <td width="15%">{{$name->idno}}</td>
        <td width="50%">
            {{get_name($name->idno)}}
        </td>
        <td>{{$name->level}}</td>
        <td>{{$name->section}}</td>
    </tr>
    @endforeach
    @else
    <tr><td colspan="5">No List For This Level</td></tr>
    @endif
    
</table> 
{{date('M d, Y')}}
