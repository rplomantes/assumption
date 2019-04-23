<?php

namespace App\Http\Controllers\Cashier;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;

class StudentLedger extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function view($school_year,$idno) {
        $academic_type = \App\Status::where('idno', $idno)->first()->academic_type;
        if($academic_type == "College"){
            $periods = array('1st Semester', '2nd Semester', 'Summer');
        }else if($academic_type == "SHS"){
            $periods = array('1st Semester', '2nd Semester');
        }else{
            $periods = array("Yearly");
        }
        
        if (Auth::user()->accesslevel == env("CASHIER") || Auth::user()->accesslevel == env("ACCTNG_STAFF") || Auth::user()->accesslevel == env("") || Auth::user()->accesslevel==env("ACCTNG_HEAD")) {
            $due_others = 0.00;
            $due_previous = 0.00;
            $totalmainpayment = 0.00;
            $totalpay = 0.00;
            $plus = 0.00;
            $totalmaindue = 0;
            $negative = 0;
            $user = \App\User::where('idno', $idno)->first();
            $status = \App\Status::where('idno', $idno)->first();

            $ledger_main = \App\Ledger::groupBy(array('category', 'category_switch'))->where('idno', $idno)->where('category_switch', '<=', '6')
                            ->selectRaw('category, sum(amount) as amount, sum(discount) as discount, sum(debit_memo)as debit_memo, sum(payment) as payment')->orderBy('category_switch')->get();

            $ledger = \App\Ledger::SelectRaw('category_switch, category, sum(amount)as amount, sum(discount) as discount,
    sum(debit_memo) as debit_memo, sum(payment) as payment')->where('idno', $idno)
                            ->where(function($query) {
                                $query->where('category_switch', 4)
                                ->orWhere('category_switch', 5);
                            })->groupBy('category', 'category_switch')->where('category', '!=', 'SRF')->orderBy('category_switch')->get();

            $ledger_srf = \App\Ledger::SelectRaw('category_switch, category, sum(amount)as amount, sum(discount) as discount,
    sum(debit_memo) as debit_memo, sum(payment) as payment')->where('idno', $idno)
                            ->where(function($query) {
                                $query->where('category_switch', 4)
                                ->orWhere('category_switch', 5);
                            })->groupBy('category', 'category_switch')->where('category', 'SRF')->orderBy('category_switch')->get();

            $ledger_main_tuition = \App\Ledger::SelectRaw('category_switch, category, sum(amount)as amount, sum(discount) as discount,
    sum(debit_memo) as debit_memo, sum(payment) as payment')->where('idno', $idno)->where('category_switch', 6)->groupBy('category', 'category_switch')->orderBy('category_switch')->get();
            $ledger_main_misc = \App\Ledger::SelectRaw('category_switch, category, sum(amount)as amount, sum(discount) as discount,
    sum(debit_memo) as debit_memo, sum(payment) as payment')->where('idno', $idno)->where('category_switch', 1)->groupBy('category', 'category_switch')->orderBy('category_switch')->get();
            $ledger_main_other = \App\Ledger::SelectRaw('category_switch, category, sum(amount)as amount, sum(discount) as discount,
    sum(debit_memo) as debit_memo, sum(payment) as payment')->where('idno', $idno)->where('category_switch', 2)->groupBy('category', 'category_switch')->orderBy('category_switch')->get();
            $ledger_main_depo = \App\Ledger::SelectRaw('category_switch, category, sum(amount)as amount, sum(discount) as discount,
    sum(debit_memo) as debit_memo, sum(payment) as payment')->where('idno', $idno)->where('category_switch', 3)->groupBy('category', 'category_switch')->orderBy('category_switch')->get();

//for accounting displaying particulars
$ledger_list_tuition = \App\Ledger::where('idno',$user->idno)->where('category_switch', 6)->first();
$ledger_list_misc = \App\Ledger::where('idno',$user->idno)->where('category_switch', 1)->get();
$ledger_list_other = \App\Ledger::where('idno',$user->idno)->where('category_switch', 2)->get();
$ledger_list_depo = \App\Ledger::where('idno',$user->idno)->where('category_switch', 3)->get();
$ledger_list = \App\Ledger::where('idno',$user->idno)->where('category', 'SRF')->where('category_switch', env("SRF_FEE"))->get();
/////

            $ledger_return = \App\Ledger::groupBy(array('category', 'category_switch'))->where('idno', $idno)->where('category_switch', '7')->where('is_returned_check', 1)
                ->selectRaw('category, sum(amount) as amount, sum(discount) as discount, sum(debit_memo)as debit_memo, sum(payment) as payment')->orderBy('category_switch')->get();

            if (count($ledger_main) > 0) {
                foreach ($ledger_main as $payment) {
                    $totalmainpayment = $totalmainpayment + $payment->debit_memo + $payment->payment;
                    $totalpay = $totalpay + $payment->debit_memo + $payment->payment;
                }
            }
            if (count($ledger_return) > 0) {
                foreach ($ledger_return as $payment) {
                    $totalpay = $totalpay - ($payment->amount - ($payment->debit_memo + $payment->payment));
//                    $totalmainpayment = $totalmainpayment + $payment->debit_memo + $payment->payment;
                }
            }

            $downpayment = \App\LedgerDueDate::where('idno', $idno)->where('school_year', $status->school_year)->where('period', $status->period)->where('due_switch', '0')->selectRaw('sum(amount) as amount')->first();
            $due_dates_list_amount = \App\LedgerDueDate::where('idno', $idno)->where('school_year', $status->school_year)->where('period', $status->period)->where('due_switch', '1')->selectRaw('sum(amount) as amount')->first();
            $duetoday = \App\LedgerDueDate::where('idno', $idno)->where('school_year', $status->school_year)->where('period', $status->period)->where('due_date', '<=', date('Y-m-31'))->where('due_switch', '1')->selectRaw('sum(amount) as amount')->first();

            $ledger_others = \App\Ledger::where('idno', $idno)->where('category_switch', env("OTHER_MISC"))->get();
            $ledger_others_noreturn = \App\Ledger::where('idno', $idno)->where('category_switch', env("OTHER_MISC"))->where('is_returned_check',0)->get();
            //$ledger_optional = \App\Ledger::where('idno',$idno)->where('category_switch',env("OPTIONAL_FEE"))->get();

            if (count($ledger_others_noreturn) > 0) {
                foreach ($ledger_others_noreturn as $ledger_other) {
                    $due_others = $due_others + $ledger_other->amount - $ledger_other->discount - $ledger_other->debit_memo - $ledger_other->payment;
                }
                if ($due_others < 0) {
                $negative = $negative + $due_others;
                $due_others = 0;
                }
            }

            $previous = \App\Ledger::groupBy(array('category', 'category_switch', 'subsidiary'))->where('idno', $idno)->where('category_switch', '>', '9')
                            ->selectRaw('subsidiary,category, sum(amount) as amount, sum(discount) as discount, sum(debit_memo)as debit_memo, sum(payment) as payment')->orderBy('category_switch')->get();

            if (count($previous) > 0) {
                foreach ($previous as $prev) {
                    $due_previous = $due_previous + $prev->amount - $prev->discount - $prev->debit_memo - $prev->payment;
                }
                if ($due_previous < 0) {
                $negative = $negative + $due_previous;
                $due_previous = 0;
                }
            }
            $totalmaindue = $downpayment->amount + $duetoday->amount - $totalmainpayment;
            if ($totalmaindue < 0) {
                $totalmaindue = 0;
            }
            $plus = ($due_dates_list_amount->amount + $downpayment->amount) - $totalpay;
            if ($plus < 0) {
                $negative = $negative + $plus;
                $plus = 0;
            }
            $totaldue = $plus - $totalmaindue + $due_others + $due_previous;
            $totaldue = $totaldue + $totalmaindue;
            $status = \App\Status::where('idno', $idno)->first();
            if ($status->academic_type == "BED") {
                $levels = \App\Status::where('idno', $idno)->where('school_year', $status->school_year)->where('period', $status->period)->first();
            } else if ($status->academic_type == "SHS") {
                $levels = \App\Status::where('idno', $idno)->where('school_year', $status->school_year)->where('period', $status->period)->first();
            } else if ($status->academic_type == "College") {
                $levels = \App\Status::where('idno', $idno)->where('school_year', $status->school_year)->where('period', $status->period)->first();
            }


            $reservations = \App\Reservation::where('idno', $idno)->where('reservation_type', '1')->orderBy('transaction_date')->get();
            $deposits = \App\Reservation::where('idno', $idno)->where('reservation_type', '2')->orderBy('transaction_date')->get();

            $payments = \App\Payment::where('idno', $idno)->where('school_year', $school_year)->orderBy('transaction_date')->get();

            $debit_memos = \App\DebitMemo::where('idno', $idno)->where('school_year', $school_year)->orderBy('transaction_date')->get();
            $student_deposits = \App\AddToStudentDeposit::where('idno', $idno)->where('school_year', $school_year)->orderBy('transaction_date')->get();

            if($status->academic_type == "SHS" || $status->academic_type == "College"){
            $due_dates = \App\LedgerDueDate::where('idno',$idno)->where('school_year', $status->school_year)->where('period', $status->period)->get();
            }else{
            $due_dates = \App\LedgerDueDate::where('idno',$idno)->where('school_year', $status->school_year)->get();
            }
            
            $is_early_enrollment = \App\CtrEarlyEnrollmentPaymentSwitch::first()->is_process_main_payment;
            
            return view("cashier.ledger", compact('idno','school_year','periods','levels', 'user', 'ledger_main', 'ledger', 'ledger_main_tuition', 'ledger_main_misc', 'ledger_main_other', 'ledger_main_depo', 'ledger_others', 'ledger_optional', 'previous', 'status', 'payments', "debit_memos", 'due_dates', 'totalmainpayment', 'totaldue', 'student_deposits', 'reservations', 'deposits', 'ledger_srf','totalpay','ledger_list_tuition','ledger_list_misc','ledger_list_other','ledger_list_depo','ledger_list', 'negative','is_early_enrollment'));
            //return $levels;
        }
    }

    function viewreceipt($reference_id) {
        $payment = \App\Payment::where('reference_id', $reference_id)->first();
        $status = \App\Status::where('idno', $payment->idno)->first();
        $accountings = \App\Accounting::where('reference_id', $reference_id)->where('accounting_type', '1')->get();
        $receipt_details = DB::Select("Select receipt_details, sum(credit) as credit from accountings where reference_id = "
                        . "'$reference_id' and credit > '0' and accounting_type = '1' group by receipt_details, reference_id");
        $receipt_less = DB::Select("Select receipt_details, sum(debit) as debit from accountings where reference_id = "
                        . "'$reference_id' and receipt_details != 'Cash' and debit > '0' and accounting_type='1'  group by receipt_details, reference_id");
        return view('cashier.viewreceipt', compact('payment', 'status', 'accountings', 'receipt_details', 'receipt_less'));
    }

    public static function getreceipt() {
        if (Auth::user()->accesslevel == env("CASHIER")) {
            $id = \App\ReferenceId::where('idno', Auth::user()->idno)->first()->id;
            $number = \App\ReferenceId::where('idno', Auth::user()->idno)->first()->receipt_no;
            $receipt = "";
            for ($i = strlen($number); $i <= 9; $i++) {
                $receipt = $receipt . "0";
            }
            if(Auth::user()->accesslevel == env("CASHIER")){
                $receipt_number = $receipt . $number;
                $check_or = \App\Payment::where('receipt_no', $receipt_number)->get();
                if(count($check_or)>0){
                return $receipt . $number."-A";
                }else{
                return $receipt . $number;
                }
            }else{
                return $receipt . $number;
            }
        }
    }

    public static function getending_receipt() {
        if (Auth::user()->accesslevel == env("CASHIER")) {
            $id = \App\ReferenceId::where('idno', Auth::user()->idno)->first()->id;
            $number = \App\ReferenceId::where('idno', Auth::user()->idno)->first()->end_receipt_no;
            $receipt = "";
            for ($i = strlen($number); $i <= 9; $i++) {
                $receipt = $receipt . "0";
            }
            if(Auth::user()->idno == "igarcia" || Auth::user()->idno == "belle"){
                $receipt_number = $receipt . $number;
                $check_or = \App\Payment::where('receipt_no', $receipt_number)->get();
                if(count($check_or)>0){
                return $receipt . $number."-A";
                }else{
                return $receipt . $number;
                }
            }else{
                return $receipt . $number;
            }
        }
    }

    public static function updatereceipt() {
        if (Auth::user()->accesslevel == env("CASHIER")) {
            $update = \App\ReferenceId::where('idno', Auth::user()->idno)->first();
            $update->receipt_no = $update->receipt_no + 1;
            $update->update();
        }
    }

    function reverserestore($reference_id) {
        if (Auth::user()->accesslevel == env("CASHIER") || Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            DB::beginTransaction();
            //$this->checkifreservation($reference_id);
            $this->reverserestore_ledger($reference_id, env("CASH"));
            $this->reverserestore_entries(\App\Payment::where('reference_id', $reference_id)->get(), $reference_id);
            $this->reverserestore_entries(\App\Accounting::where('reference_id', $reference_id)->get(), $reference_id);
            $this->reverserestore_entries(\App\Reservation::where('reference_id', $reference_id)->get(), $reference_id);
            \App\Http\Controllers\Admin\Logs::log("Reverse/Restore receipt with reference no: $reference_id.");
            DB::commit();
            return redirect(url('/cashier', array('viewreceipt', $reference_id)));
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

    function reverserestore_dm($reference_id) {
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            DB::beginTransaction();
            //$this->checkifreservation($reference_id);
            $this->reverserestore_ledger_dm($reference_id, env("DEBIT_MEMO"));
            $this->reverserestore_entries(\App\DebitMemo::where('reference_id', $reference_id)->get(), $reference_id);
            $this->reverserestore_entries(\App\Accounting::where('reference_id', $reference_id)->get(), $reference_id);
            $this->reverserestore_entries(\App\Reservation::where('reference_id', $reference_id)->get(), $reference_id);
            \App\Http\Controllers\Admin\Logs::log("Reverse/Restore Debit Memo receipt with reference no: $reference_id.");
            DB::commit();
            return redirect(url('/accounting', array('view_debit_memo', $reference_id)));
        }
    }

    function reverserestore_ledger_dm($reference_id, $entry_type) {
        $accountings = \App\Accounting::where('reference_id', $reference_id)->where('credit', '>', '0')->where('accounting_type', $entry_type)->get();
        if (count($accountings) > 0) {
            foreach ($accountings as $accounting) {
                $ledger = \App\Ledger::find($accounting->reference_number);
                if (count($ledger) > 0) {
                    if ($accounting->is_reverse == 0) {
                        $ledger->debit_memo = $ledger->debit_memo - $accounting->credit;
                    } else {
                        $ledger->debit_memo = $ledger->debit_memo + $accounting->credit;
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
                    $ob->is_reverse = "0";
                }
                $ob->update();
            }
        }
    }

}
