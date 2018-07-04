<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class ScheduleOfFees extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    function index(){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            return view('accounting.schedule_of_fees');
        }
        
    }
    
    function view(Request $request){
        if (Auth::user()->accesslevel == env('ACCTNG_STAFF') || Auth::user()->accesslevel == env('ACCTNG_HEAD')) {
            $level=$request->level;
            $program_code=$request->program_code;
            $period=$request->period;
            
            if($request->level == "1st Year" || $request->level == "2nd Year" || $request->level == "3rd Year" || $request->level == "4th Year" || $request->level == "5th Year"){
                $miscellaneous_fees = \App\CtrCollegeOtherFee::where('level', $request->level)->where('program_code', $request->program_code)->where('period', $request->period)->where('category_switch', 1)->get();
                $other_fees = \App\CtrCollegeOtherFee::where('level', $request->level)->where('program_code', $request->program_code)->where('period', $request->period)->where('category_switch', 2)->get();
                $depository_fees = \App\CtrCollegeOtherFee::where('level', $request->level)->where('program_code', $request->program_code)->where('period', $request->period)->where('category_switch', 3)->get();
                $tuition_fee = \App\CtrCollegeTuitionFee::where('level', $request->level)->where('program_code', $request->program_code)->where('period', $request->period)->first();
                $other_collections = \App\OtherCollection::where('category_switch', 0)->get();
                $amount = $tuition_fee->per_unit;
                $program_code = \App\CtrAcademicProgram::where('program_code', $program_code)->first()->program_name;
            }else if($request->level == "Grade 11" || $request->level == "Grade 12"){
                $miscellaneous_fees = \App\CtrBedFee::where('level', $request->level)->where('category_switch', 1)->get();
                $other_fees = \App\CtrBedFee::where('level', $request->level)->where('category_switch', 2)->get();
                $depository_fees = \App\CtrBedFee::where('level', $request->level)->where('category_switch', 3)->get();
                $tuition_fee = \App\CtrBedFee::where('level', $request->level)->where('category_switch', 6)->first();
                $other_collections = \App\ShsOtherCollection::get();
                $amount = $tuition_fee->amount;
                $program_code = "";
            }else{
                $miscellaneous_fees = \App\CtrBedFee::where('level', $request->level)->where('category_switch', 1)->get();
                $other_fees = \App\CtrBedFee::where('level', $request->level)->where('category_switch', 2)->get();
                $depository_fees = \App\CtrBedFee::where('level', $request->level)->where('category_switch', 3)->get();
                $tuition_fee = \App\CtrBedFee::where('level', $request->level)->where('category_switch', 6)->first();
                $other_collections = \App\OtherCollection::get();
                $amount = $tuition_fee->amount;
                $program_code = "";
            }
            
            $pdf = PDF::loadView('accounting.print_schedule_of_fees', compact('level','program_code','period','other_fees','miscellaneous_fees','depository_fees','other_collections' ,'amount'));
            $pdf->setPaper('letter', 'portrait');
            return $pdf->stream("schedule_of_fees.pdf");
        }
    }
    function plan(){
        return view('accounting.schedule_of_plan');
    }
}
