<?php

namespace App\Http\Controllers\Bookstore;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class BooksPricing extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    function booksFees(){
        if (Auth::user()->accesslevel == env('BOOKSTORE')) {
            return view('bookstore.pricing');
        }
    }
}
