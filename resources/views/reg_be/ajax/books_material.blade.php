<?php
$optional_books = \App\CtrOptionalFee::where('level',$current_level)->where('receipt_details','Books')->get();
$optional_materials = \App\CtrOptionalFee::where('level',$current_level)->where('receipt_details','Materials')->get();
$optional_other_materials = \App\CtrOptionalFee::where('level',$current_level)->where('receipt_details','Other Materials')->get();
?>                
                <div class="col-md-12 ">
                @if(count($optional_books)>0)
    
                <h5>Books</h5>
                <table border = "1" class="table table-striped">
                    <tr align="center"><td>Book Title</td><td>QTY</td><td>Amount</td></tr>
                @foreach($optional_books as $optional)
                <tr><td width="50%">
                 {{$optional->subsidiary}}
                </td><td width="20%"><input type="number" name="qty_books[{{$optional->id}}]" class="form form-control" value="{{$optional->default_qty}}" oninput="process_sub1({{$optional->id}},this.value,{{$optional->amount}},this)" onkeypress="process_sub({{$optional->id}},this.value,{{$optional->amount}},event,this)"></td>
                <td align="right"><div id="book_display{{$optional->id}}">{{number_format($optional->amount * $optional->default_qty,2)}}</td>
                </tr>
                @endforeach
                </table>
                @endif
                </div>  
                <div class="col-md-12"> 
                    
                  @if(count($optional_materials)>0)
                <h5>Materials</h5>
                <table border = "1" class="table table-striped">
                    <tr align="center"><td>Particular</td><td>QTY</td><td>Amount</td></tr>
                @foreach($optional_materials as $optional)
                <tr><td width="50%">
                 {{$optional->subsidiary}}
                </td><td width="20%"><input type="number" name="qty_materials[{{$optional->id}}]" oninput="process_sub1({{$optional->id}},this.value,{{$optional->amount}},this)" onkeypress="process_sub({{$optional->id}},this.value,{{$optional->amount}},event,this)" class="form form-control number" value="{{$optional->default_qty}}"></td>
                <td align="right"><div id="book_display{{$optional->id}}">{{number_format($optional->amount * $optional->default_qty,2)}}</div></td>
                </tr>
                @endforeach
                </table>
                @endif   
                            
                </div>
                    
                <div class="col-md-12">  
                  @if(count($optional_other_materials)>0)
                <h5>Other Materials</h5>
                <table border = "1" class="table table-striped">
                    <tr align="center"><td>Particular</td><td>QTY</td><td>Amount</td></tr>
                @foreach($optional_other_materials as $optional)
                <tr><td width="50%">
                 {{$optional->subsidiary}}
                </td><td width="20%"><input type="number" name="qty_other_materials[{{$optional->id}}]" oninput="process_sub1({{$optional->id}},this.value,{{$optional->amount}},this)" onkeypress="process_sub({{$optional->id}},this.value,{{$optional->amount}},event,this)" class="form form-control number" value="{{$optional->default_qty}}"></td>
                <td align="right"><div id="book_display{{$optional->id}}">{{number_format($optional->amount * $optional->default_qty,2)}}</div></td>
                </tr>
                @endforeach
                </table>
                @endif   
                            
                </div>

