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
        Add Grade Record
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li class="active">Add Grade Record</li>
    </ol>
</section>
@endsection
@section('maincontent')

<section class="content">
    <div class="row">
        <div class="col-md-6">
            <div class='box'>
                <div class='box-body'>
                    <form class='form form-horizontal' method="post" action="{{url('registrar_college',array('add_record_now'))}}">
                        {{ csrf_field() }}
                        <div class="col-sm-12">
                            <input name="idno" type="hidden" value="{{$idno}}">
                            <div class="form form-group">
                                <label>School Year(2018, 2019, 2020, etc.)</label>
                                <input name="school_year" type='text' class='form form-control' placeholder="School Year">
                            </div>
                            <div class="form form-group">
                                <label>Period</label>
                                <select name="period" class='form form-control'>
                                    <option>Select Period</option>
                                    <option>1st Semester</option>
                                    <option>2nd Semester</option>
                                    <option>Summer</option>
                                </select>
                            </div>
                            <div class="form form-group">
                                <label>Select Course</label>
                                <select name="course_code" class='form form-control select2'>
                                    <option>Select Course</option>
                                    @foreach ($courses as $course)
                                    <option value="{{$course->course_code}}">{{$course->course_code}} - {{$course->course_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form form-group">
                                <label>Final Grade</label>
                                <select class="form form-control select2" name="finals" id="finals">
                                <option></option>
                                <option>PASSED</option>
                                <option>1.00</option>
                                <option>1.20</option>
                                <option>1.50</option>
                                <option>1.70</option>
                                <option>2.00</option>
                                <option>2.20</option>
                                <option>2.50</option>
                                <option>2.70</option>
                                <option>3.00</option>
                                <option>3.50</option>
                                <option>4.00</option>
                                <option>FAILED</option>
                                <option>FA</option>
                                <option>INC</option>
                                <option>NA</option>
                                <option>NG</option>
                                <option>UD</option>
                                <option>W</option>
                                <option>AUDIT</option>
                            </select>
                            </div>
                            <div class="form form-group">
                                <label>&nbsp;</label>
                                <input type='submit' class='col-sm-12 btn btn-success' value = "Add Record">
                            </div>
                        </div>
                    </form>  
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('footerscript')
@endsection
