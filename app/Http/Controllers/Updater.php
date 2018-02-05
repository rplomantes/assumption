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
        
        $levels = \App\BedLevel::get();
        foreach($levels as $level){
            $update = \App\Status::where('idno',$level->idno)->first();
            $update->level = $level->level;
            $update->section = $level->section;
            $update->strand=$level->strand;
            $update->update();
        }
        
    }
}
