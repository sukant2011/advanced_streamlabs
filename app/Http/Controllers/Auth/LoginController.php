<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * This is used to login or register using sociallogin
     *
     */

    protected function _registerOrLoginUser($data){
        try {
            $user = User::where('email',$data->email)->first();
            if(!$user){
                $user = new User();
                $user->name = $data->name;
                $user->email = $data->email;
                $user->provider_id = $data->id;
                $user->avatar = $data->avatar;
                $user->save();
            }
            Auth::login($user);
        } catch (Exception $e) {
            dd($e->getMessage());
        }
    }

    //Github Login
    public function redirectToGithub(){
        return Socialite::driver('github')->stateless()->redirect();
    }
    
    //github callback  
    public function handleGithubCallback(){
        $user = Socialite::driver('github')->stateless()->user();
        
        $this->_registerorLoginUser($user);
        return redirect()->route('home');
    }
}
