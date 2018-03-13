<?php

function getCount($getlevel,$getsection,$getstrand,$schoolyear){
 if($getlevel == "Grade 11" || $getlevel == "Grade 12"){
     $count = \App\BedLevel::selectRaw("level,section,strand,count(*) as count")
             ->whereRaw("level='$getlevel' AND section = '$getsection' AND strand = '$getstrand' AND status = '3' AND school_year = '$schoolyear'")
             ->groupBy('level','section','strand')->first();
 } else{
     $count = \App\BedLevel::selectRaw("level,section,count(*) as count")
             ->whereRaw("level='$getlevel' AND section = '$getsection' AND status = '3' AND school_year = '$schoolyear'")
             ->groupBy('level','section')->first();
     }
  if(count($count)>0){
         return $count->count;
     } else {
         return "0";
     }   
 
}

function getTotal($getlevel,$getstrand,$schoolyear){
    
    if($getlevel == "Grade 11" || $getlevel == "Grade 12"){
     $count = \App\BedLevel::selectRaw("level,strand,count(*) as count")
             ->whereRaw("level='$getlevel'  AND strand = '$getstrand' AND status = '3' AND school_year = '$schoolyear'")
             ->groupBy('level','strand')->first();
 } else{
     $count = \App\BedLevel::selectRaw("level,count(*) as count")
             ->whereRaw("level='$getlevel' AND status = '3' AND school_year = '$schoolyear'")
             ->groupBy('level')->first();
     }
  if(count($count)>0){
         return $count->count;
     } else {
         return "0";
     } 
    
}

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
        Enrollment Statistics
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Here</li>
      </ol>
