<?php
$file_exist = 0;
if (file_exists(public_path("images/PICTURES/" . $user->idno . ".jpg"))) {
    $file_exist = 1;
}
?>

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
        Upload Image
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li class="active">Upload Image</li>
    </ol>
</section>
@endsection
@section('maincontent')


<div class="col-sm-12">
    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    @if (\Session::has('success'))
    <div class="alert alert-success">
        <ul>
            <li>{!! \Session::get('success') !!}</li>
        </ul>
    </div>
    @endif
</div>
<div class="col-sm-6">
    <div class="box box-widget widget-user-2">
        <!-- Add the bg color to the header using any of the bg-* classes -->

        <div class="widget-user-header bg-yellow">
            @if($file_exist==1)
            <img src="/images/PICTURES/{{$user->idno}}.jpg" width="250" height="250" class="img-circle" alt="User Image">
            @else
            <img class="img-circle" width="250" height="250" alt="User Image" src="/images/default.png">
            @endif
            <form enctype="multipart/form-data" method="post" action="{{url('save_image')}}">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="image">Upload Image</label>
                    <input type="hidden" name="idno" value="{{$user->idno}}">
                    <input name="image" type="file" id="image">
                    <p class="help-block">Image must be in .jpg format</p>
                </div>
                <div class="form-group">
                    <input class="form-control btn btn-success" type="submit" value="Upload Image">
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('footerscript')
@endsection
