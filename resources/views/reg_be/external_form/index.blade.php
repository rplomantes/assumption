<?php
    if(Auth::user()->accesslevel == env('OSA')){
    $layout = "layouts.apposa";
    } else {
    $layout = "layouts.appbedregistrar";
    }
?>

@extends($layout)
@section('messagemenu')
<li class="dropdown messages-menu">
    <!-- Menu toggle button -->
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-envelope-o"></i>
        <span class="label label-success">1</span>
    </a>
    <ul class="dropdown-menu">
        <li class="header">You have 1 messages</li>
        <li>
            <!-- inner menu: contains the messages -->
            <ul class="menu">
                <li><!-- start message -->
                    <a href="#">
                        <div class="pull-left">
                            <!-- User Image -->

                        </div>
                        <!-- Message title and timestamp -->
                        <h4>
                            Support Team
                            <small><i class="fa fa-clock-o"></i> 1 mins</small>
                        </h4>
                        <!-- The message -->
                        <p>Welcome to Assumption Student Portal!!<br>More functionality and features coming.<br>Enjoy!!</p>
                    </a>
                </li>
                <!-- end message -->
            </ul>
            <!-- /.menu -->
        </li>
        <li class="footer"><a href="#">See All Messages</a></li>
    </ul>
</li>
@endsection
@section('header')
<section class="content-header">
    <h1>
        External Forms
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>

    </ol>
</section>
@endsection
@section('maincontent')
<div class="col-md-12">
    <form method="post" action="{{url('add_external_form')}}">
        {{ csrf_field() }}
    <?php $i = 0; ?>
    <div  id="dynamic_field_form">
        <!--div class="top-row"-->
        @if(count($external_forms)>0)
        <div class="form form-group">
            <div class="col-md-3">
                <label>Form Name</label>
            </div>
            <div class="col-md-8">
                <label>Link</label>
            </div>
            <div class="col-md-1">
                <label>Add</label>
            </div>
        </div>
        @foreach($external_forms as $form)
        <div id='row_form{{$i}}' class="form form-group">
            <div class="col-md-3">
                <input class="form form-control" type="text" name="form[{{$i}}]" id='form{{$i}}' value='{{$form->form_name}}'/>
            </div>
            <div class="col-md-8">
                <input class="form form-control" type="text" name="form_link[{{$i}}]" id='form_link{{$i}}' value='{{$form->form_link}}'/>
            </div>
            <div class="col-md-1">
                @if($i == 0)
                <button type="button" name="add_form" id="add_form" class="btn btn-success"> + </button>
                @else
                <button type='button' name="remove_form" id="{{$i}}" class="btn btn-danger btn_remove btn_remove_form">X</button>
                @endif
            </div>
        </div>

        <?php $i = $i + 1; ?>
        @endforeach
        @else
        <div class="form form-group">
            <div class="col-md-3">
                <label>Form Name</label>
                <input class="form form-control form" type="text" name="form[]" id='form1'/>
            </div>
            <div class="col-md-8">
                <label>Link</label>
                <input class="form form-control form_lnk" type="text" name="form_link[]" id='form_link1'/>
            </div>
            <div class="col-md-1">
                <label>Add</label>
                <button type="button" name="form" id="add_form" class="btn btn-success"> + </button>
            </div>
        </div>
        @endif
    </div>
        <input type="submit" value="Save" class="btn btn-success col-sm-12">
    </form>
</div>

<!--View Form-->
<div class="modal fade" id="modal-view_form">
</div>


@endsection
@section('footerscript') 

<script>
    var i = "{{$i-1}}";

    $(document).ready(function () {
        $('#add_form').click(function () {

            i++;
            $('#dynamic_field_form').append('<div id="row_form' + i + '" class="form form-group">\n\
           <div class="col-md-3"><input class="form form-control"       type="text" name="form[]"       id="form' + i + '"/></div>\n\
           <div class="col-md-8"><input class="form form-control"       type="text" name="form_link[]"  id="form_link' + i + '"/></div>\n\
           <div class="col-md-1"><a href="javascript:void()" name="remove_form"  id="' + i + '" class="btn btn-danger btn_remove btn_remove_form">X</a></div></div>');


        });
        $('#dynamic_field_form').on('click', '.btn_remove_form', function () {
            var button_id = $(this).attr("id");
            $("#row_form" + button_id + "").remove();
            i--;
        });
    })
</script>

@endsection