<?php

namespace App\Http\Controllers\BedRegistrar;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ExternalForm extends Controller {

    //

    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        $external_forms = \App\ExternalForm::all();
        return view('reg_be.external_form.index',compact('external_forms'));
    }
    
    function update(Request $request) {
        
        $form = $request->form;
        $form_link = $request->form_link;
        
        $updates = \App\ExternalForm::all();

        foreach ($updates as $update) {
            $update->delete();
        }

        for ($i = 0; $i < 20; $i++) {
            if (isset($form[$i])) {
                
                $add = new \App\ExternalForm;
                $add->form_name = $form[$i];
                $add->form_link = $form_link[$i];
                $add->save();
            }
        }
        return redirect(url('bedregistrar/external_form'));
    }

}
