<?php

namespace App\Http\Controllers\RegistrarCollege\Advising;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Session;

class AssigningOfSchedules extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index($idno) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $advising_school_year = \App\CtrAdvisingSchoolYear::where('academic_type', 'College')->first();
            
            $grades = \App\GradeCollege::where('idno', $idno)->where('school_year', $advising_school_year->school_year)->where('period', $advising_school_year->period)->get();
            
            return view('reg_college.advising.assigning_of_schedules', compact('advising_school_year', 'idno', 'grades'));
        }
    }
    
    function assign_schedule(Request $request){
        $course_id = $request->course_id;
        $section_id = $request->section;
        $idno = $request->idno;
        
        $update_grade_college = \App\GradeCollege::where('id', $course_id)->first();
        $update_grade_college->course_offering_id = $section_id;
        $update_grade_college->is_advising = 0;
        $update_grade_college->save();
        
        Session::flash('message', "Schedule Updated!");
        return redirect("/registrar_college/advising/assigning_of_schedules/$idno");
    }
}
