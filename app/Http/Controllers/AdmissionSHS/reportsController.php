<?php

namespace App\Http\Controllers\AdmissionBED;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;
use Session;

class reportsController extends Controller
{
    //

    public function __construct() {
        $this->middleware('auth');
    }

    function pre_registered($date_start, $date_end) {
        if (Auth::user()->accesslevel == env("ADMISSION_BED")) {
            $non_paid = \Illuminate\Support\Facades\DB::connection('mysql2')->select("select * from pre_registrations where is_complete = 0 and created_at >= '$date_start 00:00:00' and  created_at <= '$date_end 23:59:59' and applying_for = 'Basic Education'");
            $paid = \Illuminate\Support\Facades\DB::connection('mysql2')->select("select * from pre_registrations where is_complete = 1 and date_completed >= '$date_start' and  date_completed <= '$date_end' and applying_for = 'Basic Education'");
            
            return view('admission-bed.reports.pre_registered', compact('date_start','date_end', 'non_paid', 'paid'));
        }
    }

    function for_approval($date_start, $date_end) {
        if (Auth::user()->accesslevel == env("ADMISSION_BED")) {
            $for_approval_sched = \App\Status::where('statuses.status', env('FOR_APPROVAL'))->where('date_application_finish', '>=', $date_start)->where('date_application_finish', '<=', $date_end)->join('users', 'users.idno','=','statuses.idno')->orderBy('users.lastname')->get(['users.idno', 'users.firstname','users.lastname','users.middlename','statuses.level']);

            return view('admission-bed.reports.for_approval', compact('date_start','date_end', 'for_approval_sched'));
        }
    }

    function approved($date_start, $date_end) {
        if (Auth::user()->accesslevel == env("ADMISSION_BED")) {
            $for_approval_sched = \App\Status::where('statuses.status', 0)->where('date_admission_finish', '>=', $date_start)->where('date_admission_finish', '<=', $date_end)->join('users', 'users.idno','=','statuses.idno')->orderBy('users.lastname')->get(['users.idno', 'users.firstname','users.lastname','users.middlename','statuses.level','date_admission_finish']);

            return view('admission-bed.reports.approved', compact('date_start','date_end', 'for_approval_sched'));
        }
    }

    function regrets($date_start, $date_end) {
        if (Auth::user()->accesslevel == env("ADMISSION_BED")) {
            $regret_finals = \App\Status::where('statuses.status', env('REGRET_FINAL'))->where('date_admission_finish', '>=', $date_start)->where('date_admission_finish', '<=', $date_end)->join('users', 'users.idno','=','statuses.idno')->orderBy('users.lastname')->get(['users.idno', 'users.firstname','users.lastname','users.middlename','statuses.level','date_admission_finish']);
            $regret_retreives = \App\Status::where('statuses.status', env('REGRET_RETREIVE'))->where('date_admission_finish', '>=', $date_start)->where('date_admission_finish', '<=', $date_end)->join('users', 'users.idno','=','statuses.idno')->orderBy('users.lastname')->get(['users.idno', 'users.firstname','users.lastname','users.middlename','statuses.level','date_admission_finish']);

            return view('admission-bed.reports.regrets', compact('date_start','date_end', 'regret_finals', 'regret_retreives'));
        }
    }

    function reservations() {
        if (Auth::user()->accesslevel == env("ADMISSION_BED")) {
//           $reservations = \App\Reservation::where('reservation_type', 1)
//                   ->where('is_consumed', 0)
//                   ->where('is_reverse', 0)
//                   ->join('statuses', 'statuses.idno','=','reservations.idno')
//                   ->join('users', 'users.idno','=','reservations.idno')
//                   ->where('statuses.academic_type', '!=', 'College')
//                   ->where('is_new', '1')
//                   ->where('date_admission_finish', '!=', NULL)
//                   ->orderBy('users.lastname', 'asc')
//                   ->orderBy('reservations.transaction_date', 'asc')
//                   ->get();
           
           $reservations = \App\Reservation::where('reservation_type', 1)
                   ->where('is_consumed', 0)
                   ->where('is_reverse', 0)
                   ->join('statuses', 'statuses.idno', '=', 'reservations.idno')
                   ->join('users', 'users.idno','=','reservations.idno')
                   ->where('statuses.academic_type', '!=', 'College')
                   ->where('is_new', '1')
                   ->where('date_admission_finish', '!=', NULL)
                   ->orderBy('statuses.level', 'asc')
                   ->orderBy('users.lastname', 'asc')
                   ->orderBy('reservations.transaction_date', 'asc')
                   ->get();
           
            return view('admission-bed.reports.reservations', compact('reservations'));
        }
    }
}
