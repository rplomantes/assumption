<style>
    
        body {
            font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
            font-size: 10pt;
        }
        td{
            /*border-bottom: 1px solid black;*/
            padding:2px;
        }
</style>

<strong>Assumption College</strong><br>
    {{$department}}<br/>
<h4>List of Students with Sibling Discount</h4>

@if(count($lists)>0)
<?php $total=0; $x = 0?>
<table width='100%' cellpadding='0' cellspacing='0'>
    <thead>
        <tr>
            <th style='border-bottom: 1px solid black'></th>
            <th style='border-bottom: 1px solid black'>ID No.</th>
            <th style='border-bottom: 1px solid black'>Name</th>
            @if($department == "College Department")
            <th style='border-bottom: 1px solid black'>Course</th>
            @endif
            <th style='border-bottom: 1px solid black'>Year Level</th>
            @if($department != "College Department")
            <th style='border-bottom: 1px solid black' align="center">Section</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach($lists as $list)
        <?php $total+= $list->idno;$x++;?>
        <tr>
            <td>{{$x}}</td>
            <td align='left'>{{$list->idno}}</td>
            <td>{{$list->lastname}},{{$list->firstname}} {{$list->middlename}} {{$list->extensionname}}</td>
            @if($department == "College Department")
            <td>{{$list->program_code}} </th>
            @endif
            <td>{{$list->level}}</td>
            @if($department != "College Department")
            <td align='center'>{{$list->section}}</td>
            @endif
        </tr>
        @endforeach
    </tbody>
</table>
<!--<br><br>

Prepared by:<br><br>
<strong>{{Auth::user()->lastname}}, {{Auth::user()->firstname}}</strong>-->
@else
@endif