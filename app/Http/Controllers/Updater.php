<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;

class Updater extends Controller {

    function removeDM() {
        //1920249
        //2021200
        DB::beginTransaction();
        $checkRecord = \App\UpdateLedger::where('subsidiary', "Computerized I.D.")->where('is_done', 102)->get();
        foreach ($checkRecord as $record) {
            $getDM = \App\DebitMemo::where('idno',$record->idno)->where('transaction_date', "2020-12-11")->where('explanation',"Computerized I.D. - Refund")->where('posted_by', 999999)->first();
            $getAccounting = \App\Accounting::where('reference_id',$getDM->reference_id)->where('transaction_date', "2020-12-11")->where('posted_by', 999999)->get();
            if (count($getAccounting) == 1) {
                //Remove accounting
                $deleteAccounting = \App\Accounting::where('reference_id', $getDM->reference_id)->first();
                $deleteAccounting->delete();
                
                //Remove DM
                $deleteDM = \App\DebitMemo::where('reference_id',$getDM->reference_id)->first();
                $deleteDM->delete();
                
                $record->is_done = 103;
                $record->save();
            }
        }
        DB::Commit();
        return "DONE";
    }

    function updateComputerizedID() {
        $getAll = \App\Ledger::where('school_year', 2020)->where('subsidiary', "Computerized I.D.")->get();
        DB::beginTransaction();
        foreach ($getAll as $record) {
            $checkRecord = \App\UpdateLedger::where('idno', $record->idno)->where('subsidiary', "Computerized I.D.")->where('is_done', '>=',103)->get();
            if (count($checkRecord) == 1) {
                $checkbalance = \App\Ledger::selectRaw('idno,sum(amount) as amount, sum(payment) as payment, sum(discount) as discount, sum(debit_memo) as debit_memo')->where('idno', $record->idno)->first();
                if (($checkbalance->amount - ($checkbalance->payment + $checkbalance->debit_memo + $checkbalance->discount)) == 0) {
                    //no balance
                    $this->processAddtoSD($record);
                    $done = 104;
                } elseif ($record->discount == 200) {
                    //discounted
                    $done = 101;
                } else {
                    //with balance
                    $this->processDM($record);
                    $done = 102;
                }
                $addRecord = new \App\UpdateLedger();
                $addRecord->idno = $record->idno;
                $addRecord->level = $record->level;
                $addRecord->subsidiary = "Computerized I.D.";
                $addRecord->amount = 200;
                $addRecord->is_done = $done;
                $addRecord->save();
            }
        }
        DB::Commit();

        return "DONE";
    }

    function processAddtoSD($record) {
        $reference_id = uniqid();
        $this->postAccounting($record, $reference_id);
        $this->postDebit($record, $reference_id);
        $this->postStudentDeposit($record, $reference_id);
        $this->postSD($record, $reference_id);
    }

    function postAccounting($record, $reference_id) {
        $fiscal_year = \App\CtrFiscalYear::first()->fiscal_year;
        $dept = \App\CtrAcademicProgram::where('level', $record->level)->first();
        if (count($dept) > 0) {
            $department = $dept->department;
        } else {
            $department = "None";
        }
        $addaccounting = new \App\Accounting;
        $addaccounting->transaction_date = date('Y-m-d');
        $addaccounting->reference_id = $reference_id;
        $addaccounting->accounting_type = env("STUDENT_DEPOSIT");
        $addaccounting->category = "Student Deposit";
        $addaccounting->subsidiary = $record->idno;
        $addaccounting->receipt_details = "Student Deposit";
        $addaccounting->particular = "Student Deposit";
        $addaccounting->department = $department;
        $addaccounting->accounting_code = env("STUDENT_DEPOSIT_CODE");
        $addaccounting->accounting_name = env("STUDENT_DEPOSIT_NAME");
        $addaccounting->fiscal_year = $fiscal_year;
        $addaccounting->credit = 200;
        $addaccounting->posted_by = 999999;
        $addaccounting->save();
    }

