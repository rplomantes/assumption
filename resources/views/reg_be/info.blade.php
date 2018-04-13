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
        Student Information
        <small></small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Student Information</li>
      </ol>
</section>
@endsection
@section('maincontent')
 <!-- search form (Optional) -->
 
    <div class="col-md-12">
    <div class="box">
        <div class="box-body">
     <form class="form form-horizontal" method="POST" action="{{url('/bedregistrar', array('updateinfo', $student->idno))}}">
     {{csrf_field()}}
     <div class="col-md-4">
     <div class="form form-group">
         <label>ID Number
         <input type="text" class="form form-control" value="{{$student->idno}}" readonly="readonly" name="referenceid" id="referenceid">
     </div> 
     </div> 
     <div class="col-md-8">
         <div class="col-md-3 pull-right">
             <div class="form form-group">
                 <label>User Status</label>
                 <select class="form form-control" name="user_status" id="user_status">
                     <option value="0" @if ($student->status == 0) selected=''@endif>0 - Not Active</option>
                     <option value="1" @if ($student->status == 1) selected=''@endif>1 - Active</option>
                     <option value="2" @if ($student->status == 2) selected=''@endif>2 - See Registrar</option>
                 </select>
             </div>
          </div>
         <div class="col-md-3 pull-right">
             <div class="form form-group">
                 <label><br><br></label>
                  <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#modal-default">
                Reset Password
              </button>
             </div>
          </div> 
     </div>    
         
     <div class="form form-group">
     <div class="col-md-12">    
     <label>Name</lable> 
     </div>
     <div class="col-md-3">
      <input type="text" name='firstname'id="firstname" class="form form-control" placeholder="First Name" value="{{$student->firstname}}">
     @if ($errors->has('firstname'))
                        <span class="help-block">
                            <strong>{{ $errors->first('firstname') }}</strong>
                        </span>
     @endif
     </div>
     <div class="col-md-3">
         <input type="text" name='middlename'id="middlename" class="formr form-control" placeholder="Middle Name" value="{{$student->middlename}}">
     </div>
     <div class="col-md-3">
      <input type="text" name='lastname'id="lastname" class="formr form-control" placeholder="Last Name" value="{{$student->lastname}}">
    @if ($errors->has('lastname'))
                        <span class="help-block">
                            <strong>{{ $errors->first('lastname') }}</strong>
                        </span>
    @endif
     </div>
     <div class="col-md-3">
      <input type="text" name='extensionname'id="extensionname" class="formr form-control" placeholder="Extension Name">
     </div>
     </div>
     
        
     <div class="form-group">
       <div class="col-md-12">
         <label>Address and Contact Number</label>
         </div>   
         <div class="col-md-8">
             <input type="text" class="form form-control" name='address' placeholder="Address" id="address">
         </div>  
         <div class="col-md-4">
             <input type="text" class="form form-control" name='contact_no' placeholder="Contact Number" id="contact_no">
         </div>
     </div> 
     
     <div class="form form-group">
         
         <div class="col-md-4">
             <label>Date of Birth</label>
                <div class="input-group date">
                  <div class="input-group-addon">
                    <i class="fa fa-calendar"></i>
                  </div>
                  <input type="date" name="date_of_birth" class="form-control pull-right" id="datepicker">
                </div>
         </div>  
         <div class="col-md-5">
             <label>LRN</label>
             <input type="text" class="form form-control" name="lrn" id="lrn" placeholder="LRN">
         </div>
     </div> 
     
     <div class="col-md-12">
         <hr />
     </div> 
     <div class="form form-group">
         <div class="col-md-6">
             <input type ="text" class="form form-control" name="parent_name" id='parent_name' placeholder="Parent's Name">
          </div>   
         <div class="col-md-6">
             <input type ="text" class="form form-control" name="parent_email" id='parent_email' placeholder="Parent's Email">
             @if ($errors->has('parent_email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('parent_email') }}</strong>
                        </span>
                        @endif
         
         </div>
     </div>    
     <div class="form form-group">
         <div class="col-md-12">
         <input type="submit" name="submit" class="btn btn-primary" value="Update Record">
         </div>
      </div>    
     </form> 
            
         <div class="modal fade" id="modal-default">
          <div class="modal-dialog">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Enter New Password : </h4>
              </div>
                <form method="post" action="{{url('/bedregistrar', array('resetpassword'))}}">
                     {{csrf_field()}} 
                     <input type="hidden" name="idno" value="{{$student->idno}}">
              <div class="modal-body">
                  <input type="text" name="password" class="form form-control">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
                <input type="submit" class="btn btn-primary" value="Reset Password">
              </div>
                </form>     
            </div>
            <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
        </div>
        <!-- /.modal -->
            
    </div>
 </div>  
         
        
 </div>    
@endsection
@section('footerscript')
<script>
   
</script>    
@endsection
