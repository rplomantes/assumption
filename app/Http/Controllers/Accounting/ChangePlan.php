<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;

class ChangePlan extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    //
    function index($idno){
        if(Auth::user()->accesslevel==env("ACCTNG_STAFF")){
        $student = \App\User::where('idno',$idno)->first();
        $status = \App\Status::where("idno",$idno)->first();
        $duedates =\App\LedgerDueDate::where("idno",$idno)->where("school_year",$status->school_year)->get();
        if($status->level == "Grade 11" || $status->level == "Grade 12"){
             $duedateplans=  \App\CtrDueDateBed::selectRaw('distinct plan')->where('academic_type',"SHS")->get();
        }
        else {   
             $duedateplans=  \App\CtrDueDateBed::selectRaw('distinct plan')->where('academic_type',"BED")->get();
        }
        return view('accounting.changeplan',compact('idno','student','bedlevel','duedates','status','duedateplans'));
    }
    }
    
    function post_plan(Request $request){
        $validation = $this->validate($request, [
            'plan' => 'required',
        ]);
        
        if($validation){
            DB::beginTransaction();
            $this->add_change_plan($request);
            $this->update_plan($request);
            $this->change_due_date($request);
            DB::Commit();
           return redirect(url('/cashier',array('viewledger',$request->idno))); 
        }
    }
    function add_change_plan($request){
        $originalplan = \App\Status::where('idno',$request->idno)->first()->type_of_plan;
        $changeplan = $request->plan;
        $orginalamount = \App\Ledger::where('idno',$request->idno)->where('category_switch',env("TUITION_FEE"))->first();
        $tuition = \App\CtrBedFee::where('level',$request->level)->where('category_switch',env("TUITION_FEE"))->first()->amount;
        $changeamount =$tuition+($tuition*($this->addPercentage($request->plan)/100));     
        $addchange = new \App\ChangePlan;
        $addchange->idno = $request->idno;
        $addchange->change_date = Date('Y-m-d');
        $addchange->original_plan = $originalplan;
        $addchange->change_plan = $changeplan;
        $addchange->original_amount = $orginalamount->amount;
        $addchange->change_amount = $this->roundOff($changeamount);
        $addchange->posted_by = Auth::user()->idno;
        $addchange->save();
    
        $orginalamount->amount=$this->roundOff($changeamount);
        $orginalamount->update();
    }

    function roundOff($amount) {
        return round($amount);
    }
    
    function update_plan($request){
        $status = \App\Status::where('idno',$request->idno)->first();
        $bedlevel = \App\BedLevel::where('idno',$request->idno)->where('school_year',$status->school_year)->where('period',$status->period)->first();
        $status->type_of_plan = $request->plan;
        $status->update();
        $bedlevel->type_of_plan = $request->plan;
        $bedlevel->update();
        
    }   
        
    function change_due_date($request){
        $stat = \App\Status::where('idno',$request->idno)->first();
        $schoolyear=$stat->school_year;
        $period=$stat->period;
        
        $deltedue=\App\LedgerDueDate::where('idno',$request->idno)->where('school_year',$schoolyear)->where('period',$period)->delete();
        $this->addDueDates($request, $schoolyear, $period);
    }
    
     function addPercentage($plan) {
        switch ($plan) {
            case "Plan A - Annual":
                return 0;
                break;
            case "Plan B - Semestral":
                return 1;
                break;
            case "Plan C - Quarterly":
                return 2;
                break;
            case "Plan D - Monthly":
                return 3;
                break;
        }
    }
    
    function addDueDates($request,$schoolyear,$period) {
        
        $total_decimal = 0;
        if ($request->plan == "Annual") {
            $total = \App\Ledger::selectRaw('idno, sum(amount)-sum(discount) as total')->where('idno', $request->idno)
                            ->where('category_switch', '<=', env("TUITION_FEE"))->groupBy('idno')->first();
            $addduedate = new \App\LedgerDueDate;
            $addduedate->idno = $request->idno;
            $addduedate->school_year = $schoolyear;
            if ($request->level == "Grade 11" || $request->level == "Grade 12") {
                $addduedate->period = $period;
            }
            $addduedate->due_date = Date('Y-m-d');
            $addduedate->due_switch = 0;
            $addduedate->amount = $total->total;
            $addduedate->save();
        } else {
            if ($request->level == "Grade 11" || $request->level == "Grade 12") {
                $duedates = \App\CtrDueDateBed::where('plan', $request->plan)->where('academic_type', 'SHS')->get();
            } else {
                $duedates = \App\CtrDueDateBed::where('plan', $request->plan)->where('academic_type', 'BED')->get();
            }
            $count = count($duedates) + 1;
            $duetuition = \App\Ledger::selectRaw('idno, sum(amount)-sum(discount) as total')->where('idno', $request->idno)
                            ->where('category_switch', env('TUITION_FEE'))->groupBy('idno')->first();
            $dueamount = $duetuition->total / $count;

            $dueothers = \App\Ledger::selectRaw('idno, sum(amount)-sum(discount) as total')->where('idno', $request->idno)
                            ->where('category_switch', '<', env("TUITION_FEE"))->groupBy('idno')->first();
            $addduedate = new \App\LedgerDueDate;
            $addduedate->idno = $request->idno;
            $addduedate->school_year = $schoolyear;
            if ($request->level == "Grade 11" || $request->level == "Grade 12") {
                $addduedate->period = $period;
            }

            $addduedate->due_date = Date('Y-m-d');
            $addduedate->due_switch = 0;
            $addduedate->amount = $dueothers->total;
            $addduedate->save();

            foreach ($duedates as $duedate) {
                $addduedate = new \App\LedgerDueDate;
                $addduedate->idno = $request->idno;
                $addduedate->school_year = $schoolyear;
                if ($request->level == "Grade 11" || $request->level == "Grade 12") {
                    $addduedate->period = $period;
                }
                $addduedate->due_date = $duedate->due_date;
                $addduedate->due_switch = 1;
                $plan_amount = floor($dueamount);
                $addduedate->amount = $plan_amount;
                $addduedate->save();
                $total_decimal = $total_decimal + ($dueamount-$plan_amount);
            }
             
             $this->update_due_dates($request, $dueamount, $total_decimal, $dueothers->total);
        }
    }
    function update_due_dates($request,$dueamount, $total_decimal, $dueothers){
        $update = \App\LedgerDueDate::where('idno',$request->idno)->where('due_switch', 0)->where('due_date', Date('Y-m-d'))->first();
        $update->amount = $dueothers + $dueamount + $total_decimal;
        $update->save();
    }
    
}
