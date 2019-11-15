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
        Edit Transcript Details
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li class="active">Edit Transcript Details</li>
    </ol>
</section>
@endsection
@section('maincontent')

<section class="content">
    <div class="row">
        <div class="col-md-6">
            <div class='box'>
                <div class='box-header'>
                    <div class='box-title'>{{$grade->course_code}} - {{$grade->course_name}}</div>
                </div>
                <div class='box-body'>
                    <form class='form form-horizontal' method="post" action="{{url('registrar_college',array('edit_now','grades'))}}">
                        {{ csrf_field() }}
                        <input name="id" type="hidden" value="{{$id}}">
                        <label>School Year(2018, 2019, 2020, etc.)</label>
                        <input name="school_year" type='text' class='form form-control' value = "{{$grade->school_year}}">
                        <label>Period</label>
                        <select name="period" class='form form-control'>
                            <option>Select Period</option>
                            <option @if($grade->period == "1st Semester") selected='' @endif>1st Semester</option>
                            <option @if($grade->period == "2nd Semester") selected='' @endif>2nd Semester</option>
                            <option @if($grade->period == "Summer") selected='' @endif>Summer</option>
                        </select>
                        <label>Course Code</label>
                        <input name="course_code" type='text' class='form form-control' value = "{{$grade->course_code}}">
                        <label>Course Name</label>
                        <input name="course_name" type='text' class='form form-control' value = "{{$grade->course_name}}">
                        <label>Lecture Unit</label>
                        <input name="lec" type='text' class='form form-control' value = "{{$grade->lec}}">
                        <label>Laboratory Unit</label>
                        <input name="lab" type='text' class='form form-control' value = "{{$grade->lab}}">
                        <label>&nbsp;</label>
                        <input type='submit' class='col-sm-12 btn btn-success' value = "Update Record">
                    </form>  
                </div>
            </div>
        </div>
        <div class='col-md-6'>
            <div class="box">
                <div class='box-header'>
                    <div class='box-title'>Delete Course</div>
                </div>
                <div class="box-body">
                    <a href="{{url('registrar_college',array('delete_now','grades',$id))}}" class="col-sm-12 btn btn-danger">DELETE RECORD</a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('footerscript')
@endsection
