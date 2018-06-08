<?php

namespace App\Http\Controllers\Accounting\Ajax;


use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
Use Illuminate\Support\Facades\DB;
use Request;
use PDF;
class GetPlan extends Controller
{
    function plan(){
        $department = Input::get('department');
        return view('accounting.ajax.getplan',compact('department'));
    }
    
    //
    function print_plan($department){
        $pdf = PDF::loadView('accounting.ajax.print_plan', compact('department'));
        $pdf->setPaper('legal','landscape');
        return $pdf->stream();
    }
}
