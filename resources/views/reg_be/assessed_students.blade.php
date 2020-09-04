<?php
if (Auth::user()->accesslevel == env('REG_BE')) {
    $layout = "layouts.appbedregistrar";
} else {
    $layout = "layouts.appadmission-bed";
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
        Student List
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Student List</li>
    </ol>
</section>
@endsection
@section('maincontent')
<!-- search form (Optional) -->
<div class="col-md-12">
    <div class="box">
        <div class="box-body">
            <table class="table table-condensed">
                <tr>
                    <td>#</td>
                    <td>ID Number</td>
                    <td>Name</td>
                    <td>Level</td>
                    <td>Strand</td>
                    <td>Date Assessed</td>
                </tr>
                <?php $no = 1; ?>
                @foreach($students as $student)
                <tr>
                    <td>{{$no++}}</td>
                    <td>{{$student->idno}}</td>
                    <td>{{$student->getFullNameAttribute()}}</td>
                    <td>{{$student->level}}</td>
                    <td>{{$student->strand}}</td>
                    <td>{{$student->date_registered}}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div> 
@endsection
@section('footerscript')
@endsection
