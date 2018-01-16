<?php
$user = \App\User::where('idno', $idno)->first();
$status = \App\Status::where('idno', $idno)->first();
$student_info = \App\StudentInfo::where('idno', $idno)->first();
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
@extends("layouts.appdean_college")
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
                <h3 class="widget-user-username">{{$user->firstname}} {{$user->lastname}}</h3>
                <h5 class="widget-user-desc">{{$user->idno}}</h5>
            </div>
            <div class="box-footer no-padding">
                <ul class="nav nav-stacked">
                    @if(count($status)>0)
                    @if($status->is_new == "0")
                    <li><a href="#">Previous Status <span class="pull-right">Old Student</span></a></li>
                    <li><a href="#">Previous Program <span class="pull-right">{{$status->program_code}}</span></a></li>
                    <li><a href="#">Previous Level <span class="pull-right">{{$status->level}}</span></a></li>
                    <!--<li><a href="#">Previous Section <span class="pull-right">{{$status->section}}</span></a></li>-->
                    @else
                    <li><a href="#">Status <span class="pull-right">New Student</span></a></li>
                    <li><a href="#">Program <span class="pull-right">{{$status->program_code}}</span></a></li>
                    @endif
                    @else    
                    <li><a href="#">Status <span class="pull-right">New Student</span></a></li>
                    <li><a href="#">Program <span class="pull-right">{{$status->program_code}}</span></a></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    <div class="col-sm-12 col-md-8">
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
                            <select name="program_code" id="select_program" class="form-control select2" required="">
                                <option value="">Select Program</option>
                                @foreach($programs as $program)
                                <option value="{{$program->program_code}}" @if ($status->program_code == "$program->program_code") selected="" @endif>{{$program->program_code}}-{{$program->program_name}}</option>
                                @endforeach
                            </select>     
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">     
                            <label>Level</label>     
                            <select name="level" id="select_level" class="form-control select2" required="">
                                <option value="">Select Level</option>
                                <option value="1st Year" @if ($status->level == "1st Year") selected="" @endif>1st Year</option>
                                <option value="2nd Year" @if ($status->level == "2nd Year") selected="" @endif>2nd Year</option>
                                <option value="3rd Year" @if ($status->level == "3rd Year") selected="" @endif>3rd Year</option>
                                <option value="4th Year" @if ($status->level == "4th Year") selected="" @endif>4th Year</option>
                            </select>     
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">     
                            <label>Curriculum Year</label>     
                            <select id="select_curriculum_year" class="form-control select2" required="" @if ($student_info->curriculum_year != NULL) disabled="" @endif>
                                <option value="">Select Curriculum</option>
                                @foreach ($curriculum_years as $curriculum_year)
                                <option value="{{$curriculum_year->curriculum_year}}" @if ($student_info->curriculum_year == "$curriculum_year->curriculum_year") selected="" @endif>{{$curriculum_year->curriculum_year}}</option>
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
                    <div class="form-group curriculum_year">
                        <div class="col-md-2">   
                            <label>Curriculum Year</label>     
                            <select id="curriculum_year" class="form-control select2">
                                <option value="">Curriculum Year</option>
                                @foreach ($curriculum_years as $curriculum_year)
                                <option value="{{$curriculum_year->curriculum_year}}">{{$curriculum_year->curriculum_year}}</option>
                                @endforeach
                            </select>     
                        </div>
                        <div class="col-md-6 program_code">   
                            <label>Program</label>     
                            <select id="program_code" class="form-control select2">
                                <option value="">Select Program</option>
                                @foreach($programs as $program)
                                <option value="{{$program->program_code}}">{{$program->program_name}}</option>
                                @endforeach
                            </select>     
                        </div>
                        <div class="col-md-2 level">     
                            <label>Level</label>     
                            <select id="level" class="form-control select2">
                                <option value="">Select Level</option>
                                <option value="1st Year">1st Year</option>
                                <option value="2nd Year">2nd Year</option>
                                <option value="3rd Year">3rd Year</option>
                                <option value="4th Year">4th Year</option>
                            </select>     
                        </div>
                        <div class="col-md-2 period">     
                            <label>Period</label>     
                            <select id="period" class="form-control select2" onchange="get_course_offering(this.value,curriculum_year.value,level.value, program_code.value)">
                                <option value="">Select Period</option>
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
                <h3 class="box-title">Courses to Advise</h3>
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
                $grade_colleges = \App\Advising::where('idno', $idno)->where('school_year', $school_year->school_year)->where('period', $school_year->period)->get();
                $units = 0;
                ?>
                    <div class='table-responsive'>
                @if(count($grade_colleges)>0)
                <table class="table table-striped"><thead><tr><th>Course Code</th><th>Course Name</th><th>Units</th><th>Instructor</th></tr></thead><tbody>
                        @foreach($grade_colleges as $grade_college)
                        <?php
                        $units = $units + $grade_college->lec + $grade_college->lab;
                        ?>
                        <tr>
                            <td>{{$grade_college->course_code}}</td>
                            <td>{{$grade_college->course_name}}</td>
                            <td>{{$grade_college->lec+$grade_college->lab}}</td>
                            <td><button class="btn btn-danger" onclick="removecourse('{{$grade_college->id}}')"><span class="fa fa-minus-circle"></span></button></td></tr>
                        @endforeach
                        <tr><td colspan="2"><strong>Total Units</strong></td><td><strong>{{$units}}</strong></td></tr>
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
        <a><button onclick="confirm_advised(select_curriculum_year.value,'{{$user->idno}}', select_program.value, select_level.value)" class="col-sm-12 btn btn-warning">CONFIRM ASSESSMENT</button></a>
    </div>
</div>
@endsection
@section("footerscript")
<script>
    $(".program_code").hide();
    $(".level").hide();
    $(".period").hide();
    $(".section").hide();
    $(".save").hide();
    $(document).ready(function () {
    $(".curriculum_year").on("change", function (e) {
    $(".program_code").fadeIn();
    })
    $(".program_code").on("change", function (e) {
    $(".level").fadeIn();
    })
    $(".level").on("change", function (e) {
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
    function get_course_offering(period,curriculum_year,level, program_code){
    array = {};
    array['curriculum_year'] = curriculum_year;
    array['level'] = level;
    array['program_code'] = program_code;
    array['period'] = period;
    $.ajax({
    type: "GET",
            url: "/ajax/dean/advising/get_course_offering",
            data: array,
            success: function (data) {
            $('.course_offering').fadeIn();
            $('.tablecourse').html(data);
            $('.course_offered').fadeIn();
            }

    });
    }
    function add_to_course_offered(course_offering_id){
    array = {};
    array['course_offering_id'] = course_offering_id;
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

    function addallcourses(period,curriculum_year,level, program_code){
    array = {};
    array['idno'] = "{{$user->idno}}";
    array['school_year'] = {{$school_year->school_year}};
    array['period'] = "{{$school_year->period}}";
    array['program_code'] = program_code;
    array['level'] = level;
    array['curriculum_year'] = curriculum_year;
    array['course_period'] = period;
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
    function confirm_advised(curriculum_year,idno, program_code, level){
        window.location = "/dean/advising/confirm_advised/" + idno + "/" + program_code + "/" + level + "/" + curriculum_year; 
    }
</script>
@endsection