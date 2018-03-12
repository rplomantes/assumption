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
         <tbody>
             
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
             @if(count($statistics)>0)
                <?php $islevel=""; $count=0; $strand=""; $subtotal=0; $noofloop=0;?>
                @foreach($statistics as $statistic)
                    @if($statistic->level != "Grade 11" && $statistic->level != "Grade 12" && $statistic->level!="Kinder" && $statistic->level!="Pre-Kinder")
                    @if($islevel != $statistic->level)
                        @if($noofloop > 0)
                        <?php
                        for($i=$noofloop;$i<5;$i++){
                            echo "<td></td>";
                        }
                        $noofloop=0;
                        ?>
                        <td>{{$subtotal}}</td></tr>
                        @endif
                        
                        <tr><td>{{$statistic->level}} </td>
                            @if($statistic->section == '1')
                            <?php $noofloop=1;?>
                            <td>{{$statistic->count}}</td>
                            @elseif($statistic->section == '2')
                            <?php $noofloop=2;?>
                            <td></td><td>{{$statistic->count}}</td>
                            @elseif($statistic->section == '3')
                            <?php $noofloop=3;?>
                            <td></td><td></td><td>{{$statistic->count}}</td>
                            
                            @elseif($statistic->section == '4')
                            <td></td><td></td><td></td><td>{{$statistic->count}}</td>
                            <?php $noofloop=4;?>
                            @elseif($statistic->section == '5')
                            <td></td><td></td><td></td><td></td><td>{{$statistic->count}}</td>
                            <?php $noofloop=5;?>
                            @endif
                   
                     <?php $islevel=$statistic->level; $subtotal=$statistic->count; ?>
                    @else
                        <?php $subtotal = $subtotal+$statistic->count; $noofloop=$noofloop+1;?>
                        <td>{{$statistic->count}} </td>
                    @endif
                @endif    
                @endforeach  
                @if($noofloop > 0)
                        <?php
                        for($i=$noofloop;$i<=4;$i++){
                            echo "<td></td>";
                        }
                        ?>
                        <td>{{$subtotal}}</td></tr>
                        @endif
             @endif
             
              
         
       </tbody>
       <tfoot>
       </tfoot>    
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


