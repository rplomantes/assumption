<?php

namespace App\Http\Controllers\Accounting\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            $level = Input::get("level");
            $plan = Input::get("plan");
            $dates = Input::get('dates');
            $unpaid = DB::select("SELECT * FROM statuses s,users u WHERE s.level = '".$level."' AND s.idno = u.idno AND s.type_of_plan = '".$plan."' AND s.status = '".env('ENROLLED')."' ORDER BY u.lastname ASC");
            return view('accounting.ajax.display_unpaid', compact('unpaid', 'level', 'plan', 'dates'));
        }
      }
    }
    
      function reversePost($idno) {
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
                $level = Input::get('level');
                $plan = Input::get('plan');
                $dates = Input::get('dates');
                
                DB::beginTransaction();
                $posted = \App\PostedCharges::where('idno',$idno)->where('due_date',$dates)->where('is_reversed',0)->first();
                $posted->is_reversed = 1;
                $dateposted = $posted->date_posted;
                $posted->update();
                
                $delLed = \App\Ledger::where('idno',$idno)->where('subsidiary','Late Payment Charge')->where('created_at','LIKE',$dateposted . '%')->where('payment',0)->first();
                $delLed->delete();
                DB::commit();
                
                $unpaid = DB::select("SELECT * FROM statuses s,users u WHERE s.level = '".$level."' AND s.idno = u.idno AND s.type_of_plan = '".$plan."' AND s.status = '".env('ENROLLED')."' ORDER BY u.lastname ASC");
                return view('accounting.ajax.display_unpaid', compact('unpaid', 'level', 'plan', 'dates'));
            }
    }

}
