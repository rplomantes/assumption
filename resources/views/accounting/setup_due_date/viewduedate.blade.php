<?php 
    if (Auth::user()->accesslevel==env("ACCTNG_STAFF")){
        $layout = "layouts.appaccountingstaff";    
    }else if (Auth::user()->accesslevel==env("ACCTNG_HEAD")){
        $layout = "layouts.appaccountinghead";    
    }
?>
@extends($layout) 
@section('header')
<section class="content-header">    
    <h1>
        Set-up
        <small>Due Date</small>
    </h1>
      <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Set-up Due Date</li>
      </ol>
</section>
@endsection
@section('maincontent')
 <!-- search form (Optional) -->
@if(Session::has('warning'))
<script type="text/javascript">
    toastr.warning(' <?php echo Session::get('warning'); ?>', 'Message!');
</script>
@endif

@if(Session::has('success'))
<script type="text/javascript">
    toastr.success(' <?php echo Session::get('success'); ?>', 'Message!');
</script>
@endif

<div class="row">
    <div class="col-sm-12">
        <div class="box box-primary box-solid">
            <div class="box-header">
                <h5 class="box-title">Due dates for {{$plan}}</h5>
                <div class="box-tools pull-right">
                    <!--<button class="btn btn-flat btn-success" data-target="#new-modal" data-toggle="modal">New Due Date</button>-->
                </div>
            </div>
            <div class="box-body">
                @if(Session::has('success'))
                <div class='alert alert-success'>
                    <h5>{{Session::get('success')}}</h5>
                </div>
                @endif
                
                <div class="table table-reponsive">
                    <form method="post" action="{{url('/accounting/setup/update_due_date')}}">
                        {{csrf_field()}}
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Due Date</th>
                                    @if($academic_type == "College")
                                    <th>Percentage Breakdown</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                <input type="hidden" name="plan" value="{{$plan}}">
                                <input type="hidden" name="academic_type" value="{{$academic_type}}">
                                @foreach($duedates->unique("due_date") as $duedate)
                                <tr>
                                    <td width="50%"><input type="date" name="new_dates[{{$duedate->due_date}}]" class="form-control" value="{{$duedate->due_date}}"></td>
                                    @if($academic_type == "College")
                                    <td><input readonly="" type="text" class="form-control" value="{{$duedate->percentage}}"></td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="form-group">
                            <button onclick='return confirm("Do you wish to Continue?")' type="submit" class="btn btn-flat btn-warning btn-block">Update Due Dates</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


@endsection
@section('footerscript')
<script>
function updatedate(date_id, date){
       $("#date-id").val(date_id);
       $("#date-value").val(date);
       $("#edit-modal").modal("show");
   }
</script>    
@endsection
