<?php
$total_depo = 0;
$total_other = 0;
$total_misc = 0;
?>
<html>
    <head>
        <style>
            body {
                font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
                font-size: 10pt;
            }
            .tab {
                margin-left: 30px;
            }
        </style>
    </head>
    <body>
        <b>ASSUMPTION COLLEGE INC.<br>
            SCHEDULE OF FEES</b>
        <hr><br>
        {{$program_code}}<br>
        {{$level}}<br><br>
        <table width="60%">
            <tr>
                <th>Tuition Fee</th>
                <td align="right"><div style="border-bottom: 1px solid black">{{number_format($amount,2)}}</div></td>
            </tr>
            
            
            
            
            <tr>
                <th>Miscellaneous Fees</th>
                <td></td>
            </tr>
            @foreach ($miscellaneous_fees as $miscellaneous_fee)
            <?php $total_misc = $total_misc + $miscellaneous_fee->amount; ?>
            <tr>
                <td><div class='tab'>{{$miscellaneous_fee->subsidiary}}</div></td>
                <td align="right"><div style="border-bottom: 1px solid black">{{$miscellaneous_fee->amount}}</div></td>
            </tr>
            @endforeach
            @foreach ($other_collections as $other_collection)
            @if($other_collection->category == "Miscellaneous Fees")
            <?php $total_misc = $total_misc + $other_collection->amount; ?>
            <tr>
                <td><div class='tab'>{{$other_collection->subsidiary}}</div></td>
                <td align="right"><div style="border-bottom: 1px solid black">{{$other_collection->amount}}</div></td>
            </tr>
            @endif
            @endforeach
            <tr>
                <th><div class='tab'>Total Miscellaneous Fees</div></th>
                <td align="right"><div style="border-bottom: 1px solid black">{{number_format($total_misc,2)}}</div></td>
            </tr>
            
            
            
            
            <tr>
                <th>Other Fees</th>
                <td></td>
            </tr>
            @foreach ($other_fees as $other_fee)
            <?php $total_other = $total_other + $other_fee->amount; ?>
            <tr>
                <td><div class='tab'>{{$other_fee->subsidiary}}</div></td>
                <td align="right"><div style="border-bottom: 1px solid black">{{$other_fee->amount}}</div></td>
            </tr>
            @endforeach
            @foreach ($other_collections as $other_collection)
            @if($other_collection->category == "Other Fees")
            <?php $total_other = $total_other + $other_collection->amount; ?>
            <tr>
                <td><div class='tab'>{{$other_collection->subsidiary}}</div></td>
                <td align="right"><div style="border-bottom: 1px solid black">{{number_format($other_collection->amount,2)}}</div></td>
            </tr>
            @endif
            @endforeach
            <tr>
                <th><div class='tab'>Total Other Fees</div></th>
                <td align="right"><div style="border-bottom: 1px solid black">{{number_format($total_other,2)}}</div></td>
            </tr>
            
            
            <tr>
                <th>Depository Fees</th>
                <td></td>
            </tr>
            @foreach ($depository_fees as $depository_fee)
            <?php $total_depo = $total_depo + $depository_fee->amount; ?>
            <tr>
                <td><div class='tab'>{{$depository_fee->subsidiary}}</div></td>
                <td align="right"><div style="border-bottom: 1px solid black">{{$depository_fee->amount}}</div></td>
            </tr>
            @endforeach
            @foreach ($other_collections as $other_collection)
            @if($other_collection->category == "Depository Fees")
            <?php $total_misc = $total_misc + $other_collection->amount; ?>
            <tr>
                <td><div class='tab'>{{$other_collection->subsidiary}}</div></td>
                <td align="right"><div style="border-bottom: 1px solid black">{{$other_collection->amount}}</div></td>
            </tr>
            @endif
            @endforeach
            <tr>
                <th><div class='tab'>Total Depository Fees</div></th>
                <td align="right"><div style="border-bottom: 1px solid black">{{number_format($total_depo,2)}}</div></td>
            </tr>
            
            <tr>
                <td><br></td>
                <td></td>
            </tr>
            <tr>
                <th>Total School Fees</th>
                <td align="right"><div style="border-bottom: 3px double black">{{number_format($amount + $total_other + $total_misc + $total_depo,2)}}</div></td>
            </tr>
        </table>
    </body>
</html>