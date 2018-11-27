<?php

namespace App\Http\Controllers\RegistrarCollege\GradeManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;

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
    function print_card_pdf(Request $request){
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            
            $school_year = $request->school_year;
            $period = $request->period;
            $program_code = $request->program_code;
            $level = $request->level;
            
            $students = \App\CollegeLevel::where('school_year', $school_year)->where('period', $period)->where('program_code', $program_code)->where('level', $level)->join('users', 'users.idno', 'college_levels.idno')->orderBy('users.lastname')->get();

            $pdf = PDF::loadView('reg_college.grade_management.print_report_card', compact('school_year','period','program_code','level','students', 'request'));
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream("report_card.pdf");
        }
        
    }
}
