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
            return view('accounting.outstanding_balance');
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
            
            $lists = DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.program_code,s.level,s.section, l.balance FROM users u, statuses s, (SELECT idno, (sum(amount) - (sum(debit_memo) + sum(discount))) - sum(payment) as 'balance' FROM `ledgers` GROUP BY school_year,idno) l WHERE l.balance != 0.00 and u.idno = s.idno and u.idno = l.idno and s.department LIKE '$dep' ORDER BY s.program_code,s.level,s.section");
            $pdf = PDF::loadView('accounting.print_outstanding_balance', compact('department','lists'));
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream("outstanding_balance.pdf");
        }
        
    }
    
    function print_outstanding_balanceEXCEL(Request $request){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            $dep = "";
            $department = $request->department;
            $date = date("F m,Y");
            
            if($department == "College Department"){
                $dep = '%Department';
            }
            else{
                $dep = $department;
            }
            
            $lists = DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.program_code,s.level,s.section, l.balance FROM users u, statuses s, (SELECT idno, (sum(amount) - (sum(debit_memo) + sum(discount))) - sum(payment) as 'balance' FROM `ledgers` GROUP BY school_year,idno) l WHERE l.balance != 0.00 and u.idno = s.idno and u.idno = l.idno and s.department LIKE '$dep' ORDER BY s.program_code,s.level,s.section");
            
            ob_end_clean();
            Excel::create('Outstanding Balances - '.$date, 
                function($excel) use ($department,$lists) { $excel->setTitle($department);
                    $excel->sheet($department, function ($sheet) use ($department,$lists) {
                    $sheet->loadView('accounting.print_outstanding_balance_excel', compact('department','lists'));
                    });
                })->download('xlsx');
            
        }
        
    }
}

