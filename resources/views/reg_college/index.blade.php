@extends('layouts.appreg_college')
@section('messagemenu')
<li class="dropdown messages-menu">
    <!-- Menu toggle button -->
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-envelope-o"></i>
        <span class="label label-success">4</span>
    </a>
    <ul class="dropdown-menu">
        <li class="header">You have 4 messages</li>
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
                            <small><i class="fa fa-clock-o"></i> 5 mins</small>
                        </h4>
                        <!-- The message -->
                        <p>Why not buy a new awesome theme?</p>
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
        Search Students
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li class="active"><a href="/"><i class="fa fa-home"></i> Home</a></li>
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