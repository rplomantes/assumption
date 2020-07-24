<?php
$control=1;
?>

<div class='col-sm-12'><div class='pull-right'><a target='_blank' href="{{url('/registrar_college', array('graduates','print_batch_ranking', $date_of_grad))}}"><button class='btn btn-primary'>Print Batch Ranking</button></a></div></div>
<table class='table table-condensed'>
    <th></th>
    <th>ID Number</th>
    <th>Name</th>
    <th>GPA</th>
    <th>Rank</th>
    <?php $count = count($lists3); ?>
    <?php $ranking = 100/$count; ?>
    <?php $ranking = $ranking ?>
    @foreach($lists3 as $list)
    <tr>
        <td>{{$control++}}.</td>
        <td>{{$list['idno']}}</td>
        <td>{{strtoupper($list['lastname'])}}, {{strtoupper($list['firstname'])}} {{strtoupper($list['middlename'])}}</td>
        <td>{{$list['gpa']}}</td>
        <td>{{number_format($ranking*($control-1),10)}}</td>
    </tr>
    @endforeach
</table>