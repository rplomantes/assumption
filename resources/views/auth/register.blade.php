@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Register</div>

                <div class="panel-body">
                    <form class="form-horizontal" method="POST" action="{{ route('register') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('idno') ? ' has-error' : '' }}">
                            <label for="idno" class="col-md-4 control-label">ID Number</label>
                            
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="idno" value="{{ old('idno') }}" required autofocus>

                                @if ($errors->has('idno'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('idno') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                            <div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
                                <label for="idno" class="col-md-4 control-label">First Name</label>
                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="firstname" value="{{ old('firstname') }}" required autofocus>

                                @if ($errors->has('firstname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('firstname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="form-group{{ $errors->has('middlename') ? ' has-error' : '' }}">
                            <label for="middlename" class="col-md-4 control-label">Middle Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="middlename" value="{{ old('middlename') }}" autofocus>

                                @if ($errors->has('middlename'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('middlename') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">
                            <label for="lastname" class="col-md-4 control-label">Last Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="lastname" value="{{ old('lastname') }}" required autofocus>

                                @if ($errors->has('lastname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('lastname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('extensionname') ? ' has-error' : '' }}">
                            <label for="firstname" class="col-md-4 control-label">Extension Name</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="extensionname" value="{{ old('extensionname') }}" autofocus>

                                @if ($errors->has('extensionname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('extensionname') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="form-group{{ $errors->has('accesslevel') ? ' has-error' : '' }}">
                            <label for="accesslevel" class="col-md-4 control-label">Access Level</label>

                            <div class="col-md-6">
                                <select id="accesslevel" class="form-control" name="accesslevel">
                                    <option>Select Access level</option>
                                    <option value="1">Instructor</option>
                                    <option value="10">Dean</option>
                                    <option value="11">MESIL</option>
                                    <option value="12">MSBMW</option>
                                    <option value="20">Registrar College</option>
                                    <option value="21">Registrar Basic Education</option>
                                    <option value="30">Accounting Head</option>
                                    <option value="31">Accounting Staff</option>
                                    <option value="40">Cashier</option>
                                </select>    
               
                                @if ($errors->has('accesslevel'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('accesslevel') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}">

                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-primary">
                                    Register
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
