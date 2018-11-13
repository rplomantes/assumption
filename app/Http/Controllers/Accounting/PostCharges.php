<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon;

class PostCharges extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    function index(){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
        $indic = 0;
        
        
//        $dateToday = Carbon\Carbon::now();
//        $dates = date_format($dateToday,'m') - 1;
//        $unpaid = DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.program_code,s.level,s.section, l.balance FROM users u, statuses s, (SELECT idno, (sum(amount) - (sum(debit_memo) + sum(discount))) - sum(payment) as 'balance' FROM `ledgers` GROUP BY school_year,idno) l WHERE l.balance != 0.00 and u.idno = s.idno and u.idno = l.idno and s.type_of_plan != 'Plan A' and s.department NOT LIKE '%Department' ORDER BY s.program_code,s.level,s.section");
//        return view('accounting.post_charges',compact('unpaid','dates','indic'));
      
        $levels = \App\CtrAcademicProgram::distinct()->where('academic_type','!=','College')->orderBy('sort_by', 'asc')->get(['level','sort_by']);
        $plans = \App\CtrDueDateBed::distinct()->orderBy('plan', 'asc')->get(['plan']);
        return view('accounting.post_charges',compact('levels','plans','indic'));
      }
    }
    
    public function postCharges(Request $request) {
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
           DB::beginTransaction();
//         $charges = \App\CtrLatePaymentCharges::where('academic_type', $request->acad)->where('plan', $request->plan)->first();
           $indic = 0;
            foreach ($request->post as $idno) {
                $ledger = new \App\Ledger;
                $ledger->idno = $idno;
                $status = \App\Status::where('idno', $idno)->first();
                $school_period = \App\CtrAcademicSchoolYear::where('academic_type', $status->academic_type)->first();
                if ($status->status > 0) {
                    $ledger->program_code = $status->academic_program;
                    $ledger->level = $status->level;
                    $ledger->school_year = $school_period->school_year;
                }
                $ledger->category = "Other Miscellaneous";
                $ledger->subsidiary = "Late Payment Charge";
                $ledger->receipt_details = "Late Payment Charge for the month of ". date('F', strtotime("0000-$request->date-$request->date"));
                $ledger->accounting_code = env('SURCHARGE_CODE');
                $ledger->accounting_name = env('SURCHARGE_NAME');
                $ledger->category_switch = "7";
                $ledger->amount = env('SURCHARGE_AMOUNT')*$request->count[$indic];
                $ledger->save();
                $indic++;
                
                $posted = new \App\PostedCharges;
                $posted->idno = $idno;
                $posted->due_date = $request->date;
                $posted->date_posted = \Carbon\Carbon::now();
                $posted->amount = env('SURCHARGE_AMOUNT');
                $posted->is_reversed = 0;
                $posted->posted_by = Auth::user()->idno;
                $posted->save();
            }
            DB::commit();
            $levels = \App\CtrAcademicProgram::distinct()->where('academic_type','!=','College')->orderBy('sort_by', 'asc')->get(['level','sort_by']);
            $plans = \App\CtrDueDateBed::distinct()->orderBy('plan', 'asc')->get(['plan']);
            
            \App\Http\Controllers\Admin\Logs::log("Post late payment charges.");
            return view('accounting.post_charges',compact('levels','plans','indic'));
        }
    }
}
