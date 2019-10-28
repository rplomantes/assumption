@if(count($lists)>0)
<?php $total = 0; $discount = 0;  $grandCounter = 0;
$x = 0 ?>
<table>
    <tr><td><strong colspan="4">Assumption College</strong></td></tr>
    <tr><td colspan="4">{{$department}}</td></tr>
    <tr><td colspan="5">Set Up List - {{$subsidiary}}</td></tr>
    @if($department == "College Department" or $department == "Senior High School")
    <tr><td colspan="5"><h5>{{$school_year}} - {{$period}}</h5>
    @endif
    @if($department == "Junior High School" or $department == "Elementary" or $department == "Pre School")
    <tr><td colspan="5"><h5>{{$school_year}}</h5>
    @endif
</td></tr>
    <tr></tr>
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
            <th style='border-bottom: 1px solid black'>Plan</th>
            @if($department != "College Department")
            <th style='border-bottom: 1px solid black' align="center">Section</th>
            @endif
            <th style='border-bottom: 1px solid black' align='right'>Amount</th>
            <th style='border-bottom: 1px solid black' align='right'>Discount</th>
            <th style='border-bottom: 1px solid black' align='right'>Net</th>
        </tr>
    </thead>
    <tbody>
            <?php $subdiscount = 0; ?>
            @foreach($lists as $list)
                @if($list->level == $head->level)
                <?php $total += $list->amount; $x++;  $grandCounter++; ?>
                <?php $discount += $list->discount; ?>
                <?php $subdiscount += $list->discount; ?>
                <tr>
                    <td>{{$x}}  </td>
                    <td align='left'>{{$list->idno}}</td>
                    <td>{{$list->lastname}}, {{$list->firstname}} {{$list->middlename}} {{$list->extensionname}}</td>
                    @if($department == "College Department")
                    <td>{{$list->program_code}} </th>
                        @endif
                    <td>{{$list->type_of_plan}}</td>
                    @if($department != "College Department")
                    <td align='center'>{{$list->section}}</td>
                    @endif
                    <td align='right'>{{$list->amount}}</td>
                    <td align='right'>{{$list->discount}}</td>
                    <td align='right'>{{$list->amount-$list->discount}}</td>
                </tr>
                @endif
            @endforeach
            <tr><td align="right" colspan="5">SUB TOTAL</td><td align="right"><strong>{{$head->total}}</strong></td><td align="right"><strong>{{$subdiscount}}</strong></td><td align="right"><strong>{{$head->total-$head->discount}}</strong></td></tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="5" style='border-top: 1px solid black'>GRAND TOTAL - {{$grandCounter}} Students</th>
            <td align='right' style='border-top: 1px solid black'><strong>{{$total}}</strong></td>
            <td style='border-top: 1px solid black' align="right"><strong>{{$discount}}</strong></td>
            <td align='right' style='border-top: 1px solid black'><strong>{{$total-$discount}}</strong></td>
        </tr>
    </tfoot>
</table>
@else
@endif