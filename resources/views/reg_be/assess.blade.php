<?php
$current_level="";
$levels = \App\CtrAcademicProgram::selectRaw("distinct level")->where('academic_type',"BED")->orderBy('level')->get();
$strands =\App\CtrAcademicProgram::selectRaw("distinct strand")->where('academic_code','SHS')->get();
switch ($status->level){
case "Pre-Kinder":
    $current_level = "Kinder";
    break;
case "Kinder":
    $current_level = "Grade 1";
    break;
case "Grade 1":
    $current_level = "Grade 2";
    break;    
case "Grade 2":
    $current_level = "Grade 3";
    break;
case "Grade 3":
    $current_level = "Grade 4";
    break;
case "Grade 4":
    $current_level = "Grade 5";
    break;
case "Grade 5":
    $current_level = "Grade 6";
    break;
case "Grade 6":
    $current_level = "Grade 7";
    break;
case "Grade 7":
    $current_level = "Grade 8";
    break;
case "Grade 8":
    $current_level = "Grade 9";
    break;
case "Grade 9":
    $current_level = "Grade 10";
    break;
case "Grade 10":
    $current_level = "Grade 11";
    break;
case "Grade 11":
    $current_level = "Grade 12";
    break;
}

$plans = \App\CtrDueDate::selectRaw('distinct plan')->where('academic_type','BED')->get();
$discounts = \App\CtrDiscount::get();
$optional_books = \App\CtrOptionalFee::where('level',$current_level)->where('category','books')->get();
$optional_materials = \App\CtrOptionalFee::where('level',$current_level)->where('category','materials')->get();
$optional_other_materials = \App\CtrOptionalFee::where('level',$current_level)->where('category','other_materials')->get();
$optional_pe_uniforms = \App\CtrOptionalFee::where('level',$current_level)->where('category','pe_uniform')->get();;
?>
@extends('layouts.appbedregistrar')
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
                      <p>Why not buy a new awesome theme?</p>
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
        Assessment
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Assessment</li>
      </ol>
</section>
@endsection
@section('maincontent')
<form class="form form-horizontal" method="post" action="{{url('/bedregistrar','assess')}}">
         {{csrf_field()}}
         <input type="hidden" name="idno" value="{{$user->idno}}">
         
<div class="row">
    <div class="col-md-12">
        <div class="col-md-6">
          <div class="box box-solid">
            <div class="box-header with-border">
              <h1 class="box-title"><b>{{$user->idno}} - {{$user->lastname}}, {{$user->firstname}} {{$user->middlename}}</b></h1>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <div class="box-group" id="accordion">
                <!-- we are adding the .panel class so bootstrap.js collapse plugin detects it -->
                <div class="panel box box-primary">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne">
                        Previous Grade Level
                      </a>
                    </h4>
                  </div>
                  <div id="collapseOne" class="panel-collapse collapse in">
                    <div class="box-body">
                        <div class="col-md-4">       
                 <table class="table table-responsive">        
                      @if(count($status)>0)
                    @if($status->status==env("ENROLLED"))
                <tr><td colspan="2"><b>This student is already ENROLLED!!</b></td></tr>
                <tr><th>Level</th><td>{{$status->level}}</td></tr>
                @if($status->level == "Grade 11" || $status->level=="Grade 12")
                <tr><th>Strand</th><td>{{$status->strand}}</td></tr>
                @endif
                <tr><th>Section</th><td>{{$status->section}}</td></tr>
                @elseif($status->status=="0")
                <tr><td colspan="2"><b>Previous Level</b></td></tr>
                <tr><th>Level</th><td>{{$status->level}}</td></tr>
                @if($status->level == "Grade 11" || $status->level=="Grade 12")
                <tr><th>Strand</th><td>{{$status->strand}}</td></tr>
                @endif
                <tr><th>Section</th><td>{{$status->section}}</td></tr>
                @endif
             @endif   
             </table>
                            </div>
                    </div>
                  </div>
                </div>
                <div class="panel box box-danger">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
                        Select Level, Option and Discount
                      </a>
                    </h4>
                  </div>
                  <div id="collapseFour" class="panel-collapse collapse in">
                    <div class="box-body">
                      @if(count($status)>0)
            @if($status->status == "0")
            <div class="form form-group">
            <div class="col-md-6">
                <label>Grade Level</label>
                <Select name="level" id="level" class="form form-control">
                    <option value="">Select Level</option>
                    @foreach($levels as $level)
                    <option value="{{$level->level}}"
                            @if($level->level==$current_level)
                            selected="selected"
                            @endif
                            >{{$level->level}}</option>
                    @endforeach
                </select>      
            </div>
                <div class="col-md-6" id="strand_control">
                    <label>Strand</label>
                    <Select name="strand" id="strand" class="form form-control">
                    <option value="">Select Strand</option>    
                    @foreach($strands as $strand)
                    <option value="{{$strand->strand}}"
                            @if($strand->strand == $status->strand)
                            selected="selected"
                            @endif
                            >{{$strand->strand}}</option>
                    @endforeach
                </select> 
                </div>    
            </div>  
            <div class="form form-group">
                <div class="col-md-6">
                    <label>Section</label>
                    <select name="section" id="section" class="form form-control">
                        @for($i=1;$i<=7;$i++)
                        <option value="{{$i}}"
                             @if($i == $status->section)
                                selected="selected"
                             @endif
                                >{{$i}}</option>
                        @endfor
                    </select>    
                </div>  
                <div class="col-md-6">
                    <label>Payment Options</label>
                    <select class="form form-control" name="plan" id="plan">
                        <option value="">Select Payment Option</option>
                        <option value="annual">Annual</option>
                        @foreach($plans as $plan)
                        <option value="{{$plan->plan}}">{{$plan->plan}}</option>
                        @endforeach
                    <select>    
                </div>    
            </div>    
            
            <div class="form form-group">
                <div class="col-md-6">
                    <label>Discount</label>
                    <select name="discount" id="discount" class="form form-control">
                        <option value="none">None</option>
                        @if(count($discounts)>0)
                            @foreach($discounts as $discount)
                                <option value="{{$discount->discount_code}}">{{$discount->discount_name}}</option>
                            @endforeach
                        @endif
                    </select>    
                </div>    
            </div>    
            
            @endif
         @endif
                    </div>
                  </div>
                </div>
                      
            </div>
                
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
           
 <!-- search form (Optional) -->  
