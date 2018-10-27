<?php

namespace App\Http\Controllers\AdmissionBED;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;
use Session;
use Mail;
use PDF;

class info extends Controller
{
    //

    public function __construct() {
        $this->middleware('auth');
    }

    function info($idno) {
        if (Auth::user()->accesslevel == env("REG_BE") || Auth::user()->accesslevel == env("ADMISSION_BED")) {

            
            $addprofile = \App\BedProfile::where('idno', $idno)->first();
            if (count($addprofile) == 0) {
                $addpro = new \App\BedProfile;
                $addpro->idno = $idno;
                $addpro->save();
            }
            $addparent = \App\BedParentInfo::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\BedParentInfo;
                $addpar->idno = $idno;
                $addpar->save();
            }
            $addparent = \App\BedReceivedHonor::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\BedReceivedHonor;
                $addpar->idno = $idno;
                $addpar->save();
            }
            $addparent = \App\BedApplicantFail::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\BedApplicantFail;
                $addpar->idno = $idno;
                $addpar->save();
            }
            $addparent = \App\BedRepeat::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\BedRepeat;
                $addpar->idno = $idno;
                $addpar->save();
            }
            $addparent = \App\BedProbation::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\BedProbation;
                $addpar->idno = $idno;
                $addpar->save();
            }
            $addparent = \App\BedExtraActivity::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\BedExtraActivity;
                $addpar->idno = $idno;
                $addpar->save();
            }
            $addparent = \App\BedChurchInvolvement::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\BedChurchInvolvement;
                $addpar->idno = $idno;
                $addpar->save();
            }
            $addparent = \App\BedUndergoneTherapy::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\BedUndergoneTherapy;
                $addpar->idno = $idno;
                $addpar->save();
            }
            $addparent = \App\BedLimitations::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\BedLimitations;
                $addpar->idno = $idno;
                $addpar->save();
            }
            $addparent = \App\BedRequirement::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\BedRequirement;
                $addpar->idno = $idno;
                $addpar->save();
            }
            $addparent = \App\BedSiblings::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\BedSiblings;
                $addpar->idno = $idno;
                $addpar->save();
            }
            $addparent = \App\BedOtherAlumni::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\BedOtherAlumni;
                $addpar->idno = $idno;
                $addpar->save();
            }
            $addparent = \App\InterviewStudent::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\InterviewStudent;
                $addpar->idno = $idno;
                $addpar->save();
            }
            $addparent = \App\GroupStudent::where('idno', $idno)->first();
            if (count($addparent) == 0) {
                $addpar = new \App\GroupStudent;
                $addpar->idno = $idno;
                $addpar->save();
            }

            $user = \App\User::where('idno', $idno)->first();
            $status = \App\Status::where('idno', $idno)->first();
            $info = \App\BedProfile::where('bed_profiles.idno', $idno)->join('bed_parent_infos', 'bed_parent_infos.idno','=','bed_profiles.idno')->first();
            return view("admission-bed.info", compact('user', 'info','status'));
        }
    }
    
    function approve_application($idno){
        if (Auth::user()->accesslevel == env("ADMISSION_BED")) {
            $status = \App\Status::where('idno', $idno)->first();
            $status->status=0;
            $status->date_admission_finish=date('Y-m-d');
            $status->save();
            $this->sendEmail($idno, "Approved");
            \App\Http\Controllers\Admin\Logs::log("Approved admission status application of $idno.");
            return redirect('admissionbed/info/'.$idno);
        }
    }
    
    function disapprove_application($idno){
        if (Auth::user()->accesslevel == env("ADMISSION_BED")) {
            $status = \App\Status::where('idno', $idno)->first();
            $status->status=env("REGRET_FINAL");
            $status->date_admission_finish=date('Y-m-d');
            $status->save();
            $user = \App\User::where('idno', $idno)->first();
            $user->status=0;
            $user->save();
            $this->sendEmail($idno, "Regret");
            \App\Http\Controllers\Admin\Logs::log("Disapproved admission status application of $idno.");
            return redirect('/');
        }
    }
    
    function sendEmail($idno, $type){
        $applicant_details = \App\User::where('idno', $idno)->first();
        Mail::send('admission-bed.mail-result-application',compact('applicant_details','type'), function($message) use($applicant_details) {
         $message->to($applicant_details->email, $applicant_details->firstname." ".$applicant_details->lastname)
                 ->subject('AC Admission Application Status');
         $message->from('ac.sis@assumption.edu.ph',"AC Student Information System");
        });
    }
    
    function printInfo($idno){
        $user = \App\User::where('idno', $idno)->first();
        $status = \App\Status::where('idno', $idno)->first();
        $info = \App\BedProfile::where('bed_profiles.idno', $idno)->join('bed_parent_infos', 'bed_parent_infos.idno','=','bed_profiles.idno')->first();
//        return view("admission-bed.print_info", compact('user', 'info','status'));
        $pdf = PDF::loadView("admission-bed.print_info", compact('user', 'info','status'));
        $pdf->setPaper('letter','portrait');
        return $pdf->stream('information-sheet-'.$idno.'.pdf');
    }
}
