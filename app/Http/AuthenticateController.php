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

            $seconds = $this->limiter()->availableIn(
                $this->throttleKey($request)
            );

            return response()->json(['error' => 'Demasiados intentos. Inténtalo de nuevo en '.$seconds. ' segundos... ' ], 429);

        }

		$credentials = $request->only('username', 'password', 'blocked');

		try {
			if(!$token = JWTAuth::attempt($credentials)) {
                $this->incrementLoginAttempts($request);
				return response()->json(['error' => 'Las credenciales no son correctas!'], 401);
			}
		} catch(JWTException $e) {
			return response()->json(['error' => 'No se ha podido crear el token'], 500);
		}

		$user = $request->user();

		return response()->json(compact('token', 'user'));
    }


}
