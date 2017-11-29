<?php
$courses=DB::Select("Select distinct program_code, program_name from ctr_academic_programs where academic_type='College'");
$user=\App\User::where('idno',$idno)->first();

?>
@extends("layouts.appdean_college")
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
        Dashboard
        <small>Assess student</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url('/')}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Assess student</li>
      </ol>
</section>
@endsection
@section("maincontent")
<form class="form-horizontal">
<div class="col-md-12">    
    <div class="form-group">
        Student ID : {{$user->idno}}<br>
        Student Name : {{$user->lastname}}, {{$user->firstname}}<br>
        @if(count($status)>0)
            @if($status->status == "0")
            Status : Old Student<br>
            Previous Program : {{$status->program_code}}<br>
            Previous Level: {{$status->level}}<br>
            @else
            Status : Assessed<br>
            Program : {{$status->program_code}}<br>
            Level: {{$status->level}}<br>
            @endif
        @else
        Status : New Student
        @endif   
    </div> 
    <div class="form-group"> 
    <div class="col-md-3">   
    <label>Program</label>     
    <select id="program_code" name="program_code" class="form-control">
        <option value="">Select Program</option>
        @foreach($courses as $course)
        <option value="{{$course->program_code}}">{{$course->program_name}}</option>
        @endforeach
    </select>     
   </div> 
   
  <div class="col-md-3 level">     
    <label>Level</label>     
    <select id="level" name="level" class="form-control">
        <option value="">Select Level</option>
        <option>1st Year</option>
        <option>2nd Year</option>
        <option>3rd Year</option>
        <option>4th Year</option>
    </select>     
   </div> 
 </div>    
</div>    
</form>    
@endsection

@section("footerscript")
<script>
$(document).ready(function(){
    $(".level").fadeOut(300);
    
    $("#program_code").on("change",function(e){
     $(".level").fadeIn(300);   
    })
})
</script>
@endsection