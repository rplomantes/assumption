<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AcademicRole extends Model
{
    
    protected $append  = ['full_name'];
    
    public function getFullNameAttribute(){
        $name = User::where('idno', $this->idno)->first();
        
        return $name->lastname.", ".$name->firstname." ".$name->middlename." ".$name->extensionname;
    }
    
    function user(){
        return $this->belongsTo(User::class, 'idno', 'idno');
    }
    
    function subject_name(){
        return $this->belongsTo(SubjectComponent::class,'subject_code','subject_code');
    }
}
