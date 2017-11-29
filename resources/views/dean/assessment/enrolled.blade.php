<?php
$user=  \App\User::where('idno',$idno)->first();
$grades = \App\GradeCollege::where('idno',$idno)->where('school_year',$status->school_year)
                ->where('period',$status->period)->get();

?>
@extends('layouts.appdean_college')
@section('content')

@endsection