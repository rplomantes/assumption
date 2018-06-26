<?php

namespace App\Http\Controllers\RegistrarCollege\StudentRecord;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;

class StudentRecordController extends Controller
{
    //
    function view_record ($idno){
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $user = \App\User::where('idno', $idno)->first();
            $info = \App\StudentInfo::where('idno', $idno)->first();
            $status = \App\Status::where('idno', $idno)->first();
            return view('reg_college.view_record.view', compact('idno', 'user', 'info', 'status'));
        }
    }
    function view_transcript($idno){
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $user = \App\User::where('idno', $idno)->first();
            $info = \App\StudentInfo::where('idno', $idno)->first();
            $status = \App\Status::where('idno', $idno)->first();
            return view('reg_college.view_record.transcript', compact('idno', 'user', 'info', 'status'));
        }
    }
    function finalize_transcript($idno){
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $user = \App\User::where('idno', $idno)->first();
            $level = \App\CollegeLevel::where('idno', $idno)->first();
            $info = \App\StudentInfo::where('idno', $idno)->first();
            $status = \App\Status::where('idno', $idno)->first();
            return view('reg_college.view_record.finalize_transcript', compact('idno', 'user', 'level' , 'info', 'status'));
        }
    }    
    
    function print_transcript(Request $request){
        if (Auth::user()->accesslevel == env('REG_COLLEGE')){
            $idno = $request->idno;
            $user = \App\User::where('idno', $idno)->first();     
            $level = \App\CollegeLevel::where('idno', $idno)->first();
            $info = \App\StudentInfo::where('idno', $idno)->first();
            $pdf = PDF::loadView('reg_college.view_record.print_transcript', compact('idno','user','info','status', 'level'));
            $pdf->setPaper('letter', 'portrait');
//            return $request;
            return $pdf->stream("student_list_.pdf");
        }
    }
}
