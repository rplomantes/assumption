<?php

namespace App\Http\Controllers\Bookstore;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;
use Session;
use Mail;

class ViewOrderedBooks extends Controller
{
    //

    public function __construct() {
        $this->middleware('auth');
    }

    function student_list() {
        if (Auth::user()->accesslevel == env("BOOKSTORE")) {
            $students = \App\Status::where('academic_type', "BED")->where('status', env("ENROLLED"))->get();
            return view("bookstore.student_list", compact('students'));
        }
    }
}
