<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use DB;
use PDF;

class Disbursement extends Controller {

    function disbursement_index() {
        $lists = \App\Disbursement::where('type', 0)->get();
        return view('accounting.disbursement.index', compact('lists'));
    }

    function disbursement_create() {
        $ref_id = uniqid();
        $accounting_entry = \App\ChartOfAccount::get();
        $user = \App\ReferenceId::where('idno', Auth::user()->idno)->first();
        $voucher_no = $this->getReceipt();
        return view('accounting.disbursement.check_disbursement', compact('ref_id', 'payees', 'accounting_entry', 'user','voucher_no'));
    }
    function getReceipt() {
        if (Auth::user()->accesslevel == env("ACCTNG_STAFF") || Auth::user()->accesslevel == env("ACCTNG_HEAD")) {
            $id = \App\ReferenceId::where('idno', Auth::user()->idno)->first()->id;
            $number = \App\ReferenceId::where('idno', Auth::user()->idno)->first()->voucher_no;
            $receipt = "";
            for ($i = strlen($number); $i <= 6; $i++) {
                $receipt = $receipt . "0";
            }
            return $id . $receipt . $number;
        }
    }

    function print_summary(Request $request) {
        $date_to = $request->date_to;
        $date_from = $request->date_from;
        $startDate = "$date_from";
        $dateEnd = "$date_to";
        $lists = \App\Disbursement::whereBetween('transaction_date', [$startDate, $dateEnd])->where("type",0)->orderBy('transaction_date', 'asc')->get();
        $pdf = PDF::loadView('accounting.disbursement.print_summary', compact('lists', 'startDate', 'dateEnd', 'type'));
        $pdf->setPaper('letter', 'portrait');
        return $pdf->stream("disbursement_summary.pdf");
    }

    function process(Request $request) {
        DB::beginTransaction();
        $fiscal_year = \App\CtrFiscalYear::first();
        $this->saveDisbursement($request, $fiscal_year);
        $this->finalizeEntries($request->reference);
        $this->saveEntry($request, $fiscal_year);
        $this->updateReference(Auth::user()->idno, $request->voucher_no);
        DB::commit();
        if ($request->category == 0) {
            return redirect(url('/view/disbursement', $request->reference));
        } else {
            return redirect(url('/view/petty_cash', $request->reference));
        }
    }

    function saveDisbursement($request, $fiscal_year) {
        $saveDisbursement = new \App\Disbursement;
        $saveDisbursement->transaction_date = Carbon::now();
        $saveDisbursement->reference_id = $request->reference;
        $saveDisbursement->voucher_no = $request->voucher_no;
        $saveDisbursement->type = 0;
        $saveDisbursement->payee_name = $request->payee;
        $saveDisbursement->amount = str_replace(",", "", $request->check_amount);
        $saveDisbursement->bank = $request->bank;
        $saveDisbursement->check_no = $request->check_no;
        $saveDisbursement->remarks = $request->description;
        $saveDisbursement->fiscal_year = $fiscal_year->fiscal_year;
        $saveDisbursement->processed_by = Auth::user()->idno;
        $saveDisbursement->save();
    }

    function saveEntry($request, $fiscal_year) {
        $saveEntry = new \App\Accounting;
        $saveEntry->transaction_date = Carbon::now();
        $saveEntry->reference_id = $request->reference;
        $saveEntry->category = $this->getAccountingName($request->account_name);
        $saveEntry->subsidiary = $request->description;
        $saveEntry->receipt_details = $this->getAccountingName($request->account_name);
        $saveEntry->accounting_name = $this->getAccountingName($request->account_name);
        $saveEntry->accounting_code = $request->account_name;
        $saveEntry->accounting_type = env('DISBURSEMENT');
        $saveEntry->fiscal_year = $fiscal_year->fiscal_year;
        $saveEntry->credit = $request->check_amount;
        $saveEntry->particular = $request->payee;
        $saveEntry->posted_by = Auth::user()->idno;
        $saveEntry->save();
    }

    function getAccountingName($code) {
        $acctcode = \App\ChartOfAccount::where('accounting_code', $code)->first();
        return $acctcode->accounting_name;
    }

