<?php
$levels = \App\CtrAcademicProgram::distinct()->orderBy('level', 'asc')->get(['level']);
$strands = \App\CtrAcademicProgram::selectRaw("distinct strand")->where('academic_code', 'SHS')->get();
$programs = \App\CtrAcademicProgram::selectRaw("distinct program_name, program_code")->where('academic_type', 'College')->get();
?>
<?php
if (Auth::user()->accesslevel == env("ACCTNG_STAFF")) {
    $layout = "layouts.appaccountingstaff";
} else if (Auth::user()->accesslevel == env("ACCTNG_HEAD")) {
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
        Student Related Fees
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{url("/")}}"><i class="fa fa-dashboard"></i> Home</a></li>
        <li class="active">Student Related Fees</li>
    </ol>
</section>
@endsection
@section('maincontent')
<div class="box">
    <div class="box-header">
        <!--<div class="box-title">Search</div>-->
    </div>
    <div class="box-body form-horizontal">
        <form id="myForm" method="post" target='_blank' action="">
            {{ csrf_field() }}
            <div class="form-group">
                <div class="col-sm-3">
                    <label>Select Department</label>
                    <select class="form form-control" name="department" id="department">
                        <option>Select Department</option>
                        <option>Senior High School</option>
                        <option>College Department</option>
                    </select>
                </div>
                <div class="col-sm-3" id="school_year_control">
                    <label>School Year</label>
                    <select name="school_year" class="form form-control" id="school_year">
                        <option value="">Select School Year</option>
                        <option value="2018">2018-2019</option>
                        <option value="2019">2019-2020</option>
                        <option value="2020">2020-2021</option>
                        <option value="2021">2021-2022</option>
                    </select>
                </div>
                <div class="col-sm-3" id="period_control">
                    <label>Period</label>
                    <select name="period" class="form form-control" id="period">
                        <option></option>
                        <option>1st Semester</option>
                        <option>2nd Semester</option>
                        <option>Summer</option>
                    </select>
                </div>
                <div class="col-sm-3">
                    <br>
                    <a href='javascript:void(0)' class='btn btn-primary col-sm-12' onclick='generate_report(department.value)'>Generate Report</button></a>
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-3">
                    <input type="submit" class="btn btn-success form-control" onclick="toPDF()" value="Generate PDF" >
                </div>
                <div class="col-sm-3">
                    <input type="submit" class="btn btn-warning form-control" onclick="toEXCEL()" value="Generate EXCEL" >
                </div>
            </div>
        </form>
    </div>
    <div class='box-body'>

        <div class='col-sm-12' id='display_result'></div>
    </div>
    
</div>
@endsection
@section('footerscript') 
<script>
    $("#period_control").hide();
    $("#department").on('change', function (e) {
        if ($("#department").val() == "College Department" || $("#department").val() == "Senior High School") {
            $("#period_control").fadeIn(300);
        } else {
            $("#period_control").fadeOut(300);
        }
    });
    
    
    function toPDF() {
        document.getElementById("myForm").action = "{{url('/accounting/print_student_related_fees_pdf')}}";
    }

    function toEXCEL() {
        document.getElementById("myForm").action = "{{url('/accounting/print_student_related_fees_excel')}}";
    }

    function generate_report(department) {
        var array = {};
        array['department'] = department;
        array['school_year'] = $("#school_year").val();
        array['period'] = $("#period").val();
        $.ajax({
            type: "GET",
            url: "/accounting/ajax/getstudentrelatedfees",
            data: array,
            success: function (data) {
                $("#display_result").html(data)
            }
        });
    }
</script>
@endsection
