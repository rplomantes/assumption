@if(count($lists)>0)
<?php $total = 0;
$discount = 0;
$x = 0;
$grand_total_srf=0;
?>
<table>
    <tr><td><strong colspan="4">Assumption College</strong></td></tr>
    <tr><td colspan="4">{{$department}}</td></tr>
    <tr></tr>
    <tr><td colspan="4">Student List</tr>
    <tr></tr>
    @foreach($heads as $head)
<?php $x = 0; ?>
    <tr><td colspan="6"><h4>{{$head->level}}</h4></td></tr>
    <thead>
        <tr>
            <th style='border-bottom: 1px solid black'>  </th>
            <th style='border-bottom: 1px solid black'>ID No.</th>
            <th style='border-bottom: 1px solid black'>Name</th>
            @if($department == "College Department")
            <th style='border-bottom: 1px solid black'>Course</th>
            @endif
            <th style='border-bottom: 1px solid black' align='center'>Level</th>
            @if($department != "College Department")
            <th style='border-bottom: 1px solid black' align='center'>Section</th>
            @endif
            <th style='border-bottom: 1px solid black' align='center'>Plan</th>
            <th style='border-bottom: 1px solid black; text-align: right'>Amount</th>
            <th style='border-bottom: 1px solid black; text-align: right'>Discount</th>
            @if($department == "College Department" || $department == "Senior High School")
            <th style='border-bottom: 1px solid black; text-align: right'>SRF</th>
            @endif
            <th style='border-bottom: 1px solid black; text-align: right'>Net</th>
        </tr>
    </thead>
    <tbody>
        <?php $subdiscount = 0;  $sub_total_srf=0; ?>
            @foreach($lists as $list)
                @if($list->level == $head->level)
        <?php $total += $list->assessment;
        $x++; ?>
                <?php $discount += $list->discount; ?>
                <?php $subdiscount += $list->discount; ?>
                <tr>
                    <td>{{$x}}  </td>
                    <td align='left'>{{$list->idno}}</td>
                    <td>{{$list->lastname}}, {{$list->firstname}} {{$list->middlename}} {{$list->extensionname}}</td>
                    @if($department == "College Department")
                    <td>{{$list->program_code}}</th>
                        @endif
                    <td align='center'>{{$list->level}}</td>
                    @if($department != "College Department" || $department == "Senior High School")
                    <td align='center'>{{$list->section}}</td>
                    @endif
                    <td align='center'>{{$list->type_of_plan}}</td>
                    <?php $sub_total_srf = $sub_total_srf + $list->srf_amount; ?>
                    <?php $grand_total_srf = $grand_total_srf + $list->srf_amount; ?>
            
            <td align='right'>{{number_format($list->assessment+$list->srf_amount,2)}}</td>
                    <td align='right'>{{number_format($list->discount,2)}}</td>
            
            
            @if($department == "College Department" || $department == "Senior High School")
            <td align='right'>{{number_format($list->srf_amount,2)}}</td>
            @else
            <td align='right'>0.00</td>
            @endif
            
            
            <td align='right'>{{number_format($list->assessment-($list->discount),2)}}</td>
                </tr>
                @endif
            @endforeach
        <tr><td align="right" colspan="6">SUB TOTAL</td><td align="right"><strong>{{number_format($head->total+$sub_total_srf,2)}}</strong></td><td align="right"><strong>{{number_format($subdiscount,2)}}</strong></td>
            @if($department == "College Department" || $department == "Senior High School")
            <td align="right"><strong>{{number_format($sub_total_srf,2)}}</strong></td>
            @endif
            <td align="right"><strong>{{number_format($head->total-$head->discount,2)}}</strong></td></tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="6" style='border-top: 1px solid black' align="center">GRAND TOTAL</th>
            <td align='right' style='border-top: 1px solid black'><strong>{{number_format($total+$grand_total_srf,2)}}</strong></td>
            <td style='border-top: 1px solid black' align="right"><strong>{{number_format($discount,2)}}</strong></td>
            @if($department == "College Department" || $department == "Senior High School")
            <td align='right' style='border-top: 1px solid black'><strong>{{number_format($grand_total_srf,2)}}</strong></td>
            @endif
            <td align='right' style='border-top: 1px solid black'><strong>{{number_format($total-($discount),2)}}</strong></td>
        </tr>
    </tfoot>
</table>
@else
@endif
