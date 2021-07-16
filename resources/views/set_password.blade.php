<?php
$accesslevel = \Auth::user()->accesslevel;
$layout="layouts.apphome";
?>

@extends($layout)
@section('header')
<section class="content-header">
    <h1>
        Change Password
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Change Password</a></li>

    </ol>
</section>
@endsection
@section('maincontent')
<div class="col-md-6 offset-3">
    <h5>Please type your new PASSWORD.</h5>
    <form class="form form-horizontal" method="POST" action="{{url('set_password')}}">
        {{ csrf_field() }}
        <div class="form-group row">
            <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

            <div class="col-md-6">
                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                @if ($errors->has('password'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="form-group row">
            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">Confirm Password</label>

            <div class="col-md-6">
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
            </div>
        </div>

        <div class="form-group row mb-0">
            <div class="col-md-6 offset-md-4">
                <button type="submit" class="btn btn-primary">
                    Change Password
                </button>
            </div>
        </div>   
    </form>    
</div>    

@endsection
@section('footerscript')

@endsection