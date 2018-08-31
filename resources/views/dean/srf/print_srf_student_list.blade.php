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

</style>
<?php $number=1; ?>
<div>    
    <div style='float: left; margin-left: 150px;'><img src="{{url('/images','assumption-logo.png')}}"></div>
    <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><br><b>Subject Related Fee</b></div>
</div>
<div>
    <center style='margin-top: 135px;'>
        <b>{{$course_code}}-{{$course_name}}</b><br>
        S.Y. {{$school_year}}-{{$school_year+1}}, {{$period}}
    </center>
    <table class='table' border="1" width="100%" cellspacing='0' cellpadding='5' style='margin-top: 20px;'>
        <tr>
            <th>#</th>
            <th>ID Number</th>
            <th>Name</th>
            <th><div align="center">SRF</div></th>
            <th><div align="center">Lab Fee</div></th>
            <th>Balance</th>
        </tr>
        @foreach($lists as $list)
        <?php
        $totalbalance = 0;
        $balance = \App\Ledger::where('idno', $list->idno)->where('subsidiary', $course_code)->where('school_year', $school_year)->where('period', $period)->first();
        ?>
        @if(count($balance)>0){
        <?php $deduct = $balance->payment+$balance->debit_memo+$balance->discount;
        $totalbalance = $balance->amount-$deduct;
        ?>
        @endif
        <tr>
            <td>{{$number++}}</td>
            <td>{{$list->idno}}</td>
            <td>{{$list->lastname}}, {{$list->firstname}} {{$list->middlename}}</td>
            <td>{{$list->srf}}</td>
            <td>{{$list->lab_fee}}</td>
            <td>{{$totalbalance}}</td>
        </tr>
        @endforeach
    </table>
</div>