<?php
$instructors = \App\User::where('accesslevel', 1)->orderBy('lastname', 'ASC')->get();
?>
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
        Instructors
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="#"> Instructor</a></li>
        <li class="active"><a href="{{ url ('/registrar_college', array('instructor','view_instructor'))}}"> View Instructor</a></li>
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
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th>ID Number</th>
                  <th>Name</th>
                  <th>Modify</th>
                </tr>
                </thead>
                <tbody>
            @foreach ($instructors as $instructor)
                <tr>
                  <td>{{$instructor->idno}}</td>
                  <td>{{$instructor->lastname}}, {{$instructor->firstname}} {{$instructor->extensionname}}</td>
                  <td><a href="{{url('registrar_college', array('instructor', 'modify_instructor', $instructor->idno))}}"><button class="btn btn-success"><span class="fa fa-pencil"></button></a></td>
                </tr>
            @endforeach
                </tbody>
                <tfoot>
                <tr>
                  <th>ID Number</th>
                  <th>Name</th>
                  <th>Modify</th>
                </tr>
                </tfoot>
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
    $('#example1').DataTable()
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : false,
      'ordering'    : true,
      'info'        : true,
      'autoWidth'   : false
    })
  })
</script>
@endsection