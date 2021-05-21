<?php

namespace App\Http\Controllers\BedRegistrar\Notifications;

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

    function portal_notifications($department){
        if(Auth::user()->accesslevel == env('REG_BE') || Auth::user()->accesslevel == env('ACCTNG_HEAD')){
            return view('reg_be.portal_notifications',compact('department'));
        }
    }
    
    function post_notifications(Request $request){
//        return $request;
            $add_announcement = new \App\Notification;
            $add_announcement->department = $request->department;
            $add_announcement->notification = $request->notifications_content;
            $add_announcement->idno=Auth::user()->idno;
            $add_announcement->save();
            \App\Http\Controllers\Admin\Logs::log("Post a notification");
            Session::flash('announcement','Notification Posted!');
            return redirect(url("/bed_portal_notifications/$request->depname"));
    }
}
