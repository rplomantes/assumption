<?php

namespace App\Http\Controllers\BedRegistrar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;
use Session;
use Mail;

class SiblingsBenefits extends Controller
{
    //

    public function __construct() {
        $this->middleware('auth');
    }

    function siblings() {
        if (Auth::user()->accesslevel == env("REG_BE")) {
            $siblings = \App\DiscountCollection::distinct()->join('users','users.idno','=', 'discount_collections.idno')->where('discount_collections.subsidiary',"Family Council")->orderBy('lastname','asc')->orderBy('middlename', 'asc')->orderBy('firstname','asc')->where('discount_type', "Sibling Discount")->get(['discount_collections.idno','lastname','firstname','middlename']);
            
            return view('reg_be.siblings',compact('siblings'));
        }
    }

    function remove_siblings($idno) {
        if (Auth::user()->accesslevel == env("REG_BE")) {
            $siblings = \App\DiscountCollection::where('idno', $idno)->get();
            if(count($siblings)>0){
                foreach($siblings as $sibling){
                    $sibling->delete();
                }
            }
            
            return redirect(url('bedregistrar/siblings'));
        }
    }
}
