<?php $course_name = \App\GradeCollege::distinct()->where('course_code', $course_code)->first(['course_name'])->course_name; ?>
<?php $student_count = \App\GradeCollege::where('course_code', $course_code)->where('school_year', $school_year)->where('period', $period)->where('is_advising', 1)->get(); ?>
<div class="box">
    <div class="box-header">
        <h3 class="box-title">{{$course_name}}</h3>
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="box-body">
        {{count($student_count)}}
    </div>
</div>