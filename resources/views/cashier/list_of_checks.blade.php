<?php
$totalchecks=0;
?>
@extends('layouts.appcashier')
@section('messagemenu')
 <li class="dropdown messages-menu">
            <!-- Menu toggle button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success"></span>
            </a>
</li>
<li class="dropdown notifications-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-bell-o"></i>
              <span class="label label-warning"></span>
            </a>
</li>
          
<li class="dropdown tasks-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-flag-o"></i>
              <span class="label label-danger"></span>
            </a>
</li>
@endsection
@section('header')
<section class="content-header">
      <h1>
        List of Checks
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
 <div class="container-fluid">
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
             <tr><th>Date</th><th>Receipt No</th><th>Receive From</th><th>Bank</th><th>Check Number</th><th>Amount</th><th>Status</th><th>View</th></tr>
         </thead>
         <tbody>
             @if(count($payments)>0)
                @foreach($payments as $payment)
                    <?php
                    if($payment->is_reverse==0){
                    $totalchecks=$totalchecks+$payment->check_amount;
                    }
                    ?>
                    <tr><td>{{$payment->transaction_date}}</td>
                    <td>{{$payment->receipt_no}}</td>
                    <td>{{$payment->paid_by}}</td> 
                    <td>{{$payment->bank_name}}</td>
                    <td>{{$payment->check_number}}</td>
                    @if($payment->is_reverse=="0")
                    <td>{{number_format($payment->check_amount,2)}}</td>
                    <td>Ok</td>
                    @else
                    <?php
                    $totalcanceled=$payment->cash_amount+$payment->check_amount + $payment->credit_card_amount +$payment->deposit_amount;
                    ?>
  <td><span style='color:red;text-decoration:line-through;'>
  <span style='color:#999'>{{number_format($payment->check_amount,2)}}</span></span></td>
                    <td>Canceled</td>
                    @endif
                    <td><a href="{{url('/cashier',array('viewreceipt',$payment->reference_id))}}">View</a></td></tr>
                @endforeach
             @else
             @endif
         </tbody>
          <tfoot>
                    <tr><th colspan="5">Total</th>
                    <
                    <th><b>{{number_format($totalchecks,2)}}</b></th>
                    <th></th></tr>
        
         </tfoot>    
     </table> 
     </div>    
     </div>  
  <div class="col-md-3 pull-left">
         <a href="{{url('/cashier',array('print','list_of_checks',$date_from,$date_to))}}" class="btn btn-primary" target="_blank">Print</a>
     </div>   
  </div> 
    
@endsection
@section('footerscript')

   
     <!-- daterange picker -->
<link rel="stylesheet" href="{{url('/bower_components',array('bootstrap-daterangepicker','daterangepicker.css'))}}">
<script src="{{url('/bower_components',array('datatables.net','js','jquery.dataTables.min.js'))}}"></script>
<script src="{{url('/bower_components',array('datatables.net-bs','js','dataTables.bootstrap.min.js'))}}"></script>
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
        document.location="{{url('/cashier',array('list_of_checks'))}}" + "/" + $("#date_from").val() + "/" + $("#date_to").val();
    });
      
    });
    
</script>    
@endsection
