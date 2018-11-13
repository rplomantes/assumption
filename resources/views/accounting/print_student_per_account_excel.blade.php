<table>
    <tr><td colspan="4"><strong>Assumption College</strong></td></tr>
    <tr><td colspan="4">List of Students per Account ({{date("F d, Y")}})</td></tr>
    <tr><td colspan="4">{{$department}} ({{$school_year}} - {{$school_year + 1}}) / {{$period}}</td></tr>
    <tr><td colspan="4"><h4>Account No. :  {{$info->accounting_code}} - {{$info->accounting_name}} </h4></td></tr>
    <tr><td colspan="4">( TOTAL: {{count($lists)}} student/s )</td></tr>
</table>
@if(count($lists)>0)
<?php $total=0; $x = 0?>
<table>
    <thead>
        <tr>
            <th style='border-bottom: 1px solid black'></th>
            <th style='border-bottom: 1px solid black'>ID No.</th>
            <th style='border-bottom: 1px solid black'>Name</th>
            <th style='border-bottom: 1px solid black'>Year Level</th>
            <th style='border-bottom: 1px solid black'>Section</th>
            @if($department != 'College Department')
            <th style='border-bottom: 1px solid black'>Section</th>
            @endif
        </tr>
    </thead>
    <tbody>
        @foreach($lists as $list)
        <?php $x++;?>
        <tr>
            <td>{{$x}}</td>
            <td align='left'>{{$list->idno}}</td>
            <td>{{$list->lastname}}, {{$list->firstname}} {{$list->middlename}} {{$list->extensionname}}</td>
            <td>{{$list->program_code}} {{$list->level}}</td>
            @if($department != 'College Department')
            <td>{{$list->section}}</td>
            @endif
        </tr>
        @endforeach
</table>
<br><br>

Prepared by:<br><br>
<strong>{{Auth::user()->lastname}}, {{Auth::user()->firstname}}</strong>
@else
@endif