<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;
use Excel;

class PaymentSummary extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    function payment_summary(){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            $school_years = \App\CourseOffering::distinct()->get(['school_year']);
            $levels = \App\CtrAcademicProgram::distinct()->where('academic_type','!=','College')->orderBy('sort_by', 'asc')->get(['level','sort_by']);
            return view('accounting.paymentsummary.payment_summary',compact('levels','school_years'));
        }
    }
}
