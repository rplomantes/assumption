<?php
$accesslevel = \Auth::user()->accesslevel;
switch ($accesslevel) {
    case 1:
        $layout="layouts.appcollege_instructor";
        break;
    case 10:
        $layout="layouts.appdean_college";
        break;
    case 11:
        $layout="layouts.appmesil";
        break;
    case 12:
        $layout="layouts.appmsbmw";
        break;
    case 20:
        $layout="layouts.appreg_college";
        break;
    case 21:
        $layout="layouts.appbedregistrar";
        break;
    case 22:
        $layout="layouts.apposa";
        break;
    case 23:
        $layout="layouts.appedutech";
        break;
    case 30:
        $layout="layouts.appaccountingstaff";
        break;
    case 31:
        $layout="layouts.appaccountingstaff";
        break;
    case 40:
        $layout="layouts.appcashier";
        break;
    case 100:
        $layout="layouts.admin";
        break;
    case 50:
        $layout="layouts.appbookstore";
        break;
    case 60:
        $layout="layouts.appadmission-hed";
        break;
    case 61:
        $layout="layouts.appadmission-bed";
        break;
    case 62:
        $layout="layouts.appadmission-shs";
        break;
    case 70:
        $layout="layouts.appguidace_hed";
        break;
    case 71:
        $layout="layouts.appguidance_bed";
        break;
    case 80:
        $layout="layouts.appscholarship_college";
        break;
}
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
                    Register
                </button>
            </div>
        </div>   
    </form>    
</div>    

@endsection
@section('footerscript')

@endsection