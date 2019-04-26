@if(count($lists)>0)
<?php $total = 0;
$x = 0;
?>
<table width='100%' cellpadding='0' cellspacing='0'>
    <tr><td><strong colspan="7">Assumption College</strong></td></tr>
    <tr><td colspan="7">{{$department}}</td></tr>
    <tr><td colspan="7">Unused Reservations</td></tr>
    <tr><td colspan="7"><h5>S.Y. {{$school_year}} - {{$school_year + 1}} {{$period}}</h5>
</td></tr>
    </tr>
    <?php $x = 0;?>
    <thead>
        <tr>
            <th width="5" style='border-bottom: 1px solid black'> </th>
            <th width="10"  style='border-bottom: 1px solid black'>ID No.</th>
            <th width="60"  style='border-bottom: 1px solid black'>Name</th>
            @if($department == "College Department")
            <th width="20" style='border-bottom: 1px solid black'>Course</th>
            @endif
            <th width="10"  style='border-bottom: 1px solid black'>Level</th>
            <th width="15" style='border-bottom: 1px solid black'>OR Number</th>
            <th width="15" style='border-bottom: 1px solid black'>Date</th>
            <th width="15" style='border-bottom: 1px solid black; text-align: right'>Amount</th>
        </tr>
    </thead>
    <tbody>
            @foreach($lists as $list)
                <?php $total += $list->amount; $x++; ?>
                <tr>
                    <td>{{$x}}. </td>
                    <td align='left'>{{$list->idno}}</td>
                    <td>{{$list->lastname}}, {{$list->firstname}} {{$list->middlename}} {{$list->extensionname}}</td>
                    @if($department == "College Department")
                    <td>{{$list->program_code}} </td>
                    @endif
                    <td>{{$list->level}}</td>
                    <td>{{$list->receipt_no}}</td>
                    <td>{{$list->transaction_date}}</td>
                    <td align='right'>{{$list->amount}}</td>
                </tr>
            @endforeach
            <tr>
                <td style="border-top:1px solid black" colspan="6"><strong>Total</strong></td>
                <td style="border-top:1px solid black" align='right'><strong>{{$total}}</strong></td>
            </tr>
    </tbody>
</table>
<br><br>

@else
@endif