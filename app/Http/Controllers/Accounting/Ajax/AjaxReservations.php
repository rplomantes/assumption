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
                    $period3 = "Summer";
                }else if($period == "2nd Semester"){
                $school_year2 = $school_year;
                    $period2 = "1st Semester";
                    $period3 = "1st Semester";
                }else if($period == "Summer"){
                $school_year2 = $school_year;
                    $period2 = "2nd Semester";
                    $period3 = "2nd Semester";
                }else{
                    $period2 = $period;
                    $period3 = $period;
                }
            }else{
                if($school_year == "2021"){
                    $school_year2 = "'2020-2021 - 2nd Semester'";
                }else if($school_year == "2020-2021 - 2nd Semester"){
                    return "Not yet available...";
                }else{
                    $school_year2 = $school_year - 1;
                }
            }
            
            if ($department == "Senior High School") {
                    $code_level = "(promotions.level = 'Grade 11' or promotions.level = 'Grade 12')";
                $lists = \App\Reservation::selectRaw("reservations.transaction_date,users.idno,users.lastname, users.firstname, users.middlename, promotions.level, payments.receipt_no,reservations.reference_id, reservations.amount, reservations.is_consumed")->where('reservation_type', 1)->where('reservations.is_reverse', 0)->leftJoin('payments', 'payments.reference_id','=','reservations.reference_id')->leftJoin('add_to_student_deposits', 'add_to_student_deposits.reference_id','=','reservations.reference_id')->whereRaw("(payments.school_year = $school_year2 or add_to_student_deposits.school_year = $school_year2 )")->whereRaw("(payments.period = '$period2' or payments.period = '$period3' or add_to_student_deposits.period = '$period2' or add_to_student_deposits.period = '$period3')")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->join('promotions','promotions.idno','=', 'reservations.idno')
                        ->whereRaw($code_level)
                        ->orderBy('users.lastname', 'asc')->get();
                $heads = \App\Reservation::selectRaw("promotions.level, sum(reservations.amount) as total")->where('reservation_type', 1)->where('reservations.is_reverse', 0)->leftJoin('add_to_student_deposits', 'add_to_student_deposits.reference_id','=','reservations.reference_id')->leftJoin('payments', 'payments.reference_id','=','reservations.reference_id')->whereRaw("(payments.school_year = $school_year2 or add_to_student_deposits.school_year = $school_year2 )")->whereRaw("(payments.period = '$period2' or payments.period = '$period3' or add_to_student_deposits.period = '$period2' or add_to_student_deposits.period = '$period3')")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->join('promotions','promotions.idno','=', 'reservations.idno')
                        ->whereRaw($code_level)
                        ->groupBy("statuses.level")->orderBy('statuses.level', 'asc')->get();
            } else if ($department == "College Department") {
                $lists = \App\Reservation::selectRaw("reservations.transaction_date,users.idno,users.lastname, users.firstname, users.middlename, statuses.level, payments.receipt_no,reservations.reference_id, reservations.amount, statuses.program_code, reservations.is_consumed")->where('reservation_type', 1)->where('reservations.is_reverse', 0)->leftJoin('payments', 'payments.reference_id','=','reservations.reference_id')->leftJoin('add_to_student_deposits', 'add_to_student_deposits.reference_id','=','reservations.reference_id')->whereRaw("(payments.school_year = $school_year2 or add_to_student_deposits.school_year = $school_year2 )")->whereRaw("(payments.period = '$period2' or payments.period = '$period3' or add_to_student_deposits.period = '$period2' or add_to_student_deposits.period = '$period3')")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.academic_type',"College")->orderBy('users.lastname', 'asc')->get();
                $heads = \App\Reservation::selectRaw("statuses.level, sum(reservations.amount) as total")->where('reservation_type', 1)->where('reservations.is_reverse', 0)->leftJoin('payments', 'payments.reference_id','=','reservations.reference_id')->leftJoin('add_to_student_deposits', 'add_to_student_deposits.reference_id','=','reservations.reference_id')->whereRaw("(payments.school_year = $school_year2 or add_to_student_deposits.school_year = $school_year2 )")->whereRaw("(payments.period = '$period2' or payments.period = '$period3' or add_to_student_deposits.period = '$period2' or add_to_student_deposits.period = '$period3')")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.academic_type',"College")->groupBy("statuses.level")->orderBy('statuses.level', 'asc')->get();
            } else {
                $dep = $department;
                if($dep == "Pre School"){
                    $code_level = "(promotions.level = 'Pre-Kinder' or promotions.level = 'Kinder')";
                }elseif($dep == "Elementary"){
                    $code_level = "(promotions.level = 'Grade 1' or promotions.level = 'Grade 2' or promotions.level = 'Grade 3' or promotions.level = 'Grade 4' or promotions.level = 'Grade 5' or promotions.level = 'Grade 6')";
                }elseif($dep == "Junior High School"){
                    $code_level = "(promotions.level = 'Grade 7' or promotions.level = 'Grade 8' or promotions.level = 'Grade 9' or promotions.level = 'Grade 10')";
                }
                $lists = \App\Reservation::selectRaw("reservations.transaction_date,users.idno,users.lastname, users.firstname, users.middlename, promotions.level, payments.receipt_no,reservations.reference_id, reservations.amount, reservations.is_consumed")->where('reservation_type', 1)->where('reservations.is_reverse', 0)->leftJoin('payments', 'payments.reference_id','=','reservations.reference_id')->leftJoin('add_to_student_deposits', 'add_to_student_deposits.reference_id','=','reservations.reference_id')->whereRaw("(payments.school_year = $school_year2 or add_to_student_deposits.school_year = $school_year2 )")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->join('promotions','promotions.idno','=', 'reservations.idno')
                        ->whereRaw($code_level)
                        ->orderBy('users.lastname', 'asc')->orderBy('statuses.level', 'asc')->get();
                $heads = \App\Reservation::selectRaw("promotions.level, sum(reservations.amount) as total")->where('reservation_type', 1)->where('reservations.is_reverse', 0)->leftJoin('add_to_student_deposits', 'add_to_student_deposits.reference_id','=','reservations.reference_id')->leftJoin('payments', 'payments.reference_id','=','reservations.reference_id')->whereRaw("(payments.school_year = $school_year2 or add_to_student_deposits.school_year = $school_year2 )")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->join('promotions','promotions.idno','=', 'reservations.idno')
                        ->whereRaw($code_level)
                        ->groupBy("promotions.level")->orderBy('promotions.level', 'asc')->get();
            }
            if($school_year == 2018){
                if ($department == "Senior High School") {
                    $lists = \App\Reservation::where('reservation_type', 1)->where('reservations.is_reverse', 0)->where('reference_id', "")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.academic_type',"SHS")->orderBy('users.lastname', 'asc')->get();
                    $heads = \App\Reservation::selectRaw("statuses.level, sum(reservations.amount) as total")->where('reservation_type', 1)->where('reservations.is_reverse', 0)->where('reference_id', "")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.academic_type',"SHS")->orderBy('users.lastname', 'asc')->groupBy("statuses.level")->orderBy('statuses.level', 'asc')->get();
                } else if ($department == "College Department") {
                    $lists = \App\Reservation::where('reservation_type', 1)->where('reservations.is_reverse', 0)->where('reference_id', "")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.academic_type',"College")->orderBy('users.lastname', 'asc')->get();
                    $heads = \App\Reservation::selectRaw("statuses.level, sum(reservations.amount) as total")->where('reservation_type', 1)->where('reservations.is_reverse', 0)->where('reference_id', "")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.academic_type',"College")->orderBy('users.lastname', 'asc')->groupBy("statuses.level")->orderBy('statuses.level', 'asc')->get();
                } else {
                    $dep = $department;
                    $lists = \App\Reservation::where('reservation_type', 1)->where('reservations.is_reverse', 0)->where('reference_id', "")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.department',$dep)->orderBy('users.lastname', 'asc')->get();
                    $heads = \App\Reservation::selectRaw("statuses.level, sum(reservations.amount) as total")->where('reservation_type', 1)->where('reservations.is_reverse', 0)->where('reference_id', "")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.department',$dep)->groupBy("statuses.level")->orderBy('statuses.level', 'asc')->get();
                }
            }
            return view('accounting.ajax.get_display_reservations', compact('department','school_year','period','lists','heads'));
        }
    }

    function get_deposits() {
        if (Request::ajax()) {
            $dep = "";
            $department = Input::get('department');
            $school_year = Input::get("school_year");
            $period = Input::get("period");
            
            if($department == "Senior High School" || $department == "College Department"){
                if($period == "1st Semester"){
                $school_year2 = $school_year - 1;
                    $period2 = "2nd Semester";
                    $period3 = "Summer";
                }else if($period == "2nd Semester"){
                $school_year2 = $school_year;
                    $period2 = "1st Semester";
                    $period3 = "1st Semester";
                }else if($period == "Summer"){
                $school_year2 = $school_year;
                    $period2 = "2nd Semester";
                    $period3 = "2nd Semester";
                }else{
                    $period2 = $period;
                    $period3 = $period;
                }
            }else{
                $school_year2 = $school_year - 1;
            }
            
            if ($department == "Senior High School") {
                    $code_level = "(promotions.level = 'Grade 11' or promotions.level = 'Grade 12')";
                $lists = \App\Reservation::selectRaw("reservations.transaction_date,users.idno,users.lastname, users.firstname, users.middlename, promotions.level, payments.receipt_no,reservations.reference_id, reservations.amount, reservations.is_consumed")->where('reservation_type', 2)->where('reservations.is_reverse', 0)->leftJoin('payments', 'payments.reference_id','=','reservations.reference_id')->leftJoin('add_to_student_deposits', 'add_to_student_deposits.reference_id','=','reservations.reference_id')->whereRaw("(payments.school_year = $school_year2 or add_to_student_deposits.school_year = $school_year2 )")->whereRaw("(payments.period = '$period2' or payments.period = '$period3' or add_to_student_deposits.period = '$period2' or add_to_student_deposits.period = '$period3')")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->join('promotions','promotions.idno','=','users.idno')->where('reservations.is_consumed',0)
                        ->whereRaw($code_level)
                        ->orderBy('users.lastname', 'asc')->get();
                $heads = \App\Reservation::selectRaw("promotions.level, sum(reservations.amount) as total")->where('reservation_type', 2)->where('reservations.is_reverse', 0)->leftJoin('add_to_student_deposits', 'add_to_student_deposits.reference_id','=','reservations.reference_id')->leftJoin('payments', 'payments.reference_id','=','reservations.reference_id')->whereRaw("(payments.school_year = $school_year2 or add_to_student_deposits.school_year = $school_year2 )")->whereRaw("(payments.period = '$period2' or payments.period = '$period3' or add_to_student_deposits.period = '$period2' or add_to_student_deposits.period = '$period3')")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->join('promotions','promotions.idno','=','users.idno')->where('reservations.is_consumed',0)
                        ->whereRaw($code_level)
                        ->groupBy("statuses.level")->orderBy('statuses.level', 'asc')->get();
            } else if ($department == "College Department") {
                $lists = \App\Reservation::selectRaw("reservations.transaction_date,users.idno,users.lastname, users.firstname, users.middlename, statuses.level, payments.receipt_no,reservations.reference_id, reservations.amount, statuses.program_code, reservations.is_consumed")->where('reservation_type', 2)->where('reservations.is_reverse', 0)->leftJoin('payments', 'payments.reference_id','=','reservations.reference_id')->leftJoin('add_to_student_deposits', 'add_to_student_deposits.reference_id','=','reservations.reference_id')->whereRaw("(payments.school_year = $school_year2 or add_to_student_deposits.school_year = $school_year2 )")->whereRaw("(payments.period = '$period2' or payments.period = '$period3' or add_to_student_deposits.period = '$period2' or add_to_student_deposits.period = '$period3')")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('reservations.is_consumed',0)->where('statuses.academic_type',"College")->orderBy('users.lastname', 'asc')->get();
                $heads = \App\Reservation::selectRaw("statuses.level, sum(reservations.amount) as total")->where('reservation_type', 2)->where('reservations.is_reverse', 0)->leftJoin('payments', 'payments.reference_id','=','reservations.reference_id')->leftJoin('add_to_student_deposits', 'add_to_student_deposits.reference_id','=','reservations.reference_id')->whereRaw("(payments.school_year = $school_year2 or add_to_student_deposits.school_year = $school_year2 )")->whereRaw("(payments.period = '$period2' or payments.period = '$period3' or add_to_student_deposits.period = '$period2' or add_to_student_deposits.period = '$period3')")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.academic_type',"College")->where('reservations.is_consumed',0)->groupBy("statuses.level")->orderBy('statuses.level', 'asc')->get();
            } else {
                $dep = $department;
                if($dep == "Pre School"){
                    $code_level = "(promotions.level = 'Pre-Kinder' or promotions.level = 'Kinder')";
                }elseif($dep == "Elementary"){
                    $code_level = "(promotions.level = 'Grade 1' or promotions.level = 'Grade 2' or promotions.level = 'Grade 3' or promotions.level = 'Grade 4' or promotions.level = 'Grade 5' or promotions.level = 'Grade 6')";
                }elseif($dep == "Junior High School"){
                    $code_level = "(promotions.level = 'Grade 7' or promotions.level = 'Grade 8' or promotions.level = 'Grade 9' or promotions.level = 'Grade 10')";
                }
                $lists = \App\Reservation::selectRaw("reservations.transaction_date,users.idno,users.lastname, users.firstname, users.middlename, promotions.level, payments.receipt_no,reservations.reference_id, reservations.amount, reservations.is_consumed")->where('reservation_type', 2)->where('reservations.is_reverse', 0)->leftJoin('payments', 'payments.reference_id','=','reservations.reference_id')->leftJoin('add_to_student_deposits', 'add_to_student_deposits.reference_id','=','reservations.reference_id')->whereRaw("(payments.school_year = $school_year2 or add_to_student_deposits.school_year = $school_year2 )")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->join('promotions','promotions.idno','=','users.idno')->where('reservations.is_consumed',0)
                        ->whereRaw($code_level)
                        ->orderBy('users.lastname', 'asc')->orderBy('statuses.level', 'asc')->get();
                $heads = \App\Reservation::selectRaw("promotions.level, sum(reservations.amount) as total")->where('reservation_type', 2)->where('reservations.is_reverse', 0)->leftJoin('add_to_student_deposits', 'add_to_student_deposits.reference_id','=','reservations.reference_id')->leftJoin('payments', 'payments.reference_id','=','reservations.reference_id')->whereRaw("(payments.school_year = $school_year2 or add_to_student_deposits.school_year = $school_year2 )")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->join('promotions','promotions.idno','=','users.idno')->where('reservations.is_consumed',0)
                        ->whereRaw($code_level)
                        ->groupBy("promotions.level")->orderBy('promotions.level', 'asc')->get();
            }
            if($school_year == 2018){
                if ($department == "Senior High School") {
                    $lists = \App\Reservation::where('reservation_type', 2)->where('reservations.is_reverse', 0)->where('reference_id', "")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.academic_type',"SHS")->orderBy('users.lastname', 'asc')->get();
                    $heads = \App\Reservation::selectRaw("statuses.level, sum(reservations.amount) as total")->where('reservation_type', 2)->where('reservations.is_reverse', 0)->where('reference_id', "")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.academic_type',"SHS")->orderBy('users.lastname', 'asc')->groupBy("statuses.level")->orderBy('statuses.level', 'asc')->get();
                } else if ($department == "College Department") {
                    $lists = \App\Reservation::where('reservation_type', 2)->where('reservations.is_reverse', 0)->where('reference_id', "")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.academic_type',"College")->orderBy('users.lastname', 'asc')->get();
                    $heads = \App\Reservation::selectRaw("statuses.level, sum(reservations.amount) as total")->where('reservation_type', 2)->where('reservations.is_reverse', 0)->where('reference_id', "")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.academic_type',"College")->orderBy('users.lastname', 'asc')->groupBy("statuses.level")->orderBy('statuses.level', 'asc')->get();
                } else {
                    $dep = $department;
                    $lists = \App\Reservation::where('reservation_type', 2)->where('reservations.is_reverse', 0)->where('reference_id', "")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.department',$dep)->orderBy('users.lastname', 'asc')->get();
                    $heads = \App\Reservation::selectRaw("statuses.level, sum(reservations.amount) as total")->where('reservation_type', 2)->where('reservations.is_reverse', 0)->where('reference_id', "")->join('users', 'users.idno','=', 'reservations.idno')->join('statuses','statuses.idno','=','reservations.idno')->where('statuses.department',$dep)->groupBy("statuses.level")->orderBy('statuses.level', 'asc')->get();
                }
            }
            return view('accounting.ajax.get_display_deposits', compact('department','school_year','period','lists','heads'));
        }
    }
}
