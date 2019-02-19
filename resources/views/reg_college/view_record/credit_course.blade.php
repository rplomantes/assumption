@extends('layouts.appreg_college')
@section('messagemenu')
<li class="dropdown messages-menu">
    <!-- Menu toggle button -->
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-envelope-o"></i>
        <span class="label label-success"></span>
    </a>
</li>
<li class="dropdown notifications-menu">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-bell-o"></i>
        <span class="label label-warning"></span>
    </a>
</li>

<li class="dropdown tasks-menu">
    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-flag-o"></i>
        <span class="label label-danger"></span>
    </a>
</li>
@endsection
@section('header')
<section class="content-header">
    <h1>
        Credited Courses(Transferee)
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/"><i class="fa fa-home"></i> Home</a></li>
        <li class="active">Credited Courses(Transferee)</li>
    </ol>
</section>
@endsection
@section('maincontent')

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class='box'>
                <div class='box-header'>
                    <div class='box-title'>Credit Course</div>
                </div>
                <div class='box-body'>
                    <form class='form form-horizontal' method="post" action="{{url('registrar_college',array('add_now','credit_course'))}}">
                        {{ csrf_field() }}
                        <input type="hidden" name="idno" value="{{$user->idno}}">

                        <?php $i = 0; ?>
                        <div  id="dynamic_field_credit">
                            <!--div class="top-row"-->
                            <?php $credits = \App\CollegeCredit::where('idno', $user->idno)->get(); ?>
                            @if(count($credits)>0)
                            <div class="form form-group">
                                <div class="col-md-1">
                                    <label>School Year</label>
                                </div>
                                <div class="col-md-1">
                                    <label>Period</label>
                                </div>
                                <div class="col-md-2">
                                    <label>School Name</label>
                                </div>
                                <div class="col-md-1">
                                    <label>Code</label>
                                </div>
                                <div class="col-md-2">
                                    <label>Course Name</label>
                                </div>
                                <div class="col-md-1">
                                    <label>Unit</label>
                                </div>
                                <div class="col-md-1">
                                    <label>Final Grade</label>
                                </div>
                                <div class="col-md-1">
                                    <label>Completion</label>
                                </div>
                                <div class="col-md-1">
                                    <label>Credit Course</label>
                                </div>
                            </div>
                            @foreach($credits as $credit)
                            <div id='row_credit{{$i}}' class="form form-group">
                                <div class="col-md-1">
                                    <input class="form form-control limitation" type="text" name="credit_sy[{{$i}}]" id='credit_sy{{$i}}' value='{{$credit->school_year}}'/>
                                </div>
                                <div class="col-md-1">
                                    <select class="form form-control limitation" type="text" name="credit_period[{{$i}}]" id='credit_period{{$i}}' value='{{$credit->period}}'>
                                        <option value="">Select Period</option>
                                        <option @if($credit->period == "1st Semester") selected="" @endif>1st Semester</option>
                                        <option @if($credit->period == "2nd Semester") selected="" @endif>2nd Semester</option>
                                        <option @if($credit->period == "Summer") selected="" @endif>Summer</option>
                                        <option @if($credit->period == "1st Quarter") selected='' @endif>1st Quarter</option>
                                        <option @if($credit->period == "2nd Quarter") selected='' @endif>2nd Quarter</option>
                                        <option @if($credit->period == "3rd Quarter") selected='' @endif>3rd Quarter</option>
                                        <option @if($credit->period == "4th Quarter") selected='' @endif>4th Quarter</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <input class="form form-control limitation" type="text" name="credit_school_name[{{$i}}]" id='credit_school_name{{$i}}' value='{{$credit->school_name}}'/>
                                </div>
                                <div class="col-md-1">
                                    <input class="form form-control limitation" type="text" name="credit_course_code[{{$i}}]" id='credit_course_code{{$i}}' value='{{$credit->course_code}}'/>
                                </div>
                                <div class="col-md-2">
                                    <input class="form form-control limitation" type="text" name="credit_course_name[{{$i}}]" id='credit_course_name{{$i}}' value='{{$credit->course_name}}'/>
                                </div>
                                <div class="col-md-1">
                                    <input class="form form-control limitation" type="text" name="credit_unit[{{$i}}]" id='credit_unit{{$i}}' value='{{$credit->lec}}'/>
                                </div>
                                <div class="col-md-1">
                                    <input class="form form-control limitation" type="text" name="credit_finals[{{$i}}]" id='credit_finals{{$i}}' value='{{$credit->finals}}'/>
                                </div>
                                <div class="col-md-1">
                                    <input class="form form-control limitation" type="text" name="credit_completion[{{$i}}]" id='credit_completion{{$i}}' value='{{$credit->completion}}'/>
                                </div>
                                <div class="col-md-1">
                                    <select class="form form-control limitation select2" type="text" name="credit_credit_code[{{$i}}]" id='credit_credit_code{{$i}}' value='{{$credit->credit_code}}'>
                                        <?php $levels = \App\Curriculum::distinct()->where('curriculum_year', $student_info->curriculum_year)->where('program_code', $student_info->program_code)->get(['course_code', 'course_name']); ?>
                                        <option value="">Select Course</option>
                                        @foreach ($levels as $level)
                                        <option @if($level->course_code == $credit->credit_code) selected='' @endif value="{{$level->course_code}}">{{$level->course_code}}-{{$level->course_name}}</option>
                                        @endforeach    
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    @if($i == 0)
                                    <button type="button" name="add_credit" id="add_credit" class="btn btn-success"> + </button>
                                    @else
                                    <button type='button' name="remove_credit" id="{{$i}}" class="btn btn-danger btn_remove btn_remove_credit">X</button>
                                    @endif
                                </div>
                            </div>

                            <?php $i = $i + 1; ?>
                            @endforeach
                            @else
                            <div class="form form-group">
                                <div class="col-md-1">
                                    <label>School Year</label>
                                    <input class="form form-control limitation" type="text" name="credit_sy[]" id='credit_sy1'/>
                                </div>
                                <div class="col-md-1">
                                    <label>Period</label>
                                    <select class="form form-control limitation" type="text" name="credit_period[]" id='credit_period1'>
                                        <option value="">Select Period</option>
                                        <option>1st Semester</option>
                                        <option>2nd Semester</option>
                                        <option>Summer</option>
                                        <option>1st Quarter</option>
                                        <option>2nd Quarter</option>
                                        <option>3rd Quarter</option>
                                        <option>4th Quarter</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>School Name</label>
                                    <input class="form form-control limitation" type="text" name="credit_school_name[]" id='credit_school_name1'/>
                                </div>
                                <div class="col-md-1">
                                    <label>Code</label>
                                    <input class="form form-control limitation" type="text" name="credit_course_code[]" id='credit_course_code1'/>
                                </div>
                                <div class="col-md-2">
                                    <label>Course Name</label>
                                    <input class="form form-control limitation" type="text" name="credit_course_name[]" id='credit_course_name1'/>
                                </div>
                                <div class="col-md-1">
                                    <label>Unit</label>
                                    <input class="form form-control limitation" type="text" name="credit_unit[]" id='credit_unit1'/>
                                </div>
                                <div class="col-md-1">
                                    <label>Final Grade</label>
                                    <input class="form form-control limitation" type="text" name="credit_finals[]" id='credit_finals1'/>
                                </div>
                                <div class="col-md-1">
                                    <label>Completion</label>
                                    <input class="form form-control limitation" type="text" name="credit_completion[]" id='credit_completion1'/>
                                </div>
                                <div class="col-md-2">
                                    <label>Credit Course</label>
                                    <select class="form form-control limitation select2" type="text" name="credit_credit_code[]" id='credit_credit_code1'>
                                        <?php $levels = \App\Curriculum::distinct()->where('curriculum_year', $student_info->curriculum_year)->where('program_code', $student_info->program_code)->get(['course_code', 'course_name']); ?>
                                        <option value="">Select Course</option>
                                        @foreach ($levels as $level)
                                        <option value="{{$level->course_code}}">{{$level->course_code}}-{{$level->course_name}}</option>
                                        @endforeach    
                                    </select>
                                </div>
                                <div class="col-md-1">
                                    <button type="button" name="add_credit" id="add_credit" class="btn btn-success"> + </button>
                                </div>
                            </div>
                            @endif
                        </div>

                        <label>&nbsp;</label>
                        <input type='submit' class='col-sm-12 btn btn-success' value = "Credit Courses">
                    </form>  
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
@section('footerscript')
<script>
    var i = "{{$i-1}}";
    $(document).ready(function () {
        $('#add_credit').click(function () {

            i++;
            $('#dynamic_field_credit').append(
          '<div id="row_credit' + i + '" class="form form-group">\n\
           <div class="col-md-1"><input class="form form-control" type="text" name="credit_sy[]" id="credit_sy' + i +'"/></div>\n\
           <div class="col-md-1"><select class="form form-control" type="text" name="credit_period[]" id="credit_period' + i +'"><option>Select Period</option><option>1st Semester</option><option>2nd Semester</option><option>Summer</option><option>1st Quarter</option><option>2nd Quarter</option><option>3rd Quarter</option><option>4th Quarter</option></select></div>\n\
           <div class="col-md-2"><input class="form form-control" type="text" name="credit_school_name[]" id="credit_school_name' + i +'"/></div>\n\
           <div class="col-md-1"><input class="form form-control" type="text" name="credit_course_code[]" id="credit_course_code' + i +'"/></div>\n\
           <div class="col-md-2"><input class="form form-control" type="text" name="credit_course_name[]" id="credit_course_name' + i +'"/></div>\n\
           <div class="col-md-1"><input class="form form-control" type="text" name="credit_unit[]" id="credit_unit' + i +'"/></div>\n\
           <div class="col-md-1"><input class="form form-control" type="text" name="credit_finals[]" id="credit_finals' + i +'"/></div>\n\
           <div class="col-md-1"><input class="form form-control" type="text" name="credit_completion[]" id="credit_completion' + i +'"/></div>\n\
           <div class="col-md-1"><select class="form form-control limitation select2" type="text" name="credit_credit_code[]" id="credit_credit_code' + i +'"><option value="">Select Course</option>'
            @foreach($levels as $level) + '<option value="{{$level->course_code}}">{{$level->course_code}} - {{$level->course_name}}</option>'  @endforeach
                    + '</select></div>\n\
           <div class="col-md-1"><a href="javascript:void()" name="remove_credit"  id="' + i + '" class="btn btn-danger btn_remove btn_remove_credit">X</a></div></div>');

    updatefunction()
        });
        $('#dynamic_field_credit').on('click', '.btn_remove_credit', function () {
            var button_id = $(this).attr("id");
            $("#row_credit" + button_id + "").remove();
            i--;
        });
    })
    
    
    function updatefunction(){
    $('.select2').select2();
    }
</script>
@endsection
