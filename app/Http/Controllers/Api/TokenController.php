<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class TokenController extends Controller
{
    use AuthenticatesUsers;
    public function login(Request $request)
    {
        $validator = validator($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'required',
        ]);
        if($validator->fails())
        {
            return response($validator->errors(), 401) ;
        }

        $user = User::where('email', $request->email)->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            if (method_exists($this, 'hasTooManyLoginAttempts') &&
                $this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);
                return response([
                    'email' => ['Too Many Attempts, Please try again later']
                ], 429);
            }
            $this->incrementLoginAttempts($request);
            return response([
                'email' => ['The provided credentials are incorrect.'],
            ], 401);

        }

        return $user->createToken($request->device_name)->plainTextToken;
    }
}
