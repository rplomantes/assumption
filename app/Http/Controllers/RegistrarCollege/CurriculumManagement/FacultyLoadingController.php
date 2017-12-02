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
        if (Auth::user()->accesslevel == '20') {
            return view('reg_college.curriculum_management.faculty_loading');
        }
    }

    function edit_faculty_loading($idno) {
        if (Auth::user()->accesslevel == '20') {
            return view('reg_college.curriculum_management.edit_faculty_loading', compact('idno'));
        }
    }
    
    function add_faculty_loading(Request $request) {
        if (Auth::user()->accesslevel == '20') {
            $instructor_id = $request->instructor_id;
            $course_offering_id = $request->course_offering_id;
            
            $updatecourse_offering = \App\CourseOffering::where('id', $course_offering_id)->first();
            $updatecourse_offering->instructor_id = $instructor_id;
            $updatecourse_offering->save();
            return redirect("/registrar_college/curriculum_management/edit_faculty_loading/$instructor_id");
        }
    }
    
    function remove_faculty_loading($id, $idno) {
        if (Auth::user()->accesslevel == '20') {
            $updatecourse_offering = \App\CourseOffering::where('id', $id)->first();
            $updatecourse_offering->instructor_id = NULL;
            $updatecourse_offering->save();
            
            return redirect("/registrar_college/curriculum_management/edit_faculty_loading/$idno");
        }
    }

}
