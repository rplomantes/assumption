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
        $students = \App\User::where('academic_type','BED')->get();
        foreach($students as $student){
            $level = new \App\BedLevel;
            $level->idno = $student->idno;
            $level->school_year = '2017';
            $level->level = $student->level;
            $level->section=$student->section;
            $level->strand =$student->strand;
            $level->save();
            
            $status = new \App\Status;
            $status->idno = $student->idno;
            $status->school_year = '2017';
            $status->academic_type = $student->academic_type;
            $status->save();
                    
        }
    }
}
