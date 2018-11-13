<?php

namespace App\Http\Controllers\Accounting\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use DB;

class AjaxStudentList extends Controller {

    //
    function search() {
        if (Request::ajax()) {
            $school_year = Input::get("school_year");
            $level = Input::get("level");
            $period = Input::get("period");
            
            $sy=Input::get("school_year");
            $lv=Input::get("level");

            if ($school_year == "all") {
                $school_year = "";
            } else {
                $school_year = "and school_year = '" . $school_year . "'";
            }

            if ($level == "all") {
                $level = "";
            } else {
                $level = "and level = '" . $level . "'";
            }
            
            if ($period == "all") {
                $period = "";
            } else {
                $period = "and period = '" . $period . "'";
            }

            $lists = DB::Select("select statuses.id, statuses.idno, statuses.type_of_plan from statuses join users on users.idno = statuses.idno where statuses.status=3 $school_year $level $period order by users.lastname");

            return view('accounting.ajax.display_studentlist', compact('lists', 'sy','lv'));
        }
    }

}
