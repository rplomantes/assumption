<?php

namespace App\Http\Controllers\Accounting\Ajax;

use App\Http\Controllers\Controller;
use Request;
use Illuminate\Support\Facades\Input;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class AjaxJournalEntry extends Controller
{
    
    public function __construct() {
        $this->middleware('auth');
    }
    
    function get_vouchers() {
        if (Request::ajax()) {
            $date_to = Input::get('date_to');
            $date_from = Input::get('date_from');
            $startDate = "$date_to";
            $dateEnd = "$date_from";
            $lists = \App\JournalEntry::whereBetween('transaction_date', [$startDate, $dateEnd])->orderBy('transaction_date','asc')->get();
            return view('accounting.journal_entry.ajaxdisplay', compact('lists'));
        }
    }

    function save_entries(){
        if (Request::ajax()) {
            $voucher_no = Input::get('voucher_no');
            $reference = Input::get('reference');
            $code = Input::get('code');
            $particular = Input::get('particular');
            $debit = Input::get('debit');
            $credit = Input::get('credit');
            $is_update = Input::get('is_update');
            $amount = str_replace(",","",Input::get('amount'));
            
            $fiscal_year = \App\CtrFiscalYear::first();
            
            $saveEntry = new \App\JournalEntryData;
            $saveEntry->transaction_date = Carbon::now();
            $saveEntry->reference_id = $reference;
            $saveEntry->voucher_no = $voucher_no;
            $saveEntry->category = $this->getAccountingName($code);
            $saveEntry->subsidiary = $particular;
            $saveEntry->description = $particular;
            $saveEntry->accounting_code = $code;
            $saveEntry->entry_type = 5;
            $saveEntry->fiscal_year = $fiscal_year->fiscal_year;
            $saveEntry->receipt_type = "JV";
            if($debit > 0){
                $saveEntry->debit = $debit;
                $saveEntry->credit = 0;
            }
            if($credit > 0){
                $saveEntry->debit = 0;
                $saveEntry->credit = $credit;
            }
            $saveEntry->isreverse = 1;
            $saveEntry->posted_by = Auth::user()->idno;
            $saveEntry->save();
            $this->check_entries($reference,$voucher_no);
            return $this->display_entries($reference,$is_update);
        }
    }
    
    
    function check_entries($reference,$voucher_no){
        $entries = \App\JournalEntryData::where('voucher_no',$voucher_no)->get();
        if(count($entries) > 0){
            foreach($entries as $entry){
                $entry->reference_id = $reference;
                $entry->save();
            }
        }
        return;
    }
    
    function getAccountingName($code){
        $acctcode = \App\ChartOfAccount::where('accounting_code',$code)->first();
        return $acctcode->accounting_name;
    }
    
    function display_entries($reference,$is_update) {
        $accountings = \App\JournalEntryData::where('reference_id', $reference)->get();
        $accounting_entry = \App\ChartOfAccount::orderBy("accounting_name")->get();
        return view('accounting.ajax.journal_entries', compact('reference', 'accountings', 'accounting_entry','is_update'));
        
    }
    
    function remove_entries() {
        $is_update = Input::get('is_update');
        $reference = Input::get('reference');
        $id = Input::get('id');
        $accountings = \App\JournalEntryData::where('id', $id)->delete();
        return $this->display_entries($reference,$is_update);
    }

}
