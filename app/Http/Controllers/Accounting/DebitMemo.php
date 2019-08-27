<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use \App\Http\Controllers\Cashier\MainPayment;

class DebitMemo extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index($idno) {
        if (Auth::user()->accesslevel == env("ACCTNG_STAFF") || Auth::user()->accesslevel == env("ACCTNG_HEAD")) {
            $user = \App\User::where('idno', $idno)->first();
            $receipt_number = $this->getReceipt();
            $total_other = 0.00;

            //Other Fee Total
            $other_fee_total = \App\Ledger::where('idno', $idno)->where('category_switch', env("OTHER_FEE"))
                    ->selectRaw("sum(amount) - sum(discount)-sum(debit_memo)-sum(payment) as balance")
                    ->first();
            //Miscellaneous Fee Total
            $miscellaneous_fee_total = \App\Ledger::where('idno', $idno)->where('category_switch', env("MISC_FEE"))
                    ->selectRaw("sum(amount) - sum(discount)-sum(debit_memo)-sum(payment) as balance")
                    ->first();
            ///Depository Fee Total
            $depository_fee_total = \App\Ledger::where('idno', $idno)->where('category_switch', env("DEPOSITORY_FEE"))
                    ->selectRaw("sum(amount) - sum(discount)-sum(debit_memo)-sum(payment) as balance")
                    ->first();
            //Subject Related Fee Total
            $srf_total = \App\Ledger::where('idno', $idno)->where('category_switch', env("SRF_FEE"))
                    ->selectRaw("sum(amount) - sum(discount)-sum(debit_memo)-sum(payment) as balance")
                    ->first();

            //Tuion Fee Total
            $tuition_fee_total = \App\Ledger::where('idno', $idno)->where('category_switch', env("TUITION_FEE"))
                    ->selectRaw("sum(amount) - sum(discount)-sum(debit_memo)-sum(payment) as balance")
                    ->first();
            //Optional Fee Total
            $optional_fee_total = \App\Ledger::where('idno', $idno)->where('category_switch', env("OPTIONAL_FEE"))
                    ->selectRaw("sum(amount) - sum(discount)-sum(debit_memo)-sum(payment) as balance")
                    ->first();
            //Previous Balances
            $previous_total = \App\Ledger::where('idno', $idno)->where('category_switch', '>=', '10')
                    ->selectRaw("sum(amount) - sum(discount)-sum(debit_memo)-sum(payment) as balance")
                    ->first();
            //Other Fee
            $other_misc = \App\Ledger::where('idno', $idno)->whereRaw('amount-discount-debit_memo-payment > 0 And category_switch=7')->get();

            if (count($other_misc) > 0) {
                foreach ($other_misc as $om) {
                    $total_other = $total_other + $om->amount - $om->discount - $om->debit_memo - $om->payment;
                }
            }
//      // Total Due Main
            $downpayment = \App\LedgerDueDate::where('idno', $idno)->where('due_switch', '0')->selectRaw('sum(amount) as amount')->first();
            $duetoday = \App\LedgerDueDate::where('idno', $idno)->where('due_date', '<=', date('Y-m-d'))->where('due_switch', '1')->selectRaw('sum(amount) as amount')->first();
            //Total Payment Main
            $payment = \App\Ledger::where('idno', $idno)->where('category_switch', '<=', '5')
                            ->selectRaw('sum(debit_memo)+sum(payment)+sum(discount) as payment')->first();
            //
            if ($downpayment->amount + $duetoday->amount - $payment->payment > 0) {
                $due_total = $downpayment->amount + $duetoday->amount - $payment->payment;
            } else {
                $due_total = 0;
            }
            //reservation
            $reservation = \App\Reservation::where('idno', $idno)->where('reservation_type', '1')
                            ->where('is_consumed', '0')->selectRaw('sum(amount) as amount')->first();
            //Srudent Deposit
            $deposit = \App\Reservation::where('idno', $idno)->where('reservation_type', '2')
                            ->where('is_consumed', '0')->selectRaw('sum(amount) as amount')->first();

            return view('accounting.debit_memo', compact('user', 'other_fee_total', 'miscellaneous_fee_total', 'depository_fee_total', 'srf_total', 'tuition_fee_total', 'previous_total', 'other_misc', 'reservation', 'deposit', 'receipt_number', 'due_total', 'optional_fee_total'));
        }
    }

    function getReceipt() {
        if (Auth::user()->accesslevel == env("ACCTNG_STAFF") || Auth::user()->accesslevel == env("ACCTNG_HEAD")) {
            $id = \App\ReferenceId::where('idno', Auth::user()->idno)->first()->id;
            $number = \App\ReferenceId::where('idno', Auth::user()->idno)->first()->dm_no;
            $receipt = "";
            for ($i = strlen($number); $i <= 6; $i++) {
                $receipt = $receipt . "0";
            }
            return $id . $receipt . $number;
        }
    }

    function post_debit_memo(Request $request) {

        if (Auth::user()->accesslevel == env("ACCTNG_STAFF") || Auth::user()->accesslevel == env("ACCTNG_HEAD")) {
            DB::beginTransaction();
            $reference_id = uniqid();
            $this->checkStatus($request, $reference_id);
            $this->postDM($request, $reference_id);
            $this->postAccounting($request, $reference_id);
            $this->postDebitEntry($request, $reference_id);
            $this->updateDM($request);
            
             \App\Http\Controllers\Admin\Logs::log("Post DM for $request->idno, Reference ID: $reference_id, Collected Amount: $request->collected_amount");
            DB::commit();
            return redirect(url('/accounting', array('view_debit_memo', $reference_id)));
        }
        //return $request;
    }

    function postDM($request, $reference_id) {
        $adddm = new \App\DebitMemo;
        $adddm->idno = $request->idno;
        $adddm->transaction_date = date('Y-m-d');
        $adddm->reference_id = $reference_id;
        $adddm->dm_no = $this->getReceipt();
        $adddm->explanation = $request->remark;
        $adddm->amount = $request->collected_amount;
        $adddm->posted_by = Auth::user()->idno;
        $adddm->school_year = \App\Status::where('idno', $request->idno)->first()->school_year;
        $per = \App\Status::where('idno', $request->idno)->first()->period;
        if($per == null){
            $adddm->period = "";
        }else{
            $adddm->period = $per;
        }
        $adddm->save();
    }

    function postAccounting($request, $reference_id) {
        $request->date = date('Y-m-d');

//        if($request->main_due > 0 ){
//           $totalpayment = $request->main_due;
//           $ledgers = \App\Ledger::where('idno',$request->idno)->where("category_switch",'<=','6')->whereRaw('amount-discount-debit_memo-payment>0')->orderBy('category_switch')->get(); 
//           MainPayment::processAccounting($request, $reference_id,$totalpayment,$ledgers,env("DEBIT_MEMO"));
//        }
        if ($request->miscellaneous > 0) {
            $totalpayment = $request->miscellaneous;
            $ledgers = \App\Ledger::where('idno', $request->idno)->where("category_switch", env('MISC_FEE'))->whereRaw('amount-discount-debit_memo-payment>0')->orderBy('category_switch')->get();
            MainPayment::processAccounting($request, $reference_id, $totalpayment, $ledgers, env("DEBIT_MEMO"));
        }
        if ($request->other_fee > 0) {
            $totalpayment = $request->other_fee;
            $ledgers = \App\Ledger::where('idno', $request->idno)->where("category_switch", env('OTHER_FEE'))->whereRaw('amount-discount-debit_memo-payment>0')->orderBy('category_switch')->get();
            MainPayment::processAccounting($request, $reference_id, $totalpayment, $ledgers, env("DEBIT_MEMO"));
        }
        if ($request->depository > 0) {
            $totalpayment = $request->depository;
            $ledgers = \App\Ledger::where('idno', $request->idno)->where("category_switch", env('DEPOSITORY_FEE'))->whereRaw('amount-discount-debit_memo-payment>0')->orderBy('category_switch')->get();
            MainPayment::processAccounting($request, $reference_id, $totalpayment, $ledgers, env("DEBIT_MEMO"));
        }
        if ($request->srf > 0) {
            $totalpayment = $request->srf;
            $ledgers = \App\Ledger::where('idno', $request->idno)->where("category_switch", env('SRF_FEE'))->whereRaw('amount-discount-debit_memo-payment>0')->orderBy('category_switch')->get();
            MainPayment::processAccounting($request, $reference_id, $totalpayment, $ledgers, env("DEBIT_MEMO"));
        }
        if ($request->optional > 0) {
            $totalpayment = $request->optional;
            $ledgers = \App\Ledger::where('idno', $request->idno)->where("category_switch", env('OPTIONAL_FEE'))->whereRaw('amount-discount-debit_memo-payment>0')->orderBy('category_switch')->get();
            MainPayment::processAccounting($request, $reference_id, $totalpayment, $ledgers, env("DEBIT_MEMO"));
        }
        if ($request->tuition > 0) {
            $totalpayment = $request->tuition;
            $ledgers = \App\Ledger::where('idno', $request->idno)->where("category_switch", env('TUITION_FEE'))->whereRaw('amount-discount-debit_memo-payment>0')->orderBy('category_switch')->get();
            MainPayment::processAccounting($request, $reference_id, $totalpayment, $ledgers, env("DEBIT_MEMO"));
        }

        if ($request->previous_balance > 0) {
            $totalpayment = $request->previous_balance;
            $ledgers = \App\Ledger::where('idno', $request->idno)->where("category_switch", '>=', '10')->whereRaw('amount-discount-debit_memo-payment>0')->orderBy('category_switch')->get();
            MainPayment::processAccounting($request, $reference_id, $totalpayment, $ledgers, env("DEBIT_MEMO"));
        }

        if (count($request->other_misc) > 0) {
            foreach ($request->other_misc as $key => $totalpayment) {
                $ledgers = \App\Ledger::where('id', $key)->get();
                MainPayment::processAccounting($request, $reference_id, $totalpayment, $ledgers, env("DEBIT_MEMO"));
            }
        }
    }

    function postDebitEntry($request, $reference_id) {
        $accounting = $request->accounting;
        $debit_amount = $request->debit_amount;
        $debit_particular = $request->debit_particular;
        $department = \App\Status::where('idno', $request->idno)->first()->department;
        $fiscal_year = \App\CtrFiscalYear::first()->fiscal_year;
        for ($i = 0; $i < count($accounting); $i++) {
            $addacct = new \App\Accounting;
            $addacct->transaction_date = date('Y-m-d');
            $addacct->reference_id = $reference_id;
            $addacct->accounting_type = env("DEBIT_MEMO");
            $addacct->category = $this->getAccounitngName($accounting[$i]);
            $addacct->subsidiary = $debit_particular[$i];
            $addacct->receipt_details = $this->getAccounitngName($accounting[$i]);
            $addacct->particular = $request->remark;
            $addacct->accounting_code = $accounting[$i];
            $addacct->department = $department;
            $addacct->accounting_name = $this->getAccounitngName($accounting[$i]);
            $addacct->fiscal_year = $fiscal_year;
            $addacct->debit = $debit_amount[$i];
            $addacct->posted_by = Auth::user()->idno;
            $addacct->save();
        }
    }

    function getAccounitngName($accounting_code) {
        $acctname = \App\ChartOfAccount::where('accounting_code', $accounting_code)->first();
        return $acctname->accounting_name;
    }

    function updateDM($request) {
        $dm = \App\ReferenceId::where('idno', Auth::user()->idno)->first();
        $dm->dm_no = $dm->dm_no + 1;
        $dm->update();
    }

    function view_debit_memo($reference_id) {
        $debit_memo = \App\DebitMemo::where('reference_id', $reference_id)->first();
        $accountings = \App\Accounting::selectRaw("accounting_code, accounting_name, subsidiary,sum(debit) as debit, sum(credit) as credit")
                        ->where('reference_id', $reference_id)->groupBy('accounting_name', 'accounting_code', 'subsidiary')->where('accounting_type', '2')->get();
        $user = \App\User::where('idno', $debit_memo->idno)->first();
        $status = \App\Status::where('idno', $debit_memo->idno)->first();
        return view('accounting.view_debit_memo', compact('debit_memo', 'accountings', 'user', 'status'));
    }

    function checkStatus($request, $reference_id) {
        $request->date = date('Y-m-d');
        if ($request->main_due > "0") {
            $status = \App\Status::where('idno', $request->idno)->first();
            if ($status->status == env("ASSESSED")) {
                MainPayment::addUnrealizedEntry($request, $reference_id);
                MainPayment::changeStatus($request->idno);
                //$this->notifyStudent($request, $reference_id);
            }
        }
    }

}
