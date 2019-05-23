<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mail;

class EmailBlast extends Controller {

    //

    public function __construct() {
        $this->middleware('auth');
    }

    function email() {
        if (Auth::user()->accesslevel == env("ADMIN")) {
            DB::beginTransaction();
            $emails = \App\EmailBlast::where('is_done', 0)->join('admission_heds', 'admission_heds.idno', '=', 'email_blasts.idno')
                            ->where(function ($query) {
                                $query->where("admission_heds.admission_status", "Regular")
                                ->orWhere("admission_heds.admission_status", "Scholar");
                            })->get();
            foreach ($emails as $email) {
                
                $admission = \App\AdmissionHed::where('idno', $email->idno)->first();
                $admission->is_first_enrollment = 1;
                $admission->save();
                
                $blast = \App\EmailBlast::where('idno', $email->idno)->first();
                $blast->is_done = 1;
                $blast->save();
                
                $this->sendEmail($email->idno);
                
            }
            DB::Commit();
            return "Done";
        }
    }

    function sendEmail($idno) {
        $applicant_details = \App\User::where('idno', $idno)->first();
        $applicant_details->password = bcrypt($idno);
        $applicant_details->status = 1;
        $applicant_details->save();
        
        $data = array('name' => $applicant_details->firstname . " " . $applicant_details->lastname, 'email' => $applicant_details->email);
        Mail::send('admin.mail', compact('applicant_details'), function($message) use($applicant_details) {
            $message->to($applicant_details->email, $applicant_details->firstname . " " . $applicant_details->lastname)
                    ->subject('AC Portal Access');
            $message->from('ac.sis@assumption.edu.ph', "AC Student Information System");
        });
    }

}
