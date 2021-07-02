
<?php
if(Auth::user()->accesslevel == env('ACCTNG_HEAD')){
$layout = "layouts.appaccountinghead";
} else if(Auth::user()->accesslevel == env('ACCTNG_HEAD')){
$layout = "layouts.appaccountingstaff";
}else{
$layout = "layouts.appscholarship_bed";
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
        List of Scholars
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-home"></i> Home</a></li>
        <li class="active">List of Scholars</li>
    </ol>
</section>
@endsection
@section('maincontent')
<div class="row">
    <div class="col-md-12">
        <div class='form-horizontal'>
            <div class='form-group'>
                <div class='col-sm-4'>
                    <label>Scholarship Type</label>
                    <select class="form form-control" name="scholarship" id='scholarship'>
                        <option value="">Select Scholarship</option>
                        @foreach($scholarships as $scholarship)
                        <option value="{{$scholarship->discount_code}}">{{$scholarship->discount_description}}</option>
                        @endforeach
                    </select>    
                </div>
                <div class='col-sm-2'>
                    <label>School Year</label>
                    <select class="form form-control" name="school_year" id='school_year'>
                        <option value="">Select School Year</option>
                        <option value="2017">2017-2018</option>
                        <option value="2018">2018-2019</option>
                        <option value="2019">2019-2020</option>
                        <option value="2020">2020-2021</option>
                        <option value="2021">2021-2022</option>
                    </select>
                </div>  
                <div class='col-sm-4'>
                    <label>&nbsp;</label>
                    <button formtarget="_blank" type='submit' id='view-button' class='col-sm-12 btn btn-success' onclick="getList(scholarship.value, school_year.value)"><span>Get List</span></button>
                </div>
            </div>    
        </div>
    </div>
    <div class="col-sm-12">
        <div class="box box-body" id="display_list">
            
        </div>
    </div>
</div>
@endsection
@section('footerscript')
<script>
function getList(scholarship, school_year) {
    array = {};
    array['scholarship'] = scholarship;
    array['school_year'] = school_year;
    $.ajax({
    type: "GET",
            url: "/ajax/accounting/report/get_bed_scholarship_report/",
            data: array,
            success: function (data) {
            $('#display_list').html(data);
            }

    });
}
</script>
@endsection