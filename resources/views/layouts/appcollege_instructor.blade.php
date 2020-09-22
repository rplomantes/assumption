<?php
$file_exist = 0;
if (file_exists(public_path("images/" . Auth::user()->idno . ".jpg"))) {
    $file_exist = 1;
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Assumption College - Instructor College</title>
        <link rel="shortcut icon" type="image/jpg" href="{{url('/images','assumption-logo.png')}}">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="{{url("/bower_components",array("bootstrap","dist","css","bootstrap.min.css"))}}">
        <link rel="stylesheet" href="{{url("/bower_components",array("font-awesome","css","font-awesome.min.css"))}}">
        <link rel="stylesheet" href="{{url("/bower_components",array("Ionicons","css","ionicons.min.css"))}}">
        <link rel="stylesheet" href="{{url("/dist",array("css","AdminLTE.min.css"))}}">
        <link rel="stylesheet" href="{{url("/dist",array("css","skins","skin-blue.min.css"))}}">
        <link rel="stylesheet" href="{{ asset ('plugins/pace/pace.min.css')}}">
        <link rel="stylesheet"href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <header class="main-header">
                <a href="{{url('/')}}" class="logo">
                    <span class="logo-mini"><b>A</b>CS</span>
                    <span class="logo-lg"><b>Instructor</b>COLLEGE</span>
                </a>
                <nav class="navbar navbar-static-top" role="navigation">
                    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                            
                            @yield('messagemenu')
                            
                            <li class="dropdown user user-menu">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    @if($file_exist==1)
                                    <img src="/images/{{Auth::user()->idno}}.jpg"  width="25" height="25" class="user-image" alt="User Image">
                                    @else
                                    <img class="user-image" width="25" height="25" alt="User Image" src="/images/default.png">
                                    @endif
                                    <span class="hidden-xs">{{Auth::user()->lastname}}, {{Auth::user()->firstname}}</span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="user-header">
                                        @if($file_exist==1)
                                        <img src="/images/{{Auth::user()->idno}}.jpg"  width="25" height="25" class="img-circle" alt="User Image">
                                        @else
                                        <img class="img-circle" width="25" height="25" alt="User Image" src="/images/default.png">
                                        @endif

                                        <p>
                                            {{Auth::user()->lastname}}, {{Auth::user()->firstname}}
                                            <small>Instructor - College</small>
                                        </p>
                                    </li>

                                    <li class="user-footer">
                                        <div class="pull-left">
                                            <a href="#" class="btn btn-default btn-flat">Profile</a>
                                        </div>
                                        <div class="pull-right">

                                            <a href="{{ route('logout') }}" class="btn btn-default btn-flat"
                                               onclick="event.preventDefault();
                                                       document.getElementById('logout-form').submit();">
                                                <span><i class="fa fa-sign-out"></i> Logout</span>
                                            </a>
                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                {{ csrf_field() }}
                                            </form>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </header>
            <aside class="main-sidebar">
                <section class="sidebar">
                    <div class="user-panel">
                        <div class="pull-left image">
                            @if($file_exist==1)
                            <img src="/images/{{Auth::user()->idno}}.jpg"  width="25" height="25" class="img-circle" alt="User Image">
                            @else
                            <img class="img-circle" width="25" height="25" alt="User Image" src="/images/default.png">
                            @endif
                        </div>
                        <div class="pull-left info">
                            <p>{{Auth::user()->lastname}}, {{Auth::user()->firstname}}</p>
                            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                        </div>
                    </div>
                    <ul class="sidebar-menu" data-widget="tree">
                        <li class="header">MENU</li>
                        <li><a href="{{url('/')}}"><i class="fa fa-home"></i> <span>Home</span></a></li>
                        <li class="treeview">
                            <a href="#"><i class="fa fa-list-ul"></i> <span>Course Loading</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <?php
                                $school_year = \App\CtrGradeSchoolYear::where('academic_type', 'College')->first();
                                $loads = \App\ScheduleCollege::distinct()->where('schedule_colleges.school_year', $school_year->school_year)->where('schedule_colleges.period', $school_year->period)->where('schedule_colleges.instructor_id',Auth::user()->idno)->join('course_offerings', 'course_offerings.schedule_id', '=', 'schedule_colleges.schedule_id')->get(['course_offerings.course_code']);
                                ?>
                                @foreach ($loads as $load)
                                <li class="treeview">
                                    <a href="#"><i class="fa fa-circle-o"></i> {{$load->course_code}}<span class="pull-right-container"><i class="fa fa-angle-left pull-right"></i></span></a>
                                    <ul class="treeview-menu">
                                        <?php $scheds = \App\ScheduleCollege::distinct()->where('instructor_id', Auth::user()->idno)->where('school_year', $school_year->school_year)->where('period', $school_year->period)->where('course_code', $load->course_code)->get(['schedule_id']); ?>
                                        @foreach ($scheds as $sched)
                                        
                                        <?php $is_tba = \App\ScheduleCollege::where('schedule_id', $sched->schedule_id)->first()->is_tba; ?>
                                        <li>
                                            <a href="{{url('college_instructor', array('grades', $sched->schedule_id))}}">
                                                <i class="fa fa-circle-o"></i> 
                                                @if($is_tba == 0)
                                                <span>
                                                    <?php
                                                    $schedule3s = \App\ScheduleCollege::distinct()->where('schedule_id', $sched->schedule_id)->get(['time_start', 'time_end', 'room']);
                                                    ?>   
                                                    @foreach ($schedule3s as $schedule3)
                                                    {{$schedule3->room}}
                                                    @endforeach
                                                    <?php
                                                    $schedule2s = \App\ScheduleCollege::distinct()->where('schedule_id', $sched->schedule_id)->get(['time_start', 'time_end', 'room']);
                                                    ?>
                                                    @foreach ($schedule2s as $schedule2)
                                                    <?php
                                                    $days = \App\ScheduleCollege::where('schedule_id', $sched->schedule_id)->where('time_start', $schedule2->time_start)->where('time_end', $schedule2->time_end)->where('room', $schedule2->room)->get(['day']);
                                                    ?>
                                                    <!--                @foreach ($days as $day){{$day->day}}@endforeach {{$schedule2->time}} <br>-->
                                                    [@foreach ($days as $day){{$day->day}}@endforeach {{date('g:iA', strtotime($schedule2->time_start))}}-{{date('g:iA', strtotime($schedule2->time_end))}}]<br>
                                                    @endforeach
                                                </span>
                                                @else
                                                <span>TBA</span>
                                                @endif
                                            </a>
                                        </li>
                                        @endforeach
                                    </ul>
                                </li>
                                @endforeach
                            </ul>
                        </li>
                        <li><a href="{{url('/college_instructor/my_schedule')}}"><i class="fa fa-calendar"></i> <span>My Schedule</span></a></li>
                    </ul>
                </section>
            </aside>
            <div class="content-wrapper">
                @yield('header')
                <section class="content container-fluid">
                    @yield('maincontent')
                </section>
            </div>
            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                    In partnership with <a href="http://nephilaweb.com.ph">Nephila Web Technology, Inc.</a>
                </div>
                <strong>Copyright &copy; 2018 <a href="http://assumption.edu.ph">Assumption College - San Lorenzo</a>.</strong> All rights reserved.
            </footer>
            <aside class="control-sidebar control-sidebar-dark">
                <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
                    <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
                    <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="control-sidebar-home-tab">
                        <h3 class="control-sidebar-heading">Recent Activity</h3>
                        <ul class="control-sidebar-menu">
                            <li>
                                <a href="javascript:;">
                                    <i class="menu-icon fa fa-birthday-cake bg-red"></i>
                                    <div class="menu-info">
                                        <h4 class="control-sidebar-subheading">Langdon's Birthday</h4>
                                        <p>Will be 23 on April 24th</p>
                                    </div>
                                </a>
                            </li>
                        </ul>
                        <h3 class="control-sidebar-heading">Tasks Progress</h3>
                        <ul class="control-sidebar-menu">
                            <li>
                                <a href="javascript:;">
                                    <h4 class="control-sidebar-subheading">
                                        Custom Template Design
                                        <span class="pull-right-container">
                                            <span class="label label-danger pull-right">70%</span>
                                        </span>
                                    </h4>
                                    <div class="progress progress-xxs">
                                        <div class="progress-bar progress-bar-danger" style="width: 70%"></div>
                                    </div>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
                    <div class="tab-pane" id="control-sidebar-settings-tab">
                        <form method="post">
                            <h3 class="control-sidebar-heading">General Settings</h3>
                            <div class="form-group">
                                <label class="control-sidebar-subheading">
                                    Report panel usage
                                    <input type="checkbox" class="pull-right" checked>
                                </label>
                                <p>
                                    Some information about this general settings option
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </aside>
            <div class="control-sidebar-bg"></div>
        </div>
        <script src="{{url("/bower_components",array("jquery","dist","jquery.min.js"))}}"></script>
        <script src="{{url("/bower_components",array("bootstrap","dist","js","bootstrap.min.js"))}}"></script>
        <script src="{{url("/dist",array("js","adminlte.min.js"))}}"></script>
        <script src="{{ asset('bower_components/PACE/pace.min.js')}}"></script>
        <script>
            $(document).ajaxStart(function () {
                Pace.restart()
            });
        </script>
        <script src="{{asset('bower_components/select2/dist/js/select2.full.min.js')}}"></script>
        <script>
           $(function () {
               $('.select2').select2();
           });
        </script>
        <script src="{{asset('bower_components/jquery-ui/jquery-ui.min.js')}}"></script>
        @yield('footerscript')
    </body>
</html>
