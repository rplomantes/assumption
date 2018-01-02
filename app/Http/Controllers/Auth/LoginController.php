<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->addadmin();
        $this->middleware('guest')->except('logout');
    }
    
    public function username() {
        return 'idno';
    }
    
    public function addadmin(){
        $check = DB::Select("Select * from users where idno = 'admin'");
        if(count($check)<=0){
            $password = bcrypt("nephilaadmin");
            DB::Select("Insert into users(idno,lastname,firstname,accesslevel,email, password) values('admin','admin','admin','100','admin@yahoo.com','$password')");
        }
    }
}
