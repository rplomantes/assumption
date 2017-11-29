<?php

namespace App\Http\Controllers\Dean;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;

class Assessment extends Controller
{
    //
    
      public function __construct()
    {
        $this->middleware('auth');
    }
    
    function assess($idno){
        if(Auth::user()->accessleve == env("DEAN")){
        $status = \App\Status::where('idno',$idno)->first();
        if(count($status)>0){
            if($tatus->status >= 2){
            return view('dean.assessment.enrolled',compact('status','idno'));
            } else {
                return view('dean.assessment.assess',compact('status','idno'));
            }
        } else{
            return view('dean.assessment.assess',compact('status','idno'));
        }
    }}
}
