<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Session;

class Logs extends Controller {

//
    public function __construct() {
        $this->middleware('auth');
    }

    function view_logs() {
//        $logs = \App\Log::orderBy('id', 'desc')->get();
        $logs = DB::Select("select * from `logs` where datetime > DATE_SUB(now(), INTERVAL 1 WEEK) order by `id` desc ");
        return view('admin.logs', compact('logs'));
    }

    public static function log($action) {
        $log = new \App\Log();
        $log->action = "$action";
        $log->idno = Auth::user()->idno;
        $log->datetime = date("Y-m-d H:i:s");
        $log->local_ip = $_SERVER['REMOTE_ADDR'];
        $log->public_ip = $_SERVER['REMOTE_ADDR'];
        $log->save();
    }

    function sms() {
        return view('admin.sms');
    }

}
