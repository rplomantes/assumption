<?php

namespace App\Http\Controllers\Accounting\Ajax;


use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
Use Illuminate\Support\Facades\DB;
use Request;

class GetOtherPayment extends Controller
{
    //
     public function __construct()
    {
        $this->middleware('auth');
    }
    
    function getotherpayment(){
        if(Request::ajax()){
         $search = \App\OtherPayment::where('subsidiary','like',Input::get('search')."%")->get();
         return view('accounting.ajax.getotherpayment',compact('search'));   
        }
    }
}
