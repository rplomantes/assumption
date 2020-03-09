<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CollegeLevel extends Model {

    //
    public function getFullNameAttribute() {
        $name = User::where('idno', $this->idno)->first();

        return $name->lastname . ", " . $name->firstname . " " . $name->middlename . " " . $name->extensionname;
    }

    public function getRomanLevel() {
        switch ($this->level) {
            case "1st Year":
                return "I";
                break;
            case "2nd Year":
                return "II";
                break;
            case "3rd Year":
                return "III";
                break;
            case "4th Year":
                return "IV";
                break;
            case "5th Year":
                return "V";
                break;
        }
    }

}
