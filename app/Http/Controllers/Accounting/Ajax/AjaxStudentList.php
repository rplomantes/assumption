<?php

namespace App\Http\Controllers\Accounting\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use DB;

class AjaxStudentList extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    function get_studentlist() {
        if (Request::ajax()) {
            $dep = "";
            $department = Input::get('department');

            if ($department == "College Department") {
                $dep = '%Department';
            } else {
                $dep = $department;
            }
            $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, s.program_code, s.level, s.section, SUBSTR(s.type_of_plan,5) AS type_of_plan, l.assessment FROM users u, statuses s, (SELECT idno, SUM(amount) AS 'assessment' FROM `ledgers` GROUP BY idno) l WHERE l.assessment != 0.00 AND u.idno = s.idno AND u.idno = l.idno AND s.department LIKE '$dep' AND s.status = '3' ORDER BY u.lastname, s.program_code, s.level, s.section");
            $heads = DB::select("SELECT s.level, SUM(l.assessment) AS 'total' FROM statuses s, (SELECT idno,(SUM(amount)) AS 'assessment' FROM `ledgers` GROUP BY idno) l,(SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr WHERE l.assessment != 0.00 AND s.idno = l.idno AND s.department LIKE '$dep' AND s.status = '3' AND ctr.level = s.level GROUP BY s.level,ctr.sort_by ORDER BY ctr.sort_by");
            return view('accounting.ajax.get_studentlist', compact('department','lists','heads'));
        }
    }
}
