<?php 
    if (Auth::user()->accesslevel==env("ACCTNG_STAFF")){
        $layout = "layouts.appaccountingstaff";    
    }else if (Auth::user()->accesslevel==env("ACCTNG_HEAD")){
        $layout = "layouts.appaccountinghead";    
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
        Chart of Accounts
        <small>{{$account->accounting_code}} - {{$account->accounting_name}}</small>
    </h1>
      <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Chart of Accounts</li>
      </ol>
</section>
@endsection
@section('maincontent')
 <!-- search form (Optional) -->

 <div class="col-md-6">
    <div class="container-fluid">
        <div class="box box-primary box-solid">
            <div class="box-header">
                <h5 class="box-title">Update {{$account->accounting_code}} {{$account->accounting_name}}</h5>
            </div>
            <div class="box-body">
                <form method="post" action="{{url("/accounting/chart_of_accounts/update_account")}}">
                    {{csrf_field()}}
                    <input type="hidden" name="chart_id" value="{{$id}}">
                    <div class="form-group">
                        <label>Accounting Code</label>
                        <input type="text" name="accounting_code" class="form-control" value="{{$account->accounting_code}}">
                    </div>
                    <div class="form-group">
                        <label>Accounting Name</label>
                        <input type="text" name="accounting_name" class="form-control" value="{{$account->accounting_name}}">
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select class="form-control" required name="category">
                            <option @if($account->category == "Asset") selected @endif value="Asset">Asset</option>
                            <option @if($account->category == "Liability") selected @endif value="Liability">Liability</option>
                            <option @if($account->category == "Equity") selected @endif value="Equity">Equity</option>
                            <option @if($account->category == "Income") selected @endif value="Income">Income</option>
                            <option @if($account->category == "Expenses") selected @endif value="Expenses">Expenses</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button onclick="return confirm('Do you wish to continue?')" type="submit" class="btn btn-flat btn-block btn-warning">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('footerscript')
<script>
   
</script>    
@endsection
