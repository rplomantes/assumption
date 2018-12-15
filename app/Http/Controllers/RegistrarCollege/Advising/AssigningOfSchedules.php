<?php

namespace App\Http\Controllers\RegistrarCollege\Advising;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Session;

class AssigningOfSchedules extends Controller {

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

    function assign_schedule(Request $request) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $course_id = $request->course_id;
            $section_id = $request->section;
            $idno = $request->idno;
            $schedule_id = $request->schedule_id;
            $count = 0;
            $scheds = \App\CourseOffering::where('schedule_id', $schedule_id)->get();
            foreach ($scheds as $sched) {
                $lists = \App\GradeCollege::where('course_offering_id', $sched->id)->get();
                if (count($lists) > 0) {
                    foreach ($lists as $list){
                        $count = $count + 1;
                    }
                }
            }

            if ($count < 30) {
                
                $update_grade_college = \App\GradeCollege::where('id', $course_id)->first();
                if ($section_id == "dna") {
                    $update_grade_college->course_offering_id = NULL;
                } else {
                    $update_grade_college->course_offering_id = $section_id;
                }
                $update_grade_college->save();

                Session::flash('message', "Schedule Updated!");
                
            } else {
                Session::flash('danger', "Students enrolled is more than 30 students.");
            }
            
            \App\Http\Controllers\Admin\Logs::log("Assign schedule to $idno's course_id: $course_id schedule to schedule_id: $schedule_id");
            return redirect("/registrar_college/advising/assigning_of_schedules/$idno");
        }
    }

}
