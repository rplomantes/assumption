<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use PDF;
use Excel;

class StudentsAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    function students_account(){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            return view('accounting.students_account');
        }
    }
    
    function print_students_accountPDF(Request $request){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            
            $account = $request->account;
            $school_year = $request->school_year;
            $department = $request->department;
            $period = $request->period;
            
            if ($department == "College Department") {
                $dep = '%Department';
                $lists = DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.program_code,s.level,s.section,s.academic_type FROM users u, statuses s WHERE u.idno = s.idno and u.idno IN (SELECT idno FROM `ledgers` WHERE accounting_code = '$account' and school_year = '$school_year' and period = '$period' GROUP BY accounting_code,idno) and s.department LIKE '$dep' ORDER BY u.lastname,s.program_code,s.level,s.section");
            } else {
                $dep = $department;
                $lists = DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.program_code,s.level,s.section,s.academic_type FROM users u, statuses s WHERE u.idno = s.idno and u.idno IN (SELECT idno FROM `ledgers` WHERE accounting_code = '$account' and school_year = '$school_year' GROUP BY accounting_code,idno) and s.department LIKE '$dep' ORDER BY u.lastname,s.program_code,s.level,s.section");
            }
            
            $info = \App\ChartOfAccount::where('accounting_code',$account)->first();
            $pdf = PDF::loadView('accounting.print_student_per_account', compact('info','lists','account','department','school_year','period'));
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream("student_per_account.pdf");
        }
        
    }
    
    function print_students_accountEXCEL(Request $request){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            
            $account = $request->account;
            $school_year = $request->school_year;
            $department = $request->department;
            $period = $request->period;
            
            if ($department == "College Department") {
                $dep = '%Department';
                $lists = DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.program_code,s.level,s.section,s.academic_type FROM users u, statuses s WHERE u.idno = s.idno and u.idno IN (SELECT idno FROM `ledgers` WHERE accounting_code = '$account' and school_year = '$school_year' and period = '$period' GROUP BY accounting_code,idno) and s.department LIKE '$dep' ORDER BY u.lastname,s.program_code,s.level,s.section");
            } else {
                $dep = $department;
                $lists = DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.program_code,s.level,s.section,s.academic_type FROM users u, statuses s WHERE u.idno = s.idno and u.idno IN (SELECT idno FROM `ledgers` WHERE accounting_code = '$account' and school_year = '$school_year' GROUP BY accounting_code,idno) and s.department LIKE '$dep' ORDER BY u.lastname,s.program_code,s.level,s.section");
            }
            
            $info = \App\ChartOfAccount::where('accounting_code',$account)->first();
//            $lists = DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.program_code,s.level,s.section,s.academic_type FROM users u, statuses s WHERE u.idno = s.idno and u.idno IN (SELECT idno FROM `ledgers` WHERE accounting_code = '$account' GROUP BY accounting_code,idno) ORDER BY s.program_code,s.level,s.section");
            
            $name = $info->accounting_name;
                
            ob_end_clean();
            Excel::create('Student per Account - '.$account, 
                function($excel) use ($name,$lists,$info,$account,$department,$school_year,$period) { $excel->setTitle($name);
                    $excel->sheet($name, function ($sheet) use ($name,$lists,$info,$account,$department,$school_year,$period) {
                    $sheet->loadView('accounting.print_student_per_account_excel', compact('info','lists','account','department','school_year','period'));
                    });
                })->download('xlsx');
            
        }
        
    }
}
