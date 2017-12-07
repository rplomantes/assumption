<?php
$programs = \App\CtrAcademicProgram::distinct()->where('academic_type', 'College')->get(['program_code', 'program_name']);
$user = \App\User::where('idno', $idno)->first();
?>
<?php
$school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first();
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
        Assessment
        <small>A.Y. {{$school_year->school_year}} - {{$school_year->school_year+1}} {{$school_year->period}}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li>Assessment</li>
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
                    <li><a href="#">Previous Section <span class="pull-right">{{$status->section}}</span></a></li>
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
    <div class="col-sm-8">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">Assessment</h3>
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
                        <div class="col-md-12">   
                            <label>Program</label>     
                            <select id="program_code" name="program_code" class="form-control select2">
                                <option value="">Select Program</option>
                                @foreach($programs as $program)
                                <option value="{{$program->program_code}}">{{$program->program_name}}</option>
                                @endforeach
                            </select>     
                        </div>
                    </div>
                    <div class="form form-group">
                        <div class="col-md-12 level">     
                            <label>Level</label>     
                            <select id="level" name="level" class="form-control select2" onchange="get_section(this.value, program_code.value)">
                                <option value="">Select Level</option>
                                <option value="1st Year">1st Year</option>
                                <option value="2nd Year">2nd Year</option>
                                <option value="3rd Year">3rd Year</option>
                                <option value="4th Year">4th Year</option>
                            </select>     
                        </div>
                    </div>
                    <div class="form form-group">
                        <div class="col-md-12 section">     
                            <label>Section</label>     
                            <select id="section" name="section" class="form-control select2">

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
                <h3 class="box-title">Course Offering</h3>
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
                <h3 class="box-title">Course Offered</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body tablecourse_offered">
                <?php
                $grade_colleges = \App\GradeCollege::where('idno', $idno)->where('school_year', $school_year->school_year)->where('period', $school_year->period)->get();
                $units = 0;
                ?>
                @if(count($grade_colleges)>0)
                <table class="table table-striped"><thead><tr><th>Course</th><th>Units</th><th>Schedule/Room</th><th>Instructor</th></tr></thead><tbody>
                        @foreach($grade_colleges as $grade_college)
                        <?php
                        $units = $units + $grade_college->lec + $grade_college->lab;
                        ?>
                        <tr>
                            <td>{{$grade_college->course_code}} - {{$grade_college->course_name}}</td>
                            <td>{{$grade_college->lec+$grade_college->lab}}</td>
                            <td>
                                <?php
                                $schedule3s = \App\ScheduleCollege::distinct()->where('course_offering_id', $grade_college->course_offering_id)->get(['time_start', 'time_end', 'room']);
                                ?>   
                                @foreach ($schedule3s as $schedule3)
                                {{$schedule3->room}}
                                @endforeach
                                <?php
                                $schedule2s = \App\ScheduleCollege::distinct()->where('course_offering_id', $grade_college->course_offering_id)->get(['time_start', 'time_end', 'room']);
                                ?>
                                @foreach ($schedule2s as $schedule2)
                                <?php
                                $days = \App\ScheduleCollege::where('course_offering_id', $grade_college->course_offering_id)->where('time_start', $schedule2->time_start)->where('time_end', $schedule2->time_end)->where('room', $schedule2->room)->get(['day']);
                                ?>
                                <!--                @foreach ($days as $day){{$day->day}}@endforeach {{$schedule2->time}} <br>-->
                                [@foreach ($days as $day){{$day->day}}@endforeach {{date('g:i A', strtotime($schedule2->time_start))}} - {{date('g:i A', strtotime($schedule2->time_end))}}]<br>
                                @endforeach
                            </td>
                            <?php
                            $offering_id = \App\CourseOffering::find($grade_college->course_offering_id);
                            $instructor = \App\User::where('idno', $offering_id->instructor_id)->first();

                            if (count($instructor) > 0) {
                                $data = $instructor->firstname . " " . $instructor->lastname . " " . $instructor->extensionname;
                            } else {
                                $data = "";
                            }
                            ?>
                            <td>{{$data}}</td>
                            <td><button class="btn btn-danger" onclick="removecourse('{{$grade_college->id}}')"><span class="fa fa-minus-circle"></span></button></td></tr>
                        @endforeach
                        <tr><td><strong>Total Units</strong></td><td colspan="4"><strong>{{$units}}</strong></td></tr>
                    </tbody></table>
                @else
                <div class="alert alert-danger">No Course Selected Yet!!</div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-sm-12 save">
        <!--href="{{url('dean', array('assessment','confirm_advised',$user->idno))}}"-->
        <a><button onclick="confirm_advised('{{$user->idno}}', program_code.value, level.value)" class="col-sm-12 btn btn-warning">CONFIRM ASSESSMENT</button></a>
    </div>
</div>
@endsection
@section("footerscript")
<script>
    $(".level").hide();
    $(".section").hide();
    $(".save").hide();
    $(document).ready(function () {
    $("#program_code").on("change", function (e) {
    $(".level").fadeIn();
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
            url:"/ajax/dean/assessment/get_offering_per_search",
            data:array,
            success:function(data){
            $('.course_offering').fadeIn();
            $('.tablecourse').html(data);
            $('.course_offered').fadeIn();
            }
    });
    }
    });
    function get_section(level, program_code){
    array = {};
    array['level'] = level;
    array['program_code'] = program_code;
    array['school_year'] = {{$school_year->school_year}};
    array['period'] = "{{$school_year->period}}";
    $.ajax({
    type: "GET",
            url: "/ajax/dean/assessment/get_section",
            data: array,
            success: function (data) {
            $('.section').fadeIn().html(data);
            }

    });
    }
    function get_course_offering(level, program_code, section){
    array = {};
    array['level'] = level;
    array['program_code'] = program_code;
    array['school_year'] = {{$school_year->school_year}};
    array['period'] = "{{$school_year->period}}";
    array['section'] = section;
    $.ajax({
    type: "GET",
            url: "/ajax/dean/assessment/get_course_offering",
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
            url: "/ajax/dean/assessment/add_to_course_offered",
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
            url:"/ajax/dean/assessment/remove_to_course_offered",
            data:array,
            success:function(data){
            $(".tablecourse_offered").html(data);
            $(".save").fadeIn();
            }
    });
    }
    }

    function addallcourses(level, section, program_code){
    array = {};
    array['idno'] = "{{$user->idno}}";
    array['school_year'] = {{$school_year->school_year}};
    array['period'] = "{{$school_year->period}}";
    array['program_code'] = program_code;
    array['level'] = level;
    array['section'] = section;
    //if( confirm("Are You Sure To Add All Courses?"){
    $.ajax({
    type:"GET",
            url:"/ajax/dean/assessment/add_all_courses",
            data:array,
            success:function(data){
            $(".tablecourse_offered").html(data);
            $(".save").fadeIn();
            }
    })
            //}
    }
    function confirm_advised(idno, program_code, level){
        window.location = "/dean/assessment/confirm_advised/" + idno + "/" + program_code + "/" + level; 
    }
</script>
@endsection