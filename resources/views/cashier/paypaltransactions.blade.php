@extends('layouts.appcashier')
@section('messagemenu')
@endsection
@section('header')
<section class="content-header">
      <h1>
        Search Paypal Transactions
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Paypal Transactions</li>
      </ol>
</section>
@endsection
@section('maincontent')
 <!-- search form (Optional) -->
 <div class="col-md-12">
     <small>Instruction:
         <ol>
             <li>Login to your Paypal account.</li>
             <li>At the top navigation bar, click 'Activity'.</li>
             <li>Search for the transaction you want to view.</li>
             <li>Click the name of the Transaction.</li>
             <li>In the details look for the 'Invoice ID'.</li>
             <li>Copy and paste the Invoice ID here.</li>
         </ol>
     <input type="text" id="search" class="form-control" placeholder="Invoice ID...">
        
     <div id="displaydetails">
     </div>    
 </div>    
@endsection
@section('footerscript')
<script>
    $(document).ready(function(){
       $("#search").on('keypress',function(e){
          if(e.keyCode==13){
              var array={};
              array['invoice_id'] = $("#search").val();
              $.ajax({
                  type:"GET",
                  url:"/ajax/getpaypaltransactions",
                  data:array,
                  success:function(data){
                   $("#displaydetails").html(data)
                   $("#search").val("");
                  }
              })
          } 
       }); 
    });
</script>    
@endsection