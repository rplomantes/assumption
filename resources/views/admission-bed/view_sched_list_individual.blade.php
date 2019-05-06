<?php
if(Auth::user()->accesslevel == env('ADMISSION_BED')){
$layout = "layouts.appadmission-bed";
} else {
$layout = "layouts.appadmission-shs";
}
?>

@extends($layout)
@section('messagemenu')

<link rel="stylesheet" type="text/css" href="{{asset ('jquery.datetimepicker.css')}}">
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
        Testing Schedules
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Here</li>
    </ol>
</section>
@endsection
@section('maincontent')
<?php $counter = 1; ?>
<div class="col-md-12 box box-body">
    @if (Session::has('message'))
            <div class="alert alert-success">{{ Session::get('message') }}</div>
        @endif 
    <table class="table table-condensed">
        <thead>
            <tr>
                <th>#</th>
                <th>Reference No.</th>
                <th>Name</th>
                <th>Birthday</th>
                <th>Remove</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lists as $list)
            <?php $dob = \App\BedProfile::where('idno', $list->idno)->first()->date_of_birth; ?>
            <?php $status = \App\Status::where('idno', $list->idno)->first(); ?>
            <tr>
                <td>{{$counter++}}</td>
                <td>{{$list->idno}}</td>
                <td>{{$status->getFullNameAttribute()}}</td>
                <td>{{$dob}}</td>
                @if($status->status == env('FOR_APPROVAL'))
                <td><a href="{{url('/admissionbed', array('remove_group_list_student', $id,$list->idno))}}">Remove</a></td>
                @else
                <td>Cannot Remove</td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
</div>    
@endsection
@section('footerscript')
<script src="{{ asset('build/jquery.datetimepicker.full.js')}}"></script>
<script>
$('#datetimepicker').datetimepicker({
    dayOfWeekStart: 1,
    lang: 'en'
});
$('#datetimepicker').datetimepicker();

</script>
@endsection
