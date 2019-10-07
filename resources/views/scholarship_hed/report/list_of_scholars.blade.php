@extends('layouts.appscholarship_college')
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
        <small>A.Y. {{$school_year}} - {{$school_year+1}}, {{$period}}</small>
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
                <div class='col-sm-2'>
                    <label>School Year</label>
                    <select class="form form-control" name="school_year" id='school_year'>
                        <option value="">Select School Year</option>
                        <option value="2017" @if ($school_year == 2017) selected = "" @endif>2017-2018</option>
                        <option value="2018" @if ($school_year == 2018) selected = "" @endif>2018-2019</option>
                        <option value="2019" @if ($school_year == 2019) selected = "" @endif>2019-2020</option>
                        <option value="2020" @if ($school_year == 2020) selected = "" @endif>2020-2021</option>
                        <option value="2021" @if ($school_year == 2021) selected = "" @endif>2021-2022</option>
                    </select>
                </div>
                <div class='col-sm-2'>
                    <label>Period</label>
                    <select class="form form-control" name="period" id='period'>
                        <option value="">Select Period</option>
                        <option value='1st Semester' @if ($period == "1st Semester") selected = "" @endif>1st Semester</option>
                        <option value='2nd Semester' @if ($period == "2nd Semester") selected = "" @endif>2nd Semester</option>
                        <option value='Summer' @if ($period == "Summer") selected = "" @endif>Summer</option>
                    </select>    
                </div>   
                <div class='col-sm-4'>
                    <label>&nbsp;</label>
                    <button formtarget="_blank" type='submit' id='view-button' class='col-sm-12 btn btn-success'><span>Change School Year/Period</span></button>
                </div>
            </div>    
        </div>
    </div>
    <div class="col-sm-12">
        <div class="box box-body">
            <?php $control = 1; ?>
            @if(count($scholars)>0)
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>ID Number</th>
                        <th>Name</th>
                        <th>Program Enrolled</th>
                        <th>Level</th>
                        <th>Scholarship</th>
                        <th>Tuition %</th>
                        <th>Others %</th>
                    <tr>
                </thead>
                <tbody>
                    @foreach($scholars as $scholar)
                    <tr>
                        <td>{{$control++}}.</td>
                        <td>{{$scholar->idno}}</td>
                        <td>{{$scholar->getFullNameAttribute()}}</td>
                        <td>{{$scholar->program_code}}</td>
                        <td>{{$scholar->level}}</td>
                        <td>{{$scholar->discount_description}}</td>
                        <td>{{$scholar->tuition_fee}}</td>
                        <td>{{$scholar->other_fee}}</td>
                    </tr>
                    @endforeach
                <tbody>
            </table>
            @endif
        </div>
        <a target='_blank' id='print_enroll' href='{{url('scholarship_college', array('report', 'print_list_of_scholars', $school_year, $period))}}'><button class="btn btn-success col-sm-12">PRINT LIST OF SCHOLARS</button></a>
    </div>
</div>
@endsection
@section('footerscript')
<script>
    $(document).ready(function () {
        $("#view-button").on('click', function (e) {
            document.location = "{{url('/scholarship_college',array('report'))}}" + "/list_of_scholars/" + $("#school_year").val() + "/" + $("#period").val();
        });
    });
</script>
@endsection