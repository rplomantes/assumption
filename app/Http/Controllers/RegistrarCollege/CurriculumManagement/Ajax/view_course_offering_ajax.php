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

    function get_rooms() {
        if (Request::ajax()) {
            $school_year = Input::get("school_year");
            $period = Input::get("period");
//            $program_code = Input::get("program_code");
//            $level = Input::get("level");
            
//                $rooms = \App\ScheduleCollege::distinct()->where('school_year', $school_year)->where('period', $period)->where('program_code', $program_code)->where('level', $level)->get(['room']);
                $rooms = \App\ScheduleCollege::distinct()->where('school_year', $school_year)->where('period', $period)->get(['room']);
            $data = "<label>Room</label><select id=\"room\" class=\"form-control select2\" style=\"width: 100%;\"><option value=\"\">Select room</option>";
                foreach ($rooms as $room){
                    $data = $data . "<option value='". $room->room ."'>". $room->room ."</option>";
                }
            
            $data = $data . "</select>";
            return $data;
        }
    }

    function get_offerings_room() {
        if (Request::ajax()) {
            $school_year = Input::get("school_year");
            $period = Input::get("period");
//            $program_code = Input::get("program_code");
//            $level = Input::get("level");
            $room = Input::get("room");
            
            $courses = \App\ScheduleCollege::distinct()->where('school_year', $school_year)->where('period', $period)->where('room', $room)->get(['schedule_id','course_code']);
            
            return view('reg_college.curriculum_management.ajax.show_offerings_room', compact('courses', 'school_year', 'period', 'room'));
            
        }
    }    

    function get_general() {
        if (Request::ajax()) {
            $school_year = Input::get("school_year");
            $period = Input::get("period");
            $courses = \App\CourseOffering::where('school_year', $school_year)->where('period', $period)->orderBy('course_code')->get();
            return view('reg_college.curriculum_management.ajax.show_offerings_general', compact('courses', 'school_year', 'period'));
            
        }
    }        

    function get_courses() {
        if (Request::ajax()) {
            $school_year = Input::get("school_year");
            $period = Input::get("period");
            $courses = \App\CourseOffering::distinct()->where('school_year', $school_year)->where('period', $period)->orderBy('course_code')->get(['course_code','course_name']);
            return view('reg_college.curriculum_management.ajax.show_courses', compact('courses'));
            
        }
    }  

    function get_offerings_per_course() {
        if (Request::ajax()) {
            $school_year = Input::get("school_year");
            $period = Input::get("period");
            $course_code = Input::get("course_code");
            
            $courses = \App\ScheduleCollege::distinct()->where('course_code', $course_code)->where('school_year', $school_year)->where('period', $period)->get(['schedule_id','course_code']);
            
            return view('reg_college.curriculum_management.ajax.show_offerings_course', compact('courses', 'school_year', 'period','course_code'));
            
        }
    }    

    function display_others() {
        if (Request::ajax()) {
            $program_code = Input::get("program_code");
            
            return view('reg_college.curriculum_management.ajax.show_display_others', compact('program_code'));
            
        }
    }   

    function display_others_tutorials() {
        if (Request::ajax()) {
            $program_code = Input::get("program_code");
            
            return view('reg_college.curriculum_management.ajax.show_display_others_tutorials', compact('program_code'));
            
        }
    }  
}
