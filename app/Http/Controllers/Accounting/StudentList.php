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
            $school_years = \App\CourseOffering::distinct()->get(['school_year']);
            $levels = \App\CtrAcademicProgram::distinct()->where('academic_type','!=','College')->orderBy('sort_by', 'asc')->get(['level','sort_by']);
            return view('accounting.student_list',compact('levels','school_years'));
        }
    }
    
    function print_studentlist_pdf(Request $request){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            
            $dep = "";
            $department = $request->department;
            $school_year = $request->school_year;
            $period = $request->period;
            
            if ($department == "Senior High School") {
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, s.level, s.section, SUBSTR(s.type_of_plan,5) AS type_of_plan, l.assessment FROM users u, bed_levels s, (SELECT idno, SUM(amount) AS 'assessment' FROM `ledgers` GROUP BY idno) l WHERE l.assessment != 0.00 AND u.idno = s.idno AND u.idno = l.idno AND s.department LIKE 'Senior High School' AND s.school_year = '$school_year' AND s.period = '$period' AND s.status = '3' ORDER BY u.lastname, s.strand, s.level, s.section");
                $heads = DB::select("SELECT s.level, SUM(l.assessment) AS 'total' FROM bed_levels s, (SELECT idno,(SUM(amount)) AS 'assessment' FROM `ledgers` GROUP BY idno) l,(SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr WHERE l.assessment != 0.00 AND s.idno = l.idno AND s.department LIKE 'Senior High School' AND s.school_year = '$school_year' AND s.period = '$period' AND s.status = '3' AND ctr.level = s.level GROUP BY s.level, ctr.sort_by ORDER BY ctr.sort_by");
            } else if ($department == "College Department") {
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, s.program_code, s.level, SUBSTR(s.type_of_plan,5) AS type_of_plan, l.assessment FROM users u, college_levels s, (SELECT idno, SUM(amount) AS 'assessment' FROM `ledgers` GROUP BY idno) l WHERE l.assessment != 0.00 AND u.idno = s.idno AND u.idno = l.idno AND s.school_year = '$school_year' AND s.period = '$period' AND s.status = '3' ORDER BY u.lastname, s.program_code, s.level");
                $heads = DB::select("SELECT s.level, SUM(l.assessment) AS 'total' FROM college_levels s, (SELECT idno,(SUM(amount)) AS 'assessment' FROM `ledgers` GROUP BY idno) l,(SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr WHERE l.assessment != 0.00 AND s.idno = l.idno AND s.school_year = '$school_year' AND s.period = '$period' AND s.status = '3' AND ctr.level = s.level GROUP BY s.level, ctr.sort_by ORDER BY ctr.sort_by");
            } else {
                $dep = $department;
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, s.level, s.section, SUBSTR(s.type_of_plan,5) AS type_of_plan, l.assessment FROM users u, bed_levels s, (SELECT idno, SUM(amount) AS 'assessment' FROM `ledgers` GROUP BY idno) l WHERE l.assessment != 0.00 AND u.idno = s.idno AND u.idno = l.idno AND s.department LIKE '$dep' AND s.school_year = '$school_year' AND s.status = '3' ORDER BY u.lastname, s.level, s.section");
                $heads = DB::select("SELECT s.level, SUM(l.assessment) AS 'total' FROM bed_levels s, (SELECT idno,(SUM(amount)) AS 'assessment' FROM `ledgers` GROUP BY idno) l,(SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr WHERE l.assessment != 0.00 AND s.idno = l.idno AND s.department LIKE '$dep' AND s.school_year = '$school_year' AND s.status = '3' AND ctr.level = s.level GROUP BY s.level, ctr.sort_by ORDER BY ctr.sort_by");
            }
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
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, s.level, s.section, SUBSTR(s.type_of_plan,5) AS type_of_plan, l.assessment FROM users u, bed_levels s, (SELECT idno, SUM(amount) AS 'assessment' FROM `ledgers` GROUP BY idno) l WHERE l.assessment != 0.00 AND u.idno = s.idno AND u.idno = l.idno AND s.department LIKE 'Senior High School' AND s.school_year = '$school_year' AND s.period = '$period' AND s.status = '3' ORDER BY u.lastname, s.strand, s.level, s.section");
                $heads = DB::select("SELECT s.level, SUM(l.assessment) AS 'total' FROM bed_levels s, (SELECT idno,(SUM(amount)) AS 'assessment' FROM `ledgers` GROUP BY idno) l,(SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr WHERE l.assessment != 0.00 AND s.idno = l.idno AND s.department LIKE 'Senior High School' AND s.school_year = '$school_year' AND s.period = '$period' AND s.status = '3' AND ctr.level = s.level GROUP BY s.level, ctr.sort_by ORDER BY ctr.sort_by");
            } else if ($department == "College Department") {
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, s.program_code, s.level, SUBSTR(s.type_of_plan,5) AS type_of_plan, l.assessment FROM users u, college_levels s, (SELECT idno, SUM(amount) AS 'assessment' FROM `ledgers` GROUP BY idno) l WHERE l.assessment != 0.00 AND u.idno = s.idno AND u.idno = l.idno AND s.school_year = '$school_year' AND s.period = '$period' AND s.status = '3' ORDER BY u.lastname, s.program_code, s.level");
                $heads = DB::select("SELECT s.level, SUM(l.assessment) AS 'total' FROM college_levels s, (SELECT idno,(SUM(amount)) AS 'assessment' FROM `ledgers` GROUP BY idno) l,(SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr WHERE l.assessment != 0.00 AND s.idno = l.idno AND s.school_year = '$school_year' AND s.period = '$period' AND s.status = '3' AND ctr.level = s.level GROUP BY s.level, ctr.sort_by ORDER BY ctr.sort_by");
            } else {
                $dep = $department;
                $lists = DB::select("SELECT u.idno, u.lastname, u.firstname, u.middlename, u.extensionname, s.level, s.section, SUBSTR(s.type_of_plan,5) AS type_of_plan, l.assessment FROM users u, bed_levels s, (SELECT idno, SUM(amount) AS 'assessment' FROM `ledgers` GROUP BY idno) l WHERE l.assessment != 0.00 AND u.idno = s.idno AND u.idno = l.idno AND s.department LIKE '$dep' AND s.school_year = '$school_year' AND s.status = '3' ORDER BY u.lastname, s.level, s.section");
                $heads = DB::select("SELECT s.level, SUM(l.assessment) AS 'total' FROM bed_levels s, (SELECT idno,(SUM(amount)) AS 'assessment' FROM `ledgers` GROUP BY idno) l,(SELECT DISTINCT level, sort_by FROM ctr_academic_programs) ctr WHERE l.assessment != 0.00 AND s.idno = l.idno AND s.department LIKE '$dep' AND s.school_year = '$school_year' AND s.status = '3' AND ctr.level = s.level GROUP BY s.level, ctr.sort_by ORDER BY ctr.sort_by");
            }
            
            ob_end_clean();
            Excel::create('Student List - ' .$department, 
                function($excel) use ($department,$lists,$heads) { $excel->setTitle($department);
                    $excel->sheet($department, function ($sheet) use ($department,$lists,$heads) {
                    $sheet->loadView('accounting.print_studentlist_excel', compact('department','school_year','period','lists','heads'));
                    });
                })->download('xlsx');
        }
    }
}

