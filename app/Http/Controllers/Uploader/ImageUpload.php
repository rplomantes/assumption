<?php

namespace App\Http\Controllers\Uploader;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use PDF;
use App\Http\Controllers\Cashier\MainPayment;
use DB;
use File;

class ImageUpload extends Controller {

    //
    public function __construct() {
        $this->middleware('auth');
    }

    function view_upload($idno) {
        if (Auth::user()->accesslevel == env("REG_COLLEGE")) {
            $user = \App\User::where('idno', $idno)->first();
            return view('reg_college.image_upload.view', compact('idno', 'user'));
        }
    }

    function save_upload(Request $request) {
        
        $this->validate($request, [
            'image' => 'required|image|mimes:jpg,JPG,jpeg|max:2048',
        ]);

        $image = $request->file('image');

        $destinationPath = public_path('images/PICTURES/');

        $image->move($destinationPath, $request->idno.".jpg");
        \App\Http\Controllers\Admin\Logs::log("Image Uploaded for $request->idno"); 
        return back()->with('success', 'Image Upload successful');
    }

    function remove_image($idno) {
        if (Auth::user()->accesslevel == env("REG_COLLEGE")) {
        $destinationPath = public_path("images/PICTURES/$idno".".jpg");
        
        File::delete($destinationPath);
        return back()->with('warning', 'Image Remove successful');
        }
    }

}
