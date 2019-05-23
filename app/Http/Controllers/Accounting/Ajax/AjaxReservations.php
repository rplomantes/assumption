<?php

namespace App\Http\Controllers\Accounting\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use DB;

class AjaxReservations extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    function get_reservations() {
        if (Request::ajax()) {
            $dep = "";
            $department = Input::get('department');
            $school_year = Input::get("school_year");
            $period = Input::get("period");
            
            if($department == "Senior High School" || $department == "College Department"){
                if($period == "1st Semester"){
                $school_year2 = $school_year - 1;
                    $period2 = "2nd Semester";
                }else if($period == "2nd Semester"){
                $school_year2 = $school_year;
                    $period2 = "1st Semester";
                }else if($period == "Summer"){
                $school_year2 = $school_year;
                    $period2 = "2nd Semester";
                }else{
                    $period2 = $period;
                }
            }else{
                $school_year2 = $school_year - 1;
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
            return view('accounting.ajax.get_display_reservations', compact('department','school_year','period','lists'));
        }
    }
}
