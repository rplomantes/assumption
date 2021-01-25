<style>
    .post
    {
        border-bottom:1px solid #d2d6de;
        margin-bottom:15px;
        padding-bottom:15px;
        color:#666
    }
    .post:last-of-type
    {
        border-bottom:0;
        margin-bottom:0;
        padding-bottom:0
    }
    .post .user-block
    {
        margin-bottom:15px
    }
    /* The switch - the box around the slider */
    .switch {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    /* Hide default HTML checkbox */
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* The slider */
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #ccc;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        -webkit-transition: .4s;
        transition: .4s;
    }

    input:checked + .slider {
        background-color: #2196F3;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }

    /* Rounded sliders */
    .slider.round {
        border-radius: 34px;
    }

    .slider.round:before {
        border-radius: 50%;
    } 
</style>
<?php
if (Auth::user()->accesslevel == env('ADMISSION_HED')) {
    $layout = "layouts.appadmission-hed";
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
        Pre-Registration Email
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li class="active"><a href="/"><i class="fa fa-home"></i> Pre-Registration Email</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<!-- search form (Optional) -->
<div class="col-md-12">
    <form action='{{url('/admissions/settings/pre_registration_email/post')}}' method='post'>
        {{csrf_field()}}
        <?php $regular = \App\CtrHedPreRegMessage::where('type','regular')->first(); ?>
        
        <div class="col-sm-12">
            <div class="box box-default">
                <div class="box-header with-border">
                    <i class="fa fa-warning"></i>

                    <h3 class="box-title">Registration Email</h3>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="form-group" style="margin-top:15px;">

                        <textarea id="editor1" name="message_regular" rows="5" cols="59">
{{$regular->message}}
                        </textarea>
                    </div>

                    <div class="form-group" style="margin-top:15px;">

                        <button class="btn btn-primary btn-flat btn-block" value="regular" name="submit">Update Regular Email</button>
                    </div>
                </div>
            </div>
        </div>
        
    </form>
</div>

<!-- /.search form -->

@endsection
@section('footerscript')
<script src="{{asset('/bower_components/ckeditor/ckeditor.js')}}"></script>                 
<script src="{{asset('/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js')}}"></script>
<script>
$(function () {
    CKEDITOR.replace('editor1')
})
</script>
@endsection