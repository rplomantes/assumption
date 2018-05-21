<?php

namespace App\Http\Controllers\RegistrarCollege\Advising;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Session;

class AdvisingController extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $advising_school_year = \App\CtrAdvisingSchoolYear::where('academic_type', 'College')->first();
            return view('reg_college.advising.set_up', compact('advising_school_year'));
        }
    }
    
    function save(Request $request){
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $school_year = $request->school_year;
            $period = $request->period;
            $is_available = $request->availability;
            
            $update = \App\CtrAdvisingSchoolYear::where('academic_type', 'College')->first();
            $update->school_year = $school_year;
            $update->period = "$period";
            $update->is_available = $is_available;
            $update->update();
            
            if($is_available == 1){
                $message = "OPEN!";
            } else {
                $message = "CLOSED!";
            }
            
            Session::flash('message', "Advising is now $message");
            return redirect('registrar_college/advising/set_up');
            
        }
    }
}
