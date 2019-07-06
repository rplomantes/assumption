<?php
$school_year = \App\CtrAdvisingSchoolYear::where('academic_type', 'College')->first();
?>
<?php
$courses = \App\GradeCollege::where('idno', $idno)->where('school_year', $school_year->school_year)->where('period', $school_year->period)->get();
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
        Courses Advised
        <small>A.Y. {{$school_year->school_year}} - {{$school_year->school_year+1}} {{$school_year->period}}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-home"></i> Home</a></li>
        <li>Advising</li>
        <li class="active">{{$idno}}</li>
    </ol>
</section>
@endsection
<?php
$user = \App\User::where('idno', $idno)->first();
?>
@section("maincontent")
<div class="row">
    <div class="col-sm-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">{{$user->firstname}} {{$user->middlename}} {{$user->lastname}}</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class='box-body'>
                <?php
                $grade_colleges = \App\GradeCollege::where('idno', $idno)->where('school_year', $school_year->school_year)->where('period', $school_year->period)->get();
                $units = 0;
                ?>
                    <div class='table-responsive'>
                @if(count($grade_colleges)>0)
                <table class="table table-hover table-condensed"><thead><tr><th>Code</th><th>Course Name</th><th>Lec</th><th>Lab</th></tr></thead><tbody>
                        @foreach($grade_colleges as $grade_college)
                        <?php
                        $units = $units + $grade_college->lec + $grade_college->lab;
                        ?>
                        <tr>
                            <td>{{$grade_college->course_code}}</td>
                            <td>{{$grade_college->course_name}}</td>
                            <td>{{$grade_college->lec}}</td>
                            <td>{{$grade_college->lab}}</td>
                        </tr>
                        @endforeach
                        <tr><td colspan="2"><strong>Total Units</strong></td><td colspan="2" align='center'><strong>{{$units}}</strong></td></tr>
                    </tbody></table>
                @else
                <div class="alert alert-danger">No Course Selected Yet!!</div>
                @endif
                    </div>
            </div>
        </div>
        
        @if(Auth::user()->accesslevel == env('DEAN'))        
        <div class='col-sm-6'>
            <a href='{{url('/')}}'><button class='btn btn-warning col-sm-12'><span class='fa fa-home'></span> RETURN HOME</button></a>
        </div>
        <div class='col-sm-6'>
            <a href='{{url('dean', array('advising','print_advising_slip',$idno))}}' target="_blank"><button class='btn btn-success col-sm-12'><span class='fa fa-print'></span> PRINT ADIVISING SLIP</button></a>
        </div>
        @else
        <div class='col-sm-4'>
            <a href='{{url('/')}}'><button class='btn btn-warning col-sm-12'><span class='fa fa-home'></span> RETURN HOME</button></a>
        </div>
        <div class='col-sm-4'>
            <a href='{{url('dean', array('advising','print_advising_slip',$idno))}}' target="_blank"><button class='btn btn-success col-sm-12'><span class='fa fa-print'></span> PRINT ADIVISING SLIP</button></a>
        </div>
        <div class='col-sm-4'>
            <a href='{{url('registrar_college', array('assessment',$idno))}}'><button class='btn btn-primary col-sm-12'> PROCEED TO ASSESSMENT</button></a>
        </div>
        @endif
        
    </div>
</div>
@endsection
@section("footerscript")
@endsection