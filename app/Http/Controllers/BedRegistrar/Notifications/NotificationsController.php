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

    function portal_notifications(){
        if(Auth::user()->accesslevel == env('REG_BE')){
            return view('reg_be.portal_notifications');
        }
    }
    
    function post_notifications(Request $request){
//        return $request;
        if(Auth::user()->accesslevel == env('REG_BE')){
            $add_announcement = new \App\Notification;
            $add_announcement->department = $request->department;
            $add_announcement->notification = $request->notifications_content;
            $add_announcement->idno=Auth::user()->idno;
            $add_announcement->save();
            Session::flash('announcement','Notification Posted!');
            return redirect(url('/bedregistrar/portal_notifications'));
        }
    }
}