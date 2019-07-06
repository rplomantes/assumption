<?php
$optional_pe_uniforms = \App\CtrOptionalFee::where('level',$current_level)->where('receipt_details','PE Uniforms/Others')->get();
?>

<div class="col-md-12 ">
                @if(count($optional_pe_uniforms)>0)
                <h5>PE Uniforms and Others</h5>
                <table border = "1" class="table table-striped">
                    <tr align="center"><td>Book Title</td><td>QTY</td><td>Amount</td></tr>
                @foreach($optional_pe_uniforms as $optional)
                <tr><td width="50%">
                 {{$optional->subsidiary}}
                </td><td width="20%"><input type="number" name="qty_pe_uniforms[{{$optional->id}}]" class="form form-control" value="{{$optional->default_qty}}" oninput="process_sub1({{$optional->id}},this.value,{{$optional->amount}},this)" onkeypress="process_sub({{$optional->id}},this.value,{{$optional->amount}},event,this)"></td>
                <td align="right"><div id="book_display{{$optional->id}}">{{number_format($optional->amount * $optional->default_qty,2)}}</td>
                </tr>
                @endforeach
                </table>
                @else
                <h5>No Listing For This Level</h5>
                @endif
        </div>
