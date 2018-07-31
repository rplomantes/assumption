<?php

namespace App\Http\Controllers\RegistrarCollege\CurriculumManagement\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
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

            $school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first();

            return view('reg_college.curriculum_management.ajax.course_offered', compact('program_code', 'curriculum_year', 'period', 'level', 'section', 'school_year'));
        }
    }
    
    function update_section_name($program_code) {
        $curriculum_year = Input::get("curriculum_year");
        $level = Input::get("level");
        $period = Input::get("period");
        $section = Input::get("section");
        $section_name = Input::get("section_name");
        if (Request::ajax()) {

            $school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first();
            
            $coursesoffered = \App\CourseOffering::where('program_code', $program_code)->where('school_year', $school_year->school_year)->where('period', $school_year->period)->where('level', $level)->where('section', $section)->get();
            if(count($coursesoffered)>0){
                foreach ($coursesoffered as $cc){
                    $cc->section_name = "$section_name";
                    $cc->save();
                }
            }
            
            return view('reg_college.curriculum_management.ajax.course_offered', compact('program_code', 'curriculum_year', 'period', 'level', 'section', 'school_year'));
        }
    }

    function add_to_course_offered($course_code) {
        $curriculum_year = Input::get("curriculum_year");
        $level = Input::get("level");
        $period = Input::get("period");
        $section = Input::get("section");
        $section_name = Input::get("section_name");
        $program_code = Input::get("program_code");

        $course_name = \App\Curriculum::distinct()->where('course_code', $course_code)->get(['course_name', 'course_code'])->first();
        $course_details = \App\Curriculum::where('course_code', $course_code)->where('program_code', $program_code)->first();
        $school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first();
        $counter = \App\CourseOffering::where('course_code', $course_code)->where('program_code', $program_code)->where('period', $school_year->period)->where('school_year', $school_year->school_year)->where('level', $level)->where('section', $section)->get();

        if (Request::ajax()) {
            if (count($counter) == 0) {
                $addsubject = new CourseOffering;
                $addsubject->program_code = $program_code;
                $addsubject->course_code = $course_code;
                $addsubject->course_name = $course_name->course_name;
                $addsubject->section = $section;
                $addsubject->section_name = $section_name;
                $addsubject->school_year = $school_year->school_year;
                $addsubject->period = $school_year->period;
                $addsubject->lec = $course_details->lec;
                $addsubject->lab = $course_details->lab;
                $addsubject->hours = $course_details->hours;
                $addsubject->level = $level;
                $addsubject->srf = $course_details->srf;
                $addsubject->lab_fee = $course_details->lab_fee;
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
            $section_name = Input::get("section_name");
            $level = Input::get("level");
            $period = Input::get("period");

            $school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first();


            $curriculums = \App\Curriculum::where("curriculum_year", $curriculum_year)
                            ->where("period", $period)
                            ->where("program_code", $program_code)
                            ->where("level", $level)->get();

            if (count($curriculums) > 0) {
                foreach ($curriculums as $curriculum) {
                    $course_details = \App\Curriculum::where('course_code', $curriculum->course_code)->where('program_code', $program_code)->first();
                    $counter = \App\CourseOffering::where('course_code', $curriculum->course_code)->where('school_year', $school_year->school_year)->where('program_code', $curriculum->program_code)->where('period', $school_year->period)->where('level', $curriculum->level)->where('section', $section)->first();
                    if (count($counter) == 0) {
                        $addsubject = new CourseOffering;
                        $addsubject->program_code = $program_code;
                        $addsubject->course_code = $curriculum->course_code;
                        $addsubject->course_name = $curriculum->course_name;
                        $addsubject->section = $section;
                        $addsubject->section_name = $section_name;
                        $addsubject->school_year = $school_year->school_year;
                        $addsubject->period = $school_year->period;
                        $addsubject->lec = $curriculum->lec;
                        $addsubject->lab = $curriculum->lab;
                        $addsubject->hours = $curriculum->hours;
                        $addsubject->level = $level;
                        $addsubject->srf = $course_details->srf;
                        $addsubject->lab_fee = $course_details->lab_fee;
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

            $school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first();

            $removesubject = \App\CourseOffering::find($id);
            $removesubject->delete();

            return view('reg_college.curriculum_management.ajax.course_offered', compact('program_code', 'curriculum_year', 'period', 'level', 'section', 'school_year'));
        }
    }

    function addelectives() {
        $curriculum_year = Input::get("curriculum_year");
        $level = Input::get("level");
        $period = Input::get("period");
        $section = Input::get("section");
        $section_name = Input::get("section_name");
        $program_code = Input::get("program_code");
        $id = Input::get("id");

        $course_details = \App\CtrElective::where('id', $id)->first();
        $school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first();
        $counter = \App\CourseOffering::where('course_code', $course_details->course_code)->where('program_code', $course_details->program_code)->where('period', $school_year->period)->where('school_year', $school_year->school_year)->where('level', $level)->where('section', $section)->get();

        if (Request::ajax()) {
            if (count($counter) == 0) {
                $addsubject = new CourseOffering;
                $addsubject->program_code = $program_code;
                $addsubject->course_code = $course_details->course_code;
                $addsubject->course_name = $course_details->course_name;
                $addsubject->section = $section;
                $addsubject->section_name = $section_name;
                $addsubject->school_year = $school_year->school_year;
                $addsubject->period = $school_year->period;
                $addsubject->lec = $course_details->lec;
                $addsubject->lab = $course_details->lab;
                $addsubject->hours = $course_details->hours;
                $addsubject->level = $level;
                $addsubject->srf = $course_details->srf;
                $addsubject->lab_fee = $course_details->lab_fee;
                $addsubject->percent_tuition = $course_details->percent_tuition;
                $addsubject->save();
            } else {
                
            }

            return view('reg_college.curriculum_management.ajax.course_offered', compact('program_code', 'curriculum_year', 'period', 'level', 'section', 'school_year'));
        }
    }
    function getsectionname(){
        $year_level = Input::get("year_level");
        $section = Input::get("section");
        $program_code = Input::get("program_code");
        
        $school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', "College")->first();
        
        $section_name = \App\CourseOffering::where('level', $year_level)->where('school_year', $school_year->school_year)->where('period',$school_year->period)->where('section', $section)->where('program_code', $program_code)->first();

        if(count($section_name)>0){
            $data = "<label>Section Name</label>"
                    . "<input type='text' id='section_name' name='section_name' class='form-control' value='". $section_name->section_name ."' onkeyup=\"update_section_name(this.value,'".$program_code."','".$section."','".$year_level."')\">";
        } else {
            $data = "<label>Section Name</label>"
                    . "<input type='text' id='section_name' name='section_name' class='form-control' placeholder=\"Section Name\">";
        }
        return $data;
        
    }

}
