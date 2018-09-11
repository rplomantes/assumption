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
        DEBIT MEMO
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url("/cashier",array('viewledger',$user->idno))}}"><i class="fa fa-dashboard"></i> Student Ledger</a></li>
        <li class="active">Student Deposit</li>
      </ol>
</section>
@endsection
@section('maincontent')
 <!-- search form (Optional) -->
 <div class="container">
      <div class="col-md-10 official_receipt">
     <div class="col-md-2 image img-responsive"> <img width="86"src="{{url('/images','assumption-logo.png')}}" ></div>
        <div class="col-md-10"><div class="logo">Assumption College</div>
        San Lorenzo Drive, San Lorenzo Village<br> Makati City<br> NON VAT REG. TIN 000-662-720-000</div>
        <div class="col-md-4 pull-right">
            <div class="orno">Student Deposit No. : {{$student_deposit->sd_no}}</div>
        </div>
   <div class="col-md-12 orheader">STUDENT DEPOSIT</div>
        <div class="col-md-12">
        <table class="table">
        <tr><th>Name:</th><td><b> {{$user->lastname}}, {{$user->firstname}} {{$user->middlename}}</b></td><td align="right">{{date('M d, Y',strtotime($student_deposit->transaction_date))}}</td><tr>
        @if(count($status)>0)
        @if($status->status==3)
            @if($status->department=="College")
            <tr><th>Course / Level</th><td>{{$status->program_code}} / {{$tatus->level}}</td><td></td></tr>
            @else
            <tr><th>Level / Section</th><td>{{$status->level}}</td><td></td></tr>
            @endif
        @endif 
        @endif
        <tr><th></th><td align="right"></td></tr>
        </table>
       <table class="table table-responsive">
           <tr><th>Accounting Code</th><th>Accounting Name</th><th>Particular</th><th>Debit</th><th>Credit</th></tr>
       <?php $total_debit=0;$total_credit=0;?>
       @foreach($accountings as $accounting)
       <?php $total_debit=$total_debit+$accounting->debit;
            $total_credit=$total_credit+$accounting->credit;?>
       <tr><td>{{$accounting->accounting_code}}</td><td>{{$accounting->accounting_name}}</td><td>{{$accounting->subsidiary}}</td>
           <td>{{number_format($accounting->debit,2)}}</td><td>{{number_format($accounting->credit,2)}}</td></tr>
       @endforeach
       <tr><td colspan="3"> Total</td><td><b>{{number_format($total_debit,2)}}</b></td><td><b>{{number_format($total_credit,2)}}</b></td></tr>
       </table>
      <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
        <b>Explanation:</b><br>{{$student_deposit->explanation}}
        </p>  
       <p class="" style="margin-top: 10px;">
            Posted by: <b>{{\App\User::where('idno',$student_deposit->posted_by)->first()->firstname}} {{\App\User::where('idno',$student_deposit->posted_by)->first()->lastname}}</b>
        </p>  
  <div class="form form-group">
      <div class="col-md-10">
          <a href="{{url('/accounting', array(''))}}">
      </div>    
  </div>    
        
 </div> 
   </div>
</div>   
@endsection
@section('footerscript')
<style>
 .table{border-color: #000;}
 .orno{text-align: right;
        color:#f00;
        font-size: 15pt;
 }
 .orheader{
     text-align: center;
     font-size: 18pt;
     font-weight: bold;
     text-decoration: underline;
 }
 .totalreceipt{
     color:darkblue;
     font-weight: bold;
     font-size: 12pt;
 }
 .official_receipt{
     padding: 10px;
     background-color: #fff;
     
 }
 .logo{
     font-size: 20pt;
     font-weight: bold;
     color: darkblue;
 }
</style>
<script>
    $(document).ready(function(){
       $("#search").on('keypress',function(e){
          if(e.keyCode==13){
              var array={};
              array['search'] = $("#search").val();
              $.ajax({
                  type:"GET",
                  url:"/cashier/ajax/getstudentlist",
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

