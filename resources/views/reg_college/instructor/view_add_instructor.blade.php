<?php
if(Auth::user()->accesslevel == env('DEAN')){
$layout = "layouts.appdean_college";
} else {
$layout = "layouts.appreg_college";
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
        Add New Instructor
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="#"> Instructor</a></li>
        <li class="active"><a href="{{ url ('/registrar_college', array('instructor','add_instructor'))}}"> Add Instructor</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<section class="content">
    <div class="row">
        <form class="form-horizontal" method='post' action='{{url('/registrar_college', array('instructor', 'add_new_instructor'))}}'>
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Personal Information</h3>
                    </div>
                    @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="box-body">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div class="col-sm-4">
                                <label>ID Number</label>
                                <input class="form form-control" name="idno" placeholder="ID Number*" value="{{old('idno')}}" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label>Name</label>
                                <input class="form form-control" name='firstname' placeholder='First Name*' value="{{old('firstname')}}" type="text">
                            </div>
                            <div class="col-sm-3">
                                <label>&nbsp;</label>
                                <input class="form form-control" name='middlename' placeholder='Middle Name' value="{{old('middlename')}}" type="text">
                            </div>
                            <div class="col-sm-3">
                                <label>&nbsp;</label>
                                <input class="form form-control" name='lastname' placeholder='Last Name*' value="{{old('lastname')}}" type="text">
                            </div>
                            <div class="col-sm-3">
                                <label>&nbsp;</label>
                                <input class="form form-control" name='extensionname' placeholder='Extension Name' value="{{old('extensionname')}}" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-8">
                                <label>Address</label>
                                <input class="form form-control" name='street' placeholder='Street Address' value="{{old('street')}}" type="text">
                            </div>
                            <div class="col-sm-4">
                                <label>&nbsp;</label>
                                <input class="form form-control" name='barangay' placeholder='Barangay' value="{{old('barangay')}}" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-5">
                                <input class="form form-control" name='municipality' placeholder='Municipality/City*' value="{{old('municipality')}}" type="text">
                            </div>
                            <div class="col-sm-5">
                                <input class="form form-control" name='province' placeholder='Province*' value="{{old('province')}}" type="text">
                            </div>
                            <div class="col-sm-2">
                                <input class="form form-control" name='zip' placeholder='ZIP Code' value="{{old('zip')}}" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-4">
                                <label>Birthday</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-birthday-cake"></i>
                                    </div>
                                    <input class="form form-control" name='birthdate' value="{{old('birthdate')}}" type="date">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Birth Place</label>
                                <input class="form form-control" name='place_of_birth' value="{{old('place_of_birth')}}" placeholder='Place of Birth' type="text">
                            </div>
                            <div class="col-sm-4">
                                <label>Gender</label>
                                <select class="form form-control" name='gender' type="text">
                                    <option value=''>Select Gender*</option>
                                    <option value='Male'>Male</option>
                                    <option value='Female'>Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-4">
                                <label>Contact Numbers</label>
                                <input class="form form-control" name='tel_no' placeholder='Telephone Number' value="{{old('tel_no')}}" type="text">
                            </div>
                            <div class="col-sm-4">
                                <label>&nbsp;</label>
                                <input class="form form-control" name='cell_no' placeholder='Cellphone Number' value="{{old('cell_no')}}" type="text">
                            </div>
                            <div class="col-sm-4">
                                <label>Email</label>
                                <input class="form form-control" name='email' placeholder='Email Address*' value="{{old('email')}}" type="email">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-4">
                                <label>Civil Status</label>
                                <select class="form form-control" name='civil_status' placeholder='Telephone Number' value="{{old('civil_status')}}" type="text">
                                    <option value="">Select Civil Status</option>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Divorced">Divorced</option>
                                    <option value="Widowed">Widowed</option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label>Nationality</label>
                                <input class="form form-control" name='nationality' placeholder='Nationality' value="{{old('nationality')}}" type="text">
                            </div>
                            <div class="col-sm-4">
                                <label>Religion</label>
                                <input class="form form-control" name='religion' placeholder='Religion' value="{{old('religion')}}" type="text">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">Other Information</h3>
                    </div>
                    <div class="box-body">
                        <div class="form form-group">
                            <div class="col-sm-4">
                                <label>Employment Status</label>
                                <select name="employment_status" class="form form-control">
                                    <option value="">Select employment status*</option>
                                    <option value="Regular">Regular</option>
                                    <option value="Part Time">Part Time</option>
                                </select>
                            </div>
                        </div>
                        <div class="form form-group">
                            <div class="col-sm-4">
                                <label>Academic Type </label>
                                <select name="academic_type" class="form form-control">
                                    <option value="College">College</option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label>Department </label>
                                <select name="department" class="form form-control">
                                    <option value="">Select Department</option>
                                    <option value="Psychology Department">Psychology Department</option>
                                    <option value="Education Department">Education Department</option>
                                    <option value="Business Department">Business Department</option>
                                </select>
                            </div>
                        </div>
                        <div class="form form-group">
                            <div class="col-sm-4">
                                <label>Educational Degree </label>
                                <select name="degree_status" class="form form-control">
                                    <option value="">Select Educational Degree</option>
                                    <option value="Bachelor's Degree">Bachelor's Degree</option>
                                    <option value="Master's Degree">Master's Degree</option>
                                    <option value="Doctor's Degree">Doctor's Degree</option>
                                </select>
                            </div>
                            <div class="col-sm-8">
                                <label>Program Graduated </label>
                                <input class="form form-control" name='program_graduated' placeholder='Program Name' value="{{old('program_graduated')}}" type="text">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                    <div class='form form-group'>
                        <div class='col-sm-12'>
                        <input type='submit' class='col-sm-12 btn btn-primary' value='SAVE'>
                        </div>
                    </div>
        </form>
    </div>
</section>
@endsection
@section('footerscript')
@endsection