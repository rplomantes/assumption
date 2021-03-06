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
                    <form class='form form-horizontal' method="post" action="{{url('registrar_college',array('edit_now','credit_grades'))}}">
                        {{ csrf_field() }}
                        <input name="id" type="hidden" value="{{$id}}">
                        <input name="idno" type="hidden" value="{{$grade->idno}}">
                        <label>School Year(2018, 2019, 2020, etc.)</label>
                        <input name="school_year" type='text' class='form form-control' value = "{{$grade->school_year}}">
                        <label>Period</label>
                        <select name="period" class='form form-control'>
                            <option>Select Period</option>
                            <option @if($grade->period == "1st Semester") selected='' @endif>1st Semester</option>
                            <option @if($grade->period == "2nd Semester") selected='' @endif>2nd Semester</option>
                            <option @if($grade->period == "1st Quarter") selected='' @endif>1st Quarter</option>
                            <option @if($grade->period == "2nd Quarter") selected='' @endif>2nd Quarter</option>
                            <option @if($grade->period == "3rd Quarter") selected='' @endif>3rd Quarter</option>
                            <option @if($grade->period == "4th Quarter") selected='' @endif>4th Quarter</option>
                            <option @if($credit->period == "1st Term") selected='' @endif>1st Term</option>
                            <option @if($credit->period == "2nd Term") selected='' @endif>2nd Term</option>
                            <option @if($credit->period == "3rd Term") selected='' @endif>3rd Term</option>
                            <option @if($credit->period == "Summer") selected="" @endif>Summer</option>
                            <option @if($credit->period == "Spring") selected='' @endif>Spring</option>
                            <option @if($credit->period == "Fall") selected='' @endif>Fall</option>
                            <option @if($credit->period == "Winter") selected='' @endif>Winter</option>
                        </select>
                        <label>Course Code</label>
                        <input name="course_code" type='text' class='form form-control' value = "{{$grade->course_code}}">
                        <label>Course Name</label>
                        <input name="course_name" type='text' class='form form-control' value = "{{$grade->course_name}}">
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
                    <a href="{{url('registrar_college',array('delete_now','credit_grades',$id))}}" class="col-sm-12 btn btn-danger">DELETE RECORD</a>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('footerscript')
@endsection
