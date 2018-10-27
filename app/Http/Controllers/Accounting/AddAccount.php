<?php

namespace App\Http\Controllers\Accounting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;

class AddAccount extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    function add_to_account($idno){
        if(Auth::user()->accesslevel==env("ACCTNG_STAFF") || Auth::user()->accesslevel==env("ACCTNG_HEAD")){
            $other_accounts = \App\Ledger::where('idno',$idno)->where('category_switch',env("OTHER_MISC"))->get();
            $user =  \App\User::where('idno',$idno)->first();
            $status = \App\Status::where('idno',$idno)->first();
            $chart_of_accounts=  \App\ChartOfAccount::get();
            return view('accounting.add_to_account',compact('other_accounts','user','status','chart_of_accounts'));
        }
     
    }
    function set_other_payment(){
        $other_payments = \App\OtherPayment::get();
        $chart_of_accounts = \App\ChartOfAccount::get();
         return view('accounting.set_other_payment',compact('other_payments','chart_of_accounts'));
     }  
     function post_set_other_payment(Request $request){
         $rules = [
            'particular' => 'required',
            'accounting_code' => 'required',
        ];
         if($this->validate($request, $rules)){
             $add = new \App\OtherPayment;
             $add->subsidiary = $request->particular;
             $add->accounting_code = $request->accounting_code;
             $add->accounting_name = \App\ChartOfAccount::where('accounting_code',$request->accounting_code)->first()->accounting_name;
             $add->save();
             return redirect(url('/accounting','set_other_payment'));
         }
         
     }
     function post_add_to_account(Request $request){
         if(Auth::user()->accesslevel==env('ACCTNG_STAFF') || Auth::user()->accesslevel==env("ACCTNG_HEAD"))
         $addledger = new \App\Ledger;
         $addledger->idno = $request->idno;
         $status = \App\Status::where('idno',$request->idno)->first();
         if(count($status)>0){
             if($status->academic_type == "BED" && $status->status > "0"){
               $level=  \App\BedLevel::where('idno',$request->idno)
                       ->where('school_year',$status->school_year)
                       ->where('period',$status->period)->first();
             } else if($status->academic_type=="College"){
                if($status->academic_type == "BED" && $status->status > "0"){
               $level=  \App\BedLevel::where('idno',$request->idno)
                       ->where('school_year',$status->school_year)
                       ->where('period',$status->period)->first(); 
             }
            }
         }
         if(isset($level)){
         if(count($level)>0){
                if($status->academic_type=="BED")
                $addledger->department = $level->department;
                $addledger->track = $level->track;
                $addledger->strand = $level->strand;
                $addledger->level = $level->level;
                $addledger->school_year = $level->school_year;
                $addledger->period = $status->period;
                }
                else if($status->academic_type=="College"){
                    $addledger->program_code = $level->program_code;
                    $addledger->level = $level->level;
                    $addledger->school_year = $level->school_year;
                    $addledger->period = $status->period;
                }
         }
         $addledger->category = "Other Miscellaneous";
         $addledger->subsidiary = $request->particular;
         $addledger->receipt_details = $request->particular;
         $addledger->accounting_code = $request->accounting_code;
         $addledger->accounting_name = \App\ChartOfAccount::where('accounting_code',$request->accounting_code)->first()->accounting_name;
         $addledger->category_switch = env("OTHER_MISC");
         $addledger->amount=$request->amount;
         $addledger->save();
         return redirect(url('/accounting',array('add_to_account',$request->idno)));
      
      
            }
         
     function remove_other_payment($id){
         if(Auth::user()->accesslevel==env("ACCTNG_STAFF") || Auth::user()->accesslevel==env("ACCTNG_HEAD"))
         $remove = \App\Ledger::find($id);
         $idno = $remove->idno;
         if($remove->category_switch==env('OTHER_MISC')){
             $remove->delete();
         }
         return redirect(url('/accounting',array('add_to_account',$idno)));
     }
     
    function remove_set_other_payment($id){
        if(Auth::user()->accesslevel==env("ACCTNG_STAFF") || Auth::user()->accesslevel==env("ACCTNG_HEAD")){
            $remove = \App\OtherPayment::find($id);
            $remove->delete();
            return redirect(url('/accounting','set_other_payment'));
        }
    }
     
}
