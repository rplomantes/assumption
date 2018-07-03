<?php
$file_exist = 0;
if (file_exists(public_path("images/PICTURES/" . $user->idno . ".jpg"))) {
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
       <div class="col-md-12">
            <!-- Widget: user widget style 1 -->
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
        <div class="col-sm-2">
            <a href="{{url('registrar_college', array('student_record', $user->idno))}}" class="btn btn-primary col-sm-12">Curriculum Record</a>
        </div>
        <div class="col-sm-2">
            <a href="{{url('registrar_college', array('view_info', $user->idno))}}" class="btn btn-success col-sm-12">Student Information</a>
        </div>
        <div class="col-sm-2">
            <a href="{{url('registrar_college', array('view_transcript', $user->idno))}}" class="btn btn-success col-sm-12">Transcript of Records</a>
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
                        <th width='10%'>Completion</th>
                        <th width='10%'>Final Grade</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pinnacle_grades as $pin_grades)
                    <tr>
                        <td>{{$pin_grades->course_code}}</td>
                        <td>{{$pin_grades->course_name}}</td>
                        <td>
                            <select class="grade" name="completion[{{$pin_grades->id}}]" id="completion" onchange="change_completion(this.value, '{{$pin_grades->id}}', '{{$pin_grades->idno}}', 'old')">
                                <option></option>
                                <option @if ($pin_grades->completion == "PASSED") selected='' @endif>PASSED</option>
                                <option @if ($pin_grades->completion == 1.00) selected='' @endif>1.00</option>
                                <option @if ($pin_grades->completion == 1.20) selected='' @endif>1.20</option>
                                <option @if ($pin_grades->completion == 1.50) selected='' @endif>1.50</option>
                                <option @if ($pin_grades->completion == 1.70) selected='' @endif>1.70</option>
                                <option @if ($pin_grades->completion == 2.00) selected='' @endif>2.00</option>
                                <option @if ($pin_grades->completion == 2.20) selected='' @endif>2.20</option>
                                <option @if ($pin_grades->completion == 2.50) selected='' @endif>2.50</option>
                                <option @if ($pin_grades->completion == 2.70) selected='' @endif>2.70</option>
                                <option @if ($pin_grades->completion == 3.00) selected='' @endif>3.00</option>
                                <option @if ($pin_grades->completion == 3.50) selected='' @endif>3.50</option>
                                <option @if ($pin_grades->completion == 4.00) selected='' @endif>4.00</option>
                                <option @if ($pin_grades->completion == "FAILED") selected='' @endif>FAILED</option>
                                <option @if ($pin_grades->completion == "FA") selected='' @endif>FA</option>
                                <option @if ($pin_grades->completion == "INC") selected='' @endif>INC</option>
                                <option @if ($pin_grades->completion == "NA") selected='' @endif>NA</option>
                                <option @if ($pin_grades->completion == "NG") selected='' @endif>NG</option>
                                <option @if ($pin_grades->completion == "UD") selected='' @endif>UD</option>
                                <option @if ($pin_grades->completion == "W") selected='' @endif>W</option>
                                <option @if ($pin_grades->completion == "AUDIT") selected='' @endif>AUDIT</option>
                            </select>
                        </td>
                        <td>
                            <select class="grade" name="finals[{{$pin_grades->id}}]" id="finals" onchange="change_finals(this.value, '{{$pin_grades->id}}', '{{$pin_grades->idno}}', 'old')">
                                <option></option>
                                <option @if ($pin_grades->finals == "PASSED") selected='' @endif>PASSED</option>
                                <option @if ($pin_grades->finals == 1.00) selected='' @endif>1.00</option>
                                <option @if ($pin_grades->finals == 1.20) selected='' @endif>1.20</option>
                                <option @if ($pin_grades->finals == 1.50) selected='' @endif>1.50</option>
                                <option @if ($pin_grades->finals == 1.70) selected='' @endif>1.70</option>
                                <option @if ($pin_grades->finals == 2.00) selected='' @endif>2.00</option>
                                <option @if ($pin_grades->finals == 2.20) selected='' @endif>2.20</option>
                                <option @if ($pin_grades->finals == 2.50) selected='' @endif>2.50</option>
                                <option @if ($pin_grades->finals == 2.70) selected='' @endif>2.70</option>
                                <option @if ($pin_grades->finals == 3.00) selected='' @endif>3.00</option>
                                <option @if ($pin_grades->finals == 3.50) selected='' @endif>3.50</option>
                                <option @if ($pin_grades->finals == 4.00) selected='' @endif>4.00</option>
                                <option @if ($pin_grades->finals == "FAILED") selected='' @endif>FAILED</option>
                                <option @if ($pin_grades->finals == "FA") selected='' @endif>FA</option>
                                <option @if ($pin_grades->finals == "INC") selected='' @endif>INC</option>
                                <option @if ($pin_grades->finals == "NA") selected='' @endif>NA</option>
                                <option @if ($pin_grades->finals == "NG") selected='' @endif>NG</option>
                                <option @if ($pin_grades->finals == "UD") selected='' @endif>UD</option>
                                <option @if ($pin_grades->finals == "W") selected='' @endif>W</option>
                                <option @if ($pin_grades->finals == "AUDIT") selected='' @endif>AUDIT</option>
                            </select>
                        </td>
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
                        <th width='10%'>Completion</th>
                        <th width='10%'>Final Grade</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($grades as $grade)
                    <tr>
                        <td>{{$grade->course_code}}</td>
                        <td>{{$grade->course_name}}</td>
                        <td>
                            <select class="grade" name="completion[{{$grade->id}}]" id="completion" onchange="change_completion(this.value, '{{$grade->id}}', '{{$grade->idno}}', 'new')">
                                <option></option>
                                <option @if ($grade->completion == "PASSED") selected='' @endif>PASSED</option>
                                <option @if ($grade->completion == 1.00) selected='' @endif>1.00</option>
                                <option @if ($grade->completion == 1.20) selected='' @endif>1.20</option>
                                <option @if ($grade->completion == 1.50) selected='' @endif>1.50</option>
                                <option @if ($grade->completion == 1.70) selected='' @endif>1.70</option>
                                <option @if ($grade->completion == 2.00) selected='' @endif>2.00</option>
                                <option @if ($grade->completion == 2.20) selected='' @endif>2.20</option>
                                <option @if ($grade->completion == 2.50) selected='' @endif>2.50</option>
                                <option @if ($grade->completion == 2.70) selected='' @endif>2.70</option>
                                <option @if ($grade->completion == 3.00) selected='' @endif>3.00</option>
                                <option @if ($grade->completion == 3.50) selected='' @endif>3.50</option>
                                <option @if ($grade->completion == 4.00) selected='' @endif>4.00</option>
                                <option @if ($grade->completion == "FAILED") selected='' @endif>FAILED</option>
                                <option @if ($grade->completion == "FA") selected='' @endif>FA</option>
                                <option @if ($grade->completion == "INC") selected='' @endif>INC</option>
                                <option @if ($grade->completion == "NA") selected='' @endif>NA</option>
                                <option @if ($grade->completion == "NG") selected='' @endif>NG</option>
                                <option @if ($grade->completion == "UD") selected='' @endif>UD</option>
                                <option @if ($grade->completion == "W") selected='' @endif>W</option>
                                <option @if ($grade->completion == "AUDIT") selected='' @endif>AUDIT</option>
                            </select>
                        </td>
                        <td>
                            <select class="grade" name="finals[{{$grade->id}}]" id="finals" onchange="change_finals(this.value, '{{$grade->id}}', '{{$grade->idno}}', 'new')">
                                <option></option>
                                <option @if ($grade->finals == "PASSED") selected='' @endif>PASSED</option>
                                <option @if ($grade->finals == 1.00) selected='' @endif>1.00</option>
                                <option @if ($grade->finals == 1.20) selected='' @endif>1.20</option>
                                <option @if ($grade->finals == 1.50) selected='' @endif>1.50</option>
                                <option @if ($grade->finals == 1.70) selected='' @endif>1.70</option>
                                <option @if ($grade->finals == 2.00) selected='' @endif>2.00</option>
                                <option @if ($grade->finals == 2.20) selected='' @endif>2.20</option>
                                <option @if ($grade->finals == 2.50) selected='' @endif>2.50</option>
                                <option @if ($grade->finals == 2.70) selected='' @endif>2.70</option>
                                <option @if ($grade->finals == 3.00) selected='' @endif>3.00</option>
                                <option @if ($grade->finals == 3.50) selected='' @endif>3.50</option>
                                <option @if ($grade->finals == 4.00) selected='' @endif>4.00</option>
                                <option @if ($grade->finals == "FAILED") selected='' @endif>FAILED</option>
                                <option @if ($grade->finals == "FA") selected='' @endif>FA</option>
                                <option @if ($grade->finals == "INC") selected='' @endif>INC</option>
                                <option @if ($grade->finals == "NA") selected='' @endif>NA</option>
                                <option @if ($grade->finals == "NG") selected='' @endif>NG</option>
                                <option @if ($grade->finals == "UD") selected='' @endif>UD</option>
                                <option @if ($grade->finals == "W") selected='' @endif>W</option>
                                <option @if ($grade->finals == "AUDIT") selected='' @endif>AUDIT</option>
                            </select>
                        </td>
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
        <div class="form form-group">
            <div class='col-sm-12'>
            <!--<button class="col-sm-12 btn btn-success "><span></span>PRINT TRANSCRIPT OF RECORD</button>-->
            <a target='_blank' href='{{url('registrar_college', array('view_transcript', 'finalize_transcript',$user->idno))}}'><button class="btn btn-success col-sm-12">FINALIZE TRANSCRIPT OF RECORD</button></a>            
            </div>
        </div>    
        </div>   
    </div>
</section>

@endsection
@section('footerscript')
<script>
    function change_finals(grade, grade_id, idno, stat) {
    array = {};
    array['grade'] = grade;
    array['grade_id'] = grade_id;
    array['idno'] = idno;
    array['stat'] = stat;
    $.ajax({
    type: "GET",
            url: "/ajax/registrar_college/grades/change_finals/" + idno,
            data: array,
            success: function () {
            }
    });
    }
    function change_completion(grade, grade_id, idno, stat) {
    array = {};
    array['grade'] = grade;
    array['grade_id'] = grade_id;
    array['idno'] = idno;
    array['stat'] = stat;
    $.ajax({
    type: "GET",
            url: "/ajax/registrar_college/grades/change_completion/" + idno,
            data: array,
            success: function () {
            }
    });
    }
</script>
@endsection