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
            $siblings = \App\DiscountCollection::distinct()->join('users','users.idno','=', 'discount_collections.idno')->where('discount_collections.subsidiary',"Student Development Fee")->orderBy('lastname','asc')->orderBy('middlename', 'asc')->orderBy('firstname','asc')->where('discount_type', "Sibling Discount")->get(['discount_collections.idno','lastname','firstname','middlename','discount_collections.discount_amount']);
            
            return view('reg_be.siblings',compact('siblings'));
        }
    }

    function benefits() {
        if (Auth::user()->accesslevel == env("REG_BE")) {
            $benefits = \App\DiscountCollection::distinct()->join('users','users.idno','=', 'discount_collections.idno')->where('discount_collections.subsidiary',"Student Development Fee")->orderBy('lastname','asc')->orderBy('middlename', 'asc')->orderBy('firstname','asc')->where('discount_type', "Benefit Discount")->get(['discount_collections.idno','lastname','firstname','middlename']);
            
            return view('reg_be.benefits',compact('benefits'));
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

    public static function remove_benefits($idno) {
        if (Auth::user()->accesslevel == env("REG_BE") || Auth::user()->accesslevel == env("ACCTNG_HEAD") || Auth::user()->accesslevel == env("ACCTNG_STAFF")) {
            $siblings = \App\DiscountCollection::where('idno', $idno)->get();
            if(count($siblings)>0){
                foreach($siblings as $sibling){
                    $sibling->delete();
                }
            }
            $siblings = \App\PartialStudentDiscount::where('idno', $idno)->get();
            if(count($siblings)>0){
                foreach($siblings as $sibling){
                    $sibling->delete();
                }
            }
            
            return redirect(url('bedregistrar/benefits'));
        }
    }
}
