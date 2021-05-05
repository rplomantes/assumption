<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Request as Request2;

class CashReceiptSettings extends Controller {

    //
    public function __construct() {
        $this->middleware("auth");
    }

    function index() {
        $accounting_codes = \App\CtrCashReceipt::orderBy('sort_no')->get();
        $chart_of_accounts = \App\ChartOfAccount::all();

        return view("accounting.cashreceipt.settings.index", compact('accounting_codes','chart_of_accounts'));
    }

    function delete($id) {
        $delete = \App\CtrCashReceipt::find($id);
        $delete->delete();
        return redirect(url('/accounting/settings/cashreceipt'));
    }

    function getDetails() {
        if (Request2::ajax()) {
            $id = Input::get('id');
            $details = \App\CtrCashReceipt::find($id);
            $chart_of_accounts = \App\ChartOfAccount::all();
            return view('accounting.cashreceipt.settings.details', compact('details', 'chart_of_accounts'));
        }
    }

    function update() {
        $id = Input::get('id');
        $accounting_code = Input::get('accounting_code');
        $debit_or_credit = Input::get('debit_or_credit');
        $sort_no = Input::get('sort_no');

        $update = \App\CtrCashReceipt::find($id);
        $update->accounting_code = $accounting_code;
        $update->debit_or_credit = $debit_or_credit;
        $update->sort_no = $sort_no;
        $update->save();

        return redirect(url('/accounting/settings/cashreceipt'));
    }

    function add() {
        $id = Input::get('id');
        $accounting_code = Input::get('accounting_code');
        $debit_or_credit = Input::get('debit_or_credit');
        $sort_no = Input::get('sort_no');

        $add = new \App\CtrCashReceipt();
        $add->accounting_code = $accounting_code;
        $add->debit_or_credit = $debit_or_credit;
        $add->sort_no = $sort_no;
        $add->save();

        return redirect(url('/accounting/settings/cashreceipt'));
    }

}
