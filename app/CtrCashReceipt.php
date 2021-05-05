<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CtrCashReceipt extends Model
{
    //
    
    public function accountingName(){
        $name = \App\ChartOfAccount::where('accounting_code', $this->accounting_code)->first();
        return $name->accounting_name;
    }
}
