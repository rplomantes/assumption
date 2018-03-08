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
        Student Record
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
                <a href="#" class="form form-control btn btn-success">Others</a>
            </div>
        </div>
        <div class="col-sm-12">
        <?php $school_years = \App\GradeCollege::distinct()->where('idno', $user->idno)->get(['school_year']); ?>
            @foreach ($school_years as $school_year)
            <?php $periods = \App\GradeCollege::distinct()->where('idno',$user->idno)->where('school_year', $school_year->school_year)->get(['period']); ?>
                @foreach ($periods as $period)
        <?php $grades= \App\GradeCollege::where('idno',$idno)->where('school_year', $school_year->school_year)->where('period', $period->period)->get(); ?>
            
                <table class="table table-striped">
                    {{$period->period}}, {{$school_year->school_year}} - {{$school_year->school_year+1}}
                <thead>
                <tr>
                    <th width="10%">Code</th>
                    <th width="60%">Description</th>
                    <th>Midterm</th>
                    <th>Finals</th>
                    <th>Final Grade</th>
                    <th>Grade Point</th>
                </tr>
                </thead>
                <tbody>
                @foreach($grades as $grade)
                <tr>
                    <td>{{$grade->course_code}}</td>
                    <td>{{$grade->course_name}}</td>
                    <td>{{$grade->midterm}}</td>
                    <td>{{$grade->finals}}</td>
                    <td>{{$grade->final_grade}}</td>
                    <td>{{$grade->grade_point}}</td>
                </tr>
                @endforeach
                @endforeach
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

@endsection
@section('footerscript')
@endsection