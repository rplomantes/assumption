<?php
$control = 1;
?>
<style>
    img {
        display: block;
        max-width:230px;
        max-height:95px;
        width: auto;
        height: auto;
    }
    #schoolname{
        font-size: 18pt; 
        font-weight: bolder;
    }
    .table, .th, .td {
        border-collapse: collapse;
        font: 9pt;
    }
    .table2, .th, .td {
        border: 1px solid black;
        border-collapse: collapse;
        font: 9pt;
    }

    .page_break { page-break-before: always; }
</style>
<?php $count = count($lists3); ?>
<?php $ranking = 100 / $count; ?>
<?php $ranking = $ranking; ?>
<div>    
    <div style='float: left; margin-left: 150px;'><img src="{{public_path('/images/assumption-logo.png')}}"></div>
    <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><br><b>BATCH RANKING</b></div>
</div>  
<div>
    <table class='table' border="1" width="100%" cellspacing='0' cellpadding='0' style='margin-top: 155px;'>
        <thead>
            <tr>
                <th></th>
                <th align='center'>ID Number</th>
                <th>Name</th>
                <th align='center'>GPA</th>
                <th>Rank</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lists3 as $list)
            <tr>
                <td align='right'>{{$control++}}.</td>
                <td align='center'>{{$list['idno']}}</td>
                <td>{{strtoupper($list['lastname'])}}, {{strtoupper($list['firstname'])}} {{strtoupper($list['middlename'])}}</td>
                <td align='center'>{{$list['gpa']}}</td>
                <td>{{number_format($ranking*($control-1),10)}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>