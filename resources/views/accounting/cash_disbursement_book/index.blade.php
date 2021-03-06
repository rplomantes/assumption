<?php 
    if (Auth::user()->accesslevel==env("ACCTNG_STAFF")){
        $layout = "layouts.appaccountingstaff";    
    }else if (Auth::user()->accesslevel==env("ACCTNG_HEAD")){
        $layout = "layouts.appaccountinghead";    
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
        Cash Disbursement Book
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Cash Disbursement Book</li>
      </ol>
</section>
@endsection
@section('maincontent')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
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
            <div class="row">
                <div class="col-sm-6">
                    {!! $accountings->render() !!}
                </div>
                <div class="col-sm-2 pull-right">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <a target="_blank" href='{{url('/accounting/excel_cash_disbursement_book', array($date_from, $date_to, $accountings->currentPage()))}}' class="btn btn-flat btn-block btn-primary"><i class='fa fa-table'></i> Generate CSV</a>
                    </div>
                </div>
                <div class="col-sm-2 pull-right">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <a target="_blank" href='{{url('/accounting/print_cash_disbursement_book', array($date_from, $date_to, $accountings->currentPage()))}}' class="btn btn-flat btn-block btn-primary"><i class='fa fa-print'></i> Generate PDF</a>    
                    </div>
                </div>
            </div>
            
            
            <div class="table-responsive">
                <table class="table" cellspacing="2" cellpadding="0" style="width:100%" border="1">
                    <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th>CIB-BPI 1811000716</th>
                            <th>PURCHASES</th>
                            <th>REPAIRS AND MAINTENANCE</th>
                            <th>A/R EMPLOYEE ADVANCES</th>
                            <th>OFFICE SUPPLIES</th>
                            <th>LIBRARY EXP</th>
                            <th>CASH IN BANK - PASONG TAMO</th>
                            <th>CASH IN BANK - BPI PAYROLL</th>
                            <th>W/T EXP</th>
                            <th>W/T COMP</th>
                            <th colspan="3" style="text-align:center">SUNDRIES</th>
                        </tr>
                        <tr>
                            <th>DATE</th>
                            <th>PAYEE</th>
                            <th>PARTICULARS</th>
                            <th>CV#</th>
                            <th>CHECKNO</th>
                            <th>CR</th>
                            <th>DR</th>
                            <th>DR</th>
                            <th>DR</th>
                            <th>DR</th>
                            <th>DR</th>
                            <th>CR</th>
                            <th>CR</th>
                            <th>CR</th>
                            <th>CR</th>
                            <th>ACCT TITLE</th>
                            <th>DEBIT</th>
                            <th>CREDIT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($accountings->unique("reference_id") as $reference)
                        <?php $disbursement = \App\Disbursement::where("reference_id", $reference->reference_id)->first(); ?>
                        <tr>
                            <td>{{date("d/m/y", strtotime($disbursement->transaction_date))}}</td>
                            <td>{{strtoupper($disbursement->payee_name)}}</td>
                            <td>{{strtoupper($disbursement->remarks)}}</td>
                            <td>{{$disbursement->voucher_no}}</td>
                            <td>{{$disbursement->check_no}}</td>
                            <td>
                                @if($accountings->where("reference_id", $reference->reference_id)->where("accounting_code","1046")->sum("credit") > 0)
                                {{number_format($accountings->where("reference_id", $reference->reference_id)->where("accounting_code","1046")->sum("credit"),2)}}</td>
                                @endif                                
                            <td>
                                @if($accountings->where("reference_id", $reference->reference_id)->whereIn("accounting_code",["7892","7893"])->sum("debit") > 0)
                                {{number_format($accountings->where("reference_id", $reference->reference_id)->where("accounting_code",["7892","7893"])->sum("debit"),2)}}
                                @endif
                            </td>
                            <td>
                                @if($accountings->where("reference_id", $reference->reference_id)->where("accounting_code","7331")->sum("debit") > 0)
                                {{number_format($accountings->where("reference_id", $reference->reference_id)->where("accounting_code","7331")->sum("debit"),2)}}
                                @endif
                            </td>
                            <td>
                                
                            </td>
                            <td>
                                @if($accountings->where("reference_id", $reference->reference_id)->where("accounting_code","7311")->sum("debit") > 0)
                                {{number_format($accountings->where("reference_id", $reference->reference_id)->where("accounting_code","7311")->sum("debit"),2)}}
                                @endif
                            </td>
                            <td>
                                @if($accountings->where("reference_id", $reference->reference_id)->where("accounting_code","7127")->sum("debit") > 0)
                                {{number_format($accountings->where("reference_id", $reference->reference_id)->where("accounting_code","7127")->sum("debit"),2)}}
                                @endif    
                            </td>
                            <td></td>
                            <td>
                                @if($accountings->where("reference_id", $reference->reference_id)->where("accounting_code","1061")->sum("credit") > 0)
                                {{number_format($accountings->where("reference_id", $reference->reference_id)->where("accounting_code","1061")->sum("credit"),2)}}
                                @endif
                            </td>
                            <td>
                                @if($accountings->where("reference_id", $reference->reference_id)->where("accounting_code","2311")->sum("credit") > 0)
                                {{number_format($accountings->where("reference_id", $reference->reference_id)->where("accounting_code","2311")->sum("credit"),2)}}
                                @endif
                            </td>
                            <td>
                                @if($accountings->where("reference_id", $reference->reference_id)->where("accounting_code","2301")->sum("credit") > 0)
                                {{number_format($accountings->where("reference_id", $reference->reference_id)->where("accounting_code","2301")->sum("credit"),2)}}
                                @endif
                            </td>
                            <td>
                                @foreach($sundries->where("reference_id", $reference->reference_id)->unique("accounting_name") as $sundry)
                                {{$sundry->accounting_name}}<br>
                                @endforeach
                            </td>
                            <td>
                                @foreach($sundries->where("reference_id", $reference->reference_id)->unique("accounting_name") as $sundry)
                                    {{number_format($sundries->where("reference_id", $reference->reference_id)->where("accounting_name", $sundry->accounting_name)->sum("debit"),2)}}<br>
                                @endforeach
                            </td>
                            <td>
                                @foreach($sundries->where("reference_id", $reference->reference_id)->unique("accounting_name") as $sundry)
                                {{number_format($sundries->where("reference_id", $reference->reference_id)->where("accounting_name", $sundry->accounting_name)->sum("credit"),2)}}<br>
                                @endforeach
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footerscript')
<link rel="stylesheet" href="{{url('/bower_components',array('bootstrap-daterangepicker','daterangepicker.css'))}}">
<script src="{{url('/',array('bower_components','moment','min','moment.min.js'))}}"></script>
<script src="{{url('/',array('bower_components','bootstrap-daterangepicker','daterangepicker.js'))}}"></script>

<script>
 
    
$(document).ready(function () {
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
        document.location = "{{url('/accounting',array('cash_disbursement_book'))}}" + "/" + $("#date_from").val() + "/" + $("#date_to").val();
    });

});



</script>    
@endsection
