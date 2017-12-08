<?php

namespace App\Http\Controllers\RegistrarCollege\Reports\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use DB;

class StudentList_ajax extends Controller
{
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
            
            $lists = DB::Select("Select * from statuses where status=3 $school_year $period $level $program_code");
            
            return view('reg_college.reports.student_list.ajax.display_search', compact ('lists'));

        }
    }
}
