<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class AuthenticateController extends Controller
{

    use AuthenticatesUsers;

	public function authenticate(Request $request)
	{
		$rules = [
			'username'    => 'required',
			'password' => 'required'
		];

        $this->validate($request, $rules);

        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return response()->json(['error' => 'Demasiados intentos espere...!'], 429);
            //return $this->sendLockoutResponse($request);
        }

		$credentials = $request->only('username', 'password');

		try {
			if(!$token = JWTAuth::attempt($credentials)) {
                $this->incrementLoginAttempts($request);
				return response()->json(['error' => 'Las credenciales no son correctas!'], 401);
			}
		} catch(JWTException $e) {
			return response()->json(['error' => 'Could not create token'], 500);
		}

		$user = $request->user();

		return response()->json(compact('token', 'user'));
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
}
