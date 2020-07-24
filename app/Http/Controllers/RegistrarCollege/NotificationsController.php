<?php

namespace App\Http\Controllers\RegistrarCollege;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Session;

class NotificationsController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    function portal_notifications(){
        if(Auth::user()->accesslevel == env('REG_COLLEGE')){
            return view('reg_college.portal_notifications');
        }
    }
    
    function post_notifications(Request $request){
//        return $request;
        if(Auth::user()->accesslevel == env('REG_COLLEGE')){
            $add_announcement = new \App\CollegeNotifications;
            $add_announcement->department = $request->department;
            $add_announcement->notification = $request->notifications_content;
            $add_announcement->idno=Auth::user()->idno;
            $add_announcement->save();
            Session::flash('announcement','Notification Posted!');
            return redirect(url('/registrar_college/portal_notifications'));
        }
    }
}
