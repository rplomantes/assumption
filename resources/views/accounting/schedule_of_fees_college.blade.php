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
        Schedule of Fees
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="{{url("/accounting/schedule_of_fees")}}">Schedule of Fees</a></li>
    </ol>
</section>
@endsection
@section('maincontent')

<div class="box">
    <div class="box-header">
        <div class="box-title">Select Fee</div>
    </div>
    <div class="box-body form-horizontal">
        <div class="form-group">
            <div class="col-sm-6">
                <label>Select Fee</label>
                <select class="form form-control select2" name="fee_type" id="fee_type" onchange="getFeeType()">
                    <option>&nbsp;</option>
                    <option value="9">Tuition Fees</option>
                    <option value="1">Other Fees</option>
                    <option value="2">Non Discounted Other Fees</option>
                    <option value="3">Other Fees (New Student)</option>
                    <option value="4">Non Discounted Other Fees (New Student)</option>
                    <option value="5">Practicum Fee</option>
                    <option value="6">Practicum Foreign Fee</option>
                    <option value="7">Late Payment Fees</option>
                    <option value="8">Foreign Fees</option>
                </select>
            </div>
        </div>
        <div id="display_type">

        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="col-md-6">    
                    <div id="display_fees">

                    </div>
                </div>
                <div class="col-md-6">   
                    <div id="display_form">

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
@section('footerscript')
<script>
    $("#tuition_other").hide();
    $("#non_discounted").hide();
    $("#new_others").hide();



    function getFeeType() {
        array = {};
        array['fee_type'] = $("#fee_type").val();
        $.ajax({
            type: "GET",
            url: "/accounting/ajax/getFeeType",
            data: array,
            success: function (data) {
                $('#display_type').html(data);
                $('#display_fees').empty();
                $('#display_form').empty();
            },
            error: function () {
                $('#display_type').html("Nothing to show.");
                $('#display_fees').empty();
                $('#display_form').empty();
            }
        });
    }

    function getFees() {
        array = {};
        array['fee_type'] = $("#fee_type").val();
        array['level'] = $("#level").val();
        array['program_code'] = $("#program_code").val();
        array['period'] = $("#period").val();
        $.ajax({
            type: "GET",
            url: "/accounting/ajax/getFees",
            data: array,
            success: function (data) {
                $('#display_fees').html(data);
                $('#display_form').empty();
            },
            error: function () {
                $('#display_fees').html("Nothing to show.");
                $('#display_form').empty();
            }
        });
    }

    function removeFee(id) {
        array = {};
        array['fee_type'] = $("#fee_type").val();
        $.ajax({
            type: "GET",
            url: "/accounting/ajax/removeFees/" + id,
            data: array,
            success: function (data) {
                var type = $("#fee_type").val();
                if(type <= 4){
                getFees();
                }else if(type == 9){
                getFees();
                }else{
                getFeeType();
                getFees();
                }
            },
            error: function () {
                var type = $("#fee_type").val();
                if(type <= 4){
                getFees();
                }else if(type == 9){
                getFees();
                }else{
                getFeeType();
                getFees();
                }
            }
        });
    }

    function updateFee(id) {
        array = {};
        array['fee_type'] = $("#fee_type").val();
        $.ajax({
            type: "GET",
            url: "/accounting/ajax/updateFees/" + id,
            data: array,
            success: function (data) {
                $('#display_form').html(data);
                $("#account").select2();
            },
            error: function () {
                $('#display_form').html("Nothing to show.");
            }
        });
    }

    function saveData() {
        array = {};
        array['type'] = $("#type").val();
        array['record_id'] = $("#record_id").val();
        array['amount'] = $("#amount").val();
        if ($("#type").val() !== 9) {
            array['account'] = $("#account").val();
            array['category'] = $("#category").val();
            array['subsidiary'] = $("#subsidiary").val();
        }
        $.ajax({
            type: "GET",
            url: "/accounting/ajax/updateSaveFees",
            data: array,
            success: function (data) {
                $('#display_form').html(data);
                var type = $("#fee_type").val();
                if(type <= 4){
                getFees();
                }else if(type == 9){
                getFees();
                }else{
                getFeeType();
                getFees();
                }
            },
            error: function () {
                $('#display_form').html("Nothing to show.");
                var type = $("#fee_type").val();
                if(type <= 4){
                getFees();
                }else if(type == 9){
                getFees();
                }else{
                getFeeType();
                getFees();
                }
            }
        });
    }

    function newFee() {
        array = {};
        array['fee_type'] = $("#fee_type").val();
        $.ajax({
            type: "GET",
            url: "/accounting/ajax/newFees/",
            data: array,
            success: function (data) {
                $('#display_form').html(data);
                $("#account").select2();
            },
            error: function () {
                $('#display_form').html("Nothing to show.");
            }
        });
    }

    function saveNewData() {
        array = {};
        array['type'] = $("#type").val();
        array['amount'] = $("#amount").val();
        array['account'] = $("#account").val();
        array['category'] = $("#category").val();
        array['subsidiary'] = $("#subsidiary").val();
        array['level'] = $("#level").val();
        array['program_code'] = $("#program_code").val();
        array['period'] = $("#period").val();
        $.ajax({
            type: "GET",
            url: "/accounting/ajax/newSaveFees",
            data: array,
            success: function (data) {
                var type = $("#fee_type").val();
                if(type <= 4){
                getFees();
                }else if(type == 9){
                getFees();
                }else{
                getFeeType();
                getFees();
                }
            },
            error: function () {
                $('#display_form').html("Nothing to show.");
                var type = $("#fee_type").val();
                if(type <= 4){
                getFees();
                }else if(type == 9){
                getFees();
                }else{
                getFeeType();
                getFees();
                }
            }
        });
    }

</script>
@endsection
