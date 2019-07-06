<?php
$courses = \App\Curriculum::where('program_code', $program_code)->where('curriculum_year', $curriculum_year)->where('period', $period)->where('level', $level)->get();
?>
<input type='hidden' id="program_code" value="{{$program_code}}">
<input type='hidden' id="curriculum_year" value="{{$curriculum_year}}">
<input type='hidden' id="level" value="{{$level}}">
<input type='hidden' id="period" value="{{$period}}">
<input type='hidden' id="section" value="{{$section}}">

<div class="box">
    <div class="box-header">
        <h3 class="box-title">Course to Offer</h3>
    </div>
    <div class="box-body">
        <div class='table-responsive'>
            <table class="table">
                <thead>
                    <tr>
                        <th>Course Code</th>
                        <th>Description</th>
                        <th>No. of Students Advised</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($courses as $course)<?php
$school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first();
?>
                    <?php $student_count = \App\GradeCollege::where('course_code', $course->course_code)->where('school_year', $school_year->school_year)->where('period', $school_year->period)->where('is_advising', 1)->get(); ?>
                <input type="hidden" id="course_code" value="{{$course->course_code}}">
                <input type="hidden" id="course_name" value="{{$course->course_name}}">
                <tr>
                    <td>{{$course->course_code}}</td>
                    <td>{{$course->course_name}}</td>
                    <td>{{count($student_count)}}</td>
                    <td><button class="btn btn-success" onclick="addtocourseoffering_tutorials('{{$course->course_code}}')"><span class="fa fa-plus-circle"></span></button></td>
                </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>