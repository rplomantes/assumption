<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CtrAcademicProgram extends Model
{
    static function findCode($name){
        $code = "";
        $acadProg = CtrAcademicProgram::where('program_name',$name)->first();
        if($acadProg){
            $code = $acadProg->program_code;
        }
        
        return $code;
    }
}
