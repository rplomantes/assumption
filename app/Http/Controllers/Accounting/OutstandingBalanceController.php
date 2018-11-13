<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
//use Barryvdh\DomPDF\PDF;
use PDF;
use Excel;

class OutstandingBalanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    function outstanding_balance(){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            $levels = \App\CtrAcademicProgram::distinct()->where('academic_type','!=','College')->orderBy('sort_by', 'asc')->get(['level','sort_by']);
            return view('accounting.outstanding_balance',compact('levels'));
        }
    }
    
    function print_outstanding_balancePDF(Request $request){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            
            $dep = "";
            $department = $request->department;
            
            if($department == "College Department"){
                $dep = '%Department';
            }
            else{
                $dep = $department;
            }
            $lists = DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.program_code,s.level,s.section, l.balance FROM users u, statuses s, (SELECT idno, (sum(amount) - (sum(debit_memo) + sum(discount))) - sum(payment) as 'balance' FROM `ledgers` GROUP BY idno) l WHERE l.balance != 0.00 and u.idno = s.idno and u.idno = l.idno and s.department LIKE '$dep' ORDER BY u.lastname,s.program_code,s.level,s.section");
//            $heads = DB::select("SELECT sq.level, sq.total FROM ctr_academic_programs ctr, (SELECT s.level,sum(l.balance) as 'total' FROM users u, statuses s, (SELECT idno,(sum(amount) - (sum(debit_memo) + sum(discount))) - sum(payment) as 'balance' FROM `ledgers` GROUP BY idno) l WHERE l.balance != 0.00 and u.idno = s.idno and u.idno = l.idno and s.department LIKE '$dep' GROUP BY s.level) sq WHERE sq.level = ctr.level ORDER BY ctr.sort_by");
            $heads = DB::select("SELECT s.level,sum(l.balance) as 'total' FROM statuses s, (SELECT idno,(sum(amount) - (sum(debit_memo) + sum(discount))) - sum(payment) as 'balance' FROM `ledgers` GROUP BY idno) l,(SELECT DISTINCT level,sort_by FROM ctr_academic_programs) ctr WHERE l.balance != 0.00 and s.idno = l.idno and s.department LIKE '$dep' and ctr.level = s.level GROUP BY s.level,ctr.sort_by ORDER BY ctr.sort_by");
            $pdf = PDF::loadView('accounting.print_outstanding_balance', compact('department','lists','heads'));
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream("outstanding_balance.pdf");
        }
        
    }
    
    function print_outstanding_balanceEXCEL(Request $request){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            $dep = "";
            
            $department = $request->department;
            $date = date("F d, Y");
            
            if($department == "College Department"){
                $dep = '%Department';
            }
            else{
                $dep = $department;
            }
            
            $lists = DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.program_code,s.level,s.section, l.balance FROM users u, statuses s, (SELECT idno, (sum(amount) - (sum(debit_memo) + sum(discount))) - sum(payment) as 'balance' FROM `ledgers` GROUP BY idno) l WHERE l.balance != 0.00 and u.idno = s.idno and u.idno = l.idno and s.department LIKE '$dep' ORDER BY u.lastname,s.program_code,s.level,s.section");
            $heads = DB::select("SELECT s.level,sum(l.balance) as 'total' FROM statuses s, (SELECT idno,(sum(amount) - (sum(debit_memo) + sum(discount))) - sum(payment) as 'balance' FROM `ledgers` GROUP BY idno) l,(SELECT DISTINCT level,sort_by FROM ctr_academic_programs) ctr WHERE l.balance != 0.00 and s.idno = l.idno and s.department LIKE '$dep' and ctr.level = s.level GROUP BY s.level,ctr.sort_by ORDER BY ctr.sort_by");
            
            ob_end_clean();
            Excel::create('Outstanding Balances - '.$date, 
                function($excel) use ($department,$lists,$heads) { $excel->setTitle($department);
                    $excel->sheet($department, function ($sheet) use ($department,$lists,$heads) {
                    $sheet->loadView('accounting.print_outstanding_balance_excel', compact('department','lists','heads'));
                    });
                })->download('xlsx');
            
        }
        
    }
}

