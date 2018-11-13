<?php
    if(Auth::user()->accesslevel == env('ACCTNG_STAFF')){
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
        Student List
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="#"> Other Reports</a></li>
        <li class="active"><a href="{{ url ('/accounting', array('student_list'))}}"></i> Student List</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<section class="content">
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Search Parameters</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <div class='form-horizontal'>
                        <div class='form-group'>
                            <div class='col-sm-2'>
                                <label>School Year</label>
                                <select id='school_year' class='form-control select2'>
                                    <option value='all'>All</option>
                                    <option value="2017">2017-2018</option>
                                    <option value="2018">2018-2019</option>
                                    <option value="2019">2019-2020</option>
                                    <option value="2020">2020-2021</option>
                                    <option value="2021">2021-2022</option>
<!--                                    @foreach ($school_years as $school_year)
                                    <option value='{{$school_year->school_year}}'>{{$school_year->school_year}}-{{$school_year->school_year+1}}</option>
                                    @endforeach-->
                                </select>
                            </div>
                            <div class='col-sm-2'>
                                <label>Level</label>
                                <select id='level' class='form-control select2'>
                                    <option value='all'>All</option>
                                    <option value='Pre-Kinder'>Pre-Kinder</option>                                    
                                    <option value='Kinder'>Kinder</option>
                                    <option value='Grade 1'>Grade 1</option>
                                    <option value='Grade 2'>Grade 2</option>
                                    <option value='Grade 3'>Grade 3</option>
                                    <option value='Grade 4'>Grade 4</option>
                                    <option value='Grade 5'>Grade 5</option>
                                    <option value='Grade 6'>Grade 6</option>
                                    <option value='Grade 7'>Grade 7</option>
                                    <option value='Grade 8'>Grade 8</option>
                                    <option value='Grade 9'>Grade 9</option>
                                    <option value='Grade 10'>Grade 10</option>
                                    <option value='Grade 11'>Grade 11</option>
                                    <option value='Grade 12'>Grade 12</option>                                    
                                    <option value='1st Year'>1st Year</option>
                                    <option value='2nd Year'>2nd Year</option>
                                    <option value='3rd Year'>3rd Year</option>
                                    <option value='4th Year'>4th Year</option>
                                    <option value='5th Year'>5th Year</option>                                    
                                </select>
                            </div>
                            <div class='col-sm-2' id='period_control'>
                                <label>Period</label>
                                <select id='period' class='form-control select2'>
                                    <option value='all'>All</option>
                                    <option value='1st Semester'>1st Semester</option>
                                    <option value='2nd Semester'>2nd Semester</option>
                                    <option value='Summer'>Summer</option>
                                </select>
                            </div>
                        </div>
                        <div class='form form-group'>
                            <div class='col-sm-12'>
                                <button class='col-sm-12 btn btn-success' onclick='search(school_year.value, level.value, period.value)'>SEARCH</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id='display_studentlist'>
    </div>
</section>

@endsection
@section('footerscript')
<script>
    $('#display_studentlist').hide();
</script>
<script>
$("#period_control").hide();
    $("#level").on('change', function (e) {
        if ($("#level").val() == "Grade 11" || $("#level").val() == "Grade 12") {
            $("#period_control").fadeIn(300);
        } else if ($("#level").val() == "1st Year" || $("#level").val() == "2nd Year" || $("#level").val() == "3rd Year" || $("#level").val() == "4th Year" || $("#level").val() == "5th Year") {
            $("#period_control").fadeIn(300);            
        }
        else {
            $("#period_control").fadeOut(300);
        }
    });
    
    function search(school_year, level, period) {
        array = {};
        array['school_year'] = school_year;
        array['level'] = level;
        array['period'] = period;
        $.ajax({
            type: "GET",
            url: "/ajax/accounting/student_list",
            data: array,
            success: function (data) {
                $('#display_studentlist').fadeIn().html(data);
            }

        });
    }
    
    function print_search(school_year, level, period) {
        array = {};
        array['school_year'] = school_year;
        array['level'] = level;
        array['period'] = period;

        window.open('/accounting/print_search/' + array['school_year'] + "/" + array['level'] + "/" + array['period'], "_blank") ;
    }
</script>
@endsection