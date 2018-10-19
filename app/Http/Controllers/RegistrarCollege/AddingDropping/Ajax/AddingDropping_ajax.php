<?php

namespace App\Http\Controllers\RegistrarCollege\AddingDropping\Ajax;

use App\Http\Controllers\Controller;
use DB;
use Request;
use Illuminate\Support\Facades\Input;
use Auth;

class AddingDropping_ajax extends Controller {

    //
    function index() {
        if (Request::ajax()) {
            $search = Input::get("search");
            $idno = Input::get("idno");
            $school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first();
            $courses = \App\CourseOffering::distinct()->whereRaw("(course_name like '%$search%' or course_code = '$search')")->where('school_year', $school_year->school_year)->where('period', $school_year->period)->get(array('course_code', 'course_name', 'lec', 'lab', 'srf', 'lab_fee', 'percent_tuition'));

            return view('reg_college.adding_dropping.ajax.show_courses', compact('courses', 'school_year', 'idno'));
        }
    }

    function adding() {
        if (Request::ajax()) {
            $course_code = Input::get("course_code");
            $idno = Input::get("idno");
            $school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first();

            $add_to_grade = \App\CourseOffering::where('course_code', $course_code)->where('school_year', $school_year->school_year)->where('period', $school_year->period)->first();

//            $checkcourse = \App\GradeCollege::where('idno', $idno)->where('course_code', $add_to_grade->course_code)
//                    ->where(function ($query){
//                        $query->whereRaw('finals NOT LIKE "FAILED" or finals NOT LIKE "4.00" or finals NOT LIKE "FA" or finals NOT LIKE "INC" or finals NOT LIKE "NA" or finals NOT LIKE "NG" or finals NOT LIKE "UD" or finals NOT LIKE "W" or finals NOT LIKE "AUDIT"');
//                    })
//                            ->get();
            
//            if (count($checkcourse) == 0) {
            $add = new \App\AddingDropping();
            $add->idno = $idno;
            $add->course_code = $course_code;
            $add->course_name = $add_to_grade->course_name;
            $add->level = $add_to_grade->level;
            $add->lec = $add_to_grade->lec;
            $add->lab = $add_to_grade->lab;
            $add->course_name = $add_to_grade->course_name;
            $add->srf = $add_to_grade->srf;
            $add->lab_fee = $add_to_grade->lab_fee;
            $add->percent_tuition = $add_to_grade->percent_tuition;
            $add->action = "ADD";
            $add->posted_by = Auth::user()->idno;
            $add->save();
//            }else{
                
//            }
        }
    }

    function dropping() {
        if (Request::ajax()) {
            $course_id = Input::get("course_id");
            $idno = Input::get("idno");

            $remove_to_grade = \App\GradeCollege::where('id', $course_id)->first();
            
            $add = new \App\AddingDropping();
            $add->idno = $idno;
            $add->course_id = $course_id;
            $add->course_code = $remove_to_grade->course_code;
            $add->course_name = $remove_to_grade->course_name;
            $add->level = $remove_to_grade->level;
            $add->lec = $remove_to_grade->lec;
            $add->lab = $remove_to_grade->lab;
            $add->course_name = $remove_to_grade->course_name;
            $add->srf = $remove_to_grade->srf;
            $add->lab_fee = $remove_to_grade->lab_fee;
            $add->percent_tuition = $remove_to_grade->percent_tuition;
            $add->action = "DROP";
            $add->posted_by = Auth::user()->idno;
            $add->save();
        }
    }

    function show() {
        $idno = Input::get("idno");
        
        $adding_droppings = \App\AddingDropping::where('idno', $idno)->where('is_done', 0)->get();
        
        return view('reg_college.adding_dropping.ajax.show_adding_dropping', compact('adding_droppings', 'idno'));
    }

}
