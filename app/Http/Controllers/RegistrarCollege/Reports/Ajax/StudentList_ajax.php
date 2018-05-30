<?php

namespace App\Http\Controllers\RegistrarCollege\Reports\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use DB;

class StudentList_ajax extends Controller {

    //
    function search() {
        if (Request::ajax()) {
            $school_year = Input::get("school_year");
            $period = Input::get("period");
            $level = Input::get("level");
            $program_code = Input::get("program_code");

            if ($school_year == "all") {
                $school_year = "";
            } else {
                $school_year = "and school_year = '" . $school_year . "'";
            }

            if ($period == "all") {
                $period = "";
            } else {
                $period = "and period = '" . $period . "'";
            }

            if ($level == "all") {
                $level = "";
            } else {
                $level = "and level = '" . $level . "'";
            }

            if ($program_code == "all") {
                $program_code = "";
            } else {
                $program_code = "and program_code = '" . $program_code . "'";
            }

            $lists = DB::Select("Select * from college_levels join users on users.idno = college_levels.idno where college_levels.status=3 $school_year $period $level $program_code order by users.lastname");

            return view('reg_college.reports.student_list.ajax.display_search', compact('lists'));
        }
    }

    function select_section() {
        if (Request::ajax()) {
            $school_year = Input::get("school_year");
            $period = Input::get("period");
            $level = Input::get("level");
            $program_code = Input::get("program_code");

            if ($school_year == "all") {
                $school_year = "";
            } else {
                $school_year = "and school_year = '" . $school_year . "'";
            }

            if ($period == "all") {
                $period = "";
            } else {
                $period = "and period = '" . $period . "'";
            }

            if ($level == "all") {
                $level = "";
            } else {
                $level = "and level = '" . $level . "'";
            }

            if ($program_code == "all") {
                $program_code = "";
            } else {
                $program_code = "and program_code = '" . $program_code . "'";
            }

            $lists = DB::Select("Select distinct section, section_name from course_offerings where id is not null $school_year $period $level $program_code");

            return view('reg_college.reports.student_list.ajax.display_section', compact('lists'));
        }
    }

    function select_course() {
        if (Request::ajax()) {
            $school_year = Input::get("school_year");
            $period = Input::get("period");
            $level = Input::get("level");
            $program_code = Input::get("program_code");
            $section = Input::get("section");
            $section_name = Input::get("section_name");

            if ($school_year == "all") {
                $school_year = "";
            } else {
                $school_year = "and school_year = '" . $school_year . "'";
            }

            if ($section == "all") {
                $section = "";
            } else {
                $section = "and section_name = '" . $section . "'";
            }

            if ($period == "all") {
                $period = "";
            } else {
                $period = "and period = '" . $period . "'";
            }

            if ($level == "all") {
                $level = "";
            } else {
                $level = "and level = '" . $level . "'";
            }

            if ($program_code == "all") {
                $program_code = "";
            } else {
                $program_code = "and program_code = '" . $program_code . "'";
            }

            $courses = DB::Select("Select * from course_offerings where id is not null $section $school_year $period $level $program_code");

            return view('reg_college.reports.student_list.ajax.display_course', compact('courses'));
        }
    }

    function list_per_course() {
        if (Request::ajax()) {
            $course_id = Input::get("course_id");
            
            $list_per_courses = \App\GradeCollege::where('course_offering_id', $course_id)->join('users', 'users.idno', '=', 'grade_colleges.idno')->join('statuses', 'statuses.idno', '=', 'grade_colleges.idno')->where('statuses.status', 3)->orderBy('users.lastname')->get();
            
            return view('reg_college.reports.student_list.ajax.display_per_course', compact('list_per_courses'));
        }
    }
    
}
