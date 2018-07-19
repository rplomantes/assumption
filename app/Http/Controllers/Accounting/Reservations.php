<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;
use PDF;

class Reservations extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth');
    }
    function index(){
        if (Auth::user()->accesslevel == env("ACCTNG_STAFF")) {
           $reservations = \App\Reservation::where('reservation_type', 1)->where('is_consumed', 0)->join('users', 'users.idno','=','reservations.idno')->orderBy('users.lastname', 'asc')->orderBy('reservations.transaction_date', 'asc')->get();
           
           $pdf = PDF::loadView('accounting.print_reservations', compact('reservations'));           
           $pdf->setPaper('letter','portrait');
           return $pdf->stream("reservations_report.pdf");
        }
    }
}
