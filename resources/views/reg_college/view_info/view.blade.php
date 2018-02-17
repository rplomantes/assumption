<?php
$file_exist = 0;
if (file_exists(public_path("images/" . $user->idno . ".jpg"))) {
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
        Student Information
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li class="active"><a href="{{url('registrar_college', array('view_info', $idno))}}">View Information</a></li>
    </ol>
</section>
@endsection
@section('maincontent')


<form action="{{url('registrar_college', array('save_info', $idno))}}" method="post" class="form-horizontal">
{{ csrf_field() }}
    <div class="col-sm-12">
        <div class="box box-widget widget-user-2">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-yellow">
                <div class="widget-user-image">
                    @if($file_exist==1)
                    <img src="/images/{{$user->idno}}.jpg"  width="25" height="25" class="img-circle" alt="User Image">
                    @else
                    <img class="img-circle" width="25" height="25" alt="User Image" src="/images/default.png">
                    @endif
                </div>
                <h3 class="widget-user-username">{{$user->firstname}} {{$user->lastname}}</h3>
                <h5 class="widget-user-desc">{{$user->idno}}</h5>
            </div>
            <div class="box-footer no-padding">
                <ul class="nav nav-stacked">
                    <li>
                        <div class="row">
                            <div class="col-sm-2">
                                <label>ID Number</label>
                                <input type="text" name="idno" class="form-control" value="{{$user->idno}}">
                            </div>
                            <div class="col-sm-3">
                                <label>Lastname</label>
                                <input type="text" name="lastname" class="form-control" value="{{$user->lastname}}">
                            </div>
                            <div class="col-sm-3">
                                <label>Firstname</label>
                                <input type="text" name="firstname" class="form-control" value="{{$user->firstname}}">
                            </div>
                            <div class="col-sm-2">
                                <label>Middlename</label>
                                <input type="text" name="middlename" class="form-control" value="{{$user->middlename}}">
                            </div>
                            <div class="col-sm-2">
                                <label>Extension Name</label>
                                <input type="text" name="extensionname" class="form-control" value="{{$user->extensionname}}">
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-12">
        <!-- Custom Tabs -->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab">Personal Information</a></li>
                <li><a href="#tab_2" data-toggle="tab">Family Background</a></li>
                <li><a href="#tab_3" data-toggle="tab">Educational Background</a></li>
                <li><a href="#tab_4" data-toggle="tab">Admission Credentials</a></li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    <div class="form-group">
                        <div class="col-sm-8">
                            <label>Address</label>
                            <input class="form form-control" name='street' placeholder='Street Address' value="{{$info->street}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>&nbsp;</label>
                            <input class="form form-control" name='barangay' placeholder='Barangay' value="{{$info->barangay}}" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-5">
                            <input class="form form-control" name='municipality' placeholder='Municipality/City*' value="{{$info->municipality}}" type="text">
                        </div>
                        <div class="col-sm-5">
                            <input class="form form-control" name='province' placeholder='Province*' value="{{$info->province}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <input class="form form-control" name='zip' placeholder='ZIP Code' value="{{$info->zip}}" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-4">
                            <label>Contact Numbers</label>
                            <input class="form form-control" name='tel_no' placeholder='Telephone Number' value="{{$info->tel_no}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>&nbsp;</label>
                            <input class="form form-control" name='cell_no' placeholder='Cellphone Number' value="{{$info->cel_no}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>Email</label>
                            <input class="form form-control" name='email' placeholder='Email Address*' value="{{$user->email}}" type="email">
                        </div>
                    </div>
                    <hr>
                    <div class="form-group">
                        <div class="col-sm-3">
                            <label>Birthday</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-birthday-cake"></i>
                                </div>
                                <input class="form form-control" name='birthdate' value="{{$info->birthdate}}" type="date">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label>Birth Place</label>
                            <input class="form form-control" name='place_of_birth' value="{{$info->place_of_birth}}" placeholder='Place of Birth' type="text">
                        </div>
                        <div class="col-sm-3">
                            <label>Gender</label>
                            <select class="form form-control" name='gender' type="text">
                                <option value=''>Select Gender*</option>
                                <option value='Male' @if ($info->gender == 'Male') selected='' @endif>Male</option>
                                <option value='Female' @if ($info->gender == 'Female') selected='' @endif>Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-4">
                            <label>Civil Status</label>
                            <select class="form form-control" name='civil_status' placeholder='Telephone Number' type="text">
                                <option value="">Select Civil Status</option>
                                <option value="Single" @if ($info->civil_status == 'Single') selected='' @endif>Single</option>
                                <option value="Married" @if ($info->civil_status == 'Married') selected='' @endif>Married</option>
                                <option value="Divorced" @if ($info->civil_status == 'Divorced') selected='' @endif>Divorced</option>
                                <option value="Widowed" @if ($info->civil_status == 'Widowed') selected='' @endif>Widowed</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label>Nationality</label>
                            <input class="form form-control" name='nationality' placeholder='Nationality' value="{{$info->nationality}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>Religion</label>
                            <input class="form form-control" name='religion' placeholder='Religion' value="{{$info->religion}}" type="text">
                        </div>
                    </div>
                    <hr>
                    <label>For Non-Filipinos and Filipinos Born Abroad</label>
                    <div class="form-group">
                        <div class="col-sm-8">
                            <label>Immig. Status/Visa Classification</label>
                            <input class="form form-control" name='immig_status' value="{{$info->immig_status}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>Authorized Stay</label>
                            <input class="form form-control" name='auth_stay' value="{{$info->auth_stay}}" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-4">
                            <label>Passport No.</label>
                            <input class="form form-control" name='passport' value="{{$info->passport}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>Expiration Date</label>
                            <input class="form form-control" name='passport_exp_date' value="{{$info->passport_exp_date}}" type="date">
                        </div>
                        <div class="col-sm-4">
                            <label>Place Issued</label>
                            <input class="form form-control" name='passport_place_issued' value="{{$info->passport_place_issued}}" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-4">
                            <label>ACR No.</label>
                            <input class="form form-control" name='acr_no' value="{{$info->acr_no}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>Date Issued</label>
                            <input class="form form-control" name='acr_date_issued' value="{{$info->acr_date_issued}}" type="date">
                        </div>
                        <div class="col-sm-4">
                            <label>Place Issued</label>
                            <input class="form form-control" name='acr_place_issued' value="{{$info->acr_place_issued}}" type="text">
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab_2">
                    <label>Father</label>
                    <div class="form-group">
                        <div class="col-sm-8">
                            <label>Father's Name</label>
                            <input class="form form-control" name='father' value="{{$info->father}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>&nbsp;</label>
                            <div class='radio'>
                                <label><input name='f_is_living' value="1" type="radio">Living</label>
                                <label><input name='f_is_living' value="0" type="radio">Deceased</label>
                            </div>
                        </div>
                    </div>
                    <div class='form-group'>
                        <div class="col-sm-4">
                            <label>Occupation</label>
                            <input class="form form-control" name='f_occupation' value="{{$info->f_occupation}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Business Phone</label>
                            <input class="form form-control" name='f_phone' value="{{$info->f_phone}}" type="text">
                        </div>
                        <div class="col-sm-6">
                            <label>Business Address</label>
                            <input class="form form-control" name='f_address' value="{{$info->f_address}}" type="text">
                        </div>
                    </div>
                    <label>Mother</label>
                    <div class="form-group">
                        <div class="col-sm-8">
                            <label>Mother's Name</label>
                            <input class="form form-control" name='mother' value="{{$info->mother}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>&nbsp;</label>
                            <div class='radio'>
                                <label><input name='m_is_living' value="1" type="radio">Living</label>
                                <label><input name='m_is_living' value="0" type="radio">Deceased</label>
                            </div>
                        </div>
                    </div>
                    <div class='form-group'>
                        <div class="col-sm-4">
                            <label>Occupation</label>
                            <input class="form form-control" name='m_occupation' value="{{$info->m_occupation}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Business Phone</label>
                            <input class="form form-control" name='m_phone' value="{{$info->m_phone}}" type="text">
                        </div>
                        <div class="col-sm-6">
                            <label>Business Address</label>
                            <input class="form form-control" name='m_address' value="{{$info->m_address}}" type="text">
                        </div>
                    </div>
                    <hr>
                    <label>For Married:</label>
                    <div class="form-group">
                        <div class="col-sm-8">
                            <label>Spouse's Name</label>
                            <input class="form form-control" name='spouse' value="{{$info->spouse}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>&nbsp;</label>
                            <div class='radio'>
                                <label><input name='s_is_living' value="1" type="radio">Living</label>
                                <label><input name='s_is_living' value="0" type="radio">Deceased</label>
                            </div>
                        </div>
                    </div>
                    <div class='form-group'>
                        <div class="col-sm-4">
                            <label>Occupation</label>
                            <input class="form form-control" name='s_occupation' value="{{$info->s_occupation}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Business Phone</label>
                            <input class="form form-control" name='s_phone' value="{{$info->s_phone}}" type="text">
                        </div>
                        <div class="col-sm-6">
                            <label>Business Address</label>
                            <input class="form form-control" name='s_address' value="{{$info->s_address}}" type="text">
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab_3">
                    <label>Last School Attended</label>
                    <div class='form-group'>
                        <div class="col-sm-7">
                            <label>School</label>
                            <input class="form form-control" name='last_school_attended' value="{{$info->last_school_attended}}" type="text">
                        </div>
                        <div class="col-sm-3">
                            <label>Address</label>
                            <input class="form form-control" name='last_school_address' value="{{$info->last_school_address}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Year</label>
                            <input class="form form-control" name='last_school_year' value="{{$info->last_school_year}}" type="text">
                        </div>
                    </div>
                    <hr>
                    <label>Primary School</label>
                    <div class='form-group'>
                        <div class="col-sm-7">
                            <label>School</label>
                            <input class="form form-control" name='primary' value="{{$info->primary}}" type="text">
                        </div>
                        <div class="col-sm-3">
                            <label>Address</label>
                            <input class="form form-control" name='primary_address' value="{{$info->primary_address}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Year</label>
                            <input class="form form-control" name='primary_year' value="{{$info->primary_year}}" type="text">
                        </div>
                    </div>
                    <label>Grade School</label>
                    <div class='form-group'>
                        <div class="col-sm-7">
                            <label>School</label>
                            <input class="form form-control" name='gradeschool' value="{{$info->gradeschool}}" type="text">
                        </div>
                        <div class="col-sm-3">
                            <label>Address</label>
                            <input class="form form-control" name='gradeschool_address' value="{{$info->gradeschool_address}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Year</label>
                            <input class="form form-control" name='gradeschool_year' value="{{$info->gradeschool_year}}" type="text">
                        </div>
                    </div>
                    <label>High School</label>
                    <div class='form-group'>
                        <div class="col-sm-7">
                            <label>School</label>
                            <input class="form form-control" name='highschool' value="{{$info->highschool}}" type="text">
                        </div>
                        <div class="col-sm-3">
                            <label>Address</label>
                            <input class="form form-control" name='highschool_address' value="{{$info->highschool_address}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Year</label>
                            <input class="form form-control" name='highschool_year' value="{{$info->highschool_year}}" type="text">
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab_4">
                    Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                    Lorem Ipsum has been the industry's standard dummy text ever since the 1500s,
                    when an unknown printer took a galley of type and scrambled it to make a type specimen book.
                    It has survived not only five centuries, but also the leap into electronic typesetting,
                    remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset
                    sheets containing Lorem Ipsum passages, and more recently with desktop publishing software
                    like Aldus PageMaker including versions of Lorem Ipsum.
                </div>
            </div>
        </div>
    <input type="submit" value='Save' class='form-control btn btn-success'>
    </div>
</form>
@endsection
@section('footerscript')
@endsection