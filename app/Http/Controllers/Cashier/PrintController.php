<?php

namespace App\Http\Controllers\Cashier;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;
use App\Http\Controllers\Cashier\StudentLedger;
use App\Http\Controllers\Cashier\StudentReservation;
use PDF;

class PrintController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    function printreceipt ($reference_id){
        $payment= \App\Payment::where('reference_id',$reference_id)->first();
        $status=  \App\Status::where('idno',$payment->idno)->first();
        $accountings = \App\Accounting::where('reference_id',$reference_id)->where('accounting_type','1')->get();
        $receipt_details = DB::Select("Select receipt_details, sum(credit) as credit from accountings where reference_id = "
                . "'$reference_id' and credit > '0' and accounting_type = '1' group by receipt_details, reference_id");
        $receipt_less = DB::Select("Select receipt_details, sum(debit) as debit from accountings where reference_id = "
                . "'$reference_id' and receipt_details != 'Cash' and debit > '0' and accounting_type='1'  group by receipt_details, reference_id");
        
        
        $pdf = PDF::loadView('cashier.print_receipt', compact('payment','status','accountings','receipt_details','receipt_less'));
        $pdf->setPaper(array(0, 0, 432.00, 468.0));
        return $pdf->stream("official_receipt.pdf");
    }
    
}
