<?php
$totaldm = 0;
$totalcanceled = 0;
?>
<html>        
    <style>
        table  .decimal{
            text-align: right;
            padding-right: 10px;
        }
    </style>
    <style>
        body {
            font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
        }
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
        .underline {
            border-top: 1px solid transparent;
            border-left: 1px solid transparent;
            border-right: 1px solid transparent;
        }
        .top-line {
            border-bottom: 1px solid transparent;
            border-left: 1px solid transparent;
            border-right: 1px solid transparent;
            text-align: center;
        }
        .no-border {
            border-top: 1px solid transparent;
            border-left: 1px solid transparent;
            border-right: 1px solid transparent;
            border-bottom: 1px solid transparent;
        }
        table td{
            font-size: 10pt;
        }
        table th{
            font-size: 10pt;
        }
    </style>
    <div>    
        <!--<div style='float: left; margin-left: 150px;'><img src="{{public_path('/images/assumption-logo.png')}}"></div>-->
        <div style='float: left; margin-top:12px; margin-left: 10px' align='Left'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village, Makati City</small>
            <br><br>
            <b>DEBIT SUMMARY</b>    
            <br>
            Date Covered : {{$date_from}} to {{$date_to}}
        </div>
    </div>
    <body>
        <p>
            <br><br><br><br><br><br>
        </p>
        <table width="100%" id="example1" border="1" cellspacing="0" cellpadding="2" class="table table-responsive table-striped">
            <thead>
                <tr>
                    <th>DM No</th>
                    <th>ID No</th>
                    <th>Name</th>
                    <th>Explanation</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Posted By</th>
                </tr>
            </thead>
            <tbody>
                @if(count($debits)>0)
                @foreach($debits as $debit)
                <?php $user = \App\User::where('idno', $debit->idno)->first(); ?>
                <tr>
                    <td>{{$debit->dm_no}}</td>
                    <td>{{$debit->idno}}</td>
                    <td>{{$user->getFullNameAttribute()}}</td>
                    <td>{{$debit->explanation}}</td>
                    <td>{{$debit->amount}}</td>
                    @if($debit->is_reverse=="0")
                    <?php $totaldm = $totaldm + $debit->amount; ?>
                    <td>OK</td>
                    @else
                    <?php $totalcanceled = $totalcanceled + $debit->amount; ?>
                    <td>Canceled</td>
                    @endif
                    <td>{{$debit->posted_by}}</td>
                </tr>
                @endforeach
                @else
                @endif
            </tbody>
        </table> 
        <span>
            <br>Summary of Transactions
        </span>
        <table cellspacing="0" border="1" cellpadding="2" width="20%" class="table table-responsive">
            <tr>
                <td>Total DM</td>
                <td align="right"><strong>{{number_format($totaldm,2)}}</strong></td>
            </tr>
            <tr>
                <td>Total Canceled</td>
                <td align="right"><strong>{{number_format($totalcanceled,2)}}</strong></td>
            </tr>
        </table>  
        <br><br><br>Prepared by: <br><br><b>{{Auth::user()->firstname}} {{Auth::user()->lastname}} {{Auth::user()->middlename}}</b>
        <br><br><br><br>Checked by: <br><br><b></b>
    </body>
</html>
