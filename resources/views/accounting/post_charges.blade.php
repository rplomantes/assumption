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
        Post Charges
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="{{url("/accounting/post_charges")}}">Post Charges</a></li>
    </ol>
</section>
@endsection
@section('maincontent')

<div class="box">
    <div class="container-fluid" id="unpaid">
        @if($indic != 0)
        <h4>Successfully posted late payment charges for <strong>{{$indic}}</strong> student/s</h4>
        @endif
    </div>
</div>
@endsection
@section('footerscript')
<script>
    
    $(document).ready(function(){
        $.ajax({
            type: "GET",
            url: "/accounting/ajax/get_unpaid/",
            success: function (data) {
                $('#unpaid').html(data);
            },
            error: function () {
                $('#unpaid').html("Nothing to show.");
            }
        });
    });


    function getdues() {
        array = {};
        array['level'] = $("#level").val();
        array['plan'] = $("#plan").val();
        $.ajax({
            type: "GET",
            url: "/accounting/ajax/get_due_dates",
            data: array,
            success: function (data) {
                $('#dates').html(data);
            }
        }
        );
    }
    
    
    
    function getUnpaid() {
        array = {};
        array['level'] = $("#level").val();
        array['plan'] = $("#plan").val();
        array['dates'] = $("#dates").val();
        $.ajax({
            type: "GET",
            url: "/accounting/ajax/get_unpaid/",
            data: array,
            success: function (data) {
                $('#unpaid').html(data);
            },
            error: function () {
                $('#unpaid').html("Nothing to show.");
            }
        });
    }
    
    function reversePost(idno) {
        array = {};
        array['dates'] = $("#dates").val();
        $.ajax({
            type: "GET",
            url: "/accounting/ajax/reverse_post/" + idno ,
            data: array,
            success: function (data) {
                $('#unpaid').html(data);
            },
            error: function () {
                $('#unpaid').html("Nothing to show.");
            }
        });
    }
</script>
@endsection
