<?php
$coursesoffered = \App\CourseOffering::where('program_code', $program_code)->where('school_year', $school_year->school_year)->where('period', $school_year->period)->where('level', $level)->where('section', $section)->get();
?>
<div class="box">
    <div class="box-header">
        <h3 class="box-title">Courses Offered</h3>
    </div>
    <div class="box-body">
        <div class='table-responsive'>
        <table class="table table-striped table-hover">
            <thead>
                <tr>
                    <th>Course Code</th>
                    <th>Description</th>
                    <th>Action</th>
                </tr>
                @foreach ($coursesoffered as $courseoffered)
                <tr>
                    <td>{{$courseoffered->course_code}}</td>
                    <td>{{$courseoffered->course_name}}</td>
                    <td><button class="btn btn-danger" onclick="removecourse('{{$courseoffered->id}}')"><span class="fa fa-minus-circle"></span></button></td>
                </tr>
                @endforeach
            </thead>
        </table>
        </div>
    </div>
</div>