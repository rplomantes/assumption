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
    {{$department}}<br/>
<h4>Outstanding Balances as of {{date("F d, Y")}}</h4>

@if(count($lists)>0)
<?php $total=0; $x = 0?>
<table width='100%' cellpadding='0' cellspacing='0'>
    <thead>
        <tr>
            <th style='border-bottom: 1px solid black'>  </th>
            <th style='border-bottom: 1px solid black'>ID No.</th>
            <th style='border-bottom: 1px solid black'>Name</th>
            @if($department == "College Department")
            <th style='border-bottom: 1px solid black'>Course</th>
            @endif
            <th style='border-bottom: 1px solid black'>Year Level</th>
            @if($department != "College Department")
            <th style='border-bottom: 1px solid black' align="center">Section</th>
            @endif
            <th style='border-bottom: 1px solid black' align='right'>Balance</th>
        </tr>
    </thead>
    <tbody>
        @foreach($lists as $list)
        <?php $total+= $list->balance;$x++;?>
        <tr>
            <td>{{$x}}  </td>
            <td align='left'>{{$list->idno}}</td>
            <td>{{$list->lastname}},{{$list->firstname}} {{$list->middlename}} {{$list->extensionname}}</td>
            @if($department == "College Department")
            <td>{{$list->program_code}} </th>
            @endif
            <td>{{$list->level}}</td>
            @if($department != "College Department")
            <td align='center'>{{$list->section}}</td>
            @endif
            <td align='right'>{{number_format($list->balance,2)}}</td>
        </tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="5" style='border-top: 1px solid black'>Total</th>
            <td align='right' style='border-top: 1px solid black'><strong>{{number_format($total,2)}}</strong></td>
        </tr>
    </tfoot>
</table>
<br><br>

@else
@endif