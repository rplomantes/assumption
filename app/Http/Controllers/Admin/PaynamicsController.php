<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PaynamicsController extends Controller
{
    //
    function manual_posting(){
        return view('admin.paynamics_manual_posting');
    }
    
    function credential_index(){
        $paygate_details = \App\PaygateDetail::first();
        return view('admin.paygate_details',compact('paygate_details'));
    }
    
    function credential_update(Request $request){
        $paygate_details = \App\PaygateDetail::first();
        $paygate_details->merchantid = $request->merchantid;
        $paygate_details->merchantkey = $request->merchantkey;
        $paygate_details->merchantip = $request->merchantip;
        $paygate_details->merchantsec = $request->merchantsec;
        $paygate_details->save();
        
        \App\Http\Controllers\Admin\Logs::log("Update paygate details");
        
        return redirect('admin/paynamics_credentials');
    }
    
    function portal_instruction(){
        $message = \App\OnlinePaymentNote::first()->message;
        return view('admin.online_payment_instruction',compact('message'));
    }
    
    function update_portal_instruction(Request $request){
        $message = \App\OnlinePaymentNote::first();
        $message->message = $request->message;
        $message->save();
        \App\Http\Controllers\Admin\Logs::log("Update online payment portal instructions");
        
        return redirect('online_payment_portal_instructions');
    }
}
