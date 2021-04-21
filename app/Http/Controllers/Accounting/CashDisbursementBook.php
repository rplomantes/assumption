<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Excel;
use PDF;

class CashDisbursementBook extends Controller
{
    public function __construct() {
        $this->middleware("auth");
    }
    
    function index($date_from, $date_to){
        
        $accountings = \App\Accounting::where("accounting_type", env("DISBURSEMENT"))->whereBetween("transaction_date",[$date_from,$date_to])->where("is_reverse",0)->paginate(1000);
        $sundries = $accountings->whereNotIn("accounting_code", ["1046","7892","7893","7331","7311","7127","1061","2311","2301"]);
        
        return view("accounting.cash_disbursement_book.index",compact("payments","accountings","sundries","date_from","date_to"));
      
        
    }
    
    function generateExcel($date_from, $date_to, $page){
        
        $accountings = \App\Accounting::where("accounting_type", env("DISBURSEMENT"))->whereBetween("transaction_date",[$date_from,$date_to])->where("is_reverse",0)->paginate(1000,["*"],"page",$page);
        $sundries = $accountings->whereNotIn("accounting_code", ["1046","7892","7893","7331","7311","7127","1061","2311","2301"]);
        
        ob_end_clean();
        ob_start();
        Excel::create('Cash Disbursement', function($excel) use($accountings, $sundries, $date_from, $date_to) {
            $excel->sheet('Cash Disbursement', function($sheet) use($accountings, $sundries, $date_from, $date_to){
                    $sheet->loadView('accounting.cash_disbursement_book.excel',compact('accountings','sundries', 'date_from','date_to'))
                            ->setFontSize(10);
            });
        })->download('csv');
    }
    
    function generatePDF($date_from, $date_to, $page){
        
        $accountings = \App\Accounting::where("accounting_type", env("DISBURSEMENT"))->whereBetween("transaction_date",[$date_from,$date_to])->where("is_reverse",0)->paginate(1000,["*"],"page",$page);
        $sundries = $accountings->whereNotIn("accounting_code", ["1046","7892","7893","7331","7311","7127","1061","2311","2301"]);
        
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('accounting.cash_disbursement_book.pdf', compact('accountings','sundries', 'date_from','date_to'));
        $pdf->setPaper('legal', 'landscape');
        
        return $pdf->stream();
    }
}
