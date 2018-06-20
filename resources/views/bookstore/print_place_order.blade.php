<?php
$user=\App\User::where('idno',$request->idno)->first();
$status= \App\Status::where('idno',$request->idno)->first();
$totalprice=0;
?>
<html>
    <head>
        <style>
            body {
                font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
                font-size: 7pt;
            }
        </style>
    </head>
    <body>
        <strong>Assumption College</strong><br>
        <small>San Lorenzo Drive, San Lorenzo Village<br>Makati City, 1223</small><br>
        <strong><small>Bookstore</small></strong><br>
    <center><u>O R D E R &nbsp;&nbsp; S L I P</u></center><br>
    <div align='right'>Date: {{date('Y-m-d')}}</div>
    <table>
        <tr>
            <td>ID Number:</td>
            <td>{{$user->idno}}</td>  
        </tr>
        <tr>
            <td>Name:</td>
            <td>{{strtoupper($user->lastname)}}, {{strtoupper($user->firstname)}}</td>  
        </tr>
        <tr>
            <td>Level:</td>
            <td>{{$status->level}}</td>
        </tr>
    </table>
    <table width='100%' style="border-collapse: collapse; border-top:1px solid; border-bottom:1px solid">
        <tr style="border-bottom:1px solid">
            <th style="border-bottom:1px solid">Qty</th>
            <th style="border-bottom:1px solid">Item Description</th>
            <th style="border-bottom:1px solid" align='right'>Unit Price</th>
            <th style="border-bottom:1px solid" align='right'>Total</th>
        </tr>
                
        @if(count($request->qty_books)>0)
        @foreach($request->qty_books as $key=>$value)
        @if ($value > 0)
        <?php $item = \App\CtrOptionalFee::find($key); ?>
        <tr style='font-size: 6pt;'>
            <td>{{$value}}</td>
            <td>{{$item->subsidiary}}</td>
            <td align='right'>{{number_format($item->amount,2)}}</td>
            <td align='right'>{{number_format($item->amount * $value,2)}}</td>
            <?php $totalprice=$totalprice+$item->amount*$value; ?>
        </tr>
        @endif
        @endforeach
        @endif
        
        @if($request->tshirt_size!=null)
        <?php
        $item = \App\CtrUniformSize::find($request->tshirt_size); 
        $qty = $request->tshirt_qty;
        ?>
        <tr style='font-size: 6pt;'>
            <td>{{$qty}}</td>
            <td>{{$item->subsidiary}} - {{$item->size}}</td>
            <td align='right'>{{number_format($item->amount,2)}}</td>
            <td align='right'>{{number_format($item->amount * $qty,2)}}</td>
            <?php $totalprice=$totalprice+$item->amount*$qty; ?>
        </tr>
        @endif
        
        @if($request->jogging_size!=null)
        <?php
        $item = \App\CtrUniformSize::find($request->jogging_size); 
        $qty = $request->jogging_qty;
        ?>
        <tr style='font-size: 6pt;'>
            <td>{{$qty}}</td>
            <td>{{$item->subsidiary}} - {{$item->size}}</td>
            <td align='right'>{{number_format($item->amount,2)}}</td>
            <td align='right'>{{number_format($item->amount * $qty,2)}}</td>
            <?php $totalprice=$totalprice+$item->amount*$qty; ?>
        </tr>
        @endif
        
        @if($request->socks_size!=null)
        <?php
        $item = \App\CtrUniformSize::find($request->socks_size); 
        $qty = $request->socks_qty;
        ?>
        <tr style='font-size: 6pt;'>
            <td>{{$qty}}</td>
            <td>{{$item->subsidiary}} - {{$item->size}}</td>
            <td align='right'>{{number_format($item->amount,2)}}</td>
            <td align='right'>{{number_format($item->amount * $qty,2)}}</td>
            <?php $totalprice=$totalprice+$item->amount*$qty; ?>
        </tr>
        @endif
        
        @if($request->dengue_size!=null)
        <?php
        $item = \App\CtrUniformSize::find($request->dengue_size); 
        $qty = $request->dengue_qty;
        ?>
        <tr style='font-size: 6pt;'>
            <td>{{$qty}}</td>
            <td>{{$item->subsidiary}} - {{$item->size}}</td>
            <td align='right'>{{number_format($item->amount,2)}}</td>
            <td align='right'>{{number_format($item->amount * $qty,2)}}</td>
            <?php $totalprice=$totalprice+$item->amount*$qty; ?>
        </tr>
        @endif
        <tr>
            <th style="border-top:1px solid"></th>
            <th style="border-top:1px solid"></th>
            <th style="border-top:1px solid" align='right'>TOTAL PRICE</th>
            <th style="border-top:1px solid" align='right'>Php {{number_format($totalprice,2)}}</th>
        </tr>
    </table>
    <small><i>*Please present this ORDER SLIP to the cashier for payment.</i></small>
    <br><br>
    <table width='40%'>
        <tr>
            <td>Processed by:<br><br><br></td>
        </tr>
        <tr>
            <td align='center'><div style="border-top: 1px solid">{{Auth::user()->firstname}} {{Auth::user()->lastname}}</div></td>
        </tr>
    </table>
    </body>
</html>