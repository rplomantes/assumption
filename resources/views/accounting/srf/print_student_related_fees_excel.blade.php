<table width='30%' cellpadding='0' cellspacing='0'>

    <tr><td><strong>Assumption College</strong></td></tr>
    <tr><td>{{$department}}</td></tr>
    <tr><td><h5>S.Y. {{$school_year}} - {{$school_year + 1}} {{$period}}</h5></td></tr>

    @if($department == "Senior High School")
    <?php $total = 0; ?>
    @foreach($levels as $value)
    <?php
    $x = 0;
    $ledgers = \App\Ledger::groupBy(array('strand'))->where('department', $department)->where('school_year', $school_year)->where('period', $period)->where('category_switch', env('SRF_FEE'))->where('category', 'SRF')
                    ->selectRaw('strand,sum(amount) as amount')->where('level', $value)->get();
    ?>
    <thead>
        <tr><td colspan="2"><h4>{{$value}}</h4></td></tr>
        <tr>
            <th width='10%' style='border-bottom: 1px solid black'>  </th>
            <th width='45%' style='border-bottom: 1px solid black'>Strand</th>
            <th width='45%' style='border-bottom: 1px solid black'>Amount</th>
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
            <td align="right">{{$ledger->amount}}</td>
        </tr>
        @endforeach
        <tr><td align="right" colspan="2">SUB TOTAL</td><td align="right"><strong>{{$sub_total}}</strong></td></tr>
    </tbody>
    @endforeach
    <tfoot>
        <tr>
            <th colspan="2" style='border-top: 1px solid black' align="center">GRAND TOTAL</th>
            <td align='right' style='border-top: 1px solid black'><strong>{{$total}}</strong></td>
        </tr>
    </tfoot>
    @endif


</table>