@extends('layouts.appaccountingstaff')
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
@endsection
@section('maincontent')
<section class="content">
    <div class="row">
        <div class="col-md-12">

            <div class="form-group">
                <label>Date range button:</label>
                <div class="input-group">
                    <button type="button" class="btn btn-default pull-left" id="daterange">
                        <span>
                            <i class="fa fa-calendar"></i> <span id='range'>{{$date_start}} , {{$date_end}}</span>
                        </span>
                        <i class="fa fa-caret-down"></i>

                    </button>

                    <a href="javascript:void(0)" class="btn btn-primary" id="view-button">View Summary</a>

                    <input id="date_to" class="form-control" type="hidden" value="{{$date_start}}">
                    <input id="date_from" class="form-control" type="hidden" value="{{$date_end}}">
                </div>
            </div>
        </div>
@foreach($cash_receipt as $or)
{{$or->transaction_date}}, {{$or->receipt_no}}, {{$or->amount_received}},{{$or->debits}}<br><br>
@endforeach
    </div>
</section>



@endsection
@section('footerscript')
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
        document.location="{{url('/accounting',array('cash_receipt'))}}/" + $("#date_from").val() + "/" + $("#date_to").val();
    });
      
    });
    
</script>
@endsection

