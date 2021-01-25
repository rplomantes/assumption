<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CollegeAboutYou extends Model
{
    //
    protected $append  = ['full_name'];
    
    public function getFullNameAttribute(){
        $name = User::where('idno', $this->idno)->first();
        
        return $name->lastname.", ".$name->firstname." ".$name->middlename." ".$name->extensionname;
    }
}
