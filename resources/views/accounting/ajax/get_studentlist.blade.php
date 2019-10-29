<h4>{{$department}}</h4>
@if($department == "College Department" or $department == "Senior High School") 
<h4>S.Y. {{$school_year}}-{{$school_year +1}} - {{$period}}</h4>
@endif
@if($department == "Junior High School" or $department == "Elementary" or $department == "Pre School") 
<h4>S.Y. {{$school_year}}-{{$school_year +1}}</h4>
@endif

@if(count($lists)>0)
<?php $total = 0;
$discount = 0;
$x = 0;
$grand_total_srf=0;
?>
<table width='100%' cellpadding='0' cellspacing='0'>
    @foreach($heads as $head)
<?php $x = 0; ?>
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
            <td>{{$list->program_code}} </td>
            @endif
            <td>{{$list->level}}</td>
            @if($department != "College Department" || $department == "Senior High School")
            <td align='center'>{{$list->section}}</td>
            @endif
            <td>{{$list->type_of_plan}}</td>

            <?php $srf_amount = 0; ?>
            @if($department == "College Department" || $department == "Senior High School")
            <?php
            if($department == "College Department"){
                $lists_srf = DB::select("SELECT l.assessment FROM users u, (SELECT idno, SUM(amount) AS assessment FROM ledgers WHERE category_switch IN (4,14) AND category = 'SRF' AND school_year = '$school_year' AND period = '$period' GROUP BY idno) l, college_levels c WHERE c.idno = u.idno AND l.assessment !=0.00 AND u.idno = l.idno AND c.school_year = '$school_year' AND c.period = '$period' AND (c.status = '3' or c.status = '4') and c.idno = $list->idno");
            }else{
                $lists_srf = DB::select("SELECT l.assessment FROM users u, (SELECT idno, SUM(amount) AS assessment FROM ledgers WHERE category_switch IN (4,14) AND category = 'SRF' AND school_year = '$school_year' AND period = '$period' GROUP BY idno) l, bed_levels c WHERE c.idno = u.idno AND l.assessment !=0.00 AND u.idno = l.idno AND c.school_year = '$school_year' AND c.period = '$period' AND (c.status = '3' or c.status = '4') and c.idno = $list->idno");
            }
//            $heads_srf = DB::select("SELECT c.level, SUM(l.assessment) AS 'total', SUM(l.discount) AS 'discount' FROM (SELECT idno, SUM(amount) AS 'assessment', SUM(discount) AS discount FROM ledgers WHERE category_switch IN (4,14) AND category = 'SRF' AND school_year = '$school_year' AND period = '$period' GROUP BY idno) l, (SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr, college_levels c WHERE l.assessment != 0.00 AND c.idno = l.idno AND ctr.level = c.level AND c.school_year = '$school_year' AND c.period = '$period' AND c.status = '3' and c.idno = $list->idno GROUP BY c.level, ctr.sort_by ORDER BY ctr.sort_by");
            ?>
                @if(count($lists_srf)>0)
                    @foreach($lists_srf as $list_srf)
                    <?php $srf_amount = $list_srf->assessment; ?>
                    <?php $sub_total_srf = $sub_total_srf + $list_srf->assessment; ?>
                    <?php $grand_total_srf = $grand_total_srf + $list_srf->assessment; ?>
                    @endforeach
                @endif
            @endif
            
            <td align='right'>{{number_format($list->assessment+$srf_amount,2)}}</td>
            <td align='right'>{{number_format($list->discount,2)}}</td>
            
            
            @if($department == "College Department" || $department == "Senior High School")
            <td align='right'>{{number_format($srf_amount,2)}}</td>
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
<br><br>

@else
@endif
