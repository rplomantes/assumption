<?php

namespace App\Http\Controllers\RegistrarCollege\Notifications;

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

    function portal_notifications($id=null){
        if(Auth::user()->accesslevel == env('REG_COLLEGE')){
            if($id != null){
                $notif = \App\CollegeNotifications::where('id',$id)->first();
            }
            return view('reg_college.portal_notifications',compact('notif','id'));
        }
    }
    
    function post_notifications(Request $request){
        if(Auth::user()->accesslevel == env('REG_COLLEGE')){
            if($request->id == null){
            $add_announcement = new \App\CollegeNotifications;
            $add_announcement->department = $request->department;
            $add_announcement->notification = $request->notifications_content;
            $add_announcement->idno=Auth::user()->idno;
            $add_announcement->save();
            \App\Http\Controllers\Admin\Logs::log("Notification Posted!");
            Session::flash('announcement','Notification Posted!');
            }else{
            $add_announcement = \App\CollegeNotifications::where('id',$request->id)->first();
            $add_announcement->department = $request->department;
            $add_announcement->notification = $request->notifications_content;
            $add_announcement->idno=Auth::user()->idno;
            $add_announcement->save();
            \App\Http\Controllers\Admin\Logs::log("Notification Updated for $request->id");
            Session::flash('announcement','Notification Updated!');
            }
            return redirect(url('/registrar_college/portal_notifications'));
        }
    }
}
