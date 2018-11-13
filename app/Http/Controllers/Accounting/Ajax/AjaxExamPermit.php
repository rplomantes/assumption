<?php

namespace App\Http\Controllers\Accounting\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;

class AjaxExamPermit extends Controller
{
    //
    function getstudentpermit() {
        if (Request::ajax()) {
            $school_year = Input::get("school_year");
            $level = Input::get("level");
            $period = Input::get("period");
            $exam_period = Input::get("exam_period");

            $lists = \App\CollegeLevel::join('users', 'users.idno','=', 'college_levels.idno')->where('school_year', $school_year)->where('period', $period)->where('level', $level)->where('college_levels.status', env('ENROLLED'))->orderBy('users.lastname', 'asc')->get(array('users.lastname','users.firstname','users.middlename','users.extensionname','college_levels.level','college_levels.program_code','users.idno'));
            
            $number = 1;
            return view('accounting.ajax.studentpermitlist', compact('lists', 'period', 'level', 'school_year','number','exam_period'));
        }
    }
}
