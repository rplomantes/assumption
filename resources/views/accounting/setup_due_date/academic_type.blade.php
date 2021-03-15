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
        <div class="box box-solid box-primary">
            <div class="box-header">
                <h5 class="box-title">Set-up the due dates for Installment Basis</h5>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Academic Type</th>
                                <th>View</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>BED</td>
                                <td><a href="{{url("/accounting/setup/view_due_date", array("BED"))}}">View</a></td>
                            </tr>
                            <tr>
                                <td>College</td>
                                <td><a href="{{url("/accounting/setup/view_due_date", array("College"))}}">View</a></td>
                            </tr>
                        </tbody>
                    </table>
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
