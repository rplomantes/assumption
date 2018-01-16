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
        Set Up Advising
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="#"> Advising</a></li>
        <li class="active"><a href="{{ url ('/registrar_college', array('advising','set_up'))}}"> Set Up</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<section class="content">
    <div class="row">
        <div class="col-sm-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"> Set Up</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body">
                    <form method="POST" action="{{url('registrar_college', array('advising', 'save_set_up'))}}" class="form form-horizontal">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <div class="col-sm-2">
                                <label>School Year</label>
                                <input type="text" maxlength="4" placeholder="YYYY" class="form form-control" id="school_year" name="school_year" value="{{$advising_school_year->school_year}}">
                            </div>
                            <div class="col-sm-2">
                                <label>Period</label>
                                <select type="text" class="form form-control" id="period" name="period">
                                    <option value="1st Semester" @if($advising_school_year->period == "1st Semester") selected="" @endif>1st Semester</option>
                                    <option value="2nd Semester" @if($advising_school_year->period == "2nd Semester") selected="" @endif>2nd Semester</option>
                                    <option value="Summer" @if($advising_school_year->period == "Summer") selected="" @endif>Summer</option>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label>Availability</label>
                                <select type="text" class="form form-control" id="availabilty" name="availability">
                                    <option value="1" @if($advising_school_year->is_available == 1) selected="" @endif>Open</option>
                                    <option value="0" @if($advising_school_year->is_available == 0) selected="" @endif>Close</option>
                                </select>
                            </div>
                            <div class="col-sm-2">
                                <label></label>
                                <input type="submit" value="Save" class="btn btn-success form form-control">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('footerscript')

@endsection