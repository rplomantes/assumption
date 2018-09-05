<?php

namespace App\Http\Controllers\BedRegistrar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;

class Registration extends Controller {

    //

    public function __construct() {
        $this->middleware('auth');
    }

    function register() {
        if (Auth::user()->accesslevel == env("REG_BE") || Auth::user()->accesslevel == env("ADMISSION_BED")) {
            $referenceid = uniqid();
            return view('reg_be.registration', compact('referenceid'));
        }
    }

    function post_register(Request $request) {
        if (Auth::user()->accesslevel == env("REG_BE") || Auth::user()->accesslevel == env("ADMISSION_BED")) {
            $validate = $request->validate([
                'firstname' => 'required',
                'lastname' => 'required',
            ]);

            if ($validate) {
                DB::beginTransaction();
                $addstudent = new \App\User;
                $addstudent->idno = $request->referenceid;
                $addstudent->lastname = $request->lastname;
                $addstudent->firstname = $request->firstname;
                $addstudent->middlename = $request->middlename;
                $addstudent->extensionname = $request->extensionname;
                $addstudent->is_foreign = $request->is_foreign;
                $addstudent->academic_type = "BED";
                $addstudent->lrn = $request->lrn;
                $addstudent->save();

                $addprofile = new \App\BedProfile;
                $addprofile->idno = $request->referenceid;
                $addprofile->date_of_birth = $request->date_of_birth;
                $addprofile->street = $request->street;
                $addprofile->barangay = $request->barangay;
                $addprofile->municipality = $request->municipality;
                $addprofile->province = $request->province;
                $addprofile->zip = $request->zip;
                $addprofile->tel_no = $request->tel_no;
                $addprofile->cell_no = $request->cell_no;
                $addprofile->save();

                $addstatus = new \App\Status;
                $addstatus->idno = $request->referenceid;
                $addstatus->section = "";
                $addstatus->status = 0;
                $addstatus->academic_type = "BED";
                $addstatus->save();
                
                \App\Http\Controllers\Admin\Logs::log("Register new student - [$request->referenceid]: $request->lastname, $request->firstname $request->middlename.");
                DB::Commit();
                return view('reg_be.successfull');
            }
        }
    }

    function info($idno) {
        if (Auth::user()->accesslevel == env("REG_BE")) {
            $student = \App\User::where("idno", $idno)->first();
            $bed_profile = \App\BedProfile::where("idno", $idno)->first();
            return view("reg_be.info", compact('student','bed_profile'));
        }
    }

    function reset_password(Request $request) {
        if (Auth::user()->accesslevel == env("REG_BE")) {
            $user = \App\User::where('idno', $request->idno)->first();
            $user->password = bcrypt($request->password);
            $user->is_first_login = 1;
            $user->update();
            \App\Http\Controllers\Accounting\SetReceiptController::log("Reset password of $request->idno.");
            return redirect(url('/bedregistrar', array('info', $request->idno)));
        }
    }

    function student_list() {
        if (Auth::user()->accesslevel == env("REG_BE") || Auth::user()->accesslevel == env('ADMISSION_BED')) {
            $students = \App\Status::where('academic_type', "BED")->where('status', env("ENROLLED"))->get();
            return view("reg_be.student_list", compact('students'));
        }
    }

    function updateinfo(Request $request) {
        if (Auth::user()->accesslevel == env("REG_BE")) {
            $updateuser = \App\User::where('idno', $request->referenceid)->first();
            $updateuser->firstname = $request->firstname;
            $updateuser->lastname = $request->lastname;
            $updateuser->middlename = $request->middlename;
            $updateuser->extensionname = $request->extensionname;
            $updateuser->status = $request->user_status;
            $updateuser->lrn = $request->lrn;
            $updateuser->is_foreign = $request->is_foreign;
            $updateuser->save();

            $addprofile = \App\BedProfile::where('idno', $request->referenceid)->first();
            if (count($addprofile) > 0) {
                $addprofile->date_of_birth = $request->date_of_birth;
                $addprofile->street = $request->street;
                $addprofile->barangay = $request->barangay;
                $addprofile->municipality = $request->municipality;
                $addprofile->province = $request->province;
                $addprofile->zip = $request->zip;
                $addprofile->tel_no = $request->tel_no;
                $addprofile->cell_no = $request->cell_no;
                $addprofile->save();
            } else {
                $addpro = new \App\BedProfile;
                $addpro->idno = $request->referenceid;
                $addpro->street = $request->street;
                $addpro->barangay = $request->barangay;
                $addpro->municipality = $request->municipality;
                $addpro->province = $request->province;
                $addpro->zip = $request->zip;
                $addpro->tel_no = $request->tel_no;
                $addpro->cell_no = $request->cell_no;
                $addpro->save();
            }
            \App\Http\Controllers\Admin\Logs::log("Update student information of $request->referenceid.");
            return redirect(url('/bedregistrar', array('info', $request->idno)));
        }
    }

    function sectioning() {
        if (Auth::user()->accesslevel == env("REG_BE")||Auth::user()->accesslevel == env("GUIDANCE_BED")) {
            return view("reg_be.sectioning");
        }
    }

}
