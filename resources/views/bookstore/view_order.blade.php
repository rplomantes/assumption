@extends('layouts.appbookstore')
@section('messagemenu')
 <li class="dropdown messages-menu">
            <!-- Menu toggle button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success">4</span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have 4 messages</li>
              <li>
                <!-- inner menu: contains the messages -->
                <ul class="menu">
                  <li><!-- start message -->
                    <a href="#">
                      <div class="pull-left">
                        <!-- User Image -->
                       
                      </div>
                      <!-- Message title and timestamp -->
                      <h4>
                        Support Team
                        <small><i class="fa fa-clock-o"></i> 5 mins</small>
                      </h4>
                      <!-- The message -->
                      
                    </a>
                  </li>
                  <!-- end message -->
                </ul>
                <!-- /.menu -->
              </li>
              <li class="footer"><a href="#">See All Messages</a></li>
            </ul>
          </li>
@endsection
@section('header')
<section class="content-header">
      <h1>
        Search Student
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Here</li>
      </ol>
</section>
@endsection
@section('maincontent')
 <!-- search form (Optional) -->
 
     <div class="col-md-12">
         <div class="box">
             <div class="box-body">
             @if(count($books)>0)
             <h3>Books and other supplies</h3>
             <table class="table table-striped">
                 <tr><th width="30%">Paticular</th><th>QTY</th><th>Amount</th><th>Payment</th><th>Is Served</th><th>Date Served</th><th>Remarks</th></tr>
                @foreach($books as $book)
                <tr><td>{{$book->subsidiary}}</td><td>{{$book->qty}}</td><td>{{number_format($book->amount,2)}}</td>
                    <td>{{number_format($book->payment+$book->discount+$book->debit_memo,2)}}</td>
                    <td><?php $checked="checked='checked"; 
                              $disabled="";
                              
                              if($book->is_served=="1"){
                                 // $checked="checked='checked'";
                                    if($book->date_served != ""){
                                    $disabled = "disabled='disabled'";  
                                    }  
                              }
                              
                              if($book->amount > $book->payment+$book->discount+$book->debit_memo){
                                  $checked="";
                                  $disabled="disabled='disabled'";   
                              }?>
                        <input id="qty_book" onclick="is_serve(this.checked,{{$book->id}})" type="checkbox" {{$checked}} {{$disabled}}></td><td>{{$book->date_served}}</td><td><input type="text" value="{{$book->supply_remarks}}"></td></tr>
                @endforeach
             </table>                 
             @else
             @endif
             @if(count($materials)>0)
             <h3>Required Materials (SET)</h3>
             <table class="table">
                 <tr><th width="30%">Paticular</th><th>QTY</th><th>Amount</th><th>Payment</th><th>Is Served</th><th>Remarks</th></tr>
                @foreach($materials as $book)
                <tr><td>{{$book->subsidiary}}</td><td>{{$book->qty}}</td><td>{{number_format($book->amount,2)}}</td>
                    <td>{{number_format($book->payment+$book->discount+$book->debit_memo,2)}}</td>
                    <td><?php $checked="";$disabled=""; if($book->is_served=="1"){$checked="checked='checked'";} if($book->amount > $book->payment+$book->discount+$book->debit_memo){$disabled="disabled='disabled'";}?>
                        <input type="checkbox" {{$checked}} {{$disabled}}></td><td><input type="text" value="{{$book->supply_remarks}}"></td></tr>
                @endforeach
             </table>                 
             @else
             @endif
             @if(count($other_materials)>0)
             <h3>Optional Materials (SET)</h3>
             <table class="table">
                 <tr><th width="30%">Paticular</th><th>QTY</th><th>Amount</th><th>Payment</th><th>Is Served</th><th>Remarks</th></tr>
                @foreach($other_materials as $book)
                <tr><td>{{$book->subsidiary}}</td><td>{{$book->qty}}</td><td>{{number_format($book->amount,2)}}</td>
                    <td>{{number_format($book->payment+$book->discount+$book->debit_memo,2)}}</td>
                    <td><?php $checked=""; $disabled=""; if($book->is_served=="1"){$checked="checked='checked'";} if($book->amount > $book->payment+$book->discount+$book->debit_memo){$disabled="disabled='disabled'";}?>
                        <input type="checkbox" {{$checked}} {{$disabled}}></td><td><input type="text" value="{{$book->supply_remarks}}"></td></tr>
                @endforeach
             </table>                 
             @else
             @endif
             @if(count($pe_uniforms)>0)
             <h3>PE Uniform</h3>
             <table class="table">
                 <tr><th width="30%">Paticular</th><th>QTY</th><th>Amount</th><th>Payment</th><th>Is Served</th><th>Remarks</th></tr>
                @foreach($pe_uniforms as $book)
                <tr><td>{{$book->subsidiary}}</td><td>{{$book->qty}}</td><td>{{number_format($book->amount,2)}}</td>
                    <td>{{number_format($book->payment+$book->discount+$book->debit_memo,2)}}</td>
                    <td><?php $checked=""; $disabled=""; if($book->is_served=="1"){$checked="checked='checked'";}if($book->amount > $book->payment+$book->discount+$book->debit_memo){$disabled="disabled='disabled'";}?>
                        <input type="checkbox" {{$checked}} {{$disabled}}></td><td><input type="text" value="{{$book->supply_remarks}}"></td></tr>
                @endforeach
             </table>                 
             @else
             @endif
             </div>
         </div>    
      </div>
   
@endsection
@section('footerscript')
<script>
function is_serve(value,id){
alert(value + " " + id);
}
</script>    
@endsection


