<?php

namespace App\Http\Controllers\Dean;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;

class Record extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth');
    }

    function view_info($idno) {
        if (Auth::user()->accesslevel == env('DEAN')) {
            $user = \App\User::where('idno', $idno)->first();
            $info = \App\StudentInfo::where('idno', $idno)->first();
            return view('dean.view_info', compact('idno', 'user', 'info'));
        }
    }
}
