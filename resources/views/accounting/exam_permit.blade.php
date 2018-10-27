<?php 
    if (Auth::user()->accesslevel==env("ACCTNG_STAFF")){
        $layout = "layouts.appaccountingstaff";    
    }else if (Auth::user()->accesslevel==env("ACCTNG_HEAD")){
        $layout = "layouts.appaccountinghead";    
    }
?>
@extends($layout)
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
        Examination Permit
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Exam Permit</li>
    </ol>
</section>
@endsection
@section('maincontent')
<div class="box">
    <div class="box-header">
    </div>
    <form action="{{url('accounting', array('examination_permit_hed','print_all'))}}" target="_blank" method="post">
        {{ csrf_field() }}
        <div class="box-body form-horizontal">
            <div class="form-group">

                <div class='col-sm-2'>
                    <label>School Year</label>
                    <select class="form form-control select2" name="school_year" id='school_year'>
                        <option value="">Select School Year</option>
                        <option value="2017">2017-2018</option>
                        <option value="2018">2018-2019</option>
                        <option value="2019">2019-2020</option>
                        <option value="2020">2020-2021</option>
                        <option value="2021">2021-2022</option>
                    </select>    
                </div>                               
                <div class='col-sm-2'>
                    <label>Period</label>
                    <select class="form form-control select2" name="period" id='period'>
                        <option value="">Select Period</option>
                        <option value="1st Semester">1st Semester</option>
                        <option value="2nd Semester">2nd Semester</option>
                        <option value="Summer">Summer</option>
                    </select>    
                </div>
                <div class='col-sm-2'>
                    <label>Level</label>
                    <select class="form form-control select2" name="level" id='level'>
                        <option value="">Select Level</option>
                        <option value="1st Year">1st Year</option>
                        <option value="2nd Year">2nd Year</option>
                        <option value="3rd Year">3rd Year</option>
                        <option value="4th Year">4th Year</option>
                    </select>    
                </div>
                <div class='col-sm-2'>
                    <label>Exam Period</label>
                    <select class="form form-control select2" id="exam_period" name="exam_period">
                        <option value="">Select Exam Period</option>
                        <option value="Midterm">Midterm</option>
                        <option value="Finals">Finals</option>
                    </select>    
                </div>
            </div>
            <div class='form-group'>
                <div class="col-sm-8">
                    <button type="button" class='btn btn-primary col-sm-12' onclick='generate_report(school_year.value, period.value, level.value, exam_period.value)'>Generate Report</button>
                </div>
            </div>
        </div>
        <div class='box-body'>
            <div class='col-sm-12' id='display_result'></div>
        </div>
    </form>
</div>
@endsection
@section('footerscript') 
<script>
    function generate_report(school_year, period, level, exam_period) {
        var array = {};
        array['level'] = level;
        array['school_year'] = school_year;
        array['period'] = period;
        array['exam_period'] = exam_period;
        $.ajax({
            type: "GET",
            url: "/accounting/ajax/getstudentpermit",
            data: array,
            success: function (data) {
                $("#display_result").html(data)
            }
        });
    }
</script>
@endsection
