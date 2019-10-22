<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Overpayment extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }

    //
    function apply_overpayment($idno) {
        if (Auth::user()->accesslevel == env("ACCTNG_STAFF") || Auth::user()->accesslevel==env("ACCTNG_HEAD")) {
            
            DB::beginTransaction();
            $amount_remove = $this->RemoveOverpayment($idno);
            $this->ApplyOverpayment($idno, $amount_remove);
            $this->PostOverpaymentMemo($idno, $amount_remove);
            $this->UpdateOP();
            \App\Http\Controllers\Admin\Logs::log("Apply Overpayment for $idno.");
            DB::commit();
            return redirect(url("/cashier/viewledger/2019/$idno"));
        }
    }
    function RemoveOverpayment($idno){
        $ledgers = \App\Ledger::where('idno', $idno)->whereRaw("discount + debit_memo + payment > amount")->get();
        $amount = 0;
        foreach($ledgers as $ledger){
            $amount = $amount + (($ledger->payment+$ledger->discount+$ledger->debit_memo) - $ledger->amount);
            $ledger->payment = $ledger->amount; 
            $ledger->save();
        }
        return $amount;
    }
    function ApplyOverpayment($idno, $amount_remove){
        $ledgers = \App\Ledger::where('idno', $idno)->whereRaw("discount + debit_memo + payment < amount")->get();
        foreach($ledgers as $ledger){
            $amount_to_add = $ledger->amount - ($ledger->discount + $ledger->debit_memo + $ledger->payment);
            if($amount_to_add >= $amount_remove){
                $amount_to_add = $amount_remove;
            }else{
                $amount_remove = $amount_remove-$amount_to_add;
            }
            $ledger->payment = $ledger->payment+$amount_to_add;
            $ledger->save();
        }
    }
    function PostOverpaymentMemo($idno, $amount_remove){
        $adddm = new \App\OverpaymentMemo;
        $adddm->idno = $idno;
        $adddm->transaction_date = date('Y-m-d');
        $adddm->op_no = $this->getReceipt();
        $adddm->amount = $amount_remove;
        $adddm->posted_by = Auth::user()->idno;
        $adddm->school_year = \App\Status::where('idno', $idno)->first()->school_year;
        $per = \App\Status::where('idno', $idno)->first()->period;
        if($per == null){
            $adddm->period = "";
        }else{
            $adddm->period = $per;
        }
        $adddm->save();
    }

    function updateOP() {
        $dm = \App\ReferenceId::where('idno', Auth::user()->idno)->first();
        $dm->op_no = $dm->op_no + 1;
        $dm->update();
    }

    function getReceipt() {
        if (Auth::user()->accesslevel == env("ACCTNG_STAFF") || Auth::user()->accesslevel == env("ACCTNG_HEAD")) {
            $id = \App\ReferenceId::where('idno', Auth::user()->idno)->first()->id;
            $number = \App\ReferenceId::where('idno', Auth::user()->idno)->first()->op_no;
            $receipt = "";
            for ($i = strlen($number); $i <= 6; $i++) {
                $receipt = $receipt . "0";
            }
            return $id . $receipt . $number;
        }
    }
}
