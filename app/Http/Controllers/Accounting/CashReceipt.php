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
        
        $accountings = \App\Accounting::where("accounting_type", env("CASH"))->whereBetween("transaction_date",[$date_from,$date_to])->where("is_reverse",0)->paginate(1000);
        $sundries = $accountings->whereNotIn("accounting_code", ["1046","1201","6801","2031","1231","1242"]);
        
        
        return view("accounting.cashreceipt.index",compact("payments","accountings","sundries","date_from","date_to"));
      
        
    }
    
    function generateExcel($date_from, $date_to, $page){
        
        $accountings = \App\Accounting::where("accounting_type", env("CASH"))->whereBetween("transaction_date",[$date_from,$date_to])->where("is_reverse",0)->paginate(1000,["*"],"page",$page);
        $sundries = $accountings->whereNotIn("accounting_code", ["1046","1201","6801","2031","1231","1242"]);
        
        ob_end_clean();
        ob_start();
        Excel::create('Cash Receipts', function($excel) use($accountings, $sundries, $date_from, $date_to) {
            $excel->sheet('Cash Receipts', function($sheet) use($accountings, $sundries, $date_from, $date_to){
                    $sheet->loadView('accounting.cashreceipt.excel',compact('accountings','sundries', 'date_from','date_to'))
                            ->setFontSize(10);
            });
        })->download('csv');
    }
    
    function generatePDF($date_from, $date_to, $page){
        
        $accountings = \App\Accounting::where("accounting_type", env("CASH"))->whereBetween("transaction_date",[$date_from,$date_to])->where("is_reverse",0)->paginate(1000,["*"],"page",$page);
        $sundries = $accountings->whereNotIn("accounting_code", ["1046","1201","6801","2031","1231","1242"]);
        
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('accounting.cashreceipt.pdf', compact('accountings','sundries', 'date_from','date_to'));
        $pdf->setPaper('legal', 'landscape');
        
        return $pdf->stream();
    }
}
