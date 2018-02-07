@extends('layouts.appreg_college')
@section('messagemenu')
<li class="dropdown messages-menu">
    <!-- Menu toggle button -->
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-envelope-o"></i>
        <span class="label label-success"></span>
    </a>
</li>
<li class="dropdown notifications-menu">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-bell-o"></i>
        <span class="label label-warning"></span>
    </a>
</li>

<li class="dropdown tasks-menu">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-flag-o"></i>
        <span class="label label-danger"></span>
    </a>
</li>
@endsection
@section('header')
<section class="content-header">
    <h1>
        Enrollment Statistics
        <small>{{$school_year}}-{{$school_year+1}} - {{$period}}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="#"> Reports</a></li>
        <li class="active"><a href="{{ url ('/registrar_college', array('reports','enrollment_statistics'))}}"> Enrollment Statistics</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<section class="content">
    <div class="row">
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Program</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style='width: 80px'>Level</th>
                            <th>Program</th>
                            <th>Advised</th>
                            <th>Assessed</th>
                            <th>Enrolled</th>
                        </tr>
                        @foreach ($academic_programs as $academic_program)
                        <?php
                        $advised = \App\Status::where('school_year', $school_year)->where('period', $period)->where('status', 1)->where('program_code', $academic_program->program_code)->where('level', $academic_program->level)->get();
                        $enrollees = \App\Status::where('school_year', $school_year)->where('period', $period)->where('status', 3)->where('program_code', $academic_program->program_code)->where('level', $academic_program->level)->get();
                        $assessed  = \App\Status::where('school_year', $school_year)->where('period', $period)->where('status', 2)->where('program_code', $academic_program->program_code)->where('level', $academic_program->level)->get();
                        ?>
                        <tr>
                            <td>{{$academic_program->level}}</td>
                            <td>{{$academic_program->program_code}}</td>
                            <td>{{count($advised)}}</td>
                            <td>{{count($assessed)}}</td>
                            <td>{{count($enrollees)}}</td>
                        </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Department</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered">
                        <tr>
                            <th>Department</th>
                            <th>Advised</th>
                            <th>Assessed</th>
                            <th>Enrolled</th>
                        </tr>
                        <?php
                        $totaladvised = 0;
                        $totalassessed = 0;
                        $totalenrollees = 0;
                        ?>
                        @foreach ($departments as $department)
                        <?php
                        $advised = \App\Status::where('school_year', $school_year)->where('period', $period)->where('status', 1)->where('department', $department->department)->get();
                        $enrollees = \App\Status::where('school_year', $school_year)->where('period', $period)->where('status', 3)->where('department', $department->department)->get();
                        $assessed  = \App\Status::where('school_year', $school_year)->where('period', $period)->where('status', 2)->where('department', $department->department)->get();
                        ?>
                        <tr>
                            <td>{{$department->department}}</td>
                            <td>{{count($advised)}}</td>
                            <td>{{count($assessed)}}</td>
                            <td>{{count($enrollees)}}</td>
                        </tr>
                        <?php
                        $totaladvised = $totaladvised + count($advised);
                        $totalassessed = $totalassessed + count($assessed);
                        $totalenrollees = $totalenrollees + count($enrollees);
                        ?>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title">Total</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered">
                        <tr>
                            <th style="color:green">Advised</th>
                            <th style="color:green">{{$totaladvised}}</th>
                        </tr>
                        <tr>
                            <th style="color:blue">Assessed</th>
                            <th style="color:blue">{{$totalassessed}}</th>
                        </tr>
                        <tr>
                            <th style="color:red">Enrollees</th>
                            <th style="color:red">{{$totalenrollees}}</th>
                        </tr>
                        <tr>
                            <th style="color: mediumvioletred">Total</th>
                            <th style="color: mediumvioletred">{{$totaladvised + $totalenrollees + $totalassessed}}</th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@section('footerscript')
<script type="text/javascript">
    $(document).ready(function () {
        $("#search").keypress(function (e) {
            var theEvent = e || window.event;
            var key = theEvent.keyCode || theEvent.which;
            var array = {};
            array['search'] = $("#search").val();
            if (key == 13) {
                $.ajax({
                    type: "GET",
                    url: "/ajax/registrar_college/getstudentlist",
                    data: array,
                    success: function (data) {
                        $("#studentlist").html(data);
                        $("#search").val("");
                    }
                });
            }
        })
    })
</script>
@endsection