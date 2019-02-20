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
    body{
        font-size:9pt;
    }
</style>
<div>
    <div style='float: left; margin-left: 150px;'><img src="{{public_path('/images/assumption-logo.png')}}"></div>
    <div style='float: left; margin-top:12px; margin-left: 10px' align='center'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village<br> Makati City</small><br><br><b>UNUSED RESERVATIONS</b><br>As of {{date('F d, Y')}}</div>
</div>
<?php $counter = 1; ?>
<body>
    <div>
        <table width="100%" style="margin-top: 120px" border="1" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="10%">ID Number</th>
                    <th>Name</th>
                    <th>Level</th>
                    <th width="20%">Transaction Date</th>
                    <th width="10%">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reservations as $reservation)
                <tr>
                    <td>{{$counter}}. <?php $counter++; ?></td>
                    <td>{{$reservation->idno}}</td>
                    <td>{{strtoupper($reservation->lastname)}}, {{$reservation->firstname}} {{$reservation->middlename}}</td>
                    <td>{{$reservation->level}}</td>
                    <td>{{$reservation->transaction_date}}</td>
                    <td>{{number_format($reservation->amount,2)}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
