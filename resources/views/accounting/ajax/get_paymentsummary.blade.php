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
    @foreach($heads as $head)
    <?php $x = 0;?>
    <thead>
        <tr><td colspan="6"><h4>{{$head->level}}</h4></td></tr>
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
                    <td align='right'>{{number_format($list->assessment,2)}}</td>
                    <td><strong>STILL IN DEVELOPMENT...</strong></td>
                </tr>
            @endforeach
            <tr><td align="right" colspan="4">SUB TOTAL</td><td align="right"><strong>{{number_format($head->total,2)}}</strong></td><td></td></tr>
        @endforeach
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4" style='border-top: 1px solid black' align="center">GRAND TOTAL</th>
            <td align='right' style='border-top: 1px solid black'><strong>{{number_format($total,2)}}</strong></td>
            <td style='border-top: 1px solid black'></td>
        </tr>
    </tfoot>
</table>
<br>
@endif