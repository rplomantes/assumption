<?php
$strands = \App\CtrAcademicProgram::selectRaw("distinct strand, strand_name")->where('academic_code', 'SHS')->get();
?>
@extends('layouts.appbedregistrar')
@section('messagemenu')
<li class="dropdown messages-menu">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-envelope-o"></i>
        <span class="label label-success">4</span>
    </a>
    <ul class="dropdown-menu">
        <li class="header">You have 4 messages</li>
        <li>
            <ul class="menu">
                <li>
                    <a href="#">
                        <div class="pull-left">
                        </div>
                        <h4>
                            Support Team
                            <small><i class="fa fa-clock-o"></i> 5 mins</small>
                        </h4>
                        <p>Why not buy a new awesome theme?</p>
                    </a>
                </li>
            </ul>
        </li>
        <li class="footer"><a href="#">See All Messages</a></li>
    </ul>
</li>
@endsection
@section('header')
<section class="content-header">
    <h1>
        Batch Promotions
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Batch Promotions</li>
    </ol>
</section>
@endsection
@section('maincontent')
<section class="content">
    <div class="row">
        @if (Session::has('message'))
            <div class="alert alert-success">{{ Session::get('message') }}</div>
        @endif 
        <div class="box">
            <div class='box-body'>
                <div class="col-sm-3">
                    <label>Level</label>
                    <select class="form form-control" id="level">
                        <option>Select Level</option>
                        <option>Pre-Kinder</option>
                        <option>Kinder</option>
                        <option>Grade 1</option>
                        <option>Grade 2</option>
                        <option>Grade 3</option>
                        <option>Grade 4</option>
                        <option>Grade 5</option>
                        <option>Grade 6</option>
                        <option>Grade 7</option>
                        <option>Grade 8</option>
                        <option>Grade 9</option>
                        <option>Grade 10</option>
                        <option>Grade 11</option>
                        <option>Grade 12</option>
                    </select>
                </div>
                <div class="col-sm-3">
                    <div class="strandDisplay">
                        <label>Strand</label>
                        <select class="form form-control" id="strand">
                            <option>Select Strand</option>
                            @foreach($strands as $strand)
                            <option value="{{$strand->strand}}">{{$strand->strand_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="box displayList">
        </div>
    </div>
</section>
@endsection
@section('footerscript')
<script>
    $(document).ready(function () {
        $(".strandDisplay").fadeOut(300);
        $(".displayList").fadeOut(300);

        $("#level").on('change', function (e) {
            if ($("#level").val() == "Grade 11" || $("#level").val() == "Grade 12") {
                $(".strandDisplay").fadeIn(300);
            } else {
                $(".strandDisplay").fadeOut(300);
                var array = {};
                array['level'] = $("#level").val();
                $.ajax({
                    type: "GET",
                    url: "/bedregistrar/ajax/promotions/getlist",
                    data: array,
                    success: function (data) {
                        $(".displayList").fadeIn(300).html(data);
                    }
                })
            }
        });

        $("#strand").on('change', function (e) {
            var array = {};
            array['level'] = $("#level").val();
            array['strand'] = $("#strand").val();
            $.ajax({
                type: "GET",
                url: "/bedregistrar/ajax/promotions/getlist",
                data: array,
                success: function (data) {
                    $(".displayList").fadeIn(300).html(data);
                }
            })
        });
    });
</script>
@endsection


