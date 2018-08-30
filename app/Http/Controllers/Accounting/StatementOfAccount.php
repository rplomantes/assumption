<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;

class StatementOfAccount extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function indexSOA_BED() {
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            $levels = \App\CtrAcademicProgram::distinct()->where('academic_type', 'BED')->orderBy('sort_by', 'asc')->get(['level', 'sort_by']);
            $strands = \App\CtrAcademicProgram::distinct()->where('academic_code', 'SHS')->get(['strand']);
            return view('accounting.statement_of_account_bed', compact('levels', 'strands'));
        }
    }

    function printSOA_BED($remarks, $due_date, $idno) {
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {

        \App\Http\Controllers\Admin\Logs::log("Print SOA of student - $idno.");
            $pdf = PDF::loadView('accounting.print_statement_of_account_bed', compact('idno','due_date','remarks'));
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream("statement_of_account.pdf");
        }
    }

    function printallSOA_BED(Request $request) {
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {

            $plan = $request->plan;
            $level = $request->level;
            $strand = $request->strand;
            $section = $request->section;
            $due_date = $request->due_date;
            $remarks = $request->remarks;
            
            if($level == "Grade 11" || $level == "Grade 12"){
                $strand = $strand;                
                if ($strand == "ALL") {
                    $qstrand = "";
                } else {
                    $qstrand = "and strand = '" . $strand . "'";
                }
            }else{
                $qstrand= "";
                $strand = NULL;
            }
            
            if ($plan == "ALL") {
                $qplan = "";
            } else {
                $qplan = "and type_of_plan = '" . $plan . "'";
            }
            if ($level == "ALL") {
                $qlevel = "";
            } else {
                $qlevel = "and level = '" . $level . "'";
            }
            if ($section == "ALL") {
                $qsection = "";
            } else {
                $qsection = "and section = '" . $section . "'";
            }
            $students = \App\Status::whereRaw("statuses.id is not null $qplan $qlevel $qstrand $qsection and statuses.status=3")
                    ->where('statuses.academic_type', "!=",'College')
                    ->where('statuses.status', 3)
                    ->join('users', 'users.idno','=','statuses.idno')
                    ->orderBy('users.lastname', 'asc')
                    ->get();

            \App\Http\Controllers\Admin\Logs::log("Print bulk SOA= plan:$plan, leve:$level, strand:$strand, section:$section, due dat:$due_date.");
            
            $pdf = PDF::loadView('accounting.printall_statement_of_account_bed', compact('students', 'plan','level','strand','section','due_date','remarks'));
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream("statement_of_account.pdf");
//            return view('accounting.printall_statement_of_account_bed', compact('students', 'plan','level','strand','section','due_date','remarks'));
        }
    }

}
