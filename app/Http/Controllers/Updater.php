<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade;

class Updater extends Controller
{
    //
    
    function updateBedLevel(){
        
        $levels = \App\Status::where('academic_type','BED')->get();
        foreach($levels as $level){
            $add = new \App\BedLevel;
            $add->idno = $level->idno;
            $add->level = $level->level;
            $add->strand = $level->strand;
            $add->track = $level->track;
            $add->section = $level->section;
            $add->status =$level->status;
            $add->department = $level->department;
            $add->school_year = $level->school_year;
            $add->period = $level->period;
            $add->save();
            
        
    }
}
}
