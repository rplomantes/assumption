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
    <h1 style="color: red">
        <b>{{strtoupper($user->idno)}} - {{strtoupper($user->lastname)}}, {{strtoupper($user->firstname)}}</b>
        <small>A.Y. {{$school_year->school_year}} - {{$school_year->school_year+1}} {{$school_year->period}}</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li><a href="#"> Adding/Dropping</a></li>
        <li class="active"><a href="{{ url ('/registrar_college', array('adding_dropping',$idno))}}"> {{$idno}}</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<section class="content">
    <div class="col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-title">Adding/Dropping</div>
            </div>
            <div class="box-body" id="show_adding_dropping">

                @if(count($adding_droppings)>0)
                <table class="table table-condensed">
                    <thead>
                        <tr>
                            <th>Course Code</th>
                            <th>Description</th>
                            <th>Lec</th>
                            <th>Lab</th>
                            <th>SRF</th>
                            <th>Lab Fee</th>
                            <th>Action</th>
                            <th>Remove</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($adding_droppings as $course)
                        <tr>
                            <td>{{$course->course_code}}</td>
                            <td>{{$course->course_name}}</td>
                            <td>{{$course->lec}}</td>
                            <td>{{$course->lab}}</td>
                            <td>{{$course->srf}}</td>
                            <td>{{$course->lab_fee}}</td>
                            <td>{{$course->action}}</td>
                            <td><a href="{{url('registrar_college',array('remove_adding_dropping',$idno,$course->id))}}">Remove</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @endif
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="box">
            <div class="box-header">
                <div class="box-title">Courses Enrolled</div>
            </div>
            <div class="box-body">
                <table class="table table-condensed">
                    <thead>
                        <tr>
                            <th>Code</th>
                            <th>Description</th>
                            <th>Lec</th>
                            <th>Lab</th>
                            <th>Drop</th>
                        </tr>
                        @if(count($grades)>0)
                        @foreach ($grades as $grade)
                        <tr>
                            <td>{{$grade->course_code}}</td>
                            <td>{{$grade->course_name}}</td>
                            <td>{{$grade->lec}}</td>
                            <td>{{$grade->lab}}</td>
                            <td><a href="javascript:void(0)" onclick="drop_course('{{$idno}}','{{$grade->id}}')">Drop</a></td>
                        </tr>
                        @endforeach
                        @endif
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="box">
            <div class="box-header">
                <div class="box-title">Add Course</div>
            </div>
            <div class="box-body">
                <input type="text" id="search" class="form-control" placeholder="Search...">
            </div>
            <div class="box-body" id="result">

            </div>
        </div>
    </div>
    <div class="col-sm-12">
        <div class="col-sm-6">
        <a href="{{url('/registrar_college', array('process_adding_dropping','wo', $idno))}}"><button class="btn btn-warning col-sm-12">Process without Adding/Dropping Fee</button></a>
        </div>
        <div class="col-sm-6">
        <a href="{{url('/registrar_college', array('process_adding_dropping','w', $idno))}}"><button class="btn btn-success col-sm-12">Process with Adding/Dropping Fee</button></a>
        </div>
    </div>
</section>

@endsection
@section('footerscript')
<script type="text/javascript">
    $(document).ready(function () {
        $("#search").keypress(function (e) {
            var theEvent = e || window.event;
            var key = theEvent.keyCode || theEvent.which;
            var array = {};
            array['search'] = $("#search").val();
            array['idno'] = "{{$idno}}";
            if (key == 13) {
                $.ajax({
                    type: "GET",
                    url: "/ajax/registrar_college/adding_dropping/search_offer",
                    data: array,
                    success: function (data) {
                        $("#result").html(data);
                        $("#search").val("");
                    }
                });
            }
        })
    })
    function add_course(idno, course_code) {
        array = {};
        array['idno'] = idno;
        array['course_code'] = course_code;
        $.ajax({
            type: "GET",
            url: "/ajax/registrar_college/adding_dropping/adding",
            data: array,
            success: function (data) {
                $("#result").html(data);
                show_adding_dropping(idno);
            }

        });
    }
    function drop_course(idno, course_id) {
        array = {};
        array['idno'] = idno;
        array['course_id'] = course_id;
        $.ajax({
            type: "GET",
            url: "/ajax/registrar_college/adding_dropping/dropping",
            data: array,
            success: function (data) {
                $("#result").html(data);
                show_adding_dropping(idno);
            }

        });
    }
    function show_adding_dropping(idno) {
        array = {};
        array['idno'] = idno;
        $.ajax({
            type: "GET",
            url: "/ajax/registrar_college/adding_dropping/show",
            data: array,
            success: function (data) {
                $("#show_adding_dropping").html(data);
            }

        });
    }
</script>
@endsection