<?php

namespace App\Http\Controllers\ScholarshipCollege\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use DB;

class GetStudentList_ajax extends Controller {

    //
    function getstudentlist() {
        if (Request::ajax()) {
            $search = Input::get("search");
            $lists = \App\User::where('academic_type', 'College')
                            ->where(function ($query) use ($search) {
                                $query->where("lastname", "like", "%$search%")
                                ->orWhere("firstname", "like", "%$search%")
                                ->orWhere(DB::raw("CONCAT(firstname,' ',lastname)"), "like", "%$search%")
                                ->orWhere("idno", $search);
                            })->get();

            return view('scholarship_hed.ajax.getstudentlist', compact('lists'));
        }
    }

    function get_scholarship_report() {
        if (Request::ajax()) {
            $scholarship = Input::get("scholarship");
            $school_year = Input::get("school_year");
            $period = Input::get("period");
            $scholars = \App\CollegeLevel::where('college_levels.status', env("ENROLLED"))
                            ->join('users', 'users.idno', 'college_levels.idno')
                            ->join('college_scholarships', 'college_scholarships.idno', 'college_levels.idno')
                            ->where('school_year', $school_year)
                            ->where('period', $period)
                            ->where('discount_code', "$scholarship")
                            ->orderBy('users.lastname', 'asc')->get();

            return view('scholarship_hed.ajax.ajax_list_of_scholars', compact('scholars', 'school_year', 'period','scholarship'));
        }
    }

}
