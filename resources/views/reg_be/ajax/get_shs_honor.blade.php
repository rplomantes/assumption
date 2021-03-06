<?php
$control = 1;
?>

<div class ="form form-group">
    <a href="javascript:void(0)" onclick = "export_shs_honor()" class="form btn btn-primary"> Export SHS Honor</a>
</div>
<div class='col-sm-4'>
<table class='table table-condensed'>
    <tr>
        <td>Level</td>
        <td>{{$level}}</td>
    </tr>
    <tr>
        <td>School Year</td>
        <td>{{$school_year}}</td>
    </tr>
</table>
</div>
<table class='table table-condensed'>
    <tr>
        <td></td>
        <td rowspan="2">ID Number</td>
        <td rowspan="2">Name</td>
        <td align='center' colspan="4">Academic Standing</td>
        <td align='center' colspan="4">Student Activities</td>
        <td align='center' rowspan="2">Total Average</td>
        <td align='center' rowspan="2">Rank</td>
        <td align='center' rowspan="2">Section</td>
        <td align='center' rowspan="2">Strand</td>
    <tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td align='center'>Sem 1</td>
        <td align='center'>Sem 2</td>
        <td align='center'>Ave</td>
        <td align='center'>0.7</td>
        <td align='center'>Sem 1</td>
        <td align='center'>Sem 2</td>
        <td align='center'>Ave</td>
        <td align='center'>0.3</td>
    </tr>
    <?php $count = count($lists3); ?>
    <?php $ranking = 1; ?>
    <?php $previous_gpa = 0; ?>
    <?php $skip = 0; ?>
    @foreach($lists3 as $list)
    <tr>
        <td>{{$control++}}.</td>
        <td>{{$list['idno']}}</td>
        <td>{{strtoupper($list['lastname'])}}, {{strtoupper($list['firstname'])}} {{strtoupper($list['middlename'])}}</td>
        <td align='center'>{{number_format($list['acadSem1'],3)}}</td>
        <td align='center'>{{number_format($list['acadSem2'],3)}}</td>
        <td align='center'>{{number_format($list['acadWhole'],3)}}</td>
        <td align='center'>{{number_format($list['acadWholeAve'],3)}}</td>
        <td align='center'>{{number_format($list['saSem1'],2)}}</td>
        <td align='center'>{{number_format($list['saSem2'],2)}}</td>
        <td align='center'>{{number_format($list['saWhole'],2)}}</td>
        <td align='center'>{{number_format($list['saWholeAve'],2)}}</td>
        <td align='center'>{{number_format($list['totalAve'],3)}}</td>
        @if($previous_gpa == $list['totalAve'])
            <?php $skip=$skip+1 ?>
            <td>{{$ranking-1}}</td>
        @else
            @if($skip > 0)
                <td>{{$ranking++ + $skip}}</td>
                <?php $ranking+= $skip; ?>
                <?php $skip=0; ?>
            @else
                <?php $skip=0; ?>
                <td>{{$ranking++}}</td>
            @endif
        @endif
        <td>{{$list['section']}}</td>
        <td>{{$list['strand']}}</td>
    </tr>
    @endforeach
</table>