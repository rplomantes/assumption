<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;
use PDF;

class Reservations extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index2() {
        if (Auth::user()->accesslevel == env("ACCTNG_STAFF") || Auth::user()->accesslevel == env("ACCTNG_HEAD")) {
            return view('accounting.reservations');
        }
    }

    function index() {
        if (Auth::user()->accesslevel == env("ACCTNG_STAFF") || Auth::user()->accesslevel == env("ACCTNG_HEAD")) {
            $reservations = \App\Reservation::where('reservation_type', 1)->where('is_consumed', 0)->join('users', 'users.idno', '=', 'reservations.idno')->join('statuses', 'statuses.idno', '=', 'reservations.idno')->orderBy('statuses.level', 'asc')->orderBy('users.lastname', 'asc')->orderBy('reservations.transaction_date', 'asc')->get();

            \App\Http\Controllers\Admin\Logs::log("Print reservation report");

            $pdf = PDF::loadView('accounting.print_reservations', compact('reservations'));
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream("reservations_report.pdf");
        }
    }

}
