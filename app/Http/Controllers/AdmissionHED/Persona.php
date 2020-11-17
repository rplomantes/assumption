<?php

namespace App\Http\Controllers\AdmissionHED;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Session;
use Mail;
use PDF;

class Persona extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function report() {
        if (Auth::user()->accesslevel == env('ADMISSION_HED')) {
            $school_years = \App\AdmissionHed::distinct()->orderBy('applying_for_sy','desc')->get(['applying_for_sy']);

            return view('admission-hed.persona_report',compact('school_years'));
        }
    }

}
