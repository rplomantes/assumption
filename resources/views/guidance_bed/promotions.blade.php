<?php $levels = \App\CtrAcademicProgram::distinct()->where('academic_type', 'BED')->orderBy('level', 'desc')->get(['level']); ?>
<?php $strands = \App\CtrAcademicProgram::distinct()->where('academic_type', 'BED')->orderBy('strand', 'asc')->get(['strand']); ?>
<?php
if(Auth::user()->accesslevel == env('REG_BE')){
$layout = "layouts.appbedregistrar";
} else {
$layout = "layouts.appguidance_bed";
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
        {{$user->idno}} - {{$user->lastname}}, {{$user->firstname}}
    </h1>
    <ol class="breadcrumb">
        <li class="active"><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li class="active"><a href="/">Promotions</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<!-- search form (Optional) -->
 @if (Session::has('message'))
            <div class="alert alert-success">{{ Session::get('message') }}</div>
        @endif 
<div class="col-md-4">
<form method="post" action="{{url('/guidance_bed/update_promotions')}}">
    {{ csrf_field() }}
    <input type="hidden" value="{{$idno}}" name="idno">
    <label>Level</label>
    <select class='form form-control' name="level">
        @foreach($levels as $level)
        <option @if($promotion->level == $level->level) selected="" @endif>{{$level->level}}</option>
        @endforeach
    </select>
    <br>
    <label>Strand</label>
    <select class='form form-control' name="strand">
        @foreach($strands as $strand)
        <option @if($promotion->strand == $strand->strand) selected="" @endif>{{$strand->strand}}</option>
        @endforeach
    </select>
    <label class="col-sm-12">&nbsp;</label>
    <input type="submit" value="Update Promotions!" class="btn btn-success col-sm-12">
</form>
</div>
<!-- /.search form -->

@endsection
@section('footerscript')
@endsection