<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;
use \App\Http\Controllers\Cashier\MainPayment;

class ReassessController extends Controller {

    //

    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        if (Auth::user()->accesslevel == env("ADMIN")) {

            return view('admin.reassess.view');
        }
    }

    function re_assess_now(Request $request) {
        if (Auth::user()->accesslevel == env("ADMIN")) {
            DB::beginTransaction();
            foreach ($request->idno as $idno) {
                $status = \App\Status::where('idno', $idno)->first();
                $request = \App\Status::where('idno', $idno)->first();
                $request->plan = $request->type_of_plan;

                //with reservation
                $this->reverse_reservations($idno, $status->levels_reference_id);
                $this->removeDM($idno, $status->levels_reference_id);
                //reassess
                $this->removeLedger($idno, $status->school_year, $status->period, $status->academic_type);
                $this->removeLedgerDueDate($idno, $status->school_year, $status->period, $status->academic_type);
                $this->removeGrades($idno, $status->school_year, $status->period, $status->academic_type);
                $this->returnStatus($idno, $status->school_year, $status->period, $status->academic_type);
                $this->remove_discountList($idno, $status->school_year, $status->period, $status->academic_type);

                //post assessment
                $this->addGrades($request, $status->school_year, $status->period);
                $this->addLedger($request, $status->school_year, $status->period);
                $this->addOtherCollection($request, $status->school_year, $status->period);
                $this->addSRF($request, $status->school_year, $status->period);
                $this->addDueDates($request, $status->school_year, $status->period);
                $this->modifyStatus($request, $status->school_year, $status->period);
                $this->checkReservations($request, $status->school_year, $status->period);

                $cut_off = \App\CtrEnrollmentCutOff::where('academic_type', $status->academic_type)->first();
                if (date('Y-m-d') > $cut_off->cut_off) {
                    $this->addLatePayment($request, $status->school_year, $status->period);
                }
            }
            DB::Commit();
            return redirect('/admin/re_assess');
        }
    }

    function addLedger($request, $schoolyear, $period) {
        $discount_code = 0;
        $discount_description = "";
        $discount_tuition = 0;
        $discount_other = 0;
        $discount_depository = 0;
        $discount_misc = 0;
        $discount_srf = 0;
        $request_discount = \App\PartialStudentDiscount::where('idno', $request->idno)->first();
        if (count($request_discount) > 0) {
            $discount = \App\CtrDiscount::where('discount_code', $request_discount->discount)->first();
            if (count($discount) > 0) {
                $discount_code = $discount->discount_code;
                $discount_description = $discount->discount_description;
                $discount_tuition = $discount->tuition_fee;
                $discount_other = $discount->other_fee;
                $discount_depository = $discount->depository_fee;
                $discount_misc = $discount->misc_fee;
                $this->addDiscountList($request, $schoolyear, $period, $discount);
            }
        }
        $department = \App\CtrAcademicProgram::where('level', $request->level)->first();
        $fees = \App\CtrBedFee::where('level', $request->level)->get();
        if (count($fees) > 0) {
            foreach ($fees as $fee) {
                $addledger = new \App\Ledger;
                $addledger->idno = $request->idno;
                $addledger->department = $department->department;
                $addledger->level = $request->level;
                if ($request->level == "Grade 11" || $request->level == "Grade 12") {
                    $addledger->strand = $request->strand;
                    $addledger->period = $period;
                }
                $addledger->school_year = $schoolyear;
                $addledger->category = $fee->category;
                $addledger->subsidiary = $fee->subsidiary;
                $addledger->receipt_details = $fee->receipt_details;
                $addledger->accounting_code = $fee->accounting_code;
                $addledger->accounting_name = $this->getAccountingName($fee->accounting_code);
                $addledger->category_switch = $fee->category_switch;

                $amount = $fee->amount;
                $discount_amount = 0;
                switch ($fee->category_switch) {
                    case env("MISC_FEE"):
                        $amount = $this->roundOff($fee->amount);
                        $discount_amount = $fee->amount * $discount_misc / 100;
                        break;
                    case env("OTHER_FEE"):
                        $amount = $this->roundOff($fee->amount);
                        $discount_amount = $fee->amount * $discount_other / 100;
                        break;
                    case env("DEPOSITORY_FEE"):
                        $amount = $this->roundOff($fee->amount);
                        $discount_amount = $fee->amount * $discount_depository / 100;
                        break;
                    case env("TUITION_FEE"):
                        $addpercent = $this->addPercentage($request->plan);
                        $amount = $this->roundOff(($fee->amount + ($fee->amount * $addpercent / 100)));
                        $discount_amount = $amount * $discount_tuition / 100;
                }

                $addledger->amount = $amount;
                $addledger->discount_code = $discount_code;
                $addledger->discount = $discount_amount;
                $addledger->save();
            }
            $status = \App\Status::where('idno', $request->idno)->first();
            if (count($status) > 0) {
                if ($status->is_new == "1") {

                    if ($request->level == "Grade 11") {
                        $addfee = \App\CtrNewShsStudentFee::get();
                        if (count($addfee) > 0) {
                            foreach ($addfee as $fee) {
                                $addledger = new \App\Ledger;
                                $addledger->idno = $request->idno;
                                $addledger->department = $department->department;
                                $addledger->level = $request->level;
                                $addledger->strand = $request->strand;
                                $addledger->period = $period;
                                $addledger->school_year = $schoolyear;
                                $addledger->category = $fee->category;
                                $addledger->subsidiary = $fee->subsidiary;
                                $addledger->receipt_details = $fee->receipt_details;
                                $addledger->accounting_code = $fee->accounting_code;
                                $addledger->accounting_name = $this->getAccountingName($fee->accounting_code);
                                $addledger->category_switch = $fee->category_switch;
                                $addledger->amount = $fee->amount;
                                $addledger->save();
                            }
                        }
                    } else {
                        $addfee = \App\CtrNewStudentFee::get();
                        if (count($addfee) > 0) {
                            foreach ($addfee as $fee) {
                                $addledger = new \App\Ledger;
                                $addledger->idno = $request->idno;
                                $addledger->department = $department->department;
                                $addledger->level = $request->level;
                                if ($request->level == "Grade 11" || $request->level == "Grade 12") {
                                    $addledger->strand = $request->strand;
                                    $addledger->period = $period;
                                }
                                $addledger->school_year = $schoolyear;
                                $addledger->category = $fee->category;
                                $addledger->subsidiary = $fee->subsidiary;
                                $addledger->receipt_details = $fee->receipt_details;
                                $addledger->accounting_code = $fee->accounting_code;
                                $addledger->accounting_name = $this->getAccountingName($fee->accounting_code);
                                $addledger->category_switch = $fee->category_switch;
                                $addledger->amount = $fee->amount;
                                $addledger->save();
                            }
                        }
                    }
                }
            }
            $is_foreign = \App\User::where('idno', $request->idno)->first();
            if (count($is_foreign) > 0) {
                if ($is_foreign->is_foreign == '1') {
                    $reg_amount = \App\CtrForiegnFee::where('subsidiary', "Registration")->first()->amount;
                    $checkforeign = \App\Ledger::where('idno', $request->idno)->where('school_year', $schoolyear)->where('subsidiary', 'Registration')->where('amount', $reg_amount)->get();
                    if (isset($checkforeign) == 0) {
                        $addfee = \App\CtrForiegnFee::get();
                        foreach ($addfee as $fee) {
                            $addledger = new \App\Ledger;
                            $addledger->idno = $request->idno;
                            $addledger->department = $department->department;
                            $addledger->level = $request->level;
                            if ($request->level == "Grade 11" || $request->level == "Grade 12") {
                                $addledger->strand = $request->strand;
                                $addledger->period = $period;
                            }
                            $addledger->school_year = $schoolyear;
                            $addledger->category = $fee->category;
                            $addledger->subsidiary = $fee->subsidiary;
                            $addledger->receipt_details = $fee->receipt_details;
                            $addledger->accounting_code = $fee->accounting_code;
                            $addledger->accounting_name = $this->getAccountingName($fee->accounting_code);
                            $addledger->category_switch = $fee->category_switch;
                            $addledger->amount = $fee->amount;
                            $addledger->save();
                        }
                    }
                }
            }
        }
    }

    function addOtherCollection($request, $schoolyear, $period) {
        if ($request->level == "Grade 11" || $request->level == "Grade 12") {
            $adds = \App\ShsOtherCollection::get();
        } else {
            $adds = \App\OtherCollection::get();
        }
        $dept = \App\CtrAcademicProgram::where('level', $request->level)->first();
        if (count($adds) > 0) {
            foreach ($adds as $add) {
                $addledger = new \App\Ledger;
                $addledger->idno = $request->idno;
                $addledger->department = $dept->department;
                $addledger->level = $request->level;
                if ($request->level == "Grade 11" || $request->level == "Grade 12") {
                    $addledger->strand = $request->strand;
                    $addledger->period = $period;
                }
                $addledger->school_year = $schoolyear;
                $addledger->category = $add->category;
                $addledger->subsidiary = $add->subsidiary;
                $addledger->receipt_details = $add->receipt_details;
                $addledger->accounting_code = $add->accounting_code;
                $addledger->accounting_name = $this->getAccountingName($add->accounting_code);
                $addledger->category_switch = $add->category_switch;
                $disc_other = $this->getOtherDiscount($request->idno, $add->subsidiary);
                $addledger->amount = $add->amount;
                $addledger->discount = $disc_other;
                $addledger->discount_code = $add->subsidiary;
                $addledger->save();

                if ($disc_other > 0) {

                    $discount = new \App\DiscountList;
                    $discount->discount_code = $add->subsidiary;
                    $discount->discount_description = $add->subsidiary;
                    $discount->accounting_code = NULL;
                    $discount->tuition_fee = 0;
                    $discount->other_fee = 0;
                    $discount->misc_fee = 0;
                    $discount->depository_fee = 0;
                    $discount->discount_type = 1;
                    $discount->amount = $disc_other;

                    $this->addDiscountList($request, $schoolyear, $period, $discount);
                }
            }
        }
    }

    function addSRF($request, $schoolyear, $period) {
        if ($request->level == "Grade 11" || $request->level == "Grade 12") {
            $department = \App\CtrAcademicProgram::where('level', $request->level)->first();
            $srf = \App\CtrBedSrf::where('level', $request->level)->where('strand', $request->strand)->first();
            if (count($srf) > 0) {
                $add = new \App\Ledger;
                $add->idno = $request->idno;
                $add->department = $department->department;
                $add->level = $request->level;
                if ($request->level == "Grade 11" || $request->level == "Grade 12") {
                    $add->strand = $request->strand;
                    $add->period = $period;
                }
                $add->school_year = $schoolyear;
                $add->category = $srf->category;
                $add->subsidiary = $srf->subsidiary;
                $add->receipt_details = $srf->receipt_details;
                $add->accounting_code = $srf->accounting_code;
                $add->category_switch = $srf->category_switch;
                $add->accounting_name = $this->getAccountingName($srf->accounting_code);
                $add->amount = $srf->amount;
                $add->save();
            }
        }
    }

    function addGrades($request, $schoolyear, $period) {
        if ($request->level == "Grade 11" || $request->level == "Grade 12") {
            $subjects = \App\BedCurriculum::where('level', $request->level)->where('strand', $request->strand)->where('subject_type', '<', 2)->get();
        } else {
            $subjects = \App\BedCurriculum::where('level', $request->level)->where('subject_type', '<', 2)->get();
        }
        if (count($subjects) > 0) {
            foreach ($subjects as $subject) {
                $addsubject = new \App\GradeBasicEd;
                $addsubject->idno = $request->idno;
                $addsubject->school_year = $schoolyear;
                if ($request->level == "Grade 11" || $request->level == "Grade 12") {
                    $addsubject->strand = $request->strand;
                    $addsubject->period = $period;
                }
                $addsubject->level = $request->level;
                $addsubject->subject_code = $subject->subject_code;
                $addsubject->subject_name = $subject->subject_name;
                $addsubject->group_name = $subject->group_name;
                $addsubject->units = $subject->units;
                $addsubject->display_subject_code = $subject->display_subject_code;
                $addsubject->weighted = $subject->weighted;
                $addsubject->encoded_by = Auth::user()->idno;
                $addsubject->save();
            }
        }
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
        $update->amount = $update->amount + $dueamount + $total_decimal;
        $update->save();
    }

    function removeLedger($idno, $schoolyear, $period, $academic_type) {
        if ($academic_type == "BED") {
            \App\Ledger::where('idno', $idno)->where('category_switch', env("TUITION_FEE"))->where('school_year', $schoolyear)->delete();
            \App\Ledger::where('idno', $idno)->where('category_switch', env("MISC_FEE"))->where('school_year', $schoolyear)->delete();
            \App\Ledger::where('idno', $idno)->where('category_switch', env("OTHER_FEE"))->where('school_year', $schoolyear)->delete();
            \App\Ledger::where('idno', $idno)->where('category_switch', env("DEPOSITORY_FEE"))->where('school_year', $schoolyear)->delete();
            \App\Ledger::where('idno', $idno)->where('category_switch', env("SRF_FEE"))->where('school_year', $schoolyear)->delete();
            \App\Ledger::where('idno', $idno)->where('category_switch', env("OTHER_MISC"))->where('subsidiary', "Late Payment")->where('school_year', $schoolyear)->delete();
        } else {
            \App\Ledger::where('idno', $idno)->where('category_switch', env("TUITION_FEE"))->where('school_year', $schoolyear)->where('period', $period)->delete();
            \App\Ledger::where('idno', $idno)->where('category_switch', env("MISC_FEE"))->where('school_year', $schoolyear)->where('period', $period)->delete();
            \App\Ledger::where('idno', $idno)->where('category_switch', env("OTHER_FEE"))->where('school_year', $schoolyear)->where('period', $period)->delete();
            \App\Ledger::where('idno', $idno)->where('category_switch', env("DEPOSITORY_FEE"))->where('school_year', $schoolyear)->where('period', $period)->delete();
            \App\Ledger::where('idno', $idno)->where('category_switch', env("SRF_FEE"))->where('school_year', $schoolyear)->where('period', $period)->delete();
            \App\Ledger::where('idno', $idno)->where('category_switch', env("OTHER_MISC"))->where('subsidiary', "Late Payment")->where('school_year', $schoolyear)->where('period', $period)->delete();
        }
    }

    function modifyStatus($request, $schoolyear, $period) {
        $department = \App\CtrAcademicProgram::where('level', $request->level)->first();
        $status = \App\Status::where('idno', $request->idno)->first();
        $status->status = env("ASSESSED");
        $status->level = $request->level;
        if ($request->level == "Grade 11" || $request->level == "Grade 12") {
            $status->strand = $request->strand;
            $status->period = $period;
            $status->academic_type = "SHS";
        } else {
            $status->academic_type = "BED";
        }
        $status->school_year = $schoolyear;
        $status->section = $request->section;
        $status->department = $department->department;
        $status->date_registered = date('Y-m-d');
        $status->type_of_plan = $request->plan;
        $status->update();

        $promotion = \App\Promotion::where('idno', $request->idno)->first();


        switch ($request->level) {
            case "Pre-Kinder":
                $current_level = "Kinder";
                break;
            case "Kinder":
                $current_level = "Grade 1";
                break;
            case "Grade 1":
                $current_level = "Grade 2";
                break;
            case "Grade 2":
                $current_level = "Grade 3";
                break;
            case "Grade 3":
                $current_level = "Grade 4";
                break;
            case "Grade 4":
                $current_level = "Grade 5";
                break;
            case "Grade 5":
                $current_level = "Grade 6";
                break;
            case "Grade 6":
                $current_level = "Grade 7";
                break;
            case "Grade 7":
                $current_level = "Grade 8";
                break;
            case "Grade 8":
                $current_level = "Grade 9";
                break;
            case "Grade 9":
                $current_level = "Grade 10";
                break;
            case "Grade 10":
                $current_level = "Grade 11";
                break;
            case "Grade 11":
                $current_level = "Grade 12";
                break;
            case "Grade 12":
                $current_level = "Grade 12";
                break;
        }
        if ($period == "2nd Semester") {
            switch ($status->level) {
                case "Grade 11":
                    $current_level = "Grade 11";
                    break;
                case "Grade 12":
                    $current_level = "Grade 12";
                    break;
            }
        }
        if (count($promotion) == 0) {
            $new = new \App\Promotion();
            $new->idno = $request->idno;
            $new->level = $current_level;
            $new->strand = $request->strand;
            $new->section = $request->section;
            $new->save();
        } else {
            $promotion->level = $current_level;
            $promotion->strand = $request->strand;
            $promotion->section = $request->section;
//            $promotion->save();
        }
    }

    function checkReservations($request, $school_year, $period) {
        $levels_reference_id = uniqid();
        $checkreservations = \App\Reservation::where('idno', $request->idno)->where('is_consumed', 0)->where('is_reverse', 0)->selectRaw('sum(amount) as amount')->first();
        if ($checkreservations->amount > 0) {
            $totalpayment = $checkreservations->amount;
            $reference_id = uniqid();

            $request->date = date('Y-m-d');
            MainPayment::addUnrealizedEntry($request, $reference_id);

            $ledgers = collect();
            if ($request->level == "Grade 11") {
                $ledgers_parent_partnership = \App\Ledger::where('idno', $request->idno)->whereRaw('amount-debit_memo-discount-payment > 0')->where('category', "Parent Partnership")->where('category_switch', env('PARENT_PARTNERSHIP'))->get();
                $ledgers_all = \App\Ledger::where('idno', $request->idno)->whereRaw('amount-debit_memo-discount-payment > 0')->where('category_switch', '<=', env("TUITION_FEE"))->where('category', '!=', 'Parent Partnership')->get();

                foreach ($ledgers_parent_partnership as $ppartnership) {
                    $ledgers->push($ppartnership);
                }
                foreach ($ledgers_all as $all) {
                    $ledgers->push($all);
                }
            } else {
                $ledgers_family_council = \App\Ledger::where('idno', $request->idno)->whereRaw('amount-debit_memo-discount-payment > 0')->where('category', "Family Council")->where('category_switch', env('FAMILY_COUNCIL'))->get();
                $ledgers_registration = \App\Ledger::where('idno', $request->idno)->whereRaw('amount-debit_memo-discount-payment > 0')->where('Subsidiary', "Registration")->where('category_switch', env('MISC_FEE'))->get();
                $ledgers_all = \App\Ledger::where('idno', $request->idno)->whereRaw('amount-debit_memo-discount-payment > 0')->where('category_switch', '<=', env("TUITION_FEE"))->where('category', '!=', 'Family Council')->where('Subsidiary', '!=', "Registration")->get();

                foreach ($ledgers_family_council as $fcouncil) {
                    $ledgers->push($fcouncil);
                }
                foreach ($ledgers_registration as $registration) {
                    $ledgers->push($registration);
                }
                foreach ($ledgers_all as $all) {
                    $ledgers->push($all);
                }
            }
            MainPayment::processAccounting($request, $reference_id, $totalpayment, $ledgers, env("DEBIT_MEMO"));

            $this->postDebit($request, $reference_id, $totalpayment, $levels_reference_id);

            $changestatus = \App\Status::where('idno', $request->idno)->first();
            $changestatus->status = env("ENROLLED");
            $changestatus->update();
            $changereservation = \App\Reservation::where('idno', $request->idno)->where('is_consumed', 0)->where('is_reverse', 0)->get();
            if (count($changereservation) > 0) {
                foreach ($changereservation as $change) {
                    $change->levels_reference_id = $levels_reference_id;
                    $change->is_consumed = '1';
                    $change->consume_sy = $school_year;
                    $change->update();
                }
            }
            \App\Http\Controllers\Cashier\MainPayment::addLevels($request->idno, $levels_reference_id);
        }
        $change = \App\Status::where('idno', $request->idno)->first();
        $change->levels_reference_id = $levels_reference_id;
        $change->update();
        return $this->changeStatus($request->idno, $levels_reference_id);
    }

    function changeStatus($idno) {
        $change = \App\Status::where('idno', $idno)->first();
        $change->status = env("ENROLLED");
        $change->date_enrolled = date('Y-m-d');
        $change->update();
        $no = $idno;
        if (strlen($idno) > 10) {
            $user = \App\User::where('idno', $idno)->first();
            $no = MainPayment::getIdno($idno);
            $user->idno = $no;
            $user->save();
        } else {
            $status = \App\Status::where('idno', $idno)->first();
            $status->is_new = 0;
            $status->update();
        }
        return $no;
    }

    function getIdno($idno) {
        $status = \App\Status::where('idno', $idno)->first();
        if ($status->academic_type == "College") {
            $id_no = \App\CtrStudentNumber::where('academic_type', "College")->first();
            $idNumber = $id_no->idno;
            $id_no->idno = $id_no->idno + 1;
            $id_no->update();
            for ($i = strlen($idNumber); $i <= 2; $i++) {
                $idNumber = "0" . $idNumber;
            }
            $pre = \App\CtrEnrollmentSchoolYear::where('academic_type', $status->academic_type)->first();
            $pre_number = $pre->school_year;
            return substr($pre_number, 2, 2) . $idNumber;
        } else {
            $id_no = \App\CtrStudentNumber::where('academic_type', 'BED')->first();
            $idNumber = $id_no->idno;
            $id_no->idno = $id_no->idno + 1;
            $id_no->update();
            for ($i = strlen($idNumber); $i <= 2; $i++) {
                $idNumber = "0" . $idNumber;
            }
            $pre = \App\CtrEnrollmentSchoolYear::where('academic_type', $status->academic_type)->first();
            $pre_number = $pre->school_year;
            $pre_number2 = $pre->school_year + 1;
            return substr($pre_number, 2, 2) . substr($pre_number2, 2, 2) . $idNumber;
        }
    }

    function addLatePayment($request, $schoolyear, $period) {
        $latefees = \App\CtrBedLatePayment::get();
        $department = \App\CtrAcademicProgram::where('level', $request->level)->first();
        if (count($latefees) > 0) {
            foreach ($latefees as $fee) {
                $addledger = new \App\Ledger;
                $addledger->idno = $request->idno;
                $addledger->department = $department->department;
                $addledger->level = $request->level;
                if ($request->level == "Grade 11" || $request->level == "Grade 12") {
                    $addledger->strand = $request->strand;
                    $addledger->period = $period;
                }
                $addledger->school_year = $schoolyear;
                $addledger->category = $fee->category;
                $addledger->subsidiary = $fee->subsidiary;
                $addledger->receipt_details = $fee->receipt_details;
                $addledger->accounting_code = $fee->accounting_code;
                $addledger->accounting_name = $this->getAccountingName($fee->accounting_code);
                $addledger->category_switch = $fee->category_switch;
                $addledger->amount = $fee->amount;
                $addledger->save();
            }
        }
    }

    function removeLedgerDueDate($idno, $schoolyear, $period, $academic_type) {
        if ($academic_type == "BED") {
            \App\LedgerDueDate::where('idno', $idno)->where('school_year', $schoolyear)->delete();
        } else {
            \App\LedgerDueDate::where('idno', $idno)->where('school_year', $schoolyear)->where('period', $period)->delete();
        }
    }

    function removeGrades($idno, $schoolyear, $period, $academic_type) {
        if ($academic_type == "BED") {
            \App\GradeBasicEd::where('idno', $idno)->where('school_year', $schoolyear)->delete();
        } else {
            \App\GradeBasicEd::where('idno', $idno)->where('school_year', $schoolyear)->where('period', $period)->delete();
        }
    }

    function returnStatus($idno, $schoolyear, $period, $academic_type) {
        $status = \App\Status::where('idno', $idno)->first();
        $user = \App\User::where('idno', $idno)->first();
        $assignlevel = $status->level;
        switch ($status->level) {
            case "Pre-Kinder":
                $assignlevel = "Pre-Kinder";
                $academic_type = "BED";
                break;
            case "Kinder":
                $assignlevel = "Pre-Kinder";
                $academic_type = "BED";
                break;
            case "Grade 1":
                $assignlevel = "Kinder";
                $academic_type = "BED";
                break;
            case "Grade 2":
                $assignlevel = "Grade 1";
                $academic_type = "BED";
                break;
            case "Grade 3":
                $assignlevel = "Grade 2";
                $academic_type = "BED";
                break;
            case "Grade 4":
                $assignlevel = "Grade 3";
                $academic_type = "BED";
                break;
            case "Grade 5":
                $assignlevel = "Grade 4";
                $academic_type = "BED";
                break;
            case "Grade 6":
                $assignlevel = "Grade 5";
                $academic_type = "BED";
                break;
            case "Grade 7":
                $assignlevel = "Grade 6";
                $academic_type = "BED";
                break;
            case "Grade 8":
                $assignlevel = "Grade 7";
                $academic_type = "BED";
                break;
            case "Grade 9":
                $assignlevel = "Grade 8";
                $academic_type = "BED";
                break;
            case "Grade 10":
                $assignlevel = "Grade 9";
                $academic_type = "BED";
                break;
            case "Grade 11":
                $assignlevel = "Grade 10";
                $academic_type = "BED";
                break;
            case "Grade 12":
                $assignlevel = "Grade 11";
                $academic_type = "SHS";
                break;
        }
        if ($status->period == "2nd Semester") {
            switch ($status->level) {
                case "Grade 11":
                    $assignlevel = "Grade 11";
                    $academic_type = "SHS";
                    break;
                case "Grade 12":
                    $assignlevel = "Grade 12";
                    $academic_type = "SHS";
                    break;
            }
        }
        $status->level = $assignlevel;
        $status->status = 0;
        $status->academic_type = $academic_type;
        $status->update();

        $user->academic_type = $academic_type;
        $user->update();

        $promotion = \App\Promotion::where('idno', $idno)->first();


        switch ($status->level) {
            case "Pre-Kinder":
                $current_level = "Kinder";
                break;
            case "Kinder":
                $current_level = "Grade 1";
                break;
            case "Grade 1":
                $current_level = "Grade 2";
                break;
            case "Grade 2":
                $current_level = "Grade 3";
                break;
            case "Grade 3":
                $current_level = "Grade 4";
                break;
            case "Grade 4":
                $current_level = "Grade 5";
                break;
            case "Grade 5":
                $current_level = "Grade 6";
                break;
            case "Grade 6":
                $current_level = "Grade 7";
                break;
            case "Grade 7":
                $current_level = "Grade 8";
                break;
            case "Grade 8":
                $current_level = "Grade 9";
                break;
            case "Grade 9":
                $current_level = "Grade 10";
                break;
            case "Grade 10":
                $current_level = "Grade 11";
                break;
            case "Grade 11":
                $current_level = "Grade 12";
                break;
            case "Grade 12":
                $current_level = "Grade 12";
                break;
        }
        if ($period == "2nd Semester") {
            switch ($status->level) {
                case "Grade 11":
                    $current_level = "Grade 11";
                    break;
                case "Grade 12":
                    $current_level = "Grade 12";
                    break;
            }
        }

        $promotion->level = $current_level;
//        $promotion->save();
    }

    function remove_discountlist($idno, $schoolyear, $period, $academic_type) {
        if ($academic_type == "BED") {
            \App\DiscountList::where('idno', $idno)->where('school_year', $schoolyear)->delete();
        } else {
            \App\DiscountList::where('idno', $idno)->where('school_year', $schoolyear)->where('period', $period)->delete();
        }
    }

    function reverse_reservations($idno, $levels_reference_id) {
        $reverses = \App\Reservation::where('idno', $idno)->where('levels_reference_id', $levels_reference_id)->get();
        foreach ($reverses as $reverse) {
            $reverse->levels_reference_id = "";
            $reverse->is_consumed = 0;
            $reverse->consume_sy = "";
            $reverse->save();
        }
    }

    function removeDM($idno, $levels_reference_id) {
        $removeDMs = \App\DebitMemo::where('idno', $idno)->where('levels_reference_id', $levels_reference_id)->get();
        foreach ($removeDMs as $removeDM) {
            $reference_id = $removeDM->reference_id;
            $removeDM->delete();
            $this->remove_accountings($reference_id);
        }
    }

    function remove_accountings($reference_id) {
        $remove_accountings = \App\Accounting::where('reference_id', $reference_id)->get();
        foreach ($remove_accountings as $accounting) {
            $accounting->delete();
        }
    }

    function getAccountingName($accounting_code) {
        $accounting_name = \App\ChartOfAccount::where('accounting_code', $accounting_code)->first();
        if (count($accounting_name) > 0) {
            return $accounting_name->accounting_name;
        } else {
            return "Not Found in Chart of Account";
        }
    }

    function roundOff($amount) {
        return round($amount);
    }

    function addPercentage($plan) {
        switch ($plan) {
            case "Plan A":
                return 0;
                break;
            case "Plan B":
                return 1;
                break;
            case "Plan C":
                return 2;
                break;
            case "Plan D":
                return 3;
                break;
        }
    }

    function getOtherDiscount($idno, $subsidiary) {
        $disc = \App\DiscountCollection::where('idno', $idno)->where('subsidiary', $subsidiary)->first();
        if (count($disc) > 0) {
            return $disc->discount_amount;
        } else {
            return 0;
        }
    }

    function postDebit($request, $reference_id, $totalpayment, $levels_reference_id) {
        $fiscal_year = \App\CtrFiscalYear::first()->fiscal_year;
        $reservations = \App\Reservation::where('idno', $request->idno)->where('is_consumed', 0)->where('is_reverse', 0)->get();
        $dept = \App\CtrAcademicProgram::where('level', $request->level)->first();
        $department = $dept->department; //\App\Status::where('idno',$idno)->first()->department;
        $totalReserved = 0;
        if (count($reservations) > 0) {
            foreach ($reservations as $ledger) {
                $addacct = new \App\Accounting;
                $addacct->transaction_date = date('Y-m-d');
                $addacct->reference_id = $reference_id;
                //$addacct->reference_number=$ledger->id;
                $addacct->accounting_type = env("DEBIT_MEMO");
                $addacct->subsidiary = $ledger->idno;
                $department = $dept->department;
                $addacct->department = $department;
                if ($ledger->reservation_type == 1) {
                    $category = "Reservation";
                    $accounting_code = env("RESERVATION_CODE");
                    $accounting_name = env("RESERVATION_NAME");
                } else if ($ledger->reservation_type == 2) {
                    $category = "Student Deposit";
                    $accounting_code = env("STUDENT_DEPOSIT_CODE");
                    $accounting_name = env("STUDENT_DEPOSIT_NAME");
                }
                $addacct->category = $category;
                $addacct->receipt_details = $category;
                $addacct->particular = $category;
                $addacct->accounting_code = $accounting_code;
                $addacct->accounting_name = $accounting_name;
                $addacct->department = $department;
                $addacct->fiscal_year = $fiscal_year;
                $addacct->debit = $ledger->amount;
                $addacct->posted_by = Auth::user()->idno;
                $addacct->save();
                $ledger->is_consumed = 1;
                $totalReserved = $totalReserved + $ledger->amount;
            }
            $this->postDebitMemo($request->idno, $reference_id, $totalReserved, $levels_reference_id);
        }
    }

    function postDebitMemo($idno, $reference_id, $totalReserved, $levels_reference_id) {
        $school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'BED')->first();
        $debit_memo = new \App\DebitMemo;
        $debit_memo->idno = $idno;
        $debit_memo->levels_reference_id = $levels_reference_id;
        $debit_memo->transaction_date = date("Y-m-d");
        $debit_memo->reference_id = $reference_id;
        $debit_memo->dm_no = $this->getDMNumber();
        $debit_memo->explanation = "Reversal of Reservation/Student Deposit";
        $debit_memo->amount = $totalReserved;
        $debit_memo->reservation_sy = $school_year->school_year;
        $debit_memo->posted_by = Auth::user()->idno;
        $status = \App\Status::where('idno', $idno)->first();
        $debit_memo->school_year = $status->school_year;
        if ($status->level == "Grade 11" || $status->level == "Grade 12") {
            $debit_memo->period = $status->period;
        } else {
            $debit_memo->period = "";
        }
        $debit_memo->save();
    }

    function getDMNumber() {
        $id = \App\ReferenceId::where('idno', Auth::user()->idno)->first()->id;
        $number = \App\ReferenceId::where('idno', Auth::user()->idno)->first()->dm_no;
        $receipt = "";
        for ($i = strlen($number); $i <= 6; $i++) {
            $receipt = $receipt . "0";
        }

        $update = \App\ReferenceId::where('idno', Auth::user()->idno)->first();
        $update->dm_no = $update->dm_no + 1;
        $update->update();

        return $id . $receipt . $number;
    }

    function addDiscountList($request, $school_year, $period, $discount) {
        $add_discount = new \App\DiscountList;
        $add_discount->idno = $request->idno;
        $add_discount->level = $request->level;
        if ($request->level == "Grade 11" || $request->level == "Grade 12") {
            $add_discount->strand = $request->strand;
            $add_discount->period = $period;
        }
        $add_discount->school_year = $school_year;
        $add_discount->discount_code = $discount->discount_code;
        $add_discount->discount_description = $discount->discount_description;
        $add_discount->accounting_code = $discount->accounting_code;
        $add_discount->tuition_fee = $discount->tuition_fee;
        $add_discount->other_fee = $discount->other_fee;
        $add_discount->misc_fee = $discount->misc_fee;
        $add_discount->depository_fee = $discount->depository_fee;
        $add_discount->discount_type = $discount->discount_type;
        $add_discount->amount = $discount->amount;
        $add_discount->save();
    }

    function update_sy2019_college_fees() {
        $lists = DB::table('update_sy2019_college_fees')->whereRaw('is_done = 0')->get();
        DB::beginTransaction();
        foreach ($lists as $list) {
            $checkUser = \App\User::where('idno', $list->idno)->first();
            if (count($checkUser) > 0) {

                $this->Update($list->idno, "Student Activities", 500);
                $this->Update($list->idno, "Energy Fees", 4500);
                $this->Update($list->idno, "Sports Program Fee", 1500);
                $this->Update($list->idno, "Accident Insurance", 400);

                DB::table('update_sy2019_college_fees')->where('idno', $list->idno)->update(array(
                    'is_done' => 1,
                ));
            } else {
                return "ERROR1";
            }
        }
        DB::Commit();
        Return 'DONE';
    }

    function Update($idno, $subsidiary, $amount) {
        $update = \App\Ledger::where('idno', $idno)->where('school_year', 2019)->where('period', '1st Semester')->where('Subsidiary', $subsidiary)->first();
        if (count($update) > 0) {
            $update->amount = $amount;
            $update->discount = $amount;
            $update->save();
        } else {
            return "ERROR2";
        }
    }

}
