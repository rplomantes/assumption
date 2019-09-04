<?php
$loop_cast = 1;

?>
<h4>{{$department}}</h4>
@if($department == "College Department" or $department == "Senior High School") 
<h4>S.Y. {{$school_year}}-{{$school_year +1}} - {{$period}}</h4>
@endif
@if($department == "Junior High School" or $department == "Elementary" or $department == "Pre School") 
<h4>S.Y. {{$school_year}}-{{$school_year +1}}</h4>
@endif

@if(count($lists)>0)
<?php $total = 0;
$x = 0;
?>
<table width='100%' cellpadding='0' cellspacing='0' class="table table-striped table-condensed">
    <?php $x = 0;?>
    <thead>
        <tr>
            <th style='border-bottom: 1px solid black'>  </th>
            <th style='border-bottom: 1px solid black'>ID No.</th>
            <th style='border-bottom: 1px solid black'>Name</th>
            <th style='border-bottom: 1px solid black' align='right'>Plan</th>
            <th style='border-bottom: 1px solid black; text-align: right'>Assessment</th>
            <th style='border-bottom: 1px solid black'>Payment</th>
        </tr>
    </thead>
    <tbody>
            @foreach($lists as $list)
                <?php $total += $list->assessment; $x++; ?>
                <tr>
                    <td>{{$x}}.  </td>
                    <td align='left'>{{$list->idno}}</td>
                    <td>{{ucwords(strtolower($list->lastname))}}, {{ucwords(strtolower($list->firstname))}} {{ucwords(strtolower($list->middlename))}} {{ucwords(strtolower($list->extensionname))}}</td>
                    <td>{{$list->type_of_plan}}</td>
                    <?php $assessment = \App\Ledger::SelectRaw('sum(amount)as amount')
                            ->where('idno', $list->idno)->where('school_year', $school_year)
                            ->where('period',$period)
                            ->where(function($query) {
                                $query->where('category_switch', 1)
                                ->orWhere('category_switch', 2)
                                ->orWhere('category_switch', 3)
                                ->orWhere('category_switch', 4)
                                ->orWhere('category_switch', 5)
                                ->orWhere('category_switch', 6)
                                ->orWhere('category_switch', 11)
                                ->orWhere('category_switch', 12)
                                ->orWhere('category_switch', 13)
                                ->orWhere('category_switch', 14)
                                ->orWhere('category_switch', 15)
                                ->orWhere('category_switch', 16);
                            })->first(); ?>
                    <td align='right'>{{number_format($assessment->amount,2)}}</td>
                    <td align="left">
                        <table border="1" width="100%">
                            <td></td>
                        </table>
                    </td>
                </tr>
            @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="6" style='border-top: 1px solid black' align="center"></th>
            <td></td>
        </tr>
    </tfoot>
</table>
<br>
@endif