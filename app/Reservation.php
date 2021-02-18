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
    public function getStatus(){
        $status = \App\Status::where('idno',  $this->idno)->first();
        if(count($status)>0){
            switch ($status->status){
                case "0":
                    return "Not Yet Enrolled";
                    break;
                case "2":
                    return "Assessed";
                    break;
                case "3":
                    return "Enrolled";
                    break;
                    
            }
        }else{
            return "";
        }
    }
}
