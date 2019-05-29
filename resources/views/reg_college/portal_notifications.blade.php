@extends('layouts.appreg_college')@extends('layouts.admin')
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
        Portal Notifications
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li class="active"><a href="/"><i class="fa fa-home"></i> Portal Notifications</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<!-- search form (Optional) -->
<div class="col-md-12">
    <div class="col-sm-6">
        <div class="box box-default">
            <div class="box-header with-border">
              <i class="fa fa-warning"></i>

              <h3 class="box-title">Set Portal Notifications</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
             @if(Session::has('announcement'))
             <div class='alert alert-success'>
                 {{Session::get('announcement')}}
             </div>
             @endif
            <form action='{{url('/registrar_college/portal_notifications/post')}}' method='post'>
            {{csrf_field()}}
            <div class="row">
                <div class="col-sm-6">
                    <label>Department</label>
                    <select name="department" class="form-control">
                        <option>College Records</option>
                    </select>
                </div>
            </div>
            <div class="form-group" style="margin-top:15px;">
                
                <textarea id="editor1" name="notifications_content" rows="5" cols="59">
                     
                </textarea>
            </div>
               
            <div class="form-group" style="margin-top:15px;">
                
                <button class="btn btn-primary btn-flat btn-block">Post</button>
            </div>
            </form>
        </div>

    </div>
</div>
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