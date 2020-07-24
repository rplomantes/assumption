<?php
//$school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first()->school_year;
//$period = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first()->period;
?>

<?php
if (Auth::user()->accesslevel == env('DEAN')) {
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
        Batch Ranking
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="/"> Graduates</a></li>
        <li class="active">Batch Ranking</li>
    </ol>
</section>
@endsection
@section('maincontent')
<div class='col-sm-12'>
        <div class='form-horizontal'>
            <div class='form-group'>
                <div class='col-sm-4'>
                    <label>Date of Graduation</label>
                    <select class="form form-control" name="date_of_grad" id='date_of_grad' onchange="display_batch_ranking(this.value)">
                        <option value="">Select Date of Graduation</option>
                        @foreach($years as $year)
                        <option>{{$year->date_of_grad}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class='box'>
            <div class='box-body'>
                
                    <div class="col-sm-12">
                        <div id="display_list">
                        </div>
                    </div>
            </div>
        </div>
    </form>
</div>
@endsection
@section('footerscript')
<script>
    function display_batch_ranking(year){
        array = {};
        array['date_of_grad'] = year;
        $.ajax({
            type: "GET",
            url: "/ajax/registrar_college/graduates/batch_ranking/get_students",
            data: array,
            success: function (data) {
                $('#display_list').html(data);
            }
        });
    }
</script>
@endsection