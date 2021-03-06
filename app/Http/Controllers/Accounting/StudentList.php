<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;
use Excel;

class StudentList extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    function student_list(){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            $school_years = \App\Status::distinct()->get(['school_year']);
            $levels = \App\CtrAcademicProgram::distinct()->where('academic_type','!=','College')->orderBy('sort_by', 'asc')->get(['level','sort_by']);
            return view('accounting.student_list',compact('levels','school_years'));
        }
    }
    
    function print_studentlist_pdf(Request $request){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
//            ini_set("max_execution_time",1024);
            $dep = "";
            $department = $request->department;
            $school_year = $request->school_year;
            $period = $request->period;

            if ($department == "Senior High School") {
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, b.level, b.section, b.type_of_plan ,l.assessment, l.discount FROM users u, (SELECT idno, SUM(amount) AS assessment, SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,13,16) AND school_year = '$school_year' AND period = '$period' GROUP BY idno) l, bed_levels b WHERE b.idno = u.idno AND l.assessment !=0.00 AND u.idno = l.idno AND b.department LIKE 'Senior High School' AND b.school_year = '$school_year' AND b.period = '$period' AND (b.status = '3' or b.status = '4') ORDER BY u.lastname, b.level, b.section");
                $heads = DB::select("SELECT b.level, SUM(l.assessment) AS 'total', SUM(l.discount) AS 'discount' FROM (SELECT idno, SUM(amount) AS 'assessment', SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,13,16) AND school_year = '$school_year' AND period = '$period' GROUP BY idno) l, (SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr, bed_levels b WHERE l.assessment != 0.00 AND b.idno = l.idno AND ctr.level = b.level AND b.department LIKE 'Senior High School' AND b.school_year = '$school_year' AND b.period = '$period' AND (b.status = '3' or b.status = '4') GROUP BY b.level, ctr.sort_by ORDER BY ctr.sort_by");
                $this->getSRF($lists, $department,$school_year,$period);
            } else if ($department == "College Department") {
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, c.program_code, c.level, c.type_of_plan, l.assessment, l.discount FROM users u, (SELECT idno, SUM(amount) AS assessment, SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,13,16) AND school_year = '$school_year' AND period = '$period' GROUP BY idno) l, college_levels c WHERE c.idno = u.idno AND l.assessment !=0.00 AND u.idno = l.idno AND c.school_year = '$school_year' AND c.period = '$period' AND (c.status = '3' or c.status = '4') ORDER BY u.lastname, c.program_code, c.section");
                $heads = DB::select("SELECT c.level, SUM(l.assessment) AS 'total', SUM(l.discount) AS 'discount' FROM (SELECT idno, SUM(amount) AS 'assessment', SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,13,16) AND school_year = '$school_year' AND period = '$period' GROUP BY idno) l, (SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr, college_levels c WHERE l.assessment != 0.00 AND c.idno = l.idno AND ctr.level = c.level AND c.school_year = '$school_year' AND c.period = '$period' AND (c.status = '3' or c.status = '4') GROUP BY c.level, ctr.sort_by ORDER BY ctr.sort_by");
                $this->getSRF($lists, $department,$school_year,$period);
            } else {
                $dep = $department;                
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, b.level, b.section, b.type_of_plan,l.assessment, l.discount FROM users u, (SELECT idno, SUM(amount) AS 'assessment', SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,13,16) AND school_year = '$school_year' GROUP BY idno) l, bed_levels b WHERE b.idno = u.idno AND l.assessment !=0.00 AND u.idno = l.idno AND b.department LIKE '$department' AND b.school_year = '$school_year' AND (b.status = '3' or b.status = '4') ORDER BY u.lastname, b.level, b.section");
                $heads = DB::select("SELECT b.level, SUM(l.assessment) AS 'total', SUM(l.discount) AS 'discount' FROM (SELECT idno, SUM(amount) AS 'assessment', SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,14,16) AND school_year = '$school_year' GROUP BY idno) l, (SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr, bed_levels b WHERE l.assessment != 0.00 AND b.idno = l.idno AND ctr.level = b.level AND b.department LIKE '$department' AND b.school_year = '$school_year' AND (b.status = '3' or b.status = '4') GROUP BY b.level, ctr.sort_by ORDER BY ctr.sort_by");
                $this->getSRF($lists, $department,$school_year,$period);
            }
            
             \App\Http\Controllers\Admin\Logs::log("Print Print Student List PDF");
            $pdf = PDF::loadView('accounting.print_studentlist_pdf', compact('department','school_year','period','lists','heads'));
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream("student_list.pdf");
        }       
    }
    
    function print_studentlist_excel(Request $request){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            $dep = "";
            $department = $request->department;
            $school_year = $request->school_year;
            $period = $request->period;

            if ($department == "Senior High School") {
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, b.level, b.section, b.type_of_plan ,l.assessment, l.discount FROM users u, (SELECT idno, SUM(amount) AS assessment, SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,13,16) AND school_year = '$school_year' AND period = '$period' GROUP BY idno) l, bed_levels b WHERE b.idno = u.idno AND l.assessment !=0.00 AND u.idno = l.idno AND b.department LIKE 'Senior High School' AND b.school_year = '$school_year' AND b.period = '$period' AND (b.status = '3' or b.status = '4') ORDER BY u.lastname, b.level, b.section");
                $heads = DB::select("SELECT b.level, SUM(l.assessment) AS 'total', SUM(l.discount) AS 'discount' FROM (SELECT idno, SUM(amount) AS 'assessment', SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,13,16) AND school_year = '$school_year' AND period = '$period' GROUP BY idno) l, (SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr, bed_levels b WHERE l.assessment != 0.00 AND b.idno = l.idno AND ctr.level = b.level AND b.department LIKE 'Senior High School' AND b.school_year = '$school_year' AND b.period = '$period' AND (b.status = '3' or b.status = '4') GROUP BY b.level, ctr.sort_by ORDER BY ctr.sort_by");
                $this->getSRF($lists, $department,$school_year,$period);
            } else if ($department == "College Department") {
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, c.program_code, c.level, c.type_of_plan, l.assessment, l.discount FROM users u, (SELECT idno, SUM(amount) AS assessment, SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,13,16) AND school_year = '$school_year' AND period = '$period' GROUP BY idno) l, college_levels c WHERE c.idno = u.idno AND l.assessment !=0.00 AND u.idno = l.idno AND c.school_year = '$school_year' AND c.period = '$period' AND (c.status = '3' or c.status = '4') ORDER BY u.lastname, c.program_code, c.section");
                $heads = DB::select("SELECT c.level, SUM(l.assessment) AS 'total', SUM(l.discount) AS 'discount' FROM (SELECT idno, SUM(amount) AS 'assessment', SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,13,16) AND school_year = '$school_year' AND period = '$period' GROUP BY idno) l, (SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr, college_levels c WHERE l.assessment != 0.00 AND c.idno = l.idno AND ctr.level = c.level AND c.school_year = '$school_year' AND c.period = '$period' AND (c.status = '3' or c.status = '4') GROUP BY c.level, ctr.sort_by ORDER BY ctr.sort_by");
                $this->getSRF($lists, $department,$school_year,$period);
            } else {
                $dep = $department;                
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, b.level, b.section, b.type_of_plan,l.assessment, l.discount FROM users u, (SELECT idno, SUM(amount) AS 'assessment', SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,13,16) AND school_year = '$school_year' GROUP BY idno) l, bed_levels b WHERE b.idno = u.idno AND l.assessment !=0.00 AND u.idno = l.idno AND b.department LIKE '$department' AND b.school_year = '$school_year' AND (b.status = '3' or b.status = '4') ORDER BY u.lastname, b.level, b.section");
                $heads = DB::select("SELECT b.level, SUM(l.assessment) AS 'total', SUM(l.discount) AS 'discount' FROM (SELECT idno, SUM(amount) AS 'assessment', SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,14,16) AND school_year = '$school_year' GROUP BY idno) l, (SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr, bed_levels b WHERE l.assessment != 0.00 AND b.idno = l.idno AND ctr.level = b.level AND b.department LIKE '$department' AND b.school_year = '$school_year' AND (b.status = '3' or b.status = '4') GROUP BY b.level, ctr.sort_by ORDER BY ctr.sort_by");
                $this->getSRF($lists, $department,$school_year,$period);
            }
            
             \App\Http\Controllers\Admin\Logs::log("Download Student List Excel");
            ob_end_clean();
            Excel::create('Student List - ' .$department, 
                function($excel) use ($department,$lists,$heads,$school_year,$period) { $excel->setTitle($department);
                    $excel->sheet($department, function ($sheet) use ($department,$lists,$heads,$school_year,$period) {
                    $sheet->loadView('accounting.print_studentlist_excel', compact('department','school_year','period','lists','heads'));
                    });
                })->download('xlsx');
        }
    }

    function getSRF($lists, $department,$school_year,$period) {
        foreach ($lists as $list) {
            if ($department == "College Department") {
                $lists_srf = DB::select("SELECT SUM(amount) AS assessment FROM ledgers WHERE category_switch IN (4,14) AND category = 'SRF' AND school_year = $school_year AND period = '$period' and idno = '$list->idno'");
                if (count($lists_srf) > 0) {
                    foreach ($lists_srf as $list_srf) {
                        $srf_amount = $list_srf->assessment;
                    }
                }else{
                    $srf_amount = 0;
                }
                $list->srf_amount = $srf_amount;
            } else if($department == "Senior High School") {
                $lists_srf = DB::select("SELECT SUM(amount) AS assessment FROM ledgers WHERE category_switch IN (4,14) AND category = 'SRF' AND school_year = $school_year AND period = '$period' and idno = '$list->idno'");
                if (count($lists_srf) > 0) {
                    foreach ($lists_srf as $list_srf) {
                        $srf_amount = $list_srf->assessment;
                    }
                }
                $list->srf_amount = $srf_amount;
            } else {
                $list->srf_amount = 0;
            }
        }
    }
    
    function print_studentlist_pdf1(Request $request){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            
            $dep = "";
            $department = $request->department;
            $school_year = $request->school_year;
            $period = $request->period;

            if ($department == "Senior High School") {
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, b.level, b.section, b.type_of_plan ,l.assessment, l.discount FROM users u, (SELECT idno, SUM(amount) AS assessment, SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,13,16) AND school_year = '$school_year' AND period = '$period' GROUP BY idno) l, bed_levels b WHERE b.idno = u.idno AND l.assessment !=0.00 AND u.idno = l.idno AND b.department LIKE 'Senior High School' AND b.school_year = '$school_year' AND b.period = '$period' AND (b.status = '3' or b.status = '4') ORDER BY u.lastname, b.level, b.section");
                $heads = DB::select("SELECT b.level, SUM(l.assessment) AS 'total', SUM(l.discount) AS 'discount' FROM (SELECT idno, SUM(amount) AS 'assessment', SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,13,16) AND school_year = '$school_year' AND period = '$period' GROUP BY idno) l, (SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr, bed_levels b WHERE l.assessment != 0.00 AND b.idno = l.idno AND ctr.level = b.level AND b.department LIKE 'Senior High School' AND b.school_year = '$school_year' AND b.period = '$period' AND (b.status = '3' or b.status = '4') GROUP BY b.level, ctr.sort_by ORDER BY ctr.sort_by");
            } else if ($department == "College Department") {
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, c.program_code, c.level, c.type_of_plan, l.assessment, l.discount FROM users u, (SELECT idno, SUM(amount) AS assessment, SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,13,16) AND school_year = '$school_year' AND period = '$period' GROUP BY idno) l, college_levels c WHERE c.idno = u.idno AND l.assessment !=0.00 AND u.idno = l.idno AND c.school_year = '$school_year' AND c.period = '$period' AND (c.status = '3' or c.status = '4') ORDER BY u.lastname, c.program_code, c.section");
                $heads = DB::select("SELECT c.level, SUM(l.assessment) AS 'total', SUM(l.discount) AS 'discount' FROM (SELECT idno, SUM(amount) AS 'assessment', SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,13,16) AND school_year = '$school_year' AND period = '$period' GROUP BY idno) l, (SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr, college_levels c WHERE l.assessment != 0.00 AND c.idno = l.idno AND ctr.level = c.level AND c.school_year = '$school_year' AND c.period = '$period' AND (c.status = '3' or c.status = '4') GROUP BY c.level, ctr.sort_by ORDER BY ctr.sort_by");
            } else {
                $dep = $department;                
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, b.level, b.section, b.type_of_plan,l.assessment, l.discount FROM users u, (SELECT idno, SUM(amount) AS 'assessment', SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,13,16) AND school_year = '$school_year' GROUP BY idno) l, bed_levels b WHERE b.idno = u.idno AND l.assessment !=0.00 AND u.idno = l.idno AND b.department LIKE '$department' AND b.school_year = '$school_year' AND (b.status = '3' or b.status = '4') ORDER BY u.lastname, b.level, b.section");
                $heads = DB::select("SELECT b.level, SUM(l.assessment) AS 'total', SUM(l.discount) AS 'discount' FROM (SELECT idno, SUM(amount) AS 'assessment', SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,14,16) AND school_year = '$school_year' GROUP BY idno) l, (SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr, bed_levels b WHERE l.assessment != 0.00 AND b.idno = l.idno AND ctr.level = b.level AND b.department LIKE '$department' AND b.school_year = '$school_year' AND (b.status = '3' or b.status = '4') GROUP BY b.level, ctr.sort_by ORDER BY ctr.sort_by");
            }
            
             \App\Http\Controllers\Admin\Logs::log("Print Print Student List PDF");
            $pdf = PDF::loadView('accounting.print_studentlist_pdf', compact('department','school_year','period','lists','heads'));
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream("student_list.pdf");
        }       
    }
    
    function print_studentlist_excel1(Request $request){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            $dep = "";
            $department = $request->department;
            $school_year = $request->school_year;
            $period = $request->period;

            if ($department == "Senior High School") {
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, b.level, b.section, b.type_of_plan ,l.assessment, l.discount FROM users u, (SELECT idno, SUM(amount) AS assessment, SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,13,16) AND school_year = '$school_year' AND period = '$period' GROUP BY idno) l, bed_levels b WHERE b.idno = u.idno AND l.assessment !=0.00 AND u.idno = l.idno AND b.department LIKE 'Senior High School' AND b.school_year = '$school_year' AND b.period = '$period' AND (b.status = '3' or b.status = '4') ORDER BY u.lastname, b.level, b.section");
                $heads = DB::select("SELECT b.level, SUM(l.assessment) AS 'total', SUM(l.discount) AS 'discount' FROM (SELECT idno, SUM(amount) AS 'assessment', SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,13,16) AND school_year = '$school_year' AND period = '$period' GROUP BY idno) l, (SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr, bed_levels b WHERE l.assessment != 0.00 AND b.idno = l.idno AND ctr.level = b.level AND b.department LIKE 'Senior High School' AND b.school_year = '$school_year' AND b.period = '$period' AND (b.status = '3' or b.status = '4') GROUP BY b.level, ctr.sort_by ORDER BY ctr.sort_by");
            } else if ($department == "College Department") {
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, c.program_code, c.level, c.type_of_plan, l.assessment, l.discount FROM users u, (SELECT idno, SUM(amount) AS assessment, SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,13,16) AND school_year = '$school_year' AND period = '$period' GROUP BY idno) l, college_levels c WHERE c.idno = u.idno AND l.assessment !=0.00 AND u.idno = l.idno AND c.school_year = '$school_year' AND c.period = '$period' AND (c.status = '3' or c.status = '4') ORDER BY u.lastname, c.program_code, c.section");
                $heads = DB::select("SELECT c.level, SUM(l.assessment) AS 'total', SUM(l.discount) AS 'discount' FROM (SELECT idno, SUM(amount) AS 'assessment', SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,13,16) AND school_year = '$school_year' AND period = '$period' GROUP BY idno) l, (SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr, college_levels c WHERE l.assessment != 0.00 AND c.idno = l.idno AND ctr.level = c.level AND c.school_year = '$school_year' AND c.period = '$period' AND (c.status = '3' or c.status = '4') GROUP BY c.level, ctr.sort_by ORDER BY ctr.sort_by");
            } else {
                $dep = $department;                
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, b.level, b.section, b.type_of_plan,l.assessment, l.discount FROM users u, (SELECT idno, SUM(amount) AS 'assessment', SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,13,16) AND school_year = '$school_year' GROUP BY idno) l, bed_levels b WHERE b.idno = u.idno AND l.assessment !=0.00 AND u.idno = l.idno AND b.department LIKE '$department' AND b.school_year = '$school_year' AND (b.status = '3' or b.status = '4') ORDER BY u.lastname, b.level, b.section");
                $heads = DB::select("SELECT b.level, SUM(l.assessment) AS 'total', SUM(l.discount) AS 'discount' FROM (SELECT idno, SUM(amount) AS 'assessment', SUM(discount) AS discount FROM ledgers WHERE category_switch IN (1,2,3,6,11,12,14,16) AND school_year = '$school_year' GROUP BY idno) l, (SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr, bed_levels b WHERE l.assessment != 0.00 AND b.idno = l.idno AND ctr.level = b.level AND b.department LIKE '$department' AND b.school_year = '$school_year' AND (b.status = '3' or b.status = '4') GROUP BY b.level, ctr.sort_by ORDER BY ctr.sort_by");
            }
            
             \App\Http\Controllers\Admin\Logs::log("Download Student List Excel");
            ob_end_clean();
            Excel::create('Student List - ' .$department, 
                function($excel) use ($department,$lists,$heads,$school_year,$period) { $excel->setTitle($department);
                    $excel->sheet($department, function ($sheet) use ($department,$lists,$heads,$school_year,$period) {
                    $sheet->loadView('accounting.print_studentlist_excel', compact('department','school_year','period','lists','heads'));
                    });
                })->download('xlsx');
        }
    }
    
    function student_list_report(){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            $levels = array('Pre-Kinder','Kinder','Grade 1','Grade 2','Grade 3','Grade 4','Grade 5','Grade 6','Grade 7','Grade 8','Grade 9','Grade 10','Grade 11','Grade 12','1st Year','2nd Year','3rd Year','4th Year');
//            return view('accounting.student_list_report',compact('school_year', 'period', 'levels'));
             \App\Http\Controllers\Admin\Logs::log("Download Student List_Report Excel");
            ob_end_clean();
            Excel::create('Student List Report', 
                function($excel) use ($levels) { $excel->setTitle('Student List Report');
                    $excel->sheet('Student List Report', function ($sheet) use ($levels) {
                    $sheet->loadView('accounting.student_list_report',compact('levels'));
                    });
                })->download('xlsx');
        }
    }
}

