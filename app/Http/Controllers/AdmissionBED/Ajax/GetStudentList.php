<?php

namespace App\Http\Controllers\AdmissionBED\Ajax;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
Use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Request;

class GetStudentList extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env("ADMISSION_BED")) {
                $search = Input::get('search');
                $lists = \App\User::Where("lastname", "like", "%$search%")
                                ->orWhere("firstname", "like", "%$search%")->orWhere("idno", $search)->get();
                return view('admission-bed.ajax.getstudentlist', compact('lists'));
            }
        }
    }

    function view_list() {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env("ADMISSION_BED")) {
                $schoolyear = Input::get('school_year');
                $level = Input::get('level');
                $section = Input::get('section');
                $strand = Input::get("strand");
                if ($level == "Grade 11" || $level == "Grade 12") {
                    $status = \App\BedLevel::where('level', $level)->where('section', $section)
                            ->where('strand', $strand)->where('school_year', $schoolyear)
                            ->get();
                } else {
                    $status = \App\BedLevel::where('level', $level)->where('section', $section)
                            ->where('school_year', $schoolyear)
                            ->get();
                }
                return view("reg_be.ajax.view_list", compact("status", "level", "section", 'strand'));
            }
        }
    }

    function updateSched() {
        if (Request::ajax()) {
            if (Auth::user()->accesslevel == env("ADMISSION_BED")) {
                $idno = Input::get('idno');
                $testing_id = Input::get('testing_id');
                
                $update = \App\TestingStudent::where('idno', $idno)->first();
                $update->schedule_id = $testing_id;
                $update->save();
                
                $data=array('name'=>$applicant_details->firstname." ".$applicant_details->lastname, 'email'=>$applicant_details->email);
                Mail::send('cashier.pre_registration.mail',compact('request','reference_id','applicant_details'), function($message) use($applicant_details) {
                 $message->to($applicant_details->email, $applicant_details->firstname." ".$applicant_details->lastname)
                         ->subject('Assumption College Payment Confirmation');
                 $message->from('support@assumption.edu.ph',"AC Treasurer's Office");
                });
                
            }
        }
    }

}
