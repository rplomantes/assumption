<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;

class Updater extends Controller {

    //
    function updateLedgerSenior() {
        $updates = \App\UpdateLedger::where('is_done', 0)->get();
        DB::beginTransaction();
        foreach ($updates as $update) {
            $status = \App\Status::where('idno', $update->idno)->first();
            if ($status->type_of_plan != 'Plan A') {
                $delete_ledger = \App\Ledger::where('idno', $update->idno)->where('subsidiary', $update->subsidiary)->get();

                $idno = $update->idno;
                $school_year = $status->school_year;
                $period = $status->period;

                $levels_reference_id = uniqid();

                $totalpayment = $update->amount;
                $reference_id = uniqid();
                $ledgers = \App\Ledger::where('idno', $idno)->whereRaw('amount-debit_memo-discount-payment > 0')->where('category_switch', '<=', env("TUITION_FEE"))->get();

                $this->postDM($reference_id, $totalpayment, $idno, $update->subsidiary);
                $this->processAccounting($reference_id, $totalpayment, $ledgers, env("DEBIT_MEMO"));
                $this->postDebitEntry($idno, $reference_id, $totalpayment, $levels_reference_id, $school_year, $period, $delete_ledger);

//                foreach ($delete_ledger as $del) {
//                    $del->delete();
//                }

                $update->is_done = 1;
                $update->save();
            }
        }
        DB::Commit();

        return "DONE";
    }

    function postDM($reference_id, $totalpayment, $idno, $subsidiary) {
        $adddm = new \App\DebitMemo;
        $adddm->idno = $idno;
        $adddm->transaction_date = date('Y-m-d');
        $adddm->reference_id = $reference_id;
        $adddm->dm_no = $this->getDMNumber();
        $adddm->explanation = "Debit Memo - ". $subsidiary;
        $adddm->amount = $totalpayment;
        $adddm->posted_by = 999991;
        $adddm->save();
    }

    function postDebitEntry($idno, $reference_id, $totalpayment, $levels_reference_id, $school_year, $period, $ledgers) {

        $department = \App\Status::where('idno', $idno)->first()->department;
        $fiscal_year = \App\CtrFiscalYear::first()->fiscal_year;
        foreach ($ledgers as $ledger) {
            $addacct = new \App\Accounting;
            $addacct->transaction_date = date('Y-m-d');
            $addacct->reference_id = $reference_id;
            $addacct->accounting_type = env("DEBIT_MEMO");
            $addacct->category = $ledger->category;
            $addacct->subsidiary = $ledger->subsidiary;
            $addacct->receipt_details = $ledger->receipt_details;
            $addacct->particular = $ledger->receipt_details;
            $addacct->accounting_code = $ledger->accounting_code;
            $addacct->department = $department;
            $addacct->accounting_name = $this->getAccountingName($ledger->accounting_code);
            $addacct->fiscal_year = $fiscal_year;
            $addacct->debit = $totalpayment;
            $addacct->posted_by = 999991;
            $addacct->save();
        }
    }

    function getAccountingName($accounting_code) {
        $acctname = \App\ChartOfAccount::where('accounting_code', $accounting_code)->first();
        return $acctname->accounting_name;
    }

    function updateReservation() {
        $updates = \App\UpdateReservations::where('is_done', 0)->get();
        DB::beginTransaction();
        foreach ($updates as $update) {
            $status = \App\Status::where('idno', $update->idno)->first();

            $idno = $update->idno;
            $school_year = $status->school_year;
            $period = $status->period;

            $this->checkReservations($idno, $school_year, $period);
            $update->is_done = 1;
            $update->save();
        }
        DB::Commit();

        return "DONE";
    }

    function checkReservations($idno, $school_year, $period) {
        $levels_reference_id = uniqid();
        $checkreservations = \App\Reservation::where('idno', $idno)->where('is_consumed', 0)->where('is_reverse', 0)->selectRaw('sum(amount) as amount')->first();
        if ($checkreservations->amount > 0) {
            $totalpayment = $checkreservations->amount;
            $reference_id = uniqid();
            $ledgers = \App\Ledger::where('idno', $idno)->whereRaw('amount-debit_memo-discount-payment > 0')->where('category_switch', '<=', env("TUITION_FEE"))->get();

            $this->processAccounting($reference_id, $totalpayment, $ledgers, env("DEBIT_MEMO"));
            $this->postDebit($idno, $reference_id, $totalpayment, $levels_reference_id, $school_year, $period);

//            $changestatus = \App\Status::where('idno', $idno)->first();
//            $changestatus->status = env("ENROLLED");
//            $changestatus->update();
            $changereservation = \App\Reservation::where('idno', $idno)->get();
            if (count($changereservation) > 0) {
                foreach ($changereservation as $change) {
                    $change->levels_reference_id = $levels_reference_id;
                    $change->is_consumed = '1';
                    $change->consume_sy = $school_year;
                    $change->update();
                }
            }
        }
    }

