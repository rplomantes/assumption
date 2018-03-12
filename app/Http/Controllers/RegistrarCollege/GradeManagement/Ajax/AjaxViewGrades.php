<?php

namespace App\Http\Controllers\RegistrarCollege\GradeManagement\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class AjaxViewGrades extends Controller {

    //
    function view_grades() {
        $course_code = Input::get("course_code");
        $school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first()->school_year;
        $period = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first()->period;
        $schedules = \App\CourseOffering::distinct()->where('course_code', $course_code)->where('school_year', $school_year)->where('period', $period)->get(['schedule_id']);
        
        return view('reg_college.grade_management.ajax.display_schedule', compact('schedules'));
    }

}
