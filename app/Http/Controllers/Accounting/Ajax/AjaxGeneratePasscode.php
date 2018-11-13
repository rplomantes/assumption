<?php

namespace App\Http\Controllers\Accounting\Ajax;

use App\Http\Controllers\Controller;
use DB;
use Request;
use Illuminate\Support\Facades\Input;
use Auth;

class AjaxGeneratePasscode extends Controller
{
    //
     public function __construct()
    {
        $this->middleware('auth');
    }
    
    function generatePasscode(){
        if(Request::ajax()){
            $idno = Input::get('idno');
            $passcode = mt_rand(100000, 999999);
            $add = new \App\AccountingPasscode;
            $add->generated_by_idno = $idno;
            $add->datetime_generated = date('Y-m-d H:i:s');
            $add->passcode = $passcode;
            $add->save();
            return $passcode;
            return view('accounting.ajax.get_passcode', compact('passcode'));
        }
    }
}
