<?php

namespace App\Http\Controllers\RegistrarCollege\Assessment\Ajax;

use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use DB;

class assessment_ajax extends Controller {

//
    function get_assessed_payment() {
        if (Request::ajax()) {
            $discounttf = 0;
            $discountof = 0;
            $discounttype = 0;
            $school_year = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first()->school_year;
            $period = \App\CtrEnrollmentSchoolYear::where('academic_type', 'College')->first()->period;
            $idno = Input::get("idno");
            $plan = Input::get("plan");
            $discount_code = Input::get("discount");
            $level = Input::get("level");
            $type_of_account = Input::get("type_of_account");
            $program_code = Input::get("program_code");

            $this->deletecurrentledgers($idno, $school_year, $period);
            $this->deleteledgerduedate($idno, $school_year, $period);

            if (!is_null($discount_code)) {
                $discounttype = \App\CtrDiscount::where('discount_code', $discount_code)->first()->discount_type;
                if ($discounttype == 0) {
                    $discounttf = $this->getdiscountrate('tf', $discount_code);
                    $discountof = $this->getdiscountrate('of', $discount_code);
                } else if ($discounttype == 1) {
                    $discounttf = $this->getdiscount('tf', $discount_code);
                }
            }

            if ($type_of_account == "Regular") {
                $tfr = \App\CtrCollegeTuitionFee::where('program_code', $program_code)->where('level', $level)->first();
                $tuitionrate = $tfr->per_unit;
                $otherfee = $this->getOtherFee($idno, $school_year, $period, $level, $program_code, $discountof, $discount_code);
                $tuitionfee = $this->getCollegeTuition($idno, $school_year, $period, $level, $program_code, $tuitionrate, $plan, $discounttf, $discountof, $discount_code, $discounttype);
                $ledger_due_date = $this->computeLedgerDueDate($idno, $school_year, $period, $plan);

                $this->changeStatus($school_year, $period, $plan, $type_of_account, $idno, $discount_code);
                $this->checkReservations($idno, $school_year, $period);

                $totalFee = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->sum('amount');
                $totalDiscount = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->sum('discount');
                $tuition = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 5)->sum('amount');
                $tuitionDiscount = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 5)->sum('discount');
                $misc = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 1)->sum('amount');
                $miscDiscount = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 1)->sum('discount');
                $other = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 2)->sum('amount');
                $otherDiscount = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 2)->sum('discount');
                $depo = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 3)->sum('amount');
                $depoDiscount = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 3)->sum('discount');
                $srf = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 4)->sum('amount');
                $srfDiscount = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 4)->sum('discount');

                return view('reg_college.assessment.ajax.display_result', compact('idno', 'totalFee', 'tuition', 'misc', 'other', 'depo', 'srf', 'totalDiscount', 'tuitionDiscount', 'miscDiscount', 'otherDiscount', 'depoDiscount', 'srfDiscount'));
            }
        }
    }

    function deletecurrentledgers($idno, $school_year, $period) {
        $currentledgers = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->get();
        if (count($currentledgers) > 0) {
            foreach ($currentledgers as $currentledger) {
                $currentledger->delete();
            }
        }
    }

    function deleteledgerduedate($idno, $school_year, $period) {
        $deleteledgerduedates = \App\LedgerDueDate::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->get();
        if (count($deleteledgerduedates) > 0) {
            foreach ($deleteledgerduedates as $deleteledgerduedate) {
                $deleteledgerduedate->delete();
            }
        }
    }

    function getdiscountrate($type, $discount_code) {
        if ($type == 'tf') {
            return \App\CtrDiscount::where('discount_code', $discount_code)->first()->tuition_fee;
        } elseif ($type == 'of') {
            return \App\CtrDiscount::where('discount_code', $discount_code)->first()->other_fee;
        }
    }

    function getdiscount($type, $discount_code) {
        if ($type == 'tf') {
            return \App\CtrDiscount::where('discount_code', $discount_code)->first()->amount;
        }
    }

    function getCollegeTuition($idno, $school_year, $period, $level, $program_code, $tuitionrate, $plan, $discounttf, $discountof, $discount_code, $discounttype) {
        $grades = \App\GradeCollege::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->get();

        $interest = $this->getInterest($plan);
        $tuitionfee = 0;
        $tobediscount = 0;
        foreach ($grades as $grade) {
            if ($discounttype == 0) {
                $tobediscount = $tobediscount + ((((($grade->lec + $grade->lab) * $tuitionrate * $grade->percent_tuition / 100)) * ($discounttf / 100)) * $interest);
            } else if ($discounttype == 1) {
                $tobediscount = $tobediscount + $discounttf;
            }

            $tuitionfee = $tuitionfee + (((($grade->lec + $grade->lab) * $tuitionrate * $grade->percent_tuition / 100)) * $interest);
        }
        $addledger = new \App\ledger;
        $addledger->idno = $idno;
        $addledger->department = \App\CtrAcademicProgram::where('program_code', $program_code)->first()->department;
        $addledger->program_code = $program_code;
        $addledger->level = $level;
        $addledger->school_year = $school_year;
        $addledger->period = $period;
        $addledger->category = "Tuition Fees Receivable";
        $addledger->subsidiary = "Tuition Fees Receivable";
        $addledger->receipt_details = "Tuition Fees Receivable";
        $addledger->accounting_code = env("AR_TUITION_CODE");
        $addledger->accounting_name = env("AR_TUITION_NAME");
        $addledger->category_switch = "5";
        $addledger->amount = $tuitionfee;
        $addledger->discount = $tobediscount;
        $addledger->discount_code = $discount_code;
        $addledger->save();
    }

    function getInterest($plan) {
        if ($plan == "Cash") {
            $interest = 1;
        } else if ($plan == "Quarterly") {
            $interest = 1.02;
        } else if ($plan == "Monthly") {
            $interest = 1.03;
        }
        return $interest;
    }

    function getOtherFee($idno, $school_year, $period, $level, $program_code, $discountof, $discount_code) {
        $otherfees = \App\CtrCollegeOtherFee::where('program_code', $program_code)->where('level', $level)->where('period', $period)->get();
        if (count($otherfees) > 0) {
            foreach ($otherfees as $otherfee) {
                $addledger = new \App\ledger;
                $addledger->idno = $idno;
                $addledger->department = \App\CtrAcademicProgram::where('program_code', $program_code)->first()->department;
                $addledger->program_code = $program_code;
                $addledger->level = $level;
                $addledger->school_year = $school_year;
                $addledger->period = $period;
                $addledger->category = $otherfee->category;
                $addledger->subsidiary = $otherfee->subsidiary;
                $addledger->receipt_details = $otherfee->receipt_details;
                $addledger->accounting_code = $otherfee->accounting_code;
                $addledger->accounting_name = $this->getAccountingName($otherfee->accounting_code);
                $addledger->category_switch = $otherfee->category_switch;
                $addledger->amount = $otherfee->amount;
                $addledger->discount = $otherfee->amount * ($discountof / 100);
                $addledger->discount_code = $discount_code;
                $addledger->save();
            }
        }
    }

    function computeLedgerDueDate($idno, $school_year, $period, $plan) {
        $status = \App\Status::where('idno', $idno)->first();
        $due_dates = \App\CtrDueDate::where('academic_type', $status->academic_type)->where('plan', $plan)->where('level', $status->level)->get();
        $totalTuition = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 5)->sum('amount');
        $totalOtherFees = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', '!=', 5)->sum('amount');
        $totalTuitionDiscount = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', 5)->sum('discount');
        $totalOtherFeesDiscount = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->where('category_switch', '!=', 5)->sum('discount');
        $totalFees = ($totalTuition + $totalOtherFees) - ($totalTuitionDiscount + $totalOtherFeesDiscount);
        $downpaymentamount = (($totalTuition - $totalTuitionDiscount) / 2) + ($totalOtherFees - $totalOtherFeesDiscount);
        if ($plan == 'Cash') {
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
                $addledgerduedates = new \App\LedgerDueDate;
                $addledgerduedates->idno = $idno;
                $addledgerduedates->school_year = $school_year;
                $addledgerduedates->period = $period;
                $addledgerduedates->due_switch = 1;
                $addledgerduedates->due_date = $paln->due_date;
                $addledgerduedates->amount = $this->computeplan($downpaymentamount, $totalFees, $due_dates);
                $addledgerduedates->save();
            }
        }
    }

    function computeplan($downpaymentamount, $totalFees, $due_dates) {
        $planpayment = ($totalFees - $downpaymentamount) / count($due_dates);
        return $planpayment;
    }

    function changeStatus($school_year, $period, $plan, $type_of_account, $idno, $discount_code) {
        $changestatus = \App\Status::where('idno', $idno)->first();
        $changestatus->status = 2;
        $changestatus->type_of_account = $type_of_account;
        $changestatus->type_of_plan = $plan;
        $changestatus->school_year = $school_year;
        $changestatus->period = $period;
        $changestatus->type_of_discount = $discount_code;
        $changestatus->save();
    }

    function checkReservations($idno, $school_year, $period) {
        $checkreservations = \App\Reservation::where('idno', $idno)->where('is_consumed', 0)->where('is_reverse', 0)->first();
        if (count($checkreservations) > 0) {
            DB::beginTransaction();
            $reservation_id = $checkreservations->id;
            $reference_id = uniqid();
            $reservationAmount = $checkreservations->amount;
            $this->postCredit($idno, $reference_id, $reservationAmount, $school_year, $period);
            $this->postDebit($idno, $reference_id, $reservationAmount);
            //$this->postDiscount($idno, $reference_id, $reservationAmount);
            $this->postDebitMemo($idno, $reference_id, $reservationAmount);
            $this->updateLedger($idno, $reservationAmount, $school_year, $period);
            $this->updateReservation($idno, $reservation_id);
            //$this->updateStatus($idno);
            DB::commit();
        }
    }

    function postCredit($idno, $reference_id, $reservationAmount, $school_year, $period) {
        $fiscal_year = \App\CtrFiscalYear::first()->fiscal_year;
        $ledgers = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->get();
        foreach ($ledgers as $ledger) {

            if ($reservationAmount != 0) {

                if ($ledger->debit_memo == 0 && $ledger->payment == 0) {
                    
                } else {
                    $addaccounting = new \App\Accounting;

                    $total = $ledger->amount - $ledger->payment - $ledger->discount - $ledger->esc - $ledger->debit_memo;
                    if ($total < $reservationAmount) {
                        $addaccounting->credit = ($reservationAmount - ($ledger->debit_memo + ($reservationAmount - $total)));
                        //$addaccounting->save();
                        $reservationAmount = $reservationAmount - $total;
                    } else if ($total >= $reservationAmount) {
                        $addaccounting->credit = $ledger->debit_memo + ($reservationAmount);
                        //$addaccounting->save();
                        $reservationAmount = 0;
                    }
                    $addaccounting->transaction_date = date('Y-m-d');
                    $addaccounting->reference_id = $reference_id;
                    $addaccounting->accounting_type = 2;
                    $addaccounting->category = $ledger->category;
                    $addaccounting->subsidiary = $ledger->subsidiary;
                    $addaccounting->receipt_details = $ledger->receipt_details;
                    $addaccounting->accounting_code = $ledger->accounting_code;
                    $addaccounting->accounting_name = $ledger->accounting_name;
                    $addaccounting->fiscal_year = $fiscal_year;
                    $addaccounting->posted_by = Auth::user()->idno;
                    $addaccounting->save();
                }
            }
        }
    }

    function postDebit($idno, $reference_id, $reservationAmount) {
        $fiscal_year = \App\CtrFiscalYear::first()->fiscal_year;

        $addaccounting = new \App\Accounting;
        $addaccounting->transaction_date = date('Y-m-d');
        $addaccounting->reference_id = $reference_id;
        $addaccounting->accounting_type = 2;
        $addaccounting->category = "Reservation";
        $addaccounting->subsidiary = $idno;
        $addaccounting->receipt_details = "Reservation";
        $addaccounting->particular = "Reservation";
        $addaccounting->accounting_code = "210400";
        $addaccounting->accounting_name = "Student Reservation";
        $addaccounting->fiscal_year = $fiscal_year;
        $addaccounting->debit = $reservationAmount;
        $addaccounting->posted_by = Auth::user()->idno;
        $addaccounting->save();
    }

    function postDebitMemo($idno, $reference_id, $reservationAmount) {
        $adddebitmemo = new \App\DebitMemo;
        $adddebitmemo->idno = $idno;
        $adddebitmemo->transaction_date = date('Y-m-d');
        $adddebitmemo->reference_id = $reference_id;
        $adddebitmemo->dm_no = $this->getdmno();
        $adddebitmemo->explanation = "Reversal of Reservation";
        $adddebitmemo->amount = $reservationAmount;
        $adddebitmemo->posted_by = Auth::user()->idno;
        $adddebitmemo->save();
    }

    function getdmno() {
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

    function updateReservation($idno, $reservation_id) {
        $updatereservation = \App\Reservation::where('idno', $idno)->where('id', $reservation_id)->first();
        $updatereservation->is_consumed = 1;
        $updatereservation->save();
    }

    function updateLedger($idno, $reservationAmount, $school_year, $period) {
        $ledgers = \App\Ledger::where('idno', $idno)->where('school_year', $school_year)->where('period', $period)->get();
        foreach ($ledgers as $ledger) {
            $total = $ledger->amount - $ledger->payment - $ledger->discount - $ledger->esc - $ledger->debit_memo;
            if ($reservationAmount != 0) {
                if ($total < $reservationAmount) {
                    $ledger->debit_memo = ($reservationAmount - ($ledger->debit_memo + ($reservationAmount - $total)));
                    $ledger->save();
                    $reservationAmount = $reservationAmount - $total;
                } else if ($total >= $reservationAmount) {
                    $ledger->debit_memo = $ledger->debit_memo + ($reservationAmount);
                    $ledger->save();
                    $reservationAmount = 0;
                }
            }
        }
    }

    function updateStatus($idno) {
        $updateStatus = \App\Status::where('idno', $idno)->first();
        $updateStatus->status = 3;
        $updateStatus->save();
    }
    
    function getAccountingName($accounting_code){
        $accounting_name = \App\ChartOfAccount::where('accounting_code', $accounting_code)->first()->accounting_name;
        return $accounting_name;
    }

}
