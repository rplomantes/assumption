<?php
//$school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first()->school_year;
//$period = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first()->period;
?>

<?php
if (Auth::user()->accesslevel == env('DEAN')) {
    $layout = "layouts.appdean_college";
} else {
    $layout = "layouts.appreg_college";
}

function getCount($course_code, $school_year, $period, $grade) {
    if($grade == "TOTAL"){
        $count = \App\GradeCollege::distinct()->where('course_code', $course_code)->where('school_year', $school_year)->where('period', $period)->get(array('idno'));
    }else{
        $count = \App\GradeCollege::where('course_code', $course_code)->where('school_year', $school_year)->where('period', $period)->where('finals', $grade)->get();
    }
    if(count($count)==0){
        return "";
    }else{
        return (count($count));
    }
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
        Statistics of Grades
        <small>A.Y. {{$school_year}}-{{$school_year+1}} - {{$period}}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="/"> Grade Management</a></li>
        <li class="active"><a href="{{url('registrar_college', array('grade_management','statistics_of_grades'))}}">Statistics of Grades</a></li>
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
            <div class='col-sm-4'>
                <label>&nbsp;</label>
                <button formtarget="_blank" type='submit' id='view-button' class='col-sm-12 btn btn-success'><span>Change School Year/Period</span></button>
            </div>
        </div>    
    </div>
    @if (count($subjects)>0)
            <div class='col-sm-12'><div class='pull-right'><a target='_blank' href="{{url('registrar_college',array('print_statistics_of_grades', $school_year,$period))}}"><button class='btn btn-primary'>Print Grade</button></a></div></div>
    @foreach($subjects as $subject)
    <div class='col-sm-12'>
        <div class='box'>
            <div class='box-body'>
                <label>{{$subject->course_code}} - {{$subject->course_name}}</label>
                <table class="table table-condensed table-bordered">
                    <tr>
                        <td align='center'>PASSED</td>
                        <td align='center'>1.00</td>
                        <td align='center'>1.20</td>
                        <td align='center'>1.50</td>
                        <td align='center'>1.70</td>
                        <td align='center'>2.00</td>
                        <td align='center'>2.20</td>
                        <td align='center'>2.50</td>
                        <td align='center'>2.70</td>
                        <td align='center'>3.00</td>
                        <td align='center'>3.50</td>
                        <td align='center'>4.00</td>
                        <td align='center'>FA</td>
                        <td align='center'>INC</td>
                        <td align='center'>NA</td>
                        <td align='center'>NG</td>
                        <td align='center'>UD</td>
                        <td align='center'>W</td>
                        <td align='center'>AUDIT</td>
                        <td align='center'><strong>TOTAL</strong></td>
                    </tr>
                    <tr>
                        <td align='center'>{{getCount($subject->course_code,$school_year,$period,"PASSED")}}</td>
                        <td align='center'>{{getCount($subject->course_code,$school_year,$period,"1.00")}}</td>
                        <td align='center'>{{getCount($subject->course_code,$school_year,$period,"1.20")}}</td>
                        <td align='center'>{{getCount($subject->course_code,$school_year,$period,"1.50")}}</td>
                        <td align='center'>{{getCount($subject->course_code,$school_year,$period,"1.70")}}</td>
                        <td align='center'>{{getCount($subject->course_code,$school_year,$period,"2.00")}}</td>
                        <td align='center'>{{getCount($subject->course_code,$school_year,$period,"2.20")}}</td>
                        <td align='center'>{{getCount($subject->course_code,$school_year,$period,"2.50")}}</td>
                        <td align='center'>{{getCount($subject->course_code,$school_year,$period,"2.70")}}</td>
                        <td align='center'>{{getCount($subject->course_code,$school_year,$period,"3.00")}}</td>
                        <td align='center'>{{getCount($subject->course_code,$school_year,$period,"3.50")}}</td>
                        <td align='center'>{{getCount($subject->course_code,$school_year,$period,"4.00")}}</td>
                        <td align='center'>{{getCount($subject->course_code,$school_year,$period,"FA")}}</td>
                        <td align='center'>{{getCount($subject->course_code,$school_year,$period,"INC")}}</td>
                        <td align='center'>{{getCount($subject->course_code,$school_year,$period,"NA")}}</td>
                        <td align='center'>{{getCount($subject->course_code,$school_year,$period,"NG")}}</td>
                        <td align='center'>{{getCount($subject->course_code,$school_year,$period,"UD")}}</td>
                        <td align='center'>{{getCount($subject->course_code,$school_year,$period,"W")}}</td>
                        <td align='center'>{{getCount($subject->course_code,$school_year,$period,"AUDIT")}}</td>
                        <td align='center'><strong>{{getCount($subject->course_code,$school_year,$period,"TOTAL")}}</strong></td>
                    </tr>
                </table>

            </div>
        </div>
    </div>
    @endforeach
    @endif
</div>
@endsection
@section('footerscript')
<script>
    $(document).ready(function () {
        $("#view-button").on('click', function (e) {
            document.location = "{{url('/registrar_college',array('grade_management'))}}" + "/statistics_of_grades/" + $("#school_year").val() + "/" + $("#period").val();
        });
    });
</script>
@endsection