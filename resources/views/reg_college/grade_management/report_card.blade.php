<?php
if(Auth::user()->accesslevel == env('DEAN')){
$layout = "layouts.appdean_college";
} else {
$layout = "layouts.appreg_college";
}
?>
<?php
$programs = \App\CtrAcademicProgram::distinct()->where('academic_type','College')->get(['program_code', 'program_name']);
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
        Report Card
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="/"> Grade Management</a></li>
        <li class="active"><a href="{{url('registrar_college', array('grade_management','report_card'))}}">Report Card</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<div class='col-sm-12'>
    <div class='box'>
        <div class='box-body'>
            <form class='form-horizontal' id="myForm" action='{{url('registrar_college', array('grade_management','open_close', 'submit'))}}' method='post'>
                {{ csrf_field() }}
                <div class='form-group'>
                    <div class='col-sm-2'>
                        <label>School Year</label>
                        <select name='school_year' class='form form-control select2'>
                            <option>School Year</option>
                            <option value="2017">2017-2018</option>
                                    <option value="2018">2018-2019</option>
                                    <option value="2019">2019-2020</option>
                                    <option value="2020">2020-2021</option>
                                    <option value="2021">2021-2022</option>
                        </select>
                    </div>
                    <div class='col-sm-2'>
                        <label>Period</label>
                        <select name='period' class='form form-control select2'>
                            <option>Period</option>
                            <option>1st Semester</option>
                            <option>2nd Semester</option>
                            <option>Summer</option>
                        </select>
                    </div>
                    <div class='col-sm-6'>
                        <label>Program</label>
                        <select name='program_code' class='form form-control select2'>
                            <option>Program</option>
                            @foreach ($programs as $program)
                            <option value='{{$program->program_code}}'>{{$program->program_code}}-{{$program->program_name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class='col-sm-2'>
                        <label>Level</label>
                        <select name='level' class='form form-control select2'>
                            <option>Level</option>
                            <option>1st Year</option>
                            <option>2nd Year</option>
                            <option>3rd Year</option>
                            <option>4th Year</option>
                        </select>
                    </div>
                    <div class='col-sm-8'>
                        <label class='col-sm-12'>&nbsp;</label>
                        <button class='btn btn-primary col-sm-12' type='button' onclick="generateCard(school_year.value, period.value, program_code.value, level.value)">Generate List</button>
                    </div>
                    <div class='col-sm-4'>
                        <label class='col-sm-12'>&nbsp;</label>
                        <input type="submit" class="btn btn-warning form-control" onclick="toPDF()" value="Print PDF">
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div id="result">
    </div>
</div>
@endsection
@section('footerscript')
<script>
    function generateCard(school_year,period,program_code,level) {
        array = {};
        array['school_year'] = school_year;
        array['period'] = period;
        array['program_code'] = program_code;
        array['level'] = level;
        $.ajax({
            type: "GET",
            url: "/ajax/registrar_college/grade_management/generate_card/",
            data: array,
            success: function (data) {
                $('#result').html(data);
            }
        });
    }
    function toPDF() {
        document.getElementById("myForm").action = "{{url('/registrar_college/grade_management/print_card_pdf')}}";
    }
</script>
@endsection