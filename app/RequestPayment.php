<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class RequestPayment extends Model {

    //

    protected $append = ['full_name'];

    public function getFullNameAttribute() {
        $name = User::where('idno', $this->idno)->first();
        if ($name) {
            return $name->lastname . ", " . $name->firstname . " " . $name->middlename . " " . $name->extensionname;
        } else {
            $db_ext = DB::connection('mysql2');
            $name = $db_ext->table('pre_registrations')->where('idno', $this->idno)->first();

            return $name->lastname . ", " . $name->firstname . " " . $name->middlename . " " . $name->extensionname;
        }
    }

}
