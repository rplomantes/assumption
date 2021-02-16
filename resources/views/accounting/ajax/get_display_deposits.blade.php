<h4>{{$department}}</h4>
@if($department == "College Department" or $department == "Senior High School") 
<h4>S.Y. {{$school_year}}-{{$school_year +1}} - {{$period}}</h4>
@endif
@if($department == "Junior High School" or $department == "Elementary" or $department == "Pre School") 
<h4>S.Y. {{$school_year}}-{{$school_year +1}}</h4>
@endif
As of {{date('F d, Y')}}
@if(count($lists)>0)
<?php $total = 0;
$x = 0;
?>
<table width='100%' cellpadding='0' cellspacing='0'>
    @foreach($heads as $head)
    <?php $x = 0; $prev_idno = "";?>
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
            <th style='border-bottom: 1px solid black'>OR Number</th>
            <th style='border-bottom: 1px solid black'>Date</th>
            <th style='border-bottom: 1px solid black; text-align: right'>Amount</th>
            <th style='border-bottom: 1px solid black; text-align: right'>Status</th>
        </tr>
    </thead>
    <tbody>
            @foreach($lists as $list)
                @if($list->level == $head->level)
                <?php $total += $list->amount; ?>
                <tr>
                    <td>@if($prev_idno != $list->idno) <?php $x++; ?> {{$x}} @endif  </td>
                    <td align='left'>{{$list->idno}}</td>
                    <td>{{$list->lastname}}, {{$list->firstname}} {{$list->middlename}} {{$list->extensionname}}</td>
                    @if($department == "College Department")
                    <td>{{$list->program_code}}</td>
                    @endif
                    <td>{{$list->level}}</td>
                    @if(Auth::user()->accesslevel == env("ADMISSION_HED"))
                    <td>{{$list->receipt_no}}</td>
                    @else
                    <td><a href="{{url('cashier', array('viewreceipt',$list->reference_id))}}">{{$list->receipt_no}}</a></td>
                    @endif
                    <td>{{$list->transaction_date}}</td>
                    <td align='right'>{{number_format($list->amount,2)}}</td>
                    <td align='right'>
                        @switch($list->is_consumed)
                        @case(1)
                        Used
                        @break
                        @case(0)
                        Unused
                        @break
                        @case(2)
                        Tag as Used
                        @break
                        @endswitch
                    </td>
                </tr>
                @endif
                <?php $prev_idno = $list->idno; ?>
            @endforeach
            <tr><td align="right" @if($department == "College Department") colspan="7" @else colspan="6" @endif>SUB TOTAL</td><td align="right"><strong>{{number_format($head->total,2)}}</strong></td></tr>
    @endforeach
            <tr>
                <td style="border-top:1px solid black" @if($department == "College Department") colspan="7" @else colspan="6" @endif><strong>Total</strong></td>
                <td style="border-top:1px solid black" align='right'><strong>{{number_format($total,2)}}</strong></td><td style="border-top:1px solid black"></td>
            </tr>
    </tbody>
</table>
<br><br>

@else
@endif