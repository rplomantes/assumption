<?php

namespace App\Http\Controllers\BedRegistrar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use PDF;
use Auth;

class TotalDailyEnrollment extends Controller
{
    public function __contruct(){
        $this->middleware('auth');
    }

    function index($date_start, $date_end){
        if(Auth::user()->accesslevel == env('REG_BE')){
                $students = \App\BedLevel::where('bed_levels.status', 3)
                        ->whereBetween('bed_levels.date_enrolled', array($date_start,$date_end))
                        ->join('users', 'bed_levels.idno', '=', 'users.idno')
                        ->orderBy('users.lastname','ASC')
                        ->get();
            return view('reg_be.total_daily_enrollment', compact('date_start','date_end','students'));
        }    
    }
    
    function print_daily_enrollment($date_start, $date_end){
        if (Auth::user()->accesslevel == env('REG_BE')) {
                $students = \App\BedLevel::where('bed_levels.status', 3)
                        ->whereBetween('bed_levels.date_enrolled', array($date_start,$date_end))
                        ->join('users', 'bed_levels.idno', '=', 'users.idno')
                        ->orderBy('users.lastname','ASC')
                        ->get();
            $pdf = PDF::loadView('reg_be.print_total_daily_enrollment', compact('date_start','date_end','students'));
            $pdf->setPaper(array(0, 0, 612.00, 792.0));
            return $pdf->stream("student_list_.pdf");
        }            
    }
}
