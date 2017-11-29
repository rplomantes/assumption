<?php

namespace App\Http\Controllers\Cashier;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;

class StudentLedger extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    function view($idno){
     if(Auth::user()->accesslevel==env("CASHIER")){
      $user = \App\User::where('idno',$idno)->first();
      $ledgers = \App\User::where('idno',$idno)->get();
      $status = \App\Statu::where('idno',$idno)->first();
      $payments =
     }       
    }
}
