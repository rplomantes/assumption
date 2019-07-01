<style>
    
        body {
            font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
            font-size: 8pt;
        }
</style>

<strong>Assumption College</strong><br>
{{$department}}<br>
@if($department == "College Department" or $department == "Senior High School")
{{$school_year}}-{{$school_year+1}}, {{$period}}
@endif
@if($department == "Junior High School" or $department == "Elementary" or $department == "Pre School") 
{{$school_year}}-{{$school_year+1}}
@endif
<h3>Unused Reservations</h3>

As of {{date('F d, Y')}}

@if(count($lists)>0)
<?php $total = 0;
$x = 0;
?>
<table width='100%' cellpadding='0' cellspacing='0'>
    <?php $x = 0;?>
    <thead>
        <tr><td colspan="6"><h4></h4></td></tr>
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
                    <td align='right'>{{number_format($list->amount,2)}}</td>
                    <td align='right'>
                        @switch($list->is_consumed)
                        @case(1)
                        Used
                        @break
                        @case(0)
                        Unused
                        @break
                        @endswitch
                    </td>
                </tr>
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