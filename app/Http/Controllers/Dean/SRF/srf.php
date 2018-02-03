<?php

namespace App\Http\Controllers\Dean\SRF;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;

class srf extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        if (Auth::user()->accesslevel == env('DEAN')) {
            $programs = \App\Curriculum::distinct()->get(['program_code', 'program_name']);
            return view('dean.srf.view_srf', compact('programs'));
        }
    }
    
    function modify_srf($course_code){
        if (Auth::user()->accesslevel == env('DEAN')) {
            $course_name = \App\Curriculum::where('course_code', $course_code)->first()->course_name;
            $srf = \App\Curriculum::where('course_code', $course_code)->first()->srf;
            return view('dean.srf.modify_srf', compact('course_code', 'course_name', 'srf'));
        }
    }
    
    function set_srf(Request $request){
        if (Auth::user()->accesslevel == env('DEAN')) {
            $course_code = $request->course_code;
            $sets = \App\Curriculum::where('course_code', $course_code)->get();
            
            foreach ($sets as $set){
                $set->srf = $request->srf;
                $set->save();
            }
            return redirect("dean/srf/modify/$course_code");
        }
    }
    
    function print_index(){
        if (Auth::user()->accesslevel == env('DEAN')) {
            $programs = \App\Curriculum::distinct()->get(['program_code', 'program_name']);
            $curriculum_years = \App\Curriculum::distinct()->get(['curriculum_year']);
            return view('dean.srf.print_index', compact('curriculum_years', 'programs'));
        }
    }
    
    function print_srf_now($program_code, $level, $period, $curriculum_year){
        if (Auth::user()->accesslevel == env('DEAN')) {
            $programs = \App\Curriculum::where('program_code', $program_code)->where('level', $level)->where('period', $period)->where('curriculum_year', $curriculum_year)->get();
            $program_name = \App\CtrAcademicProgram::where('program_code', $program_code)->first()->program_name;

            $pdf = PDF::loadView('dean.srf.print_srf_report', compact('programs', 'program_name', 'program_code', 'level', 'period', 'curriculum_year'));
            $pdf->setPaper(array(0, 0, 612.00, 792.0));
            return $pdf->stream("srf_report.pdf");
            
        }
    }
}
