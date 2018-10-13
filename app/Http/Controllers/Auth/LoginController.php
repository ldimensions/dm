<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth,Session;
use Illuminate\Contracts\Auth\Guard;
use App\User;

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
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request){
        $this->validate($request, [
            'email' => 'required|email', 'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt(
                    array(
                        'email'=>$request->email,
                        'password'=>$request->password,
                        'is_deleted'=> '0'
                    )
                )
            )
        {
            $user       =   Auth::user();
            if(Auth::user()->status == 1){
                Session::flush();
                Auth::guard()->logout();
                return redirect('/login')
                    ->withInput($request->only('email'))
                    ->withErrors([
                        'email' => 'Account is disabled. Please contact Administrator',
                    ]);
            }

            if($user->hasRole('Admin')){
                return redirect('/admin/dashboard');
            }if($user->hasRole('Editor')){
                return redirect('/editor/dashboard');
            }else if($user->hasRole('User')){
                return redirect('/');
            }
        }
        return redirect('/login')
                    ->withInput($request->only('email'))
                    ->withErrors(['email'=>'Login Invalid']);
    }

    public function logout(Request $request) {        
        Auth::logout();
        return redirect('/');
    }    
}
