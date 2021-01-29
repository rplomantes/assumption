<?php
//$school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first()->school_year;
//$period = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first()->period;
?>
    
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
        Incomplete Grades
        <small>A.Y. {{$school_year}}-{{$school_year+1}} - {{$period}}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="/"> Grade Management</a></li>
        <li class="active"><a href="{{url('registrar_college', array('grade_management','incomplete_grades'))}}">Incomplete Grades</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<div class='col-sm-12'>
    <div class='form-horizontal'>
        <div class='form-group'>
            <div class='col-sm-2'>
                <label>School Year</label>
                <select class="form form-control select2" name="school_year" id='school_year'>
                    <option value="">Select School Year</option>
                <option value="2018" @if ($school_year == 2018) selected = "" @endif>2018-2019</option>
                <option value="2019" @if ($school_year == 2019) selected = "" @endif>2019-2020</option>
                <option value="2020" @if ($school_year == 2020) selected = "" @endif>2020-2021</option>
                <option value="2021" @if ($school_year == 2021) selected = "" @endif>2021-2022</option>
                </select>
            </div>
            <div class='col-sm-2'>
                <label>Period</label>
                <select class="form form-control select2" name="period" id='period'>
                    <option value="">Select Period</option>
                    <option value='1st Semester' @if ($period == "1st Semester") selected = "" @endif>1st Semester</option>
                    <option value='2nd Semester' @if ($period == "2nd Semester") selected = "" @endif>2nd Semester</option>
                    <option value='Summer' @if ($period == "Summer") selected = "" @endif>Summer</option>
                </select>    
            </div>
            <div class='col-sm-2'>
                <label>Midterm/Finals</label>
                <select class="form form-control select2" name="term" id='term'>
                    <option value="">Select</option>
                    <option value='midterm' @if ($term == "midterm") selected = "" @endif>Midterm</option>
                    <option value='finals' @if ($term == "finals") selected = "" @endif>Finals</option>
                </select>    
            </div>
            <div class='col-sm-2'>
                <label>Search For:</label>
                <select class="form form-control select2" name="type" id='type'>
                    <option value="">Select</option>
                    <option value='inc_ng' @if ($type == "inc_ng") selected = "" @endif>INC/NG</option>
                    <option value='blank' @if ($type == "blank") selected = "" @endif>Blank Grades</option>
                </select>    
            </div>
            
            <div class='col-sm-4'>
                <label>&nbsp;</label>
                <button formtarget="_blank" type='submit' id='view-button' class='col-sm-12 btn btn-success'><span>Change School Year/Period</span></button>
            </div>
        </div>    
    </div>
    <div class='box'>
        <div class='box-body'>
            <table class="table table-condensed table-bordered">
            @if (count($incomplete_grades)>0)
            <div class='col-sm-12'><div class='pull-right'><a target='_blank' href="{{url('registrar_college',array('print_grade_incomplete',$type, $school_year,$period,$term))}}"><button class='btn btn-primary'>Print Grade</button></a></div></div>
            <?php $counter = 1; ?>
            <tr>
                <th>#</th>
                <th>ID Number</th>
                <th>Name</th>
                <th>Course Code</th>
                <th>Course Name</th>
                <th>Grade</th>
                <th>Section</th>
                <th>Instructor</th>
            </tr>
            @foreach($incomplete_grades as $grade)
            
                    <?php
                $offering_id = \App\CourseOffering::find($grade->course_offering_id);
                ?>
            @if($offering_id)
            <tr>
                <td>{{$counter++}}.</td>
                <td>{{$grade->idno}}</td>
                <td>{{$grade->lastname}}, {{$grade->firstname}} {{$grade->middlename}}</td>
                <td>{{$grade->course_code}}</td>
                <td>{{$grade->course_name}}</td>
                <td><strong>
                    @if($term == "midterm")
                        {{$grade->midterm}}
                    @elseif($term == "finals")
                        {{$grade->finals}}
                    @endif
                    
                    </strong>
                </td>
                <td>
                    
                    {{$offering_id->section_name}}
                </td>
                <td>
                    <?php
                $offering_id = \App\CourseOffering::find($grade->course_offering_id);
                    $schedule_instructor = \App\ScheduleCollege::distinct()->where('schedule_id', $offering_id->schedule_id)->get(['instructor_id']);

                    foreach($schedule_instructor as $get){
                        if ($get->instructor_id != NULL){
                            $instructor = \App\User::where('idno', $get->instructor_id)->first();
                            echo "$instructor->firstname $instructor->lastname $instructor->extensionname";
                        } else {
                        echo "";
                        }
                    }
                ?>
                </td>
            </tr>
            @endif
            @endforeach
            
            @else
            <tr>
                <td>No result found!!!</td>
            </tr>
            @endif
            </table>
            
        </div>
    </div>
</div>
@endsection
@section('footerscript')
<script>
    $(document).ready(function(){
      $("#view-button").on('click',function(e){
        document.location="{{url('/registrar_college',array('grade_management'))}}" + '/' + $("#type").val() + "/" + $("#school_year").val() + "/" + $("#period").val() + "/" + $("#term").val();
      });
    });
</script>
@endsection