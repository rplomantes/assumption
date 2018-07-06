<?php
$file_exist=0;
if(file_exists(public_path("images/".Auth::user()->idno.".jpg"))){
    $file_exist=1;
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Assumption College - Accounting</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="{{url('/bower_components',array('bootstrap','dist','css','bootstrap.min.css'))}}">
  <link rel="stylesheet" href="{{url("/bower_components",array("font-awesome","css","font-awesome.min.css"))}}">
  <link rel="stylesheet" href="{{url("/bower_components",array("Ionicons","css","ionicons.min.css"))}}">
  <link rel="stylesheet" href="{{url("dist",array("css","AdminLTE.min.css"))}}">
  <link rel="stylesheet" href="{{url("dist",array("css","skins","skin-blue.min.css"))}}">
  <link rel="stylesheet" href="{{url("/bower_components", array("datatables.net-bs","css","dataTables.bootstrap.min.css"))}}">
  <link rel="stylesheet" href="{{url('/dist',array('css','skins','_all-skins.min.css'))}}">
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
  <header class="main-header">
    <a href="{{url('/')}}" class="logo">
      <span class="logo-mini"><b>A</b>CS</span>
      <span class="logo-lg"><b>Accounting</b>AC</span>
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
                                            <small>Accounting</small>
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
          <!-- Control Sidebar Toggle Button -->
          <li>
            <a href="javascript:void(0)" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
          </li>
        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
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
          <!-- Status -->
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>

     

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MENU</li>
        <!-- Optionally, you can add icons to the links -->
        <li><a href="{{url('/')}}"><i class="fa fa-home"></i> <span>Home</span></a></li>
        <li><a href="{{url('/accounting','set_other_payment')}}"><i class="fa fa-money"></i> <span>Set Other Payment</span></a></li>
        <li class="treeview">
          <a href="#"><i class="fa fa-columns"></i> <span>Official Receipt</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
        <li><a href="{{url('/accounting','search_or')}}"><span> Search OR</span></a></li>
        <li><a href="{{url('/accounting','set_or')}}"><span> Set OR Number</span></a></li>
          </ul>
        </li>
        <li><a href="{{url('/')}}"><i class="fa fa-link"></i> <span>Disbursement</span></a></li>
        <li><a href="{{url('/')}}"><i class="fa fa-link"></i> <span>Journal Entry</span></a></li>
        <li class="treeview">
          <a href="#"><i class="fa fa-link"></i> <span>Book of Accounts</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{url('/accounting',array('cash_receipt',date('Y-m-d'),date('Y-m-d')))}}">Cash Receipts </a></li>
            <li><a href="{{url('/accounting',array('cash_disbursement',date('Y-m-d'),date('Y-m-d')))}}">Cash Disbursement </a></li>
            <li><a href="{{url('/accounting',array('general_journal',date('Y-m-d'),date('Y-m-d')))}}">General Journal </a></li>
            <li><a href="{{url('/accounting',array('debit_memo',date('Y-m-d'),date('Y-m-d')))}}">Debit Memo Journal </a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-link"></i> <span>Debit/Credit Summary</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{url('/accounting',array('cash_receipt_drcr',date('Y-m-d'),date('Y-m-d')))}}">Cash Receipts </a></li>
            <li><a href="{{url('/accounting',array('disburesement_drcr',date('Y-m-d'),date('Y-m-d')))}}">Cash Disbursement </a></li>
            <li><a href="{{url('/accounting',array('general_journal_drcr',date('Y-m-d'),date('Y-m-d')))}}">General Journal </a></li>
            <li><a href="{{url('/accounting',array('debit_memo_journal_drcr',date('Y-m-d'),date('Y-m-d')))}}">Debit Memo Journal </a></li>
          </ul>
        </li>
        
        <li class="treeview">
          <a href="#"><i class="fa fa-link"></i> <span>Collection Report</span>
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
          <a href="#"><i class="fa fa-link"></i> <span>Schedule Of Fees</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
          </a>
          <ul class="treeview-menu">
              
        <li><a href="{{url('accounting',array('schedule_of_fees',))}}"><i class="fa fa-link"></i> <span>Per Level</span></a></li>
        <li><a href="{{url('accounting',array('schedule_of_plan',))}}"><i class="fa fa-link"></i> <span>Per Plan</span></a></li> 
       </ul>
          </li>         
        <?php 
            $school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first()->school_year;
            $period = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first()->period;
            $bed_school_year = \App\CtrEnrollmentSchoolYear::where('academic_type','BED')->first();
        ?>          
        <li class="treeview">
            <a href="#"><i class="fa fa-bar-chart"></i> <span>Enrollment Statistics</span>   
                <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
        <ul class="treeview-menu">
            <li><a href="{{url('/registrar_college', array('reports', 'enrollment_statistics', $school_year, $period))}}"><i class="fa fa-circle-o"></i> <span>HED Statistics</span></a></li>
            <li><a href="{{url('/bedregistrar',array('enrollment_statistics',$bed_school_year->school_year))}}"><i class="fa fa-link"></i> <span>BED Statistics</span></a></li>                
        </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    @yield('header')

    <!-- Main content -->
    <section class="content container-fluid">

      <!--------------------------
        | Your Page Content Here |
        -------------------------->
        @yield('maincontent')
        
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
      In partnership with <a href="http://nephilaweb.com.ph">Nephila Web Technology, Inc.</a>
    </div>
    <!-- Default to the left -->
    <strong>Copyright &copy; 2018 <a href="http://assumption.edu.ph">Assumption College - San Lorenzo</a>.</strong> All rights reserved.
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Create the tabs -->
    <ul class="nav nav-tabs nav-justified control-sidebar-tabs">
      <li class="active"><a href="#control-sidebar-home-tab" data-toggle="tab"><i class="fa fa-home"></i></a></li>
      <li><a href="#control-sidebar-settings-tab" data-toggle="tab"><i class="fa fa-gears"></i></a></li>
    </ul>
    <!-- Tab panes -->
    <div class="tab-content">
      <!-- Home tab content -->
      <div class="tab-pane active" id="control-sidebar-home-tab">
        <h3 class="control-sidebar-heading">Recent Activity</h3>
      </div>
      
      <div class="tab-pane" id="control-sidebar-stats-tab">Stats Tab Content</div>
      <!-- /.tab-pane -->
      <!-- Settings tab content -->
      <div class="tab-pane" id="control-sidebar-settings-tab">
        
      </div>
      <!-- /.tab-pane -->
    </div>
  </aside>
  <!-- /.control-sidebar -->
  <!-- Add the sidebar's background. This div must be placed
  immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

<!-- REQUIRED JS SCRIPTS -->

<!-- jQuery 3 -->
<script src="{{url("/bower_components",array("jquery","dist","jquery.min.js"))}}"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{url("/bower_components",array("bootstrap","dist","js","bootstrap.min.js"))}}"></script>
<!-- AdminLTE App -->
<script src="{{url("/dist",array("js","adminlte.min.js"))}}"></script>
@yield('footerscript')

</body>
</html>

