<?php

namespace App\Http\Controllers\BedRegistrar\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
Use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Request;
use PDF;
use Excel;

class AjaxPromotionsController extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth');
    }

    function getlist() {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env("REG_BE")) {
                $level = Input::get('level');
                $strand = Input::get('strand');
                
                if ($level == "Grade 11" || $level == "Grade 12") {
                $schoolyear = \App\CtrAcademicSchoolYear::where('academic_type', 'SHS')->first()->school_year;
                $period = \App\CtrAcademicSchoolYear::where('academic_type', 'SHS')->first()->period;
                        $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                                        . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level' and bed_levels.strand = '$strand' "
                                        . " and bed_levels.school_year = '$schoolyear' and bed_levels.period = '$period' order by users.lastname, users.firstname, users.middlename");
                } else {
                $schoolyear = \App\CtrAcademicSchoolYear::where('academic_type', 'BED')->first()->school_year;
                        $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                                        . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level'  "
                                        . " and bed_levels.school_year = '$schoolyear' order by users.lastname, users.firstname, users.middlename");
                }
                
                return view('reg_be.ajax.promotions_getstudentlist', compact('status', 'level','strand'));
            }
        }
    }
}
