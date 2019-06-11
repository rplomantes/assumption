<?php

namespace App\Http\Controllers\GuidanceBed;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Support\Facades\Input;
use DB;
use Session;

class PromotionsController extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index($idno) {
        if (Auth::user()->accesslevel == env('GUIDANCE_BED') || Auth::user()->accesslevel == env('REG_BE')) {
            $user = \App\User::where('idno', $idno)->first();
            $status = \App\Status::where('idno', $idno)->first();
            $bed_info = \App\BedProfile::where('idno', $idno)->first();
            $promotion = \App\Promotion::where('idno', $idno)->first();
            if (count($promotion) == 0) {
                $addpar = new \App\Promotion;
                $addpar->idno = $idno;
                $addpar->level = $status->level;
                $addpar->strand = $status->strand;
                $addpar->section = $status->section;
                $addpar->save();
                $promotion = \App\Promotion::where('idno', $idno)->first();
            }

            return view('guidance_bed.promotions', compact('idno', 'status', 'bed_info', 'user', 'promotion'));
        }
    }

    function update_promotions(Request $request) {
        if (Auth::user()->accesslevel == env('GUIDANCE_BED') || Auth::user()->accesslevel == env('REG_BE')) {
            $level = $request->level;
            $idno = $request->idno;
            $strand = $request->strand;

            $update_promotions = \App\Promotion::where('idno', $idno)->first();
            if (count($update_promotions) > 0) {
                $update_promotions->level = $level;
                if ($request->level == "Grade 11" || $request->level == "Grade 12") {
                    $update_promotions->strand = $request->strand;
                } else {
                    $update_promotions->strand = NULL;
                }
                if ($level == "Pre-Kinder" || $level == "Kinder") {
                    $update_promotions->section = "A";
                } else {
                    if ($request->level == "Grade 12") {
                        
                    } else {
                        $update_promotions->section = 1;
                    }
                }
                $update_promotions->save();

                Session::flash('message', 'Promotions Updated!');
                \App\Http\Controllers\Admin\Logs::log("Update promotions of $idno to $level");
            }
            return redirect("/guidance_bed/promotions/$idno");
        }
    }

}
