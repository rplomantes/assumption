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
        Student's Transcript of Records
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li class="active"><a href="{{url('registrar_college', array('student_record', $idno))}}">View Record</a></li>
    </ol>
</section>
@endsection
@section('maincontent')

<section class="content">
    <div class="row">
        <div class="col-md-8">
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
                    <h5 class="widget-user-desc">
                        <?php
                        switch ($status->status) {
                            case 0:
                                echo "Not Yet Advised or Assessed For This School Year";
                                break;
                            case env("ADVISING"):
                                echo "Already Advised but Not Assessed Yet";
                                break;
                            case env("ASSESSED"):
                                echo "Assessed";
                                break;
                            case env("ENROLLED"):
                                echo "Enrolled";
                                break;
                            case 4:
                                echo "Dropped";
                        }
                        ?>
                    </h5>
                </div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <a href="#" class="form form-control btn btn-primary">Print Student Record</a>
            </div>
            <div class="form-group">
                <a href="{{url('registrar_college', array('view_info', $user->idno))}}" class="form form-control btn btn-success">View Student Information</a>
            </div>
            <div class="form-group">
                <a href="{{url('registrar_college', array('view_transcript', $user->idno))}}" class="form form-control btn btn-success">View Transcript</a>
            </div>
        </div>
        <div class="col-sm-12">
            <?php $pinnacle_sy = \App\CollegeGrades2018::distinct()->where('idno', $idno)->orderBy('school_year', 'asc')->get(['school_year']); ?>
            @if(count($pinnacle_sy)>0)
                @foreach ($pinnacle_sy as $pin_sy)
                    <?php $pinnacle_period = \App\CollegeGrades2018::distinct()->where('idno', $idno)->where('school_year', $pin_sy->school_year)->orderBy('period', 'asc')->get(['period']); ?>
                    @foreach($pinnacle_period as $pin_pr)
                        <?php $pinnacle_grades = \App\CollegeGrades2018::where('idno', $idno)->where('school_year', $pin_sy->school_year)->where('period', $pin_pr->period)->get(); ?>
                        <h4>{{$pin_sy->school_year}}-{{$pin_sy->school_year+1}}, {{$pin_pr->period}}</h4>
                            <table class="table table-striped table-condensed" width="100%">
                                <thead>
                                    <tr>
                                        <th width='5%'>Course Code</th>
                                        <th width='40%'>Course Name</th>
                                        <th width='10%'>Final Grade</th>
                                    </tr>
                                </thead>
                                <tbody>
                        @foreach($pinnacle_grades as $pin_grades)
                                    <tr>
                                        <td>{{$pin_grades->course_code}}</td>
                                        <td><?php $get_course_name = \App\Curriculum::where('course_code', $pin_grades->course_code)->first(); ?>
                                            @if(count($get_course_name)>0)
                                            {{$get_course_name->course_name}}
                                            @else
                                            <i style="color: red;">Course name not found</i>
                                            @endif</td>
                                        <td>{{$pin_grades->finals}}</td>
                                    </tr>
                        @endforeach
                                </tbody>
                            </table>
                    @endforeach
                @endforeach
                <?php $grades_sy = \App\GradeCollege::distinct()->where('idno', $idno)->orderBy('school_year', 'asc')->get(['school_year']); ?>
                @if(count($grades_sy)>0)
                    @foreach($grades_sy as $sy)
                        <?php $grades_pr = \App\GradeCollege::distinct()->where('idno', $idno)->where('school_year', $sy->school_year)->orderBy('period', 'asc')->get(['period']); ?>
                        @foreach ($grades_pr as $pr)
                            <?php $grades = \App\GradeCollege::where('idno', $idno)->where('school_year', $sy->school_year)->where('period', $pr->period)->get(); ?><h4>{{$sy->school_year}}-{{$sy->school_year+1}}, {{$pr->period}}</h4>
                            <table class="table table-striped table-condensed" width="100%">
                                <thead>
                                    <tr>
                                        <th width='5%'>Course Codes</th>
                                        <th width='40%'>Course Name</th>
                                        <th width='10%'>Final Grade</th>
                                    </tr>
                                </thead>
                                <tbody>
                            @foreach ($grades as $grade)
                                    <tr>
                                        <td>{{$grade->course_code}}</td>
                                        <td>{{$grade->course_name}}</td>
                                        <td>{{$grade->finals}}</td>
                                    </tr>
                            @endforeach
                                </tbody>
                            </table>
                        @endforeach
                    @endforeach
                @endif
            @else
            
                <?php $grades_sy = \App\GradeCollege::distinct()->where('idno', $idno)->orderBy('school_year', 'asc')->get(['school_year']); ?>
                @if(count($grades_sy)>0)
                    @foreach($grades_sy as $sy)
                        <?php $grades_pr = \App\GradeCollege::distinct()->where('idno', $idno)->where('school_year', $sy->school_year)->orderBy('period', 'asc')->get(['period']); ?>
                        @foreach ($grades_pr as $pr)
                            <?php $grades = \App\GradeCollege::where('idno', $idno)->where('school_year', $sy->school_year)->where('period', $pr->period)->get(); ?><h4>{{$sy->school_year}}-{{$sy->school_year+1}}, {{$pr->period}}</h4>
                            <table class="table table-striped table-condensed" width="100%">
                                <thead>
                                    <tr>
                                        <th width='5%'>Course Codes</th>
                                        <th width='40%'>Course Name</th>
                                        <th width='10%'>Final Grade</th>
                                    </tr>
                                </thead>
                                <tbody>
                            @foreach ($grades as $grade)
                                    <tr>
                                        <td>{{$grade->course_code}}</td>
                                        <td>{{$grade->course_name}}</td>
                                        <td>{{$grade->finals}}</td>
                                    </tr>
                            @endforeach
                                </tbody>
                            </table>
                        @endforeach
                    @endforeach
                @endif
            @endif
        </div>
    </div>
</section>

@endsection
@section('footerscript')
@endsection