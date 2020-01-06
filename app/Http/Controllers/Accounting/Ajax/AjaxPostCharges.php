<?php

namespace App\Http\Controllers\Accounting\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon;

class AjaxPostCharges extends Controller {

    function getDueDates() {
        if (Request::ajax()) {
         if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            $level = Input::get("level");
            $plan = Input::get("plan");
            $acadType = "";
            if ($level == "Grade 11" || $level == "Grade 12") {
                $acadType = "SHS";
            } else {
                $acadType = "BED";
            }
            $dues = \App\CtrDueDateBed::where('academic_type', $acadType)->where('plan', $plan)->get();
//            $dues = DB::select("SELECT *, MONTH(due_date) as 'month' FROM ctr_due_date_beds WHERE academic_type = '".$acadType."' AND plan ='".$plan."'");
            $data = "<option></option>";
            foreach ($dues as $due) {
                $due_date = date_create($due->due_date);
                $month = date_format(date_create($due->due_date),'m');
                $data = $data . "<option value='" . $month . "'>" . date_format($due_date, "F Y") . "</option>";
            }
            return $data;
        }
        }
    }

    function getUnpaid() {
        if (Request::ajax()) {
         if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            
            $dateToday = Carbon\Carbon::now();
            $dates = sprintf("%02d",date_format($dateToday,'m') - 1);
            $dates2 = date_format($dateToday,"Y-'$dates'-31");
            if($dates == 0 ){
                $dates = 12;
                $dates2 = date_format($dateToday,"Y-'$dates'-31");
            }
            $unpaid = DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.program_code,s.level,s.section, s.type_of_plan,l.balance FROM users u, statuses s, (SELECT idno, (sum(amount) - (sum(debit_memo) + sum(discount))) - sum(payment) as 'balance' FROM `ledgers` GROUP BY school_year,idno) l WHERE l.balance != 0.00 and u.idno = s.idno and u.idno = l.idno and s.department NOT LIKE '%Department' AND s.status = '".env('ENROLLED')."' ORDER BY u.lastname,s.program_code,s.level,s.section");
             
//            $level = Input::get("level");
//            $plan = Input::get("plan");
//            $dates = Input::get('dates');
//            $unpaid = DB::select("SELECT * FROM statuses s,users u WHERE s.level = '".$level."' AND s.idno = u.idno AND s.type_of_plan = '".$plan."' AND s.status = '".env('ENROLLED')."' ORDER BY u.lastname ASC");
            return view('accounting.ajax.display_unpaid', compact('unpaid', 'dates'));
        }
      }
    }
    
      function reversePost($idno) {
        $academic_type = \App\Status::where('idno', $idno)->first();
        $school_year = \App\CtrAcademicSchoolYear::where('academic_type', $academic_type->academic_type)->first();
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
                $dateToday = Carbon\Carbon::now();
                $dates = sprintf("%02d",date_format($dateToday,'m') - 1);
                $dates2 = date_format($dateToday,"Y-'$dates'-31");
                if($dates == 0 ){
                    $dates = 12;
                    $dates2 = date_format($dateToday,"Y-'$dates'-31");
                }
                
                DB::beginTransaction();
                $dateToday = Carbon\Carbon::now();
                $years2 = sprintf("%02d",date_format($dateToday,'Y'));
                $posted = \App\PostedCharges::where('idno',$idno)->where('due_date',$dates)->where('is_reversed',0)->whereRaw("YEAR(date_posted) = '$years2'")->first();
                $posted->is_reversed = 1;
                $dateposted = $posted->date_posted;
                $posted->update();
                
                $delLed = \App\Ledger::where('idno',$idno)->where('subsidiary','Late Payment Charge')->where('created_at','LIKE',$dateposted . '%')->where('payment',0)->first();
                $delLed->delete();
                DB::commit();
                
            $unpaid = DB::select("SELECT u.idno,u.lastname,u.middlename,u.firstname,u.extensionname,s.program_code,s.level,s.section, s.type_of_plan,l.balance FROM users u, statuses s, (SELECT idno, (sum(amount) - (sum(debit_memo) + sum(discount))) - sum(payment) as 'balance' FROM `ledgers` GROUP BY school_year,idno) l WHERE l.balance != 0.00 and u.idno = s.idno and u.idno = l.idno and s.department NOT LIKE '%Department' AND s.status = '".env('ENROLLED')."' ORDER BY u.lastname,s.program_code,s.level,s.section");
            
                return view('accounting.ajax.display_unpaid', compact('unpaid','dates'));
            }
    }

}
