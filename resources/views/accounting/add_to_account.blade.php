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
        Add To Account
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="{{url("/cashier",array("viewledger",$user->idno))}}"><i class="fa fa-dashboard"></i> Student Ledger</a></li>
        <li class="active">Add to account</li>
      </ol>
</section>
@endsection
@section('maincontent')

    <div class="col-md-6"> 
    <div class="box">    
    <div class="box-body">
           
        <table class="table table responsive">
        <tr><th>ID No. </th><td><span id="idno">{{$user->idno}}</span></td></tr>
        <tr><th>Name </th><td><span id="sname">{{$user->lastname}}, {{$user->firstname}}</span></b></td></tr>
        @if(count($status)>0)
        @if($status->academic_type=="College")
        <tr><th>Course</th><td>{{$status->program_code}}</td></tr>
        <tr><th>Level</th><td>{{$status->level}}</td></tr>
        @else
        <tr><th>Grade Level</th><td>{{$status->level}}</td></tr>
        <tr><th>Section</th><td>{{$status->section}}</td></tr>
        @endif
        @endif
        </table>
        </div>
    </div>
    </div>    

    <div class="col-md-6">
        <div class="box">    
    <div class="box-body">
            <h5>Add to Account:</h5>
            <form action="{{url('/accounting',array('add_to_account'))}}" method="post">
                {!!csrf_field()!!}
                <div class="form form-group">
                    <label>Particular :</label>
                    <input type="text" class="form form-control" name="particular" id="particular">
                    <input type="hidden" name="idno" value="{{$user->idno}}">
                </div>
                <div class="form form-group">
                    <label>Credit Entry :</label>
                    <select name="accounting_code" id="accounting_code" class="form form-control select2">
                        @foreach($chart_of_accounts as $chart_of_account)
                            <option value="{{$chart_of_account->accounting_code}}">{{$chart_of_account->accounting_name}}</option>
                        @endforeach
                    </select>    
                </div> 
                <div class="form form-group">
                    <label>Amount :</label>
                    <input type="text" name="amount" id="amount" class="form form-control number">
                </div>  
                <div class="form form-group">
                    <input type="submit" name="submit" value="Add To Account" id="submit" class="btn btn-primary">
                </div>    
            </form>    
        </div>    
     </div>   
</div>    
<div class="col-md-12">
  <div id="other_account_display">  
    @if(count($other_accounts)>0)
    <table class="table table-responsive">
        <tr><th>Particular</th><th>Accounting Entry</th><th>Amount</th><th>Balance</th><th>Remove<th></tr>
        @foreach($other_accounts as $other_account)
        <?php $balance = $other_account->amount-$other_account->debit_memo-$other_account->payment;?>
        <tr><td>{{$other_account->receipt_details}}</td><td>{{$other_account->accounting_name}}</td><td>{{number_format($other_account->amount,2)}}</td><td>{{number_format($balance,2)}}</td><td>
            @if($balance==$other_account->amount)
            <a href="{{url('/accounting',array('remove_other_payment',$other_account->id))}}" onclick="return confirm('Are You Sure ?')">Remove</a>
            @else
            Remove
            @endif
            </td></tr>
        @endforeach
        </table>
    @else    
    <h1>No other payment added!</h1>
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
        $("#submit").fadeOut(300);
        $("#particular").on("keypress",function(e){
            if(e.keyCode==13){
                if($("#particular").val()==""){
                    alert("Please fill-up particular");
                }else{
                $("#accounting_code").focus();
                }
                e.preventDefault();
            }
        })
        
        $("#amount").on("keypress",function(e){
            if(e.keyCode==13){
                if($("#amount").val()==""){
                    alert("Please put amount")
                } else {
                   $("#submit").fadeIn(300) 
                   $("#submit").focus();
                }
                e.preventDefault();
            }
        })
        
        $(".number").on('keypress',function(e){
        var theEvent = e || window.event;
        var key = theEvent.keyCode || theEvent.which;
        if ((key < 48 || key > 57) && !(key == 8 || key == 9 || key == 13 || key == 37 || key == 39 || key == 46) ){ 
        theEvent.returnValue = false;
        if (theEvent.preventDefault) theEvent.preventDefault();
        }
    })
        
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


