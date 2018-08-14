<?php

namespace App\Http\Controllers\RegistrarCollege\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;

class NstpReportsController extends Controller
{
    function index(){
        if(Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel==env('DEAN')){
            $programs = \App\Curriculum::distinct()->where('course_code', 'like' ,'%NSTP%')->get(['course_code','course_name']);
            return view('reg_college.reports.nstp_reports', compact('programs'));
        }    
    }
    
    function print_nstp(Request $request){
        if(Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel==env('DEAN')){
            $students = \App\GradeCollege::
                    join('college_levels', 'college_levels.idno','=','grade_colleges.idno')
                    ->where('college_levels.status', '3')
                    ->where('grade_colleges.school_year', $request->school_year)
                    ->where('grade_colleges.period', $request->period)
                    ->where('grade_colleges.course_code', $request->course_code)
                    ->get();
//            $programs = \App\CtrAcademicProgram::distinct()->where('school_year', $request->school_year)->where('period', $request->period)->where('course_code', $request->course_code)->get([]);
            $programs = \App\CtrAcademicProgram::distinct()->where('academic_type', 'College')->get(['program_code', 'program_name']);
            $pdf = PDF::loadView('reg_college.reports.print_nstp_reports', compact('programs', 'request', 'students'));
            $pdf->setPaper('letter','landscape');
            return $pdf->stream('nstp_reports.pdf');
        }    
    }   
}