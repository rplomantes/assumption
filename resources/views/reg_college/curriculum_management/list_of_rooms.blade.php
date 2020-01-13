<?php
$layout = "layouts.appreg_college";
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
        List of Rooms
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">List of Rooms</li>
    </ol>
</section>
@endsection
@section('maincontent')
<div class="col-md-10">  
                @if (Session::has('message'))
                <div class="alert alert-warning">{{ Session::get('message') }}</div>
                @endif  
    <div class="box"> 
        <div class="box-header">
            <div class="box-title">Update Rooms</div>
            <div class="pull-right"><a href="javascript:void(0)" data-toggle="modal" data-target="#modal-default"><button class="btn btn-success">Add Room</button></a></div>
        </div>
        <div class="box-body">
            <table class="table table-condensed">
                <thead>
                    <tr>
                        <th>Room</th>
                        <th>Affect Conflicts?</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rooms as $room)
                    <tr>
                        <td>{{$room->room}}</td>
                        <td>@if($room->is_no_conflict==0) <b style='color: red'>Doesn't affect Conflicts</b>   @else Affect Conflicts @endif</td>
                        <td><a href='{{url('/registrar_college', array('curriculum_management','delete_room',$room->id))}}'>Delete</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div> 
</div>

<div class="modal fade" id="modal-default">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Add a Room</h4>
            </div>
            <form method="post" action="{{url("registrar_college", array('curriculum_management', 'add_room'))}}">
                {{csrf_field()}}
                <div class="modal-body">
                    <label>Room Name/No:</label>
                    <input type="text" name="room" class="form form-control">

                    <label>Affect Conflict:</label>
                    <div class="input-group">
                        <select name="is_no_conflict" class="form form-control">
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" class="btn btn-primary" value="Create Room">
                </div>
            </form>     
        </div>
    </div>
</div>
@endsection
