@extends('layouts.appreg_college')
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
        View Curriculum
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="#"></i> Curriculum Management</a></li>
        <li class="active"><a href="{{ url ('/registrar_college', array('curriculum_management','curriculum'))}}"></i> Curriculum</a></li>
    </ol>
</section>
@endsection
@section('maincontent')

<?php
$program = \App\Curriculum::distinct()->where('program_code', $program_code)->get(['program_name'])->first();
$levels = \App\Curriculum::distinct()->where('program_code', $program_code)->where('curriculum_year', $curriculum_year)->orderBy('level', 'asc')->orderBy('period', 'asc')->get(['level', 'period']);
?>

<section class="content">
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">{{$program->program_name}} - {{$curriculum_year}}</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-remove"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <?php $totalUnits = 0; ?>
                    @foreach ($levels as $level)
                    <table class="table table-condensed">
                        <thead>
                        <th>{{$level->period}}</th>
                        <th>{{$level->level}}</th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        </thead>
                        <tbody>
                            <tr>
                                <th class='col-sm-2'>Subject Code</th>
                                <th class='col-sm-7'>Subject Description</th>
                                <th class='col-sm-1'>LEC</th>
                                <th class='col-sm-1'>LAB</th>
                                <th class='col-sm-1'>UNITS</th>
                            </tr>
                            <?php
                            $curriculums = \App\Curriculum::where('program_code', $program_code)->where('curriculum_year', $curriculum_year)->where('level', $level->level)->where('period', $level->period)->get();
                            ?>
                            <?php
                            $totalLec = 0;
                            $totalLab = 0;
                            ?>
                            @foreach ($curriculums as $curriculum)
                            <tr>
                                <td>{{$curriculum->course_code}}</td>
                                <td>{{$curriculum->course_name}}</td>
                                <td>@if ($curriculum->lec==0) @else {{$curriculum->lec}} @endif <?php $totalLec = $curriculum->lec + $totalLec; ?></td>
                                <td>@if ($curriculum->lab==0) @else {{$curriculum->lab}} @endif <?php $totalLab = $curriculum->lab + $totalLab; ?></td>
                                <td>{!!$curriculum->lec + $curriculum->lab!!}</td>
                            </tr>
                            @endforeach
                            <tr>
                                <td></td>
                                <th><div align='right'>Total</div> </th>
                                <th><?php echo $totalLec; ?></th>
                                <th><?php echo $totalLab; ?></th>
                                <th><?php $totalUnits = $totalUnits + $totalLec + $totalLab; ?> {!! $totalLec + $totalLab !!}</th>
                            </tr>
                        </tbody>
                    </table>
                    @endforeach
                    <table class="table table-condensed">
                        <tr>
                            <th>Total Units: {!! $totalUnits !!}</th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('footerscript')

@endsection