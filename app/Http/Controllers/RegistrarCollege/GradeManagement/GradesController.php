<?php

namespace App\Http\Controllers\RegistrarCollege\GradeManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class GradesController extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth');
    }

    function view_grades($school_year, $period) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env("DEAN")) {
            return view('reg_college.grade_management.view_grades', compact('school_year', 'period'));
        }
    }
    
    function report_card(){
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            return view('reg_college.grade_management.report_card');
        }
    }
}
