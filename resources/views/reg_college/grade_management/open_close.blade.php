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
        Open/Close Grading Module
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="/"> Grade Management</a></li>
        <li class="active"><a href="{{url('registrar_college', array('grade_management','open_close'))}}">Open/Close</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<div class='col-sm-12'>
    <div class='box'>
        <div class='box-header'>
            <div class='box-title'>Update All Instructor</div>
        </div>
        <div class='box-body'>
            <form class='form-horizontal' action='{{url('registrar_college', array('grade_management','open_close', 'submit'))}}' method='post'>
                {{ csrf_field() }}
                <div class='form-group'>
                    <div class='col-sm-4'>
                        <label>Midterm</label>
                        <select name='midterm' class='form form-control'>
                            <option value='0' @if ($status->midterm == 0) selected='' @endif>Open</option>
                            <option value='1' @if ($status->midterm == 1) selected='' @endif>Close</option>
                        </select>
                    </div>
                    <div class='col-sm-4'>
                        <label>Finals</label>
                        <select name='finals' class='form form-control'>
                            <option value='0' @if ($status->finals == 0) selected='' @endif>Open</option>
                            <option value='1' @if ($status->finals == 1) selected='' @endif>Close</option>
                        </select>
                    </div>
                    <div class='col-sm-4'>
                        <label>&nbsp;</label>
                        <input type='submit' value='Save' class='btn btn-success col-sm-12' onclick="if (confirm('Do you really want to continue?'))
                                    return true;
                                else
                                    return false;">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class='box'>
        <div class='box-header'>
            <div class='box-title'>Update Individually</div>
        </div>
        <div class='box-body'>
            <?php
            $instructors = \App\User::where('accesslevel', 1)->orderBy('lastname', 'ASC')->get();
            ?>
            <table id="example1" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID Number</th>
                        <th>Name</th>
                        <th>Midterm</th>
                        <th>Finals</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($instructors as $instructor)      
                    <?php
                    $addparent = \App\CtrCollegeGrading::where('idno', $instructor->idno)->first();
                        if (count($addparent) == 0) {
                            $addpar = new \App\CtrCollegeGrading;
                            $addpar->idno = $instructor->idno;
                            $addpar->academic_type = "College";
                            $addpar->save();
                        }
                    $close = \App\CtrCollegeGrading::where('idno', $instructor->idno)->first();
                    ?>
                    <tr>
                        <td>{{$instructor->idno}}</td>
                        <td>{{$instructor->lastname}}, {{$instructor->firstname}} {{$instructor->extensionname}}</td>
                        <td>
                            <select name='midterm' class='form form-control' onchange="updatemidtermclose('{{$instructor->idno}}', this.value)">
                                <option value='0' @if ($close->midterm == 0) selected='' @endif>Open</option>
                                <option value='1' @if ($close->midterm == 1) selected='' @endif>Close</option>
                            </select>
                        </td>
                        <td>
                            <select name='finals' class='form form-control' onchange="updatefinalclose('{{$instructor->idno}}', this.value)">
                                <option value='0' @if ($close->finals == 0) selected='' @endif>Open</option>
                                <option value='1' @if ($close->finals == 1) selected='' @endif>Close</option>
                            </select>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('footerscript')
<!-- DataTables -->
<script src="{{url('bower_components/datatables.net/js/jquery.dataTables.min.js')}}"></script>
<script src="{{url('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
<script>
                            $(function () {
                                $('#example1').DataTable()
                                $('#example2').DataTable({
                                    'paging': true,
                                    'lengthChange': false,
                                    'searching': false,
                                    'ordering': true,
                                    'info': true,
                                    'autoWidth': false
                                })
                            })
                            

function updatemidtermclose(idno, close){
    array = {};
    array['idno'] = idno;
    array['close'] = close;
    $.ajax({
        type: "GET",
        url: "/ajax/registrar_college/grade_management/update_open_close/midterm",
        data: array,
        success: function (data) {
        }

    });
}
function updatefinalsclose(idno, close){
    array = {};
    array['idno'] = idno;
    array['close'] = close;
    $.ajax({
        type: "GET",
        url: "/ajax/registrar_college/grade_management/update_open_close/finals",
        data: array,
        success: function (data) {
        }

    });
}
</script>
@endsection