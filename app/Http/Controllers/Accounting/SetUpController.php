<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;
use PDF;
use Excel;

class SetUpController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    function set_up_summary(){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            return view('accounting.set_up_summary');
        }
        
    }
    
    function set_up_list() {
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            return view('accounting.set_up_list');
        }
    }
    
    function print_set_up_summary(Request $request){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
     
            $department = $request->department;
            $school_year = $request->school_year;
            $period = $request->period;
            
            if ($department == "College Department") {
                $ledgers = DB::select("SELECT l.accounting_code, l.subsidiary, SUM(l.amount) AS amount, SUM(l.discount) AS discount FROM ledgers l, (SELECT idno FROM college_levels WHERE school_year = '$school_year' AND period = '$period' AND status = '3') c WHERE l.idno = c.idno AND l.amount > 0 AND ((l.category_switch < 14 AND l.category_switch > 10) or l.category_switch < 4) AND l.school_year = '$school_year' AND period = '$period' GROUP BY l.subsidiary, l.accounting_code");
                $srfs = DB::select("SELECT l.accounting_code, l.category, SUM(l.amount) AS amount, SUM(l.discount) AS discount FROM ledgers l, (SELECT idno FROM college_levels WHERE school_year = '$school_year' AND period = '$period' AND status = '3') c WHERE l.idno = c.idno AND l.amount > 0 AND ((l.category_switch = 14 OR l.category_switch = 4) and l.category = 'SRF') AND l.school_year = '$school_year' AND period = '$period' GROUP BY l.category, l.accounting_code");
                $tuitions = DB::select("SELECT l.accounting_code, l.subsidiary, SUM(l.amount) AS amount, SUM(l.discount) AS discount FROM ledgers l, (SELECT idno FROM college_levels WHERE school_year = '$school_year' AND period = '$period' AND status = '3') c WHERE l.idno = c.idno AND l.amount > 0 AND (l.category_switch = 6 or l.category_switch = 16) AND l.school_year = '$school_year' AND period = '$period' GROUP BY l.subsidiary, l.accounting_code");
            } else if ($department == "Senior High School") {
                $ledgers = DB::select("SELECT l.accounting_code, l.subsidiary, SUM(l.amount) AS amount, SUM(l.discount) AS discount FROM ledgers l, (SELECT idno FROM bed_levels WHERE department LIKE 'Senior High School' AND school_year = '$school_year' AND period = '$period' AND (status = '3' or status = '4')) b WHERE l.idno = b.idno AND l.amount > 0 AND ((l.category_switch < 14 AND l.category_switch > 10) or l.category_switch < 4) AND l.department LIKE 'Senior High School' AND l.school_year = '$school_year' AND l.period = '$period' GROUP BY l.subsidiary, l.accounting_code");
                $srfs = DB::select("SELECT l.accounting_code, l.category, SUM(l.amount) AS amount, SUM(l.discount) AS discount FROM ledgers l, (SELECT idno FROM bed_levels WHERE department LIKE 'Senior High School' AND school_year = '$school_year' AND period = '$period' AND (status = '3' or status = '4')) b WHERE l.idno = b.idno AND l.amount > 0 AND ((l.category_switch = 14 OR l.category_switch = 4) and l.category = 'SRF') AND l.department LIKE 'Senior High School' AND l.school_year = '$school_year' AND l.period = '$period' GROUP BY l.category, l.accounting_code");
                $tuitions = DB::select("SELECT l.accounting_code, l.subsidiary, SUM(l.amount) AS amount, SUM(l.discount) AS discount FROM ledgers l, (SELECT idno FROM bed_levels WHERE department LIKE 'Senior High School' AND school_year = '$school_year' AND period = '$period' AND (status = '3' or status = '4')) b WHERE l.idno = b.idno AND l.amount > 0 AND (l.category_switch = 6 or l.category_switch = 16) AND department LIKE 'Senior High School' AND l.school_year = '$school_year' AND period = '$period' GROUP BY l.subsidiary, l.accounting_code");
            } else {
                $ledgers = DB::select("SELECT l.accounting_code, l.subsidiary, SUM(l.amount) AS amount, SUM(l.discount) AS discount FROM ledgers l, (SELECT idno FROM bed_levels WHERE department LIKE '$department' AND school_year = '$school_year' AND (status = '3' or status = '4')) b WHERE l.idno = b.idno AND l.amount > 0 AND ((l.category_switch < 14 AND l.category_switch > 10) or l.category_switch < 4) AND l.department LIKE '$department' AND l.school_year = '$school_year' GROUP BY l.subsidiary, l.accounting_code");
                $srfs = array();
                $tuitions = DB::select("SELECT l.accounting_code, l.subsidiary, SUM(l.amount) AS amount, SUM(l.discount) AS discount FROM ledgers l, (SELECT idno FROM bed_levels WHERE department LIKE '$department' AND school_year = '$school_year' AND (status = '3' or status = '4')) b WHERE l.idno = b.idno AND l.amount > 0 AND (l.category_switch = 6 or l.category_switch = 16) AND department LIKE '$department' AND l.school_year = '$school_year' GROUP BY l.subsidiary, l.accounting_code");
            }
            
             \App\Http\Controllers\Admin\Logs::log("Print Set Up Summary PDF");
            $pdf = PDF::loadView('accounting.print_setupsummary', compact('ledgers','tuitions','department','school_year','period','srfs'));
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream("set_up_summary.pdf");
        }
    }
    
    function print_set_up_summary_excel(Request $request){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
     
            $department = $request->department;
            $school_year = $request->school_year;
            $period = $request->period;
            
             \App\Http\Controllers\Admin\Logs::log("Download Set Up Summary Excel");
            if ($department == "College Department") {
                $ledgers = DB::select("SELECT l.accounting_code, l.subsidiary, SUM(l.amount) AS amount, SUM(l.discount) AS discount FROM ledgers l, (SELECT idno FROM college_levels WHERE school_year = '$school_year' AND period = '$period' AND status = '3') c WHERE l.idno = c.idno AND l.amount > 0 AND ((l.category_switch < 14 AND l.category_switch > 10) or l.category_switch < 4) AND l.school_year = '$school_year' AND period = '$period' GROUP BY l.subsidiary, l.accounting_code");
                $srfs = DB::select("SELECT l.accounting_code, l.category, SUM(l.amount) AS amount, SUM(l.discount) AS discount FROM ledgers l, (SELECT idno FROM college_levels WHERE school_year = '$school_year' AND period = '$period' AND status = '3') c WHERE l.idno = c.idno AND l.amount > 0 AND ((l.category_switch = 14 OR l.category_switch = 4) and l.category = 'SRF') AND l.school_year = '$school_year' AND period = '$period' GROUP BY l.category, l.accounting_code");
                $tuitions = DB::select("SELECT l.accounting_code, l.subsidiary, SUM(l.amount) AS amount, SUM(l.discount) AS discount FROM ledgers l, (SELECT idno FROM college_levels WHERE school_year = '$school_year' AND period = '$period' AND status = '3') c WHERE l.idno = c.idno AND l.amount > 0 AND (l.category_switch = 6 or l.category_switch = 16) AND l.school_year = '$school_year' AND period = '$period' GROUP BY l.subsidiary, l.accounting_code");
            } else if ($department == "Senior High School") {
                $ledgers = DB::select("SELECT l.accounting_code, l.subsidiary, SUM(l.amount) AS amount, SUM(l.discount) AS discount FROM ledgers l, (SELECT idno FROM bed_levels WHERE department LIKE 'Senior High School' AND school_year = '$school_year' AND period = '$period' AND (status = '3' or status = '4')) b WHERE l.idno = b.idno AND l.amount > 0 AND ((l.category_switch < 14 AND l.category_switch > 10) or l.category_switch < 4) AND l.department LIKE 'Senior High School' AND l.school_year = '$school_year' AND l.period = '$period' GROUP BY l.subsidiary, l.accounting_code");
                $srfs = DB::select("SELECT l.accounting_code, l.category, SUM(l.amount) AS amount, SUM(l.discount) AS discount FROM ledgers l, (SELECT idno FROM bed_levels WHERE department LIKE 'Senior High School' AND school_year = '$school_year' AND period = '$period' AND (status = '3' or status = '4')) b WHERE l.idno = b.idno AND l.amount > 0 AND ((l.category_switch = 14 OR l.category_switch = 4) and l.category = 'SRF') AND l.department LIKE 'Senior High School' AND l.school_year = '$school_year' AND l.period = '$period' GROUP BY l.category, l.accounting_code");
                $tuitions = DB::select("SELECT l.accounting_code, l.subsidiary, SUM(l.amount) AS amount, SUM(l.discount) AS discount FROM ledgers l, (SELECT idno FROM bed_levels WHERE department LIKE 'Senior High School' AND school_year = '$school_year' AND period = '$period' AND (status = '3' or status = '4')) b WHERE l.idno = b.idno AND l.amount > 0 AND (l.category_switch = 6 or l.category_switch = 16) AND department LIKE 'Senior High School' AND l.school_year = '$school_year' AND period = '$period' GROUP BY l.subsidiary, l.accounting_code");
            } else {
                $ledgers = DB::select("SELECT l.accounting_code, l.subsidiary, SUM(l.amount) AS amount, SUM(l.discount) AS discount FROM ledgers l, (SELECT idno FROM bed_levels WHERE department LIKE '$department' AND school_year = '$school_year' AND (status = '3' or status = '4')) b WHERE l.idno = b.idno AND l.amount > 0 AND ((l.category_switch < 14 AND l.category_switch > 10) or l.category_switch < 4) AND l.department LIKE '$department' AND l.school_year = '$school_year' GROUP BY l.subsidiary, l.accounting_code");
                $srfs = array();
                $tuitions = DB::select("SELECT l.accounting_code, l.subsidiary, SUM(l.amount) AS amount, SUM(l.discount) AS discount FROM ledgers l, (SELECT idno FROM bed_levels WHERE department LIKE '$department' AND school_year = '$school_year' AND (status = '3' or status = '4')) b WHERE l.idno = b.idno AND l.amount > 0 AND (l.category_switch = 6 or l.category_switch = 16) AND department LIKE '$department' AND l.school_year = '$school_year' GROUP BY l.subsidiary, l.accounting_code");
            }
            
            ob_end_clean();
            Excel::create('Set Up Summary - ' .$department, 
                function($excel) use ($department,$school_year,$period,$ledgers,$tuitions,$srfs) { $excel->setTitle($department);
                    $excel->sheet($department, function ($sheet) use ($department,$school_year,$period,$ledgers,$tuitions,$srfs) {
                    $sheet->loadView('accounting.print_setupsummary_excel', compact('department','school_year','period','ledgers','tuitions','srfs'));
                    });
                })->download('xlsx');
        }
    }
    
    function print_setuplist_pdf(Request $request){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
     
            $department = $request->department;
            $school_year = $request->school_year;
            $period = $request->period;
            $subsidiary = $request->subsidiary;
            
            if ($department == "College Department") {
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, c.program_code, c.level, c.type_of_plan, l.amount, l.discount FROM users u, (SELECT idno, SUM(amount) AS amount, SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,4,6,11,12,13,14,16) AND school_year = '$school_year' AND period = '$period' AND subsidiary = '$subsidiary' GROUP BY idno) l, college_levels c WHERE c.idno = u.idno AND l.amount !=0.00 AND u.idno = l.idno AND c.school_year = '$school_year' AND c.period = '$period' AND (c.status = '3' or c.status = '4') ORDER BY u.lastname, c.program_code, c.section");
                $heads = DB::select("SELECT c.level, SUM(l.amount) AS 'total', SUM(l.discount) AS 'discount' FROM (SELECT idno, SUM(amount) AS 'amount', SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,4,6,11,12,13,14,16) AND school_year = '$school_year' AND period = '$period' AND subsidiary = '$subsidiary' GROUP BY idno) l, (SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr, college_levels c WHERE l.amount != 0.00 AND c.idno = l.idno AND ctr.level = c.level AND c.school_year = '$school_year' AND c.period = '$period' AND (c.status = '3' or c.status = '4') GROUP BY c.level, ctr.sort_by ORDER BY ctr.sort_by");
            } else if ($department == "Senior High School") {
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, b.level, b.section, b.type_of_plan ,l.amount, l.discount FROM users u, (SELECT idno, SUM(amount) AS amount, SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,4,6,11,12,13,14,16) AND school_year = '$school_year' AND period = '$period' AND subsidiary = '$subsidiary' GROUP BY idno) l, bed_levels b WHERE b.idno = u.idno AND l.amount !=0.00 AND u.idno = l.idno AND b.department LIKE 'Senior High School' AND b.school_year = '$school_year' AND b.period = '$period' AND (b.status = '3' or b.status = '4') ORDER BY u.lastname, b.level, b.section");
                $heads = DB::select("SELECT b.level, SUM(l.amount) AS 'total', SUM(l.discount) AS 'discount' FROM (SELECT idno, SUM(amount) AS 'amount', SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,4,6,11,12,13,14,16) AND school_year = '$school_year' AND period = '$period' AND subsidiary = '$subsidiary' GROUP BY idno) l, (SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr, bed_levels b WHERE l.amount != 0.00 AND b.idno = l.idno AND ctr.level = b.level AND b.department LIKE 'Senior High School' AND b.school_year = '$school_year' AND b.period = '$period' AND (b.status = '3' or b.status = '4') GROUP BY b.level, ctr.sort_by ORDER BY ctr.sort_by");
            } else {
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, b.level, b.section,b.type_of_plan, l.amount, l.discount FROM users u, (SELECT idno, SUM(amount) AS amount, SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,13,16) AND school_year = '$school_year' AND subsidiary = '$subsidiary' GROUP BY idno) l, bed_levels b WHERE b.idno = u.idno AND l.amount !=0.00 AND u.idno = l.idno AND b.department LIKE '$department' AND b.school_year = '$school_year' AND (b.status = '3' or b.status = '4') ORDER BY u.lastname, b.level, b.section");
                $heads = DB::select("SELECT b.level, SUM(l.amount) AS 'total', SUM(l.discount) AS 'discount' FROM (SELECT idno, SUM(amount) AS 'amount', SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,14,16) AND school_year = '$school_year' AND subsidiary = '$subsidiary' GROUP BY idno) l, (SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr, bed_levels b WHERE l.amount != 0.00 AND b.idno = l.idno AND ctr.level = b.level AND b.department LIKE '$department' AND b.school_year = '$school_year' AND (b.status = '3' or b.status = '4') GROUP BY b.level, ctr.sort_by ORDER BY ctr.sort_by");
            }
            
            $pdf = PDF::loadView('accounting.print_setuplist_pdf', compact('lists','heads','department','school_year','period','subsidiary'));
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream("set_up_list.pdf");
        }
    }
    
    function print_setuplist_excel(Request $request){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
     
            $department = $request->department;
            $school_year = $request->school_year;
            $period = $request->period;
            $subsidiary = $request->subsidiary;
            
            if ($department == "College Department") {
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, c.program_code, c.level, c.type_of_plan, l.amount, l.discount FROM users u, (SELECT idno, SUM(amount) AS amount, SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,4,6,11,12,13,14,16) AND school_year = '$school_year' AND period = '$period' AND subsidiary = '$subsidiary' GROUP BY idno) l, college_levels c WHERE c.idno = u.idno AND l.amount !=0.00 AND u.idno = l.idno AND c.school_year = '$school_year' AND c.period = '$period' AND (c.status = '3' or c.status = '4') ORDER BY u.lastname, c.program_code, c.section");
                $heads = DB::select("SELECT c.level, SUM(l.amount) AS 'total', SUM(l.discount) AS 'discount' FROM (SELECT idno, SUM(amount) AS 'amount', SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,4,6,11,12,13,14,16) AND school_year = '$school_year' AND period = '$period' AND subsidiary = '$subsidiary' GROUP BY idno) l, (SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr, college_levels c WHERE l.amount != 0.00 AND c.idno = l.idno AND ctr.level = c.level AND c.school_year = '$school_year' AND c.period = '$period' AND (c.status = '3' or c.status = '4') GROUP BY c.level, ctr.sort_by ORDER BY ctr.sort_by");
            } else if ($department == "Senior High School") {
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, b.level, b.section, b.type_of_plan ,l.amount, l.discount FROM users u, (SELECT idno, SUM(amount) AS amount, SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,4,6,11,12,13,14,16) AND school_year = '$school_year' AND period = '$period' AND subsidiary = '$subsidiary' GROUP BY idno) l, bed_levels b WHERE b.idno = u.idno AND l.amount !=0.00 AND u.idno = l.idno AND b.department LIKE 'Senior High School' AND b.school_year = '$school_year' AND b.period = '$period' AND (b.status = '3' or b.status = '4') ORDER BY u.lastname, b.level, b.section");
                $heads = DB::select("SELECT b.level, SUM(l.amount) AS 'total', SUM(l.discount) AS 'discount' FROM (SELECT idno, SUM(amount) AS 'amount', SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,4,6,11,12,13,14,16) AND school_year = '$school_year' AND period = '$period' AND subsidiary = '$subsidiary' GROUP BY idno) l, (SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr, bed_levels b WHERE l.amount != 0.00 AND b.idno = l.idno AND ctr.level = b.level AND b.department LIKE 'Senior High School' AND b.school_year = '$school_year' AND b.period = '$period' AND (b.status = '3' or b.status = '4') GROUP BY b.level, ctr.sort_by ORDER BY ctr.sort_by");
            } else {
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, b.level, b.section,b.type_of_plan, l.amount, l.discount FROM users u, (SELECT idno, SUM(amount) AS amount, SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,13,16) AND school_year = '$school_year' AND subsidiary = '$subsidiary' GROUP BY idno) l, bed_levels b WHERE b.idno = u.idno AND l.amount !=0.00 AND u.idno = l.idno AND b.department LIKE '$department' AND b.school_year = '$school_year' AND (b.status = '3' or b.status = '4') ORDER BY u.lastname, b.level, b.section");
                $heads = DB::select("SELECT b.level, SUM(l.amount) AS 'total', SUM(l.discount) AS 'discount' FROM (SELECT idno, SUM(amount) AS 'amount', SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,14,16) AND school_year = '$school_year' AND subsidiary = '$subsidiary' GROUP BY idno) l, (SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr, bed_levels b WHERE l.amount != 0.00 AND b.idno = l.idno AND ctr.level = b.level AND b.department LIKE '$department' AND b.school_year = '$school_year' AND (b.status = '3' or b.status = '4') GROUP BY b.level, ctr.sort_by ORDER BY ctr.sort_by");
            }
            
            ob_end_clean();
            Excel::create('Set Up List - ' .$subsidiary, 
                function($excel) use ($department,$school_year,$period,$subsidiary,$lists,$heads) { $excel->setTitle($department);
                    $excel->sheet($department, function ($sheet) use ($department,$school_year,$period,$subsidiary,$lists,$heads) {
                    $sheet->loadView('accounting.print_setuplist_excel', compact('department','school_year','period','subsidiary','lists','heads'));
                    });
                })->download('xlsx');
        }
    }
}
