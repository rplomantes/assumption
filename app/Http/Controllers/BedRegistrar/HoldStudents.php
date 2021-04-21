<?php

namespace App\Http\Controllers\BedRegistrar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HoldStudents extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function view() {
        if (Auth::user()->accesslevel == env("REG_BE")) {
            $hold_students = \App\HoldGrade::join('users','users.idno','=','hold_grades.idno')
                    ->join('statuses','statuses.idno','=','hold_grades.idno')
                    ->orderBy('users.lastname')
                    ->get();
            return view('reg_be.hold_students',compact('hold_students'));
        }
    }

    function add($idno) {
        if (Auth::user()->accesslevel == env("REG_BE")) {
            
            $check = \App\HoldGrade::where('idno',$idno)->first();
            if(!$check){
                $add = new \App\HoldGrade();
                $add->idno = $idno;
                $add->hold_by = Auth::user()->idno;
                $add->save();
            }
            \App\Http\Controllers\Admin\Logs::log("Hold $idno in viewing grades.");
            
            return redirect('/bedregistrar/hold_students');
        }
    }

    function delete($idno) {
        if (Auth::user()->accesslevel == env("REG_BE")) {
            
            $check = \App\HoldGrade::where('idno',$idno)->first();
            if($check){
                $check->delete();
            }
            \App\Http\Controllers\Admin\Logs::log("Remove $idno in holding of viewing grades.");
            
            return redirect('/bedregistrar/hold_students');
        }
    }

}
