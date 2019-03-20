@extends('layouts.appbookstore')
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
        Updating of Books/Materials Pricing
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Updating of Books/Materials Pricing</li>
    </ol>
</section>
@endsection
@section('maincontent')

<div class="box">
    <div class="box-header">
        <div class="box-title">Select Group</div>
    </div>
    <div class="box-body form-horizontal">
        <div class="form-group">
            <div class="col-sm-6">
                <label>Select Group</label>
                <select class="form form-control select2" name="group_type" id="group_type" onchange="getGroupType()">
                    <option>&nbsp;</option>
                    <option value="1">Books/Materials Prices</option>
                    <option value="2">Required/Other Required Materials Listing</option>
                    <option value="5">Uniform Sizes and Prices</option>
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

function display_particular_control(category){
    if(category == "Materials" || category =="Other Materials"){
         $("#particular_control").fadeOut(300);  
       }else {  
       $("#particular_control").fadeIn(300);
        }
}

    function getGroupType() {
        array = {};
        array['group_type'] = $("#group_type").val();
        $.ajax({
            type: "GET",
            url: "/bookstore/ajax/getGroupType",
            data: array,
            success: function (data) {
                $('#display_type').html(data);
                $('#display_fees').empty();
                $('#display_form').empty();
            },
            error: function () {
                $('#display_type').html("");
                $('#display_fees').empty();
                $('#display_form').empty();
            }
        });
    }

    function getFees() {
        array = {};
        array['group_type'] = $("#group_type").val();
        array['level'] = $("#level").val();
        $.ajax({
            type: "GET",
            url: "/bookstore/ajax/getFees",
            data: array,
            success: function (data) {
                $('#display_fees').html(data);
                $('#display_form').empty();
            },
            error: function () {
                $('#display_fees').html("");
                $('#display_form').empty();
            }
        });
    }

    function removeFee(id) {
        array = {};
        array['group_type'] = $("#group_type").val();
        $.ajax({
            type: "GET",
            url: "/bookstore/ajax/removeFees/" + id,
            data: array,
            success: function (data) {
                var type = $("#group_type").val();
                if(type <= 4){
                getFees();
                }else if(type == 9){
                getFees();
                }else{
                getGroupType()
                getFees();
                }
            },
            error: function () {
                var type = $("#group_type").val();
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
        array['group_type'] = $("#group_type").val();
        $.ajax({
            type: "GET",
            url: "/bookstore/ajax/updateFees/" + id,
            data: array,
            success: function (data) {
                $('#display_form').html(data);
                $("#account").select2();
            },
            error: function () {
                $('#display_form').html("");
            }
        });
    }

    function saveData() {
        array = {};
        array['type'] = $("#group_type").val();
        array['record_id'] = $("#record_id").val();
        array['amount'] = $("#amount").val();
        array['particular'] = $("#particular").val();
        array['subsidiary'] = $("#particular").val();
        array['subsidiary2'] = $("#subsidiary").val();
        array['category'] = $("#category").val();
        array['size'] = $("#size").val();
        $.ajax({
            type: "GET",
            url: "/bookstore/ajax/updateSaveFees",
            data: array,
            success: function (data) {
                $('#display_form').html(data);
                var type = $("#group_type").val();
                if(type <= 4){
                getFees();
                }else if(type == 9){
                getFees();
                }else{
                getGroupType()
                getFees();
                }
            },
            error: function () {
                $('#display_form').html("");
                var type = $("#group_type").val();
                if(type <= 4){
                getFees();
                }else if(type == 9){
                getFees();
                }else{
                getGroupType()
                getFees();
                }
            }
        });
    }

    function newFee() {
        array = {};
        array['group_type'] = $("#group_type").val();
        $.ajax({
            type: "GET",
            url: "/bookstore/ajax/newFees/",
            data: array,
            success: function (data) {
                $('#display_form').html(data);
                $("#account").select2();
            },
            error: function () {
                $('#display_form').html("");
            }
        });
    }

    function saveNewData() {
        array = {};
        array['type'] = $("#group_type").val();
        array['record_id'] = $("#record_id").val();
        array['amount'] = $("#amount").val();
        array['particular'] = $("#particular").val();
        array['subsidiary'] = $("#particular").val();
        array['subsidiary2'] = $("#subsidiary").val();
        array['category'] = $("#category").val();
        array['level'] = $("#level").val();
        array['size'] = $("#size").val();
        $.ajax({
            type: "GET",
            url: "/bookstore/ajax/newSaveFees",
            data: array,
            success: function (data) {
                var type = $("#group_type").val();
                if(type <= 4){
                getFees();
                }else if(type == 9){
                getFees();
                }else{
                getGroupType()
                getFees();
                }
            },
            error: function () {
                $('#display_form').html("");
                var type = $("#group_type").val();
                if(type <= 4){
                getFees();
                }else if(type == 9){
                getFees();
                }else{
                getGroupType()
                getFees();
                }
            }
        });
    }

</script>
@endsection
