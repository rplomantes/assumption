<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FreshmenStudentList extends Model
{
    
    public function __construct($school_year) {
        $this->list = $this->listfreshmen($school_year);
    }
    
    function listfreshmen($school_year){ 
        $list2 = \App\CollegeLevel::distinct()->where('college_levels.school_year', $school_year)->where('college_levels.status', 3)->where('college_levels.level', "1st Year")->get(['idno', 'level', 'program_code']);
        return $list2;
    }
}
