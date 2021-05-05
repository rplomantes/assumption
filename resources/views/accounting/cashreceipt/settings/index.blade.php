<?php
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
        Cash Receipt Settings
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Cash Receipt Settings</li>
    </ol>
</section>
@endsection
@section('maincontent')
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-9">
            <div class='box box-body'>
                <button class="btn btn-primary" data-toggle="modal" data-target="#modal-default">Add</button>
                <table class='table table-striped'>
                    <tr><th>Accounting Code</th><th>Accounting Name</th><th>Debit or Credit?</th><th>Sort No.</th><th></th><th></th></tr>
                    @foreach($accounting_codes as $accounting_code)
                    <tr>
                        <td>{{$accounting_code->accounting_code}}</td>
                        <td>{{$accounting_code->accountingName()}}</td>
                        <td>{{$accounting_code->debit_or_credit}}</td>
                        <td>{{$accounting_code->sort_no}}</td>
                        <td><a href="javascript:void(0)" data-toggle="modal" data-target="#modal-default" onclick="getDetails('{{$accounting_code->id}}')">Update</a></td>
                        <td><a href='{{url('accounting',array('settings','cashreceipt','delete',$accounting_code->id))}}'>Remove</a></td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <form type='get' class="form" action="{{url('/accounting/settings/cashreceipt/add')}}">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Add</h4>
                </div>
                <h1>
                    <div class="modal-body" style="font-size: 11pt;">
                        {{csrf_field()}}
                        <div class="form-group">
                            <label>Accounting Details</label>
                            <select name="accounting_code" class="form form-control">
                                @foreach($chart_of_accounts as $chart)
                                <option value="{{$chart->accounting_code}}">{{$chart->accounting_code}}-{{$chart->accounting_name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Debit or Credit</label>
                            <select name="debit_or_credit" class="form form-control">
                                <option value="debit"  selected="">Debit</option>
                                <option value="credit" selected="">Credit</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Sort No.</label>
                            <input type="number" name="sort_no" class="form form-control" min="1" value="1">
                        </div>
                    </div>
                </h1>
                <div class="modal-footer">

                    <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                    <input type="submit" value="Add" class="btn btn-success">
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
@section('footerscript')
<script>
    function getDetails(id) {
    array = {};
    array['id'] = id;
    $.ajax({
    type: "GET",
            url: "/accounting/settings/cashreceipt/get_details",
            data: array,
            success: function (data) {
            $(".modal-content").html(data);
            }
    });
    }
</script>    
@endsection