</div>
            <div class="col-md-6">
                <div class="panel box box-success">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                        List of Books and Required Materials
                      </a>
                    </h4>
                  </div>
                  <div id="collapseTwo" class="panel-collapse collapse">
               <div class="box-body">
                <div class="row">   
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
                    
                   </div> 
                  </div>
                </div>
                      
              </div>
           <div class="panel box box-success">
                  <div class="box-header with-border">
                    <h4 class="box-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                        PE Uniforms and Others
                      </a>
                    </h4>
                  </div>
                  <div id="collapseThree" class="panel-collapse collapse">
               <div class="box-body">
                <div class="row">   
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
                @endif
                </div>  
                
                    
  
                    
                   </div> 
                  </div>
                </div>
                      
              </div> 
              </div>  
  <input type="submit" name="submit" value="Process Assessment" class="btn btn-primary form-control">           
 </div>   
 </div>     
            
    
 </form> 
@endsection
@section('footerscript')
<style>
    .book_table td{ padding:5px;}
</style>
<script>
    $(document).ready(function(){
       if($("#level").val()=="Grade 11" || $("#level").val()=="Grade 12"){
         $("#strand_control").fadeIn(300);  
       }else {  
       $("#strand_control").fadeOut(300);
        }
       $("#level").on('change',function(e){
           if($("#level").val()=="Grade 11" || $("#level").val()=="Grade 12"){
               $("#strand_control").fadeIn(300);
           } else {
               $("#strand_control").fadeOut(300);
           }
       })
       
     $(".number").on('keypress',function(e){
        var theEvent = e || window.event;
        var key = theEvent.keyCode || theEvent.which;
        if ((key < 48 || key > 57) && !(key == 8 || key == 9 || key == 13 || key == 37 || key == 39 || key == 46) ){ 
        theEvent.returnValue = false;
        if (theEvent.preventDefault) theEvent.preventDefault();
        }
    })
    
    });
    
    function process_sub(id,qty,amount,event,these){  
        //alert(event.keyCode)
        if(event.keyCode==13){   
        if(qty<0){
            qty=0;
            these.value=0;
        }      
        total = amount*qty;
        $("#book_display"+id).html(total.toFixed(2));
        event.preventDefault();
        } 
    }
    function process_sub1(id,qty,amount,these){
         if(qty<0){
            qty=0;
            these.value=0;
        }      
        total = amount*qty;
        $("#book_display"+id).html(total.toFixed(2));
    }
</script>    
@endsection


