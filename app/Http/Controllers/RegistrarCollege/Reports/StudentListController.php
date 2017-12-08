<?php

namespace App\Http\Controllers\RegistrarCollege\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class StudentListController extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function search() {
        if (Auth::user()->accesslevel == '20') {
            $school_years = \App\CourseOffering::distinct()->get(['school_year']);
            $programs = \App\CtrAcademicProgram::distinct()->get(['program_code', 'program_name']);
            return view('reg_college.reports.student_list.search', compact('school_years', 'programs'));
        }
    }

}
