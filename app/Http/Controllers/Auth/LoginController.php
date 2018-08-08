<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $this->add999999();
        //$this->generatePassword();
        $this->middleware('guest')->except('logout');
    }
    
    public function username() {
        return 'idno';
    }
    
    public function login (Request $request) {
        
        $this->validateLogin($request);
        
        if (Auth::attempt(['idno' => $request->input('idno'), 'password' => $request->input('password'), 'status' => 1])) {
            return redirect()->intended('/');
        } else if (Auth::attempt(['idno' => $request->input('idno'), 'password' => $request->input('password'), 'status' => 0])) {
                Auth::logout();
                return view('auth.login')->withErrors("Access Denied - Account Deactivated");
        }
        return $this->sendFailedLoginResponse($request);
    }
    
    public function addadmin(){
        $check = DB::Select("Select * from users where idno = 'admin'");
        if(count($check)<=0){
            $password = bcrypt("nephilaadmin");
            DB::Select("Insert into users(idno,lastname,firstname,accesslevel,email, password) values('admin','admin','admin','100','admin@yahoo.com','$password')");
            DB::Select("Insert into users(idno,lastname,firstname,accesslevel,email, password) values('999999','none','none','0','999999@999999.com',NULL)");
        }
    }
    public function add999999(){
        $check = DB::Select("Select * from users where idno = 999999");
        if(count($check)<=0){
            $password = bcrypt("999999");
            DB::Select("Insert into users(idno,lastname,firstname,accesslevel,email, password) values('999999','none','none','0','999999@999999.com','$password')");
        }
    }
    public function generatePassword(){
        $prof = \App\User::where('accesslevel', 1)->where('is_first_login', 1)->get();
        foreach ($prof as $profs){
            $password = strtolower($profs->lastname);
            $pass = bcrypt($password);
            $profs->password = "$pass";
            $profs->save();
        }
    }
}
