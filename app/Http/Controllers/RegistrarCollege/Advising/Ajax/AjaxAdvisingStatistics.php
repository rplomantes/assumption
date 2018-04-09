<?php

namespace App\Http\Controllers\RegistrarCollege\Advising\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class AjaxAdvisingStatistics extends Controller
{
    //
    function get_advising_statistics() {
        if (Request::ajax()) {
            $course_code = Input::get("course_code");
            $school_year = Input::get("school_year");
            $period = Input::get("period");

            return view('reg_college.advising.ajax.get_advising_statistics', compact('course_code', 'school_year', 'period'));
        }
    }
}
