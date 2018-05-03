<?php

namespace App\Http\Controllers\RegistrarCollege\StudentRecord;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

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
}
