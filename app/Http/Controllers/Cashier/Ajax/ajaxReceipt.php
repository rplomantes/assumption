<?php

namespace App\Http\Controllers\Cashier\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
Use Illuminate\Support\Facades\DB;
use Request;

class ajaxReceipt extends Controller
{
    //
     function reason_reverserestore(){
        if(Request::ajax()){
            $payment_reference_id = Input::get('payment_reference_id');
            $reason = Input::get('reason');
            
            $update_receipt = \App\Payment::where('reference_id', $payment_reference_id)->first();
            $update_receipt->reason_reverse = $reason;
            $update_receipt->update();
            
        }
    }
}
