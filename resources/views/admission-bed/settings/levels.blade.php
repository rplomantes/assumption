<?php
if(Auth::user()->accesslevel == env('ADMISSION_BED')){
$layout = "layouts.appadmission-bed";
} else {
$layout = "layouts.appadmission-shs";
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
        Approved Applicants
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Pre Registration Settings</li>
    </ol>
</section>
@endsection
@section('maincontent')
<div class="col-md-6">  
    <div class="box">
        <div class="box-header">
            <div class="box-title">Open/Close Levels</div>
        </div>
        <div class="box-body">
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th>Levels</th>
                        <th>Open/Close</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($levels as $level)
                    <tr>
                        <td>{{$level->level}}</td>
                        <td>@if($level->is_on == 1) Open @else <b style='color: red'>Close</b> @endif</td>
                        <td><a href='{{url('/bedadmission',array('settings','update_levels',$level->level))}}'>Change Status</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div> 
</div>
@endsection
