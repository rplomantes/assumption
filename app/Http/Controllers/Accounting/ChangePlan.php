<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;

class ChangePlan extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    //
    function index($idno) {
        if (Auth::user()->accesslevel == env("ACCTNG_STAFF") || Auth::user()->accesslevel==env("ACCTNG_HEAD")) {
            $student = \App\User::where('idno', $idno)->first();
            $status = \App\Status::where("idno", $idno)->first();
            if ($status->academic_type == "College") {
                $duedates = \App\LedgerDueDate::where("idno", $idno)->where("school_year", $status->school_year)->where('period', $status->period)->get();
                $duedateplans = \App\CtrDueDate::selectRaw('distinct plan')->where('academic_type', "College")->get();
            } else {
                if ($status->level == "Grade 11" || $status->level == "Grade 12") {
                    $duedates = \App\LedgerDueDate::where("idno", $idno)->where("school_year", $status->school_year)->where('period', $status->period)->get();
                    $duedateplans = \App\CtrDueDateBed::selectRaw('distinct plan')->where('academic_type', "SHS")->get();
                } else {
                $duedates = \App\LedgerDueDate::where("idno", $idno)->where("school_year", $status->school_year)->get();
                    $duedateplans = \App\CtrDueDateBed::selectRaw('distinct plan')->where('academic_type', "BED")->get();
                }
            }
            return view('accounting.changeplan', compact('idno', 'student', 'bedlevel', 'duedates', 'status', 'duedateplans'));
        }
    }

    function post_plan(Request $request) {
        $validation = $this->validate($request, [
            'plan' => 'required',
        ]);

        if ($validation) {
            $stat = \App\Status::where('idno', $request->idno)->first();
            if ($request->academic_type == "College") {
                DB::beginTransaction();
                $this->college_add_change_plan($request);
                $this->college_update_plan($request);
                $this->college_change_due_date($request);
                $this->log("Change plan of ". $request->idno." to ". $request->plan);
                DB::Commit();
            } else {
                DB::beginTransaction();
                $this->add_change_plan($request);
                $this->update_plan($request);
                $this->change_due_date($request);
                $this->log("Change plan of ". $request->idno." to ". $request->plan);
                DB::Commit();
            }
            return redirect(url('/cashier', array('viewledger',$stat->school_year, $request->idno)));
        }
    }

    function add_change_plan($request) {
        $originalplan = \App\Status::where('idno', $request->idno)->first()->type_of_plan;
        $changeplan = $request->plan;
        $orginalamount = \App\Ledger::where('idno', $request->idno)->where('category_switch', env("TUITION_FEE"))->first();
        $tuition = \App\CtrBedFee::where('level', $request->level)->where('category_switch', env("TUITION_FEE"))->first()->amount;
        $changeamount = $tuition + ($tuition * ($this->addPercentage($request->plan) / 100));
        $notchangeamount = $tuition;
        $addchange = new \App\ChangePlan;
        $addchange->idno = $request->idno;
        $addchange->change_date = Date('Y-m-d');
        $addchange->original_plan = $originalplan;
        $addchange->change_plan = $changeplan;
        $addchange->original_amount = $orginalamount->amount;
        $addchange->change_amount = $this->roundOff($changeamount);
        $addchange->posted_by = Auth::user()->idno;
        $addchange->save();

        $discount = \App\CtrDiscount::where('discount_code', $orginalamount->discount_code)->first();
        if (count($discount) > 0) {
            $discount_code = $discount->discount_code;
            $discount_description = $discount->discount_description;
            $discount_tuition = $discount->tuition_fee;
        }
        
        $orginalamount->amount = $this->roundOff($changeamount);
        $notorginalamount = $this->roundOff($notchangeamount);
        
        if (count($discount) > 0) {
        $amount = $notorginalamount;
        $discount_amount = $amount * $discount_tuition / 100;
        $orginalamount->discount_code = $discount_code;
        $orginalamount->discount = $discount_amount;
        }
        
        $orginalamount->update();
    }

    function roundOff($amount) {
        return round($amount);
    }

    function update_plan($request) {
        $status = \App\Status::where('idno', $request->idno)->first();
        $bedlevel = \App\BedLevel::where('idno', $request->idno)->where('school_year', $status->school_year)->where('period', $status->period)->first();
        $status->type_of_plan = $request->plan;
        $status->update();
        $bedlevel->type_of_plan = $request->plan;
        $bedlevel->update();
    }

    function change_due_date($request) {
        $stat = \App\Status::where('idno', $request->idno)->first();
        $schoolyear = $stat->school_year;
        $period = $stat->period;

        $deltedue = \App\LedgerDueDate::where('idno', $request->idno)->where('school_year', $schoolyear)->where('period', $period)->delete();
        $this->addDueDates($request, $schoolyear, $period);
    }

    function addPercentage($plan) {
        $interest = \App\CtrBedPlan::where('plan',$plan)->first()->interest;
        return $interest;
    }

    function addDueDates($request, $schoolyear, $period) {

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
                $total_decimal = $total_decimal + ($dueamount - $plan_amount);
            }

            $this->update_due_dates($request, $dueamount, $total_decimal, $dueothers->total);
        }
    }

    function update_due_dates($request, $dueamount, $total_decimal, $dueothers) {
        $update = \App\LedgerDueDate::where('idno', $request->idno)->where('due_switch', 0)->where('due_date', Date('Y-m-d'))->first();
        $update->amount = $dueothers + $dueamount + $total_decimal;
        $update->save();
    }
    
    

    function college_add_change_plan($request) {
        $tuition = 0;

        $stat = \App\Status::where('idno', $request->idno)->first();
        $school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first()->school_year;
        $period = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first()->period;
        $tfr = \App\CtrCollegeTuitionFee::where('program_code', $stat->program_code)->where('period', $period)->where('level', $stat->level)->first();
        $tuitionrate = $tfr->per_unit;
        $tobediscount = 0;
        
        $grades = \App\GradeCollege::where('idno', $request->idno)->where('school_year', $school_year)->where('period', $period)->get();

            $discounttype = \App\CollegeScholarship::where('idno', $request->idno)->first();
            
            if($discounttype->discount_code != NULL){
                if ($discounttype->discount_type == 0) {
                    $discounttf = $this->getdiscountrate('tf', $discounttype->discount_code, $request->idno);
//                    $discountof = $this->getdiscountrate('of', $discounttype->discount_code, $request->idno);
                    
                    //remove this after updating
//                    $discountof = $this->getdiscountrate('of', $discounttype->discount_code, $request->idno);
//                    $otherfees = \App\CtrCollegeOtherFee::where('program_code', $stat->program_code)->where('level', $stat->level)->where('period', $period)->get();
//                    foreach ($otherfees as $of){
//                        $getofledger = \App\Ledger::where('school_year', $school_year)->where('period', $period)->where('idno', $request->idno)->where('subsidiary',$of->subsidiary)->first();
//                        $getofledger->discount = $getofledger->amount * ($discountof / 100);
//                        $getofledger->discount_code = $discounttype->discount_code;
//                        $getofledger->save();
//                    }
                    //up to here
                    
                } else if ($discounttype->discount_type == 1) {
                    $discounttf = $this->getdiscount('tf', $discounttype->discount_code, $request->idno);
                }
            }
        
        
        foreach ($grades as $grade) {
            if($discounttype->discount_code != NULL){
                if ($discounttype->discount_type == 0) {
                    $tobediscount = $tobediscount + ((((($grade->lec + $grade->lab) * $tuitionrate * $grade->percent_tuition / 100)) * ($discounttf / 100)));
                } else if ($discounttype->discount_type == 1) {
                    $tobediscount = $tobediscount + $discounttf;
                }
            }
            
            $tuition = $tuition + (((($grade->lec + $grade->lab) * $tuitionrate * $grade->percent_tuition / 100)));
        }
        
        $originalplan = \App\Status::where('idno', $request->idno)->first()->type_of_plan;
        $changeplan = $request->plan;
        $orginalamount = \App\Ledger::where('idno', $request->idno)->where('level', $stat->level)->where('school_year', $school_year)->where('period', $period)->where('category_switch', env('TUITION_FEE'))->first();
        
        $add_payment = 0;
        $add_debit = 0;
        $plan_charge = \App\CtrPlanCharge::where('academic_type', "College")->first();
        $oldamount = \App\Ledger::where('idno', $request->idno)->where('level', $stat->level)->where('school_year', $school_year)->where('period', $period)->where('amount','<=', $plan_charge->amount)->where('category_switch', env('TUITION_FEE'))->get();
        if(count($oldamount)>0){
            foreach($oldamount as $del){
                $add_payment = $del->payment + $add_payment;
                $add_debit = $del->debit_memo + $add_debit;
                $del->delete();
            }
        }
//        $tuition = \App\CtrBedFee::where('level', $request->level)->where('category_switch', env("TUITION_FEE"))->first()->amount;
//        $changeamount = $tuition + ($tuition * ($this->addPercentage($request->plan) / 100));
        $changeamount = $tuition + ($tuition * (0 / 100));
        $addchange = new \App\ChangePlan;
        $addchange->idno = $request->idno;
        $addchange->change_date = Date('Y-m-d');
        $addchange->original_plan = $originalplan;
        $addchange->change_plan = $changeplan;
        $addchange->original_amount = $orginalamount->amount;
        $addchange->change_amount = $this->roundOff($changeamount);
        $addchange->posted_by = Auth::user()->idno;
        $addchange->save();

        $orginalamount->amount = $this->roundOff($changeamount);
        $orginalamount->discount = $this->roundOff($tobediscount);
        $orginalamount->payment = $orginalamount->payment + $add_payment;
        $orginalamount->debit_memo = $orginalamount->debit_memo + $add_debit;
        $orginalamount->update();
        
        $addamount = 0; 
        $due_dates = \App\CtrDueDate::where('academic_type', "College")->where('plan', $changeplan)->where('level', $stat->level)->get();
        $plan_charge = \App\CtrPlanCharge::where('academic_type', "College")->first();
        if (count($due_dates) > 0) {
            foreach ($due_dates as $paln) {
                $addamount = $addamount + $plan_charge->amount;
//                $addledger = new \App\ledger;
//                $addledger->idno = $idno;
//                $addledger->department = \App\CtrAcademicProgram::where('program_code', $program_code)->first()->department;
//                $addledger->program_code = $program_code;
//                $addledger->level = $level;
//                $addledger->school_year = $school_year;
//                $addledger->period = $period;
//                $addledger->category = "Tuition Fee";
//                $addledger->subsidiary = "Tuition Fee";
//                $addledger->receipt_details = "Tuition Fee";
//                $addledger->accounting_code = env("AR_TUITION_CODE");
//                $addledger->accounting_name = env("AR_TUITION_NAME");
//                $addledger->category_switch = env("TUITION_FEE");
//                $addledger->amount = 300;
//                $addledger->save();
            }
            $updateledger = \App\Ledger::where('idno', $request->idno)->where('level', $stat->level)->where('school_year', $school_year)->where('period', $period)->where('category_switch', env('TUITION_FEE'))->first();
            $updateledger->amount = $updateledger->amount + $addamount;
            $updateledger->save();
        }
        
    }

    function college_update_plan($request) {
        $status = \App\Status::where('idno', $request->idno)->first();
        $bedlevel = \App\CollegeLevel::where('idno', $request->idno)->where('school_year', $status->school_year)->where('period', $status->period)->first();
        $status->type_of_plan = $request->plan;
        $status->update();
        $bedlevel->type_of_plan = $request->plan;
        $bedlevel->update();
    }

    function college_change_due_date($request) {
        $stat = \App\Status::where('idno', $request->idno)->first();
        $schoolyear = $stat->school_year;
        $period = $stat->period;

        $deltedue = \App\LedgerDueDate::where('idno', $request->idno)->where('school_year', $schoolyear)->where('period', $period)->delete();
        $this->computeLedgerDueDates($request->idno, $schoolyear, $period, $stat->type_of_plan);
    }
    
    function get_percentage_now($plan){
        if ($plan == "Plan A") {
            $interest = 1;
        } else if ($plan == "Plan B") {
            $interest = .5;
        } else if ($plan == "Plan C") {
            $interest = .35;
        } else if ($plan == "Plan D") {
            $interest = .2;
        }
        return $interest;
    }
    
    function computeLedgerDueDates($idno, $school_year, $period, $plan) {
        $total_decimal = 0;
        $status = \App\Status::where('idno', $idno)->first();
        $due_dates = \App\CtrDueDate::where('academic_type', $status->academic_type)->where('plan', $plan)->where('level', $status->level)->get();
        $percentage_now = $this->get_percentage_now($plan);
        
        $totalTuition = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 6)->sum('amount');
        $totalOtherFees = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', '<', 6)->sum('amount');
        $totalTuitionDiscount = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 6)->sum('discount');
        $totalOtherFeesDiscount = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', '<', 6)->sum('discount');
        $totalFees = ($totalTuition + $totalOtherFees) - ($totalTuitionDiscount + $totalOtherFeesDiscount);
        $downpaymentamount = (($totalTuition - $totalTuitionDiscount) * $percentage_now) + ($totalOtherFees - $totalOtherFeesDiscount);
        if ($plan == 'Plan A') {
            $addledgerduedates = new \App\LedgerDueDate;
            $addledgerduedates->idno = $idno;
            $addledgerduedates->school_year = $school_year;
            $addledgerduedates->period = $period;
            $addledgerduedates->due_switch = 0;
            $addledgerduedates->due_date = date('Y-m-d');
            $addledgerduedates->amount = $totalFees;
            $addledgerduedates->save();
        } else {
            $addledgerduedates = new \App\LedgerDueDate;
            $addledgerduedates->idno = $idno;
            $addledgerduedates->school_year = $school_year;
            $addledgerduedates->period = $period;
            $addledgerduedates->due_switch = 0;
            $addledgerduedates->due_date = date('Y-m-d');
            $addledgerduedates->amount = $downpaymentamount;
            $addledgerduedates->save();
            foreach ($due_dates as $paln) {
                $totalFees_percentage = (($totalTuition*($paln->percentage/100)) + $totalOtherFees) - (($totalTuitionDiscount*($paln->percentage/100)) + $totalOtherFeesDiscount);
                $tf_percentage =        (($totalTuition*($paln->percentage/100))                    - (($totalTuitionDiscount*($paln->percentage/100))));

                $addledgerduedates = new \App\LedgerDueDate;
                $addledgerduedates->idno = $idno;
                $addledgerduedates->school_year = $school_year;
                $addledgerduedates->period = $period;
                $addledgerduedates->due_switch = 1;
                $addledgerduedates->due_date = $paln->due_date;
                $plan_amount = floor($this->computeplan($downpaymentamount, $totalFees_percentage, $due_dates, $tf_percentage));
                $addledgerduedates->amount = $plan_amount;
                $addledgerduedates->save();
                $total_decimal = $total_decimal + ($this->computeplan($downpaymentamount, $totalFees_percentage, $due_dates, $tf_percentage) - $plan_amount);
            }
            $this->college_update_due_dates($idno, $total_decimal, $downpaymentamount);
        }
    }

    function computeplan($downpaymentamount, $totalFees, $due_dates, $tf) {
        $planpayment = $tf;
//        $planpayment = ($totalFees - $downpaymentamount) / count($due_dates);
        return $planpayment;
    }

    function college_update_due_dates($idno, $total_decimal, $downpaymentamount) {
        $update = \App\LedgerDueDate::where('idno', $idno)->where('due_switch', 0)->where('due_date', Date('Y-m-d'))->first();
        $update->amount = $downpaymentamount + $total_decimal;
        $update->save();
    }
    
    public static function log($action){
        $log = new \App\Log();
        $log->action = "$action";
        $log->idno = Auth::user()->idno;
        $log->datetime = date("Y-m-d H:i:s");
        $log->save();
    }
    
    function getdiscountrate($type, $discount_code, $idno) {
        if ($type == 'tf') {
            return \App\CollegeScholarship::where('idno', $idno)->where('discount_code', $discount_code)->first()->tuition_fee;
        } elseif ($type == 'of') {
            return \App\CollegeScholarship::where('idno', $idno)->where('discount_code', $discount_code)->first()->other_fee;
        } elseif ($type == 'nondiscounted') {
            return \App\CollegeScholarship::where('idno', $idno)->where('discount_code', $discount_code)->first()->other_fee;
        } elseif ($type == 'srf') {
            return \App\CollegeScholarship::where('idno', $idno)->where('discount_code', $discount_code)->first()->other_fee;
        }
    }

    function getdiscount($type, $discount_code, $idno) {
        if ($type == 'tf') {
            return \App\CollegeScholarship::where('idno', $idno)->where('discount_code', $discount_code)->first()->amount;
        }
    }

}
