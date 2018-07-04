@extends('layouts.appaccountingstaff')
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
        Search Official Receipt
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Search OR</li>
    </ol>
</section>
@endsection
@section('maincontent')
<!-- search form (Optional) -->
<div class="box">
    <div class="box-body">
        <div class="col-md-4">
            <label>Search OR Number or Payee's Name</label>
            <input type="text" id="search" class="form-control" placeholder="Search...">

            <div id="displaystudent">
            </div>    
        </div>    
        <div class="col-sm-8"></div>
        <div class="col-sm-12" id="display_or">

        </div>
    </div>
</div>
@endsection
@section('footerscript')
<script>
    $(document).ready(function () {
        $("#search").on('keypress', function (e) {
            if (e.keyCode == 13) {
                var array = {};
                array['search'] = $("#search").val();
                $.ajax({
                    type: "GET",
                    url: "/accounting/ajax/getsearch_or",
                    data: array,
                    success: function (data) {
                        $("#display_or").html(data)
                        $("#search").val("");
                    }
                })
            }
        });
    });
</script>    
@endsection
