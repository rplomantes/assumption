<?php
//$school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first()->school_year;
//$period = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first()->period;
?>

@extends("layouts.appbedregistrar")
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
        Batch Ranking
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="/"> Graduates</a></li>
        <li class="active">Batch Ranking</li>
    </ol>
</section>
@endsection
@section('maincontent')
<div class='col-sm-12'>
        <div class='form-horizontal'>
            <div class='form-group'>
                <div class='col-sm-4'>
                    <label>Level</label>
                    <select class="form form-control" name="level" id='level'>
                        <option value="">Select Level</option>
                        @foreach($levels as $level)
                        <option>{{$level->level}}</option>
                        @endforeach
                    </select>
                </div>
                <div class='col-sm-4'>
                    <label>School Year</label>
                    <select class="form form-control" name="school_year" id='school_year' onchange="display_batch_ranking(this.value,level.value)">
                        <option value="">Select School Year</option>
                        @foreach($years as $year)
                        <option>{{$year->school_year}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class='box'>
            <div class='box-body'>
                
                    <div class="col-sm-12">
                        <div id="display_list">
                        </div>
                    </div>
            </div>
        </div>
    </form>
</div>
@endsection
@section('footerscript')
<script>
    function display_batch_ranking(year,level){
        array = {};
        array['school_year'] = year;
        array['level'] = level;
        $.ajax({
            type: "GET",
            url: "/ajax/bedregistrar/batch_ranking/get_students",
            data: array,
            success: function (data) {
                $('#display_list').html(data);
            }
        });
    }
</script>
@endsection