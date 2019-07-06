<?php 
    if (Auth::user()->accesslevel==env("ACCTNG_STAFF")){
        $layout = "layouts.appaccountingstaff";    
    }else if (Auth::user()->accesslevel==env("ACCTNG_HEAD")){
        $layout = "layouts.appaccountinghead";    
    }
?>
@extends($layout)
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
        Schedule of Fees
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active"><a href="{{url("/accounting/schedule_of_fees")}}">Schedule of Fees</a></li>
    </ol>
</section>
@endsection
@section('maincontent')

<div class="box">
    <div class="box-header">
        <div class="col-md-6">
        <div class="form form-group">
            <label>Department</label>
            <select id="department" class="form-control">
                <option>Select Department</option>
                <option>Pre School</option>
                <option>Elementary</option>
                <option>Junior High School</option>
                <option>Senior High School</option>
            </select>    
         </div>
         </div>
        <div class="clearfix"></div>
        <div class="col-md-12">
            <div id="display_plan">
            </div>    
        </div>    
    </div>   
</div>

@endsection
@section('footerscript')
<script>
$(document).ready(function(){
    $('#department').on('change',function(e){
        var array={};
        array['department']= $("#department").val();
        $.ajax({
            type:"GET",
            url:"/accounting/ajax/getplan",
            data:array,
            success:function(data){
                $("#display_plan").html(data);
            }
        })
    })
})
</script>
@endsection


