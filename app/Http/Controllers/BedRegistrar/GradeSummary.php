<?php

namespace App\Http\Controllers\BedRegistrar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;
use PDF;

class GradeSummary extends Controller {

    //

    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        if (Auth::user()->accesslevel == env("REG_BE")) {
            $students = \App\Status::where('academic_type', "BED")->where('status', env("ENROLLED"))->get();
            return view("reg_be.grade_summary.student_list", compact('students'));
        }
    }
    

    function print_now($level,$strand,$section,$schoolyear,$period) {
        if (Auth::user()->accesslevel == env("REG_BE")) {
            if (Auth::user()->accesslevel == env("REG_BE") || Auth::user()->accesslevel == env('ADMISSION_BED')) {

                if ($level == "Grade 11" || $level == "Grade 12") {
                    if ($section == "All") {

                        $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                                        . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level' and bed_levels.strand = '$strand' "
                                        . " and bed_levels.school_year = '$schoolyear' and bed_levels.period = '$period' by users.lastname, users.firstname, users.middlename");
                    } else {

                        $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                                        . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level' and bed_levels.strand = '$strand' "
                                        . " and bed_levels.section = '$section' and bed_levels.school_year = '$schoolyear' and bed_levels.period = '$period' and bed_levels.status = 3 order by users.lastname, users.firstname, users.middlename");
                        //list of not yet enrolled
			$students = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                                        . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level' and bed_levels.strand = '$strand' "
                                        . " and bed_levels.section = '$section' and bed_levels.school_year = '$schoolyear' and bed_levels.period = '$period' order by users.lastname, users.firstname, users.middlename");
                    }
                } else {
                    $period = "";
                    if ($section == "All") {

                        $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                                        . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level'  "
                                        . " and bed_levels.school_year = '$schoolyear' and bed_levels.status = 3 order by users.lastname, users.firstname, users.middlename");
                    } else {

                        $status = DB::Select("Select bed_levels.idno, users.lastname, users.firstname, users.middlename, bed_levels.section  from "
                                        . "bed_levels, users where bed_levels.idno=users.idno and bed_levels.level = '$level'  "
                                        . " and bed_levels.section = '$section' and bed_levels.school_year = '$schoolyear' and bed_levels.status = 3 order by users.lastname, users.firstname, users.middlename");
                        //list of not yet enrolled
                        $students = DB::Select("Select users.lastname as lastname, users.firstname as firstname, users.middlename as middlename,  statuses.idno as idno, "
                                    . " promotions.level as level, promotions.strand as strand, promotions.section as section from users, statuses, promotions where promotions.idno = users.idno and users.idno = statuses.idno "
                                    . " and promotions.level = '$level' and statuses.status <= 3 and promotions.section = '$section' order by lastname, firstname, middlename");
                    }
                }
                $pdf = PDF::loadView("reg_be.ajax.print_grade_summary_view_list", compact("status", "level", "section", 'strand', 'schoolyear', 'period','students'));
                $pdf->setPaper(array(0, 0, 612.00, 792.0));
                return $pdf->stream("sac_grade_summary.pdf");
            }
        }
    }

}
