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
        View Information
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li class="active"><a href="/"><i class="fa fa-home"></i> Dashboard</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<div class="box">
    <div class="box-header">
        <!--<div class="box-title">Search</div>-->
    </div>
    <div class="box-body form-horizontal">
        <form id="myForm" method="post" target='_blank' action="">
            {{ csrf_field() }}
            <div class="form-group">
                <div class="col-sm-3">
                    <label>Select Department</label>
                    <select class="form form-control" name="department" id="department">
                        <option>Select Department</option>
                        <option>Pre School</option>
                        <option>Elementary</option>
                        <option>Junior High School</option>
                        <option>Senior High School</option>
                    </select>
                </div>
                <div class="col-sm-3">
                    <br>
                    <a href='javascript:void(0)' class='btn btn-primary col-sm-12' onclick='generate_report(department.value)'>Generate Report</button></a>
                </div>
            </div>
        </form>
    </div>
    <div class='box-body'>

        <div class='col-sm-12' id='display_result'></div>
    </div>
    
</div>
@endsection
@section('footerscript')
<script>

    function generate_report(department) {
        var array = {};
        array['department'] = department;
        $.ajax({
            type: "GET",
            url: "/admin/ajax/getreassess_list",
            data: array,
            success: function (data) {
                $("#display_result").html(data)
            }
        });
    }
</script>
@endsection