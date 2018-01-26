<?php

namespace App\Http\Controllers\RegistrarCollege\CurriculumManagement\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class facultyloading_ajax extends Controller
{
    //
    function show_available_loads() {
        if (Request::ajax()) {
            $course_code = Input::get("course_code");
            $instructor_id = Input::get("instructor_id");

            return view('reg_college.curriculum_management.ajax.loads_to_schedule', compact('course_code','instructor_id'));
        }
    }
}
