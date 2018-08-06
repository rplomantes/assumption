<?php

namespace App\Http\Controllers\RegistrarCollege\Reports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Auth;
use PDF;
use DB;

class StudentListController extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function search() {
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || env('ADMISSION_HED')) {
            $school_years = \App\CourseOffering::distinct()->get(['school_year']);
            $programs = \App\CtrAcademicProgram::distinct()->where('academic_type', 'College')->get(['program_code', 'program_name']);
            return view('reg_college.reports.student_list.search', compact('school_years', 'programs'));
        }
    }

    function print_search($school_years, $periods, $levels, $program_codes) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE') || Auth::user()->accesslevel == env('ADMISSION_HED')) {

            $school_year = $school_years;
            $period = $periods;
            $level = $levels;
            $program_code = $program_codes;
            
            if ($school_year == "all") {
                $school_year = "";
            } else {
                $school_year = "and statuses.school_year = '" . $school_year . "'";
            }

            if ($period == "all") {
                $period = "";
            } else {
                $period = "and statuses.period = '" . $period . "'";
            }

            if ($course_code == "all") {
                $course_code = "";
            } else {
                $course_code = "and grade_colleges.course_code = '" . $course_code . "'";
            }

            if ($level == "all") {
                $level = "";
            } else {
                $level = "and statuses.level = '" . $level . "'";
            }

            if ($section == "all") {
                $section = "";
            } else {
                $section = "and course_offerings.section = '" . $section . "'";
            }

            if ($section_name == "all") {
                $section_name = "";
            } else {
                $section_name = "and course_offerings.section_name = '" . $section_name . "'";
            }

            if ($program_code == "all") {
                $program_code = "";
            } else {
                $program_code = "and statuses.program_code = '" . $program_code . "'";
            }
            
            
            $list_per_courses = DB::Select("Select distinct users.idno, users.lastname, users.firstname, users.middlename from grade_colleges join users on users.idno = grade_colleges.idno join statuses on statuses.idno = grade_colleges.idno where grade_colleges.id is not null $school_year $period $level $program_code $course_code and statuses.status = 3 order by users.lastname");
            //$school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', "College")->first();

            $pdf = PDF::loadView('reg_college.reports.student_list.print_search', compact('lists', 'school_years', 'periods', 'levels', 'program_codes'));
            $pdf->setPaper(array(0, 0, 612.00, 792.0));
            return $pdf->stream("student_list_.pdf");
        }
    }

    function per_course() {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $school_years = \App\CourseOffering::distinct()->get(['school_year']);
            $programs = \App\CtrAcademicProgram::distinct()->where('academic_type', 'College')->get(['program_code', 'program_name']);
            return view('reg_college.reports.student_list.per_course', compact('school_years', 'programs'));
        }
    }

    function print_per_course($course_code, $section, $section_name, $school_years, $periods, $levels, $program_codes) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $course_code = $course_code;
            $section = $section;
            $section_name = $section_name;
            $school_year = $school_years;
            $period = $periods;
            $level = $levels;
            $program_code = $program_codes;
            
            if ($school_year == "all") {
                $school_year = "";
            } else {
                $school_year = "and statuses.school_year = '" . $school_year . "'";
            }

            if ($period == "all") {
                $period = "";
            } else {
                $period = "and statuses.period = '" . $period . "'";
            }

            if ($course_code == "all") {
                $course_code = "";
            } else {
                $course_code = "and grade_colleges.course_code = '" . $course_code . "'";
            }

            if ($level == "all") {
                $level = "";
            } else {
                $level = "and statuses.level = '" . $level . "'";
            }

            if ($section == "all") {
                $section = "";
            } else {
                $section = "and course_offerings.section = '" . $section . "'";
            }

            if ($section_name == "all") {
                $section_name = "";
            } else {
                $section_name = "and course_offerings.section_name = '" . $section_name . "'";
            }

            if ($program_code == "all") {
                $program_code = "";
            } else {
                $program_code = "and statuses.program_code = '" . $program_code . "'";
            }
            
            
            $list_per_courses = DB::Select("Select distinct users.idno, users.lastname, users.firstname, users.middlename from grade_colleges join users on users.idno = grade_colleges.idno join statuses on statuses.idno = grade_colleges.idno where grade_colleges.id is not null $school_year $period $level $program_code $course_code and statuses.status = 3 order by users.lastname");
//            $course_code = \App\CourseOffering::where('id', $course_id)->first()->course_code;
//            $course_name = \App\CourseOffering::where('course_code', $course_code)->first()->course_name;
//            $schedule_id = \App\CourseOffering::where('id', $course_id)->first()->schedule_id;

            $pdf = PDF::loadView('reg_college.reports.student_list.print_per_course', compact('course_code','course_name','list_per_courses', 'section','section_name','school_years', 'periods', 'levels', 'program_codes'));
            $pdf->setPaper(array(0, 0, 612.00, 792.0));
            return $pdf->stream("student_list_.pdf");
        }
    }

    function per_instructor() {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            
            return view('reg_college.reports.student_list.per_instructor', compact('school_years', 'programs'));
        }
    }
    
}
