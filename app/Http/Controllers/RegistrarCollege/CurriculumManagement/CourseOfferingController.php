<?php

namespace App\Http\Controllers\RegistrarCollege\CurriculumManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class CourseOfferingController extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        if (Auth::user()->accesslevel == '20') {
            return view('reg_college.curriculum_management.course_offering');
        }
    }

    function viewofferings($program_code) {
        if (Auth::user()->accesslevel == '20') {
            return view('reg_college.curriculum_management.view_course_offering', compact('program_code'));
        }
    }

}
