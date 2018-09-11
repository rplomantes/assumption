@extends('layouts.appbedregistrar')
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
        Daily Enrollment Statistics
        <small>{{$date_start}} - {{$date_end}}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="#"> Reports</a></li>
        <li class="active"><a href="{{ url ('/bed_registrar', array('reports','total_daily_enrollment_statistics',$date_start, $date_end))}}"> Enrollment Statistics</a></li>
    </ol>
</section>
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
            
            <div class="box">
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered">
                        <thead>
                            <tr>              
                                <th>#</th>
                                <th>Id Number</th>
                                <th>Last Name</th>
                                <th>First Name</th>
                                <th>Level</th>
                                <th>Section</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 1; ?>
                            @foreach($students as $student)
                            <?php $info = \App\User::where('idno', $student->idno)->first(); ?>    
                            <tr>
                                <td>{{$count}}</td>
                                <td>{{$student->idno}}</td>
                                <td>{{$info->lastname}}</td>
                                <td>{{$info->firstname}}</td>
                                <td>{{$student->level}}</td>
                                    <td>{{$student->section}}</td>
                            </tr>
                            <?php $count = $count + 1; ?>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <a target='_blank' href='{{url('bed_registrar', array('reports', 'print_total_daily_enrollment_statistics', $date_start, $date_end))}}'><button class="btn btn-success col-sm-12">PRINT DAILY ENROLLMENT REPORT</button></a>
        </div>
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
        document.location="{{url('/bed_registrar',array('reports'))}}" + "/total_daily_enrollment_statistics/" + $("#date_from").val() + "/" + $("#date_to").val();
    });
      
    });
    
</script>

@endsection