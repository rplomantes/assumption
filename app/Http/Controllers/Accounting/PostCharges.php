<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PostCharges extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    function index(){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
        $indic = 0;
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
                $ledger->receipt_details = "Late Payment Charge";
                $ledger->accounting_code = env('SURCHARGE_CODE');
                $ledger->accounting_name = env('SURCHARGE_NAME');
                $ledger->category_switch = "7";
                $ledger->amount = env('SURCHARGE_AMOUNT');
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
            $levels = \App\CtrAcademicProgram::distinct()->where('academic_type','!=','College')->orderBy('level', 'asc')->get(['level']);
            $plans = \App\CtrDueDateBed::distinct()->orderBy('plan', 'asc')->get(['plan']);
            return view('accounting.post_charges',compact('levels','plans','indic'));
        }
    }
}
