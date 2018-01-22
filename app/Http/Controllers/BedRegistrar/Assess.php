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
    
    function asssess($idno){
        if(Auth::user()->accesslevel==env("REG_BE")){
            $user=  \App\User::where('idno',$idno)->first();
            if($user->academic_type=="BED"){
                $status = \App\Status::where('idno',$idno)->first();
                $ledgers = \App\Ledger::where('idno',$idno)->where('category_switch','<=','6')->get();
                return view('reg_be.assess',compact('user','status','ledgers'));
            }
        }
    }
    
    function postassess($idno){
        if(Auth::user()->accesslevel == env("REG_BE")){
            $status = \App\Status::where("idno",$idno)->first();
                if($status->academic_type != "College"){
                    if($status->status == env('ASSESSED')){
                        $schoolyear =  \App\CtrAcademicSchoolYear::where('academic_type',"BED")->first();
                        DB::beginTransaction();
                        $this->changeStatus($id);
                        $this->addGrades($idno, $schoolyear);
                        $this->addLedger($idno, $schoolyear);
                        DB::commit();
                    }
                    else if($status->status == env('ENROLLED')){
                        
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
                ->whereRaw("school_year=$school_year AND sort_by <= '10'")->groupBy('sort_by','strand','section','strand')
                ->get();
        $abm =\App\BedLevel::selectRaw("sort_by, strand, section, count(*) as count")
                ->whereRaw("school_year=$school_year AND strand = 'ABM'")->groupBy('sort_by','strand','section','strand')
                ->get();
        
        $humms=\App\BedLevel::selectRaw("sort_by, strand, section, count(*) as count")
                ->whereRaw("school_year=$school_year AND strand = 'HUMMS'")->groupBy('sort_by','strand','section','strand')
                ->get();
        
        $stem =\App\BedLevel::selectRaw("sort_by, strand, section, count(*) as count")
                ->whereRaw("school_year=$school_year AND strand = 'STEM'")->groupBy('sort_by','strand','section','strand')
                ->get();
      
        return view('reg_be.enrollment_statistics',compact('statistics','abm','humms','stem'));
    }
}
