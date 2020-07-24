<?php

namespace App\Http\Controllers\RegistrarCollege\CurriculumManagement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Session;

class UpdateRooms extends Controller
{
    //
    public function __construct() {
        $this->middleware('auth');
    }

    function index() {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $rooms = \App\CtrRoom::whereRaw('id is not null')->orderBy('room','asc')->get();
            return view('reg_college.curriculum_management.list_of_rooms',compact('rooms'));
        }
    }

    function delete_room($id) {
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $room = \App\CtrRoom::where('id',$id)->first();
            $room->delete();
            $rooms = \App\CtrRoom::whereRaw('id is not null')->orderBy('room','asc')->get();
            
            Session::flash('message', "Room deleted!");
            return redirect('registrar_college/curriculum_management/update_rooms');
        }
    }
    
    function add_room(Request $request){
        if (Auth::user()->accesslevel == env('REG_COLLEGE')) {
            $add_room = New \App\CtrRoom;
            $add_room->room = $request->room;
            $add_room->is_no_conflict = $request->is_no_conflict;
            $add_room->save();
            
            Session::flash('message', "Room added!");
            return redirect('registrar_college/curriculum_management/update_rooms');
        }
    }
}
