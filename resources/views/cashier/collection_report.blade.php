<?php
$totalcash=0;
$totalcheck=0;
$totalcreditcard=0;
$totalbankdeposit=0;
$total=0;
$ntotal=0;
$grandtotal=0;

$totalcanceled=0;
?>
@extends('layouts.appcashier')
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
        Collection Report
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Collection Report</li>
      </ol>
</section>
@endsection
@section('maincontent')
 <!-- search form (Optional) -->
 
     <div class="form-group">
                <label>Date range button:</label>
                <div class="input-group">
                  <button type="button" class="btn btn-default pull-left" id="daterange">
                    <span>
                        <i class="fa fa-calendar"></i> <span id='range'>{{$date_from}} , {{$date_to}}</span>
                    </span>
                     <i class="fa fa-caret-down"></i>
                     
                  </button>
                    
                     <a href="javascript:void(0)" class="btn btn-primary" id="view-button">View Summary</a>
                    
                    <input id="date_to" class="form-control" type="hidden" value="{{$date_to}}">
                    <input id="date_from" class="form-control" type="hidden" value="{{$date_from}}">
                </div>
      </div>
     
     <div class="box">    
     <div class="box-body">
     <table id="example1" class="table table-responsive table-hover">
         <thead>
             <tr><th>Date</th><th>Receipt No</th><th>Receive From</th><th>Cash</th><th>Check</th><th>Credit Card</th><th>Bank Deposit</th><th>Total</th><th>Status</th><th>View</th></tr>
         </thead>
         <tbody>
             @if(count($payments)>0)
                @foreach($payments as $payment)
                    <?php
                    if($payment->is_reverse==0){
                    $totalcash=$totalcash+$payment->cash_amount;
                    $totalcheck=$totalcheck+$payment->check_amount;
                    $totalcreditcard=$totalcreditcard+$payment->credit_card_amount;
                    $totalbankdeposit=$totalbankdeposit+$payment->deposit_amount;
                    $total=$payment->cash_amount+$payment->check_amount+$payment->credit_card_amount+$payment->deposit_amount;
                    $ntotal=$totalcash+$totalcheck+$totalcreditcard+$totalbankdeposit;
                    $grandtotal=$grandtotal+$total;
                    }
                    ?>
                    <tr><td>{{$payment->transaction_date}}</td>
                    <td>{{$payment->receipt_no}}</td>
                    <td>{{$payment->paid_by}}</td>
                    @if($payment->is_reverse=="0")
                    <td>{{number_format($payment->cash_amount,2)}}</td>
                    <td>{{number_format($payment->check_amount,2)}}</td>
                    <td>{{number_format($payment->credit_card_amount,2)}}</td>
                    <td>{{number_format($payment->deposit_amount,2)}}</td>
                    <td><b>{{number_format($total,2)}}</b></td>
                    <td>Ok</td>
                    @else
                    <?php
                    $totalcanceled=$payment->cash_amount+$payment->check_amount + $payment->credit_card_amount +$payment->deposit_amount;
                    ?>
                    <td><span style='color:red;text-decoration:line-through'>
  <span style='color:black'>{{number_format($payment->cash_amount,2)}}</span></span></td>
                    <td><span style='color:red;text-decoration:line-through'>
  <span style='color:black'>{{number_format($payment->check_amount,2)}}</span></span></td>
                    <td><span style='color:red;text-decoration:line-through'>
  <span style='color:black'>{{number_format($payment->credit_card_amount,2)}}</span></span></td>
                    <td><span style='color:red;text-decoration:line-through'>
  <span style='color:black'>{{number_format($payment->deposit_amount,2)}}</span></span></td>
  <td><span style='color:red;text-decoration:line-through;'>
  <span style='color:#999'>{{number_format($totalcanceled,2)}}</span></span></td>
                    <td>Canceled</td>
                    @endif
                    <td><a href="{{url('/cashier',array('viewreceipt',$payment->reference_id))}}">View</a></td></tr>
                @endforeach
             @else
             @endif
         </tbody>
          <tfoot>
                    <tr><th colspan="3">Total</th>
                    <th>{{number_format($totalcash,2)}}</th>
                    <th>{{number_format($totalcheck,2)}}</th>
                    <th>{{number_format($totalcreditcard,2)}}</th>
                    <th>{{number_format($totalbankdeposit,2)}}</th>
                    <th><b>{{number_format($grandtotal,2)}}</b></th>
                    <th></th></tr>
        
         </tfoot>    
     </table> 
     </div>    
     </div>  
   
    
@endsection
@section('footerscript')

   
     <!-- daterange picker -->
<link rel="stylesheet" href="{{url('/bower_components',array('bootstrap-daterangepicker','daterangepicker.css'))}}">

 

<script src="{{url('/bower_components',array('datatables.net','js','jquery.dataTables.min.js'))}}"></script>
<script src="{{url('/bower_components',array('datatables.net-bs','js','dataTables.bootstrap.min.js'))}}"></script>
<!-- SlimScroll -->
<script src="{{url('/bower_components',array('jquery-slimscroll','jquery.slimscroll.min.js'))}}"></script>
<!-- FastClick -->
<script src="{{url('/bower_components',array('fastclick','lib','fastclick.js'))}}"></script>
<!-- AdminLTE App -->
<script src="{{url('/dist',array('js','adminlte.min.js'))}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{url('/dist',array('js','demo.js'))}}"></script>


<script src="{{url('/',array('bower_components','moment','min','moment.min.js'))}}"></script>
<script src="{{url('/',array('bower_components','bootstrap-daterangepicker','daterangepicker.js'))}}"></script>
<script>
    $(document).ready(function(){
       $('#example1').DataTable();
       $('#daterange').daterangepicker(
         {
          ranges   : {
          'Select'       : [moment(), moment()],
          'Today'       : [moment(), moment()],
          'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
          'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
          'Last 30 Days': [moment().subtract(29, 'days'), moment()],
          'This Month'  : [moment().startOf('month'), moment().endOf('month')],
          'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
        },
        startDate: moment(),
        endDate  : moment(),
        
      },
      function (start, end) {
        $('#daterange span #range').html(start.format('YYYY-MM-DD') + ' , ' + end.format('YYYY-MM-DD'));
        x=$('#range').html();
        splitdate=x.split(',');
        todate=splitdate[1];
        fromdate=splitdate[0];
        to=todate.trim()
        from=fromdate.trim()
        $('#date_to').val(to);
        $('#date_from').val(from);
      });
      $("#view-button").on('click',function(e){
        document.location="{{url('/cashier',array('collection_report'))}}" + "/" + $("#date_from").val() + "/" + $("#date_to").val();
    });
      
    });
    
</script>    
@endsection
