<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use PDF;
use Excel;

class Reservations extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        if (Auth::user()->accesslevel == env("ACCTNG_STAFF") || Auth::user()->accesslevel == env("ACCTNG_HEAD")) {
            return view('accounting.reservations');
        }
    }
    
    function print_reservationsPDF(Request $request){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {

            $dep = "";
            $department = $request->department;
            $school_year = $request->school_year;
            $school_year2 = $school_year - 1;
            $period = $request->period;
            if($period == "1st Semester"){
                $period2 = "2nd Semester";
            }else if($period == "2nd Semester"){
                $period2 = "1st Semester";
            }else if($period == "Summer"){
                $period2 = "2nd Semester";
            }else{
                $period2 = $period;
            }
            if ($department == "Senior High School") {
                $lists = \App\Reservation::where('reservation_type', 1)->where('reservations.is_reverse', 0)->where('is_consumed', 0)->join('payments', 'payments.reference_id','=','reservations.reference_id')->where('payments.school_year', $school_year2)->where('payments.period', $period2)->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.academic_type',"Senior High School")->orderBy('users.lastname', 'asc')->get();
            } else if ($department == "College Department") {
                $lists = \App\Reservation::where('reservation_type', 1)->where('reservations.is_reverse', 0)->where('is_consumed', 0)->join('payments', 'payments.reference_id','=','reservations.reference_id')->where('payments.school_year', $school_year2)->where('payments.period', $period2)->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.academic_type',"College")->orderBy('users.lastname', 'asc')->get();
            } else {
                $dep = $department;
                $lists = \App\Reservation::where('reservation_type', 1)->where('reservations.is_reverse', 0)->where('is_consumed', 0)->join('payments', 'payments.reference_id','=','reservations.reference_id')->where('payments.school_year', $school_year2)->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.department',$dep)->orderBy('users.lastname', 'asc')->get();
            }
            if($school_year == 2018){
                if ($department == "Senior High School") {
                    $lists = \App\Reservation::where('reservation_type', 1)->where('reservations.is_reverse', 0)->where('is_consumed', 0)->where('reference_id', "")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.academic_type',"Senior High School")->orderBy('users.lastname', 'asc')->get();
                } else if ($department == "College Department") {
                    $lists = \App\Reservation::where('reservation_type', 1)->where('reservations.is_reverse', 0)->where('is_consumed', 0)->where('reference_id', "")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.academic_type',"College")->orderBy('users.lastname', 'asc')->get();
                } else {
                    $dep = $department;
                    $lists = \App\Reservation::where('reservation_type', 1)->where('reservations.is_reverse', 0)->where('is_consumed', 0)->where('reference_id', "")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.department',$dep)->orderBy('users.lastname', 'asc')->get();
                }
            }
            
             \App\Http\Controllers\Admin\Logs::log("Print Unused Reservations for $school_year $period PDF");
            $pdf = PDF::loadView('accounting.print_reservations_pdf', compact('department','school_year','period','lists'));
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream("unused_reservations.pdf");
        }
        
    }
    
    function print_reservationsEXCEL(Request $request){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            $dep = "";
            $department = $request->department;
            $school_year = $request->school_year;
            $school_year2 = $school_year - 1;
            $period = $request->period;
            if($period == "1st Semester"){
                $period2 = "2nd Semester";
            }else if($period == "2nd Semester"){
                $period2 = "1st Semester";
            }else if($period == "Summer"){
                $period2 = "2nd Semester";
            }else{
                $period2 = $period;
            }
            if ($department == "Senior High School") {
                $lists = \App\Reservation::where('reservation_type', 1)->where('reservations.is_reverse', 0)->where('is_consumed', 0)->join('payments', 'payments.reference_id','=','reservations.reference_id')->where('payments.school_year', $school_year2)->where('payments.period', $period2)->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.academic_type',"Senior High School")->orderBy('users.lastname', 'asc')->get();
            } else if ($department == "College Department") {
                $lists = \App\Reservation::where('reservation_type', 1)->where('reservations.is_reverse', 0)->where('is_consumed', 0)->join('payments', 'payments.reference_id','=','reservations.reference_id')->where('payments.school_year', $school_year2)->where('payments.period', $period2)->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.academic_type',"College")->orderBy('users.lastname', 'asc')->get();
            } else {
                $dep = $department;
                $lists = \App\Reservation::where('reservation_type', 1)->where('reservations.is_reverse', 0)->where('is_consumed', 0)->join('payments', 'payments.reference_id','=','reservations.reference_id')->where('payments.school_year', $school_year2)->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.department',$dep)->orderBy('users.lastname', 'asc')->get();
            }
            if($school_year == 2018){
                if ($department == "Senior High School") {
                    $lists = \App\Reservation::where('reservation_type', 1)->where('reservations.is_reverse', 0)->where('is_consumed', 0)->where('reference_id', "")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.academic_type',"Senior High School")->orderBy('users.lastname', 'asc')->get();
                } else if ($department == "College Department") {
                    $lists = \App\Reservation::where('reservation_type', 1)->where('reservations.is_reverse', 0)->where('is_consumed', 0)->where('reference_id', "")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.academic_type',"College")->orderBy('users.lastname', 'asc')->get();
                } else {
                    $dep = $department;
                    $lists = \App\Reservation::where('reservation_type', 1)->where('reservations.is_reverse', 0)->where('is_consumed', 0)->where('reference_id', "")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.department',$dep)->orderBy('users.lastname', 'asc')->get();
                }
            }
            
             \App\Http\Controllers\Admin\Logs::log("Download Unused Reservations for $school_year $period Excel");
            
            ob_end_clean();
            Excel::create('Unused Reservations', 
                function($excel) use ($department,$school_year,$period,$lists) { $excel->setTitle($department);
                    $excel->sheet($department, function ($sheet) use ($department,$school_year,$period, $lists) {
                    $sheet->loadView('accounting.print_reservations_excel', compact('department','school_year','period','lists'));
                    });
                })->download('xlsx');
            
        }
        
    }
    
    function getReservations($idno, $lastname,$firstname,$middlename, $level, $transaction_date, $amount){
        $array2 = array();
        $array2['idno'] = $idno;
        $array2['lastname'] = $lastname;
        $array2['firstname'] = $firstname;
        $array2['middlename'] = $middlename;
        $array2['level'] = $level;
        $array2['transaction_date'] = $transaction_date;
        $array2['amount'] = $amount;
        
        return $array2;
    }

    function index2() {
        if (Auth::user()->accesslevel == env("ACCTNG_STAFF") || Auth::user()->accesslevel == env("ACCTNG_HEAD")) {
            $reservation = \App\Reservation::where('reservation_type', 1)->where('is_consumed', 0)->join('users', 'users.idno', '=', 'reservations.idno')->join('statuses', 'statuses.idno', '=', 'reservations.idno')->orderBy('statuses.level', 'asc')->orderBy('users.lastname', 'asc')->orderBy('reservations.transaction_date', 'asc')->get();
            
            $reservations = collect();
            foreach ($reservation as $reserve){
                $reservations->push($this->getReservations($reserve->idno, $reserve->lastname, $reserve->firstname, $reserve->middlename, $reserve->level, $reserve->transaction_date, $reserve->amount));
            }
            
            \App\Http\Controllers\Admin\Logs::log("Print reservation report");
            $pdf = PDF::loadView('accounting.print_reservations', compact('reservations'));
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream("reservations_report.pdf");
        }
    }

}
