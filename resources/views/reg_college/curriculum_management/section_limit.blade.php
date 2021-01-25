<?php
$layout = "layouts.appreg_college";
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
        Section Limit
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Section Limit</li>
    </ol>
</section>
@endsection
@section('maincontent')
<div class="col-md-6">  
                @if (Session::has('message'))
                <div class="alert alert-success">{{ Session::get('message') }}</div>
                @endif  
    <div class="box"> 
        <div class="box-header">
            <div class="box-title">Update Section Limit</div>
        </div>
        <div class="box-body">
            <form action="{{url('registrar_college',array('curriculum_management','update_section_limit'))}}" method="post">
                {{csrf_field()}}
                <div class="form form-group">
                    <label>Room Limit</label>
                    <input type="number" min = 0 class="form form-control" value="{{$limit->limit}}" name="limit">
                </div>
                <div class="form form-group">
                    <input type="submit" value="Save" class="form form-control btn btn-success">
                </div>
            </form>
        </div>
    </div> 
</div>
@endsection
