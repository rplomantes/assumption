<?php

namespace App\Http\Controllers\RegistrarCollege\Advising\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class AssignSchedule_ajax extends Controller
{
    //
    function get_section() {
        if (Request::ajax()) {
            $schedule_id = Input::get("schedule_id");
            $course_id = Input::get("course_id");
            $idno = Input::get("idno");
            
            $sections = \App\CourseOffering::where('schedule_id', $schedule_id)->get(['section', 'section_name', 'id']);

            return view('reg_college.advising.ajax.show_sections', compact('sections', 'schedule_id', 'course_id', 'idno'));
        }
    }
}
