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

    function statisticsReport() {
        if (Auth::user()->accesslevel == env('ADMISSION_HED')) {
            $school_years = \App\AdmissionHed::distinct()->orderBy('applying_for_sy','desc')->get(['applying_for_sy']);

            return view('admission-hed.persona_statistics_report',compact('school_years'));
        }
    }

    function report($school_year=null) {
        if (Auth::user()->accesslevel == env('ADMISSION_HED')) {
            $school_years = \App\AdmissionHed::distinct()->orderBy('applying_for_sy','desc')->get(['applying_for_sy']);
            if($school_year != null){
                $applicants = \App\CollegeAboutYou::join('admission_heds', 'admission_heds.idno','college_about_yous.idno')
                        ->join('student_infos','student_infos.idno','college_about_yous.idno')
                        ->where('admission_heds.applying_for_sy',$school_year)->get();
            }

            return view('admission-hed.persona_report',compact('school_years','school_year','applicants'));
        }
    }

}