    function finalizeEntries($reference) {
        $accountings = \App\DisbursementData::where('reference_id', $reference)->get();
        foreach ($accountings as $account) {
            $saveEntry = new \App\Accounting;
            $saveEntry->transaction_date = Carbon::now();
            $saveEntry->reference_id = $reference;
            $saveEntry->category = $account->category;
            $saveEntry->subsidiary = $account->description;
            $saveEntry->receipt_details = $account->receipt_details;
            $saveEntry->accounting_code = $account->accounting_code;
            $saveEntry->accounting_name = $this->getAccountingName($account->accounting_code);
            $saveEntry->accounting_type = $account->entry_type;
            $saveEntry->fiscal_year = $account->fiscal_year;
            $saveEntry->debit = $account->debit;
            $saveEntry->credit = $account->credit;
            $saveEntry->particular = $account->particular;
            $saveEntry->posted_by = $account->posted_by;
            $saveEntry->save();

            $account->delete();
        }
    }

    function updateReference($idno, $voucher_no) {
        $user = \App\ReferenceId::where('idno', $idno)->first();
        $user->voucher_no = $user->voucher_no + 1;
        $user->save();
    }

    function viewDisbursement($reference) {
        $accountings = \App\Accounting::where('reference_id', $reference)->get();
        $disbursement = \App\Disbursement::where('reference_id', $reference)->first();
        if(Auth::user()->accesslevel == env("ACCTNG_HEAD")){
            return view('accounting.disbursement.editable_disbursement', compact('reference', 'disbursement', 'accountings'));
        }else{
            return view('accounting.disbursement.view_disbursement', compact('reference', 'disbursement', 'accountings'));
        }
        
    }

    function printVoucher($reference) {
        $accountings = \App\Accounting::where('reference_id', $reference)->get();
        $disbursement = \App\Disbursement::where('reference_id', $reference)->first();
        $pdf = PDF::loadView('accounting.disbursement.print_voucher', compact('reference', 'disbursement', 'accountings'));
        $pdf->setPaper('letter', 'portrait');
        return $pdf->stream("summary_promissory_note.pdf");
    }
    
    function print_check_disbursement($reference_id){
        $accountings = \App\Accounting::where('reference_id', $reference_id)->get();
        $disbursement = \App\Disbursement::where('reference_id', $reference_id)->first();
        $pdf = PDF::loadView('accounting.disbursement.print_check',compact('reference_id','disbursement','accountings'));
        $pdf->setPaper('letter','portrait');
        return $pdf->stream("disbursement.pdf");
    }

    function printVoucherLabels($reference) {
        $accountings = \App\Accounting::where('reference_id', $reference)->get();
        $disbursement = \App\Disbursement::where('reference_id', $reference)->first();
//        return view('accounting.disbursement.print_disbursement',compact('reference','disbursement','accountings'));
        $pdf = PDF::loadView('accounting.disbursement.print_disbursement', compact('reference', 'disbursement', 'accountings'));
        $pdf->setPaper('letter', 'portrait');
        return $pdf->stream("summary_promissory_note.pdf");
    }

    function cancelDisbursement($reference) {
        $data = \App\DisbursementData::where('reference_id', $reference)->delete();
        $record = \App\Disbursement::where('reference_id', $reference)->delete();
        return redirect(url('/disbursement'));
    }

    function petty_cash_index() {
        $lists = \App\Disbursement::where('type', 1)->get();
        return view('accounting.disbursement.petty_cash_index', compact('lists'));
    }

    function petty_cash_create() {
        $ref_id = uniqid();
        $accounting_entry = \App\ChartOfAccount::get();
        $payees = \App\Disbursement::selectRaw('id,payee_name')->distinct(['payee_name'])->get();
        $user = \App\CtrReferenceId::where('idno', Auth::user()->idno)->first();
        return view('accounting.disbursement.cash_disbursement', compact('ref_id', 'payees', 'accounting_entry', 'user'));
    }

    function viewPettyCash($reference) {
        $accountings = \App\Accounting::where('reference_id', $reference)->get();
        $disbursement = \App\Disbursement::where('reference_id', $reference)->first();
        return view('accounting.disbursement.view_pettycash', compact('reference', 'disbursement', 'accountings'));
    }

    function printPettyCashVoucher($reference) {
        $accountings = \App\Accounting::where('reference_id', $reference)->get();
        $disbursement = \App\Disbursement::where('reference_id', $reference)->first();
//        return view('accounting.disbursement.print_disbursement',compact('reference','disbursement','accountings'));
        $pdf = PDF::loadView('accounting.disbursement.print_pettycash_voucher', compact('reference', 'disbursement', 'accountings'));
        $pdf->setPaper('letter', 'portrait');
        return $pdf->stream("summary_promissory_note.pdf");
    }

