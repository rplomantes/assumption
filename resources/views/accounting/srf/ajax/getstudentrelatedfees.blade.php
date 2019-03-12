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
<h5>S.Y. {{$school_year}} - {{$school_year + 1}} {{$period}}</h5>




@if($department == "Senior High School")
<?php $total = 0; ?>
@foreach($levels as $value)
<?php
$x = 0;
$ledgers = \App\Ledger::groupBy(array('strand'))->where('department', $department)->where('school_year', $school_year)->where('period', $period)
        ->where(function ($query){
                        $query->where('category_switch', env('SRF_FEE'))
                              ->orWhere('category_switch', env('SRF_FEE')+10);
                    })
        ->where('category', 'SRF')
                ->selectRaw('strand,sum(amount) as amount')->where('level', $value)->get();
?>
<table width='30%' cellpadding='0' cellspacing='0'>
    <thead>
        <tr><td colspan="2"><h4>{{$value}}</h4></td></tr>
        <tr>
            <th width='10%' style='border-bottom: 1px solid black'>  </th>
            <th width='45%' style='border-bottom: 1px solid black'>Strand</th>
            <th width='45%' style='border-bottom: 1px solid black' align='center'>Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php $sub_total = 0; ?>
        @foreach($ledgers as $ledger)
        <?php
        $sub_total += $ledger->amount;
        $total += $ledger->amount;
        $x++;
        ?>
        <tr>
            <td>{{$x}}</td>
            <td>{{$ledger->strand}}</td>
            <td align="right">{{number_format($ledger->amount)}}</td>
        </tr>
        @endforeach
        <tr><td align="right" colspan="2">SUB TOTAL</td><td align="right"><strong>{{number_format($sub_total)}}</strong></td></tr>
    </tbody>
    @endforeach
    <tfoot>
        <tr>
            <th colspan="2" style='border-top: 1px solid black' align="center">GRAND TOTAL</th>
            <td align='right' style='border-top: 1px solid black'><strong>{{number_format($total)}}</strong></td>
        </tr>
    </tfoot>
</table>
@else

<?php $total = 0; ?>
@foreach($groups as $group)
<?php
$x = 0;
if ($group == "Laboratory Fee") {
    $ledgers = \App\Ledger::
            groupBy(array('subsidiary'))
            ->where('school_year', $school_year)
            ->where('period', $period)
            ->where(function ($query){
                        $query->where('category_switch', env('SRF_FEE'))
                              ->orWhere('category_switch', env('SRF_FEE')+10);
                    })
            ->where('category', 'SRF')
            ->where('subsidiary', 'like', '%Lab Fee%')
                ->selectRaw('subsidiary,sum(amount) as amount')
            ->where('amount', '>', 0)
            ->orderBy('subsidiary', 'asc')
            ->orderBy('amount', 'asc')
            ->get();
} else {
    $ledgers = \App\Ledger::
            groupBy(array('subsidiary'))
            ->where('department', $group)
            ->where('school_year', $school_year)
            ->where('period', $period)
            ->where(function ($query){
                        $query->where('category_switch', env('SRF_FEE'))
                              ->orWhere('category_switch', env('SRF_FEE')+10);
                    })
            ->where('category', 'SRF')
            ->where('subsidiary', 'not like', '%Lab Fee%')
            ->selectRaw('subsidiary,sum(amount) as amount')
                ->where('department', $group)
            ->where('amount', '>', 0)
            ->orderBy('subsidiary', 'asc')
            ->orderBy('amount', 'asc')
            ->get();
}
?>
<table width='70%' cellpadding='0' cellspacing='0'>
    <thead>
        <tr><td colspan="2"><h4>{{$group}}</h4></td></tr>
        <tr>
            <th width='4%' style='border-bottom: 1px solid black'>  </th>
            <th width='78%' style='border-bottom: 1px solid black'>Course</th>
            <th width='18%' style='border-bottom: 1px solid black' align='center'>Amount</th>
        </tr>
    </thead>
    <tbody>
        <?php $sub_total = 0; ?>
        @foreach($ledgers as $ledger)
        <?php  
        if($group != "Laboratory Fee"){
            $check = \App\Curriculum::where('course_code', $ledger->subsidiary)->first();
            if(count($check)>0){
                $course_name = "(". $check->course_name . ")";
            }else{
                $course_name = "";
            }
        }else{
        $course_name = "";
        }
        ?>
        <?php
        $sub_total += $ledger->amount;
        $total += $ledger->amount;
        $x++;
        ?>
        <tr>
            <td>{{$x}}.</td>
            <td>{{$ledger->subsidiary}}&nbsp;{{$course_name}}</td>
            <td align="right">{{number_format($ledger->amount)}}</td>
        </tr>
        @endforeach
        <tr><td align="right" colspan="2">SUB TOTAL</td><td align="right"><strong>{{number_format($sub_total)}}</strong></td></tr>
    </tbody>
    @endforeach
    <tfoot>
        <tr>
            <th colspan="2" style='border-top: 1px solid black' align="center">GRAND TOTAL</th>
            <td align='right' style='border-top: 1px solid black'><strong>{{number_format($total)}}</strong></td>
        </tr>
    </tfoot>
</table>
@endif