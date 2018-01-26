<?php

namespace App\Http\Controllers\RegistrarCollege\CurriculumManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class FacultyLoadingController extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            return view('reg_college.curriculum_management.faculty_loading');
        }
    }

    function edit_faculty_loading($idno) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            return view('reg_college.curriculum_management.edit_faculty_loading', compact('idno'));
        }
    }
    
    function add_faculty_loading(Request $request) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $instructor_id = $request->instructor_id;
            $schedule_id = $request->schedule_id;
            
            $updatecourse_offering = \App\ScheduleCollege::where('schedule_id', $schedule_id)->get();
            foreach($updatecourse_offering as $updatesched){
            $updatesched->instructor_id = "$instructor_id";
            $updatesched->save();
            }
            return redirect("/registrar_college/curriculum_management/edit_faculty_loading/$instructor_id");
        }
    }
    
    function remove_faculty_loading($schedule_id, $idno) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $updatecourse_offering = \App\ScheduleCollege::where('schedule_id', $schedule_id)->get();
            foreach($updatecourse_offering as $updatesched){
            $updatesched->instructor_id = NULL;
            $updatesched->save();
            }
            
            return redirect("/registrar_college/curriculum_management/edit_faculty_loading/$idno");
        }
    }

}
