<?php

namespace App\Http\Controllers\Dean;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class srf extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        if (Auth::user()->accesslevel == env('DEAN')) {
            return view('dean.srf.view_srf');
        }
    }
}
