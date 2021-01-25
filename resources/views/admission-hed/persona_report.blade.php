<?php

function getCoreValues($idno) {
    $value = ['awareness', 'commitment', 'kindness', 'simplicity', 'humility', 'integrity', 'oneness', 'nature', 'others'];
    $core_values = "";
    foreach ($value as $key) {

        if ($key == "others") {
            $is_one = \App\CollegeAboutYou::selectRaw("$key")->where('idno', $idno)->value("$key");
            if ($is_one != null) {
                $core_values = $core_values . ' Others: ' . "$is_one ";
            }
        } else {
            $is_one = \App\CollegeAboutYou::selectRaw("$key")->where('idno', $idno)->where("$key", 1)->value("$key");
            if ($is_one == 1) {
                $core_values = $core_values . '' . "$key ";
            }
        }
    }
    return $core_values;
}
?>

@extends('layouts.appadmission-hed')
@section('header')
<section class="content-header">
    <h1>
        Persona Report
    </h1>
    <ol class="breadcrumb">
        <li class="active"><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li class="active"><a href="/">Persona Report</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<div class="row">
    <div class="col-sm-12">

        <div class='form-group'>
            <div class='col-sm-2'>
                <label>School Year</label>
                <select class="form form-control select2" name="school_year" id='school_year'>
                    <option value="">Select School Year</option>
                    <option value="2020" @if ($school_year == 2020) selected = "" @endif>2020-2021</option>
                    <option value="2021" @if ($school_year == 2021) selected = "" @endif>2021-2022</option>
                    <option value="2022" @if ($school_year == 2022) selected = "" @endif>2022-2023</option>
                    <option value="2023" @if ($school_year == 2023) selected = "" @endif>2023-2024</option>
                    <option value="2024" @if ($school_year == 2024) selected = "" @endif>2024-2025</option>
                    <option value="2025" @if ($school_year == 2025) selected = "" @endif>2025-2026</option>
                </select>
            </div>
            <div class='col-sm-4'>
                <label>&nbsp;</label>
                <button formtarget="_blank" type='submit' id='view-button' class='col-sm-12 btn btn-success'><span>Change School Year/Period</span></button>
            </div>
        </div> 
    </div>
    <br>
    <br>
    <br>
    <br>
</div>
@if($school_year)
<div class="col sm-12">
    <div class="box">
        <div class="box-header"><div class="box-title">Report</div></div>
        <div class="box-body">
            <table class="table">
                <tr>
                    <td><strong>SY:{{$school_year}}-{{$school_year+1}}</strong></td>
                    <td><strong>Applicant ID</strong></td>
                    <td><strong>Name</strong></td>
                    <td><strong>Address</strong></td>
                    <td><strong>City</strong></td>
                    <td><strong>Birth Date</strong></td>
                    <td><strong>Preferred Course</strong></td>
                    <td><strong>Interest/Hobbies</strong></td>
                    <td><strong>Core Values</strong></td>
                    <td><strong>Goals</strong></td>
                    <td><strong>Challenges/Key Concerns</strong></td>
                    <td><strong>Preferred Comm Channel</strong></td>
                </tr>
                @foreach($applicants as $applicant)
                <?php $applying_for = \App\AdmissionHed::where('idno', $applicant->idno)->first(); ?>
                <tr>
                    <td></td>
                    <td>{{$applicant->idno}}</td>
                    <td>{{$applicant->getFullNameAttribute()}}</td>
                    <td>{{$applicant->street}}</td>
                    <td>{{$applicant->municipality}}</td>
                    <td>{{$applicant->birthdate}}</td>
                    <td>{{$applying_for->program_code}}</td>
                    <td>{{$applicant->interest}}</td>
                    <td>{{getCoreValues($applicant->idno)}}</td>
                    <td>{{$applicant->goals}}</td>
                    <td>{{$applicant->challenges}}</td>
                    <td>{{$applicant->com_channel}}</td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>
@endif

@endsection
@section('footerscript')
<script>
    $(document).ready(function () {
        $("#view-button").on('click', function (e) {
            document.location = "{{url('/admissions',array('persona_report'))}}" + '/' + $("#school_year").val();
        });
    });
</script>
@endsection