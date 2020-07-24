<?php

namespace App\Http\Controllers\AdmissionHED;

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
        if (Auth::user()->accesslevel == env("ADMISSION_HED")) {
            
            $non_paid = \App\Status::where("statuses.created_at",'>=', "$date_start 00:00:00")->where('statuses.created_at','<=', "$date_end 23:59:59")->join('student_infos', 'student_infos.idno', 'statuses.idno')->join('admission_heds', 'admission_heds.idno', 'statuses.idno')->join('users', 'users.idno', 'statuses.idno')->get();
            return view('admission-hed.pre_registered', compact('date_start','date_end', 'non_paid', 'paid'));
        }
    }
}
