<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;
use Session;

class Logs extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth');
    }
    function view_logs() {
        $logs = \App\Log::orderBy('id', 'desc')->get();
        return view('admin.logs', compact('logs'));
    }
    public static function log($action){
        $log = new \App\Log();
        $log->action = "$action";
        $log->idno = Auth::user()->idno;
        $log->datetime = date("Y-m-d H:i:s");
        $log->save();
    }
}
