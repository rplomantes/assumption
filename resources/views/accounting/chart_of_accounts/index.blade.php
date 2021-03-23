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
        <small></small>
    </h1>
      <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Chart of Accounts</li>
      </ol>
</section>
@endsection
@section('maincontent')
 <!-- search form (Optional) -->

 <div class="col-md-12">
     <div class="container-fluid">
        @if(Session::has('success'))
        <div class="alert alert-success">
            {{Session::get('success')}}
        </div>
        @endif
        
        @if(count($errors)>0)
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                <li>{{$error}}</li>
                @endforeach
            </ul>
        </div>
        @endif
         
         <div class="box box-default">
             <div class="box-header with-border">
                 <h5 class="box-title">List of Accounts</h5>
                 <div class="box-tools pull-right">
                      <a target="_blank" class="btn btn-flat btn-success" href="{{url('/accounting/print_chart_of_accounts')}}">Print List</a>
                      <button type="button" class="pull-right btn btn-flat btn-primary" data-toggle="modal" data-target="#modal-default">
                        Create New Account
                      </button>
                 </div>
             </div>
             <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Accounting Code</th>
                                <th>Accounting Name</th>
                                <th>Category</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($accounts as $account)
                            <?php
                            
                            ?>
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$account->accounting_code}}</td>
                                <td>{{$account->accounting_name}}</td>
                                <td>{{$account->category}}</td>
                                <td>
                                    <a href="{{url('/accounting/chart_of_accounts/update_account', array($account->id))}}" class="btn btn-flat btn-block btn-warning">Update</a>
                                    <a href="{{url('/accounting/chart_of_accounts/delete_account', array($account->id))}}" onclick="return confirm('Do you wish to Continue?')" class="btn btn-flat btn-block btn-danger">Delete</a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
             </div>
         </div>
     </div>
 </div>    

 <div class="modal fade" id="modal-default">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">New Account Form</h4>
        </div>
        <div class="modal-body">
            <form method="post" action="{{url("/accounting/chart_of_accounts/new_account")}}">
                {{csrf_field()}}
                <div class="form-group">
                    <label>Accounting Code</label>
                    <input type="text" class="form-control" name="accounting_code">
                </div>
                <div class="form-group">
                    <label>Accounting Name</label>
                    <input type="text" class="form-control" name="accounting_name">
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select class="form-control" required name="category">
                        <option value="Asset">Asset</option>
                        <option value="Liability">Liability</option>
                        <option value="Equity">Equity</option>
                        <option value="Income">Income</option>
                        <option value="Expenses">Expenses</option>
                    </select>
                </div>
                <div class="form-group">
                    <button onclick="return confirm('Do you wish to Continue?')" class="btn btn-flat btn-warning btn-block"><i class="fa fa-check-circle-o"></i> Save Changes</button>
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
