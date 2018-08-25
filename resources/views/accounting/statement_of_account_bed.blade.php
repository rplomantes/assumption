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
@section('header')
<section class="content-header">
    <h1>
        Statement of Account
        <small>Basic Education Department</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Statement of Account</li>
    </ol>
</section>
@endsection
@section('maincontent')
<form action="{{url('accounting', array('statement_of_account','print_all','bed'))}}" target="_blank" method="post">
    {{ csrf_field() }}
    <div class="box">
        <div class="box-header">
            <div class="box-title">Set Up</div>
        </div>
        <div class="form form-horizontal">
            <div class="box-body">
                <div class="col-sm-12">
                    <div class="form form-group">
                        <div class="col-sm-3" id="plan_control">
                            <label>Plan</label>
                            <select class="form form-control" name="plan" id="plan">
                                <option value="">Select Plan</option>
                                <option>ALL</option>
                                <option>Plan A</option>
                                <option>Plan B</option>
                                <option>Plan C</option>
                                <option>Plan D</option>
                            </select>
                        </div>
                        <div class="col-sm-2" id="level_control">
                            <label>Level</label>
                            <select name="level" id="level" class="form-control" onchange="get_section(this.value, strand.value)">
                                <option value="">Select Level</option>
                                <option>ALL</option>
                                @foreach($levels as $level)
                                <option>{{$level->level}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2" id="strand_control">
                            <label>Strand</label>
                            <select name="strand" id="strand" class="form-control" onchange="get_section(level.value, this.value)">
                                <option value=NULL></option>
                                <option>ALL</option>
                                @foreach ($strands as $strand)
                                <option>{{$strand->strand}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-2" id="section_control">
                            <label>Section</label>
                            <select name="section" id="section" class="form-control">
                                <option value="">Select Section</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <label>Due Date</label>
                            <input name="due_date" id="due_date" type="date" class="form-control">
                        </div>
                    </div>
                    <div class="form form-group">
                        <div class="col-sm-12">
                            <label>Remarks</label>
                            <input name="remarks" value="Please settle your account on or before the due date." id="remarks" type="text" class="form-control">
                        </div>
                    </div>
                    <div class="form form-group">
                        <div class="col-sm-12">
                            <button type="button" class="btn btn-primary col-sm-12" onclick="generate_report(plan.value, level.value, strand.value, section.value, due_date.value, remarks.value)">Generate Statement of Account</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-body" id="display_list">
            </div>
        </div>
    </div>
</form>
@endsection
@section('footerscript')

<script>
    $("#strand_control").hide();
    $("#level").on('change', function (e) {
        if ($("#level").val() == "Grade 11" || $("#level").val() == "Grade 12") {
            $("#strand_control").fadeIn(300);
        } else {
            $("#strand_control").fadeOut(300);
        }
    });

    function get_section(level, strand) {
        array = {};
        array['level'] = level;
        array['strand'] = strand;
        $.ajax({
            type: "GET",
            url: "/ajax/accounting/statement_of_account/bed/get_section",
            data: array,
            success: function (data) {
                $('#section_control').html(data);
            }

        });
    }
    function generate_report(plan, level, strand, section, due_date, remarks) {
        array = {};
        array['plan'] = plan;
        array['level'] = level;
        array['strand'] = strand;
        array['section'] = section;
        array['due_date'] = due_date;
        array['remarks'] = remarks;
        $.ajax({
            type: "GET",
            url: "/ajax/accounting/statement_of_account/bed/get_soa",
            data: array,
            success: function (data) {
                $('#display_list').html(data);

            }

        });
    }
    function print_soa_student(due_date, remarks, idno) {
        array = {};
        array['idno'] = idno;
        array['due_date'] = due_date;
        array['remarks'] = remarks;
        window.open('/accounting/statement_of_account/print/bed/' + array['remarks'] + "/" + array['due_date'] + "/" + array['idno'], "_blank");
//        window.open('/accounting/statement_of_account/print/bed/' + array['idno'], "_blank") ;
    }
</script>
@endsection
