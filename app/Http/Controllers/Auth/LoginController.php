<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
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

    public function authenticate(Request $request)
    {
        $credentials = $request->only('email', 'password');
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }
        if (Auth::validate($credentials))
        {
            $user = User::select('approved')->where('email', $request->email)->first();
            if (!$user->approved)
            {
                $this->incrementLoginAttempts($request);
                return redirect()->back()->withInput($request->only($this->username(), 'remember'))
                ->withErrors(['email' => 'Sorry, your account is not yet approved by the administrator.']);
            }
            Auth::attempt($credentials, $request->only('remember'));
        }
        $this->incrementLoginAttempts($request);
            return redirect()->back()->withInput($request->only($this->username(), 'remember'))
                ->withErrors(['email' => 'These credentials do not match our records.']);
    }
}
