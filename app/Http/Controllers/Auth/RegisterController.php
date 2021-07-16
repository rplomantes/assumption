<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller {

use RegistersUsers;

    protected $redirectTo = '/';

    public function __construct() {
        $this->middleware('maker');
    }
    
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    protected function validator(array $data) {
        return Validator::make($data, [
                    'idno' => 'required|unique:users',
                    'firstname' => 'required|string|max:255',
                    'lastname' => 'required|string|max:255',
                    'password' => 'required|string|min:6|confirmed',
        ]);
    }

    protected function create(array $data) {


        $user = User::create([
                    'idno' => $data['idno'],
                    'lastname' => $data['lastname'],
                    'firstname' => $data['firstname'],
                    'middlename' => $data['middlename'],
                    'extensionname' => $data['extensionname'],
                    'accesslevel' => $data['accesslevel'],
                    'email' => $data['email'],
                    'password' => bcrypt($data['password']),
        ]);

        \App\ReferenceId::create([
                    'idno' => $data['idno'],
        ]);
        
        return $user;
    }
    
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

//        $this->guard()->login($user);

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }
    
    protected function registered(Request $request, $user)
    {
        //
    }
}
