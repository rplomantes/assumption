<?php $x = $total_credit = $total_debit = 0; ?>
<style>   
    th {
        border-collapse: collapse;
        border-bottom: 2px solid black;
    }
    td{
        border-collapse: collapse;
        border-bottom: 1px solid #696969;
    }
    .page_break { 
        page-break-before: always;
    }
    .header_image 
    {
        position: absolute;
        bottom: 898px;
        right: 0;
        left: -350;
        z-index: -1;
    }
</style>
<div align="left">
    <strong>ASSUMPTION COLLEGE</strong><br>
    <small>San Lorenzo Drive, San Lorenzo Village, Makati City</small><br><br>
    <small style="font-size:14pt;"><strong>Trial Balance</strong></small><br>
    @if($finalStartDate == $finalEndDate)
    <small style="font-size:11pt;">Date Covered : {{date_format(date_create($finalStartDate),"F d, Y")}}</small><br>
    @else
    <small style="font-size:11pt;">Date Covered : {{date_format(date_create($finalStartDate),"F d, Y")}} - {{date_format(date_create($finalEndDate),"F d, Y")}}</small><br>
    @endif
</div>
</br>
&nbsp;
</br>
<div style='width:100%;font-size:10pt;'>
    <div id='table' style='width:100%'>
        <table cellspacing="1" cellpadding="2" class="table table-responsive table-striped" style='width:100%'>
            <thead>
                <tr>
                    <th></th>
                    <th class="col-sm-3">Account Code</th>
                    <th class="col-sm-3">Account Name</th>
                    <th class="col-sm-3" style="text-align:right">Debit</th>
                    <th class="col-sm-3" style="text-align:right">Credit</th>
                </tr>
            </thead>
            <tbody>
                @foreach($lists as $list)
                <tr>
                    <td> {{++$x}} </td>
                    <td>{{$list->accounting_code}}</td>
                    <td>{{$list->accounting_name}}</td>
                    <td align="right">{{number_format(abs($list->debit),2)}}&nbsp;</td>
                    <td align="right">{{number_format(abs($list->credit),2)}}&nbsp;</td>
                    <?php
                    $total_credit += abs($list->credit);
                    $total_debit += abs($list->debit);
                    ?>
                </tr>
                @endforeach
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><b>TOTAL</b> </td>
                    <td align="right" ><b>{{number_format($total_debit,2)}}&nbsp;</b></td>
                    <td align="right" ><b>{{number_format($total_credit,2)}}&nbsp;</b></td>
                </tr>
            </tbody>
        </table>
    </div>
    <br><br>
    @if (Auth::user()->accesslevel == env('ACCTNG_STAFF'))
    Prepared by:<br><br>
    <strong>{{Auth::user()->lastname}}, {{Auth::user()->firstname}}</strong><br>
    Accounting Staff
    @else (Auth::user()->accesslevel == env('ACCTNG_STAFF'))
    Prepared by:<br><br>
    <strong>{{Auth::user()->lastname}}, {{Auth::user()->firstname}}</strong><br>
    Accounting Head
    @endif
    <br><small style="font-size:7pt;">{{date("m/d/Y H:m:s")}}</small>
</div>