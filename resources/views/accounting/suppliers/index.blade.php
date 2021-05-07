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
        List of Suppliers
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Here</li>
      </ol>
</section>
@endsection
@section('maincontent')
<div class="row">
    <div class="col-sm-12">
        
        @if($errors->count())
        <div class="alert-danger alert">
            <ul>
            @foreach($errors->all() as $error)
            <li>{{$error}}</li>
            @endforeach
            </ul>
        </div>
        @endif

        @if(Session::has("success"))
        <div class="alert alert-success">
            <h5><center>{{Session::get("success")}}</center></h5>
        </div>
        @endif
        
        <div class="box box-default">
            <div class="box-header with-border">
                <h5 class="box-title"><i class="fa fa-users"></i> List of Suppliers</h5>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-flat btn-success" data-toggle="modal" data-target="#modal-default">
                        New Supplier
                    </button>
                </div>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table id="datatable" class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width:2%">#</th>
                                <th>Supplier</th>
                                <th>Address</th>
                                <th>TIN</th>
                                <th>Tax Code</th>
                                <th width="20%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($suppliers as $supplier)
                            <tr>
                                <td>{{$loop->iteration}}</td>
                                <td>{{$supplier->supplier_name}}</td>
                                <td>{{$supplier->address}}</td>
                                <td>{{$supplier->tin}}</td>
                                <td>{{$supplier->tax_code}}</td>
                                <td>
                                    <a href="javascript:void(0)" data-toggle="modal" data-target="#modal-edit"  onclick="edit_form('{{$supplier->id}}')" class="btn btn-flat btn-warning"><i class="fa fa-edit"></i> Update</a>
                                    <a onclick="return confirm('Do you wish to Continue?')" href="{{url("/accounting/supplier/delete", array($supplier->id))}}" class="btn btn-flat btn-danger"><i class="fa fa-close"></i> Delete</a>
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
<div class="modal-div"></div>
@include("accounting.suppliers.create")

<div class="modal fade" id="modal-edit">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Update Supplier</h4>
        </div>
        <div class="modal-body" id="model-body-edit">
        </div>
      </div>
    </div>
  </div>

@endsection
@section('footerscript')
<script src="{{url('/bower_components',array('datatables.net','js','jquery.dataTables.min.js'))}}"></script>
<script src="{{url('/bower_components',array('datatables.net-bs','js','dataTables.bootstrap.min.js'))}}"></script>
<script>
$("#datatable").DataTable({});    

function edit_form(id) {
    array = {}
    array['id'] = id;
    $.ajax({
        type: "GET",
        url: "/accounting/supplier/edit",
        data: array,
        success: function (data) {
            $(".modal-body").html(data);
        }

    });
} 
</script>
@endsection
