<?php

namespace App\Http\Controllers\RegistrarCollege\Graduates;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class BatchRanking extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth');
    }

    function view() {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $years = \App\StudentInfo::distinct()->where('date_of_grad', "!=", null)->orderBy('date_of_grad', 'dsc')->get(array('date_of_grad'));
            return view('reg_college.graduates.view_batch_ranking', compact('years'));
        }
    }
}