    function postDebit($record, $reference_id) {
        $department = \App\Status::where('idno', $record->idno)->first()->department;
        $fiscal_year = \App\CtrFiscalYear::first()->fiscal_year;
        $addacct = new \App\Accounting;
        $addacct->transaction_date = date('Y-m-d');
        $addacct->reference_id = $reference_id;
        $addacct->accounting_type = env("STUDENT_DEPOSIT");
        $addacct->category = "Student Fees Receivable";
        $addacct->subsidiary = "Computerized I.D.";
        $addacct->receipt_details = "Student Fees Receivable";
        $addacct->particular = "Computerized I.D. - Refund";
        $addacct->accounting_code = 1201;
        $addacct->department = $department;
        $addacct->accounting_name = "Student Fees Receivable";
        $addacct->fiscal_year = $fiscal_year;
        $addacct->debit = 200;
        $addacct->posted_by = 999999;
        $addacct->save();
    }

    function postStudentDeposit($record, $reference_id) {
        $addreservation = new \App\Reservation;
        $addreservation->idno = $record->idno;
        $addreservation->reference_id = $reference_id;
        $addreservation->transaction_date = date('Y-m-d');
        $addreservation->amount = 200;
        $addreservation->reservation_type = 2;
        $addreservation->posted_by = 999999;
        $addreservation->save();
    }

    function postSD($record, $reference_id) {
        $adddm = new \App\AddToStudentDeposit;
        $adddm->idno = $record->idno;
        $adddm->transaction_date = date('Y-m-d');
        $adddm->reference_id = $reference_id;
        $adddm->sd_no = $this->getReceipt();
        $adddm->explanation = "Computerized I.D. - Refund";
        $adddm->amount = 200;
        $adddm->posted_by = 999999;
        $adddm->school_year = $record->school_year;
        $adddm->period = $record->period;
        $adddm->save();
    }

    function getReceipt() {
        $id = \App\ReferenceId::where('idno', 999999)->first()->id;
        $number = \App\ReferenceId::where('idno', 999999)->first()->sd_no;
        $receipt = "";
        for ($i = strlen($number); $i <= 6; $i++) {
            $receipt = $receipt . "0";
        }
        return $id . $receipt . $number;
    }

    function getReceiptDM() {
        $id = \App\ReferenceId::where('idno', 999999)->first()->id;
        $number = \App\ReferenceId::where('idno', 999999)->first()->dm_no;
        $receipt = "";
        for ($i = strlen($number); $i <= 6; $i++) {
            $receipt = $receipt . "0";
        }
        return $id . $receipt . $number;
    }

    function processDM($record) {
        $reference_id = uniqid();
        $this->postDM($record, $reference_id);
        $this->processAccounting($record, $reference_id);
        $this->postDebitEntry($record, $reference_id);
        $this->updateDM();
    }

    function postDM($record, $reference_id) {
        $adddm = new \App\DebitMemo;
        $adddm->idno = $record->idno;
        $adddm->transaction_date = date('Y-m-d');
        $adddm->reference_id = $reference_id;
        $adddm->dm_no = $this->getReceiptDM();
        $adddm->explanation = "Computerized I.D. - Refund";
        $adddm->amount = 200;
        $adddm->posted_by = 999999;
        $status = \App\Status::where('idno', $record->idno)->first();
        $adddm->school_year = $record->school_year;
        if ($status->level == "Grade 11" || $status->level == "Grade 12") {
            $adddm->period = $record->period;
        } else {
            $adddm->period = "";
        }
        $adddm->save();
    }

