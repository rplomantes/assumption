<?php
$levels = \App\CtrAcademicProgram::selectRaw("distinct level")->where('academic_type',"BED")->orderBy('level')->get();
$strands =\App\CtrAcademicProgram::selectRaw("distinct strand")->where('academic_code','SHS')->get();
?>
@extends('layouts.appbedregistrar')
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
        Assessment
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Assessment</li>
      </ol>
</section>
@endsection
@section('maincontent')
 <!-- search form (Optional) -->        
     <div class="col-md-4">
         <div class="box">
         <div class="box-body">
         <table class="table table-responsive">
             <tr><th>ID No</th><td>{{$user->idno}}</td></tr>
             <tr><th>Name</th><td><b>{{$user->lastname}}, {{$user->firstname}} {{$user->middlename}}</b></td></tr>
             @if(count($status)>0)
                @if($status->status==env("ENROLLED"))
                <tr><td colspan="2"><b>This student is already ENROLLED!!</b></td></tr>
                <tr><th>Level</th><td>{{$status->level}}</td></tr>
                @if($status->level == "Grade 11" || $status->level=="Grade 12")
                <tr><th>Strand</th><td>{{$status->strand}}</td></tr>
                @endif
                <tr><th>Section</th><td>{{$status->section}}</td></tr>
                @elseif($status->status=="0")
                <tr><td colspan="2"><b>Previous Level</b></td></tr>
                <tr><th>Level</th><td>{{$status->level}}</td></tr>
                @if($status->level == "Grade 11" || $status->level=="Grade 12")
                <tr><th>Strand</th><td>{{$status->strand}}</td></tr>
                @endif
                <tr><th>Section</th><td>{{$status->section}}</td></tr>
                @endif
             @endif   
         </table>  
       </div>
       </div>   
         <form class="form form-horizontal" method="post" action="{{url('/bedregistrar','assess')}}">
         {{csrf_field()}}
         <input type="hidden" name="idno" value="{{$user->idno}}">
          @if(count($status)>0)
            @if($status->status == "0")
            <div class="form form-group">
            <div class="col-md-6">
                <label>Grade Level</label>
                <Select name="level" id="level" class="form form-control">
                    <option value="">Select Level</option>
                    @foreach($levels as $level)
                    <option value="{{$level->level}}">{{$level->level}}</option>
                    @endforeach
                </select>      
            </div>
                <div class="col-md-6" id="strand_control">
                    <label>Strand</label>
                    <Select name="strand" id="strand" class="form form-control">
                    <option value="">Select Strand</option>    
                    @foreach($strands as $strand)
                    <option value="{{$strand->strand}}">{{$strand->strand}}</option>
                    @endforeach
                </select> 
                </div>    
            </div>    
            
            @endif
         @endif
         </form>   
     </div>    
    
@endsection
@section('footerscript')

<script>
    $(document).ready(function(){
       $("#strand_control").fadeOut(300);
       $("#level").on('change',function(e){
           if($("#level").val()=="Grade 11" || $("#level").val()=="Grade 12"){
               $("#strand_control").fadeIn(300);
           } else {
               $("#strand_control").fadeOut(300);
           }
       })
       
       $("#search").on('keypress',function(e){
          if(e.keyCode==13){
              var array={};
              array['search'] = $("#search").val();
              $.ajax({
                  type:"GET",
                  url:"/bedregistrar/ajax/getstudentlist",
                  data:array,
                  success:function(data){
                   $("#displaystudent").html(data)
                   $("#search").val("");
                  }
              })
          } 
       }); 
    });
</script>    
@endsection


