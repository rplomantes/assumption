@extends('layouts.appcollege_instructor')
@section('messagemenu')
<li class="dropdown messages-menu no-print">
    <!-- Menu toggle button -->
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-envelope-o"></i>
        <span class="label label-success"></span>
    </a>
</li>
<li class="dropdown notifications-menu no-print">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-bell-o"></i>
        <span class="label label-warning"></span>
    </a>
</li>

<li class="dropdown tasks-menu no-print">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-flag-o"></i>
        <span class="label label-danger"></span>
    </a>
</li>
@endsection
@section('header')
<section class="content-header no-print">
    <h1>
        My Schedule
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li class="active"><a href="{{ url ('college_instructor','my_schedule')}}"> My Schedule</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<div class="box">
    <div class="box-body no-print">
        <div class="col-md-2">
            <div class="form-group">
                <label>School Year</label>
                <select id="school_year" class="form-control" style="width: 100%;" onchange="select_room()">
                    <option>Select school year</option>
                    <option value="2017">2017-2018</option>
                    <option value="2018">2018-2019</option>
                    <option value="2019">2019-2020</option>
                    <option value="2020">2020-2021</option>
                    <option value="2021">2021-2022</option>
                </select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label>Period</label>
                <select id="period" class="form-control" style="width: 100%;">
                    <option>Select period</option>
                    <option value="1st Semester">1st Semester</option>
                    <option value="2nd Semester">2nd Semester</option>
                    <option value="Summer">Summer</option>
                </select>
            </div>
        </div>
        <div class="col-md-2" id="room-form">
            <div class="form-group">
                <label>&nbsp;</label>
                <button class="col-sm-12 btn btn-primary" onclick="generateSchedule()">Generate Schedule</button>
            </div>
        </div>
        <div class="col-md-2" id="room-form">
            <div class="form-group">
                <label>&nbsp;</label>
                <button class="col-sm-12 btn btn-warning" onclick="print_now()">Print Now</button>
            </div>
        </div>
    </div>
    <div class="box-body" id="generateSchedule">

    </div>
</div>
@endsection

@section('footerscript')
<script>

    function generateSchedule() {
        array = {};
        array['school_year'] = $("#school_year").val();
        array['period'] = $("#period").val();
        $.ajax({
            type: "GET",
            url: "/ajax/college_instructor/generateSchedule/",
            data: array,
            success: function (data) {
                $('#generateSchedule').html(data);
            }

        });
    }

    function print_now() {
        window.open("/college_instructor/print_my_schedule/" + $("#school_year").val() + "/" + $("#period").val())
    }
</script>
@endsection
