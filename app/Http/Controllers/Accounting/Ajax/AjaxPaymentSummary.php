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
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, s.level, s.section, SUBSTR(s.type_of_plan,5) AS type_of_plan, l.assessment FROM users u, bed_levels s, (SELECT idno, period, SUM(amount) AS 'assessment' FROM `ledgers` WHERE (category_switch <= 6 or (category_switch <= 16 and category_switch >= 10)) and period = '$period' and school_year = '$school_year' GROUP BY idno, period) l WHERE l.assessment != 0.00 AND u.idno = s.idno AND u.idno = l.idno AND s.department LIKE 'Senior High School' AND s.school_year = '$school_year' AND s.period = '$period' AND (s.status = '3' or s.status = '4') ORDER BY u.lastname, s.strand, s.level, s.section");
                $heads = DB::select("SELECT s.level, SUM(l.assessment) AS 'total' FROM bed_levels s, (SELECT idno, period, (SUM(amount)) AS 'assessment' FROM `ledgers` WHERE (category_switch <= 6 or (category_switch <= 16 and category_switch >= 10)) and school_year = '$school_year' and period = '$period' GROUP BY idno, period) l,(SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr WHERE l.assessment != 0.00 AND s.idno = l.idno AND s.department LIKE 'Senior High School' AND s.school_year = '$school_year' AND s.period = '$period' AND (s.status = '3' or s.status = '4') AND ctr.level = s.level GROUP BY s.level, ctr.sort_by ORDER BY ctr.sort_by");
            } else if ($department == "College Department") {
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, s.program_code, s.level, SUBSTR(s.type_of_plan,5) AS type_of_plan, l.assessment FROM users u, college_levels s, (SELECT idno, period, SUM(amount) AS 'assessment' FROM `ledgers` WHERE (category_switch <= 6 or (category_switch <= 16 and category_switch >= 10)) and period = '$period' and school_year = '$school_year' GROUP BY idno, period) l WHERE l.assessment != 0.00 AND u.idno = s.idno AND u.idno = l.idno AND s.school_year = '$school_year' AND s.period = '$period' AND s.status = '3' ORDER BY u.lastname, s.program_code, s.level");
                $heads = DB::select("SELECT s.level, SUM(l.assessment) AS 'total' FROM college_levels s, (SELECT idno, period, (SUM(amount)) AS 'assessment' FROM `ledgers` where (category_switch <= 6 or (category_switch <= 16 and category_switch >= 10)) and school_year = '$school_year' and period = '$period' GROUP BY idno, period) l,(SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr WHERE l.assessment != 0.00 AND s.idno = l.idno AND s.school_year = '$school_year' AND s.period = '$period' AND s.status = '3' AND ctr.level = s.level GROUP BY s.level, ctr.sort_by ORDER BY ctr.sort_by");
            } else {
                $dep = $department;
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, s.level, s.section, SUBSTR(s.type_of_plan,5) AS type_of_plan, l.assessment FROM users u, bed_levels s, (SELECT idno, SUM(amount)-SUM(discount) AS 'assessment' FROM `ledgers` where (category_switch <= 6 or (category_switch <= 16 and category_switch >= 10)) and school_year = '$school_year' GROUP BY idno) l WHERE l.assessment != 0.00 AND u.idno = s.idno AND u.idno = l.idno AND s.department LIKE '$dep' AND s.school_year = '$school_year' AND (s.status = '3' or s.status = '4') ORDER BY u.lastname, s.level, s.section");
                $heads = DB::select("SELECT s.level, SUM(l.assessment) AS 'total' FROM bed_levels s, (SELECT idno,(SUM(amount)-SUM(discount)) AS 'assessment' FROM `ledgers` where (category_switch <= 6 or (category_switch <= 16 and category_switch >= 10)) and school_year = '$school_year' GROUP BY idno) l,(SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr WHERE l.assessment != 0.00 AND s.idno = l.idno AND s.department LIKE '$dep' AND s.school_year = '$school_year' AND (s.status = '3' or s.status = '4') AND ctr.level = s.level GROUP BY s.level, ctr.sort_by ORDER BY ctr.sort_by");
//                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, s.level, s.section, SUBSTR(s.type_of_plan,5) AS type_of_plan, l.assessment FROM users u, bed_levels s, (SELECT idno, SUM(amount)-SUM(discount) AS 'assessment' FROM `ledgers` where (category_switch <= 6 or (category_switch <= 16 and category_switch >= 10)) and school_year = '$school_year' GROUP BY idno) l WHERE l.assessment != 0.00 AND u.idno = s.idno AND u.idno = l.idno AND s.department LIKE '$dep' AND s.school_year = '$school_year' AND (s.status = '3' or s.status = '4') and u.idno = 1819226 ORDER BY u.lastname, s.level, s.section");
//                $heads = DB::select("SELECT s.level, SUM(l.assessment) AS 'total' FROM bed_levels s, (SELECT idno,(SUM(amount)-SUM(discount)) AS 'assessment' FROM `ledgers` where (category_switch <= 6 or (category_switch <= 16 and category_switch >= 10)) and school_year = '$school_year' GROUP BY idno) l,(SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr WHERE l.assessment != 0.00 AND s.idno = l.idno AND s.department LIKE '$dep' AND s.school_year = '$school_year' AND (s.status = '3' or s.status = '4') AND ctr.level = s.level and s.idno = 1819226 GROUP BY s.level, ctr.sort_by ORDER BY ctr.sort_by");
            }
//            return "STILL ON DEVELOPMENT...";
            return view('accounting.ajax.get_paymentsummary', compact('department','school_year','period','lists','heads'));
        }
    }
}
