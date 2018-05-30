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
@section('maincontent')


<div class="col-sm-12">
    <div class="box">
        <div class="box-header">
            <div class="box-title">Cashiers</div>
        </div>
        <div class="box-body">
        @if (Session::has('message'))
            <div class="alert alert-success">{{ Session::get('message') }}</div>
        @endif
            <table class="table">
                <thead>
                    <tr>
                        <th>ID No</th>
                        <th>Name</th>
                        <th>Starting OR Number</th>
                        <th>Ending OR Number</th>
                    </tr>
                </thead>
                <tbody>
                    <form method='post' action='{{url('accounting/update_or')}}'>
                        {{ csrf_field() }}
                    @foreach ($users as $user)
                    <?php $or_number = \App\ReferenceId::where('idno', $user->idno)->first()->receipt_no; ?>
                    <?php $end_or_number = \App\ReferenceId::where('idno', $user->idno)->first()->end_receipt_no; ?>
                        <input type='hidden' name='idno[]' value='{{$user->idno}}'>
                    <tr>
                        <td>{{$user->idno}}</td>
                        <td>{{$user->lastname}}, {{$user->firstname}}</td>
                        <td><input class="form form-control" size="2" id='or' type=text value="{{$or_number}}" name="or_number[]"></td>
                        <td><input class="form form-control" size="2" id='end_or' type=text value="{{$end_or_number}}" name="end_or_number[]"></td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="4"><input type='submit' class='btn btn-success col-sm-12' value='Update OR Numbers'></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
@section('footerscript')  
@endsection
