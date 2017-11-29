<?php

namespace App\Http\Controllers\RegistrarCollege\CurriculumManagement\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use DB;
use App\CourseOffering;

class courseoffering_ajax extends Controller {

    //
    function listcurriculum($program_code) {
        if (Request::ajax()) {
            $curriculum_year = Input::get("curriculum_year");
            $level = Input::get("level");
            $period = Input::get("period");
            $section = Input::get("section");

            return view('reg_college.curriculum_management.ajax.course_to_offer', compact('program_code', 'curriculum_year', 'period', 'level', 'section'));
        }
    }

    function listcourse_offered($program_code) {
        $curriculum_year = Input::get("curriculum_year");
        $level = Input::get("level");
        $period = Input::get("period");
        $section = Input::get("section");
        if (Request::ajax()) {

            $school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first();

            return view('reg_college.curriculum_management.ajax.course_offered', compact('program_code', 'curriculum_year', 'period', 'level', 'section', 'school_year'));
        }
    }

    function add_to_course_offered($course_code) {
        $curriculum_year = Input::get("curriculum_year");
        $level = Input::get("level");
        $period = Input::get("period");
        $section = Input::get("section");
        $program_code = Input::get("program_code");

        $course_name = \App\Curriculum::distinct()->where('course_code', $course_code)->get(['course_name', 'course_code'])->first();
        $course_details = \App\Curriculum::where('course_code', $course_code)->where('program_code', $program_code)->first();
        $school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first();
        $counter = \App\CourseOffering::where('course_code', $course_code)->where('program_code', $program_code)->where('period', $school_year->period)->where('school_year', $school_year->school_year)->where('level', $level)->where('section', $section)->get();

        if (Request::ajax()) {
            if (count($counter) == 0) {
                $addsubject = new CourseOffering;
                $addsubject->program_code = $program_code;
                $addsubject->course_code = $course_code;
                $addsubject->course_name = $course_name->course_name;
                $addsubject->section = $section;
                $addsubject->school_year = $school_year->school_year;
                $addsubject->period = $school_year->period;
                $addsubject->lec = $course_details->lec;
                $addsubject->lab = $course_details->lab;
                $addsubject->hours = $course_details->hours;
                $addsubject->level = $level;
                $addsubject->percent_tuition = $course_details->percent_tuition;
                $addsubject->save();
            } else {
                
            }

            return view('reg_college.curriculum_management.ajax.course_offered', compact('program_code', 'curriculum_year', 'period', 'level', 'section', 'school_year'));
        }
    }

    function add_all_courses() {

        if (Request::ajax()) {
            $program_code = Input::get("program_code");
            $curriculum_year = Input::get("curriculum_year");
            $section = Input::get("section");
            $level = Input::get("level");
            $period = Input::get("period");
            $course_code = Input::get("course_code");

            $school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first();


            $curriculums = \App\Curriculum::where("curriculum_year", $curriculum_year)
                            ->where("period", $period)
                            ->where("program_code", $program_code)
                            ->where("level", $level)->get();

            if (count($curriculums) > 0) {
                foreach ($curriculums as $curriculum) {
                    $counter = \App\CourseOffering::where('course_code', $curriculum->course_code)->where('school_year', $school_year->school_year)->where('program_code', $curriculum->program_code)->where('period', $school_year->period)->where('level', $curriculum->level)->where('section', $section)->first();
                    if (count($counter) == 0) {
                        $addsubject = new CourseOffering;
                        $addsubject->program_code = $program_code;
                        $addsubject->course_code = $curriculum->course_code;
                        $addsubject->course_name = $curriculum->course_name;
                        $addsubject->section = $section;
                        $addsubject->school_year = $school_year->school_year;
                        $addsubject->period = $school_year->period;
                        $addsubject->lec = $curriculum->lec;
                        $addsubject->lab = $curriculum->lab;
                        $addsubject->hours = $curriculum->hours;
                        $addsubject->level = $level;
                        $addsubject->percent_tuition = $curriculum->percent_tuition;
                        $addsubject->save();
                    }
                }
            }
            return view('reg_college.curriculum_management.ajax.course_offered', compact('program_code', 'curriculum_year', 'period', 'level', 'section', 'school_year'));
        }
    }

    function remove_course($id) {

        if (Request::ajax()) {
            $program_code = Input::get("program_code");
            $curriculum_year = Input::get("curriculum_year");
            $section = Input::get("section");
            $level = Input::get("level");
            $period = Input::get("period");
            
            $school_year = \App\CtrAcademicSchoolYear::where('academic_type', 'College')->first();

            $removesubject = \App\CourseOffering::find($id);
            $removesubject->delete();

            return view('reg_college.curriculum_management.ajax.course_offered', compact('program_code', 'curriculum_year', 'period', 'level', 'section', 'school_year'));
        }
    }

}
