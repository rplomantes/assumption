<?php

namespace App\Http\Controllers\RegistrarCollege\CurriculumManagement\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class view_course_offering_ajax extends Controller
{
    //
    function get_sections() {
        if (Request::ajax()) {
            $school_year = Input::get("school_year");
            $period = Input::get("period");
            $program_code = Input::get("program_code");
            $level = Input::get("level");
            
            $sections = \App\CourseOffering::distinct()->where('school_year', $school_year)->where('period', $period)->where('program_code', $program_code)->where('level', $level)->get(['section', 'section_name']);
            $data = "<label>Section</label><select id=\"section\" class=\"form-control select2\" style=\"width: 100%;\"><option value=\"\">Select section</option>";
                foreach ($sections as $section){
                    $data = $data . "<option value='". $section->section_name ."'>". $section->section_name ."</option>";
                }
            
            $data = $data . "</select>";
            return $data;
        }
    }
    function get_offerings() {
        if (Request::ajax()) {
            $school_year = Input::get("school_year");
            $period = Input::get("period");
            $program_code = Input::get("program_code");
            $level = Input::get("level");
            $section = Input::get("section");
            
            $courses = \App\CourseOffering::where('school_year', $school_year)->where('period', $period)->where('program_code', $program_code)->where('level', $level)->where('section_name', $section)->get();
            
            return view('reg_college.curriculum_management.ajax.show_offerings', compact('courses', 'school_year', 'period', 'program_code', 'level','section'));
            
        }
    }
}
