@extends('layouts.appbedregistrar')
@section('messagemenu')
<li class="dropdown messages-menu">
    <!-- Menu toggle button -->
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-envelope-o"></i>
        <span class="label label-success">1</span>
    </a>
    <ul class="dropdown-menu">
        <li class="header">You have 1 messages</li>
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
                            <small><i class="fa fa-clock-o"></i> 1 mins</small>
                        </h4>
                        <!-- The message -->
                        <p>Welcome to Assumption Student Portal!!<br>More functionality and features coming.<br>Enjoy!!</p>
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
        Request Form
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>

    </ol>
</section>
@endsection
@section('maincontent')
<div class="col-md-12">

    @if(count($forms_request)>0)
    <div class="box box-body">
        <div class="table-responsive">
        <table class="table table-condensed table-responsive">
            <tr>
                <th>Reference No</th>
                <th>Date Requested</th>
                <th>ID No</th>
                <th>Requested By</th>
                <th>Requests</th>
                <th>Purpose</th>
                <th>Amount</th>
                <th>OR Number</th>
                <th>Date Claimed</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            @foreach($forms_request as $form_requested)
            <tr>
                <td>{{$form_requested->reference_id}}</td>
                <td>{{$form_requested->created_at}}</td>
                <td>{{$form_requested->idno}}</td>
                <td>{{$form_requested->getFullNameAttribute()}}</td>
                <?php $request_lists = \App\RequestList::where('reference_id',$form_requested->reference_id)->get(); ?>
                <td>
                    @if(count($request_lists)>0)
                    @foreach($request_lists as $request_list)
                    {{$request_list->document_name}}-{{$request_list->cost}}<br>
                    @endforeach
                    @endif
                </td>
                <td>{{$form_requested->purpose}}</td>
                <td>{{$form_requested->amount_pay}}</td>
                <td>{{$form_requested->or_number}}</td>
                <td>{{$form_requested->claim_date}}</td>
                <td>@if($form_requested->status==0) Pending @elseif($form_requested->status==1) Paid @elseif($form_requested->status==2) Claimed @endif</td>
                <td>
                    @if($form_requested->status==0)
                    <a href="javascript:void(0)" data-toggle="modal" data-target="#modal-view_form" onclick="getForm('{{$form_requested->reference_id}}')">Paid</a>
                    @elseif($form_requested->status==1)
                    <a href="{{url('tag_as_claimed',$form_requested->reference_id)}}">Claim</a>
                    @else
                    @endif
                
                </td>
            </tr>
            @endforeach
        </table>
    </div>
    </div>
    @endif
</div>    


<!--View Form-->
<div class="modal fade" id="modal-view_form">
</div>


@endsection
@section('footerscript') 

<script>
            function getForm(reference_id) {
            var array = {};
            array['reference_id'] = reference_id;
            $.ajax({
            type: "get",
                    url: "/get_requestforms",
                    data: array,
                    success: function (data) {
                    $("#modal-view_form").html(data);
                    }
            })
            }
</script>

@endsection