<?php
if(Auth::user()->accesslevel == env('ADMISSION_BED')){
$layout = "layouts.appadmission-bed";
} else {
$layout = "layouts.appadmission-shs";
}
?>

@extends($layout)
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
        Reservations
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">For Approval</li>
    </ol>
</section>
@endsection
@section('maincontent') 
<div class="col-md-12">  
    <div class="box">
        <div class="box-header">
            <div class="box-title">Result</div>
        </div>
<?php $counter = 1; ?>
        <div class="box-body">
            <table id="example1" class="table table-condensed">
                <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="10%">ID Number</th>
                    <th>Name</th>
                    <th>Contact No.</th>
                    <th>Level</th>
                    <th>Strand</th>
                    <th width="20%">Transaction Date</th>
                    <th width="10%">Amount</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reservations as $reservation)
                <tr>
                    <td>{{$counter}}. <?php $counter++; ?></td>
                    <td>{{$reservation->idno}}</td>
                    <td>{{strtoupper($reservation->lastname)}}, {{$reservation->firstname}} {{$reservation->middlename}}</td>
                    <td>{{$reservation->cell_no}}; {{$reservation->tel_no}}</td>
                    <td>{{$reservation->level}}</td>
                    <td>{{$reservation->strand}}</td>
                    <td>{{$reservation->transaction_date}}</td>
                    <td>{{number_format($reservation->amount,2)}}</td>
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
        document.location = "{{url('/bedadmission',array('reports'))}}" + "/for_approval/" + $("#date_from").val() + "/" + $("#date_to").val();
    });

});

</script>
<!-- DataTables -->
<script src="{{url('bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{url('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
<script>
  $(function () {
    $('#example1').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>
@endsection
