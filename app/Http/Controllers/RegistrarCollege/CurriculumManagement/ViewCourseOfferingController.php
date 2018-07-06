<?php

namespace App\Http\Controllers\RegistrarCollege\CurriculumManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;

class ViewCourseOfferingController extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            return view('reg_college.curriculum_management.view_full_course_offering');
        }
    }
    
    function index2() {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            return view('reg_college.curriculum_management.view_full_course_offering_room');
        }
    }
    
    function index3() {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            return view('reg_college.curriculum_management.view_full_course_offering_general');
        }
    }
    
    function print_offerings(Request $request){
 
        if (Auth::user()->accesslevel == env('REG_COLLEGE')){
            $courses = \App\CourseOffering::where('school_year', $request->school_year)->where('period', $request->period)->where('program_code', $request->program_code)->where('level', $request->level)->where('section_name', $request->section)->get();
//            $schedules = \App\CourseOffering::where('school_year', $request->school_year)->where('period', $request->period)->where('program_code', $request->program_code)->where('level', $request->level)->where('section_name', $request->section)->get();
            $pdf = PDF::loadView('reg_college.curriculum_management.print_show_offerings',compact('courses','request'));
            $pdf->setPaper('letter','landscape');
          return $pdf->stream('course_offerings.pdf');           
        }        
    }
    
    function print_offerings_room(Request $request){
 
        if (Auth::user()->accesslevel == env('REG_COLLEGE')){
            $courses = \App\ScheduleCollege::distinct()->where('school_year', $request->school_year)->where('period', $request->period)->where('room', $request->room)->get(['schedule_id','course_code','course_offering_id']);
            $pdf = PDF::loadView('reg_college.curriculum_management.print_show_offerings_room',compact('courses','request'));
            $pdf->setPaper('letter','landscape');
          return $pdf->stream('course_offerings.pdf');           
        }        
    }

    function print_offerings_general(Request $request){
 
        if (Auth::user()->accesslevel == env('REG_COLLEGE')){
            $courses = \App\CourseOffering::where('school_year', $request->school_year)->where('period', $request->period)->orderBy('course_code')->get();
                       
            $pdf = PDF::loadView('reg_college.curriculum_management.print_show_offerings_general',compact('courses','request'));
            $pdf->setPaper('letter','landscape');
          return $pdf->stream('course_offerings_general.pdf');           
        }        
    }       
}
