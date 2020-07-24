<?php
if (Auth::user()->accesslevel == env("ACCTNG_STAFF")) {
    $layout = "layouts.appaccountingstaff";
} else if (Auth::user()->accesslevel == env("ACCTNG_HEAD")) {
    $layout = "layouts.appaccountinghead";
}
?>
@extends($layout)
@section('maincontent')
<?php
$date_from = \Carbon\Carbon::today()->toDateString();
?>
<link rel="stylesheet" href="{{ asset('css/dataTables.bootstrap.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('css/daterangepicker.css') }}" />
<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <h3 class="display">Check Disbursement</h3>
        </div>
    </div>
    <div class="col-md-12">
        <div class='row'>
            <form action="{{ url('/print/disbursement_note') }}" method="post">
                {{csrf_field()}}
                <div class='col-md-4'>
                    <div class="form-group">
                        <label>Date range button:</label>
                        <div class="input-group">
                            <button type="button" class="btn btn-default pull-left" id="daterange">
                                <span>
                                    <i class="fa fa-calendar"></i> <span id='range'>{{date('Y-m-d')}} , {{date('Y-m-d')}}</span>
                                </span>
                                <i class="fa fa-caret-down"></i>

                            </button>

                            <input id="type" class="form-control" name="type" type="hidden" value=0>
                            <input id="date_to" class="form-control" name="date_to" type="hidden" value="{{date('Y-m-d')}}">
                            <input id="date_from" class="form-control" name="date_from" type="hidden" value="{{date('Y-m-d')}}">
                        </div>
                    </div>
                </div>
                <div class='col-md-8'>
                    </br>
                    <a class='btn btn-warning' id='view'><span class="fa fa-eye"></span><b> View Summary</b></a>
                    <button type="submit" class="btn btn-primary"><span class="fa fa-print"></span><b> Print Summary</b></button>
                    <a type="button" class="btn btn-success" href="{{url("/disbursement/new")}}"><span class="fa fa-plus"></span><b> Create New</b></a>
                </div>
            </form>
        </div>
        <div class="box">
            <div id="display" class="box-body">
                <h4 class="display"></h4>
                <table class="table table-condensed dataTable" id="example1">
                    <thead>
                    <th class="col-md-2">Voucher No.</th>
                    <th class="col-md-2">Date</th>
                    <th class="col-md-2">Payee Name</th>
                    <th class="col-md-3">Remarks</th>
                    <th class="col-md-2">Amount</th>
                    <th class="col-md-1"></th>
                    </thead>
                    <tbody>
                        @foreach($lists as $list)
                        <tr>
                            <td>{{str_pad($list->voucher_no,4,"0",STR_PAD_LEFT)}}</td>
                            <td>{{date_format(date_create($list->transaction_date),"F d, Y")}}</td>
                            <td>{{$list->payee_name}}</td>
                            <td>{{$list->remarks}}</td>
                            <td>{{number_format($list->amount,2)}}</td>
                            <td><a type="button" href="{{url('/view/disbursement/'.$list->reference_id)}}"><b> View</b></a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop

@section('footerscript')
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

    $('#view').on('click', function () {
        var array = {};
        array["date_to"] = $('#date_to').val();
        array["date_from"] = $('#date_from').val();
        array["type"] = 0;
        $.ajax({
            type: "GET",
            url: "/ajax/get_disbursements_note",
            data: array,
            success: function (data) {
                $("#display").empty();
                $("#display").html(data);
               $('#example1').DataTable();
            }
        });
    });
});
</script>
@stop