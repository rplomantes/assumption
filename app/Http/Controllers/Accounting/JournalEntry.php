<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use DB;
use PDF;

class JournalEntry extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    function jv_index() {
        $lists = \App\JournalEntry::where('id','!=',null)->limit(0);
        return view('accounting.journal_entry.index', compact('lists'));
    }
    
    function print_summary(Request $request) {
            $date_to = $request->date_to;
            $date_from = $request->date_from;
            $finalStartDate = "$date_to";
            $finalEndDate = "$date_from";
            $lists = \App\JournalEntry::whereBetween('transaction_date', [$finalStartDate, $finalEndDate])->orderBy('transaction_date','asc')->get();
        
        $pdf = PDF::loadView('accounting.journal_entry.print_summary', compact('lists', 'finalStartDate', 'finalEndDate'));
        $pdf->setPaper('letter', 'portrait');
        return $pdf->stream("journal_voucher_summary.pdf");
    }

    function jv_create() {
        $ref_id = uniqid();
        $accounting_entry = \App\ChartOfAccount::all();
        $user = \App\ReferenceId::where('idno', Auth::user()->idno)->first();
        $jv_voucher = $this->getReceipt();
        return view('accounting.journal_entry.new_journal_entry', compact('ref_id','accounting_entry', 'user','jv_voucher'));
    }
    function getReceipt() {
        if (Auth::user()->accesslevel == env("ACCTNG_STAFF") || Auth::user()->accesslevel == env("ACCTNG_HEAD")) {
            $id = \App\ReferenceId::where('idno', Auth::user()->idno)->first()->id;
            $number = \App\ReferenceId::where('idno', Auth::user()->idno)->first()->jv_voucher;

            $monthnow = date("m");
            
            if(!\App\JournalEntry::where("processed_by", Auth::user()->idno)->whereYear("transaction_date", date("Y"))->whereMonth("transaction_date", date("m"))->first()){
                $number = 0;
                
                $updatenumber = \App\ReferenceId::where('idno', Auth::user()->idno)->first();
                $updatenumber->jv_voucher = 0;
                $updatenumber->update();
            }
            
            $receipt = "";
            for ($i = strlen($number); $i <= 4; $i++) {
                $receipt = $receipt . "0";
            }
            return $monthnow."-".$id . $receipt . $number;
        }
    }
    
    function cancel_jv($reference){
        $data = \App\JournalEntryData::where('reference_id',$reference)->delete();
        $record = \App\JournalEntry::where('reference_id',$reference)->delete();
        return redirect(url('/journal_entry'));
    }
    
    function process_jv(Request $request){
        DB::beginTransaction();
        $fiscal_year = \App\CtrFiscalYear::first();
        $this->saveJournalEntry($request, $fiscal_year);
        $this->finalizeEntries($request->entry_date,$request->reference);
        $this->updateReference(Auth::user()->idno, $request->voucher_no, $request->category);
        DB::commit();
        return redirect(url('/view/journal_voucher', $request->reference));
    }
    
    function saveJournalEntry($request, $fiscal_year) {
        $saveVoucher = new \App\JournalEntry;
        $saveVoucher->transaction_date = $request->entry_date;
        $saveVoucher->reference_id = $request->reference;
        $saveVoucher->voucher_no = $request->voucher_no;
        $saveVoucher->particular = $request->description;
        $saveVoucher->fiscal_year = $fiscal_year->fiscal_year;
        $saveVoucher->processed_by = Auth::user()->idno;
        $saveVoucher->save();
    }

    function getAccountingName($code) {
        $acctcode = \App\ChartOfAccount::where('accounting_code', $code)->first();
        return $acctcode->accounting_name;
    }

    function finalizeEntries($entry_date,$reference) {
        $entry = \App\JournalEntry::where('reference_id', $reference)->first();
        $accountings = \App\JournalEntryData::where('reference_id', $reference)->get();
        $this->removeCopy("Accounting",$reference);
        foreach ($accountings as $account) {
            $saveEntry = new \App\Accounting;
            $saveEntry->transaction_date = $entry_date;
            $saveEntry->reference_id = $reference;
            $saveEntry->category = $account->category;
            $saveEntry->receipt_details = $account->subsidiary;
            $saveEntry->accounting_name = $this->getAccountingName($account->accounting_code);
            $saveEntry->accounting_code = $account->accounting_code;
            $saveEntry->accounting_type = env('JOURNAL');
            $saveEntry->fiscal_year = $account->fiscal_year;
            $saveEntry->debit = $account->debit;
            $saveEntry->credit = $account->credit;
            $saveEntry->particular = $entry->particular;
            $saveEntry->posted_by = $account->posted_by;
            $saveEntry->save();
            $account->delete();
        }
    }

    function updateReference($idno, $voucher_no) {
        $user = \App\ReferenceId::where('idno', $idno)->first();
        $user->jv_voucher = $user->jv_voucher + 1;
        $user->save();
    }
    
    function viewJournalVoucher($reference){
        $accountings = \App\Accounting::where('reference_id',$reference)->get();
        $journal_entry = \App\JournalEntry::where('reference_id',$reference)->first();
        return view('accounting.journal_entry.view_voucher',compact('reference','journal_entry','accountings'));
     }
     
     function printVoucher($reference){
        $accountings = \App\Accounting::where('reference_id',$reference)->get();
        $journal_entry = \App\JournalEntry::where('reference_id',$reference)->first();
        $pdf = PDF::loadView('accounting.journal_entry.print_journal_voucher',compact('reference','journal_entry','accountings'));
        $pdf->setPaper('letter','portrait');
        return $pdf->stream("journal_entry.pdf");
    }
    
    function reverseVoucher($reference) {
        $record = \App\JournalEntry::where('reference_id', $reference)->first();
        if ($record->transaction_date == date("Y-m-d")) {
            $record->is_reverse = 1;
            $record->save();
            $data = \App\Accounting::where('reference_id', $reference)->get();
            foreach ($data as $entry) {
                $entry->is_reverse = 1;
                $entry->save();
            }

            return redirect(url('/view/journal_voucher', $reference));
        } else {
            echo "This action cannot be done. Please go back";
        }
    }

    function restoreVoucher($reference) {
        $record = \App\JournalEntry::where('reference_id', $reference)->first();
        if ($record->transaction_date == date("Y-m-d")) {
            $record->is_reverse = 0;
            $record->save();
            $data = \App\Accounting::where('reference_id', $reference)->get();
            foreach ($data as $entry) {
                $entry->is_reverse = 0;
                $entry->save();
            }
            return redirect(url('/view/journal_voucher', $reference));
        } else {
            echo "This action cannot be done. Please go back";
        }
    }
    
    function editJournalEntry($reference) {
        $accounting_entry = \App\ChartOfAccount::get();
        $voucher = \App\JournalEntry::where('reference_id', $reference)->first();
        $data = \App\Accounting::where('reference_id', $reference)->get();
        if ($voucher->transaction_date == date("Y-m-d")) {
            $this->removeCopy("Raw", $reference);
            foreach ($data as $entry) {
                $this->copyAccountings($entry, $voucher);
            }
            $accountings = \App\JournalEntryData::where('reference_id', $reference)->get();
            return view('accounting.journal_entry.edit_journal_entry', compact('accounting_entry', 'accountings', 'reference', 'data', 'voucher'));
            
        } else {
            echo "Not allowed. Please go back";
        }
    }
    
    function cancelEdit($reference){
        $this->removeCopy("Raw",$reference);
        return redirect(url('/view/journal_voucher', $reference));
    }
    
    function removeCopy($type, $reference) {
        if ($type == "Accounting") {
            $data = \App\Accounting::where('reference_id', $reference)->delete();
        } else {
            $data = \App\JournalEntryData::where('reference_id', $reference)->delete();
        }
    }

    function copyAccountings($entry, $voucher) {
        $saveEntry = new \App\JournalEntryData;
        $saveEntry->transaction_date = $entry->transaction_date;
        $saveEntry->reference_id = $entry->reference_id;
        $saveEntry->voucher_no = $voucher->voucher_no;
        $saveEntry->category = $entry->category;
        $saveEntry->subsidiary = $entry->description;
        $saveEntry->description = $entry->description;
        $saveEntry->accounting_code = $entry->accounting_code;
        $saveEntry->entry_type = env('JOURNAL');
        $saveEntry->fiscal_year = $entry->fiscal_year;
        $saveEntry->receipt_type = "JV";
        $saveEntry->debit = $entry->debit;
        $saveEntry->credit = $entry->credit;
        $saveEntry->isreverse = 1;
        $saveEntry->posted_by = Auth::user()->idno;
        $saveEntry->save();
    }
    
    function update(Request $request) {
        DB::beginTransaction();
        $fiscal_year = \App\CtrFiscalYear::first();
        $this->updateVoucher($request, $fiscal_year);
        $this->finalizeEntries($request->entry_date,$request->reference);
        DB::commit();
        return redirect(url('/view/journal_voucher', $request->reference));
    }
    
    function updateVoucher($request, $fiscal_year) {
        $saveVoucher = \App\JournalEntry::where('reference_id', $request->reference)->first();
        $saveVoucher->transaction_date = $request->entry_date;
        $saveVoucher->particular = $request->description;
        $saveVoucher->fiscal_year = $fiscal_year->fiscal_year;
        $saveVoucher->processed_by = Auth::user()->idno;
        $saveVoucher->save();
    }

}
