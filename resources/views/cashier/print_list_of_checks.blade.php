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

    <body>
        <div>    
            <!--<div style='float: left; margin-left: 150px;'><img src="{{public_path('/images/assumption-logo.png')}}"></div>-->
            <div style='float: left; margin-top:12px; margin-left: 10px' align='Left'><span id="schoolname">Assumption College</span> <br><small> San Lorenzo Drive, San Lorenzo Village, Makati City</small>
                <br><br>
                <b>LIST OF CHECKS</b>    
                <br>
                Date Covered : {{$date_from}} to {{$date_to}}
            </div>
        </div>
        <p>
            <br><br><br><br><br><br>
        </p>
        @if(count($payments)>0)
        <?php 
        $totalAmount = 0;
        $number = 1;
        ?>
        <table width="100%" id="example1" border="1" cellspacing="0" cellpadding="2" class="table table-responsive table-striped">
            <tr>
                <td width="10px"></td>
                <td><b>Bank</b></td>
                <td><b>Check Number</b></td>
                <td><b>Amount</b></td>
                @if(Auth::user()->accesslevel==env("ACCTNG_STAFF"))
                <th>Posted By</th>
                @endif
            </tr>
            @foreach($payments as $payment)
            <?php
            $totalAmount = $totalAmount + $payment->check_amount;
            ?>
            <tr>
                <td>{{$number++}}.</td>
                <td>{{$payment->bank_name}}</td>
                <td>{{$payment->check_number}}</td>
                <td align="right">{{number_format($payment->check_amount,2)}}</td>
                @if(Auth::user()->accesslevel==env("ACCTNG_STAFF"))
                <td>{{$payment->posted_by}}</td>
                @endif
            </tr>
            @endforeach
            <tr>
                <td colspan="3"><b>Total Amount</b></td>
                <td><b>{{number_format($totalAmount,2)}}</b></td>
                @if(Auth::user()->accesslevel==env("ACCTNG_STAFF"))
                <th></th>
                @endif
            </tr>
        </table>    
        @else
        <h5>No Checks Collection Within Those Dates</h5>
        @endif
        <table boeder="0">
            <tr><td>Prepared By:</td></tr>
            <tr><td align="right">{{Auth::user()->firstname}} {{Auth::user()->lastname}}</td></tr>
        </table>    
    </body>
</html>    
