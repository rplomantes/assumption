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
        Tagging of Graduates
        <small>A.Y. {{$school_year}}-{{$school_year+1}}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="/"> Graduates</a></li>
        <li class="active">Tagging of Graduates</li>
    </ol>
</section>
@endsection
@section('maincontent')
<div class='col-sm-12'>

    <form class="form-horizontal" action="{{url('registrar_college', 'save_tagging_of_graduates')}}" method="POST">
        {{ csrf_field()}}
        <div class='form-horizontal'>
            <div class='form-group'>
                <div class='col-sm-2'>
                    <label>School Year</label>
                    <select class="form form-control select2" name="school_year" id='school_year'>
                        <option value="">Select School Year</option>
                        <option value="2018" @if ($school_year == 2018) selected = "" @endif>2018-2019</option>
                        <option value="2019" @if ($school_year == 2019) selected = "" @endif>2019-2020</option>
                        <option value="2020" @if ($school_year == 2020) selected = "" @endif>2020-2021</option>
                        <option value="2021" @if ($school_year == 2021) selected = "" @endif>2021-2022</option>
                    </select>
                </div>
                <div class='col-sm-4'>
                    <label>&nbsp;</label>
                    <button formtarget="_blank" type='submit' id='view-button' class='col-sm-12 btn btn-success'><span>Change School Year/Period</span></button>
                </div>
            </div>
            <div class="form-group">
                <div  class="col-sm-2">
                    <label>Date of Graduation</label>
                    <input type="date" name="date_of_grad" required="" class="form form-control">
                </div>
                <div class="col-sm-4">
                    <label><br></label>
                    <input type="submit" class="col-sm-12 btn btn-primary" value="Tag as Graduates">
                </div>
            </div>
        </div>
        <div class='box'>
            <div class='box-body'>
                <table class="table table-condensed table-bordered">
                    @if(count($graduates)>0)
                    <?php $counter = 1; ?>
                    <tr>
                        <th>#</th>
                        <th>ID Number</th>
                        <th>Name</th>
                        <th>Program</th>
                        <th>Last Semester Enrolled</th>
                        <th></th>
                    </tr>
                    @foreach($graduates as $grad)
                    <tr>
                        <td>{{$counter++}}.</td>
                        <td>{{$grad->idno}}</td>
                        <td>{{$grad->lastname}}, {{$grad->firstname}} {{$grad->middlename}}</td>
                        <td>{{$grad->program_code}}</td>
                        <td>
                            <?php
                            $period = \App\CollegeLevel::where('idno', $grad->idno)->where('school_year', $school_year)->orderBy('period', 'desc')->first();
                            ?>
                            @if($period->period == "1st Semester")
                    <f style="color:green;">{{$period->period}}</f>
                    @elseif($period->period == "2nd Semester")
                    <f style="color:red;">{{$period->period}}</f>
                    @elseif($period->period == "Summer")
                    <f style="color:blue;">{{$period->period}}</f>
                    @endif
                    </td>
                    <td>
                        @if($grad->date_of_grad == NULL)
                        <input type="checkbox" name="post[]" value="{{$grad->idno}}" checked/> 
                        @else
                        {{$grad->date_of_grad}}
                        @endif
                    </td>
                    </tr>
                    @endforeach
                    @else
                    <tr>
                        <td>No result found!!!</td>
                    </tr>
                    @endif
                </table>

            </div>
        </div>
    </form>
</div>
@endsection
@section('footerscript')
<script>
    $(document).ready(function () {
        $("#view-button").on('click', function (e) {
            document.location = "{{url('/registrar_college',array('graduates'))}}" + "/tagging/" + $("#school_year").val();
        });
    });
</script>
@endsection