<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthenticateController extends Controller
{
	public function authenticate(Request $request)
	{
		$rules = [
			'username'    => 'required',
			'password' => 'required'
		];

		$this->validate($request, $rules);

		$credentials = $request->only('username', 'password');

		try {
			if(!$token = JWTAuth::attempt($credentials)) {
				return response()->json(['error' => 'Las credenciales no son correctas!'], 401);
			}
		} catch(JWTException $e) {
			return response()->json(['error' => 'Could not create token'], 500);
		}

		$user = $request->user();

		return response()->json(compact('token', 'user'));
	}
}
