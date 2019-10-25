<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;
use PDF;
use Excel;

class NstpController extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            return view('accounting.nstp_reports');
        }
    }

    function get_list(Request $request) {
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            
            $levels = ['1st Year','2nd Year', '3rd Year','4th Year', '5th Year'];
            
            if ($request->submit == "print_pdf") {
                $pdf = PDF::loadView('accounting.print_nstp_reports', compact('request','levels'));
                $pdf->setPaper(array(0, 0, 612, 936));
                return $pdf->stream('nstp_reports.pdf');
            } else {
                ob_end_clean();
                Excel::create('NSTP-REPORT', function($excel) use ($request, $levels) {
                    $excel->setTitle("NSTP Report");
                    $excel->sheet("NSTP Report", function ($sheet) use ($request, $levels) {
                        $sheet->loadView('accounting.print_nstp_reports_excel', compact('request','levels'));
                    });
                })->download('xlsx');
            }
        }
    }

}
