<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;
use App\Http\Controllers\Cashier\MainPayment;
use DB;

class BenefitScholar extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        if (Auth::user()->accesslevel == env("ACCTNG_HEAD") || Auth::user()->accesslevel == env("ACCTNG_STAFF")) {
            return view('accounting.benefit_scholar.index');
        }
    }

    function bed_index() {
        if (Auth::user()->accesslevel == env("ACCTNG_HEAD") || Auth::user()->accesslevel == env("ACCTNG_STAFF")) {
            return view('accounting.benefit_scholar.bed_index');
        }
    }
}
