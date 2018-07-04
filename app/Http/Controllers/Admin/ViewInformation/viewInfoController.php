<?php

namespace App\Http\Controllers\Admin\ViewInformation;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;

class viewInfoController extends Controller
{
    //
    
     public function __construct()
    {
        $this->middleware('auth');
    }
    
    function index($idno){
        if(Auth::user()->accesslevel == env("ADMIN")){
            $user = \App\User::where('idno', $idno)->first();
            
            return view('admin.view_information.view_info', compact('idno', 'user'));
        }
    }
    
    function resetpassword(Request $request) {
        if(Auth::user()->accesslevel==env("ADMIN")){
            $user=  \App\User::where('idno',$request->idno)->first();
            $user->password = bcrypt($request->password);
            $user->is_first_login=1;
            $user->update();
            return redirect(url('/admin',array('view_information',$request->idno)));
        }
    }
    
    function update_info(Request $request) {
        if(Auth::user()->accesslevel == env("ADMIN")){
            $validate = $request->validate([
                'firstname' => 'required',
                'lastname' => 'required',
                'email' => 'required',
            ]);
            
            if($validate){
                DB::beginTransaction(); 
                $update = \App\User::where('idno', $request->idno)->first();
                $update->firstname = $request->firstname;
                $update->lastname = $request->lastname;
                $update->middlename = $request->middlename;
                $update->extensionname = $request->extensionname;
                $update->email = $request->email;
                $update->accesslevel = $request->accesslevel;
                $update->save();
                DB::Commit();
            }
            return redirect(url('/admin',array('view_information',$request->idno)));
        }
    }
}
