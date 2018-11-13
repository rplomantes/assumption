<style>
    
        body {
            font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
            font-size: 10pt;
        }
        td{
            padding:2px;
        }
</style>

<strong>Assumption College</strong><br>
    List of Students per Account ({{date("F d, Y")}})<br/>
    {{$department}}  ({{$school_year}} - {{$school_year + 1}})</br>
{{$period}}
    <h4>Account No. : {{$info->accounting_code}} - {{$info->accounting_name}} </br>
        ( TOTAL: {{count($lists)}} student/s )
    </h4>
    </br>
@if(count($lists)>0)
<?php $total=0; $x = 0?>
<table width='100%' cellpadding='0' cellspacing='0'>
    <thead>
        <tr>
            <th style='border-bottom: 1px solid black'></th>
            <th style='border-bottom: 1px solid black'>ID No.</th>
            <th style='border-bottom: 1px solid black'>Name</th>
            <th style='border-bottom: 1px solid black'>Year Level</th>
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
@else
@endif