    function print_summary_cash(Request $request) {
        $date = $request->date;
        $dates = explode('-', $date); // two dates MM/DD/YYYY-MM/DD/YYYY
        $trim = trim($dates[0], " ");
        $startDate = explode('/', $trim); // MM[0] DD[1] YYYY[2]
        $minus = $startDate[1] - 1;
        $dateEnd = "$startDate[2]-$startDate[0]-$minus";
        $finalStartDate = "$startDate[2]-$startDate[0]-$startDate[1]";
        $trimend = trim($dates[1], " ");
        $endDate = explode('/', $trimend); // MM[0] DD[1] YYYY[2] 
        $finalEndDate = "$endDate[2]-$endDate[0]-$endDate[1]";
        $lists = \App\Disbursement::where('type', 1)->whereBetween('transaction_date', [$finalStartDate, $finalEndDate])->orderBy('transaction_date', 'asc')->get();
        $pdf = PDF::loadView('accounting.disbursement.print_summary_cash', compact('lists', 'finalStartDate', 'finalEndDate'));
        $pdf->setPaper('letter', 'portrait');
        return $pdf->stream("disbursement_summary.pdf");
    }
    
    function edit_disbursement(Request $request){
        
        $checkvoucherno = \App\Disbursement::where("voucher_no", $request->voucher_no)->first();
        if($checkvoucherno){
            if($checkvoucherno->reference_id != $request->reference_id){
                return back()->withErrors("There's an existing voucher no used on disbursement");
            }
        }
        
        $accountings = \App\Accounting::where("reference_id", $request->reference_id)->get();
        if(!$accountings->isEmpty()){
            foreach($accountings as $accounting){
                $accounting->delete();
            }
        }
        
        $updatedisbursement = \App\Disbursement::where("reference_id", $request->reference_id)->first();
        
        if(count($request->accounting_codes) > 0){
            foreach($request->accounting_codes as $key=>$accounting_code){
                if(array_key_exists($key, $request->accounting_codes)){
                    $account = \App\ChartOfAccount::where("accounting_code", $accounting_code)->first();
                    
                    $saveEntry = new \App\Accounting;
                    $saveEntry->transaction_date = $updatedisbursement->transaction_id;
                    $saveEntry->reference_id = $request->reference_id;
                    $saveEntry->category = $this->getAccountingName($accounting_code);
                    $saveEntry->subsidiary = $request->particulars[$key];;
                    $saveEntry->receipt_details = $this->getAccountingName($accounting_code);
                    $saveEntry->accounting_code = $accounting_code;
                    $saveEntry->accounting_name = $this->getAccountingName($accounting_code);
                    $saveEntry->accounting_type = env('DISBURSEMENT');
                    $saveEntry->fiscal_year = \App\CtrFiscalYear::value("fiscal_year");
                    $saveEntry->debit = $request->debit[$key];
                    $saveEntry->credit = $request->credit[$key];
                    $saveEntry->particular = $request->particulars[$key];;
                    $saveEntry->posted_by = $updatedisbursement->processed_by;
                    $saveEntry->save();
                }
            }
        }
        
        $actual_amount = array_sum($request->debit) - array_sum($request->credit);
        
        $updatedisbursement->bank = $request->bank;
        $updatedisbursement->amount = $actual_amount > 0 ? $actual_amount : 0;
        $updatedisbursement->voucher_no = $request->voucher_no;
        $updatedisbursement->payee_name = $request->payee;
        $updatedisbursement->check_no = $request->check_number;
        $updatedisbursement->remarks = $request->remarks;
        $updatedisbursement->update();
        
        if($actual_amount > 0){
            $saveEntry = new \App\Accounting;
            $saveEntry->transaction_date = $updatedisbursement->transaction_id;
            $saveEntry->reference_id = $request->reference_id;
            $saveEntry->category = $this->getAccountingName($request->account_name);
            $saveEntry->subsidiary = $request->remarks;
            $saveEntry->receipt_details = $this->getAccountingName($request->account_name);
            $saveEntry->accounting_name = $this->getAccountingName($request->account_name);
            $saveEntry->accounting_code = $request->account_name;
            $saveEntry->accounting_type = env('DISBURSEMENT');
            $saveEntry->fiscal_year = \App\CtrFiscalYear::value("fiscal_year");
            $saveEntry->credit = $actual_amount;
            $saveEntry->particular = $request->payee;
            $saveEntry->posted_by = $updatedisbursement->processed_by;
            $saveEntry->save();
        }
        
        
        return back()->withSuccess("You have updated the voucher");
    }

}
