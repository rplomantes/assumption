<?php

namespace App\Http\Controllers\RegistrarCollege\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use Auth;


class ChedEnrollmentReportsController extends Controller
{
    public function __contruct(){
        $this->middleware('auth');
    }
    
    function index(){
        if(Auth::user()->accesslevel == env('REG_COLLEGE')){
           $programs = \App\CollegeLevel::distinct()->where('academic_type', 'College')->orderBy('program_name')->get(['program_name','program_code']);
//           $levels= \App\CollegeLevel::distinct()->where('academic_type', 'College')->orderBy('level')->get(['level']);
//           $periods= \App\CollegeLevel::distinct()->where('academic_type', 'College')->orderBy('period')->get(['period']);
           return view('reg_college.reports.ched_enrollment_reports', compact('programs'));
        }
    }
    
    function print_report(Request $request) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {           
            $students = \App\CollegeLevel::where('program_code', $request->program_code)->where('level', $request->level)->where('school_year', $request->school_year)->where('period', $request->period)->where('status',3)->get();
            $pdf = PDF::loadView('reg_college.reports.print_ched_enrollment_report', compact('students','request'));
            $pdf->setPaper('letter','landscape');
            return $pdf->stream("student_list_.pdf");
        }
    }        
    
}
