@extends('layouts.appbedregistrar')
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
        Sibling Discounts
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url("/bedregistrar/siblings")}}"><i class="fa fa-dashboard"></i> Siblings</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<?php $control = 1; ?>
<div class="col-md-12">
    <div class="col-md-8">
        <div class="box">
            <div class="box-header">
                <div class="box-title">List of Students with Sibling Discount</div>
            </div>
            <div class="box-body">
                <table class="table table-condensed">
                    <tr>
                        <td align="right"><strong>#</strong></td>
                        <th>ID Number</th>
                        <th>Name</th>
                        <th>Level</th>
                        <th>Status</th>
                        <th>SDF Discount</th>
                        <td align="right"><strong>Remove Student</strong></td>
                    </tr>
                    @foreach($siblings as $sibling)
                    <?php $status = \App\Status::where('idno', $sibling->idno)->first(); ?>
                    <tr>
                        <td align="right">{{$control++}}.</td>
                        <td>{{$sibling->idno}}</td>
                        <td>{{$sibling->lastname}}, {{$sibling->firstname}} {{$sibling->middlename}}</td>
                        <td>{{$status->level}}</td>
                        <td>
                            @if($status->status == 3)Enrolled
                            @elseif($status->status == 2) Assessed
                            @elseif($status->status == 10) Pre-Registered
                            @elseif($status->status == 11) For Approval
                            @elseif($status->status == 4) Withdrawn-{{$status->date_dropped}}
                            @else Not Yet Enrolled @endif
                        </td>
                        <td>
                            @if($sibling->discount_type == "Benefit Discount")
                            100%
                            @else
                            50%
                            @endif
                        </td>
                        <td align="right"><a href="{{url("/remove_sibling", array($sibling->idno))}}">Remove Student</a></td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="box">
            <div class="box-header">
                <div class="box-title">Add Student</div>
            </div>
            <div class="box-body">
                <input type="text" id="search" class="form-control" placeholder="Search...">

                <div id="displaystudent">
                </div>    
            </div>
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
                    url: "/bedregistrar/ajax/getstudentlist_siblings",
                    data: array,
                    success: function (data) {
                        $("#displaystudent").html(data)
                        $("#search").val("");
                    }
                })
            }
        });
    });
</script> 
@endsection
