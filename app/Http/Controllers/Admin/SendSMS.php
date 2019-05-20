<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SendSMS extends Controller {

//
    public function __construct() {
        $this->middleware('json');
    }

    function sms() {
        return view('admin.sms');
    }

    function send_sms(Request $request) {
        return $request;
    }

}
