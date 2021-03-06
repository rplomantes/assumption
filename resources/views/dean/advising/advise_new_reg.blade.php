<?php
$user = \App\User::where('idno', $idno)->first();
$status = \App\Status::where('idno', $idno)->first();
$admission_hed = \App\AdmissionHed::where('idno', $idno)->first();
$student_info = \App\StudentInfo::where('idno', $idno)->first();
//$programs = \App\CtrAcademicProgram::distinct()->where('academic_type', 'College')->where('department', $status->department)->get(['program_code', 'program_name']);
$programs = \App\CtrAcademicProgram::distinct()->where('academic_type', 'College')->get(['program_code', 'program_name']);
$curriculum_years = \App\Curriculum::distinct()->get(['curriculum_year']);
?>
<?php
$school_year = \App\CtrAdvisingSchoolYear::where('academic_type', 'College')->first();
?>
<?php
$file_exist = 0;
if (file_exists(public_path("images/" . $user->idno . ".jpg"))) {
    $file_exist = 1;
}
?>
<link rel="stylesheet" href="{{ asset ('bower_components/select2/dist/css/select2.min.css')}}">

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
        Advising
        <small>A.Y. {{$school_year->school_year}} - {{$school_year->school_year+1}} {{$school_year->period}}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-home"></i> Home</a></li>
        <li>Advising</li>
        <li class="active">{{$user->idno}}</li>
    </ol>
</section>
@endsection
@section("maincontent")
<div class="row">
    <div class="col-md-4">
        <!-- Widget: user widget style 1 -->
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
                <h3 class="widget-user-username">{{$user->firstname}} {{$user->lastname}} {{$user->middlename}}</h3>
                <h5 class="widget-user-desc">{{$user->idno}}</h5>
            </div>
            <div class="box-footer no-padding">
                <ul class="nav nav-stacked">
                    <li><a href="#">Status <span class="pull-right">New Student</span></a></li>
                    <li><a href="#">Admission Status <span class="pull-right">{{$admission_hed->admission_status}}</span></a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Student Data</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="form-horizontal">
                    <div class="form-group">
                        <div class="col-md-12">   
                            <label>Program</label>     
                            <select name="program_code" id="select_program" class="form-control select2" required="" onchange='get_curriculum_year(this.value)'>
                                <option value="">Select Program</option>
                                @foreach($programs as $program)
                                <option value="{{$program->program_code}}" @if ($admission_hed->program_code == $program->program_code) selected="" @endif>{{$program->program_code}}-{{$program->program_name}}</option>
                                @endforeach
                            </select>     
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">     
                            <label>Level</label>     
                            <select name="level" id="select_level" class="form-control select2" required="">
                                <option value="">Select Level</option>
                                @if ($school_year->period == "1st Semester")
                                <option value="1st Year" @if ($status->level == NULL) selected="" @endif>1st Year</option>
                                <option value="2nd Year" @if ($status->level == "1st Year") selected="" @endif>2nd Year</option>
                                <option value="3rd Year" @if ($status->level == "2nd Year") selected="" @endif>3rd Year</option>
                                <option value="4th Year" @if ($status->level == "3rd Year") selected="" @endif>4th Year</option>
                                @else
                                <option value="1st Year" @if ($status->level == "1st Year") selected="" @endif>1st Year</option>
                                <option value="2nd Year" @if ($status->level == "2nd Year") selected="" @endif>2nd Year</option>
                                <option value="3rd Year" @if ($status->level == "3rd Year") selected="" @endif>3rd Year</option>
                                <option value="4th Year" @if ($status->level == "4th Year") selected="" @endif>4th Year</option>
                                @endif
                                
                            </select>     
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">     
                            <label>Curriculum Year</label>     
                            <select id="select_curriculum_year" class="form-control select2" required="">
                                <option value="">Select Curriculum</option>
                                @foreach($curriculum_years as $curriculum_year)
                                <option>{{$curriculum_year->curriculum_year}}</option>
                                @endforeach
                            </select>     
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Search Courses</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body">
                <div class="form-horizontal">
                    <div class='form-group'>
                        <div class="col-sm-12">
                            <label>Search Course</label>
                            <input type="text" class="form form-control" id="search"> 
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-4">   
                            <label>Program</label>     
                            <select id="program_code" class="form-control select2">
                                <option value="null">Select Program</option>
                                @foreach($programs as $program)
                                <option value="{{$program->program_code}}">{{$program->program_name}}</option>
                                @endforeach
                            </select>     
                        </div>
                        <div class="col-md-4 level">     
                            <label>Level</label>     
                            <!--<select id="level" class="form-control" onchange="get_section(this.value, program_code.value)">-->
                            <select id="level" class="form-control">
                                <option value="null">Select Level</option>
                                <option value="1st Year">1st Year</option>
                                <option value="2nd Year">2nd Year</option>
                                <option value="3rd Year">3rd Year</option>
                                <option value="4th Year">4th Year</option>
                            </select>     
                        </div>
                        <div class="col-md-4 period">     
                            <label>Period</label>     
                            <select id="period" name="period" class="form-control" onchange="get_curricula(level.value, program_code.value, period.value, select_curriculum_year.value)">
                                <option value="null"></option>
                                <option value="1st Semester">1st Semester</option>
                                <option value="2nd Semester">2nd Semester</option>
                                <option value="Summer">Summer</option>
                            </select>    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-6 course_offering">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Curriculum</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body tablecourse">

            </div>
        </div>
    </div>
    <div class="col-sm-6 course_offered">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Courses Advised</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body tablecourse_offered">
                <?php
                $grade_colleges = \App\GradeCollege::where('idno', $idno)->where('school_year', $school_year->school_year)->where('period', $school_year->period)->get();
                $units = 0;
                ?>
                    <div class='table-responsive'>
                @if(count($grade_colleges)>0)
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Course Name</th>
                            <th>Lec</th>
                            <th>Lab</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($grade_colleges as $grade_college)
                        <?php
                        $units = $units + $grade_college->lec + $grade_college->lab;
                        $offering_ids = \App\CourseOffering::find($grade_college->course_offering_id);
                        ?>
                        <tr>
                            <td>{{$grade_college->course_code}}</td>
                            <td>{{$grade_college->course_name}}</td>
                            <td>{{$grade_college->lec}}</td>
                            <td>{{$grade_college->lab}}</td>
                            <td><button class="btn btn-danger" onclick="removecourse('{{$grade_college->id}}')"><span class="fa fa-minus-circle"></span></button></td></tr>
                        @endforeach
                        <tr><td><strong>Total Units</strong></td><td></td><td></td><td></td><td><strong>{{$units}}</strong></td></tr>
                    </tbody></table>
                @else
                <div class="alert alert-danger">No Course Selected Yet!!</div>
                @endif
                    </div>
            </div>
        </div>
    </div>
    <div class="col-sm-12 save">
        <!--href="{{url('dean', array('assessment','confirm_advised',$user->idno))}}"-->
        <a><button onclick="confirm_advised('{{$user->idno}}', select_program.value, select_level.value, select_curriculum_year.value, period.value)" class="col-sm-12 btn btn-warning">CONFIRM ADVISEMENT</button></a>
    </div>
