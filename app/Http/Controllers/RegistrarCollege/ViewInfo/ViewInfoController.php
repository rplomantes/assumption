<?php

namespace App\Http\Controllers\RegistrarCollege\ViewInfo;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;

class ViewInfoController extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function view_info($idno) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $user = \App\User::where('idno', $idno)->first();
            $info = \App\StudentInfo::where('idno', $idno)->first();
            return view('reg_college.view_info.view', compact('idno', 'user', 'info'));
        }
    }

    function save_info(Request $request) {
        $validate = $request->validate($request, [
            'firstname' => 'required',
            'lastname' => 'required',
            'municipality' => 'required',
            'province' => 'required',
            'birthdate' => 'required',
            'gender' => 'required',
            'email' => 'required',
        ]);

        if ($validate) {
            DB::beginTransaction();
            $this->updateStatus($request);
            $this->updateInfo($request);
            $this->updateUser($request);
            DB::Commit();
        }
    }

}
