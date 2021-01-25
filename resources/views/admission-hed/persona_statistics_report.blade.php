<?php

function getCount($school_year, $type) {
    $awareness = \App\CollegeAboutYou::selectRaw("sum(awareness) as count")->join('admission_heds', 'admission_heds.idno', 'college_about_yous.idno')->where('admission_heds.applying_for_sy', $school_year)->first();
    $commitment = \App\CollegeAboutYou::selectRaw("sum(commitment) as count")->join('admission_heds', 'admission_heds.idno', 'college_about_yous.idno')->where('admission_heds.applying_for_sy', $school_year)->first();
    $kindness = \App\CollegeAboutYou::selectRaw("sum(kindness) as count")->join('admission_heds', 'admission_heds.idno', 'college_about_yous.idno')->where('admission_heds.applying_for_sy', $school_year)->first();
    $simplicity = \App\CollegeAboutYou::selectRaw("sum(simplicity) as count")->join('admission_heds', 'admission_heds.idno', 'college_about_yous.idno')->where('admission_heds.applying_for_sy', $school_year)->first();
    $humility = \App\CollegeAboutYou::selectRaw("sum(humility) as count")->join('admission_heds', 'admission_heds.idno', 'college_about_yous.idno')->where('admission_heds.applying_for_sy', $school_year)->first();
    $integrity = \App\CollegeAboutYou::selectRaw("sum(integrity) as count")->join('admission_heds', 'admission_heds.idno', 'college_about_yous.idno')->where('admission_heds.applying_for_sy', $school_year)->first();
    $oneness = \App\CollegeAboutYou::selectRaw("sum(oneness) as count")->join('admission_heds', 'admission_heds.idno', 'college_about_yous.idno')->where('admission_heds.applying_for_sy', $school_year)->first();
    $nature = \App\CollegeAboutYou::selectRaw("sum(nature) as count")->join('admission_heds', 'admission_heds.idno', 'college_about_yous.idno')->where('admission_heds.applying_for_sy', $school_year)->first();
    $others = \App\CollegeAboutYou::distinct()->join('admission_heds', 'admission_heds.idno', 'college_about_yous.idno')->where('admission_heds.applying_for_sy', $school_year)->get(['college_about_yous.others']);

    switch ($type) {
        case "awareness":
            return $awareness;
            break;
        case "commitment":
            return $commitment;
            break;
        case "kindness":
            return $kindness;
            break;
        case "simplicity":
            return $simplicity;
            break;
        case "humility":
            return $humility;
            break;
        case "integrity":
            return $integrity;
            break;
        case "oneness":
            return $oneness;
            break;
        case "nature":
            return $nature;
            break;
        case "others":
            return $others;
            break;
    }
}
?>

@extends('layouts.appadmission-hed')
@section('header')
<section class="content-header">
    <h1>
        Persona Statistics Report
    </h1>
    <ol class="breadcrumb">
        <li class="active"><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li class="active"><a href="/">Persona Statistics Report</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<div class="col sm-12">
    <div class="box">
        <div class="box-header"><div class="box-title"></div></div>
        <div class="box-body">
            <table class="table">
                <tr>
                    <td><strong>Applying School Year</strong></td>
                    <td align="center"><strong>Awareness</strong></td>
                    <td align="center"><strong>Commitment</strong></td>
                    <td align="center"><strong>Kindness</strong></td>
                    <td align="center"><strong>Simplicity</strong></td>
                    <td align="center"><strong>Humility</strong></td>
                    <td align="center"><strong>Integrity</strong></td>
                    <td align="center"><strong>Oneness</strong></td>
                    <td align="center"><strong>Nature</strong></td>
                    <td align="center"><strong>Others</strong></td>
                </tr>
                @foreach ($school_years as $school_year)
                <tr>
                    <td>{{$school_year->applying_for_sy}}</td>
                    <td align="center"><?php echo $counts = getCount($school_year->applying_for_sy, "awareness")->count; ?></td>
                    <td align="center"><?php echo $counts = getCount($school_year->applying_for_sy, "commitment")->count; ?></td>
                    <td align="center"><?php echo $counts = getCount($school_year->applying_for_sy, "kindness")->count; ?></td>
                    <td align="center"><?php echo $counts = getCount($school_year->applying_for_sy, "simplicity")->count; ?></td>
                    <td align="center"><?php echo $counts = getCount($school_year->applying_for_sy, "humility")->count; ?></td>
                    <td align="center"><?php echo $counts = getCount($school_year->applying_for_sy, "integrity")->count; ?></td>
                    <td align="center"><?php echo $counts = getCount($school_year->applying_for_sy, "oneness")->count; ?></td>
                    <td align="center"><?php echo $counts = getCount($school_year->applying_for_sy, "nature")->count; ?></td>
                    <td align="center"><?php $others = getCount($school_year->applying_for_sy, "others"); ?>
                        @foreach ($others as $other)
                        {{$other->others}}<br>
                        @endforeach
                    </td>
                </tr>
                @endforeach
            </table>
        </div>
    </div>
</div>

@endsection
@section('footerscript')
@endsection