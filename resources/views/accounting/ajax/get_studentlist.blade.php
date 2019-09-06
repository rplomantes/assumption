<h4>{{$department}}</h4>
@if($department == "College Department" or $department == "Senior High School") 
<h4>S.Y. {{$school_year}}-{{$school_year +1}} - {{$period}}</h4>
@endif
@if($department == "Junior High School" or $department == "Elementary" or $department == "Pre School") 
<h4>S.Y. {{$school_year}}-{{$school_year +1}}</h4>
@endif

@if(count($lists)>0)
<?php $total = 0; $discount = 0;
$x = 0 ?>
<table width='100%' cellpadding='0' cellspacing='0'>
    @foreach($heads as $head)
    <?php $x = 0;?>
    <thead>
        <tr><td colspan="6"><h4>{{$head->level}}</h4></td></tr>
        <tr>
            <th style='border-bottom: 1px solid black'>  </th>
            <th style='border-bottom: 1px solid black'>ID No.</th>
            <th style='border-bottom: 1px solid black'>Name</th>
            @if($department == "College Department")
            <th style='border-bottom: 1px solid black'>Course</th>
            @endif
            <th style='border-bottom: 1px solid black'>Level</th>
            @if($department != "College Department")
            <th style='border-bottom: 1px solid black'>Section</th>
            @endif
            <th style='border-bottom: 1px solid black' align='right'>Plan</th>
            <th style='border-bottom: 1px solid black; text-align: right'>Amount</th>
            <th style='border-bottom: 1px solid black; text-align: right'>Discount</th>
            <th style='border-bottom: 1px solid black; text-align: right'>Net</th>
        </tr>
    </thead>
    <tbody>
            <?php $subdiscount = 0; ?>
            @foreach($lists as $list)
                @if($list->level == $head->level)
                <?php $total += $list->assessment; $x++; ?>
                <?php $discount += $list->discount; ?>
                <?php $subdiscount += $list->discount; ?>
                <tr>
                    <td>{{$x}}  </td>
                    <td align='left'>{{$list->idno}}</td>
                    <td>{{$list->lastname}}, {{$list->firstname}} {{$list->middlename}} {{$list->extensionname}}</td>
                    @if($department == "College Department")
                    <td>{{$list->program_code}} </td>
                    @endif
                    <td>{{$list->level}}</td>
                    @if($department != "College Department")
                    <td align='center'>{{$list->section}}</td>
                    @endif
                    <td>{{$list->type_of_plan}}</td>
                    <td align='right'>{{number_format($list->assessment,2)}}</td>
                    <td align='right'>{{number_format($list->discount,2)}}</td>
                    <td align='right'>{{number_format($list->assessment-$list->discount,2)}}</td>
                </tr>
                @endif
            @endforeach
            <tr><td align="right" colspan="6">SUB TOTAL</td><td align="right"><strong>{{number_format($head->total,2)}}</strong></td><td align="right"><strong>{{number_format($subdiscount,2)}}</strong></td><td align="right"><strong>{{number_format($head->total-$head->discount,2)}}</strong></td></tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="6" style='border-top: 1px solid black' align="center">GRAND TOTAL</th>
            <td align='right' style='border-top: 1px solid black'><strong>{{number_format($total,2)}}</strong></td>
            <td style='border-top: 1px solid black' align="right"><strong>{{number_format($discount,2)}}</strong></td>
            <td align='right' style='border-top: 1px solid black'><strong>{{number_format($total-$discount,2)}}</strong></td>
        </tr>
    </tfoot>
</table>
<br><br>

@else
@endif