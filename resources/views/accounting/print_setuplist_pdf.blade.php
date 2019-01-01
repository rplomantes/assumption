<style>
    
        body {
            font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
            font-size: 10pt;
        }
</style>

<strong>Assumption College</strong><br>
{{$department}}<br>
@if($department == "College Department" or $department == "Senior High School")
{{$school_year}} - {{$period}}
@endif
@if($department == "Junior High School" or $department == "Elementary" or $department == "Pre School") 
{{$school_year}}
@endif
<h3>Set Up List - {{$subsidiary}}</h3>

@if(count($lists)>0)
<?php $total = 0;
$x = 0 ?>
<table width='100%' cellpadding='0' cellspacing='0'>
    @foreach($heads as $head)
    <?php $x = 0;?>
    <tr><td colspan="6"><h4>{{$head->level}}</h4></td></tr>
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
            <th style='border-bottom: 1px solid black' align='right'>Amount</th>
        </tr>
    </thead>
    <tbody>
            @foreach($lists as $list)
                @if($list->level == $head->level)
                <?php $total += $list->amount; $x++; ?>
                <tr>
                    <td>{{$x}}  </td>
                    <td align='left'>{{$list->idno}}</td>
                    <td>{{$list->lastname}}, {{$list->firstname}} {{$list->middlename}} {{$list->extensionname}}</td>
                    @if($department == "College Department")
                    <td>{{$list->program_code}} </th>
                        @endif
                    <td>{{$list->level}}</td>
                    @if($department != "College Department")
                    <td align='center'>{{$list->section}}</td>
                    @endif
                    <td align='right'>{{number_format($list->amount,2)}}</td>
                </tr>
                @endif
            @endforeach
            <tr><td align="right" colspan="5">SUB TOTAL</td><td align="right"><strong>{{number_format($head->total,2)}}</strong></td></tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="5" style='border-top: 1px solid black' align="left">GRAND TOTAL</th>
            <td align='right' style='border-top: 1px solid black'><strong>{{number_format($total,2)}}</strong></td>
        </tr>
    </tfoot>
</table>
<br><br>
Run Date: {{date('Y-m-d H:i:s')}}<br><br><br>

Prepared by:<br><br>
<strong>{{Auth::user()->lastname}}, {{Auth::user()->firstname}}</strong>
@else
@endif