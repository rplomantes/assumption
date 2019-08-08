<?php
    if(Auth::user()->accesslevel == env('ADMISSION_HED')){
    $layout = "layouts.appadmission-hed";
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

<link rel="stylesheet" href="{{ asset ('bower_components/select2/dist/css/select2.min.css')}}">
<section class="content-header">
    <h1>
        Admission
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="#"> Curriculum Management</a></li>
        <li class="active"><a href="{{ url ('/registrar_college', array('admission','new_student'))}}"> New Student</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<section class="content">
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
<!--Personal Info-->                    
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
                                <input class="form form-control" name='province' placeholder='Province/Metro Manila*' value="{{old('province')}}" type="text">
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
                            <div class="col-sm-3">
                                <label>Civil Status</label>
                                <select class="form form-control" name='civil_status' placeholder='Telephone Number' value="{{old('civil_status')}}" type="text">
                                    <option value="">Select Civil Status</option>
                                    <option value="Single">Single</option>
                                    <option value="Married">Married</option>
                                    <option value="Divorced">Divorced</option>
                                    <option value="Widowed">Widowed</option>
                                </select>   
                            </div>
                            <div class="col-sm-3">
                                <label>Nationality</label>
                                <input class="form form-control" name='nationality' placeholder='Nationality' value="{{old('nationality')}}" type="text">
                            </div>
                            <div class="col-sm-3">
                                <label>Religion</label>
                                <input class="form form-control" name='religion' placeholder='Religion' value="{{old('religion')}}" type="text">
                            </div>
                            <div class="col-sm-3">
                                <label>Local/Foreigner</label>
                                <select class="form form-control" name='is_foreign' value="{{old('is_alien')}}" type="text">
                                    <option value="">Select Local/Foreign</option>
                                    <option value="0" @if ( old('is_foreign') == 0) selected='' @endif >Local</option>
                                    <option value="1" @if ( old('is_foreign') == 1) selected='' @endif >Foreign</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-9">
                                <label>Last School Attended</label>
                                <input class="form form-control" name='last_school_attended' placeholder='Last School Attended' value="{{old('last_school_attended')}}" type="text">
                            </div>
                        <div class="col-sm-3">
                                <label>Applying for Scholarship</label>
                                <select class="form form-control" name='is_scholarship' type="text">
                                    <option value='No'>No</option>
                                    <option value='Yes'>Yes</option>
                                </select>
                            </div>
                        </div>

                        <hr>
                        <div class="form-group">
                        <div class="col-sm-4">
                            <label>Applying For</label>
                            <select class="form form-control" name='applying_for'>
                                    <option value=''>Senior Highschool</option>
                                    <option value=''>College</option>             
                                    <option value=''>GradSchool</option>
                            </select>        
                        </div>                              
                        
                            <div class="col-sm-4">
                                <label>Citizenship</label>
                                <select class="form form-control" name='citizenship' type="text">
                                    <option value=''>Filipino</option>
                                    <option value=''>Dual Citizen</option>
                                    <option value=''>Foreigner</option>                                   
                                </select>
                                </div>
                        </div>
                        <div class="form-group">
                        <div class="col-sm-6">
                                <label>Do you have now, or in the past, a condition/s which require or requires you to see a professional?</label>
                                <select class="form form-control" id="condition" name='see_professional' type="text">
                                    <option value=''>Select Condition*</option>
                                    <option value='No'>No</option>
                                    <option value='10'>Yes</option>
                                </select>    
                            </div>
                        </div>
                        <div class="form-group">
                        <div class="col-sm-6">
                            <select class="form form-control" id="conditionType" name='conditionType' type="text">
                            <label>Condition:</label>                            
                                    <option value=''>Select Condition*</option>>
                                    <option value='Medical'>Medical</option>
                                    <option value='Psychological'>Psychological</option>
                                    <option value='Learning_Disability'>Learning Disability</option>
                                    <option value='Emotional'>Emotional</option>
                                    <option value='Social'>Social</option>
                                    <option value='Others'>Others</option>
                            </select>  
                        </div>
                        <div class="col-sm-6" id="specifyCondition">
                                <label>Please specify condition and type of professional seen:</label>
                                <input class="form form-control" placeholder="Optional*" name='condition' type="text">
                        </div>    
                        </div>
                            <div class="form-group">
                                <div class="col-sm-6" >
                                    <label>Parent/Guardian</label>
                                    <select class="form form-control" id="typeofguardian" name="typeofguardian" type="text">
                                        <option value=''>Select Guardian*</option>
                                        <option value='1'>Parent</option>
                                        <option value='2'>Guardian</option> 
                                    </select>    
                                </div>
                            </div>                         
                
 
                            

<!--                        <div class="form-group">
                            <div class="col-sm-12">
                                <label>Program to enroll*</label>
                                <select class="form form-control select2" name='program_to_enroll' type="text">
                                    <option value="">Select a Program</option>
                                    @foreach ($programs as $program)
                                    <option value="{{$program->program_code}}">{{$program->program_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>-->
                        <hr>
                        
<!--Family Background-->
<!--Father Info-->
                        <div id="family_background">
                        <h4 class="box-title"><span class='fa fa-edit'></span>Family Background</h4>
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label>Name of Father</label>
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
                        </div><!-- form-group-->
                        <div class="form-group">
                            <div class="col-sm-6">
                                <label>Living?</label>
                                <select class="form form-control" name='living' type="text">
                                    <option value='Yes'>Yes</option>
                                    <option value='No' >No</option>
                                </select>    
                            </div>    
                            <div class="col-sm-4">
                                <label>Citizenship</label>
                                <select class="form form-control" name='citizenship' type="text">
                                    <option value=''>Filipino</option>
                                    <option value=''>Foreigner</option>                                   
                                </select>  
                            </div>     
                        </div><!-- form-group-->
                        
                        <div class="form-group">
                            <div class="col-sm-8">
                                <label>Current Address</label>
                                <input class="form form-control" name='street' placeholder='Street Address' value="{{old('street')}}" type="text">
                            </div>
                            <div class="col-sm-4">
                                <label>&nbsp;</label>
                                <input class="form form-control" name='barangay' placeholder='Barangay' value="{{old('barangay')}}" type="text">
                            </div>
                        </div><!-- form-group-->
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
                        </div><!-- form-group-->   
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
                        </div><!-- form-group-->
                        <div class="form-group">
                            <div class="col-sm-6">
                                <label>Education</label>
                                <input class="form form-control" name='father_school_attainment' placeholder='Highest Education Attainment' value="{{old('father_school_attainment')}}" type="text">
                            </div>
                            <div class="col-sm-6">
                                <label>Business Occupation</label>
                                <input class="form form-control" name='father_occupation' placeholder='Business/Occupation' value="{{old('father_occupation')}}" type="text">
                            </div>                        
                        </div><!-- form-group-->                              
                        <div class="form-group">
                            <div class="col-sm-6">
                                <label>Company Name</label>
                                <input class="form form-control" name='father_company' placeholder='Company Name' value="{{old('father_company')}}" type="text">
                            </div>
                            <div class="col-sm-6">
                                <label>Position</label>
                                <input class="form form-control" name='father_position' placeholder='Position' value="{{old('father_position')}}" type="text">
                            </div>                        
                        </div><!-- form-group-->    

                        
                        <hr>
<!--Mother Info-->
                   <div class="form-group">
                            <div class="col-sm-3">
                                <label>Name of Mother</label>
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
                            <div class="col-sm-6">
                                <label>Living?</label>
                                <select class="form form-control" name='living' type="text">
                                    <option value='Yes'>Yes</option>
                                    <option value='No' >No</option>
                                </select>    
                            </div>    
                            <div class="col-sm-4">
                                <label>Citizenship</label>
                                <select class="form form-control" name='citizenship' type="text">
                                    <option value=''>Filipino</option>
                                    <option value=''>Foreigner</option>                                   
                                </select>  
                            </div>     
                        </div>
                        
                        <div class="form-group">
                            <div class="col-sm-8">
                                <label>Current Address</label>
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
                            <div class="col-sm-6">
                                <label>Education</label>
                                <input class="form form-control" name='mother_school_attainment' placeholder='Highest Education Attainment' value="{{old('mother_school_attainment')}}" type="text">
                            </div>
                            <div class="col-sm-6">
                                <label>Business Occupation</label>
                                <input class="form form-control" name='mother_occupation' placeholder='Business/Occupation' value="{{old('mother_occupation')}}" type="text">
                            </div>                        
                        </div>                              
                        <div class="form-group">
                            <div class="col-sm-6">
                                <label>Company Name</label>
                                <input class="form form-control" name='mother_company' placeholder='Company Name' value="{{old('mother_company')}}" type="text">
                            </div>
                            <div class="col-sm-6">
                                <label>Position</label>
                                <input class="form form-control" name='mother_position' placeholder='Position' value="{{old('mother_position')}}" type="text">
                            </div>                        
                        </div> 
                        </div>
<!--for Guardian-->
                        <div id="guardian">
                        <h4 class="box-title"><span class='fa fa-edit'></span>Name of Guardian</h4>                            
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label>Name of Guardian</label>
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
                            <div class="col-sm-6">
                                <label>Living?</label>
                                <select class="form form-control" name='living' type="text">
                                    <option value='Yes'>Yes</option>
                                    <option value='No' >No</option>
                                </select>    
                            </div>    
                            <div class="col-sm-4">
                                <label>Citizenship</label>
                                <select class="form form-control" name='citizenship' type="text">
                                    <option value=''>Filipino</option>
                                    <option value=''>Foreigner</option>                                   
                                </select>  
                            </div>     
                        </div>
                        
                        <div class="form-group">
                            <div class="col-sm-8">
                                <label>Current Address</label>
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
                            <div class="col-sm-6">
                                <label>Relation to Applicant</label>
                                <input class="form form-control" name='relation_to_applicant' placeholder='Relation to Applicant' value="{{old('relation_to_applicant')}}" type="text">
                            </div>
                            <div class="col-sm-6">
                                <label>Business Occupation</label>
                                <input class="form form-control" name='guardian_occupation' placeholder='Business/Occupation' value="{{old('guardian_occupation')}}" type="text">
                            </div>                        
                        </div>                              
                        <div class="form-group">
                            <div class="col-sm-6">
                                <label>Company Name</label>
                                <input class="form form-control" name='guardian_company' placeholder='Company Name' value="{{old('guardian_company')}}" type="text">
                            </div>
                            <div class="col-sm-6">
                                <label>Position</label>
                                <input class="form form-control" name='guardian_position' placeholder='Position' value="{{old('guardian_position')}}" type="text">
                            </div>                        
                        </div> 
                    </div>
                    <div id="siblings">
                        <h4 class="box-title"><span class='fa fa-edit'></span>Siblings</h4>                            
                        <div class="form-group">
                            <div class="col-sm-3">
                                <label>Brothers and Sisters</label>
                                <input class="form form-control" name='firstname' placeholder='First Name*' value="{{old('firstname')}}" type="text">
                            </div>
                        </div>
                    </div><!--siblings-->
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

<script>
    $("#conditionType").hide();
    $("#specifyCondition").hide();
    $("#family_background").hide();
    $("#guardian").hide();

    
    $("#conditionType").change(function(){
    $("#specifyCondition").fadeIn();
    });
//    $('#typeofguardian').change(function(){
//        var value = $('#typeofguardian').val();
//        alert(value);
//    })
    
    $('#typeofguardian').on('change',function(){
        var value = $('#typeofguardian').val();
        if(value == 1){
            $("#family_background").fadeIn();
            $("#guardian").hide();
        }
        else{
            $("#family_background").hide();
            $("#guardian").fadeIn();            
        }
    });
    $('#condition').on('change', function(){
        var value = $('#condition').val();
        if(value == 10){
            $('#conditionType').fadeIn();
        }
        else{
            $('#conditionType').hide();
        }
    });
    
</script>
<script src="{{asset('bower_components/select2/dist/js/select2.full.min.js')}}"></script>
<script>
$(function () {
    $('.select2').select2();
});
</script>   

@endsection