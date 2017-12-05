@extends('layouts.appreg_college')
@section('messagemenu')
<link rel="stylesheet" href="{{ asset ('bower_components/select2/dist/css/select2.min.css')}}">
<?php
$programs = \App\CtrAcademicProgram::distinct()->where('academic_type', 'College')->get(['program_code', 'program_name']);
?>
<li class="dropdown messages-menu">
    <!-- Menu toggle button -->
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-envelope-o"></i>
        <span class="label label-success">4</span>
    </a>
    <ul class="dropdown-menu">
        <li class="header">You have 4 messages</li>
        <li>
            <!-- inner menu: contains the messages -->
            <ul class="menu">
                <li><!-- start message -->
                    <a href="#">
                        <div class="pull-left">
                            <!-- User Image -->

                        </div>
                        <!-- Message title and timestamp -->
                        <h4>
                            Support Team
                            <small><i class="fa fa-clock-o"></i> 5 mins</small>
                        </h4>
                        <!-- The message -->
                        <p>Why not buy a new awesome theme?</p>
                    </a>
                </li>
                <!-- end message -->
            </ul>
            <!-- /.menu -->
        </li>
        <li class="footer"><a href="#">See All Messages</a></li>
    </ul>
</li>
@endsection
@section('header')
<section class="content-header">
    <h1>
        Admission
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="#"></i> Curriculum Management</a></li>
        <li class="active"><a href="{{ url ('/registrar_college', array('admission','new_student'))}}"></i> New Student</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<section class="content">
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><span class='fa fa-edit'></span> Personal Information</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
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
                </div>
                <div class="box-body">
                    <form class="form-horizontal" method='post' action='{{url('/registrar_college', array('admission', 'add_new_student'))}}'>
                        {{ csrf_field() }}
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
                                <input class="form form-control" name='birthdate' value="{{old('birthdate')}}" type="date">
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
                            <div class="col-sm-12">
                                <label>Last School Attended</label>
                                <input class="form form-control" name='last_school_attended' placeholder='Last School Attended' value="{{old('last_school_attended')}}" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <label>Program to enroll*</label>
                                <select class="form form-control select2" name='program_to_enroll' type="text">
                                    <option value="">Select a Program</option>
                                    @foreach ($programs as $program)
                                    <option value="{{$program->program_code}}">{{$program->program_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input class="form form-control btn btn-success" type="submit" value='REGISTER NEW STUDENT'>
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
<script src="{{asset('bower_components/select2/dist/js/select2.full.min.js')}}"></script>
<script>
$(function () {
    $('.select2').select2();
});
</script>
@endsection