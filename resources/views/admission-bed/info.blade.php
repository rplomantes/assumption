<?php
$file_exist = 0;
if (file_exists(public_path("images/PICTURES/" . $user->idno . ".jpg"))) {
    $file_exist = 1;
}
?>

@extends('layouts.appadmission-bed')
@section('messagemenu')
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
        Student Information
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Student Information</li>
      </ol>
</section>
@endsection
@section('maincontent')
<div class="form-horizontal">
    @if($status->status == env("FOR_APPROVAL"))
    {{ csrf_field() }}
    <div class="col-md-12">
        
         <div class="col-md-3 pull-right">
             <div class="form form-group">
                 <label>Admission Status: </label><button class="form form-control btn btn-success" style="color: white">FOR APPROVAL</button>
             </div>
          </div>
        <?php $testing_schedules = \App\TestingSchedule::where('is_remove',0)->get(); ?>
        <?php $testing = \App\TestingStudent::where('idno',$user->idno)->first(); ?>
        <div class="col-md-3 pull-right">
             <div class="form form-group">
                 <label>Testing Schedule:</label>
                 <select class="form form-control" onchange='update_testing(this.value)'>
                     <option>Select Schedule</option>
                     @foreach($testing_schedules as $sched)
                     <option value='{{$sched->id}}' @if($testing->schedule_id == $sched->id)selected @endif>{{$sched->datetime}}-{{$sched->room}}</option>
                     @endforeach
                 </select>
             </div>
          </div>
    </div>
    @elseif($status->status == env("PRE_REGISTERED"))
    {{ csrf_field() }}
    <div class="col-md-12">
        
         <div class="col-md-3 pull-right">
             <div class="form form-group">
                 <label>Admission Status: </label><button class="form form-control btn btn-success" style="color: white">PRE-REGISTERED</button>
             </div>
          </div>
    </div>
    
    @endif
    <div class="col-sm-12">
        @if (Session::has('message'))
            <div class="alert alert-success">{{ Session::get('message') }}</div>
        @endif  
        <div class="box box-widget widget-user-2">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-yellow">
                <div class="widget-user-image">
                    @if($file_exist==1)
                        <img src="/images/PICTURES/{{$user->idno}}.jpg"  width="25" height="25" class="img-circle" alt="User Image">
                    @else
                    <img class="img-circle" width="25" height="25" alt="User Image" src="/images/default.png">
                    @endif
                </div>
                <h3 class="widget-user-username">{{$user->firstname}} {{$user->middlename}} {{$user->lastname}}</h3>
                <h5 class="widget-user-desc">{{$user->idno}}</h5>
            </div>
            <div class="box-footer no-padding">
                <ul class="nav nav-stacked">
                    <li>
                        <div class="row">
                            <div class="col-sm-2">
                                <label>ID Number</label>
                                <input type="text" name="idno" class="form-control" value="{{old('idno',$user->idno)}}" readonly="">
                            </div>
                            <div class="col-sm-3">
                                <label>Lastname</label>
                                <input type="text" name="lastname" class="form-control" value="{{old('lastname',$user->lastname)}}">
                            </div>
                            <div class="col-sm-3">
                                <label>Firstname</label>
                                <input type="text" name="firstname" class="form-control" value="{{old('firstname',$user->firstname)}}">
                            </div>
                            <div class="col-sm-2">
                                <label>Middlename</label>
                                <input type="text" name="middlename" class="form-control" value="{{old('middlename',$user->middlename)}}">
                            </div>
                            <div class="col-sm-2">
                                <label>Extension Name</label>
                                <input type="text" name="extensionname" class="form-control" value="{{old('extensionname',$user->extensionname)}}">
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
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
    </div>
    <div class="col-md-12">
        <!-- Custom Tabs -->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#tab_1" data-toggle="tab">Personal Information</a></li>
                <li><a href="#tab_2" data-toggle="tab">Family Background</a></li>
                <li><a href="#tab_3" data-toggle="tab">Educational Background</a></li>
                <!--<li><a href="#tab_4" data-toggle="tab">Admission Credentials</a></li>-->
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="tab_1">
                    <div class="form-group">
                        <div class="col-sm-8">
                            <label>Address</label>
                            <input class="form form-control" name='street' placeholder='Street Address' value="{{old('street',$info->street)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>&nbsp;</label>
                            <input class="form form-control" name='barangay' placeholder='Barangay' value="{{old('barangay',$info->barangay)}}" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-5">
                            <input class="form form-control" name='municipality' placeholder='Municipality/City*' value="{{old('municipality',$info->municipality)}}" type="text">
                        </div>
                        <div class="col-sm-5">
                            <input class="form form-control" name='province' placeholder='Province*' value="{{old('province',$info->province)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <input class="form form-control" name='zip' placeholder='ZIP Code' value="{{old('zip',$info->zip)}}" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-4">
                            <label>Contact Numbers</label>
                            <input class="form form-control" name='tel_no' placeholder='Telephone Number' value="{{old('tel_no',$info->tel_no)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>&nbsp;</label>
                            <input class="form form-control" name='cell_no' placeholder='Cellphone Number' value="{{old('cel_no',$info->cell_no)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>Email</label>
                            <input class="form form-control" name='email' placeholder='Email Address*' readonly="" value="{{old('email',$user->email)}}" type="email">
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
                                <input class="form form-control" name='date_of_birth' value="{{old('birthdate',$info->date_of_birth)}}" type="date">
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label>Birth Place</label>
                            <input class="form form-control" name='place_of_birth' value="{{old('place_of_birth',$info->place_of_birth)}}" placeholder='Place of Birth' type="text">
                        </div>
                        <div class="col-sm-3">
                            <label>Gender</label>
                            <select class="form form-control" name='gender' type="text">
                                <option value=''>Select Gender*</option>
                                <option value='Female' @if ($info->gender == 'Female') selected='' @endif>Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-4">
                            <label>Nationality</label>
                            <input class="form form-control" name='nationality' placeholder='Nationality' value="{{old('nationality',$info->nationality)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>Religion</label>
                            <input class="form form-control" name='religion' placeholder='Religion' value="{{old('religion',$info->religion)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>Local/Foreigner</label>
                            <select class="form form-control" name='is_foreign' value="{{old('is_alien')}}" type="text">
                                <option value="">Select Local/Foreign</option>
                                <option value="0" @if ($user->is_foreign == 0) selected='' @endif>Local</option>
                                <option value="1" @if ($user->is_foreign == 1) selected='' @endif >Foreign</option>
                            </select>
                        </div>
                    </div>
                    <hr>
                    <label>For Non-Filipinos and Filipinos Born Abroad</label>
                    <div class="form-group">
                        <div class="col-sm-8">
                            <label>Immig. Status/Visa Classification</label>
                            <input class="form form-control" name='immig_status' value="{{old('immig_status',$info->immig_status)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>Authorized Stay</label>
                            <input class="form form-control" name='auth_stay' value="{{old('auth_stay',$info->auth_stay)}}" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-4">
                            <label>Passport No.</label>
                            <input class="form form-control" name='passport' value="{{old('passport',$info->passport)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>Expiration Date</label>
                            <input class="form form-control" name='passport_exp_date' value="{{old('passport_exp_date',$info->passport_exp_date)}}" type="date">
                        </div>
                        <div class="col-sm-4">
                            <label>Place Issued</label>
                            <input class="form form-control" name='passport_place_issued' value="{{old('passport_place_issued',$info->passport_place_issued)}}" type="text">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-4">
                            <label>ACR No.</label>
                            <input class="form form-control" name='acr_no' value="{{old('acr_no',$info->acr_no)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>Date Issued</label>
                            <input class="form form-control" name='acr_date_issued' value="{{old('acr_date_issued',$info->acr_date_issued)}}" type="date">
                        </div>
                        <div class="col-sm-4">
                            <label>Place Issued</label>
                            <input class="form form-control" name='acr_place_issued' value="{{old('acr_place_issued',$info->acr_place_issued)}}" type="text">
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab_2">
                    <label>Father</label>
                    <div class="form-group">
                        <div class="col-sm-8">
                            <label>Father's Name</label>
                            <input class="form form-control" name='father' value="{{old('father',$info->father)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Nationality</label>
                            <input class="form form-control" name='f_citizenship' value="{{old('f_citizenship',$info->f_citizenship)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>&nbsp;</label>
                            <div class='radio'>
                                <label><input name='f_is_living' @if($info->f_is_living==1)checked @endif value="1" type="radio">Living</label>
                                <label><input name='f_is_living' @if($info->f_is_living==0)checked @endif value="0" type="radio">Deceased</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-2">
                            <label>Religion</label>
                            <input class="form form-control" name='f_religion' value="{{old('f_religion',$info->f_religion)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>Highest Educational Attainment</label>
                            <input class="form form-control" name='f_education' value="{{old('f_education',$info->f_education)}}" type="text">
                        </div>
                        <div class="col-sm-6">
                            <label>School</label>
                            <input class="form form-control" name='f_school' value="{{old('f_school',$info->f_school)}}" type="text">
                        </div>
                    </div>
                    <div class='form-group'>
                        <div class="col-sm-3">
                            <label>Occupation</label>
                            <input class="form form-control" name='f_occupation' value="{{old('f_occupation',$info->f_occupation)}}" type="text">
                        </div>
                        <div class="col-sm-3">
                            <label>Company Name</label>
                            <input class="form form-control" name='f_company_name' value="{{old('f_company_name',$info->f_occupation)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>Company Address</label>
                            <input class="form form-control" name='f_company_address' value="{{old('f_company_address',$info->f_occupation)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Phone Number</label>
                            <input class="form form-control" name='f_company_number' value="{{old('f_company_number',$info->f_phone)}}" type="text">
                        </div>
                    </div>
                    <div class='form-group'>
                        <div class="col-sm-3">
                            <label>Telephone Number</label>
                            <input class="form form-control" name='f_phone' value="{{old('f_phone',$info->f_phone)}}" type="text">
                        </div>
                        <div class="col-sm-3">
                            <label>Cellphone Number</label>
                            <input class="form form-control" name='f_cell_no' value="{{old('f_cell_no',$info->f_cell_no)}}" type="text">
                        </div>
                        <div class="col-sm-3">
                            <label>Address</label>
                            <input class="form form-control" name='f_address' value="{{old('f_address',$info->f_address)}}" type="text">
                        </div>
                        <div class="col-sm-3">
                            <label>Email</label>
                            <input class="form form-control" name='f_email' value="{{old('f_email',$info->f_email)}}" type="email">
                        </div>
                    </div>
                    <div class='form-group'>
                        <div class="col-sm-3">
                            <label>Member in Any Organization</label>
                            <div class='radio'>
                                <label><input name='f_any_org' @if($info->f_any_org==1)checked @endif value="1" type="radio">Yes</label>
                                <label><input name='f_any_org' @if($info->f_any_org==0)checked @endif value="0" type="radio">No</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <label>Type of Organization</label>
                            <input class="form form-control" name='f_type_of_org' value="{{old('f_type_of_org',$info->f_type_of_org)}}" type="text">
                        </div>
                        <div class="col-sm-6">
                            <label>Area of expertise you can share with us</label>
                            <input class="form form-control" name='f_expertise' value="{{old('f_expertise',$info->f_expertise)}}" type="text">
                        </div>
                    </div>
                    <hr>
                    <label>Mother</label>
                    <div class="form-group">
                        <div class="col-sm-8">
                            <label>Mother's Name</label>
                            <input class="form form-control" name='mother' value="{{old('father',$info->mother)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Nationality</label>
                            <input class="form form-control" name='m_citizenship' value="{{old('m_citizenship',$info->m_citizenship)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>&nbsp;</label>
                            <div class='radio'>
                                <label><input name='m_is_living' @if($info->m_is_living==1)checked @endif value="1" type="radio">Living</label>
                                <label><input name='m_is_living' @if($info->m_is_living==0)checked @endif value="0" type="radio">Deceased</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-2">
                            <label>Religion</label>
                            <input class="form form-control" name='m_religion' value="{{old('m_religion',$info->m_religion)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>Highest Educational Attainment</label>
                            <input class="form form-control" name='m_education' value="{{old('m_education',$info->m_education)}}" type="text">
                        </div>
                        <div class="col-sm-6">
                            <label>School</label>
                            <input class="form form-control" name='m_school' value="{{old('m_school',$info->m_school)}}" type="text">
                        </div>
                    </div>
                    <div class='form-group'>
                        <div class="col-sm-3">
                            <label>Occupation</label>
                            <input class="form form-control" name='m_occupation' value="{{old('m_occupation',$info->m_occupation)}}" type="text">
                        </div>
                        <div class="col-sm-3">
                            <label>Company Name</label>
                            <input class="form form-control" name='m_company_name' value="{{old('m_company_name',$info->m_occupation)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>Company Address</label>
                            <input class="form form-control" name='m_company_address' value="{{old('m_company_address',$info->m_occupation)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Phone Number</label>
                            <input class="form form-control" name='m_company_number' value="{{old('m_company_number',$info->m_phone)}}" type="text">
                        </div>
                    </div>
                    <div class='form-group'>
                        <div class="col-sm-3">
                            <label>Telephone Number</label>
                            <input class="form form-control" name='m_phone' value="{{old('m_phone',$info->m_phone)}}" type="text">
                        </div>
                        <div class="col-sm-3">
                            <label>Cellphone Number</label>
                            <input class="form form-control" name='m_cell_no' value="{{old('m_cell_no',$info->m_cell_no)}}" type="text">
                        </div>
                        <div class="col-sm-3">
                            <label>Address</label>
                            <input class="form form-control" name='m_address' value="{{old('m_address',$info->m_address)}}" type="text">
                        </div>
                        <div class="col-sm-3">
                            <label>Email</label>
                            <input class="form form-control" name='m_email' value="{{old('m_email',$info->m_email)}}" type="email">
                        </div>
                    </div>
                    <div class='form-group'>
                        <div class="col-sm-3">
                            <label>Member in Any Organization</label>
                            <div class='radio'>
                                <label><input name='m_any_org' @if($info->m_any_org==1)checked @endif value="1" type="radio">Yes</label>
                                <label><input name='m_any_org' @if($info->m_any_org==0)checked @endif value="0" type="radio">No</label>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <label>Type of Organization</label>
                            <input class="form form-control" name='m_type_of_org' value="{{old('m_type_of_org',$info->m_type_of_org)}}" type="text">
                        </div>
                        <div class="col-sm-6">
                            <label>Area of expertise you can share with us</label>
                            <input class="form form-control" name='m_expertise' value="{{old('m_expertise',$info->m_expertise)}}" type="text">
                        </div>
                    </div>
                    <hr>
                    <div  class="form form-group">
                        <div class="col-sm-3">
                            <label>Parent's Civil Status at Present</label>
                            <select class="form form-control" name='parents_civil_status' type="text">
                                <option value=''>Select Civil Status</option>
                                <option @if ($info->parents_civil_status == 'Married') selected='' @endif>Married</option>
                                <option @if ($info->parents_civil_status == 'Separated') selected='' @endif>Separated</option>
                                <option @if ($info->parents_civil_status == 'Single') selected='' @endif>Single</option>
                                <option @if ($info->parents_civil_status == 'Widowed') selected='' @endif>Widowed</option>
                                <option @if ($info->parents_civil_status == 'Living-In') selected='' @endif>Living-In</option>
                                <option @if ($info->parents_civil_status == 'Anulled') selected='' @endif>Anulled</option>
                            </select>
                        </div>
                    </div>
                    <hr>
                    <label>If not living with parents:</label>
                    <div class="form-group">
                        <div class="col-sm-8">
                            <label>Guardian's Name</label>
                            <input class="form form-control" name='guardian' value="{{old('guardian',$info->guardian)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>Relation</label>
                            <input class="form form-control" name='g_relation' value="{{old('g_relation',$info->g_relation)}}" type="text">
                        </div>
                    </div>
                    <div class='form-group'>
                        <div class="col-sm-8">
                            <label>Address</label>
                            <input class="form form-control" name='g_address' value="{{old('g_address',$info->g_address)}}" type="text">
                        </div>
                        <div class="col-sm-4">
                            <label>Contact Number</label>
                            <input class="form form-control" name='g_contact_no' value="{{old('g_contact_no',$info->g_contact_no)}}" type="text">
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="tab_3">
                    <label>Present School</label>
                    <div class='form-group'>
                        <div class="col-sm-7">
                            <label>School</label>
                            <input class="form form-control" name='present_school' value="{{old('present_school',$info->present_school)}}" type="text">
                        </div>
                        <div class="col-sm-5">
                            <label>Address</label>
                            <input class="form form-control" name='present_school_address' value="{{old('present_school_address',$info->present_school_address)}}" type="text">
                        </div>
                    </div>
                    <div class='form-group'>
                        <div class="col-sm-5">
                            <label>Principal</label>
                            <input class="form form-control" name='present_principal' value="{{old('last_principal',$info->present_principal)}}" type="text">
                        </div>
                        <div class="col-sm-5">
                            <label>Guidance Counselor</label>
                            <input class="form form-control" name='present_guidance' value="{{old('present_guidance',$info->present_guidance)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Telephone Number</label>
                            <input class="form form-control" name='present_tel_no' value="{{old('present_tel_no',$info->present_tel_no)}}" type="text">
                        </div>
                    </div>
                    <hr>
                    <label>Primary School</label>
                    <div class='form-group'>
                        <div class="col-sm-7">
                            <label>School</label>
                            <input class="form form-control" name='primary' value="{{old('primary',$info->primary)}}" type="text">
                        </div>
                        <div class="col-sm-3">
                            <label>Address</label>
                            <input class="form form-control" name='primary_address' value="{{old('primary_address',$info->primary_address)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Year</label>
                            <input class="form form-control" name='primary_year' value="{{old('primary_year',$info->primary_year)}}" type="text">
                        </div>
                    </div>
                    <label>Grade School</label>
                    <div class='form-group'>
                        <div class="col-sm-7">
                            <label>School</label>
                            <input class="form form-control" name='gradeschool' value="{{old('gradeschool',$info->gradeschool)}}" type="text">
                        </div>
                        <div class="col-sm-3">
                            <label>Address</label>
                            <input class="form form-control" name='gradeschool_address' value="{{old('gradeschool_address',$info->gradeschool_address)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Year</label>
                            <input class="form form-control" name='gradeschool_year' value="{{old('gradeschool_year',$info->gradeschool_year)}}" type="text">
                        </div>
                    </div>
                    <label>High School</label>
                    <div class='form-group'>
                        <div class="col-sm-7">
                            <label>School</label>
                            <input class="form form-control" name='highschool' value="{{old('highschool',$info->highschool)}}" type="text">
                        </div>
                        <div class="col-sm-3">
                            <label>Address</label>
                            <input class="form form-control" name='highschool_address' value="{{old('highschool_address',$info->highschool_address)}}" type="text">
                        </div>
                        <div class="col-sm-2">
                            <label>Year</label>
                            <input class="form form-control" name='highschool_year' value="{{old('highschool_year',$info->highschool_year)}}" type="text">
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
        @if($status->status == env("FOR_APPROVAL"))
        <a href="{{url('admissionbed', array('approve_application', $user->idno))}}"><button class='btn btn-warning col-sm-12'>Approve Application Status</button></a>
        @endif
    </div>
</div>
@endsection
@section('footerscript')
<script>
function update_testing(id){
    array = {};
    array['idno'] = "{{$user->idno}}";
    array['testing_id'] = id;
    $.ajax({
        type: "GET",
        url: "/ajax/admissionbed/update_schedule",
        data: array,
        success: function (data) {
        }

    });
}
</script>
@endsection