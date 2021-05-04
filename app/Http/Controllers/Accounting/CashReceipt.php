<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Excel;
use PDF;

class CashReceipt extends Controller
{
    public function __construct() {
        $this->middleware("auth");
    }
    
    function index($date_from, $date_to){
        $accounting_codes = \App\CtrCashReceipt::orderBy('sort_no')->get();
        $account_code = \App\CtrCashReceipt::orderBy('sort_no')->pluck('accounting_code');
        
        $accountings = \App\Accounting::where("accounting_type", env("CASH"))->whereBetween("transaction_date",[$date_from,$date_to])->where("is_reverse",0)->paginate(1000);
        $sundries = $accountings->whereNotIn("accounting_code", $account_code);
      
        return view("accounting.cashreceipt.index",compact("accountings","sundries","date_from","date_to",'accounting_codes'));
      
        
    }
    
    function generateExcel($date_from, $date_to, $page){
        $accounting_codes = \App\CtrCashReceipt::orderBy('sort_no')->get();
        $account_code = \App\CtrCashReceipt::orderBy('sort_no')->pluck('accounting_code');
        
        $accountings = \App\Accounting::where("accounting_type", env("CASH"))->whereBetween("transaction_date",[$date_from,$date_to])->where("is_reverse",0)->paginate(1000);
        $sundries = $accountings->whereNotIn("accounting_code", $account_code);
        
        ob_end_clean();
        ob_start();
        Excel::create('Cash Receipts', function($excel) use($accountings, $sundries, $date_from, $date_to,$accounting_codes) {
            $excel->sheet('Cash Receipts', function($sheet) use($accountings, $sundries, $date_from, $date_to,$accounting_codes){
                    $sheet->loadView('accounting.cashreceipt.excel',compact('accountings','sundries', 'date_from','date_to','accounting_codes'))
                            ->setFontSize(10);
            });
        })->download('csv');
    }
    
    function generatePDF($date_from, $date_to, $page){
        $accounting_codes = \App\CtrCashReceipt::orderBy('sort_no')->get();
        $account_code = \App\CtrCashReceipt::orderBy('sort_no')->pluck('accounting_code');
        
        $accountings = \App\Accounting::where("accounting_type", env("CASH"))->whereBetween("transaction_date",[$date_from,$date_to])->where("is_reverse",0)->paginate(1000);
        $sundries = $accountings->whereNotIn("accounting_code", $account_code);
        
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('accounting.cashreceipt.pdf', compact('accountings','sundries', 'date_from','date_to','accounting_codes'));
        $pdf->setPaper('legal', 'landscape');
        
        return $pdf->stream();
    }
}
