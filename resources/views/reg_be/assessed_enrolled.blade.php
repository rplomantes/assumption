<?php
$display_status = "ASSESSED";
$user = \App\User::where('idno',$idno)->first();
$status =  \App\Status::where('idno',$idno)->first();
if($status->status == env("ENROLLED"))
    $display_status = "ENROLLED";
$ledger = \App\Ledger::SelectRaw('category,category_switch, sum(amount)as amount, sum(discount) as discount,
    sum(debit_memo) as debit_memo, sum(payment) as payment')->where('idno',$idno)->groupBy('category_switch','category')->orderBy('category_switch')->get();
$due_dates = \App\LedgerDueDate::where('idno',$idno)->get();
$totalmainpayment=0;
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
        Registration Form
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
 <div class="row">
     <div class="box">
         <div class="box-body">
        <div class="col-md-12">
          <div class="page-header">
             Assumption College
            <small class="pull-right">{{$status->date_registered}}</small>
            <br ><small>San Lorenzo Village, Makati</small>
          </div>   
        </div>
<div class="col-md-6">
         
        
             <div class="student_id_no">{{$user->idno}}</div>
             <div id="student_name">{{$user->lastname}}, {{$user->firstname}} {{$user->middlename}}</div>
             <div class="student_level_starnd">{{$status->level}} - {{$status->section}} 
                 @if($status->level == "Grade 11" || $status->level == "Grade 12")
                 ( {{$status->strand}} )
                 @endif
                </div>    
 </div>
             <div class="col-md-6">
                 <div class="pull-right">
                 <label>Status</label>
                 <div class="btn form-control btn-success">
                     {{$display_status}}
                 </div>
                 </div>
             </div>    
<hr />
<div class="col-md-8">
    <label>Breakdown of Fees</label>
        <div class="form-group">
    <table class="table table table-striped table-bordered"><tr><th>Description</th><th>Amount</th><th>Discount</th><th>Debit Memo</th><th>Payment</th><th>Balance</th></tr>
           <?php
           $totalamount=0;$totaldiscount=0;$totaldm=0;$totalpayment=0;$balance=0;$totalbalance=0;
           ?>
           @foreach($ledger as $main)
           <?php
               $totalamount=$totalamount+$main->amount;
               $totaldiscount=$totaldiscount+$main->discount;
               $totaldm=$totaldm+$main->debit_memo;
               $totalpayment=$totalpayment+$main->payment;
               $balance=+$main->amount-$main->discount-$main->debit_memo-$main->payment;
               $totalbalance=$totalbalance+$balance;
               ?>
               <tr><td>{{$main->category}}</td>
               <td align="right">{{number_format($main->amount,2)}}</td>
               <td align="right">{{number_format($main->discount,2)}}</td>
               <td align="right">{{number_format($main->debit_memo,2)}}</td>
               <td align="right"><span class="payment">{{number_format($main->payment,2)}}</span></td>
               <td align="right"><b>{{number_format($balance,2)}}</b></td></tr>
           @endforeach
               <tr><td>Total</td>
               <td align="right">{{number_format($totalamount,2)}}</td>
               <td align="right">{{number_format($totaldiscount,2)}}</td>
               <td align="right">{{number_format($totaldm,2)}}</td>
               <td align="right"><span class="payment">{{number_format($totalpayment,2)}}</span></td>
               <td align="right"><b>{{number_format($totalbalance,2)}}</b></td></tr>
            </table> 
    </div>
</div>
<div class="col-md-4">
    @if(count($due_dates)>0)
        <label>Schedule of Payment</label>
        <div class="form-group">
            <?php $totalpay = $totalpayment; $display=""; $remark="";?>
            <table class="table table-striped"><tr><td>Due Date</td><td align="right">Due Amount</td><td>Remarks</td></tr>
            @foreach($due_dates as $due_date)
            <?php 
            if($totalpay >= $due_date->amount){
                $display = "<span class=\"text_through\">".number_format($due_date->amount,2)."<span>";  
                $totalpay = $totalpay - $due_date->amount;
                $remark = "<span style=\"font-style:italic;color:#f00\">paid</span>";
            } else {
                $display = number_format($due_date->amount-$totalpay,2);
                $totalpay=0;
                $remark="";
            }
            ?>
            @if($due_date->due_switch=="0")
            <?php $duedate = "Upon Enrollment";?>
            @else
            <?php $duedate = $due_date->due_date;?>
            @endif
            <tr><td>{{$duedate}}</td><td align="right">{!!$display!!}</td><td align="center">{!!$remark!!}</td></tr>
            @endforeach
            </table>    
        </div>
        @endif
    </div>
    @if($status->status==env("ASSESSED") && $totaldm > 0)
<div class="col-md-6">
    <a href="{{url('/bedregistrar',array('reassess_reservations',$idno, $status->levels_reference_id))}}" class="btn btn-success form form-control" onclick="return confirm('Are you Sure To Re-assess {{$user->firstname}} {{$user->lastname}} ? ')">Re-assess {{$user->idno}} - {{$user->lastname}}, {{$user->firstname}}</a>    
</div>
    <div class="col-md-3">
    <a href="{{url('/bedregistrar',array('print_assessment',$idno))}}" class="btn btn-success form form-control">Print Assessment Form</a>
    </div>
    @elseif ($status->status==env("ASSESSED"))
<div class="col-md-6">
    <a href="{{url('/bedregistrar',array('reassess',$idno))}}" class="btn btn-success form form-control" onclick="return confirm('Are you Sure To Re-assess {{$user->firstname}} {{$user->lastname}} ? ')">Re-assess {{$user->idno}} - {{$user->lastname}}, {{$user->firstname}}</a>
</div>
    <div class="col-md-3">
    <a href="{{url('/bedregistrar',array('print_assessment',$idno))}}" class="btn btn-success form form-control">Print Assessment Form</a>
    </div>
    @else
<div class="col-md-6">
    <a href="" class="btn btn-default form form-control">Can't Re-assess! Status is Already Enrolled</a>
</div>
    @endif
<div class="col-md-3">
    <a href="{{url('/')}}" class="col-sm-12 btn btn-primary form fom-control">Back To Main</a>
</div>    
</div>      
</div>    
</div>     
    
@endsection
@section('footerscript')
<style>
    #student_name{font-size: 18pt; font-weight: bold; color: darkgreen}
    .student_id_no, .student_level_starnd{font-size:14pt;}
</style>
<script src="{{url('/bower_components',array('datatables.net','js','jquery.dataTables.min.js'))}}"></script>
<script src="{{url('/bower_components',array('datatables.net-bs','js','dataTables.bootstrap.min.js'))}}"></script>

<script>
    $(document).ready(function(){
        $('#example1').DataTable();
        $('#example2').DataTable();
       $("#search").on('keypress',function(e){
          if(e.keyCode==13){
              var array={};
              array['search'] = $("#search").val();
              $.ajax({
                  type:"GET",
                  url:"/bedregistrar/ajax/getstudentlist",
                  data:array,
                  success:function(data){
                   $("#displaystudent").html(data)
                   $("#search").val("");
                  }
              })
          } 
       }); 
    });
</script>    
@endsection

