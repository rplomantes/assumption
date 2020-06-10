<?php

namespace App\Http\Controllers\BedRegistrar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;
use PDF;

class RequestForm extends Controller {

    public function __construct() {
        $this->middleware('auth');
    }
    
    function index() {
        $forms_request = \App\FormRequest::orderBy('created_at','desc')->limit(100)->get();
        
        return view('reg_be.request_form.index',compact('forms_request'));
    }
    
    function AjaxGetForm(){
        if (\Illuminate\Support\Facades\Request::ajax()) {
            $reference_id = \Illuminate\Support\Facades\Input::get('reference_id');
            $form_requests = \App\FormRequest::orderBy('reference_id',$reference_id)->get();
            return view('reg_be.request_form.getforms', compact('form_requests','reference_id'));
        }
    }
    
    function updateOR(Request $request){
            $form_requests = \App\FormRequest::where('reference_id',$request->reference_id)->first();
            $form_requests->or_number=$request->or_number;
            $form_requests->status=1;
            $form_requests->save();
            
            return redirect('/bedregistrar/request_form');
    }
    
    function tag_as_claimed($reference_id){
            $form_requests = \App\FormRequest::where('reference_id',$reference_id)->first();
            $form_requests->claim_date = date('Y-m-d');
            $form_requests->status=3;
            $form_requests->save();
            
            return redirect('/bedregistrar/request_form');
    }
    
    function tag_as_for_claiming(Request $request){
            $form_requests = \App\FormRequest::where('reference_id',$request->reference_id)->first();
            $form_requests->claiming_date = $request->date_for_claiming;
            $form_requests->status=2;
            $form_requests->save();
            
            return redirect('/bedregistrar/request_form');
    }
    
}
