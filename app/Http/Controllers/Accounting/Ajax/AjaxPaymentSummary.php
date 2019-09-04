<?php

namespace App\Http\Controllers\Accounting\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use DB;

class AjaxPaymentSummary extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth');
    }

    function get_payment_summary() {
        if (Request::ajax()) {
            $dep = "";
            $department = Input::get('department');
            $school_year = Input::get("school_year");
            $period = Input::get("period");

            if ($department == "College Department") {
                $lists = \App\CollegeLevel::where('school_year', $school_year)->where('period',$period)->where('college_levels.status', env('ENROLLED'))->join('users', 'users.idno','college_levels.idno')->get();
            } else if ($department == "Senior High School") {
                $lists = \App\BedLevel::where('department', $department)->where('school_year', $school_year)->where('period',$period)->where('bed_levels.status', env('ENROLLED'))->join('users', 'users.idno','bed_levels.idno')->get();
            } else {
                $lists = \App\BedLevel::where('department', $department)->where('school_year', $school_year)->where('bed_levels.status', env('ENROLLED'))->join('users', 'users.idno','bed_levels.idno')->orderBy('users.lastname')->get();
            }
//            return "STILL ON DEVELOPMENT...";
            return view('accounting.ajax.get_paymentsummary', compact('department','school_year','period','lists'));
        }
    }
}
