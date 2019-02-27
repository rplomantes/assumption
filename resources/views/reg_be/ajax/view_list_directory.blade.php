<?php
function get_name($idno){
    $names = \App\User::where('idno',$idno)->first();
    return $names->lastname.", ".$names->firstname." ".$names->middlename;
}
$i=1;
?>

<h3>Assumption College</h3>
<div>Level : {{$level}}</div>
@if($level=="Grade 11" || $level=="Grade 12")
<div>Strand : {{$strand}}</div>
@endif
@if($section=="All")
<table border="1" class="table table-responsive table-striped">
    <tr>
        <th>#</th>
        <th>Student ID</th>
        <th>Student Name</th>
        <th>Section</th>
        <th>Street</th>
        <th>Barangay</th>
        <th>Municipality/City</th>
        <th>Province</th>
        <th>Zip</th>
        <th>Tel No.</th>
        <th>Cell No.</th>
        <th>Email</th>
    </tr>
    @if(count($status)>0)
    @foreach($status as $name)
    <?php $get_directory = \App\BedProfile::where('idno',$name->idno)->first(); ?>
    <?php $email = \App\User::where('idno',$name->idno)->first(); ?>
    <tr>
        <td>{{$i++}}</td>
        <td>{{$name->idno}}</td>
        <td>{{get_name($name->idno)}}</td>
        <td>{{$name->section}}</td>
        @if(count($get_directory)>0)
        <td>{{$get_directory->street}}</td>
        <td>{{$get_directory->barangay}}</td>
        <td>{{$get_directory->municipality}}</td>
        <td>{{$get_directory->province}}</td>
        <td>{{$get_directory->zip}}</td>
        <td>{{$get_directory->tel_no}}</td>
        <td>{{$get_directory->cell_no}}</td>
        <td>{{$email->email}}</td>
        @else
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        @endif
    </tr>
    @endforeach
    @else
    <tr>
        <td colspan="8">No List For This Level</td>
    </tr>
    @endif
    
</table> 
<div class ="form form-group">
    <a href="javascript:void(0)" onclick = "export_student_directory()" class="form btn btn-primary"> Print Student List</a>
</div> 
@else

<div>Section : {{$section}}</div>

<table border="1" class="table table-responsive table-striped">
    <tr>
        <th>#</th>
        <th>Student ID</th>
        <th>Student Name</th>
        <th>Street</th>
        <th>Barangay</th>
        <th>Municipality/City</th>
        <th>Province</th>
        <th>Zip</th>
        <th>Tel No.</th>
        <th>Cell No.</th>
        <th>Email</th>
    </tr>
    @if(count($status)>0)
    @foreach($status as $name)
    <?php $get_directory = \App\BedProfile::where('idno',$name->idno)->first(); ?>
    <?php $email = \App\User::where('idno',$name->idno)->first(); ?>
    <tr>
        <td>{{$i++}}</td>
        <td>{{$name->idno}}</td>
        <td>{{get_name($name->idno)}}</td>
        @if(count($get_directory)>0)
        <td>{{$get_directory->street}}</td>
        <td>{{$get_directory->barangay}}</td>
        <td>{{$get_directory->municipality}}</td>
        <td>{{$get_directory->province}}</td>
        <td>{{$get_directory->zip}}</td>
        <td>{{$get_directory->tel_no}}</td>
        <td>{{$get_directory->cell_no}}</td>
        <td>{{$email->email}}</td>
        @else
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        @endif
    </tr>
    @endforeach
    @else
    <tr>
        <td colspan="8">No List For This Level</td>
    </tr>
    @endif
    
</table>    
<div class ="form form-group">
    <a href="javascript:void(0)" onclick = "export_student_directory()" class="form btn btn-primary"> Export Directory</a>
</div>    
@endif