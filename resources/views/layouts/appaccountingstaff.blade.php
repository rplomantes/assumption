<?php
$file_exist = 0;
if (file_exists(public_path("images/" . Auth::user()->idno . ".jpg"))) {
    $file_exist = 1;
}
?>

<!DOCTYPE html>
<html>
    <head class="no-print">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Assumption College - Accounting</title>
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <link rel="stylesheet" href="{{ asset ('bower_components/bootstrap/dist/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{ asset ('bower_components/font-awesome/css/font-awesome.min.css')}}">
        <link rel="stylesheet" href="{{ asset ('bower_components/Ionicons/css/ionicons.min.css')}}">
        <link rel="stylesheet" href="{{ asset ('dist/css/AdminLTE.min.css')}}">
        <link rel="stylesheet" href="{{ asset ('dist/css/skins/skin-blue.min.css')}}">
        <link rel="stylesheet" href="{{ asset ('plugins/pace/pace.min.css')}}">
        <link rel="stylesheet" href="{{ asset ('bower_components/select2/dist/css/select2.min.css')}}">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <link rel="stylesheet" href="{{asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css')}}">
    </head>
    <body class="hold-transition skin-blue sidebar-mini">
        <div class="wrapper">
            <header class="main-header">
                <a href="{{url('/')}}" class="logo">
                    <span class="logo-mini"><b>A</b>CS</span>
                    <span class="logo-lg"><b>Accounting</b>STAFF</span>
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
                                            <small>Accounting Staff</small>
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
            <aside class="main-sidebar no-print">

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
                        <li><a href="{{url('/accounting','set_other_payment')}}"><i class="fa fa-money"></i> <span>Set Other Payment</span></a></li>
<!--                        <li class="treeview">
                            <a href="#"><i class="fa fa-file-archive-o"></i> <span>Official Receipt</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{url('/accounting','search_or')}}"><span> Search OR</span></a></li>
                                <li><a href="{{url('/accounting','set_or')}}"><span> Set OR Number</span></a></li>
                            </ul>
                        </li>-->
                        <li><a href="{{url('/')}}"><i class="fa fa-columns"></i> <span>Disbursement</span></a></li>
                        <li><a href="{{url('/')}}"><i class="fa fa-pencil"></i> <span>Journal Entry</span></a></li>
                        <li class="treeview">
                            <a href="#"><i class="fa fa-book"></i> <span>Book of Accounts</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <?php $date_start = date('Y-m-d');
                            $date_end = date('Y-m-d'); ?>
                            <ul class="treeview-menu">
                                <li><a href="{{url('/accounting', array('cash_receipt',$date_start, $date_end))}}"><span> Cash Receipt</span></a></li>
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#"><i class="fa fa-align-justify"></i> <span>Debit/Credit Summary</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                            </ul>
                        </li>

                        <li class="treeview">
                            <a href="#"><i class="fa fa-file-excel-o"></i> <span>Collection Report</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{url('cashier',array('collection_report',date('Y-m-d'),date('Y-m-d')))}}">Collection Report Summary</a></li>
                                <li><a href="{{url('cashier',array('list_of_checks',date('Y-m-d'),date('Y-m-d')))}}">Check </a></li>
                                <li><a href="{{url('cashier',array('credit_cards',date('Y-m-d'),date('Y-m-d')))}}">Credit Card </a></li>
                                <li><a href="{{url('cashier',array('bank_deposits',date('Y-m-d'),date('Y-m-d')))}}">Bank Deposit </a></li>
                            </ul>
                        </li>

                        <li class="treeview">
                            <a href="#"><i class="fa fa-bookmark"></i> <span>Statement of Account</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{url('accounting',array('statement_of_account','bed'))}}">BED</a></li>
                                <li><a href="{{url('accounting')}}">HED</a></li>
                            </ul>
                        </li>
                        <li class="treeview">
                            <a href="#"><i class="fa fa-list-alt"></i> <span>Schedule Of Fees</span>
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">

                                <li><a href="{{url('accounting',array('schedule_of_fees',))}}"><span>Per Level</span></a></li>
                                <li><a href="{{url('accounting',array('schedule_of_plan',))}}"><span>Per Plan</span></a></li> 
                                <li><a href="{{url('accounting',array('schedule_of_fees_college'))}}"><i class="fa fa-list-alt"></i> <span>Update Fees</span></a></li>
                            </ul>
                        </li>  
                        <li><a href="{{url('accounting',array('post_charges'))}}"><i class="fa fa-check-square"></i> <span>Post Charges</span></a></li>
                        <?php
                        $school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first()->school_year;
                        $period = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first()->period;
                        $bed_school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'BED')->first();
                        ?>          
                        <li class="treeview">
                            <a href="#"><i class="fa fa-bar-chart"></i> <span>Reports</span>   
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{url('/accounting',array('student_list'))}}"><span>Student List</span></a></li>
                                <li><a href="{{url('/accounting', array('set_up_summary'))}}"><span>Set Up Summary</span></a></li>
                                <li><a href="{{url('/accounting', array('set_up_list'))}}"><span>Set Up List</span></a></li>
                                <li><a href="{{url('/accounting',array('outstanding_balances'))}}"><span>Outstanding Balances</span></a></li>
                                <li><a href="{{url('/accounting',array('student_per_account'))}}"><span>Student per Account List</span></a></li>
                            </ul>
                        </li>       
                        <li class="treeview">
                            <a href="#"><i class="fa fa-bar-chart"></i> <span>Other Reports</span>   
                                <span class="pull-right-container">
                                    <i class="fa fa-angle-left pull-right"></i>
                                </span>
                            </a>
                            <ul class="treeview-menu">
                                <li><a href="{{url('/registrar_college', array('reports', 'enrollment_statistics', $school_year, $period))}}"><span>HED Statistics</span></a></li>
                                <li><a href="{{url('/bedregistrar',array('enrollment_statistics',$bed_school_year->school_year))}}"><span>BED Statistics</span></a></li>
                                <li><a href="{{url('/accounting',array('unused_reservations'))}}"><span>Unused Reservations</span></a></li>
                                <li><a href="{{url('/accounting',array('examination_permit_hed'))}}"><span>Examination Permit - HED</span></a></li>
                            </ul>
                        </li>
                    </ul>
                </section>
            </aside>
            <div class="content-wrapper">
                @yield('header')
                <section class="content container-fluid">
                    @yield('maincontent')
                </section>
            </div>
            <footer class="main-footer no-print">
                <div class="pull-right hidden-xs">
                    In partnership with <a href="http://nephilaweb.com.ph">Nephila Web Technology, Inc.</a>
                </div>
                <strong>Copyright &copy; 2018 <a href="http://assumption.edu.ph">Assumption College - San Lorenzo</a>.</strong> All rights reserved.
            </footer>

            <aside class="control-sidebar control-sidebar-dark no-print">
                <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
                    <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
                    <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
                </ul>
                <div class="tab-content no-print">
                </div>
            </aside>
            <div class="control-sidebar-bg no-print"></div>
        </div>
        <script src="{{ asset ('bower_components/jquery/dist/jquery.min.js')}}"></script>
        <script src="{{ asset ('bower_components/bootstrap/dist/js/bootstrap.min.js')}}"></script>
        <script src="{{ asset ('dist/js/adminlte.min.js')}}"></script>
        <script src="{{ asset ('bower_components/PACE/pace.min.js')}}"></script>
        <script>
           $(document).ajaxStart(function () {
               Pace.restart()
           })
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

