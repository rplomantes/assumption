@extends('layouts.admin')
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
        Dashboard
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li class="active"><a href="/"><i class="fa fa-home"></i> Dashboard</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
            <div class="box-header">
              <h3 class="box-title">Instructors List</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
    <table id="datatable" class="table table-condensed">
        <thead>
            <tr>
                <th>User</th>
                <th>Action</th>
                <th>Date/Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($logs as $log)
            <?php $user = \App\User::where('idno', $log->idno)->first(); ?>
            <tr>
                <td>{{$user->lastname}}, {{$user->firstname}}</td>
                <td>{{$log->action}}</td>
                <td>{{date('Y-m-d g:i A',strtotime($log->datetime))}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
            </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('footerscript')

<!-- DataTables -->
<script src="{{url('bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{url('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>

<script>
  $(function () {
      $('#datatable').DataTable({
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : false,
      'info'        : true,
      'autoWidth'   : true
    })
  })
</script>
@endsection