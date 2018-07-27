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
        Set Other Payment
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Set Other Payment</li>
      </ol>
</section>
@endsection
@section('maincontent')
 <!-- search form (Optional) -->
<div class="col-md-6">
    <div class="box">
        <div class="box-body">
     <form method = "POST" action = "{{url('/accounting','set_other_payment')}}">
         {!!csrf_field()!!}
         <div class="form form-group">
             <label>Particular</label>
             <input type="text" name="particular" id="particular" class="form form-control">
         </div>
         <div class="form form-group">
             <label>Accounting Entry</label>
             <select name="accounting_code" id="accounting_code" class="form form-control select2">
                 @if(count($chart_of_accounts)>0)
                    @foreach($chart_of_accounts as $chart_of_account)
                        <option value="{{$chart_of_account->accounting_code}}">{{$chart_of_account->accounting_name}}</option>
                    @endforeach
                 @endif
             </select>    
         </div>    
         <div class="form form-group">
             <input type="submit" name="submit" id="submit" class="btn btn-primary" value="Add Other Payment">
         </div>    
     </form> 
         </div>   
    </div>        
</div>    
 <div class="col-md-6"> 
 <input type="text" id="search" class="form-control" placeholder="Search...">  
<div id="displayotherpayment">
@if(count($other_payments)>0)
<table class="table table-responsive"><tr><th>Particular</th><th>Accounting Code</th><th>Accounting Name</th><th>Remove</th></tr>
    @foreach($other_payments as $other_payment)
        <tr><td>{{$other_payment->subsidiary}}</td><td>{{$other_payment->accounting_code}}</td><td>{{$other_payment->accounting_name}}</td>
            <td><a href="{{url('/accounting',array('remove_set_other_payment',$other_payment->id))}}" onclick="return confirm('Are You Sure?')">Remove</a></td></tr>
    @endforeach
</table>    
@else
<h3>No Other Payment Set Yet!!!!</h3>
@endif
</div>    
 </div>    
@endsection
@section('footerscript')
<link rel="stylesheet" href="{{url('/',array('bower_components','select2','dist','css','select2.min.css'))}}">

<style>
    #idno{font-weight: bold;
          color:#f00;
          font-size: 14pt;
    }
    #sname{
        font-weight: bold;
        font-size: 16pt;
    }
</style>

<script src="{{url('/',array('bower_components','select2','dist','js','select2.full.min.js'))}}"></script>

<script>
    $(document).ready(function(){
       $(".select2").select2();
       $("#particular").focus();
       $("#particular").on("keypress",function(e){
           if(e.keyCode==13){
               if($("#particular").val()==""){
                   alert("Please fill-up particular")
               }
               $("#accounting_code").focus();
               e.preventDefault()
           }
       })  
       $("#search").on('keypress',function(e){
          if(e.keyCode==13){
              var array={};
              array['search'] = $("#search").val();
              $.ajax({
                  type:"GET",
                  url:"/accounting/ajax/getotherpayment",
                  data:array,
                  success:function(data){
                   $("#displayotherpayment").html(data)
                   $("#search").val("");
                  }
              })
          } 
       }); 
    });
</script>    
@endsection