    function processAccounting($reference_id, $totalpayment, $ledgers, $accounting_type) {
        $fiscal_year = \App\CtrFiscalYear::first()->fiscal_year;
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
                        $addacct->posted_by = 999991;
                        $addacct->save();
                        $totalpayment = $totalpayment - $amount;
                    } else {
                        if ($totalpayment > 0) {
                            if ($accounting_type == env("DEBIT_MEMO")) {
                                $ledger->debit_memo = $ledger->debit_memo + $totalpayment;
                            } else {
                                $ledger->payment = $ledger->payment + $totalpayment;
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
                            $addacct->fiscal_year = $fiscal_year;
                            $addacct->credit = $totalpayment;
                            $addacct->posted_by = 999991;
                            $addacct->save();
                            $totalpayment = 0;
                        }
                    }
                }
            }
        }
    }

    function postDebit($idno, $reference_id, $totalpayment, $levels_reference_id, $school_year, $period) {
        $fiscal_year = \App\CtrFiscalYear::first()->fiscal_year;
        $reservations = \App\Reservation::where('idno', $idno)->where('is_consumed', 0)->where('is_reverse', 0)->get();
        $department = \App\Status::where('idno', $idno)->first()->department;
        $totalReserved = 0;
        if (count($reservations) > 0) {
            foreach ($reservations as $ledger) {
                $addacct = new \App\Accounting;
                $addacct->transaction_date = date('Y-m-d');
                $addacct->reference_id = $reference_id;
                //$addacct->reference_number=$ledger->id;
                $addacct->accounting_type = env("DEBIT_MEMO");
                $addacct->subsidiary = $ledger->idno;
                $dept = \App\Status::where('idno', $idno)->first();
                if (count($dept) > 0) {
                    $department = $dept->department;
                } else {
                    $department = "None";
                }
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
                $addacct->posted_by = 999991;
                $addacct->save();
                $ledger->is_consumed = 1;
                $totalReserved = $totalReserved + $ledger->amount;
            }
            $this->postDebitMemo($idno, $reference_id, $totalReserved, $levels_reference_id, $school_year, $period);
        }
    }

    function postDebitMemo($idno, $reference_id, $totalReserved, $levels_reference_id, $school_year, $period) {

        $debit_memo = new \App\DebitMemo;
        $debit_memo->idno = $idno;
        $debit_memo->levels_reference_id = $levels_reference_id;
        $debit_memo->transaction_date = date("Y-m-d");
        $debit_memo->reference_id = $reference_id;
        $debit_memo->dm_no = $this->getDMNumber();
        $debit_memo->explanation = "Reversal of Reservation/Student Deposit";
        $debit_memo->amount = $totalReserved;
        $debit_memo->reservation_sy = $school_year;
        $debit_memo->posted_by = "999991";
        $debit_memo->save();
    }

    function getDMNumber() {
        $id = \App\ReferenceId::where('idno', 999991)->first()->id;
        $number = \App\ReferenceId::where('idno', 999991)->first()->dm_no;
        $receipt = "";
        for ($i = strlen($number); $i <= 6; $i++) {
            $receipt = $receipt . "0";
        }

        $update = \App\ReferenceId::where('idno', 999991)->first();
        $update->dm_no = $update->dm_no + 1;
        $update->update();

        return $id . $receipt . $number;
    }

    function updateBedLevel() {
        $users = \App\User::where('accesslevel', 0)->where('is_first_login', 1)->where('academic_type', 'BED')->get();
        foreach ($users as $user) {
            $update = \App\User::find($user->id);
            $update->password = bcrypt(strtolower($user->idno));
            $update->update();
        }
        return "Done";
    }

    function updateCollege() {
        $users = \App\User::where('accesslevel', 0)->where('is_first_login', 1)->where('academic_type', 'College')->get();
        foreach ($users as $user) {
            $update = \App\User::find($user->id);
            $update->password = bcrypt(strtolower($user->idno));
            $update->status = 1;
            $update->update();
        }
        return "Done";
    }

    function updateInstructor() {
        $users = \App\User::where('accesslevel', 1)->where('is_first_login', 1)->get();
        foreach ($users as $user) {
            $update = \App\User::find($user->id);
            $update->password = bcrypt(strtolower($user->idno));
            $update->update();
        }
        return "Done";
    }

    /*
      $data = DB::Select("Select * from partial_student_discount");
      $notmuch="";
      foreach($data as $dat){
      $find = \App\Promotion::where('idno',$dat->idno)->first();
      if(count($find)>0){
      $find->discount = $dat->discount;
      $find->update();
      } else {
      $notmuch=$notmuch."-".$dat->idno;
      }
      }

      return $notmuch;

     */

    /*
      $students = \App\Status::where('level','Pre-Kinder')->get();
      $current_level="";
      foreach($students as $student){
      $add = new \App\Promotion;
      $add->idno=$student->idno;
      switch ($student->level){
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


      }
      $add->level = $current_level;
      $add->section = $student->section;
      $add->section = $student->section;
      $add->strand = $student->strand;
      $add->save();

      } */
    /*
      $levels = \App\Status::where('level','Kinder')->get();
      foreach($levels as $level){
      $add = new \App\BedLevel;
      $add->idno = $level->idno;
      $add->level = $level->level;
      $add->strand = $level->strand;
      $add->track = $level->track;
      $add->section = $level->section;
      $add->status =$level->status;
      $add->department = $level->department;
      $add->school_year = $level->school_year;
      $add->period = $level->period;
      $add->save();


      }
     */
    /*
      $users = \App\User::where('level','Pre-Kinder')->get();
      foreach($users as $user){
      $add = new \App\Status;
      $add->idno = $user->idno;
      $add->level = $user->level;
      $add->section = $user->section;
      $add->academic_type = "BED";
      $add->status = "3";
      if($user->is_new == "New"){
      $add->is_new=1;
      } else {
      $add->is_new=0;
      }
      $add->department = "Pre-Kinder";
      $add->school_year="2017";
      $add->save();
      } */
}
