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
        View Orders
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
         <div>{{$idno}} <br> <b>{{$user->lastname}}, {{$user->firstname}} {{$user->middlename}}</b>
         <br>@if(count($status)>0)
         {{$status->level}} - {{$status->section}}
         @endif
         </div>
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
                        <input id="qty_book" onclick="is_serve(this.checked,{{$book->id}})" type="checkbox" {{$checked}} {{$disabled}}></td><td>{{$book->date_served}}</td>
                    
                    <td> <select id="remarks" onchange="change_remarks(this.value,{{$book->id}})">
                            <option value="" >&nbsp;</option>
                            <option value="Not Yet Served" @if($book->supply_remarks=="Not Yet Served") selected="selected" @endif>Not Yet Served</option>
                        </select></td></tr>
                @endforeach
             </table>                 
             @else
             @endif
             
             
             @if(count($materials)>0)
             <h3>Required Materials (SET)</h3>
             <table class="table table-striped">
                 <tr><th width="30%">Paticular</th><th>QTY</th><th>Amount</th><th>Payment</th><th>Is Served</th><th>Date Served</th><th>Remarks</th></tr>
                @foreach($materials as $book)
                <tr><td>{{$book->subsidiary}}
                    @if(count($material_details)>0)
                    <ul>
                    @foreach($material_details as $material)
                    <li>{{$material->particular}}</li>
                    @endforeach
                    </ul>
                    @endif
                    
                    </td><td>{{$book->qty}}</td><td>{{number_format($book->amount,2)}}</td>
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
                        <input id="qty_book" onclick="is_serve(this.checked,{{$book->id}})" type="checkbox" {{$checked}} {{$disabled}}></td><td>{{$book->date_served}}</td>
                    
                    <td> <select id="remarks" onchange="change_remarks(this.value,{{$book->id}})">
                            <option value="" >&nbsp;</option>
                            <option value="Not Yet Served" @if($book->supply_remarks=="Not Yet Served") selected="selected" @endif>Not Yet Served</option>
                        </select></td></tr>
                @endforeach
             </table>                 
             @else
             @endif
             
             @if(count($other_materials)>0)
             <h3>Optional Materials (SET)</h3>
             <table class="table table-striped">
                 <tr><th width="30%">Paticular</th><th>QTY</th><th>Amount</th><th>Payment</th><th>Is Served</th><th>Date Served</th><th>Remarks</th></tr>
                @foreach($other_materials as $book)
                <tr><td>{{$book->subsidiary}}
                    @if(count($other_material_details)>0)
                    <ul>
                    @foreach($other_material_details as $material)
                    <li>{{$material->particular}}</li>
                    @endforeach
                    </ul>
                    @endif
                    </td><td>{{$book->qty}}</td><td>{{number_format($book->amount,2)}}</td>
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
                        <input id="qty_book" onclick="is_serve(this.checked,{{$book->id}})" type="checkbox" {{$checked}} {{$disabled}}></td><td>{{$book->date_served}}</td>
                    
                    <td> <select id="remarks" onchange="change_remarks(this.value,{{$book->id}})">
                            <option value="" >&nbsp;</option>
                            <option value="Not Yet Served" @if($book->supply_remarks=="Not Yet Served") selected="selected" @endif>Not Yet Served</option>
                        </select></td></tr>
                @endforeach
             </table>                 
             @else
             @endif
             @if(count($pe_uniforms)>0)
             <h3>PE Uniform</h3>
             <table class="table table-striped">
                 <tr><th width="30%">Particular</th><th>QTY</th><th>Amount</th><th>Payment</th><th>Is Served</th><th>Date Served</th><th>Remarks</th></tr>
                @foreach($pe_uniforms as $book)
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
                        <input id="qty_book" onclick="is_serve(this.checked,{{$book->id}})" type="checkbox" {{$checked}} {{$disabled}}></td><td>{{$book->date_served}}</td>
                    
                    <td> <select id="remarks" onchange="change_remarks(this.value,{{$book->id}})">
                            <option value="" >&nbsp;</option>
                            <option value="Not Yet Served" @if($book->supply_remarks=="Not Yet Served") selected="selected" @endif>Not Yet Served</option>
                        </select></td></tr>
                @endforeach
             </table> 
             
             @else
             @endif
             <a href="{{url('/bookstore',array('print_order',$idno))}}" class="btn btn-primary form-control">Print Receiving Form</a>
             </div>
         </div>    
      </div>
   
@endsection
@section('footerscript')
<script>
function is_serve(value,id){
alert(value)
}

function change_remarks(value, id){
    var array={};
    array['value']=value;
    array['id']=id;
    
    $.ajax({
        type:"GET",
        url:"/bookstore/ajax/change_remarks",
        data:array,
        success:function(data){
            
        }
                
    })
              
}

</script>    
@endsection


