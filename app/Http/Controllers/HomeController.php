<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $accesslevel = \Auth::user()->accesslevel;
        switch($accesslevel){
            case 1:
                return view('dean.index');
                break;
            case 10:
                return view('dean.index');
                break;
            case 11:
                return view('mesil.index');
                break;
            case 12:
                return view('msbmw.index');
                break;
            case 20:
                return view('reg_college.index');
                break;
            case 21:
                
                return view('reg_be.index',compact('school_year'));
                break;
            case 30:
                return view('accounting.index');
                break;
            case 31:
                return view('accounting.index');
                break;
            case 40:
                return view('cashier.index');
                break;
            case 100:
                return view('admin.index');
                break;
        }
    }
}
