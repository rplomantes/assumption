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
            // if ($status->type_of_plan != 'Plan A') {
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

            $update->is_done = 10;
            $update->save();
            // }
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
        $adddm->explanation = "Debit Memo - " . $subsidiary;
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

    function update_reverserestore() {
        $or = DB::select("SELECT * FROM `update_or` WHERE `is_reverse` = 0 ");
        foreach ($or as $o) {
            $reference_id = $o->reference_id;
            DB::beginTransaction();
            //$this->checkifreservation($reference_id);
            $this->reverserestore_ledger($reference_id, env("CASH"));
            $this->reverserestore_entries(\App\Payment::where('reference_id', $reference_id)->get(), $reference_id);
            $this->reverserestore_entries(\App\Accounting::where('reference_id', $reference_id)->get(), $reference_id);
            $this->reverserestore_entries(\App\Reservation::where('reference_id', $reference_id)->get(), $reference_id);
            \App\Http\Controllers\Admin\Logs::log("Reverse/Restore receipt with reference no: $reference_id.");
            $this->deleteAccounting($reference_id);
            $this->deletePayment($reference_id);
            DB::commit();
        }
        return "Done";
    }

    function deleteAccounting($reference_id) {
        $accounting = \App\Accounting::where('reference_id', $reference_id)->get();
        if (count($accounting) > 0) {
            foreach ($accounting as $acc) {
                $acc->delete();
            }
        }
    }

    function deletePayment($reference_id) {
        $accounting = \App\Payment::where('reference_id', $reference_id)->get();
        if (count($accounting) > 0) {
            foreach ($accounting as $acc) {
                $acc->delete();
            }
        }
    }

    function reverserestore_ledger($reference_id, $entry_type) {
        $accountings = \App\Accounting::where('reference_id', $reference_id)->where('credit', '>', '0')->where('accounting_type', $entry_type)->get();
        if (count($accountings) > 0) {
            foreach ($accountings as $accounting) {
                $ledger = \App\Ledger::find($accounting->reference_number);
                if (count($ledger) > 0) {
                    if ($accounting->is_reverse == 0) {
                        $ledger->payment = $ledger->payment - $accounting->credit;
                    } else {
                        $ledger->payment = $ledger->payment + $accounting->credit;
                    }
                    $ledger->update();
                }
            }
        }
    }

    function reverserestore_entries($obj, $reference_id) {
        if (count($obj) > 0) {
            foreach ($obj as $ob) {
                if ($ob->is_reverse == "0") {
                    $ob->is_reverse = "1";
                } else {
                    $ob->is_reverse = "1";
                }
                $ob->update();
            }
        }
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

    function updateStudentDevFee() {
        DB::beginTransaction();
        $lists = \App\Ledger::where('school_year', 2020)->where('subsidiary', 'Student Development Fee')->where('amount', 1000)->where('department', "Senior High School")->get();
        foreach ($lists as $list) {
            $list->amount = 750;
            $list->update();

            $this->add_change_plan($list);
//            $this->update_plan($list);
            $this->change_due_date($list);
        }
        DB::Commit();
        echo "Done";
    }

    function add_change_plan($request) {
        $originalplan = \App\Status::where('idno', $request->idno)->first()->type_of_plan;
        $changeplan = $originalplan;
        $orginalamount = \App\Ledger::where('idno', $request->idno)->where('category_switch', env("TUITION_FEE"))->first();
        $tuition = \App\CtrBedFee::where('level', $request->level)->where('category_switch', env("TUITION_FEE"))->first()->amount;
        $changeamount = $tuition + ($tuition * ($this->addPercentage($originalplan) / 100));
        $notchangeamount = $tuition;
        $addchange = new \App\ChangePlan;
        $addchange->idno = $request->idno;
        $addchange->change_date = Date('Y-m-d');
        $addchange->original_plan = $originalplan;
        $addchange->change_plan = $changeplan;
        $addchange->original_amount = $orginalamount->amount;
        $addchange->change_amount = $this->roundOff($changeamount);
        $addchange->posted_by = 'kanastacio';
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

//    function update_plan($request) {
//        $status = \App\Status::where('idno', $request->idno)->first();
//        $bedlevel = \App\BedLevel::where('idno', $request->idno)->where('school_year', $status->school_year)->where('period', $status->period)->first();
//        $status->type_of_plan = $status->plan;
//        $status->update();
//        $bedlevel->type_of_plan = $status->plan;
//        $bedlevel->update();
//    }

    function change_due_date($request) {
        $stat = \App\Status::where('idno', $request->idno)->first();
        $schoolyear = $stat->school_year;
        $period = $stat->period;

        $deltedue = \App\LedgerDueDate::where('idno', $request->idno)->where('school_year', $schoolyear)->where('period', $period)->delete();
        $this->addDueDates($request, $schoolyear, $period);
    }

    function addPercentage($plan) {
        $interest = \App\CtrBedPlan::where('plan', $plan)->first()->interest;
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

    function updateCollegeLedger() {
//        $subsidiary1 = "Placement";
//        $ledgers1 = \App\Ledger::where('subsidiary', $subsidiary1)->where('school_year', 2020)->where('program_code', "!=", null)->get();

//        $subsidiary2 = "Accident Insurance";
//        $ledgers2 = \App\Ledger::where('subsidiary', $subsidiary2)->where('school_year', 2020)->where('program_code', "!=", null)->get();

        $subsidiary3 = "AAA Membership Fee";
        $ledgers3 = \App\Ledger::where('subsidiary', $subsidiary3)->where('school_year', 2020)->where('program_code', "!=", null)->get();

        DB::beginTransaction();
//        foreach ($ledgers1 as $ledger1) {
//            $ledger1->amount = 0;
//            $ledger1->save();
//        }
//
//        foreach ($ledgers2 as $ledger2) {
//            $ledger2->amount = 0;
//            $ledger2->save();
//        }

        foreach ($ledgers3 as $ledger3) {
            $ledger3->amount = 0;
            $ledger3->save();
            
            
            $student = \App\Status::where('idno', $ledger3->idno)->first();
            $this->changePlan($student);
        }
        
//        $students = \App\Status::where('academic_type','College')->where('school_year', 2020)->where('period','1st Semester')->where('type_of_plan',"!=", 'Plan A')->where('status',env('ENROLLED'))->get();
//        $students = \App\Status::where('academic_type','College')->where('school_year', 2020)->where('period','1st Semester')->where('status',env('ENROLLED'))->get();

//        foreach ($students as $student){
//            $this->changePlan($student);
//        }
        DB::Commit();
        return "DONE";
    }

    function changePlan($student) {
        $this->college_add_change_plan($student);
        $this->college_update_plan($student);
        $this->college_change_due_date($student);
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
        $changeplan = $request->type_of_plan;
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
        $addchange->posted_by = 'sronquillo';
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
            }
            $updateledger = \App\Ledger::where('idno', $request->idno)->where('level', $stat->level)->where('school_year', $school_year)->where('period', $period)->where('category_switch', env('TUITION_FEE'))->first();
            $updateledger->amount = $updateledger->amount + $addamount;
            $updateledger->save();
        }
        
    }

    function college_update_plan($request) {
        $status = \App\Status::where('idno', $request->idno)->first();
        $bedlevel = \App\CollegeLevel::where('idno', $request->idno)->where('school_year', $status->school_year)->where('period', $status->period)->first();
        $status->type_of_plan = $request->type_of_plan;
        $status->update();
        $bedlevel->type_of_plan = $request->type_of_plan;
        $bedlevel->update();
    }

    function college_change_due_date($request) {
        $stat = \App\Status::where('idno', $request->idno)->first();
        $schoolyear = $stat->school_year;
        $period = $stat->period;

        $deltedue = \App\LedgerDueDate::where('idno', $request->idno)->where('school_year', $schoolyear)->where('period', $period)->delete();
        $this->computeLedgerDueDates($request->idno, $schoolyear, $period, $stat->type_of_plan);
    }

    function getdiscount($type, $discount_code, $idno) {
        if ($type == 'tf') {
            return \App\CollegeScholarship::where('idno', $idno)->where('discount_code', $discount_code)->first()->amount;
        }
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

}
