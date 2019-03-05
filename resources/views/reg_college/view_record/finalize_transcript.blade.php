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
        <form class="form form-horizontal" method="post" action='{{url('/registrar_college', array('view_transcript','print_transcript'))}}'>
            {{ csrf_field() }}
            <input type='hidden' name='idno' value='{{$user->idno}}'>
            <div class="col-md-12">
                <div class="box box-widget widget-user-2">
                    <div class="widget-user-header bg-yellow">
                        <table class="table table-condensed" width="100%">
                            <tr>
                                <td width='20%'>Student Name:</td>
                                <td><b>{{strtoupper($user->lastname)}}, {{strtoupper($user->firstname)}} {{strtoupper($user->middlename)}}</b></td>
                            </tr>
                            <tr>
                                <td>Student Number:</td>
                                <td><b>{{$user->idno}}</b></td>
                            </tr>
                            <tr>
                                <td>Course:</td>
                                <td><b>{{strtoupper($level->program_name)}}</b></td>
                            </tr>
                            <tr>
                                <td>Date of Admission:</td>
                                <td><b><input class="form form-control" type="date" name="date_of_admission" value='{{old('date_of_admission', $info->date_of_admission)}}'></b></td>
                            </tr>
                            <tr>
                                <td>Date and Place of Birth:</td>
                                <td><b>{{strtoupper(date('F d, Y',strtotime($info->birthdate)))}}, {{strtoupper($info->place_of_birth)}}</b></td>
                            </tr>
                            <tr>
                                <td>Citizenship:</td>
                                <td><b>{{strtoupper($info->nationality)}}</b></td>
                            </tr>
                            <tr>
                                <td>Father's Name:</td>
                                <td><b>{{strtoupper($info->father)}}</b></td>
                            </tr>
                            <tr>
                                <td>Mother's Name:</td>
                                <td><b>{{strtoupper($info->mother)}}</b></td>
                            </tr>
                            <tr>
                                <td>Address:</td>
                                <td><b>{{strtoupper($info->street)}} {{strtoupper($info->barangay)}} {{strtoupper($info->municipality)}}</br></td>
                            </tr>
                            <tr>
                                <td>Grade School:</td>
                                <td><b>{{strtoupper($info->gradeschool)}} {{strtoupper($info->gradeschool_address)}}</br></td>
                            </tr>
                            <tr>
                                <td>High School:</td>
                                <td><b>{{strtoupper($info->highschool)}} {{strtoupper($info->highschool_address)}}</br></td>
                            </tr>
                            <tr>
                                <td>Senior High School:</td>
                                <td><b>{{strtoupper($info->senior_highschool)}} {{strtoupper($info->senior_highschool_address)}}</br></td>
                            </tr>
                            <tr>
                                <td>Tertiary School:</td>
                                <td><b><input class="form form-control" type="text" name='tertiary' value="{{old('tertiary', $info->tertiary)}}" ></b></td>
                            </tr>
                            <tr>
                                <td>Degree Earned:</td>
                                <td><b>{{strtoupper($level->program_name)}}</b></td>
                            </tr>
                            <tr>
                                <td>Award:</td>
                                <td><b><input class="form form-control" type="text" name='award' value="{{old('award', $info->award)}}" ></b></td>
                            </tr>
                            <tr>
                                <td>Date of Graduation:</td>
                                <td><b><input class="form form-control" type="date" name="date_of_grad" value="{{old('date_of_grad', $info->date_of_grad)}}" ></b></td>
                            </tr>
                            <tr>
                                <td>S.O. Number:</td>
                                <td><b>EXEMPTED</b></td>
                            </tr>
                            <tr>
                                <td>Remarks:</td>
                                <td><b><input class='form form-control' type="text" name='remarks'value="{{old('remarks', $info->remarks)}}" ></b></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-12">
                <div class="form form-group">
                    <input type='submit' class='col-sm-12 btn btn-success' value='PRINT TRANSCRIPT OF RECORD'>
                    <!--<a target='_blank' href='{{url('registrar_college', array('view_transcript', 'print_transcript',$user->idno))}}'><button class="btn btn-success col-sm-12">PRINT TRANSCRIPT OF RECORD</button></a>-->            
                </div>
            </div>      
        </form>
    </div>
</section>

@endsection
@section('footerscript')
@endsection