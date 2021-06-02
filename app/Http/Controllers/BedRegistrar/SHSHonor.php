<?php

namespace App\Http\Controllers\BedRegistrar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class SHSHonor extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        if (Auth::user()->accesslevel == env('REG_BE')) {
            $years = \App\BedLevel::distinct()->orderBy('school_year', 'dsc')->get(array('school_year'));
            $levels = \App\CtrAcademicProgram::distinct()->orderBy('sort_by', 'asc')->where('academic_code','SHS')->get(array('level','sort_by'));
            return view('reg_be.shs_honor.index', compact('years','levels'));
        }
    }
}
