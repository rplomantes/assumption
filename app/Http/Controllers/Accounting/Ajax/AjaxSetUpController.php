<?php

namespace App\Http\Controllers\Accounting\Ajax;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
Use Illuminate\Support\Facades\DB;
use Request;

class AjaxSetUpController extends Controller
{
    //
     public function __construct()
    {
        $this->middleware('auth');
    }
    
    function getsetupsummary(){
        if(Request::ajax()){
            $department = Input::get('department');
            $school_year = Input::get('school_year');
            $period = Input::get('period');
            
            if ($department == "College Department") {
                $ledgers = DB::select("SELECT l.accounting_code, l.subsidiary, SUM(l.amount) AS amount FROM ledgers l, (SELECT idno FROM college_levels WHERE school_year = '$school_year' AND period = '$period' AND status = '3') c WHERE l.idno = c.idno AND l.amount > 0 AND ((l.category_switch < 14 AND l.category_switch > 10) or l.category_switch < 4) AND l.school_year = '$school_year' AND period = '$period' GROUP BY l.subsidiary, l.accounting_code");
                $tuitions = DB::select("SELECT l.accounting_code, l.subsidiary, SUM(l.amount) AS amount FROM ledgers l, (SELECT idno FROM college_levels WHERE school_year = '$school_year' AND period = '$period' AND status = '3') c WHERE l.idno = c.idno AND l.amount > 0 AND (l.category_switch = 6 or l.category_switch = 16) AND l.school_year = '$school_year' AND period = '$period' GROUP BY l.subsidiary, l.accounting_code");
            } else if ($department == "Senior High School") {
                $ledgers = DB::select("SELECT l.accounting_code, l.subsidiary, SUM(l.amount) AS amount FROM ledgers l, (SELECT idno FROM bed_levels WHERE department LIKE 'Senior High School' AND school_year = '$school_year' AND period = '$period' AND status = '3') b WHERE l.idno = b.idno AND l.amount > 0 AND ((l.category_switch < 14 AND l.category_switch > 10) or l.category_switch < 4) AND l.department LIKE 'Senior High School' AND l.school_year = '$school_year' AND l.period = '$period' GROUP BY l.subsidiary, l.accounting_code");
                $tuitions = DB::select("SELECT l.accounting_code, l.subsidiary, SUM(l.amount) AS amount FROM ledgers l, (SELECT idno FROM bed_levels WHERE department LIKE 'Senior High School' AND school_year = '$school_year' AND period = '$period' AND status = '3') b WHERE l.idno = b.idno AND l.amount > 0 AND (l.category_switch = 6 or l.category_switch = 16) AND department LIKE 'Senior High School' AND l.school_year = '$school_year' AND period = '$period' GROUP BY l.subsidiary, l.accounting_code");
            } else {
                $ledgers = DB::select("SELECT l.accounting_code, l.subsidiary, SUM(l.amount) AS amount FROM ledgers l, (SELECT idno FROM bed_levels WHERE department LIKE '$department' AND school_year = '$school_year' AND status = '3') b WHERE l.idno = b.idno AND l.amount > 0 AND ((l.category_switch < 14 AND l.category_switch > 10) or l.category_switch < 4) AND l.department LIKE '$department' AND l.school_year = '$school_year' GROUP BY l.subsidiary, l.accounting_code");
                $tuitions = DB::select("SELECT l.accounting_code, l.subsidiary, SUM(l.amount) AS amount FROM ledgers l, (SELECT idno FROM bed_levels WHERE department LIKE '$department' AND school_year = '$school_year' AND status = '3') b WHERE l.idno = b.idno AND l.amount > 0 AND (l.category_switch = 6 or l.category_switch = 16) AND department LIKE '$department' AND l.school_year = '$school_year' GROUP BY l.subsidiary, l.accounting_code");
            }    
            
            return view('accounting.ajax.getsetupsummary',compact('ledgers', 'tuitions','department','school_year','period'));
        }
    }
    
