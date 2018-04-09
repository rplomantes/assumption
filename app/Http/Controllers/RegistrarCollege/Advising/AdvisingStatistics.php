<?php

namespace App\Http\Controllers\RegistrarCollege\Advising;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class AdvisingStatistics extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $advising_school_year = \App\CtrAdvisingSchoolYear::where('academic_type', 'College')->first();
            $courses = \App\GradeCollege::distinct()->where('school_year', $advising_school_year->school_year)->where('period', $advising_school_year->period)->where('is_advising', 1)->get(['course_code', 'course_name']);
            return view('reg_college.advising.advising_statistics', compact('advising_school_year', 'courses'));
        }
    }
}
