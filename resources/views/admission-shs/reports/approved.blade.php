@extends('layouts.appadmission-bed')
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
        Approved Applicants
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Approved Applicants</li>
    </ol>
</section>
@endsection
@section('maincontent')
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
<div class="col-md-12">  
    <div class="box">
        <div class="box-header">
            <div class="box-title">Result</div>
        </div>
        <div class="box-body">
            <table id="example2" class="table table-condensed">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID Number</th>
                        <th>Name</th>
                        <th>Level</th>
                        <th>Date of Birth</th>
                        <th>Date Approved</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $number = 1; ?>
                    @foreach ($for_approval_sched as $p)
                    <?php $dob = \App\BedProfile::where('idno', $p->idno)->first()->date_of_birth; ?>
                    <tr>
                        <td>{{$number++}}</td>
                        <td>{{$p->idno}}</td>
                        <td>{{$p->lastname}}, {{$p->firstname}} {{$p->middlename}} {{$p->extensionname}}</td>
                        <td>{{$p->level}}</td>
                        <td>{{$dob}}</td>
                        <td>{{$p->date_admission_finish}}</td>
                        
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div> 
</div>
@endsection
@section('footerscript') 
<link rel="stylesheet" href="{{url('/bower_components',array('bootstrap-daterangepicker','daterangepicker.css'))}}">
<script src="{{url('/bower_components',array('datatables.net','js','jquery.dataTables.min.js'))}}"></script>
<script src="{{url('/bower_components',array('datatables.net-bs','js','dataTables.bootstrap.min.js'))}}"></script>
<script src="{{url('/',array('bower_components','moment','min','moment.min.js'))}}"></script>
<script src="{{url('/',array('bower_components','bootstrap-daterangepicker','daterangepicker.js'))}}"></script>
<script>
$(document).ready(function () {
    $('#example1').DataTable();
    $('#daterange').daterangepicker(
            {
                ranges: {
                    'Select': [moment(), moment()],
                    'Today': [moment(), moment()],
                    'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    'Last 7 Days': [moment().subtract(6, 'days'), moment()],
                    'Last 30 Days': [moment().subtract(29, 'days'), moment()],
                    'This Month': [moment().startOf('month'), moment().endOf('month')],
                    'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                startDate: moment(),
                endDate: moment(),

            },
            function (start, end) {
                $('#daterange span #range').html(start.format('YYYY-MM-DD') + ' , ' + end.format('YYYY-MM-DD'));
                x = $('#range').html();
                splitdate = x.split(',');
                todate = splitdate[1];
                fromdate = splitdate[0];
                to = todate.trim()
                from = fromdate.trim()
                $('#date_to').val(to);
                $('#date_from').val(from);
            });
    $("#view-button").on('click', function (e) {
        document.location = "{{url('/bedadmission',array('reports'))}}" + "/approved/" + $("#date_from").val() + "/" + $("#date_to").val();
    });

});

</script>
<!-- DataTables -->
<script src="{{url('bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{url('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
<script>
  $(function () {
    $('#example2').DataTable({
      'order'       : [[ 3, "asc" ]]
    })
  })
</script>
@endsection
