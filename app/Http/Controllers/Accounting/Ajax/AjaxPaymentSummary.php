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

            if ($department == "Senior High School") {
                
            } else if ($department == "College Department") {
                
            } else {
                $dep = $department;
                
            }
//            return view('accounting.ajax.get_studentlist', compact('department','school_year','period','lists','heads'));
        }
    }
}