</div>
@endsection
@section("footerscript")
<script>
    $(".level").hide();
    $(".period").hide();
    $(".save").hide();
    $(document).ready(function () {
        $("#program_code").on("change", function (e) {
            $(".period").hide();
            $(".level").fadeIn();
        })
        $("#level").on("change", function (e) {
            $(".period").fadeIn();
        })
    })
</script>
<script>

    $("#search").keypress(function(e){
    if (e.keyCode == 13){
    array = {}
    array['school_year'] = {{$school_year->school_year}};
    array['period'] = "{{$school_year->period}}";
    array['search'] = $("#search").val();
    $.ajax({
    type:"GET",
            url:"/ajax/dean/advising/get_offering_per_search",
            data:array,
            success:function(data){
            $('.course_offering').fadeIn();
            $('.tablecourse').html(data);
            $('.course_offered').fadeIn();
            }
    });
    }
    });
    function get_curricula(level, program_code, period, curriculum_year){
    array = {};
    array['level'] = level;
    array['program_code'] = program_code;
    array['school_year'] = {{$school_year->school_year}};
    array['period'] = "{{$school_year->period}}";
    array['curriculum_period'] = period;
    array['curriculum_year'] = curriculum_year;
    $.ajax({
    type: "GET",
            url: "/ajax/dean/advising/get_curricula",
            data: array,
            success: function (data) {
            $('.course_offering').fadeIn();
            $('.tablecourse').html(data);
            $('.course_offered').fadeIn();
            }

    });
    }
    function add_to_course_offered(curriculum_id){
    array = {};
    array['curriculum_id'] = curriculum_id;
    array['idno'] = "{{$user->idno}}";
    array['school_year'] = {{$school_year->school_year}};
    array['period'] = "{{$school_year->period}}";
    $.ajax({
    type: "GET",
            url: "/ajax/dean/advising/add_to_course_offered",
            data: array,
            success: function (data) {
            $('.tablecourse_offered').html(data);
            $(".save").fadeIn();
            }

    });
    }
    function removecourse(id){
    array = {};
    array['id'] = id;
    array['idno'] = "{{$user->idno}}";
    array['school_year'] = {{$school_year->school_year}};
    array['period'] = "{{$school_year->period}}";
    if (confirm("Are You Sure To Remove?")){
    $.ajax({
    type:"GET",
            url:"/ajax/dean/advising/remove_to_course_offered",
            data:array,
            success:function(data){
            $(".tablecourse_offered").html(data);
            $(".save").fadeIn();
            }
    });
    }
    }

    function addallcourses(level, curriculum_period, program_code, curriculum_year){
    array = {};
    array['idno'] = "{{$user->idno}}";
    array['school_year'] = {{$school_year->school_year}};
    array['period'] = "{{$school_year->period}}";
    array['program_code'] = program_code;
    array['level'] = level;
    array['curriculum_period'] = curriculum_period;
    array['curriculum_year'] = curriculum_year;
    //if( confirm("Are You Sure To Add All Courses?"){
    $.ajax({
    type:"GET",
            url:"/ajax/dean/advising/add_all_courses",
            data:array,
            success:function(data){
            $(".tablecourse_offered").html(data);
            $(".save").fadeIn();
            }
    })
            //}
    }

    function get_curriculum_year(program_code){
    array = {};
    array['program_code'] = program_code;
    $.ajax({
    type:"GET",
            url:"/ajax/dean/advising/get_curriculum",
            data:array,
            success:function(data){
            $("#select_curriculum_year").html(data);
            }
    })
    }
    
    function confirm_advised(idno, program_code, level, curriculum_year, section){
        window.location = "/dean/advising/confirm_advised/" + idno + "/" + program_code + "/" + level + "/" + curriculum_year + "/" + section; 
    }
</script>
@endsection
