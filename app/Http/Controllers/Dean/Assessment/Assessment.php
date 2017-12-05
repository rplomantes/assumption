<?php

namespace App\Http\Controllers\Dean\Assessment;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;

class Assessment extends Controller {

    //

    public function __construct() {
        $this->middleware('auth');
    }

    function assess($idno) {
        if (Auth::user()->accesslevel == 10) {
            $status = \App\Status::where('idno', $idno)->first();
            if (count($status) > 0) {
                if ($status->status >= 2) {
                    return view('dean.assessment.enrolled', compact('status', 'idno'));
                } else {
                    return view('dean.assessment.assess', compact('status', 'idno'));
                }
            } else {
                return view('dean.assessment.assess', compact('status', 'idno'));
            }
        }
    }

}
