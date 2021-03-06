<?php
$levels = DB::Select("SELECT distinct level, sort_by from ctr_academic_programs where academic_type='BED' order by sort_by");
$strands = DB::Select("Select distinct strand from ctr_academic_programs where academic_type='BED'");
$school_years = DB::Select("Select distinct school_year from bed_levels");
?>
<?php 
if(Auth::user()->accesslevel == env('REG_BE')){
    $layout = "layouts.appbedregistrar";
}else{
    $layout = "layouts.appadmission-bed";
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
        Report Card Sequencing
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Report Card Sequencing</li>
      </ol>
</section>
@endsection
@section('maincontent')
 <!-- search form (Optional) -->
 <div class="col-md-12">
     <div class="col-md-2">
         <div class="form form-group">
        <label>Level</label>
        <select class="form-control select2" id="level" data-placeholder="Select Level">
                  <option>Select Level</option>      
                  @foreach($levels as $level)
                  <option>{{$level->level}}</option>
                  @endforeach
        </select>
        </div>      
     </div>  
     <div class="col-md-2">
        <div class="form form-group">
        <label>Strand</label>
        <div class="strandDisplay">
        <select class="form-control select2" id="strand" data-placeholder="Select Strand">       
                 <option>Select Strand</option> 
                 @foreach($strands as $level)
                  <option>{{$level->strand}}</option>
                  @endforeach
        </select>
        </div>    
        </div>      
     </div>  
     <div class="col-md-2">
        <div class="form form-group">
        <label>School Year</label>
        <select class="form-control select2" id="school_year" data-placeholder="Select School Year">
                        style="width: 100%;">
                  @foreach($school_years as $school_year)
                  <option>{{$school_year->school_year}}</option>
                  @endforeach
        </select>
        </div>      
     </div> 
     <div class="col-md-2">
        <div class="form form-group">
        <label>Period</label>
        <div class="periodDisplay">
        <select class="form-control select2" id="period" data-placeholder="Select Period">
                        style="width: 100%;">
                        <option value="">Select Period</option>
                        <option>1st Semester</option>
                        <option>2nd Semester</option>
        </select>
        </div>
        </div>      
     </div> 
     
     <div class="col-md-2">
        <div class="form form-group">
            <br>
            <button class="btn btn-primary form-control" id="view_list">View Subjects</button>
        </div>      
     </div> 
     
     </div>
 <div class="col-md-12">
     <div class="box">
         <div class="box-body">
     <div id="displaySubjects">
     </div> 
     </div></div>        
 </div>    
@endsection
@section('footerscript')

<script src="{{url('/dist',array('js','demo.js'))}}"></script>
<script src="{{url('/',array('bower_components','select2','dist','js','select2.full.min.js'))}}"></script>
<script>
    
    $(document).ready(function(){
        $(".strandDisplay").fadeOut(300);
        $(".periodDisplay").fadeOut(300);
        $('.select2').select2()
        
        $("#level").on('change',function(e){
            if($("#level").val()=="Grade 11" || $("#level").val()=="Grade 12" ){
               $(".strandDisplay").fadeIn(300); 
               $(".periodDisplay").fadeIn(300);
               $("#sectionDisplay").html("");
            } else {
                $(".strandDisplay").fadeOut(300);
                $(".periodDisplay").fadeOut(300);
                $(".period").val("");
            }
        });
        
         $("#strand").on('change',function(e){
            
                var array={};
                array['level']=$("#level").val();
                array['strand']=$("#strand").val()
                $.ajax({
                    type:"GET",
                    url:"/bedregistrar/ajax/getsection",
                    data:array,
                    success: function(data){  
                        $("#sectionDisplay").html(data)
                        $('.select2').select2()
                    }
                })
            
        });
        
       $("#view_list").on('click',function(e){
       
              var array={};
              array['level'] = $("#level").val();
              array['strand'] = $("#strand").val();
              array['school_year']=$("#school_year").val();
              array['period']=$("#period").val();
              $.ajax({
                  type:"GET",
                  url:"/bedregistrar/ajax/report_card_sequencing/view_subjects",
                  data:array,
                  success:function(data){
                   $("#displaySubjects").html(data)
                   
                  }
              })
           
       }); 
       
       
    });
</script>    
@endsection
