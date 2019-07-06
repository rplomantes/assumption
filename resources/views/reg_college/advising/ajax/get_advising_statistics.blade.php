<?php $course_name = \App\GradeCollege::distinct()->where('course_code', $course_code)->first(['course_name'])->course_name; ?>
<?php $student_count = \App\GradeCollege::where('course_code', $course_code)->where('school_year', $school_year)->where('period', $period)->where('is_advising', 1)->get(); ?>
<?php $lec = \App\GradeCollege::distinct()->where('course_code', $course_code)->first(['lec'])->lec; ?>
<?php $lab = \App\GradeCollege::distinct()->where('course_code', $course_code)->first(['lab'])->lab; ?>
<?php $details = \App\GradeCollege::where('course_code', $course_code)->where('school_year', $school_year)->where('period', $period)->where('is_advising', 1)->get(); ?>
<div class="box">
    <div class="box-header">
        <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
        </div>
    </div>
    <div class="box-body">
        <table class="table">
            <thead>
                <tr>
                    <th>Course Code</th>
                    <th>Course Name</th>
                    <th>Lec</th>
                    <th>Lab</th>
                    <th>Scheduling</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{$course_code}}</td>
                    <td>{{$course_name}}</td>
                    <td>{{$lec}}</td>
                    <td>{{$lab}}</td>
                    <td><a target="_blank" href="{{url('/registrar_college', array('advising','sectioning', $course_code))}}"><button>Sectioning</button></a></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>