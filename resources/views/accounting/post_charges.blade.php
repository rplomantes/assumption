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
    <div class="box-header">
        <div class="box-title">Search</div>
    </div>
    <form method="post" action="{{url('/accounting/')}}">
        {{ csrf_field() }}
        <div class="box-body form-horizontal">
            <div class="form-group">
                <div class="col-sm-3">
                    <label>Select Level</label>
                    <select name="level" id="level" class="form form-control" onchange="getdues()">
                        @foreach ($levels as $level)
                        <option value="{{$level->level}}">{{$level->level}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-sm-3" id="strand_control">
                    <label>Select Plan</label>
                    <select name="plan" id="plan" class="form form-control" onchange="getdues()">
                        <option value=""></option>    
                        @foreach($plans as $plan)
                        <option value="{{$plan->plan}}">{{$plan->plan}}</option>
                        @endforeach
                    </select> 
                </div>
                <div class="col-sm-3" id="section_control">
                    <label>Select Due Date</label>
                    <select name="dates" id="dates" class="form form-control" onchange="getUnpaid()">
                        <option></option>
                    </select> 
                </div>
            </div>
        </div>
    </form>
    <div class="container-fluid" id="unpaid">
        @if($indic != 0)
        <h4>Successfully posted late payment charges for <strong>{{$indic}}</strong> student/s</h4>
        @endif
    </div>
</div>
@endsection
@section('footerscript')
<script>
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
    
    function reversePost($idno) {
        array = {};
        array['level'] = $("#level").val();
        array['plan'] = $("#plan").val();
        array['dates'] = $("#dates").val();
        $.ajax({
            type: "GET",
            url: "/accounting/ajax/reverse_post/" + $idno ,
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