    function getsubsidiary() {
        if (Request::ajax()) {
            $department = Input::get('department');
            $school_year = Input::get('school_year');
            $period = Input::get('period');
            
            if ($department == "College Department") {
                $subsidiarys = DB::select("SELECT l.subsidiary FROM ledgers l, (SELECT idno FROM college_levels WHERE school_year = '$school_year' AND period = '$period') c WHERE l.idno = c.idno AND l.category_switch IN (1,2,3,6,11,12,13,16) AND l.school_year = '$school_year' AND period = '$period' GROUP BY l.subsidiary");
            } else if ($department == "Senior High School") {
                $subsidiarys = DB::select("SELECT l.subsidiary FROM ledgers l, (SELECT idno FROM bed_levels WHERE department LIKE 'Senior High School' AND school_year = '$school_year' AND period = '$period') b WHERE l.idno = b.idno AND l.department LIKE 'Senior High School' AND l.category_switch IN (1,2,3,6,11,12,13,16) AND l.school_year = '$school_year' AND period = '$period' GROUP BY l.subsidiary");
            } else {
                $subsidiarys = DB::select("SELECT l.subsidiary FROM ledgers l, (SELECT idno FROM bed_levels WHERE department LIKE '$department' AND school_year = '$school_year') b WHERE l.idno = b.idno AND l.department LIKE '$department' AND l.category_switch IN (1,2,3,6,11,12,13,16) AND l.school_year = '$school_year' GROUP BY l.subsidiary");            
            }
                        
            $data  = "<select class=\"form form-control\">"
                     . "<option value=\"\">Select Subsidiary</option>";
            foreach ($subsidiarys as $subsidiary) {
                $data = $data . "<option value='". $subsidiary->subsidiary ."'>" . $subsidiary->subsidiary . "</option>";
            }
            $data = $data . "</select>";
            return $data;
        }
    }
    
    function getsetuplist(){
        if(Request::ajax()){
            $department = Input::get('department');
            $school_year = Input::get('school_year');
            $period = Input::get('period');
            $subsidiary = Input::get('subsidiary');
            
            if ($department == "College Department") {
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, c.program_code, c.level, l.amount FROM users u, (SELECT idno, SUM(amount) AS amount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,13,16) AND school_year = '$school_year' AND period = '$period' AND subsidiary = '$subsidiary' GROUP BY idno) l, college_levels c WHERE c.idno = u.idno AND l.amount !=0.00 AND u.idno = l.idno AND c.school_year = '$school_year' AND c.period = '$period' AND c.status = '3' ORDER BY u.lastname, c.program_code, c.section");
                $heads = DB::select("SELECT c.level, SUM(l.amount) AS 'total' FROM (SELECT idno, SUM(amount) AS 'amount' FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,13,16) AND school_year = '$school_year' AND period = '$period' AND subsidiary = '$subsidiary' GROUP BY idno) l, (SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr, college_levels c WHERE l.amount != 0.00 AND c.idno = l.idno AND ctr.level = c.level AND c.school_year = '$school_year' AND c.period = '$period' AND c.status = '3' GROUP BY c.level, ctr.sort_by ORDER BY ctr.sort_by");
            } else if ($department == "Senior High School") {
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, b.level, b.section, l.amount FROM users u, (SELECT idno, SUM(amount) AS amount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,13,16) AND school_year = '$school_year' AND period = '$period' AND subsidiary = '$subsidiary' GROUP BY idno) l, bed_levels b WHERE b.idno = u.idno AND l.amount !=0.00 AND u.idno = l.idno AND b.department LIKE 'Senior High School' AND b.school_year = '$school_year' AND b.period = '$period' AND b.status = '3' ORDER BY u.lastname, b.level, b.section");
                $heads = DB::select("SELECT b.level, SUM(l.amount) AS 'total' FROM (SELECT idno, SUM(amount) AS 'amount' FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,13,16) AND school_year = '$school_year' AND period = '$period' AND subsidiary = '$subsidiary' GROUP BY idno) l, (SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr, bed_levels b WHERE l.amount != 0.00 AND b.idno = l.idno AND ctr.level = b.level AND b.department LIKE 'Senior High School' AND b.school_year = '$school_year' AND b.period = '$period' AND b.status = '3' GROUP BY b.level, ctr.sort_by ORDER BY ctr.sort_by");
            } else {
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, b.level, b.section, l.amount FROM users u, (SELECT idno, SUM(amount) AS amount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,13,16) AND school_year = '$school_year' AND subsidiary = '$subsidiary' GROUP BY idno) l, bed_levels b WHERE b.idno = u.idno AND l.amount !=0.00 AND u.idno = l.idno AND b.department LIKE '$department' AND b.school_year = '$school_year' AND b.status = '3' ORDER BY u.lastname, b.level, b.section");
                $heads = DB::select("SELECT b.level, SUM(l.amount) AS 'total' FROM (SELECT idno, SUM(amount) AS 'amount' FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,14,16) AND school_year = '$school_year' AND subsidiary = '$subsidiary' GROUP BY idno) l, (SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr, bed_levels b WHERE l.amount != 0.00 AND b.idno = l.idno AND ctr.level = b.level AND b.department LIKE '$department' AND b.school_year = '$school_year' AND b.status = '3' GROUP BY b.level, ctr.sort_by ORDER BY ctr.sort_by");
            }
            
            return view('accounting.ajax.getsetuplist',compact('lists','heads','department','school_year','period','subsidiary'));
        }
    }
}
