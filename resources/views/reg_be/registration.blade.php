<?php
    if(Auth::user()->accesslevel == env('ADMISSION_BED')){
    $layout = "layouts.appadmission-bed";
    $title = "BED Registration";
    }else if(Auth::user()->accesslevel == env('ADMISSION_SHS')){
    $layout = "layouts.appadmission-shs";
    $title = "SHS Registration";
    } else {
    $layout = "layouts.appbedregistrar";
    $title = "BED Registration";
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
        {{$title}}
        <small>Registration For Basic Education</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">{{$title}}</li>
      </ol>
</section>
@endsection
@section('maincontent')
 <!-- search form (Optional) -->
 <div class="col-md-12">
    <div class="box">
        <div class="box-body">
     <form class="form form-horizontal" method="POST" action="{{url('/bedregistrar','registration')}}">
     {{csrf_field()}}
     <div class="col-md-4">
     <div class="form form-group">
         <label>ID Number
         <input type="text" class="form form-control" value="{{$referenceid}}" readonly="readonly" name="referenceid" id="referenceid">
     </div> 
     </div>    
         
     <div class="form form-group">
     <div class="col-md-12">    
     <label>Name</lable> 
     </div>
     <div class="col-md-3">
      <input type="text" name='firstname'id="firstnaame" class="formr form-control" placeholder="First Name">
     @if ($errors->has('firstname'))
                        <span class="help-block">
                            <strong>{{ $errors->first('firstname') }}</strong>
                        </span>
     @endif
     </div>
     <div class="col-md-3">
      <input type="text" name='middlename'id="middlename" class="formr form-control" placeholder="Middle Name">
     </div>
     <div class="col-md-3">
      <input type="text" name='lastname'id="lastname" class="formr form-control" placeholder="Last Name">
    @if ($errors->has('lastname'))
                        <span class="help-block">
                            <strong>{{ $errors->first('lastname') }}</strong>
                        </span>
    @endif
     </div>
     <div class="col-md-3">
      <input type="text" name='extensionname'id="extensionname" class="form form-control" placeholder="Extension Name">
     </div>
     </div>
     
        
     <div class="form-group">
       <div class="col-md-12">
         <label>Address</label>
         </div>   
         <div class="col-md-6">
             <input type="text" class="form form-control" placeholder="Street" name="street" id="address">
         </div>   
         <div class="col-md-6">
             <input type="text" class="form form-control" placeholder="Barangay" name="barangay" id="address">
         </div>   
     </div> 
     
     <div class="form-group">
       <div class="col-md-12">
         </div>     
         <div class="col-md-5">
             <input type="text" class="form form-control" placeholder="Municipality/City" name="municipality" id="address">
         </div>   
         <div class="col-md-5">
             <input type="text" class="form form-control" placeholder="Province" name="province" id="address">
         </div>  
         <div class="col-md-2">
             <input type="text" class="form form-control" placeholder="Zip Code" name="zip" id="address">
         </div>
     </div>
     
     <div class="form-group">
       <div class="col-md-12">
         <label>Contact Numbers</label>
         </div> 
         <div class="col-md-4">
             <input type="text" class="form form-control" placeholder="Telephone Number" name='tel_no' id="tel_no">
         </div>
         <div class="col-md-4">
             <input type="text" class="form form-control" placeholder="Cellphone Number" name='cell_no' id="cell_no">
         </div>
         <div class="col-md-4">
             <input type="email" class="form form-control" placeholder="Email*" name='email' id="email">
         </div>
     </div> 
     
     <div class="form form-group">
         
         <div class="col-md-4">
             <label>Date of Birth</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="text" name="date_of_birth" class="form-control pull-right" id="datepicker">
                </div>
         </div>  
         <div class="col-md-5">
             <label>LRN</label>
             <input type="text" class="form form-control" name="lrn" id="lrn" placeholder="LRN">
         </div>  
         <div class="col-md-3">
             <label>Local/Foreign</label>
             <select class="form form-control" name='is_foreign' value="{{old('is_foreign')}}" type="text">
                <option value="">Select Local/Foreign</option>
                <option value="0" @if ( old('is_foreign') == 0) selected='' @endif >Local</option>
                <option value="1" @if ( old('is_foreign') == 1) selected='' @endif >Foreign</option>
            </select>
         </div>
     </div>  
     <div class="form form-group">
         <div class="col-md-12">
         <input type="submit" name="submit" class="btn btn-primary" value="Register student">
         </div>
      </div>    
     </form>    
    </div>
 </div>
 </div>    
@endsection
@section('footerscript')
<style>
    .help-block{color:red}
</style>
<link rel="stylesheet" href="{{url('/bower_components',array('bootstrap-datepicker','dist','css','bootstrap-datepicker.min.css'))}}">
<script src="{{url('/bower_components',array('bootstrap-datepicker','dist','js','bootstrap-datepicker.min.js'))}}"></script>
<script>
    $(document).ready(function(){
        $('#datepicker').datepicker({
        autoclose: true,
        format:'yyyy-mm-dd',
        });
    
       $("#search").on('keypress',function(e){
          if(e.keyCode==13){
              var array={};
              array['search'] = $("#search").val();
              $.ajax({
                  type:"GET",
                  url:"/admissionbed/ajax/getstudentlist",
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

