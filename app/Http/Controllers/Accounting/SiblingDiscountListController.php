<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use PDF;
use Excel;

class SiblingDiscountListController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    function sibling_discount(){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            return view('accounting.sibling_discount_list');
        }
    }
    
    function print_sibling_discountPDF(Request $request){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            
            $dep = "";
            $department = $request->department;
            $school_year = $request->school_year;
            $period = $request->period;
            
            if($department == "College Department"){
                $dep = '%Department';
            }
            else{
                $dep = $department;
            }
            $subsidiary = "Student Development Fee";
            
            if ($department == "College Department") {
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, c.program_code, c.level, c.type_of_plan, l.amount, l.discount FROM users u, (SELECT idno, SUM(amount) AS amount, SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,4,6,11,12,13,14,16) AND school_year = '$school_year' AND period = '$period' AND subsidiary = '$subsidiary' GROUP BY idno) l, college_levels c WHERE c.idno = u.idno AND l.amount !=0.00 AND u.idno = l.idno AND c.school_year = '$school_year' AND c.period = '$period' and (c.status = '3' or c.status = '4') ORDER BY u.lastname, c.program_code, c.section");
                $heads = DB::select("SELECT c.level, SUM(l.amount) AS 'total', SUM(l.discount) AS 'discount' FROM (SELECT idno, SUM(amount) AS 'amount', SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,4,6,11,12,13,14,16) AND school_year = '$school_year' AND period = '$period' AND subsidiary = '$subsidiary' GROUP BY idno) l, (SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr, college_levels c WHERE l.amount != 0.00 AND c.idno = l.idno AND ctr.level = c.level AND c.school_year = '$school_year' AND c.period = '$period' and (c.status = '3' or c.status = '4') GROUP BY c.level, ctr.sort_by ORDER BY ctr.sort_by");
            } else if ($department == "Senior High School") {
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, b.level, b.section, b.type_of_plan ,l.amount, l.discount FROM users u, (SELECT idno, SUM(amount) AS amount, SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,4,6,11,12,13,14,16) AND school_year = '$school_year' AND period = '$period' AND subsidiary = '$subsidiary' GROUP BY idno) l, bed_levels b WHERE b.idno = u.idno AND l.amount !=0.00 AND u.idno = l.idno AND b.department LIKE 'Senior High School' AND b.school_year = '$school_year' AND b.period = '$period' AND (b.status = '3' or b.status = '4') ORDER BY u.lastname, b.level, b.section");
                $heads = DB::select("SELECT b.level, SUM(l.amount) AS 'total', SUM(l.discount) AS 'discount' FROM (SELECT idno, SUM(amount) AS 'amount', SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,4,6,11,12,13,14,16) AND school_year = '$school_year' AND period = '$period' AND subsidiary = '$subsidiary' GROUP BY idno) l, (SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr, bed_levels b WHERE l.amount != 0.00 AND b.idno = l.idno AND ctr.level = b.level AND b.department LIKE 'Senior High School' AND b.school_year = '$school_year' AND b.period = '$period' AND (b.status = '3' or b.status = '4') GROUP BY b.level, ctr.sort_by ORDER BY ctr.sort_by");
            } else {
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, b.level, b.section, b.type_of_plan,l.amount, l.discount FROM users u, (SELECT idno, SUM(amount) AS amount, SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,13,16) AND school_year = '$school_year' AND subsidiary = '$subsidiary' GROUP BY idno) l, bed_levels b WHERE b.idno = u.idno AND l.amount !=0.00 AND u.idno = l.idno AND b.department LIKE '$department' AND b.school_year = '$school_year' AND (b.status = '3' or b.status = '4') ORDER BY u.lastname, b.level, b.section");
                $heads = DB::select("SELECT b.level, SUM(l.amount) AS 'total', SUM(l.discount) AS 'discount' FROM (SELECT idno, SUM(amount) AS 'amount', SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,14,16) AND school_year = '$school_year' AND subsidiary = '$subsidiary' GROUP BY idno) l, (SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr, bed_levels b WHERE l.amount != 0.00 AND b.idno = l.idno AND ctr.level = b.level AND b.department LIKE '$department' AND b.school_year = '$school_year' AND (b.status = '3' or b.status = '4') GROUP BY b.level, ctr.sort_by ORDER BY ctr.sort_by");
            }