    function postDebitEntry($record, $reference_id) {
        $department = \App\Status::where('idno', $record->idno)->first()->department;
        $fiscal_year = \App\CtrFiscalYear::first()->fiscal_year;
        $addacct = new \App\Accounting;
        $addacct->transaction_date = date('Y-m-d');
        $addacct->reference_id = $reference_id;
        $addacct->accounting_type = env("DEBIT_MEMO");
        $addacct->category = "Student Fees Receivable";
        $addacct->subsidiary = "Computerized I.D.";
        $addacct->receipt_details = "Student Fees Receivable";
        $addacct->particular = "Computerized I.D. - Refund";
        $addacct->accounting_code = 1201;
        $addacct->department = $department;
        $addacct->accounting_name = "Student Fees Receivable";
        $addacct->fiscal_year = $fiscal_year;
        $addacct->debit = 200;
        $addacct->posted_by = 999999;
        $addacct->save();
    }

    function updateDM() {
        $dm = \App\ReferenceId::where('idno', 999999)->first();
        $dm->dm_no = $dm->dm_no + 1;
        $dm->update();
    }

    function processAccounting($record, $reference_id) {
        $totalpayment = 200;
        $accounting_type = env("DEBIT_MEMO");
        $ledgers = \App\Ledger::where('idno', $record->idno)->whereRaw('amount-debit_memo-discount-payment > 0')->where('category_switch', '<=', env("TUITION_FEE"))->get();
        $fiscal_year = \App\CtrFiscalYear::first()->fiscal_year;
        $totalamount = 0;
        if (count($ledgers) > 0) {
            foreach ($ledgers as $ledger) {
                if ($totalpayment > 0) {
                    if ($totalpayment >= $ledger->amount - $ledger->discount - $ledger->debit_memo - $ledger->payment) {
                        $amount = $ledger->amount - $ledger->discount - $ledger->debit_memo - $ledger->payment;
                        if ($accounting_type == env("DEBIT_MEMO")) {
                            $ledger->debit_memo = $ledger->debit_memo + $amount;
                        } else {
                            $ledger->payment = $ledger->payment + $amount;
                        }
                        $ledger->update();

                        $addacct = new \App\Accounting;
                        $addacct->transaction_date = date('Y-m-d');
                        $addacct->reference_id = $reference_id;
                        $addacct->reference_number = $ledger->id;
                        $addacct->accounting_type = $accounting_type;
                        $addacct->category = $ledger->category;
                        $addacct->subsidiary = $ledger->subsidiary;
                        $addacct->receipt_details = $ledger->receipt_details;
                        $addacct->particular = $ledger->receipt_details;
                        $addacct->accounting_code = $ledger->accounting_code;
                        $addacct->accounting_name = $ledger->accounting_name;
                        $addacct->department = $ledger->department;
                        $addacct->fiscal_year = $fiscal_year;
                        $addacct->credit = $amount;
                        $addacct->posted_by = 999999;
                        $addacct->save();
                        $totalamount = $totalamount + $amount;
                        $totalpayment = $totalpayment - $amount;
                    } else {
                        if ($totalpayment > 0) {
                            $ledger->debit_memo = $ledger->debit_memo + $totalpayment;
                            $ledger->update();
                            $addacct = new \App\Accounting;
                            $addacct->transaction_date = date('Y-m-d');
                            $addacct->reference_id = $reference_id;
                            $addacct->reference_number = $ledger->id;
                            $addacct->accounting_type = $accounting_type;
                            $addacct->category = $ledger->category;
                            $addacct->subsidiary = $ledger->subsidiary;
                            $addacct->receipt_details = $ledger->receipt_details;
                            $addacct->particular = $ledger->receipt_details;
                            $addacct->accounting_code = $ledger->accounting_code;
                            $addacct->accounting_name = $ledger->accounting_name;
                            $addacct->fiscal_year = $fiscal_year;
                            $addacct->credit = $totalpayment;
                            $addacct->posted_by = 999999;
                            $addacct->save();
                            $totalamount = $totalamount + $totalpayment;
                            $totalpayment = 0;
                        }
                    }
                }
            }
        }
    }

}
