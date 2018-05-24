<?php
$bookamount=0;
$materialamount=0;
$othermaterialamount=0;
$peamount=0;
?>
<html>
    <head>
        <style>
            small {font-size:8pt; font-style: italic}
            .table-striped{font-size:7pt;width:100%}
            .sub_title{font-size:7pt; font-weight: bold;}
            .blank{border-bottom: solid; border-width: thin; margin:5px;}
        </style>
    </head>
    <body>
        <table class="table-striped" width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr><td align="center">
        <span style="font-size:10pt;font-weight: bold">Assumption College</span>
        </td></tr>
         @if(count($status)>0) 
        <tr><td align="center"> 
           @if(count($status)>0)
           @if($status->academic_type=="BED")
          <div class="sub_title"> BASIC EDUCATION DIVISION</div>
           @else
           COLLEGE DIVISION
           @endif
           @endif
        </td>
        <tr><td align="center">
                <div class="sub_title"> LIST OF REQUIRED BOOKS & MATERIALS FOR SY {{$status->school_year}} - {{$status->school_year+1}} </div>  
         </td></tr>
        @else
        <tr><td>LIST OF REQUIRED BOOKS & MATERIALS </td></tr>
         @endif
         <tr><td  align="center"><div class="sub_title">as of {{date('M d Y')}}</div></td></tr>
         </table>
        <table border="1" cellspacing="0" cellpadding="3" width="100%"><tr><td align="center">{{$user->idno}}</td><td  align="center">{{$user->lastname}}, {{$user->firstname}} {{$user->middlename}}</td>
                <td  align="center">@if(count($status)>0)
                    {{$status->level}} - {{$status->section}}
                    @endif
                </td></tr> 
            <tr><td align="center"><small>Student ID</small></td><td align="center"><small>Student Name</small></td><td align="center"><small>Level/Section</small></tr>
        </table> 
        <table width="100%"><tr><td width="70%">
        @if(count($books)>0)
        <div class="sub_title">BOOKS AND OTHER SUPPLIES</div>  
        <table border="1" cellspacing="0" cellpadding="2" class="table table-striped">
                 <tr><th width="5%"></th><th width="50%">Paticular</th><th>QTY</th><th>Amount</th><th>Remarks</th></tr>
                <?php $count=1; $bookamount=0;?>
                @foreach($books as $book)
                @if($book->amount == $book->payment+$book->discount+$book->debit_memo)
                <tr><td align="right">{{$count++}}.</td><td>{{$book->subsidiary}}</td><td width="5%" align="center">{{$book->qty}}</td><td align="right">{{number_format($book->amount,2)}}</td>
                    <td align="center"> {{$book->supply_remarks}}</td></tr>
                <?php $bookamount=$bookamount+ $book->amount;?>
                @endif
                @endforeach
                <tr><td colspan="4">Total</td><td align="right"><b>{{number_format($bookamount,2)}}</b></td></tr>
             </table>
        @endif
        
        @if(count($materials)>0)
         <div class="sub_title">AC MATERIALS</div>  
        <table border="1" cellspacing="0" cellpadding="0" class="table table-striped">
                 <tr><th width="5%"></th><th width="50%">Paticular</th><th>QTY</th><th>Amount</th><th>Remarks</th></tr>
                <?php $count=1; $materialamount=0;?>
                @foreach($materials as $book)
                @if($book->amount == $book->payment+$book->discount+$book->debit_memo)
                <tr><td align="right" valign="top">{{$count++}}.</td><td>{{$book->subsidiary}}
                    @if(count($material_details)>0)
                    <ul>
                    @foreach($material_details as $material)
                    <li>{{$material->particular}}</li>
                    @endforeach
                    </ul>
                    @endif
                    </td><td width="5%" align="center" valign="top">{{$book->qty}}</td><td align="right" valign="top">{{number_format($book->amount,2)}}</td>
                    <td valign="top" align="center"> {{$book->supply_remarks}}</td></tr>
                <?php $materialamount=$materialamount+ $book->amount;?>
                @endif
                @endforeach
                <tr><td colspan="4">Total</td><td align="right"><b>{{number_format($materialamount,2)}}</b></td></tr>
             </table> 
         @endif
         @if(count($other_materials)>0)
         <div class="sub_title">OTHER MATERIALS</div>  
        <table border="1" cellspacing="0" cellpadding="0" class="table table-striped">
                 <tr><th width="5%"></th><th width="50%">Paticular</th><th>QTY</th><th>Amount</th><th>Remarks</th></tr>
                <?php $count=1; $othermaterialamount=0;?>
                @foreach($other_materials as $book)
                @if($book->amount == $book->payment+$book->discount+$book->debit_memo)
                <tr><td align="right" valign="top">{{$count++}}.</td><td>{{$book->subsidiary}}
                    @if(count($other_material_details)>0)
                    <ul>
                    @foreach($other_material_details as $material)
                    <li>{{$material->particular}}</li>
                    @endforeach
                    </ul>
                    @endif
                    </td><td valign="top" width="5%" align="center">{{$book->qty}}</td><td align="right" valign="top">{{number_format($book->amount,2)}}</td>
                    <td valign="top" align="center"> {{$book->supply_remarks}}</td></tr>
                <?php $othermaterialamount=$othermaterialamount+ $book->amount;?>
                @endif
                @endforeach
                <tr><td colspan="4">Total</td><td align="right"><b>{{number_format($othermaterialamount,2)}}</b></td></tr>
             </table> 
         @endif
         @if(count($pe_uniforms)>0)
         <div class="sub_title">PE UNIFORMS</div>  
        <table border="1" cellspacing="0" cellpadding="0" class="table table-striped">
                 <tr><th width="5%"></th><th width="50%">Paticular</th><th>QTY</th><th>Amount</th><th>Remarks</th></tr>
                <?php $count=1; $peamount=0;?>
                @foreach($pe_uniforms as $book)
                @if($book->amount == $book->payment+$book->discount+$book->debit_memo)
                <tr><td align="right" valign="top">{{$count++}}.</td><td>{{$book->subsidiary}}
                    
                    </td><td valign="top" width="5%" align="center">{{$book->qty}}</td><td align="right" valign="top">{{number_format($book->amount,2)}}</td>
                    <td valign="top" align="center"> {{$book->supply_remarks}}</td></tr>
                <?php $peamount=$peamount+ $book->amount;?>
                @endif
                @endforeach
                <tr><td colspan="4">Total</td><td align="right"><b>{{number_format($peamount,2)}}</b></td></tr>
             </table> 
         @endif
                </td><td valign="top">
                    <div class="sub_title">CUSTOMER COPY</div>
                    <table  class="table-striped" border="1" cellspacing="0" cellpadding="1">
                        <tr><td>Books</td><td align="right">{{number_format($bookamount,2)}}</td></tr>
                        <tr><td>AC MAterial</td><td align="right">{{number_format($materialamount,2)}}</td></tr>
                        <tr><td>Oth Material</td><td align="right">{{number_format($othermaterialamount,2)}}</td></tr>
                        <tr><td>PE Uniform</td><td align="right">{{number_format($peamount,2)}}</td></tr>
                        <tr><td><b>TOTAL</b></td><td align="right"><b>{{number_format($peamount+$othermaterialamount+$materialamount+$bookamount,2)}}</b></td></tr>
                    </table>    
                </td></tr></table>
        <br>
                    <table border='0' cellspacing="0" cellpadding="0" class="table-striped">
                        <tr><td>
                        * Please review the items to be ordered above. Once items are bought, returns, replacements, and refunds will no longer be accepted unless items are defective.
                </td></tr>
                        <tr><td>
                        * Unsold books will be pulled out by August 31, {{$status->school_year}}
                </td></tr><tr>
                            <td>
            RECEIVED THE ABOVE ITEMS COMPLETE, AND IN GOOD ORDER AND CONDITION.
                </td></tr>
                    </table>
           <br>
        <table class="table-striped" border="0" cellpadding="0" cellspacing="0"><tr>
               <td align="center" width="33%"><div class="blank">&nbsp;</div></td><td align="center" width="33%"><div class="blank">&nbsp;</div></td><td><div class="blank">&nbsp;</div></td></tr>
           <tr> <td align="center">Print Name</td><td align="center">Signature</td><td align="center">Date</td></tr>
           </table>
        </body>
</html>
