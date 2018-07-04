<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;

class BookOfAccount extends Controller
{
    //
      public function __construct()
    {
        $this->middleware('auth');
    }
    function cash_receipt(){
        $cash_receipt = \App\Accounting::selectRaw('accounting_code,accounting_name, sum(debit) as debit,'
                . 'sum(credit) as credit')->whereRaw('transaction_date');
    }
}
