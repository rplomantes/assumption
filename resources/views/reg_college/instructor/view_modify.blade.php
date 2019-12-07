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
        {{$user_info->firstname}} {{$user_info->lastname}}
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="#"> Instructor</a></li>
        <li class="active"><a href="{{ url ('/registrar_college', array('instructor','view_instructor'))}}"> View Instructor</a></li>
        <li class="active"><a href="{{ url ('/registrar_college', array('instructor','modify_instructor', $idno))}}"> {{$idno}}</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<section class="content">
    <div class="row">
        <form class="form-horizontal" method='post' action='{{url('/registrar_college', array('instructor', 'modify_old_instructor'))}}'>
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
                                <input class="form form-control" readonly="" name="idno" placeholder="ID Number*" value="{{$user_info->idno}}" type="text">
                            </div>
                        <div class="col-sm-3 pull-right">
                            <label><br><br></label>
                            <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-default">
                            Reset Password
                            </button>
                        </div>                            
                    </div>
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label>Name</label>
                                <input class="form form-control" name='firstname' placeholder='First Name*' value="{{$user_info->firstname}}" type="text">
                            </div>
                            <div class="col-sm-3">
                                <label>&nbsp;</label>
                                <input class="form form-control" name='middlename' placeholder='Middle Name' value="{{$user_info->middlename}}" type="text">
                            </div>
                            <div class="col-sm-3">
                                <label>&nbsp;</label>
                                <input class="form form-control" name='lastname' placeholder='Last Name*' value="{{$user_info->lastname}}" type="text">
                            </div>
                            <div class="col-sm-3">
                                <label>&nbsp;</label>
                                <input class="form form-control" name='extensionname' placeholder='Extension Name' value="{{$user_info->extensionname}}" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-8">
                                <label>Address</label>
                                <input class="form form-control" name='street' placeholder='Street Address' value="{{$instructor_info->street}}" type="text">
                            </div>
                            <div class="col-sm-4">
                                <label>&nbsp;</label>
                                <input class="form form-control" name='barangay' placeholder='Barangay' value="{{$instructor_info->barangay}}" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-5">
                                <input class="form form-control" name='municipality' placeholder='Municipality/City*' value="{{$instructor_info->municipality}}" type="text">
                            </div>
                            <div class="col-sm-5">
                                <input class="form form-control" name='province' placeholder='Province*' value="{{$instructor_info->province}}" type="text">
                            </div>
                            <div class="col-sm-2">
                                <input class="form form-control" name='zip' placeholder='ZIP Code' value="{{$instructor_info->zip}}" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-4">
                                <label>Birthday</label>
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-birthday-cake"></i>
                                    </div>
                                    <input class="form form-control" name='birthdate' value="{{$instructor_info->birthdate}}" type="date">
                                </div>
                            </div>
                            <div class="col-sm-4">
                                <label>Birth Place</label>
                                <input class="form form-control" name='place_of_birth' value="{{$instructor_info->place_of_birth}}" placeholder='Place of Birth' type="text">
                            </div>
                            <div class="col-sm-4">
                                <label>Gender</label>
                                <select class="form form-control" name='gender' type="text">
                                    <option value=''>Select Gender*</option>
                                    <option value='Male' @if($instructor_info->gender == 'Male') selected="" @endif>Male</option>
                                    <option value='Female' @if($instructor_info->gender == 'Female') selected="" @endif>Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-4">
                                <label>Contact Numbers</label>
                                <input class="form form-control" name='tel_no' placeholder='Telephone Number' value="{{$instructor_info->tel_no}}" type="text">
                            </div>
                            <div class="col-sm-4">
                                <label>&nbsp;</label>
                                <input class="form form-control" name='cell_no' placeholder='Cellphone Number' value="{{$instructor_info->cell_no}}" type="text">
                            </div>
                            <div class="col-sm-4">
                                <label>Email</label>
                                <input class="form form-control" name='email' placeholder='Email Address*' value="{{$user_info->email}}" type="email">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-4">
                                <label>Civil Status</label>
                                <select class="form form-control" name='civil_status' placeholder='Telephone Number' value="{{$instructor_info->civil_status}}" type="text">
                                    <option value="">Select Civil Status</option>
                                    <option value="Single" @if($instructor_info->civil_status == 'Single') selected="" @endif>Single</option>
                                    <option value="Married" @if($instructor_info->civil_status == 'Married') selected="" @endif>Married</option>
                                    <option value="Divorced" @if($instructor_info->civil_status == 'Divorced') selected="" @endif>Divorced</option>
                                    <option value="Widowed" @if($instructor_info->civil_status == 'Widowed') selected="" @endif>Widowed</option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label>Nationality</label>
                                <input class="form form-control" name='nationality' placeholder='Nationality' value="{{$instructor_info->nationality}}" type="text">
                            </div>
                            <div class="col-sm-4">
                                <label>Religion</label>
                                <input class="form form-control" name='religion' placeholder='Religion' value="{{$instructor_info->religion}}" type="text">
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
                                    <option value="Regular" @if($instructor_info->employment_status == 'Regular') selected="" @endif>Regular</option>
                                    <option value="Part Time" @if($instructor_info->employment_status == 'Part Time') selected="" @endif>Part Time</option>
                                </select>
                            </div>
                        </div>
                        <div class="form form-group">
                            <div class="col-sm-4">
                                <label>Academic Type </label>
                                <select name="academic_type" class="form form-control">
                                    <option value="College" @if($instructor_info->academic_type == 'College') selected="" @endif>College</option>
                                </select>
                            </div>
                            <div class="col-sm-4">
                                <label>Department </label>
                                <select name="department" class="form form-control">
                                    <option value="">Select Department</option>
                                    <option value="Psychology Department" @if($instructor_info->department == 'Psychology Department') selected="" @endif>Psychology Department</option>
                                    <option value="Tourism Department" @if($instructor_info->department == 'Tourism Department') selected="" @endif>Tourism Department</option>
                                    <option value="Education Department" @if($instructor_info->department == 'Education Department') selected="" @endif>Education Department</option>
                                    <option value="Business Department" @if($instructor_info->department == 'Business Department') selected="" @endif>Business Department</option>
                                </select>
                            </div>
                        </div>
                        <div class="form form-group">
                            <div class="col-sm-4">
                                <label>Educational Degree </label>
                                <select name="degree_status" class="form form-control">
                                    <option value="">Select Educational Degree</option>
                                    <option value="Bachelor's Degree" @if($instructor_info->degree_status == "Bachelor's Degree") selected="" @endif>Bachelor's Degree</option>
                                    <option value="Master's Degree" @if($instructor_info->degree_status == "Master's Degree") selected="" @endif>Master's Degree</option>
                                    <option value="Doctor's Degree" @if($instructor_info->degree_status == "Doctor's Degree") selected="" @endif>Doctor's Degree</option>
                                </select>
                            </div>
                            <div class="col-sm-8">
                                <label>Program Graduated </label>
                                <input class="form form-control" name='program_graduated' placeholder='Program Name' value="{{$instructor_info->program_graduated}}" type="text">
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
         <div class="modal fade" id="modal-default">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Enter New Password : </h4>
              </div>
                <form method="post" action="{{url('/registrar_college/instructor/resetpassword')}}">
                     {{csrf_field()}} 
                     <input type="hidden" name="idno" value="{{$user_info->idno}}">
              <div class="modal-body">
                  <input type="text" name="password" class="form form-control">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-primary" value="Reset Password">
              </div>
                </form>     
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->        
    </div>
</section>
@endsection
@section('footerscript')
@endsection