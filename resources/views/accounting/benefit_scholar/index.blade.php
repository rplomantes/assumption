<?php
if (Auth::user()->accesslevel == env("ACCTNG_STAFF")) {
    $layout = "layouts.appaccountingstaff";
} else if (Auth::user()->accesslevel == env("ACCTNG_HEAD")) {
    $layout = "layouts.appaccountinghead";
} else if (Auth::user()->accesslevel == env("SCHOLARSHIP_BED")) {
    $layout = "layouts.appscholarship_bed";
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
        College Benefit Scholar
        <small>Search Students</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-home"></i> Home</a></li>
        <li class="active">Search student</li>
      </ol>
</section>
@endsection
@section('maincontent')
 <!-- search form (Optional) -->
      
    
        <div class="col-md-12">
          <input type="text" id="search" class="form-control" placeholder="Search...">
     
            <div id="studentlist">
            </div>    
        </div>    
      <!-- /.search form -->
     
@endsection
@section('footerscript')
<script>
    $(document).ready(function(){

        $("#search").on('keypress',function(e){
        if(e.keyCode==13){
          var array={};  
          array['search'] = $("#search").val();
            $.ajax({
                type:"GET",
                url:"{{url('/ajax',array('accounting_benefit_scholar','getstudentlist'))}}",
                data:array,
                success:function(data){
                    $("#studentlist").html(data);
                    $("#search").val("");
                }
            })
        }
        });
    });
</script>    
@endsection
