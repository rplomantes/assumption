<?php
$control=1;
?>
<table class='table table-condensed'>
    <tr>
    <th></th>
    <th>ID Number</th>
    <th>Name</th>
    <th>Average</th>
    <th>Rank</th>
    <th>Section</th>
    <th>Strand</th>
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
        <td>{{number_format($list['gpa'],3)}}</td>
        @if($previous_gpa == $list['gpa'])
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
    <?php $previous_gpa = $list['gpa']; ?>
    @endforeach
</table>