<strong>Assumption College</strong><br>
    {{$department}}<br/>
<h4>List of Students with Sibling Discount</h4>
@if(count($lists)>0)
<?php $total = 0; $discount = 0;  $grandCounter = 0;
$x = 0 ?>
<table>
    @foreach($heads as $head)
    <?php $x = 0;?>
    <thead>
        <tr><td><h4>{{$head->level}}</h4></td></tr>
        <tr>
            <th>  </th>
            <th>ID No.</th>
            <th>Name</th>
            @if($department == "College Department")
            <th>Course</th>
            @endif
            <th>Plan</th>
            @if($department != "College Department")
            <th align="center">Section</th>
            @endif
            <th align='right'>Amount</th>
            <th align='right'>Discount</th>
            <th align='right'>Net</th>
        </tr>
    </thead>
    <tbody>
            <?php $subdiscount = 0; ?>
            @foreach($lists as $list)
            @if($list->discount > 0)
                @if($list->level == $head->level)
                <?php $total += $list->amount; $x++; $grandCounter++; ?>
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
                @endif
            @endforeach
            <tr><td align="right">SUB TOTAL</td><td align="right"><strong>{{$head->total}}</strong></td><td align="right"><strong>{{$subdiscount}}</strong></td><td align="right"><strong>{{$head->total-$head->discount}}</strong></td></tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th>GRAND TOTAL - {{$grandCounter}} Students</th>
            <td align='right'><strong>{{$total}}</strong></td>
            <td align="right"><strong>{{$discount}}</strong></td>
            <td align='right'><strong>{{$total-$discount}}</strong></td>
        </tr>
    </tfoot>
</table>
<br><br>

@else
@endif