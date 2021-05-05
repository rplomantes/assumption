<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BulkOtherPayments extends Model
{
    //
    
    function getFullNameAttribute(){
        $name = \App\User::where('idno', $this->idno)->first();
        return $name->lastname.", ".$name->firstname;
    }
    
    function getLevelSection(){
        $status = \App\Status::where('idno',$this->idno)->first();
        return $status->level."-".$status->section." ".$status->strand;
    }
}
