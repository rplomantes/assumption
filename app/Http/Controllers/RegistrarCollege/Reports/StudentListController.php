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
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $school_years = \App\CourseOffering::distinct()->get(['school_year']);
            $programs = \App\CtrAcademicProgram::distinct()->where('academic_type', 'College')->get(['program_code', 'program_name']);
            return view('reg_college.reports.student_list.search', compact('school_years', 'programs'));
        }
    }

    function print_search($school_years, $periods, $levels, $program_codes) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {

            $school_year = $school_years;
            $period = $periods;
            $level = $levels;
            $program_code = $program_codes;

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

            $lists = DB::Select("Select * from statuses where status=3 $school_year $period $level $program_code");
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

    function print_per_course($course_id, $section,$school_years, $periods, $levels, $program_codes) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $list_per_courses = \App\GradeCollege::where('course_offering_id', $course_id)->get();
            $course_code = \App\CourseOffering::where('id', $course_id)->first()->course_code;
            $course_name = \App\CourseOffering::where('id', $course_id)->first()->course_name;

            $pdf = PDF::loadView('reg_college.reports.student_list.print_per_course', compact('course_code','course_name','list_per_courses', 'section','school_years', 'periods', 'levels', 'program_codes'));
            $pdf->setPaper(array(0, 0, 612.00, 792.0));
            return $pdf->stream("student_list_.pdf");
        }
    }

}
