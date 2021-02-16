<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    //
    public function getRemarks(){
        $remarks = \App\Payment::where('reference_id',  $this->reference_id)->first();
        if(count($remarks)>0){
        return $remarks->remarks;
        }else{
            return "";
        }
    }
}
