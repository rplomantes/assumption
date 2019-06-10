<?php
$totaldm = 0;
$totalcanceled = 0;
$layout = "";
if (Auth::user()->accesslevel == env("ACCTNG_STAFF")) {
    $layout = "layouts.appaccountingstaff";
} else if (Auth::user()->accesslevel == env("ACCTNG_HEAD")) {
    $layout = "layouts.appaccountinghead";
}
?>
@extends($layout)
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
        Collection Report
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Debit Summary</li>
    </ol>
</section>
@endsection
@section('maincontent')
<?php $dm_users = \App\DebitMemo::distinct()->join('users', 'users.idno','=', 'debit_memos.posted_by')->where('users.accesslevel','!=',0)->orderBy('debit_memos.posted_by','asc')->get(['posted_by']); ?>
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


            <input id="date_to" class="form-control" type="hidden" value="{{$date_to}}">
            <input id="date_from" class="form-control" type="hidden" value="{{$date_from}}">
            <label>Posted by:</label>
            <select name="posted_by" class='posted_by' id="posted_by">
                <option @if($posted_by == 'all') selected='' @endif value="all">All</option>
                @foreach($dm_users as $dm_user)
                <option @if($dm_user->posted_by == $posted_by) selected='' @endif>{{$dm_user->posted_by}}</option>
                @endforeach
            </select>
            <a href="javascript:void(0)" class="btn btn-primary" id="view-button">View Summary</a>
        </div>
    </div>

    <div class="box">  
        <div class="box-body table-responsive">
            <table id="example1" class="table table-responsive table-striped">
                <thead>
                    <tr>
                        <th>DM No</th>
                        <th>ID No</th>
                        <th>Name</th>
                        <th>Explanation</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Posted By</th>
                        <th>View</th>
                    </tr>
                </thead>
                <tbody>
                    @if(count($debits)>0)
                    @foreach($debits as $debit)
                    <?php $user = \App\User::where('idno', $debit->idno)->first(); ?>
                    <tr>
                        <td>{{$debit->dm_no}}</td>
                        <td>{{$debit->idno}}</td>
                        <td>{{$user->getFullNameAttribute()}}</td>
                        <td>{{$debit->explanation}}</td>
                        <td>{{$debit->amount}}</td>
                        @if($debit->is_reverse=="0")
                        <?php $totaldm = $totaldm + $debit->amount; ?>
                        <td>OK</td>
                        @else
                        <?php $totalcanceled = $totalcanceled + $debit->amount; ?>
                        <td>Canceled</td>
                        @endif
                        <td>{{$debit->posted_by}}</td>
                        <td><a href="{{url('/accounting',array('view_debit_memo',$debit->reference_id))}}">View</a></td>
                    </tr>
                    @endforeach
                    @else
                    @endif
                </tbody>
            </table> 
            <div class="col-sm-3">
                <span>
                    <br>Summary of Transactions
                </span>
                <table cellspacing="0" cellpadding="2" width="50%" class="table table-responsive">
                    <tr>
                        <td>Total DM</td>
                        <td align="right"><strong>{{number_format($totaldm,2)}}</strong></td>
                    </tr>
                    <tr>
                        <td>Total Canceled</td>
                        <td align="right"><strong>{{number_format($totalcanceled,2)}}</strong></td>
                    </tr>
                </table>  
            </div>
        </div>
    </div>
</div> 
<div class="col-md-3 pull-left">
    <a href="{{url('/accounting',array('print_debit_summary',$date_from,$date_to,$posted_by))}}" class="btn btn-primary" target="_blank">Print</a>
</div>

@endsection
@section('footerscript')
<style>
    table  .decimal{
        text-align: right;
        padding-right: 10px;
    }
</style>    
<!-- daterange picker -->
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
        document.location = "{{url('/accounting',array('debit_summary'))}}" + "/" + $("#date_from").val() + "/" + $("#date_to").val() + "/" + $("#posted_by").val();
    });

});

</script>    
@endsection
