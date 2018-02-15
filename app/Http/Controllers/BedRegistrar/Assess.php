<?php

namespace App\Http\Controllers\BedRegistrar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;

class Assess extends Controller
{
    //
     public function __construct()
    {
        $this->middleware('auth');
    }
    
    function assess($idno){
        if(Auth::user()->accesslevel==env("REG_BE")){
            $user=  \App\User::where('idno',$idno)->first();
            if($user->academic_type=="BED"){
                $school_year = \App\CtrAcademicSchoolYear::where('academic_type','BED')->first()->school_year;
                $status = \App\Status::where('idno',$idno)->first();
                $ledgers = \App\Ledger::where('idno',$idno)->where('category_switch','<=','6')->get();
                $level = \App\BedLevel::where('idno',$idno)->where('school_year',$school_year)->first();
                if(count($status)>0){
                    if($status->status==env("ASSESSED")){
                        
                    } else{
                    return view('reg_be.assess',compact('user','status','ledgers','level'));    
                    } 
                } else {
                    return view('reg_be.assess',compact('user','status','ledgers','level'));
                }
                }
                    
            }
        }
    
    
    function post_assess(Request $request){
        if(Auth::user()->accesslevel == env("REG_BE")){
            $user = \App\User::where("idno",$request->idno)->first();
                if($user->academic_type == "BED"){
                    $status = \App\Status::where('idno',$request->idno)->first();
                    if($tatus->status == 0 ){
                        $schoolyear =  \App\CtrAcademicSchoolYear::where('academic_type',"BED")->first();
                        DB::beginTransaction();
                        $this->addGrades($request, $schoolyear);
                        $this->addLedger($request, $schoolyear);
                        $this->addStatus($request);
                        DB::commit();
                    }
                    else if($status->status >= env('ASSESSED')){
                        
                    }
                else{
                    view('unauthorized');
                }    
            }  
            
        }
    }
    function changeStatus($id){
        
    }
    function addGrades($idno, $schoolyear){
        
    }
    
    function addLedger($idno,$schoolyear){
        
        
    }
    
    function enrollment_statistics($school_year){
        
        $statistics = \App\BedLevel::selectRaw("sort_by, strand,section, count(*) as count")
                ->whereRaw("school_year=$school_year AND sort_by <= '10' AND status='3'")->groupBy('sort_by','strand','section','strand')
                ->get();
        $abm =\App\BedLevel::selectRaw("sort_by, strand, section, count(*) as count")
                ->whereRaw("school_year=$school_year AND strand = 'ABM' AND status='3'")->groupBy('sort_by','strand','section','strand')
                ->get();
        
        $humms=\App\BedLevel::selectRaw("sort_by, strand, section, count(*) as count")
                ->whereRaw("school_year=$school_year AND strand = 'HUMMS' AND status='3'")->groupBy('sort_by','strand','section','strand')
                ->get();
        
        $stem =\App\BedLevel::selectRaw("sort_by, strand, section, count(*) as count")
                ->whereRaw("school_year=$school_year AND strand = 'STEM' AND status='3'")->groupBy('sort_by','strand','section','strand')
                ->get();
      
        return view('reg_be.enrollment_statistics',compact('statistics','abm','humms','stem'));
    }
    
    function addMainFee($level,$idno){
        
    }
    function addSRFee($level,$track, $idno){
        
    }
    function addOptionFee($level,$idno){
        
    }
}
