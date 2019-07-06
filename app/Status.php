<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    //
    protected $append  = ['full_name'];
    
    public function getFullNameAttribute(){
        $name = User::where('idno', $this->idno)->first();
        
        return $name->lastname.", ".$name->firstname." ".$name->middlename." ".$name->extensionname;
    }
    
    public function bedLevel(){
        return $this->hasMany(BedLevel::class,'idno','idno')->where('school_year', $this->school_year)->where('period', $this->period);
    }
    
    public function collegeLevel(){
        return $this->hasMany(CollegeLevel::class,'idno','idno')->where('school_year', $this->school_year)->where('period', $this->period);
    }
    
    
    public function user(){
        return $this->hasOne('App\User');
    }
}