</section>
@endsection
@section('maincontent')
 <!-- search form (Optional) -->
 <div class="col-md-12">
     <div class="box">    
     <div class="box-body">
     <h3>Pre School</h3>
     <table id="example1" class="table table-responsive table-striped">
         <thead>
              <tr><th>GRADE LEVEL</th><th>A</th><th>B</th><th>C</th><th>D</th><th>E</th><th>F</th><th>Total</th></tr>
         </thead>
          <tr><td>Pre Kinder </td><td>{{getCount('Pre-Kinder','A','',$school_year)}}</td>
             <td>{{getCount('Pre-Kinder','B','',$school_year)}}</td>
             <td>{{getCount('Pre-Kinder','C','',$school_year)}}</td>
             <td>{{getCount('Pre-Kinder','D','',$school_year)}}</td>
             <td>{{getCount('Pre-Kinder','E','',$school_year)}}</td>
             <td>{{gettotal('Pre-Kinder','F',$school_year)}}</td>
            </tr>
            <tr><td>Kinder </td><td>{{getCount('Kinder','A','',$school_year)}}</td>
             <td>{{getCount('Kinder','B','',$school_year)}}</td>
             <td>{{getCount('Kinder','C','',$school_year)}}</td>
             <td>{{getCount('Kinder','D','',$school_year)}}</td>
             <td>{{getCount('Kinder','E','',$school_year)}}</td>
             <td>{{gettotal('Kinder','F',$school_year)}}</td>
            </tr>
         <tbody>
            @if(count($kinder)>0)
            <tr>
            @foreach($kinder as $count)
            <td>{{$count->count}}</td>
            @endforeach
            <tr>
            @endif
         </tbody>
     </table>
     </div>
         </div>
     <div class="box">    
     <div class="box-body">
     
     <h3>Grades 1 - 10</h3>    
     <table id="example2" class="table table-responsive table-striped">
         <thead>
             <tr><th>GRADE LEVEL</th><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>Total</th></tr>
         </thead>
         <tbody>
             <tr><td>Grade 1 </td><td>{{getCount('Grade 1','1','',$school_year)}}</td>
             <td>{{getCount('Grade 1','2','',$school_year)}}</td>
             <td>{{getCount('Grade 1','3','',$school_year)}}</td>
             <td>{{getCount('Grade 1','4','',$school_year)}}</td>
             <td>{{getCount('Grade 1','5','',$school_year)}}</td>
             <td>{{gettotal('Grade 1','',$school_year)}}</td>
            </tr>
             <tr><td>Grade 2 </td><td>{{getCount('Grade 2','1','',$school_year)}}</td>
             <td>{{getCount('Grade 2','2','',$school_year)}}</td>
             <td>{{getCount('Grade 2','3','',$school_year)}}</td>
             <td>{{getCount('Grade 2','4','',$school_year)}}</td>
             <td>{{getCount('Grade 2','5','',$school_year)}}</td>
             <td>{{gettotal('Grade 2','',$school_year)}}</td>
            </tr>
             <tr><td>Grade 3 </td><td>{{getCount('Grade 3','1','',$school_year)}}</td>
             <td>{{getCount('Grade 3','2','',$school_year)}}</td>
             <td>{{getCount('Grade 3','3','',$school_year)}}</td>
             <td>{{getCount('Grade 3','4','',$school_year)}}</td>
             <td>{{getCount('Grade 3','5','',$school_year)}}</td>
             <td>{{gettotal('Grade 3','',$school_year)}}</td>
            </tr>
             <tr><td>Grade 4 </td><td>{{getCount('Grade 4','1','',$school_year)}}</td>
             <td>{{getCount('Grade 4','2','',$school_year)}}</td>
             <td>{{getCount('Grade 4','3','',$school_year)}}</td>
             <td>{{getCount('Grade 4','4','',$school_year)}}</td>
             <td>{{getCount('Grade 4','5','',$school_year)}}</td>
             <td>{{gettotal('Grade 4','',$school_year)}}</td>
            </tr>
             <tr><td>Grade 5 </td><td>{{getCount('Grade 5','1','',$school_year)}}</td>
             <td>{{getCount('Grade 5','2','',$school_year)}}</td>
             <td>{{getCount('Grade 5','3','',$school_year)}}</td>
             <td>{{getCount('Grade 5','4','',$school_year)}}</td>
             <td>{{getCount('Grade 5','5','',$school_year)}}</td>
             <td>{{gettotal('Grade 5','',$school_year)}}</td>
            </tr>
             <tr><td>Grade 6 </td><td>{{getCount('Grade 6','1','',$school_year)}}</td>
             <td>{{getCount('Grade 6','2','',$school_year)}}</td>
             <td>{{getCount('Grade 6','3','',$school_year)}}</td>
             <td>{{getCount('Grade 6','4','',$school_year)}}</td>
             <td>{{getCount('Grade 6','5','',$school_year)}}</td>
             <td>{{gettotal('Grade 6','',$school_year)}}</td>
            </tr>
             <tr><td>Grade 7 </td><td>{{getCount('Grade 7','1','',$school_year)}}</td>
             <td>{{getCount('Grade 7','2','',$school_year)}}</td>
             <td>{{getCount('Grade 7','3','',$school_year)}}</td>
             <td>{{getCount('Grade 7','4','',$school_year)}}</td>
             <td>{{getCount('Grade 7','5','',$school_year)}}</td>
             <td>{{gettotal('Grade 7','7',$school_year)}}</td>
            </tr>
             <tr><td>Grade 8 </td><td>{{getCount('Grade 8','1','',$school_year)}}</td>
             <td>{{getCount('Grade 8','2','',$school_year)}}</td>
             <td>{{getCount('Grade 8','3','',$school_year)}}</td>
             <td>{{getCount('Grade 8','4','',$school_year)}}</td>
             <td>{{getCount('Grade 8','5','',$school_year)}}</td>
             <td>{{gettotal('Grade 8','',$school_year)}}</td>
            </tr>
             <tr><td>Grade 9 </td><td>{{getCount('Grade 9','1','',$school_year)}}</td>
             <td>{{getCount('Grade 9','2','',$school_year)}}</td>
             <td>{{getCount('Grade 9','3','',$school_year)}}</td>
             <td>{{getCount('Grade 9','4','',$school_year)}}</td>
             <td>{{getCount('Grade 9','5','',$school_year)}}</td>
             <td>{{gettotal('Grade 9','',$school_year)}}</td>
            </tr>
             <tr><td>Grade 10 </td><td>{{getCount('Grade 10','1','',$school_year)}}</td>
             <td>{{getCount('Grade 10','2','',$school_year)}}</td>
             <td>{{getCount('Grade 10','3','',$school_year)}}</td>
             <td>{{getCount('Grade 10','4','',$school_year)}}</td>
             <td>{{getCount('Grade 10','5','',$school_year)}}</td>
             <td>{{gettotal('Grade 10','',$school_year)}}</td>
            </tr>
       </tbody>
       <tfoot>
       </tfoot>    
       </table>
       </div>
       </div>  
     <div class="box">    
     <div class="box-body">
     
     <h3>Grades  11 - 12</h3> 
     <table id="example2" class="table table-responsive table-striped">
         <thead>
             <tr><th>GRADE LEVEL</th><th>1</th><th>2</th><th>3</th><th>4</th><th>5</th><th>Total</th></tr>
         </thead>
         <tr><td>Grade 11 ABM</td><td>{{getCount('Grade 11','1','ABM',$school_year)}}</td>
             <td>{{getCount('Grade 11','2','ABM',$school_year)}}</td>
             <td>{{getCount('Grade 11','3','ABM',$school_year)}}</td>
             <td>{{getCount('Grade 11','4','ABM',$school_year)}}</td>
             <td>{{getCount('Grade 11','5','ABM',$school_year)}}</td>
             <td>{{gettotal('Grade 11','ABM',$school_year)}}</td>
          </tr>
          <tr><td>Grade 11 HUMMS</td><td>{{getCount('Grade 11','1','HUMMS',$school_year)}}</td>
             <td>{{getCount('Grade 11','2','HUMMS',$school_year)}}</td>
             <td>{{getCount('Grade 11','3','HUMMS',$school_year)}}</td>
             <td>{{getCount('Grade 11','4','HUMMS',$school_year)}}</td>
             <td>{{getCount('Grade 11','5','HUMMS',$school_year)}}</td>
             <td>{{gettotal('Grade 11','HUMMS',$school_year)}}</td>
          </tr>
          <tr><td>Grade 11 STEM</td><td>{{getCount('Grade 11','1','STEM',$school_year)}}</td>
             <td>{{getCount('Grade 11','2','STEM',$school_year)}}</td>
             <td>{{getCount('Grade 11','3','STEM',$school_year)}}</td>
             <td>{{getCount('Grade 11','4','STEM',$school_year)}}</td>
             <td>{{getCount('Grade 11','5','STEM',$school_year)}}</td>
             <td>{{gettotal('Grade 11','STEM',$school_year)}}</td>
          </tr>
          
          <tr><td>Grade 12 ABM</td><td>{{getCount('Grade 12','1','ABM',$school_year)}}</td>
             <td>{{getCount('Grade 12','2','ABM',$school_year)}}</td>
             <td>{{getCount('Grade 12','3','ABM',$school_year)}}</td>
             <td>{{getCount('Grade 12','4','ABM',$school_year)}}</td>
             <td>{{getCount('Grade 12','5','ABM',$school_year)}}</td>
             <td>{{gettotal('Grade 12','ABM',$school_year)}}</td>
          </tr>
          <tr><td>Grade 12 HUMMS</td><td>{{getCount('Grade 12','1','HUMMS',$school_year)}}</td>
             <td>{{getCount('Grade 12','2','HUMMS',$school_year)}}</td>
             <td>{{getCount('Grade 12','3','HUMMS',$school_year)}}</td>
             <td>{{getCount('Grade 12','4','HUMMS',$school_year)}}</td>
             <td>{{getCount('Grade 12','5','HUMMS',$school_year)}}</td>
             <td>{{gettotal('Grade 12','HUMMS',$school_year)}}</td>
          </tr>
          <tr><td>Grade 12 STEM</td><td>{{getCount('Grade 12','1','STEM',$school_year)}}</td>
             <td>{{getCount('Grade 12','2','STEM',$school_year)}}</td>
             <td>{{getCount('Grade 12','3','STEM',$school_year)}}</td>
             <td>{{getCount('Grade 12','4','STEM',$school_year)}}</td>
             <td>{{getCount('Grade 12','5','STEM',$school_year)}}</td>
             <td>{{gettotal('Grade 12','STEM',$school_year)}}</td>
          </tr>
          
     </table>    
     </div>
     </div>    
 </div>    
@endsection
@section('footerscript')
<script src="{{url('/bower_components',array('datatables.net','js','jquery.dataTables.min.js'))}}"></script>
<script src="{{url('/bower_components',array('datatables.net-bs','js','dataTables.bootstrap.min.js'))}}"></script>

<script>
    $(document).ready(function(){
        $('#example1').DataTable();
        $('#example2').DataTable();
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


