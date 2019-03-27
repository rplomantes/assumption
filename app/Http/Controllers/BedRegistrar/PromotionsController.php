<?php

namespace App\Http\Controllers\BedRegistrar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Session;

class PromotionsController extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function batch_promotions() {
        if (Auth::user()->accesslevel == env("REG_BE")) {
            return view('reg_be.promotions', compact('levels'));
        }
    }

    function update_promotions(Request $request) {
        if (Auth::user()->accesslevel == env("REG_BE")) {
            DB::beginTransaction();
            foreach ($request->post as $idno) {
                $checkpromotions = \App\Promotion::where('idno', $idno)->first();
                if (count($checkpromotions) > 0) {
                    $checkpromotions->level = $request->promote_level;
                    if ($request->promote_level == "Grade 11" || $request->promote_level == "Grade 12") {
                        $checkpromotions->strand = $request->promote_strand;
                    } else {
                        $checkpromotions->strand = NULL;
                    }
                    if($request->promot_level == "Pre-Kinder" || $request->promote_level == "Kinder"){
                        $checkpromotions->section = "A";
                    }else{
                        $checkpromotions->section = 1;
                    }
                    $checkpromotions->save();
                } else {
                    $new_promotion = new \App\Promotion;
                    $new_promotion->idno = $idno;
                    $new_promotion->level = $request->promote_level;
                    $new_promotion->section = 1;
                    if ($request->promote_level == "Grade 11" || $request->promote_level == "Grade 12") {
                        $new_promotion->strand = $request->promote_strand;
                    }
                    $new_promotion->save();
                }
            }
            DB::commit();
            if ($request->promote_level == "Grade 11" || $request->promote_level == "Grade 12") {
            \App\Http\Controllers\Admin\Logs::log("Update batch promotions of students to $request->level to $request->promote_level and $request->strand to $request->promote_strand");
            Session::flash('message', "Update batch promotions of students to $request->level to $request->promote_level and $request->strand to $request->promote_strand");
            }else{                
            \App\Http\Controllers\Admin\Logs::log("Update batch promotions of students to $request->level to $request->promote_level");
            Session::flash('message', "Update batch promotions of students to $request->level to $request->promote_level");
            }
            return redirect('bedregistrar/batch_promotions');
        }
    }

    function individual_promotions() {
        if (Auth::user()->accesslevel == env("REG_BE")) {
            return view('reg_be.individual_promotions');
        }
    }

}
