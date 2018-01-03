@extends('layouts.admin')
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
        Dashboard
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li class="active"><a href="/"><i class="fa fa-home"></i> Dashboard</a></li>
    </ol>
</section>
@endsection
@section('maincontent')
<!-- search form (Optional) -->
<div class="col-md-12">
    <input type="text" id="search" class="form-control" placeholder="Search...">

    <div id="studentlist">
        <table class="table table-condensed">
            
        </table>
    </div>    
</div>
<!-- /.search form -->

@endsection
@section('footerscript')
<script type="text/javascript">
   $(document).ready(function(){
       $("#search").keypress(function(e){
           var theEvent = e || window.event;
           var key = theEvent.keyCode || theEvent.which;
           var array={};
           array['search']=$("#search").val();
           if(key==13){
               $.ajax({
                   type:"GET",
                   url:"/ajax/registrar_college/getstudentlist",
                   data:array,
                   success:function(data){
                       $("#studentlist").html(data);
                       $("#search").val("");
                   }
               });
           }
       })
   })
</script>
@endsection