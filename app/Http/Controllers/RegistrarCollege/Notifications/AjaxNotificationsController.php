<?php

namespace App\Http\Controllers\RegistrarCollege\Notifications;

use App\Http\Controllers\Controller;
use Auth;
use Session;
use Request;
use Illuminate\Support\Facades\Input;

class AjaxNotificationsController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }

    function set_status(){
        if (Request::ajax()) {
            $id = Input::get("id");
            $notification = \App\CollegeNotifications::where('id',$id)->first();
            if($notification->is_active == 1){
                $notification->is_active = 0;
                $notification->save();
            }
            else{
                $notification->is_active = 1;
                $notification->save();
            }
            $notifications = \App\CollegeNotifications::orderBy('created_at', 'desc')->get();
            return view('reg_college.list_notifications',compact('notifications'));
        }
    }
}