//            return view('accounting.print_sibling_discount', compact('lists','heads','department','school_year','period','subsidiary'));
            $pdf = PDF::loadView('accounting.print_sibling_discount', compact('lists','heads','department','school_year','period','subsidiary'));
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream("sibling_discounts_list.pdf");
        }
        
    }
    
    function print_sibling_discountEXCEL(Request $request){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            
            $dep = "";
            $department = $request->department;
            $school_year = $request->school_year;
            $period = $request->period;
            
            if($department == "College Department"){
                $dep = '%Department';
            }
            else{
                $dep = $department;
            }
            $subsidiary = "Student Development Fee";
            
            if ($department == "College Department") {
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, c.program_code, c.level, c.type_of_plan, l.amount, l.discount FROM users u, (SELECT idno, SUM(amount) AS amount, SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,4,6,11,12,13,14,16) AND school_year = '$school_year' AND period = '$period' AND subsidiary = '$subsidiary' GROUP BY idno) l, college_levels c WHERE c.idno = u.idno AND l.amount !=0.00 AND u.idno = l.idno AND c.school_year = '$school_year' AND c.period = '$period' and (c.status = '3' or c.status = '4') ORDER BY u.lastname, c.program_code, c.section");
                $heads = DB::select("SELECT c.level, SUM(l.amount) AS 'total', SUM(l.discount) AS 'discount' FROM (SELECT idno, SUM(amount) AS 'amount', SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,4,6,11,12,13,14,16) AND school_year = '$school_year' AND period = '$period' AND subsidiary = '$subsidiary' GROUP BY idno) l, (SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr, college_levels c WHERE l.amount != 0.00 AND c.idno = l.idno AND ctr.level = c.level AND c.school_year = '$school_year' AND c.period = '$period' and (c.status = '3' or c.status = '4') GROUP BY c.level, ctr.sort_by ORDER BY ctr.sort_by");
            } else if ($department == "Senior High School") {
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, b.level, b.section, b.type_of_plan ,l.amount, l.discount FROM users u, (SELECT idno, SUM(amount) AS amount, SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,4,6,11,12,13,14,16) AND school_year = '$school_year' AND period = '$period' AND subsidiary = '$subsidiary' GROUP BY idno) l, bed_levels b WHERE b.idno = u.idno AND l.amount !=0.00 AND u.idno = l.idno AND b.department LIKE 'Senior High School' AND b.school_year = '$school_year' AND b.period = '$period' AND (b.status = '3' or b.status = '4') ORDER BY u.lastname, b.level, b.section");
                $heads = DB::select("SELECT b.level, SUM(l.amount) AS 'total', SUM(l.discount) AS 'discount' FROM (SELECT idno, SUM(amount) AS 'amount', SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,4,6,11,12,13,14,16) AND school_year = '$school_year' AND period = '$period' AND subsidiary = '$subsidiary' GROUP BY idno) l, (SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr, bed_levels b WHERE l.amount != 0.00 AND b.idno = l.idno AND ctr.level = b.level AND b.department LIKE 'Senior High School' AND b.school_year = '$school_year' AND b.period = '$period' AND (b.status = '3' or b.status = '4') GROUP BY b.level, ctr.sort_by ORDER BY ctr.sort_by");
            } else {
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, b.level, b.section, b.type_of_plan,l.amount, l.discount FROM users u, (SELECT idno, SUM(amount) AS amount, SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,13,16) AND school_year = '$school_year' AND subsidiary = '$subsidiary' GROUP BY idno) l, bed_levels b WHERE b.idno = u.idno AND l.amount !=0.00 AND u.idno = l.idno AND b.department LIKE '$department' AND b.school_year = '$school_year' AND (b.status = '3' or b.status = '4') ORDER BY u.lastname, b.level, b.section");
                $heads = DB::select("SELECT b.level, SUM(l.amount) AS 'total', SUM(l.discount) AS 'discount' FROM (SELECT idno, SUM(amount) AS 'amount', SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,14,16) AND school_year = '$school_year' AND subsidiary = '$subsidiary' GROUP BY idno) l, (SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr, bed_levels b WHERE l.amount != 0.00 AND b.idno = l.idno AND ctr.level = b.level AND b.department LIKE '$department' AND b.school_year = '$school_year' AND (b.status = '3' or b.status = '4') GROUP BY b.level, ctr.sort_by ORDER BY ctr.sort_by");
            }
            
             \App\Http\Controllers\Admin\Logs::log("Download Sibling Discount List Excel");
            ob_end_clean();
            Excel::create('Sibling Discount', 
                function($excel) use ($lists,$heads,$department,$school_year,$period,$subsidiary) { $excel->setTitle($department);
                    $excel->sheet($department, function ($sheet) use ($lists,$heads,$department,$school_year,$period,$subsidiary) {
                    $sheet->loadView('accounting.print_sibling_discount_excel', compact('lists','heads','department','school_year','period','subsidiary'));
                    });
                })->download('xlsx');
            
        }
        
    }
}
