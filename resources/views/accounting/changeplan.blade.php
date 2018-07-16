@extends('layouts.appaccountingstaff')
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
        Change Plan
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Change Plan</li>
      </ol>
</section>
@endsection
@section('maincontent')
 <!-- search form (Optional) -->
 <div class="col-md-12">
     <div class="box">
     <div class="col-md-6">   
     <table class="table table-striped">
     <tr><td>ID Number : </td><td>{{$idno}}</td></tr> 
     <tr><td>Name : </td><td><b>{{$student->lastname}}, {{$student->firstname}} {{$student->middlename}}</b></td></tr>
     <tr><td>Level : </td><td>{{$status->level}}</td></tr>
     <tr><td>Plan : </td><td><span id="plan">{{$status->type_of_plan}}</span></td></tr>
     </table>
         
      <h3>Schedule of Fees</h3>
      <table class="table table-striped">
          <tr><td>Due Date</td><td>Amount</td></tr>
          @if(count($duedates)>0)
          @foreach($duedates as $duedate)
          <tr><td>{{$duedate->due_date}}</td><td>{{number_format($duedate->amount,2)}}</td></tr>
          @endforeach
          @endif
      </table>   
     </div>   
     
     <div class="col-md-6">
         
         <label>Change Plan To :</label>
         
             <form method="POST" action="{{url('/accounting',array('change_plan'))}}">
                 {!!csrf_field()!!}
                 <input type="hidden" name="idno" value ="{{$idno}}">
                 <input type="hidden" name="level" value="{{$status->level}}">
                 <input type="hidden" name="academic_type" value="{{$status->academic_type}}">
             <div class="form form-group">    
             <select class="form form-control" name="plan">
                 <option value="">Select Plan</option>
                 <option value="Plan A">Plan A</option>
                 @foreach($duedateplans as $plan)
                 <option value="{{$plan->plan}}">{{$plan->plan}}</option>
                 @endforeach
             </select>    
            </div>
            <div class="form form-group">
                <input type="submit" name="submit" value="Process Change Plan" class="btn btn-primary form-control">
            </div>
           </form>      
     </div> 
    </div>     
 </div>    
@endsection
@section('footerscript')
<style>
    #plan{color:red; font-weight: bold; font-size: 12pt;}
</style> 
@endsection

